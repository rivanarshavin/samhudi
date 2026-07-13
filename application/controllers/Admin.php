<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Admin_model');

        // Proteksi Halaman Admin: Hanya untuk role admin atau super_admin
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
            return;
        }

        $role = $this->session->userdata('role');
        if (!in_array($role, ['admin', 'super_admin'])) {
            redirect('home'); // Anggota biasa ditendang ke homepage
            return;
        }
    }

    public function index()
    {
        $data = [
            'admin_name'        => $this->session->userdata('full_name'),
            'admin_role'        => $this->session->userdata('role'),
            'total_members'     => $this->Admin_model->get_total_members(),
            'total_news'        => $this->Admin_model->get_total_news(),
            'total_forums'      => $this->Admin_model->get_total_forums(),
            'total_wills'       => $this->Admin_model->get_total_wills(),
            'recent_activities' => $this->Admin_model->get_recent_activities(5),
        ];

        $this->load->view('admin/dashboard', $data);
    }

    // ================= KELOLA SILSILAH =================

    public function silsilah()
    {
        $this->load->model('Silsilah_model');
        
        $search = $this->input->get('search') ?? '';
        $gender = $this->input->get('gender') ?? '';
        $is_alive = $this->input->get('is_alive') ?? '';

        $data = [
            'admin_name' => $this->session->userdata('full_name'),
            'admin_role' => $this->session->userdata('role'),
            'members'    => $this->Silsilah_model->get_all_members($search, $gender, $is_alive),
            'search'     => $search,
            'gender'     => $gender,
            'is_alive'   => $is_alive
        ];

        $this->load->view('admin/silsilah/index', $data);
    }

    public function silsilah_add()
    {
        $this->load->model('Silsilah_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data = [
                'admin_name'     => $this->session->userdata('full_name'),
                'admin_role'     => $this->session->userdata('role'),
                'families'       => $this->Silsilah_model->get_all_families(),
                'fathers'        => $this->Silsilah_model->get_parent_options('L'),
                'mothers'        => $this->Silsilah_model->get_parent_options('P'),
                'unlinked_users' => $this->Silsilah_model->get_unlinked_users()
            ];

            $this->load->view('admin/silsilah/add', $data);
        } else {
            $photo = null;

            // Handle photo upload
            if (!empty($_FILES['photo']['name'])) {
                $config['upload_path']   = './assets/uploads/family/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size']      = 2048; // 2MB
                $config['file_name']     = 'member_' . time();

                // Ensure path exists
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('photo')) {
                    $upload_data = $this->upload->data();
                    $photo = 'assets/uploads/family/' . $upload_data['file_name'];
                }
            }

            $insert_data = [
                'family_id'   => $this->input->post('family_id') ? $this->input->post('family_id') : null,
                'user_id'     => $this->input->post('user_id') ? $this->input->post('user_id') : null,
                'father_id'   => $this->input->post('father_id') ? $this->input->post('father_id') : null,
                'mother_id'   => $this->input->post('mother_id') ? $this->input->post('mother_id') : null,
                'full_name'   => $this->input->post('full_name'),
                'gender'      => $this->input->post('gender'),
                'birth_place' => $this->input->post('birth_place'),
                'birth_date'  => $this->input->post('birth_date') ? $this->input->post('birth_date') : null,
                'death_date'  => $this->input->post('is_alive') == 1 ? null : ($this->input->post('death_date') ? $this->input->post('death_date') : null),
                'phone'       => $this->input->post('phone'),
                'email'       => $this->input->post('email'),
                'occupation'  => $this->input->post('occupation'),
                'address'     => $this->input->post('address'),
                'is_alive'    => $this->input->post('is_alive') ?? 1,
                'photo'       => $photo
            ];

            // Auto-create family if none exists
            if (empty($insert_data['family_id'])) {
                $families = $this->Silsilah_model->get_all_families();
                if (empty($families)) {
                    // Create default family
                    $this->db->insert('families', [
                        'family_name' => 'Keluarga Besar H.M Samhudi',
                        'description' => 'Keluarga Utama'
                    ]);
                    $insert_data['family_id'] = $this->db->insert_id();
                } else {
                    $insert_data['family_id'] = $families[0]['id'];
                }
            }

            $this->Silsilah_model->insert_member($insert_data);
            $this->session->set_flashdata('success', 'Anggota silsilah berhasil ditambahkan.');
            redirect('admin/silsilah');
        }
    }

    public function silsilah_edit($id)
    {
        $this->load->model('Silsilah_model');
        $this->load->library('form_validation');

        $member = $this->Silsilah_model->get_member_by_id($id);
        if (!$member) {
            show_404();
        }

        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data = [
                'admin_name'     => $this->session->userdata('full_name'),
                'admin_role'     => $this->session->userdata('role'),
                'member'         => $member,
                'families'       => $this->Silsilah_model->get_all_families(),
                'fathers'        => $this->Silsilah_model->get_parent_options('L'),
                'mothers'        => $this->Silsilah_model->get_parent_options('P'),
                'unlinked_users' => $this->Silsilah_model->get_unlinked_users($member['user_id'])
            ];

            $this->load->view('admin/silsilah/edit', $data);
        } else {
            $photo = $member['photo'];

            // Handle photo upload
            if (!empty($_FILES['photo']['name'])) {
                $config['upload_path']   = './assets/uploads/family/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size']      = 2048; // 2MB
                $config['file_name']     = 'member_' . time();

                // Ensure path exists
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('photo')) {
                    // Delete old photo if exists
                    if ($photo && file_exists('./' . $photo)) {
                        unlink('./' . $photo);
                    }
                    $upload_data = $this->upload->data();
                    $photo = 'assets/uploads/family/' . $upload_data['file_name'];
                }
            }

            $update_data = [
                'family_id'   => $this->input->post('family_id') ? $this->input->post('family_id') : null,
                'user_id'     => $this->input->post('user_id') ? $this->input->post('user_id') : null,
                'father_id'   => $this->input->post('father_id') ? $this->input->post('father_id') : null,
                'mother_id'   => $this->input->post('mother_id') ? $this->input->post('mother_id') : null,
                'full_name'   => $this->input->post('full_name'),
                'gender'      => $this->input->post('gender'),
                'birth_place' => $this->input->post('birth_place'),
                'birth_date'  => $this->input->post('birth_date') ? $this->input->post('birth_date') : null,
                'death_date'  => $this->input->post('is_alive') == 1 ? null : ($this->input->post('death_date') ? $this->input->post('death_date') : null),
                'phone'       => $this->input->post('phone'),
                'email'       => $this->input->post('email'),
                'occupation'  => $this->input->post('occupation'),
                'address'     => $this->input->post('address'),
                'is_alive'    => $this->input->post('is_alive') ?? 1,
                'photo'       => $photo
            ];

            $this->Silsilah_model->update_member($id, $update_data);
            $this->session->set_flashdata('success', 'Anggota silsilah berhasil diperbarui.');
            redirect('admin/silsilah');
        }
    }

    public function silsilah_delete($id)
    {
        $this->load->model('Silsilah_model');
        $member = $this->Silsilah_model->get_member_by_id($id);
        if ($member) {
            // Delete photo file
            if ($member['photo'] && file_exists('./' . $member['photo'])) {
                unlink('./' . $member['photo']);
            }
            $this->Silsilah_model->delete_member($id);
            $this->session->set_flashdata('success', 'Anggota silsilah berhasil dihapus.');
        }
        redirect('admin/silsilah');
    }

    public function forum()
    {
        $search = $this->input->get('search') ?? '';

        $data = [
            'admin_name' => $this->session->userdata('full_name'),
            'admin_role' => $this->session->userdata('role'),
            'forums'     => $this->Admin_model->get_all_forums_admin($search),
            'search'     => $search,
        ];

        $this->load->view('admin/forum/index', $data);
    }

    public function forum_delete($id)
    {
        $forum = $this->Admin_model->get_forum_by_id_admin($id);
        if ($forum) {
            $this->Admin_model->delete_forum_admin($id);
            $this->session->set_flashdata('success', 'Topik forum berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Forum tidak ditemukan.');
        }
        redirect('admin/forum');
    }

    // ================= KELOLA BERITA =================

    public function berita()
    {
        $search = $this->input->get('search') ?? '';
        $status = $this->input->get('status') ?? '';

        $data = [
            'admin_name' => $this->session->userdata('full_name'),
            'admin_role' => $this->session->userdata('role'),
            'news_list'  => $this->Admin_model->get_all_news_admin($search, $status),
            'search'     => $search,
            'status'     => $status,
        ];

        $this->load->view('admin/berita/index', $data);
    }

    public function berita_add()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Judul Berita', 'required|trim');
        $this->form_validation->set_rules('content', 'Isi Berita', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data = [
                'admin_name' => $this->session->userdata('full_name'),
                'admin_role' => $this->session->userdata('role'),
            ];
            $this->load->view('admin/berita/add', $data);
        } else {
            $thumbnail = null;

            // Handle thumbnail upload
            if (!empty($_FILES['thumbnail']['name'])) {
                $config['upload_path']   = './assets/uploads/news/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size']      = 2048;
                $config['file_name']     = 'news_' . time();

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('thumbnail')) {
                    $upload_data = $this->upload->data();
                    $thumbnail   = 'assets/uploads/news/' . $upload_data['file_name'];
                }
            }

            $title = $this->input->post('title');
            $slug  = url_title($title, 'dash', TRUE) . '-' . time();

            $insert_data = [
                'title'     => $title,
                'slug'      => $slug,
                'thumbnail' => $thumbnail,
                'content'   => $this->input->post('content'),
                'author_id' => $this->session->userdata('user_id'),
                'status'    => $this->input->post('status') ?: 'draft',
            ];

            $this->Admin_model->insert_news($insert_data);
            $this->session->set_flashdata('success', 'Berita berhasil ditambahkan.');
            redirect('admin/berita');
        }
    }

    public function berita_edit($id)
    {
        $news = $this->Admin_model->get_news_by_id($id);
        if (!$news) {
            show_404();
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Judul Berita', 'required|trim');
        $this->form_validation->set_rules('content', 'Isi Berita', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data = [
                'admin_name' => $this->session->userdata('full_name'),
                'admin_role' => $this->session->userdata('role'),
                'news'       => $news,
            ];
            $this->load->view('admin/berita/edit', $data);
        } else {
            $thumbnail = $news['thumbnail'];

            // Handle thumbnail upload
            if (!empty($_FILES['thumbnail']['name'])) {
                $config['upload_path']   = './assets/uploads/news/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size']      = 2048;
                $config['file_name']     = 'news_' . time();

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('thumbnail')) {
                    if ($thumbnail && file_exists('./' . $thumbnail)) {
                        unlink('./' . $thumbnail);
                    }
                    $upload_data = $this->upload->data();
                    $thumbnail   = 'assets/uploads/news/' . $upload_data['file_name'];
                }
            }

            $update_data = [
                'title'     => $this->input->post('title'),
                'thumbnail' => $thumbnail,
                'content'   => $this->input->post('content'),
                'status'    => $this->input->post('status') ?: 'draft',
            ];

            $this->Admin_model->update_news($id, $update_data);
            $this->session->set_flashdata('success', 'Berita berhasil diperbarui.');
            redirect('admin/berita');
        }
    }

    public function berita_delete($id)
    {
        $news = $this->Admin_model->get_news_by_id($id);
        if ($news) {
            if ($news['thumbnail'] && file_exists('./' . $news['thumbnail'])) {
                unlink('./' . $news['thumbnail']);
            }
            $this->Admin_model->delete_news($id);
            $this->session->set_flashdata('success', 'Berita berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Berita tidak ditemukan.');
        }
        redirect('admin/berita');
    }

    public function berita_toggle_status($id)
    {
        $this->Admin_model->toggle_news_status($id);
        $this->session->set_flashdata('success', 'Status berita berhasil diubah.');
        redirect('admin/berita');
    }
}
