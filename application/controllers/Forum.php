<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forum extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Forum_model');
        $this->load->model('User_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation', 'session']);
    }

    private function get_logged_user_id()
    {
        return $this->session->userdata('user_id');
    }

    public function index()
    {
        $user_id = $this->get_logged_user_id();
        if (!$user_id) {
            redirect('auth/');
            return;
        }

        $filter = $this->input->get('filter') ? $this->input->get('filter') : 'all';
        $search = $this->input->get('search') ? $this->input->get('search') : '';

        // Get user details
        $data['user'] = $this->User_model->get_by_id($user_id);
        $data['forums'] = $this->Forum_model->get_all_forums($user_id, $filter, $search);
        $data['popular_weekly'] = $this->Forum_model->get_popular_weekly();
        $data['filter'] = $filter;
        $data['search'] = $search;

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('forum/index', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $user_id = $this->get_logged_user_id();
        if (!$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu.']);
            return;
        }

        $title = $this->input->post('title');
        $content = $this->input->post('content');

        if (empty($title) || empty($content)) {
            $this->session->set_flashdata('error_msg', 'Judul dan konten tidak boleh kosong.');
            redirect('forum');
            return;
        }

        $media_url = null;
        $media_type = null;

        // Process media upload
        if (!empty($_FILES['media']['name'])) {
            $upload_path = FCPATH . 'assets/uploads/forum/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $file_ext = strtolower(pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION));
            $new_name = 'forum_' . time() . '_' . rand(100, 999) . '.' . $file_ext;
            $target_file = $upload_path . $new_name;

            if (move_uploaded_file($_FILES['media']['tmp_name'], $target_file)) {
                $media_url = 'assets/uploads/forum/' . $new_name;
                
                // Determine media type
                $image_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $video_exts = ['mp4', 'webm', 'ogg', 'mov', 'avi'];
                if (in_array($file_ext, $image_exts)) {
                    $media_type = 'image';
                } elseif (in_array($file_ext, $video_exts)) {
                    $media_type = 'video';
                }
            }
        }

        $data = [
            'title'      => $title,
            'content'    => $content,
            'media_url'  => $media_url,
            'media_type' => $media_type,
            'created_by' => $user_id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->Forum_model->create_forum($data);
        $this->session->set_flashdata('success_msg', 'Topik diskusi berhasil diterbitkan!');
        redirect('forum');
    }

    public function view($id)
    {
        $user_id = $this->get_logged_user_id();
        $data['forum'] = $this->Forum_model->get_forum($id, $user_id);

        if (!$data['forum']) {
            show_404();
            return;
        }

        $data['comments'] = $this->Forum_model->get_comments($id);
        $data['user'] = $this->User_model->get_by_id($user_id);

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('forum/view', $data);
        $this->load->view('templates/footer');
    }

    public function comment($forum_id)
    {
        $user_id = $this->get_logged_user_id();
        if (!$user_id) {
            redirect('auth/');
            return;
        }

        $comment = $this->input->post('comment');
        if (empty($comment)) {
            redirect('forum/view/' . $forum_id);
            return;
        }

        $parent_id = $this->input->post('parent_id');

        $data = [
            'forum_id'   => $forum_id,
            'user_id'    => $user_id,
            'parent_id'  => $parent_id ? $parent_id : null,
            'comment'    => $comment,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->Forum_model->create_comment($data);
        redirect('forum/view/' . $forum_id);
    }

    // Ajax endpoint to fetch comments for a post
    public function get_comments_ajax($forum_id)
    {
        $comments = $this->Forum_model->get_comments($forum_id);
        // Format base URLs for avatars
        foreach ($comments as &$c) {
            $c->avatar_url = !empty($c->author_avatar) ? base_url($c->author_avatar) : base_url('assets/images/photo.png');
            if (!empty($c->replies)) {
                foreach ($c->replies as &$r) {
                    $r->avatar_url = !empty($r->author_avatar) ? base_url($r->author_avatar) : base_url('assets/images/photo.png');
                }
            }
        }
        echo json_encode($comments);
    }

    // Ajax endpoint for toggling like
    public function like($forum_id)
    {
        $user_id = $this->get_logged_user_id();
        if (!$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']);
            return;
        }

        $res = $this->Forum_model->toggle_like($forum_id, $user_id);
        echo json_encode(['status' => 'success', 'action' => $res['status'], 'likes_count' => $res['count']]);
    }

    // Ajax endpoint for toggling save/bookmark
    public function save($forum_id)
    {
        $user_id = $this->get_logged_user_id();
        if (!$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']);
            return;
        }

        $res = $this->Forum_model->toggle_save($forum_id, $user_id);
        echo json_encode(['status' => 'success', 'action' => $res['status']]);
    }

    // Profile updates inline
    public function update_profile()
    {
        $user_id = $this->get_logged_user_id();
        if (!$user_id) {
            $this->session->set_flashdata('error_msg', 'Sesi Anda telah berakhir.');
            redirect('forum');
            return;
        }

        $bio = $this->input->post('bio');
        $location = $this->input->post('location');

        $update_data = [
            'bio' => $bio,
            'location' => $location
        ];

        $upload_path = FCPATH . 'assets/uploads/profiles/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        // Handle avatar upload
        if (!empty($_FILES['avatar']['name'])) {
            $file_ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            $new_name = 'avatar_' . $user_id . '_' . time() . '.' . $file_ext;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_path . $new_name)) {
                $update_data['avatar'] = 'assets/uploads/profiles/' . $new_name;
                
                // Update session avatar if stored there
                $this->session->set_userdata('avatar', $update_data['avatar']);
            }
        }

        // Handle cover_banner upload
        if (!empty($_FILES['cover_banner']['name'])) {
            $file_ext = strtolower(pathinfo($_FILES['cover_banner']['name'], PATHINFO_EXTENSION));
            $new_name = 'cover_' . $user_id . '_' . time() . '.' . $file_ext;
            if (move_uploaded_file($_FILES['cover_banner']['tmp_name'], $upload_path . $new_name)) {
                $update_data['cover_banner'] = 'assets/uploads/profiles/' . $new_name;
            }
        }

        $this->User_model->update($user_id, $update_data);
        $this->session->set_flashdata('success_msg', 'Profil berhasil diperbarui!');
        redirect('forum');
    }

    // --- REALTIME CHAT ENDPOINTS ---
    
    // Get list of users with their last message and unread count
    public function chat_contacts()
    {
        $user_id = $this->get_logged_user_id();
        if (!$user_id) {
            echo json_encode([]);
            return;
        }

        $contacts = $this->Forum_model->get_chat_contacts($user_id);
        
        // Format HTML or send JSON
        echo json_encode($contacts);
    }

    // Get messages for a specific conversation
    public function chat_messages($other_id)
    {
        $user_id = $this->get_logged_user_id();
        if (!$user_id) {
            echo json_encode([]);
            return;
        }

        $this->Forum_model->mark_messages_read($user_id, $other_id);
        $messages = $this->Forum_model->get_chat_messages($user_id, $other_id);
        
        // Parse times to format nice string
        foreach ($messages as &$m) {
            $m->formatted_time = date('H.i', strtotime($m->created_at));
        }

        echo json_encode($messages);
    }

    // Send a message
    public function send_chat_message()
    {
        $user_id = $this->get_logged_user_id();
        if (!$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'Sesi berakhir. Silakan login.']);
            return;
        }

        $receiver_id = $this->input->post('receiver_id');
        $message = $this->input->post('message');

        if (empty($receiver_id) || empty($message)) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
            return;
        }

        $msg_id = $this->Forum_model->send_chat_message($user_id, $receiver_id, $message);
        echo json_encode(['status' => 'success', 'message_id' => $msg_id]);
    }

    // Delete a forum post
    public function delete($id)
    {
        $user_id = $this->get_logged_user_id();
        if (!$user_id) {
            $this->session->set_flashdata('error_msg', 'Silakan login terlebih dahulu.');
            redirect('forum');
            return;
        }

        $forum = $this->Forum_model->get_forum($id, $user_id);
        if (!$forum) {
            show_404();
            return;
        }

        // Check ownership
        if ($forum->created_by != $user_id) {
            $this->session->set_flashdata('error_msg', 'Anda tidak memiliki akses untuk menghapus postingan ini.');
            redirect('forum');
            return;
        }

        // Delete associated media files from server
        if (!empty($forum->media_url) && file_exists('./' . $forum->media_url)) {
            unlink('./' . $forum->media_url);
        }

        $this->Forum_model->delete_forum($id);
        $this->session->set_flashdata('success_msg', 'Postingan berhasil dihapus!');
        redirect('forum');
    }
}