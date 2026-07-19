<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Menghitung total user dengan role member
     */
    public function get_total_members()
    {
        return $this->db->where('role', 'member')->count_all_results('users');
    }

    /**
     * Menghitung total berita yang dipublish
     */
    public function get_total_news()
    {
        return $this->db->where('status', 'publish')->count_all_results('news');
    }

    /**
     * Menghitung total forum diskusi
     */
    public function get_total_forums()
    {
        return $this->db->count_all_results('forums');
    }

    /**
     * Menghitung total data wasiat
     */
    public function get_total_wills()
    {
        return $this->db->count_all_results('wills');
    }

    /**
     * Mendapatkan aktivitas terbaru dari database
     */
    public function get_recent_activities($limit = 5)
    {
        $sql = "
            (SELECT 
                'Membuat Postingan Forum' AS aktivitas, 
                u.full_name AS pengguna, 
                f.created_at AS waktu, 
                'Berhasil' AS status, 
                f.id AS reff_id, 
                'forum' AS tipe
            FROM forums f
            JOIN users u ON f.created_by = u.id)
            
            UNION ALL
            
            (SELECT 
                'Menambahkan Data Family' AS aktivitas, 
                u.full_name AS pengguna, 
                fm.created_at AS waktu, 
                'Berhasil' AS status, 
                fm.id AS reff_id, 
                'silsilah' AS tipe
            FROM family_members fm
            JOIN users u ON fm.user_id = u.id)
            
            UNION ALL
            
            (SELECT 
                'Membuat Berita' AS aktivitas, 
                u.full_name AS pengguna, 
                n.created_at AS waktu, 
                IF(n.status = 'publish', 'Publish', 'Draft') AS status, 
                n.id AS reff_id, 
                'berita' AS tipe
            FROM news n
            JOIN users u ON n.author_id = u.id)
            
            UNION ALL
            
            (SELECT 
                'Membuat Wasiat' AS aktivitas, 
                u.full_name AS pengguna, 
                w.created_at AS waktu, 
                IF(w.status = 'public', 'Public', 'Private') AS status, 
                w.id AS reff_id, 
                'wasiat' AS tipe
            FROM wills w
            JOIN users u ON w.created_by = u.id)
            
            ORDER BY waktu DESC 
            LIMIT ?
        ";

        return $this->db->query($sql, array((int)$limit))->result_array();
    }

    // =================== FORUM ADMIN ===================

    /**
     * Ambil semua forum dengan jumlah komentar dan nama author
     */
    public function get_all_forums_admin($search = '')
    {
        $this->db->select('forums.*, users.full_name AS author_name,
            (SELECT COUNT(*) FROM forum_comments WHERE forum_comments.forum_id = forums.id) AS comment_count');
        $this->db->from('forums');
        $this->db->join('users', 'users.id = forums.created_by', 'left');
        if (!empty($search)) {
            $this->db->like('forums.title', $search);
        }
        $this->db->order_by('forums.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil detail forum by ID
     */
    public function get_forum_by_id_admin($id)
    {
        $this->db->select('forums.*, users.full_name AS author_name');
        $this->db->from('forums');
        $this->db->join('users', 'users.id = forums.created_by', 'left');
        $this->db->where('forums.id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Hapus forum (cascade ke komentar)
     */
    public function delete_forum_admin($id)
    {
        $id = (int) $id;

        // Nonaktifkan foreign key checks sementara
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        // Hapus likes dan saves terkait
        $this->db->delete('forum_likes', ['forum_id' => $id]);
        $this->db->delete('forum_saves', ['forum_id' => $id]);

        // Hapus semua komentar (termasuk yang punya parent_id)
        $this->db->delete('forum_comments', ['forum_id' => $id]);

        // Hapus forum utama
        $result = $this->db->delete('forums', ['id' => $id]);

        // Aktifkan kembali foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        return $result;
    }

    /**
     * Ambil semua komentar untuk forum ini
     */
    public function get_forum_comments_admin($forum_id)
    {
        $this->db->select('forum_comments.*, users.full_name AS author_name');
        $this->db->from('forum_comments');
        $this->db->join('users', 'users.id = forum_comments.user_id', 'left');
        $this->db->where('forum_comments.forum_id', $forum_id);
        $this->db->order_by('forum_comments.created_at', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Hapus komentar tertentu
     */
    public function delete_comment_admin($id)
    {
        $id = (int) $id;

        // Nonaktifkan foreign key checks sementara
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        // Hapus komentar beserta semua reply-nya
        $this->db->delete('forum_comments', ['parent_id' => $id]);
        $this->db->delete('forum_comments', ['id' => $id]);

        // Aktifkan kembali foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        return true;
    }


    /**
     * Ambil semua berita + nama author
     */
    public function get_all_news_admin($search = '', $status = '')
    {
        $this->db->select('news.*, users.full_name AS author_name');
        $this->db->from('news');
        $this->db->join('users', 'users.id = news.author_id', 'left');
        if (!empty($search)) {
            $this->db->like('news.title', $search);
        }
        if (!empty($status)) {
            $this->db->where('news.status', $status);
        }
        $this->db->order_by('news.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil satu berita by ID
     */
    public function get_news_by_id($id)
    {
        return $this->db->get_where('news', ['id' => $id])->row_array();
    }

    /**
     * Tambah berita baru
     */
    public function insert_news($data)
    {
        return $this->db->insert('news', $data);
    }

    /**
     * Update berita
     */
    public function update_news($id, $data)
    {
        return $this->db->update('news', $data, ['id' => $id]);
    }

    /**
     * Hapus berita
     */
    public function delete_news($id)
    {
        return $this->db->delete('news', ['id' => $id]);
    }

    /**
     * Toggle status berita draft/publish
     */
    public function toggle_news_status($id)
    {
        $news = $this->get_news_by_id($id);
        if (!$news) return false;
        $new_status = ($news['status'] === 'publish') ? 'draft' : 'publish';
        return $this->db->update('news', ['status' => $new_status], ['id' => $id]);
    }

    /**
     * Set satu berita sebagai highlight (eksklusif — hanya 1 aktif)
     * Jika berita yang diklik sudah di-highlight, cabut highlight-nya.
     */
    public function highlight_news($id)
    {
        $news = $this->get_news_by_id($id);
        if (!$news) return false;

        if (!empty($news['is_highlight'])) {
            // Sudah highlight → cabut
            return $this->db->update('news', ['is_highlight' => 0], ['id' => $id]);
        }

        // Cabut highlight semua berita dulu
        $this->db->update('news', ['is_highlight' => 0]);
        // Set highlight pada berita terpilih
        return $this->db->update('news', ['is_highlight' => 1], ['id' => $id]);
    }

    /**
     * Ambil berita yang sedang di-highlight (maksimal 1)
     */
    public function get_highlighted_news()
    {
        $this->db->select('news.*, users.full_name AS author_name');
        $this->db->from('news');
        $this->db->join('users', 'users.id = news.author_id', 'left');
        $this->db->where('news.is_highlight', 1);
        $this->db->where('news.status', 'publish');
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    /**
     * Ambil berita berdasarkan slug (untuk halaman detail)
     */
    public function get_news_by_slug($slug)
    {
        $this->db->select('news.*, users.full_name AS author_name');
        $this->db->from('news');
        $this->db->join('users', 'users.id = news.author_id', 'left');
        $this->db->where('news.slug', $slug);
        return $this->db->get()->row_array();
    }

    /**
     * Menambah jumlah views pada berita
     */
    public function increment_news_views($id)
    {
        $this->db->where('id', $id);
        $this->db->set('views', 'views+1', FALSE);
        $this->db->update('news');
    }

    /**
     * Menambah jumlah likes pada berita
     */
    public function increment_news_likes($id)
    {
        $this->db->where('id', $id);
        $this->db->set('likes', 'likes+1', FALSE);
        $this->db->update('news');
    }

    /**
     * Mengurangi jumlah likes pada berita
     */
    public function decrement_news_likes($id)
    {
        $this->db->where('id', $id);
        $this->db->set('likes', 'IF(likes > 0, likes - 1, 0)', FALSE);
        $this->db->update('news');
    }

    /**
     * Ambil berita lainnya selain berita yang sedang dibuka (untuk sidebar)
     */
    public function get_other_news($exclude_id, $limit = 4)
    {
        $this->db->select('news.*, users.full_name AS author_name');
        $this->db->from('news');
        $this->db->join('users', 'users.id = news.author_id', 'left');
        $this->db->where('news.status', 'publish');
        $this->db->where('news.id !=', $exclude_id);
        $this->db->order_by('news.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    /**
     * Ambil semua berita publish untuk halaman daftar
     */
    public function get_all_published_news($limit = 20, $offset = 0)
    {
        $this->db->select('news.*, users.full_name AS author_name');
        $this->db->from('news');
        $this->db->join('users', 'users.id = news.author_id', 'left');
        $this->db->where('news.status', 'publish');
        $this->db->order_by('news.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    /**
     * Hitung total berita publish
     */
    public function count_published_news()
    {
        return $this->db->where('status', 'publish')->count_all_results('news');
    }

    /**
     * Berita paling banyak dilihat (untuk sidebar profile)
     */
    public function get_most_viewed_news($limit = 5)
    {
        $this->db->select('news.*, users.full_name AS author_name');
        $this->db->from('news');
        $this->db->join('users', 'users.id = news.author_id', 'left');
        $this->db->where('news.status', 'publish');
        $this->db->order_by('news.views', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    /**
     * Berita yang di-like user (dari tabel news_likes)
     */
    public function get_user_news_likes($user_id, $limit = 20)
    {
        $this->db->select('news.*, users.full_name AS author_name');
        $this->db->from('news_likes');
        $this->db->join('news', 'news.id = news_likes.news_id', 'left');
        $this->db->join('users', 'users.id = news.author_id', 'left');
        $this->db->where('news_likes.user_id', $user_id);
        $this->db->where('news.status', 'publish');
        $this->db->order_by('news_likes.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    /**
     * Toggle like berita per-user (untuk news_likes table)
     */
    public function toggle_news_like($news_id, $user_id)
    {
        $check = $this->db->get_where('news_likes', ['news_id' => $news_id, 'user_id' => $user_id])->row();
        if ($check) {
            $this->db->delete('news_likes', ['news_id' => $news_id, 'user_id' => $user_id]);
            $this->decrement_news_likes($news_id);
            return 'unlike';
        } else {
            $this->db->insert('news_likes', ['news_id' => $news_id, 'user_id' => $user_id]);
            $this->increment_news_likes($news_id);
            return 'like';
        }
    }

    /**
     * Cek apakah user sudah like berita
     */
    public function user_liked_news($news_id, $user_id)
    {
        return (bool) $this->db->get_where('news_likes', ['news_id' => $news_id, 'user_id' => $user_id])->row();
    }

    // =================== KELOLA PEKERJA ===================

    public function get_all_pekerja_admin($search = '')
    {
        $this->db->select('op.*, u.full_name, u.avatar, u.email');
        $this->db->from('open_to_work_profiles op');
        $this->db->join('users u', 'u.id = op.user_id', 'left');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('u.full_name', $search);
            $this->db->or_like('op.desired_job', $search);
            $this->db->group_end();
        }
        $this->db->order_by('op.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_pekerja_by_id($id)
    {
        $this->db->select('op.*, u.full_name');
        $this->db->from('open_to_work_profiles op');
        $this->db->join('users u', 'u.id = op.user_id', 'left');
        $this->db->where('op.id', $id);
        return $this->db->get()->row_array();
    }

    public function update_pekerja($id, $data)
    {
        return $this->db->update('open_to_work_profiles', $data, ['id' => $id]);
    }

    public function delete_pekerja($id)
    {
        $pekerja = $this->get_pekerja_by_id($id);
        if ($pekerja) {
            // Update tabel user agar tidak lagi open_to_work
            $this->db->update('users', ['open_to_work' => 0], ['id' => $pekerja['user_id']]);
            // Delete record dari tabel open_to_work_profiles
            return $this->db->delete('open_to_work_profiles', ['id' => $id]);
        }
        return false;
    }
}

