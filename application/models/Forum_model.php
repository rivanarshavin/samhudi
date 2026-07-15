<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forum_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_forums($user_id = null, $filter = 'all', $search = '')
    {
        $this->db->select('
            forums.*, 
            users.full_name AS author_name, 
            users.avatar AS author_avatar,
            (SELECT COUNT(*) FROM forum_likes WHERE forum_likes.forum_id = forums.id) AS likes_count,
            (SELECT COUNT(*) FROM forum_comments WHERE forum_comments.forum_id = forums.id) AS comments_count
        ');

        if ($user_id) {
            $this->db->select('
                IF((SELECT COUNT(*) FROM forum_likes WHERE forum_likes.forum_id = forums.id AND forum_likes.user_id = ' . intval($user_id) . ') > 0, 1, 0) AS liked_by_user,
                IF((SELECT COUNT(*) FROM forum_saves WHERE forum_saves.forum_id = forums.id AND forum_saves.user_id = ' . intval($user_id) . ') > 0, 1, 0) AS saved_by_user
            ');
        } else {
            $this->db->select('0 AS liked_by_user, 0 AS saved_by_user');
        }

        $this->db->from('forums');
        $this->db->join('users', 'users.id = forums.created_by', 'left');

        // Apply filters
        if ($filter === 'populer') {
            $this->db->order_by('likes_count', 'DESC');
        } elseif ($filter === 'saved' && $user_id) {
            $this->db->join('forum_saves', 'forum_saves.forum_id = forums.id');
            $this->db->where('forum_saves.user_id', $user_id);
            $this->db->order_by('forum_saves.created_at', 'DESC');
        } elseif ($filter === 'my_posts' && $user_id) {
            $this->db->where('forums.created_by', $user_id);
            $this->db->order_by('forums.created_at', 'DESC');
        } else {
            // Default: 'terbaru' or 'all'
            $this->db->order_by('forums.created_at', 'DESC');
        }

        // Apply search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('forums.title', $search);
            $this->db->or_like('forums.content', $search);
            $this->db->group_end();
        }

        return $this->db->get()->result();
    }

    public function get_popular_weekly()
    {
        $this->db->select('
            forums.id, 
            forums.title, 
            (SELECT COUNT(*) FROM forum_likes WHERE forum_likes.forum_id = forums.id) AS likes_count,
            (SELECT COUNT(*) FROM forum_comments WHERE forum_comments.forum_id = forums.id) AS comments_count
        ');
        $this->db->from('forums');
        $this->db->order_by('likes_count', 'DESC');
        $this->db->limit(5);
        return $this->db->get()->result();
    }

    public function get_forum($id, $user_id = null)
    {
        $this->db->select('
            forums.*, 
            users.full_name AS author_name, 
            users.avatar AS author_avatar,
            (SELECT COUNT(*) FROM forum_likes WHERE forum_likes.forum_id = forums.id) AS likes_count,
            (SELECT COUNT(*) FROM forum_comments WHERE forum_comments.forum_id = forums.id) AS comments_count
        ');

        if ($user_id) {
            $this->db->select('
                IF((SELECT COUNT(*) FROM forum_likes WHERE forum_likes.forum_id = forums.id AND forum_likes.user_id = ' . intval($user_id) . ') > 0, 1, 0) AS liked_by_user,
                IF((SELECT COUNT(*) FROM forum_saves WHERE forum_saves.forum_id = forums.id AND forum_saves.user_id = ' . intval($user_id) . ') > 0, 1, 0) AS saved_by_user
            ');
        } else {
            $this->db->select('0 AS liked_by_user, 0 AS saved_by_user');
        }

        $this->db->from('forums');
        $this->db->join('users', 'users.id = forums.created_by', 'left');
        $this->db->where('forums.id', $id);
        return $this->db->get()->row();
    }

    public function create_forum($data)
    {
        return $this->db->insert('forums', $data);
    }

    public function toggle_like($forum_id, $user_id)
    {
        $check = $this->db->get_where('forum_likes', ['forum_id' => $forum_id, 'user_id' => $user_id])->row();
        if ($check) {
            $this->db->delete('forum_likes', ['forum_id' => $forum_id, 'user_id' => $user_id]);
            return ['status' => 'unliked', 'count' => $this->get_likes_count($forum_id)];
        } else {
            $this->db->insert('forum_likes', ['forum_id' => $forum_id, 'user_id' => $user_id]);
            return ['status' => 'liked', 'count' => $this->get_likes_count($forum_id)];
        }
    }

    public function toggle_save($forum_id, $user_id)
    {
        $check = $this->db->get_where('forum_saves', ['forum_id' => $forum_id, 'user_id' => $user_id])->row();
        if ($check) {
            $this->db->delete('forum_saves', ['forum_id' => $forum_id, 'user_id' => $user_id]);
            return ['status' => 'unsaved'];
        } else {
            $this->db->insert('forum_saves', ['forum_id' => $forum_id, 'user_id' => $user_id]);
            return ['status' => 'saved'];
        }
    }

    private function get_likes_count($forum_id)
    {
        return $this->db->where('forum_id', $forum_id)->count_all_results('forum_likes');
    }

    public function get_comments($forum_id)
    {
        $this->db->select('forum_comments.*, users.full_name AS author_name, users.avatar AS author_avatar');
        $this->db->from('forum_comments');
        $this->db->join('users', 'users.id = forum_comments.user_id', 'left');
        $this->db->where('forum_comments.forum_id', $forum_id);
        $this->db->order_by('forum_comments.created_at', 'ASC');
        $rows = $this->db->get()->result();

        $comments = [];
        $children = [];

        foreach ($rows as $row) {
            if ($row->parent_id === null) {
                $comments[$row->id] = $row;
                $comments[$row->id]->replies = [];
            } else {
                $children[] = $row;
            }
        }

        foreach ($children as $child) {
            if (isset($comments[$child->parent_id])) {
                $comments[$child->parent_id]->replies[] = $child;
            }
        }

        return array_values($comments);
    }

    public function create_comment($data)
    {
        return $this->db->insert('forum_comments', $data);
    }

    // --- Chat System ---
    public function get_chat_contacts($user_id)
    {
        // Get all members other than the logged-in user
        $this->db->select('id, full_name, avatar, username');
        $this->db->from('users');
        $this->db->where('id !=', $user_id);
        $users = $this->db->get()->result();

        foreach ($users as &$u) {
            // Get last message
            $this->db->select('message, created_at');
            $this->db->from('messages');
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('sender_id', $user_id);
            $this->db->where('receiver_id', $u->id);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where('sender_id', $u->id);
            $this->db->where('receiver_id', $user_id);
            $this->db->group_end();
            $this->db->group_end();
            $this->db->order_by('created_at', 'DESC');
            $this->db->limit(1);
            $last_msg = $this->db->get()->row();

            $u->last_message = $last_msg ? $last_msg->message : '';
            $u->last_time = $last_msg ? date('H.i', strtotime($last_msg->created_at)) : '';

            // Get unread count
            $this->db->from('messages');
            $this->db->where('sender_id', $u->id);
            $this->db->where('receiver_id', $user_id);
            $this->db->where('is_read', 0);
            $u->unread_count = $this->db->count_all_results();
        }

        return $users;
    }

    public function get_chat_messages($user_id, $other_id)
    {
        $this->db->select('messages.*, sender.full_name AS sender_name, sender.avatar AS sender_avatar');
        $this->db->from('messages');
        $this->db->join('users AS sender', 'sender.id = messages.sender_id');
        $this->db->group_start();
        $this->db->group_start();
        $this->db->where('sender_id', $user_id);
        $this->db->where('receiver_id', $other_id);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('sender_id', $other_id);
        $this->db->where('receiver_id', $user_id);
        $this->db->group_end();
        $this->db->group_end();
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get()->result();
    }

    public function send_chat_message($sender_id, $receiver_id, $message)
    {
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message,
            'is_read' => 0
        ];
        $this->db->insert('messages', $data);
        return $this->db->insert_id();
    }

    public function mark_messages_read($user_id, $sender_id)
    {
        $this->db->where('sender_id', $sender_id);
        $this->db->where('receiver_id', $user_id);
        return $this->db->update('messages', ['is_read' => 1]);
    }

    public function get_user_forums($user_id, $limit = 20)
    {
        $this->db->select('
            forums.*, 
            (SELECT COUNT(*) FROM forum_likes WHERE forum_likes.forum_id = forums.id) AS likes_count,
            (SELECT COUNT(*) FROM forum_comments WHERE forum_comments.forum_id = forums.id) AS comments_count
        ');
        $this->db->from('forums');
        $this->db->where('forums.created_by', $user_id);
        $this->db->order_by('forums.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_user_comments($user_id, $limit = 20)
    {
        $this->db->select('forum_comments.*, forums.title AS forum_title, forums.id AS forum_id');
        $this->db->from('forum_comments');
        $this->db->join('forums', 'forums.id = forum_comments.forum_id', 'left');
        $this->db->where('forum_comments.user_id', $user_id);
        $this->db->where('forum_comments.parent_id IS NULL');
        $this->db->order_by('forum_comments.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function delete_forum($id)
    {
        // Delete related likes
        $this->db->delete('forum_likes', ['forum_id' => $id]);
        // Delete related saves
        $this->db->delete('forum_saves', ['forum_id' => $id]);
        // Delete related comments
        $this->db->delete('forum_comments', ['forum_id' => $id]);
        // Delete the forum post itself
        return $this->db->delete('forums', ['id' => $id]);
    }

    public function get_user_liked_forums($user_id, $limit = 20)
    {
        $this->db->select('
            forums.*, 
            (SELECT COUNT(*) FROM forum_likes WHERE forum_likes.forum_id = forums.id) AS likes_count,
            (SELECT COUNT(*) FROM forum_comments WHERE forum_comments.forum_id = forums.id) AS comments_count
        ');
        $this->db->from('forum_likes');
        $this->db->join('forums', 'forums.id = forum_likes.forum_id', 'inner');
        $this->db->where('forum_likes.user_id', $user_id);
        $this->db->order_by('forum_likes.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
}