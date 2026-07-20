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
        $this->load->model('Log_model');

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

    private function _log_action($action)
    {
        $user_id = $this->session->userdata('user_id');
        $nama = $this->session->userdata('nama') ?? $this->session->userdata('full_name') ?? 'Admin';
        $role = $this->session->userdata('role');
        $this->Log_model->insert_log($user_id, $nama, $role, $action);
    }

    public function history_log()
    {
        $data = [
            'logs' => $this->Log_model->get_all_logs(1000), // Batasi 1000 log terbaru
            'admin_name' => $this->session->userdata('nama') ?? $this->session->userdata('full_name') ?? 'Admin',
            'admin_role' => $this->session->userdata('role'),
            'active_menu' => 'history_log'
        ];
        $this->load->view('admin/logs', $data);
    }

    public function index()
    {
        $config_path = FCPATH . 'assets/banner-config.json';
        $images_path = FCPATH . 'assets/images/';

        // Handle carousel upload & caption update
        $carousel_config_path = FCPATH . 'assets/carousel-config.json';

        if ($this->input->method() === 'post' && $this->input->post('save_carousel')) {
            $carousel = json_decode(file_get_contents($carousel_config_path), true);
            $captions = $this->input->post('captions') ?: [];

            $upload_path = $images_path . 'family/';
            $files = $_FILES['carousel_file'];

            $count = max(count($captions), is_array($files['name']) ? count($files['name']) : 0);
            $new_carousel = [];

            for ($i = 0; $i < $count; $i++) {
                $caption = $captions[$i] ?? 'Keluarga';
                $file = isset($carousel[$i]) ? $carousel[$i]['file'] : null;

                if (!empty($files['name'][$i]) && $files['error'][$i] === 0) {
                    if (!is_dir($upload_path)) mkdir($upload_path, 0777, true);
                    $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                    $new_name = 'carousel_' . time() . '_' . $i . '.' . $ext;
                    move_uploaded_file($files['tmp_name'][$i], $upload_path . $new_name);
                    $file = 'family/' . $new_name;
                }

                if ($file) {
                    $new_carousel[] = ['file' => $file, 'caption' => $caption];
                }
            }

            file_put_contents($carousel_config_path, json_encode($new_carousel));
            $this->session->set_flashdata('carousel_success', 'Carousel berhasil diperbarui.');
            redirect('admin#carousel-section');
        }

        if ($this->input->method() === 'post' && $this->input->post('delete_carousel')) {
            $index = $this->input->post('delete_index');
            $carousel = json_decode(file_get_contents($carousel_config_path), true);
            if (isset($carousel[$index])) {
                $file_path = $images_path . $carousel[$index]['file'];
                if (file_exists($file_path)) unlink($file_path);
                array_splice($carousel, $index, 1);
                file_put_contents($carousel_config_path, json_encode($carousel));
            }
            redirect('admin#carousel-section');
        }

        if ($this->input->method() === 'post' && $this->input->post('upload_banner')) {
            if (!empty($_FILES['banner_file']['name'])) {
                $allowed = ['jpg','jpeg','png','webp','gif'];
                $ext = strtolower(pathinfo($_FILES['banner_file']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed)) {
                    $this->session->set_flashdata('banner_error', 'Format file tidak didukung. Gunakan jpg, jpeg, png, webp, atau gif.');
                    redirect('admin#banner-section');
                    return;
                }

                if ($_FILES['banner_file']['size'] > 5 * 1024 * 1024) {
                    $this->session->set_flashdata('banner_error', 'Ukuran file maksimal 5MB.');
                    redirect('admin#banner-section');
                    return;
                }

                if (!is_dir($images_path)) mkdir($images_path, 0777, true);

                $new_name = 'banner_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['banner_file']['tmp_name'], $images_path . $new_name)) {
                    $current = json_decode(file_get_contents($config_path), true);
                    $current['file'] = $new_name;
                    file_put_contents($config_path, json_encode($current));
                    $this->session->set_flashdata('banner_success', 'Banner berhasil diperbarui.');
                } else {
                    $this->session->set_flashdata('banner_error', 'Gagal upload file. Coba lagi.');
                }
            } else {
                $this->session->set_flashdata('banner_error', 'Pilih file gambar terlebih dahulu.');
            }
            redirect('admin#banner-section');
        }

        // Handle sambutan text update
        if ($this->input->method() === 'post' && $this->input->post('save_sambutan')) {
            $pars_raw = $this->input->post('sambutan_pars', TRUE);
            $pars = array_values(array_filter(array_map('trim', preg_split('/\n\s*\n/', $pars_raw))));
            file_put_contents(FCPATH . 'assets/sambutan-config.json', json_encode([
                'title' => $this->input->post('sambutan_title', TRUE),
                'paragraphs' => $pars,
                'closing' => $this->input->post('sambutan_closing', TRUE),
                'sender' => $this->input->post('sambutan_sender', TRUE),
            ]));
            $this->session->set_flashdata('sambutan_success', 'Teks sambutan berhasil diperbarui.');
            redirect('admin#sambutan-section');
        }

        // Handle intro text update
        if ($this->input->method() === 'post' && $this->input->post('save_intro')) {
            $intro_text = $this->input->post('intro_text', TRUE);
            $intro_sender = $this->input->post('intro_sender', TRUE);
            file_put_contents(FCPATH . 'assets/intro-config.json', json_encode([
                'text' => $intro_text,
                'sender' => $intro_sender
            ]));
            $this->session->set_flashdata('intro_success', 'Teks intro berhasil diperbarui.');
            redirect('admin#intro-section');
        }

        // Handle makam (lokasi pemakaman)
        $makam_config_path = FCPATH . 'assets/makam-config.json';

        // Handle single photo delete
        if ($this->input->get('delete_makam_photo') !== null) {
            $idx = (int) $this->input->get('delete_makam_photo');
            $makam = json_decode(file_get_contents($makam_config_path), true);
            if (isset($makam['photos'][$idx])) {
                $old = $makam['photos'][$idx];
                if ($old && strpos($old, 'assets/uploads/makam/') === 0 && file_exists('./' . $old)) {
                    unlink('./' . $old);
                }
                array_splice($makam['photos'], $idx, 1);
                file_put_contents($makam_config_path, json_encode($makam));
            }
            redirect('admin#makam-section');
        }

        if ($this->input->method() === 'post' && $this->input->post('save_makam')) {
            $makam = json_decode(file_get_contents($makam_config_path), true);
            $makam['address'] = $this->input->post('makam_address', TRUE);
            $makam['maps_embed_url'] = $this->input->post('makam_maps_url', TRUE);
            $makam['maps_link'] = $this->input->post('makam_maps_link', TRUE);

            $upload_path = FCPATH . 'assets/uploads/makam/';
            if (!is_dir($upload_path)) mkdir($upload_path, 0777, true);
            $allowed = ['jpg','jpeg','png','webp','gif'];

            if (isset($_FILES['makam_photo_new']) && is_array($_FILES['makam_photo_new']['name'])) {
                $files = $_FILES['makam_photo_new'];
                $names = array_filter($files['name']);
                if (!empty($names)) {
                    $base = time();
                    $idx = 0;
                    foreach ($names as $i => $name) {
                        if ($files['error'][$i] !== 0) continue;
                        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        if (!in_array($ext, $allowed)) continue;
                        $new_name = 'makam_' . $base . '_' . $idx . '_' . uniqid() . '.' . $ext;
                        if (move_uploaded_file($files['tmp_name'][$i], $upload_path . $new_name)) {
                            $makam['photos'][] = 'assets/uploads/makam/' . $new_name;
                            $idx++;
                        }
                    }
                }
            }

            file_put_contents($makam_config_path, json_encode($makam));
            $this->session->set_flashdata('makam_success', 'Data lokasi pemakaman berhasil diperbarui.');
            redirect('admin#makam-section');
        }

        $banner_config = json_decode(file_get_contents($config_path), true);
        $intro_config = json_decode(file_get_contents(FCPATH . 'assets/intro-config.json'), true);
        $sambutan_config = json_decode(file_get_contents(FCPATH . 'assets/sambutan-config.json'), true);
        $makam_config = json_decode(file_get_contents($makam_config_path), true);

        $data = [
            'admin_name'        => $this->session->userdata('full_name'),
            'admin_role'        => $this->session->userdata('role'),
            'total_members'     => $this->Admin_model->get_total_members(),
            'total_news'        => $this->Admin_model->get_total_news(),
            'total_forums'      => $this->Admin_model->get_total_forums(),
            'total_wills'       => $this->Admin_model->get_total_wills(),
            'recent_activities' => $this->Admin_model->get_recent_activities(5),
            'highlighted_news'  => $this->Admin_model->get_highlighted_news(),
            'selected_banner'   => $banner_config['file'] ?? 'background2.png',
            'carousel_items'    => json_decode(file_get_contents($carousel_config_path), true),
            'intro_text'        => $intro_config['text'] ?? "Dengan rasa syukur dan bangga,\nkami persembahkan website ini\nsebagai ruang digital untuk\nmenyambung tali silaturahmi",
            'intro_sender'      => $intro_config['sender'] ?? 'From (nama)',
            'sambutan_title'    => $sambutan_config['title'] ?? "Assalamu'alaikum Warahmatullahi Wabarakatuh,",
            'sambutan_pars'     => $sambutan_config['paragraphs'] ?? [],
            'sambutan_closing'  => $sambutan_config['closing'] ?? "Wassalamu'alaikum Warahmatullahi Wabarakatuh.",
            'sambutan_sender'   => $sambutan_config['sender'] ?? 'Keluarga Besar H.M. Samhudi',
            'makam_address'     => $makam_config['address'] ?? '',
            'makam_maps_url'    => $makam_config['maps_embed_url'] ?? '',
            'makam_maps_link'   => $makam_config['maps_link'] ?? '',
            'makam_photos'      => $makam_config['photos'] ?? [],
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
        $generasi = $this->input->get('generasi') ?? '';
        $status = $this->input->get('status') ?? '';

        $data = [
            'admin_name' => $this->session->userdata('full_name'),
            'admin_role' => $this->session->userdata('role'),
            'members'    => $this->Silsilah_model->get_all_members($search, $gender, $is_alive, $generasi, $status),
            'search'     => $search,
            'gender'     => $gender,
            'is_alive'   => $is_alive,
            'generasi'   => $generasi,
            'status'     => $status,
            'max_generasi' => $this->Silsilah_model->get_max_generation()
        ];

        $this->load->view('admin/silsilah/index', $data);
    }

    public function silsilah_approve($id)
    {
        $this->load->model('Silsilah_model');
        $member = $this->Silsilah_model->get_member_by_id($id);
        if ($member) {
            $this->Silsilah_model->update_member($id, ['status' => 'approved']);
            // Activate linked user account if exists
            if (!empty($member['user_id'])) {
                $this->db->where('id', $member['user_id'])->update('users', ['status' => 'active']);
            }
            $this->_log_action('Menyetujui pendaftaran silsilah: ' . $member['full_name']);
            $this->session->set_flashdata('success', 'Anggota silsilah dan akun penggunanya berhasil disetujui.');
        } else {
            $this->session->set_flashdata('error', 'Anggota tidak ditemukan.');
        }
        redirect('admin/silsilah');
    }

    public function silsilah_reject($id)
    {
        $this->load->model('Silsilah_model');
        $member = $this->Silsilah_model->get_member_by_id($id);
        if ($member) {
            $this->Silsilah_model->update_member($id, ['status' => 'rejected']);
            // Delete linked user account to allow re-registration
            if (!empty($member['user_id'])) {
                $this->db->where('user_id', $member['user_id'])->update('family_members', ['user_id' => null]);
                $this->db->where('id', $member['user_id'])->delete('users');
            }
            $this->_log_action('Menolak pendaftaran silsilah: ' . $member['full_name']);
            $this->session->set_flashdata('success', 'Pendaftaran anggota ditolak.');
        } else {
            $this->session->set_flashdata('error', 'Anggota tidak ditemukan.');
        }
        redirect('admin/silsilah');
    }

    public function silsilah_add()
    {
        $this->load->model('Silsilah_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim|is_unique[family_members.full_name]');
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required');
        $this->form_validation->set_message('is_unique', 'Nama {field} sudah terdaftar dalam silsilah. Mohon gunakan nama lain (misal: tambah nama panggilan/alias).');

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
                $config['upload_path']   = FCPATH . 'assets/uploads/family/';
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
            $this->_log_action('Menambahkan anggota silsilah: ' . $insert_data['full_name']);
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

        $is_valid = $this->form_validation->run();
        
        // Manual unique check for full_name (excluding current id)
        if ($is_valid && $this->input->post('full_name')) {
            $this->db->where('full_name', trim($this->input->post('full_name')));
            $this->db->where('id !=', $id);
            if ($this->db->get('family_members')->num_rows() > 0) {
                $is_valid = FALSE;
                // Add fake form error so view can show it (or use session flashdata)
                $this->session->set_flashdata('error', 'Nama anggota "' . htmlspecialchars($this->input->post('full_name')) . '" sudah terdaftar dalam silsilah.');
            }
        }

        if ($is_valid == FALSE) {
            $data = [
                'admin_name'     => $this->session->userdata('full_name'),
                'admin_role'     => $this->session->userdata('role'),
                'member'         => $member,
                'families'       => $this->Silsilah_model->get_all_families(),
                'fathers'        => $this->Silsilah_model->get_parent_options('L'),
                'mothers'        => $this->Silsilah_model->get_parent_options('P'),
                'spouse_options' => $this->Silsilah_model->get_spouse_options($member['gender'], $id),
                'current_spouses'=> $this->Silsilah_model->get_spouses_by_member_id($id),
                'unlinked_users' => $this->Silsilah_model->get_unlinked_users($member['user_id'])
            ];

            $this->load->view('admin/silsilah/edit', $data);
        } else {
            $photo = $member['photo'];

            // Handle photo upload
            if (!empty($_FILES['photo']['name'])) {
                $config['upload_path']   = FCPATH . 'assets/uploads/family/';
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
                'generasi'    => $this->input->post('generasi') ? $this->input->post('generasi') : null,
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
            
            // Update spouses
            $spouses = $this->input->post('spouses') ?? [];
            $this->Silsilah_model->sync_marriages($id, $update_data['gender'], $spouses);

            $this->_log_action('Mengedit data silsilah: ' . $update_data['full_name']);
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
            // Delete linked user account if exists
            if (!empty($member['user_id'])) {
                $this->db->where('user_id', $member['user_id'])->update('family_members', ['user_id' => null]);
                $this->db->where('id', $member['user_id'])->delete('users');
            }
            $this->Silsilah_model->delete_member($id);
            $this->_log_action('Menghapus data silsilah: ' . $member['full_name']);
            $this->session->set_flashdata('success', 'Anggota silsilah dan akun penggunanya berhasil dihapus.');
        }
        redirect('admin/silsilah');
    }

    public function silsilah_delete_multiple()
    {
        $ids = $this->input->post('ids');
        if (!empty($ids) && is_array($ids)) {
            $this->load->model('Silsilah_model');
            $deleted_count = 0;
            
            foreach ($ids as $id) {
                $member = $this->Silsilah_model->get_member_by_id($id);
                if ($member) {
                    // Delete photo file
                    if ($member['photo'] && file_exists('./' . $member['photo'])) {
                        unlink('./' . $member['photo']);
                    }
                    // Delete linked user account if exists
                    if (!empty($member['user_id'])) {
                        $this->db->where('user_id', $member['user_id'])->update('family_members', ['user_id' => null]);
                        $this->db->where('id', $member['user_id'])->delete('users');
                    }
                    $this->Silsilah_model->delete_member($id);
                    $deleted_count++;
                }
            }
            
            if ($deleted_count > 0) {
                $this->_log_action('Menghapus ' . $deleted_count . ' data silsilah secara massal');
                $this->session->set_flashdata('success', $deleted_count . ' anggota silsilah berhasil dihapus.');
            }
        } else {
            $this->session->set_flashdata('error', 'Tidak ada anggota yang dipilih untuk dihapus.');
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
            $this->_log_action('Menghapus topik forum: ' . $forum['title']);
            $this->session->set_flashdata('success', 'Topik forum berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Forum tidak ditemukan.');
        }
        redirect('admin/forum');
    }

    public function api_get_forum_details($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $forum = $this->Admin_model->get_forum_by_id_admin($id);
        if (!$forum) {
            echo json_encode(['status' => false, 'message' => 'Forum tidak ditemukan.']);
            return;
        }

        $comments = $this->Admin_model->get_forum_comments_admin($id);
        echo json_encode([
            'status' => true,
            'forum' => $forum,
            'comments' => $comments
        ]);
    }

    public function forum_comment_delete($comment_id, $forum_id)
    {
        $this->Admin_model->delete_comment_admin($comment_id);
        $this->_log_action('Menghapus komentar di forum ID: ' . $forum_id);
        $this->session->set_flashdata('success', 'Komentar jorok/tidak pantas berhasil dihapus.');
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
                $config['upload_path']   = FCPATH . 'assets/uploads/news/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size']      = 2048;
                $config['file_name']     = 'news_' . time();

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload');
                $this->upload->initialize($config);

                if ($this->upload->do_upload('thumbnail')) {
                    $upload_data = $this->upload->data();
                    $thumbnail   = 'assets/uploads/news/' . $upload_data['file_name'];
                } else {
                    $data = [
                        'admin_name' => $this->session->userdata('full_name'),
                        'admin_role' => $this->session->userdata('role'),
                        'upload_error' => $this->upload->display_errors('', '')
                    ];
                    $this->load->view('admin/berita/add', $data);
                    return;
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
            $this->_log_action('Menambahkan berita: ' . $insert_data['title']);
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
                $config['upload_path']   = FCPATH . 'assets/uploads/news/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size']      = 2048;
                $config['file_name']     = 'news_' . time();

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload');
                $this->upload->initialize($config);

                if ($this->upload->do_upload('thumbnail')) {
                    if ($thumbnail && file_exists('./' . $thumbnail)) {
                        unlink('./' . $thumbnail);
                    }
                    $upload_data = $this->upload->data();
                    $thumbnail   = 'assets/uploads/news/' . $upload_data['file_name'];
                } else {
                    $data = [
                        'admin_name' => $this->session->userdata('full_name'),
                        'admin_role' => $this->session->userdata('role'),
                        'news'       => $news,
                        'upload_error' => $this->upload->display_errors('', '')
                    ];
                    $this->load->view('admin/berita/edit', $data);
                    return;
                }
            }

            $update_data = [
                'title'     => $this->input->post('title'),
                'thumbnail' => $thumbnail,
                'content'   => $this->input->post('content'),
                'status'    => $this->input->post('status') ?: 'draft',
            ];

            $this->Admin_model->update_news($id, $update_data);
            $this->_log_action('Mengedit berita: ' . $update_data['title']);
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
            $this->_log_action('Menghapus berita: ' . $news['title']);
            $this->session->set_flashdata('success', 'Berita berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Berita tidak ditemukan.');
        }
        redirect('admin/berita');
    }

    public function berita_toggle_status($id)
    {
        $this->Admin_model->toggle_news_status($id);
        $this->_log_action('Mengubah status berita ID: ' . $id);
        $this->session->set_flashdata('success', 'Status berita berhasil diubah.');
        redirect('admin/berita');
    }

    public function berita_highlight($id)
    {
        $this->Admin_model->highlight_news($id);
        $this->_log_action('Mengubah highlight berita ID: ' . $id);
        $this->session->set_flashdata('success', 'Status highlight berita berhasil diubah.');
        redirect('admin/berita');
    }

    // ================= KELOLA BANNER PROFIL =================
    public function banner_profil()
    {
        $data = [
            'admin_name' => $this->session->userdata('full_name'),
            'admin_role' => $this->session->userdata('role'),
            'banners'    => $this->db->order_by('created_at', 'DESC')->get('profile_banners')->result_array()
        ];
        $this->load->view('admin/banner_profil/index', $data);
    }

    public function banner_profil_add()
    {
        if ($this->input->method() == 'post') {
            if (!empty($_FILES['banner_file']['name'])) {
                $config['upload_path']   = FCPATH . 'assets/uploads/banners/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size']      = 5120; // 5MB
                $config['file_name']     = 'pbanner_' . time();

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload');
                $this->upload->initialize($config);

                if ($this->upload->do_upload('banner_file')) {
                    $upload_data = $this->upload->data();
                    $image_path  = 'assets/uploads/banners/' . $upload_data['file_name'];
                    
                    $this->db->insert('profile_banners', ['image_path' => $image_path]);
                    $this->session->set_flashdata('success', 'Banner berhasil ditambahkan.');
                    redirect('admin/banner_profil');
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                    redirect('admin/banner_profil_add');
                }
            } else {
                $this->session->set_flashdata('error', 'Silakan pilih gambar.');
                redirect('admin/banner_profil_add');
            }
        } else {
            $data = [
                'admin_name' => $this->session->userdata('full_name'),
                'admin_role' => $this->session->userdata('role')
            ];
            $this->load->view('admin/banner_profil/add', $data);
        }
    }

    public function banner_profil_delete($id)
    {
        $banner = $this->db->get_where('profile_banners', ['id' => $id])->row_array();
        if ($banner) {
            if (file_exists('./' . $banner['image_path'])) {
                unlink('./' . $banner['image_path']);
            }
            $this->db->delete('profile_banners', ['id' => $id]);
            $this->session->set_flashdata('success', 'Banner berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Banner tidak ditemukan.');
        }
        redirect('admin/banner_profil');
    }

    // --- MANAJEMEN SILSILAH LAMA ---
    public function kelola_silsilah()
    {
        $this->load->model('Silsilah_model');
        // Ambil semua data member untuk tabel
        $data['members'] = $this->db->query("
            SELECT fm.*, 
                   f.full_name as ayah_name, 
                   m.full_name as ibu_name
            FROM family_members fm
            LEFT JOIN family_members f ON fm.father_id = f.id
            LEFT JOIN family_members m ON fm.mother_id = m.id
            ORDER BY fm.id DESC
        ")->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('partials/navbar');
        $this->load->view('admin/kelola_silsilah', $data);
        $this->load->view('templates/footer');
    }

    public function api_delete_member($id)
    {
        $this->load->model('Silsilah_model');
        header('Content-Type: application/json; charset=utf-8');
        
        $member = $this->Silsilah_model->get_member_by_id($id);
        if ($member) {
            // Delete old photo
            if ($member['photo'] && file_exists('./' . $member['photo'])) {
                unlink('./' . $member['photo']);
            }
            // Delete user account if linked
            if (!empty($member['user_id'])) {
                $this->db->where('user_id', $member['user_id'])->update('family_members', ['user_id' => null]);
                $this->db->where('id', $member['user_id'])->delete('users');
            }
            $this->Silsilah_model->delete_member($id);
            $this->_log_action('Menghapus anggota silsilah (API): ' . $member['full_name']);
        }
        echo json_encode(['status' => true, 'message' => 'Anggota dan akun penggunanya berhasil dihapus.']);
    }

    // ================= KELOLA PENGGUNA =================

    public function pengguna()
    {
        $search = $this->input->get('search') ?? '';
        $status = $this->input->get('status') ?? '';
        $role   = $this->input->get('role') ?? '';

        $this->db->select('*');
        $this->db->from('users');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('full_name', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('phone', $search);
            $this->db->group_end();
        }

        if (!empty($status)) {
            $this->db->where('status', $status);
        }

        if (!empty($role)) {
            $this->db->where('role', $role);
        }

        $this->db->order_by('id', 'DESC');
        $users = $this->db->get()->result_array();

        $data = [
            'admin_name' => $this->session->userdata('full_name'),
            'admin_role' => $this->session->userdata('role'),
            'users'      => $users,
            'search'     => $search,
            'status'     => $status,
            'role_filter'=> $role,
            'active_menu'=> 'pengguna'
        ];

        $this->load->view('admin/pengguna', $data);
    }

    public function pengguna_approve($id)
    {
        $this->db->where('id', $id)->update('users', [
            'status' => 'active',
            'is_verified' => 1
        ]);
        $this->_log_action('Menyetujui pendaftaran pengguna ID: ' . $id);
        $this->session->set_flashdata('success', 'User berhasil disetujui.');
        redirect('admin/pengguna');
    }

    public function pengguna_delete($id)
    {
        // Set user_id in family_members to NULL before deleting user
        $this->db->where('user_id', $id)->update('family_members', ['user_id' => null]);
        $this->db->where('id', $id)->delete('users');
        $this->_log_action('Menghapus pengguna ID: ' . $id);

        $this->session->set_flashdata('success', 'User berhasil dihapus.');
        redirect('admin/pengguna');
    }

    // ================= KELOLA LOWONGAN =================

    public function lowongan()
    {
        $this->load->model('Linkedin_model');

        $all_jobs = $this->Linkedin_model->get_all_jobs();

        // Attach applicants to each job
        $jobs = [];
        foreach ($all_jobs as $job) {
            $job_arr               = (array) $job;
            $job_arr['applicants'] = $this->Linkedin_model->get_applications_by_job($job->id);
            $jobs[]                = $job_arr;
        }

        $data = [
            'admin_name' => $this->session->userdata('full_name'),
            'admin_role' => $this->session->userdata('role'),
            'jobs'       => $jobs,
            'active_menu'=> 'lowongan',
        ];

        $this->load->view('admin/lowongan', $data);
    }

    public function lowongan_approve($id)
    {
        $this->load->model('Linkedin_model');
        $this->Linkedin_model->update_job_status($id, 'approved');
        $this->session->set_flashdata('success', 'Lowongan berhasil disetujui.');
        redirect('admin/lowongan');
    }

    public function lowongan_reject($id)
    {
        $this->load->model('Linkedin_model');
        $this->Linkedin_model->update_job_status($id, 'rejected');
        $this->session->set_flashdata('success', 'Lowongan berhasil ditolak.');
        redirect('admin/lowongan');
    }

    public function lowongan_delete($id)
    {
        $this->load->model('Linkedin_model');
        $this->Linkedin_model->delete_job($id);
        $this->session->set_flashdata('success', 'Lowongan berhasil dihapus.');
        redirect('admin/lowongan');
    }

    public function lowongan_add()
    {
        $this->load->model('Linkedin_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('company_name', 'Nama Perusahaan', 'required');
        $this->form_validation->set_rules('job_title', 'Posisi / Jenis Pekerjaan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Nama perusahaan dan posisi wajib diisi.');
            redirect('admin/lowongan');
            return;
        }

        $data = [
            'user_id'        => $this->session->userdata('user_id'),
            'publisher_name' => $this->session->userdata('full_name'),
            'company_name'   => $this->input->post('company_name'),
            'job_title'      => $this->input->post('job_title'),
            'salary'         => $this->input->post('salary'),
            'job_type'       => $this->input->post('job_type'),
            'working_hours'  => $this->input->post('working_hours'),
            'location'       => $this->input->post('location'),
            'description'    => $this->input->post('description'),
            'status'         => 'approved',
        ];

        $this->Linkedin_model->create_job($data);
        $this->session->set_flashdata('success', 'Lowongan berhasil ditambahkan dan langsung aktif.');
        redirect('admin/lowongan');
    }

    // ================= KELOLA PEKERJA (OPEN TO WORK) =================

    public function pekerja()
    {
        $search = $this->input->get('search') ?? '';

        $data = [
            'admin_name' => $this->session->userdata('full_name'),
            'admin_role' => $this->session->userdata('role'),
            'pekerja'    => $this->Admin_model->get_all_pekerja_admin($search),
            'search'     => $search,
        ];

        $this->load->view('admin/pekerja/index', $data);
    }

    public function pekerja_edit($id)
    {
        $pekerja = $this->Admin_model->get_pekerja_by_id($id);
        if (!$pekerja) {
            show_404();
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('desired_job', 'Pekerjaan Diharapkan', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data = [
                'admin_name' => $this->session->userdata('full_name'),
                'admin_role' => $this->session->userdata('role'),
                'pekerja'    => $pekerja,
            ];
            $this->load->view('admin/pekerja/edit', $data);
        } else {
            // Handle CV upload
            $cv_path = $pekerja['cv_path'];
            if (!empty($_FILES['cv_file']['name'])) {
                $upload_dir = FCPATH . 'assets/uploads/cv/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $config['upload_path']   = $upload_dir;
                $config['allowed_types'] = 'pdf|jpg|jpeg|png|doc|docx';
                $config['max_size']      = 2048; // 2MB
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('cv_file')) {
                    if (!empty($cv_path) && file_exists(FCPATH . $cv_path)) {
                        unlink(FCPATH . $cv_path);
                    }
                    $upload_data = $this->upload->data();
                    $cv_path = 'assets/uploads/cv/' . $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', 'Gagal upload CV: ' . $this->upload->display_errors('', ''));
                    redirect('admin/pekerja_edit/' . $id);
                    return;
                }
            }

            $update_data = [
                'birth_date'   => $this->input->post('birth_date') ?: NULL,
                'work_history' => $this->input->post('work_history'),
                'desired_job'  => $this->input->post('desired_job'),
                'about'        => $this->input->post('about'),
                'cv_path'      => $cv_path,
            ];

            $this->Admin_model->update_pekerja($id, $update_data);
            $this->session->set_flashdata('success', 'Profil pekerja berhasil diperbarui.');
            redirect('admin/pekerja');
        }
    }

    public function pekerja_delete($id)
    {
        $pekerja = $this->Admin_model->get_pekerja_by_id($id);
        if ($pekerja) {
            // Hapus file CV jika ada
            if (!empty($pekerja['cv_path']) && file_exists(FCPATH . $pekerja['cv_path'])) {
                unlink(FCPATH . $pekerja['cv_path']);
            }
            $this->Admin_model->delete_pekerja($id);
            $this->session->set_flashdata('success', 'Profil pekerja berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Profil pekerja tidak ditemukan.');
        }
        redirect('admin/pekerja');
    }

}
