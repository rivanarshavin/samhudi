<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Familytree extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Family_model');
        $this->load->library('session');
        $this->load->helper('url');

        // Middleware Onboarding
        $allowed_methods = ['api_search_members', 'api_get_unlinked_members', 'api_save_member'];
        $current_method = $this->router->fetch_method();
        
        if ($this->session->userdata('logged_in') && !in_array($current_method, $allowed_methods)) {
            $role = $this->session->userdata('role');
            if ($role === 'member') {
                $user_id = $this->session->userdata('user_id');
                if (!$this->Family_model->is_user_onboarded($user_id)) {
                    // Redirect to onboarding page if not onboarded
                    redirect('auth/onboarding');
                }
            }
        }
    }

    public function index()
    {
        $data = [];
        if ($this->session->userdata('logged_in')) {
            $user_id = $this->session->userdata('user_id');
            $member = $this->db->get_where('family_members', ['user_id' => $user_id])->row();
            $data['logged_in_member_id'] = $member ? $member->id : null;
        }

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('silsilah/familytree_view', $data);
        $this->load->view('templates/footer');
    }

    public function get_member_detail()
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = $this->input->get('id');
        
        if (!$id) {
            echo json_encode(['error' => 'ID tidak ditemukan']);
            return;
        }

        $bypass = $this->input->get('preview') == 1;
        $data = $this->Family_model->get_member_full_details($id, $bypass);

        if (!$data) {
            echo json_encode(['error' => 'Data tidak ditemukan']);
            return;
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function get_family_tree()
    {
        header('Content-Type: application/json; charset=utf-8');

        $rootId   = $this->input->get('root_id');
        $familyId = $this->input->get('family_id');

        $data = $this->Family_model->get_family_tree($rootId, $familyId);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
    // --- FITUR TAMBAH ANGGOTA (WIZARD) ---
    
    public function add()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('errors', ['Anda harus login terlebih dahulu untuk menambah anggota silsilah.']);
            redirect('auth');
            return;
        }

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('silsilah/add_member_view');
        $this->load->view('templates/footer');
    }
    
    public function api_search_members()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!$this->session->userdata('logged_in')) {
            echo json_encode([]);
            return;
        }

        $term = $this->input->get('term');
        if (empty($term)) {
            echo json_encode([]);
            return;
        }
        $results = $this->Family_model->search_members_for_wizard($term);
        echo json_encode($results);
    }
    
    public function api_get_unlinked_members()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!$this->session->userdata('logged_in')) {
            echo json_encode([]);
            return;
        }

        $results = $this->Family_model->get_unlinked_members(20);
        echo json_encode($results);
    }
    
    public function api_save_member()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['status' => false, 'message' => 'Sesi berakhir, silakan login kembali.']);
            return;
        }
        
        $role = $this->input->post('role'); // 'anak', 'pasangan', 'orangtua'
        $rel_ids = $this->input->post('rel_id'); // ID dari anggota yang dipilih
        
        if (!is_array($rel_ids)) {
            $rel_ids = empty($rel_ids) ? [] : explode(',', $rel_ids);
        }

        $processed_rel_ids = [];
        $this->load->model('Family_model');

        $full_name = trim($this->input->post('full_name'));

        // Cek nama kembar di database family_members untuk main user
        $this->db->where('full_name', $full_name);
        if ($this->db->get('family_members')->num_rows() > 0) {
            echo json_encode(['status' => false, 'message' => 'Nama "' . htmlspecialchars($full_name) . '" sudah terdaftar dalam silsilah. Mohon gunakan nama yang berbeda (misal: tambah nama panggilan/alias).']);
            return;
        }

        foreach ($rel_ids as $r_id) {
            if (strpos($r_id, 'new_') === 0) {
                // Format: new_Nama_Gender_Generasi_ParentID
                $parts = explode('_', $r_id);
                $parent_id = array_pop($parts);
                if ($parent_id == '0') $parent_id = null;
                
                $generasi = array_pop($parts);
                $gender = array_pop($parts); // L atau P
                array_shift($parts); // hapus awalan 'new'
                $name = urldecode(implode('_', $parts));
                $name = trim($name);

                // Cek nama kembar untuk relasi
                $this->db->where('full_name', $name);
                if ($this->db->get('family_members')->num_rows() > 0) {
                    echo json_encode(['status' => false, 'message' => 'Nama relasi "' . htmlspecialchars($name) . '" sudah ada di silsilah. Mohon pilih dari daftar, atau gunakan nama berbeda jika orangnya berbeda.']);
                    return;
                }

                // Buat relasi baru (pending)
                $new_member_data = [
                    'full_name' => $name,
                    'gender'    => $gender,
                    'generasi'  => $generasi,
                    'is_alive'  => 1,
                    'status'    => 'pending'
                ];
                    
                    // Cek jika parent ID disertakan
                    if ($parent_id) {
                        $parent_member = $this->db->where('id', $parent_id)->get('family_members')->row();
                        if ($parent_member) {
                            if (strtoupper($parent_member->gender) === 'L') {
                                $new_member_data['father_id'] = $parent_id;
                            } else {
                                $new_member_data['mother_id'] = $parent_id;
                            }
                        }
                    }
                    
                    $this->db->insert('family_members', $new_member_data);
                    $processed_rel_ids[] = $this->db->insert_id();
            } else {
                $processed_rel_ids[] = (int)$r_id;
            }
        }

        $this->load->library('session');
        $pending_user_id = $this->session->userdata('pending_user_id');

        $data = [
            'full_name' => $full_name,
            'birth_date' => $this->input->post('birth_date'),
            'gender' => $this->input->post('gender'), // 'L' atau 'P'
            'generasi' => $this->input->post('generasi') ? (int)$this->input->post('generasi') : NULL,
            'is_alive' => 1,
            'status' => 'approved',
            'created_by' => $this->session->userdata('user_id')
        ];

        if ($pending_user_id) {
            $data['user_id'] = $pending_user_id;
            
            // Auto fill phone and email from the users record
            $this->load->model('User_model');
            $pending_user = $this->User_model->get_by_id($pending_user_id);
            if ($pending_user) {
                $data['phone'] = $pending_user->phone;
                $data['email'] = $pending_user->email;
            }
        }
        
        if (empty($role) || empty($processed_rel_ids) || empty($data['full_name']) || empty($data['gender'])) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap.']);
            return;
        }
        
        // Handle photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['name']) {
            $config['upload_path']   = FCPATH . 'assets/uploads/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['file_name']     = time() . '_' . $_FILES['photo']['name'];
            
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }
            
            $this->load->library('upload');
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('photo')) {
                $uploadData = $this->upload->data();
                $data['photo'] = 'assets/uploads/' . $uploadData['file_name'];
            }
        }
        
        $result = $this->Family_model->insert_new_member($data, $role, $processed_rel_ids);
        
        if (isset($result['status']) && $result['status']) {
            echo json_encode(['status' => true, 'message' => 'Berhasil menambahkan data keluarga.', 'id' => $result['id'] ?? null]);
        } else {
            $msg = $result['message'] ?? 'Gagal menambahkan data, pastikan relasi valid.';
            echo json_encode(['status' => false, 'message' => $msg]);
        }
    }

    public function edit_member($id = null)
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('errors', ['Anda harus login terlebih dahulu.']);
            redirect('auth');
            return;
        }

        if (!$id) {
            redirect('familytree');
            return;
        }

        $this->load->model('Family_model');
        $member = $this->db->get_where('family_members', ['id' => $id])->row_array();

        if (!$member) {
            show_404();
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        if ($role !== 'admin' && $role !== 'super_admin' && $member['created_by'] != $user_id) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
            redirect('familytree');
            return;
        }

        if ($this->input->post()) {
            $data = [
                'full_name' => $this->input->post('full_name'),
                'gender' => $this->input->post('gender'),
                'birth_place' => $this->input->post('birth_place'),
                'birth_date' => empty($this->input->post('birth_date')) ? NULL : $this->input->post('birth_date'),
                'death_date' => empty($this->input->post('death_date')) ? NULL : $this->input->post('death_date'),
                'is_alive' => $this->input->post('is_alive') ? 1 : 0,
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'occupation' => $this->input->post('occupation'),
                'address' => $this->input->post('address'),
                'generasi' => $this->input->post('generasi') ? (int)$this->input->post('generasi') : NULL,
                'father_id' => $this->input->post('father_id') ? (int)$this->input->post('father_id') : NULL,
                'mother_id' => $this->input->post('mother_id') ? (int)$this->input->post('mother_id') : NULL,
            ];

            // Handle photo upload
            if (isset($_FILES['photo']) && $_FILES['photo']['name']) {
                $config['upload_path']   = FCPATH . 'assets/uploads/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size']      = 2048; // 2MB
                $config['file_name']     = time() . '_' . $_FILES['photo']['name'];
                
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }
                
                $this->load->library('upload');
                $this->upload->initialize($config);
                
                if ($this->upload->do_upload('photo')) {
                    $uploadData = $this->upload->data();
                    $data['photo'] = 'assets/uploads/' . $uploadData['file_name'];
                    
                    if (!empty($member['photo']) && file_exists(FCPATH . $member['photo'])) {
                        unlink(FCPATH . $member['photo']);
                    }
                }
            }

            $this->db->where('id', $id);
            $this->db->update('family_members', $data);

            $this->session->set_flashdata('success', 'Data berhasil diubah.');
            redirect('familytree');
            return;
        }

        $data['member'] = $member;
        $this->load->view('silsilah/edit', $data);
    }

    private function _is_nuclear_family($user_id, $target_id)
    {
        $user_member = $this->db->get_where('family_members', ['user_id' => $user_id])->row();
        if (!$user_member) return false;
        $uid = $user_member->id;
        
        if ($uid == $target_id) return true;
        
        // Cek apakah target adalah anak
        $this->db->where('id', $target_id);
        $this->db->group_start();
        $this->db->where('father_id', $uid);
        $this->db->or_where('mother_id', $uid);
        $this->db->group_end();
        if ($this->db->count_all_results('family_members') > 0) return true;
        
        // Cek apakah target adalah pasangan
        $this->db->group_start();
        $this->db->where('husband_id', $uid);
        $this->db->where('wife_id', $target_id);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('husband_id', $target_id);
        $this->db->where('wife_id', $uid);
        $this->db->group_end();
        if ($this->db->count_all_results('marriages') > 0) return true;
        
        return false;
    }

    public function api_get_member_raw()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['error' => 'Akses ditolak']);
            return;
        }

        $id = $this->input->get('id');
        $user_id = $this->session->userdata('user_id');
        
        if (!$this->_is_nuclear_family($user_id, $id)) {
            echo json_encode(['error' => 'Anda hanya dapat mengedit data keluarga inti Anda (suami/istri, anak).']);
            return;
        }
        
        $member = $this->db->get_where('family_members', ['id' => $id])->row_array();
        if (!$member) {
            echo json_encode(['error' => 'Data tidak ditemukan']);
            return;
        }
        
        $this->load->model('Silsilah_model');
        $member['spouses'] = $this->Silsilah_model->get_spouses_by_member_id($id);
        
        echo json_encode($member);
    }

    public function api_update_member()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['status' => false, 'message' => 'Akses ditolak']);
            return;
        }

        $id = $this->input->post('id');
        $user_id = $this->session->userdata('user_id');
        
        if (!$this->_is_nuclear_family($user_id, $id)) {
            echo json_encode(['status' => false, 'message' => 'Anda hanya dapat mengedit data keluarga inti Anda.']);
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => strip_tags(validation_errors())]);
            return;
        }
        
        // Manual check for unique full_name if name changed
        $full_name = trim($this->input->post('full_name'));
        $current_member = $this->db->get_where('family_members', ['id' => $id])->row();
        if ($full_name !== $current_member->full_name) {
            $this->db->where('full_name', $full_name);
            $this->db->where('id !=', $id);
            if ($this->db->get('family_members')->num_rows() > 0) {
                echo json_encode(['status' => false, 'message' => 'Nama sudah terdaftar. Mohon gunakan variasi nama yang berbeda agar tidak kembar.']);
                return;
            }
        }

        $update_data = [
            'full_name'   => $full_name,
            'gender'      => $this->input->post('gender'),
            'birth_place' => $this->input->post('birth_place'),
            'birth_date'  => $this->input->post('birth_date') ? $this->input->post('birth_date') : null,
            'occupation'  => $this->input->post('occupation'),
            'address'     => $this->input->post('address'),
            'phone'       => $this->input->post('phone'),
            'email'       => $this->input->post('email'),
            'generasi'    => $this->input->post('generasi') ? $this->input->post('generasi') : null,
            'father_id'   => $this->input->post('father_id') ? $this->input->post('father_id') : null,
            'mother_id'   => $this->input->post('mother_id') ? $this->input->post('mother_id') : null,
            'is_alive'    => $this->input->post('is_alive') ?? 1,
            'death_date'  => $this->input->post('is_alive') == 1 ? null : ($this->input->post('death_date') ? $this->input->post('death_date') : null),
        ];

        // Handle photo upload if exists
        if (isset($_FILES['photo']) && $_FILES['photo']['name']) {
            $config['upload_path']   = FCPATH . 'assets/uploads/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['file_name']     = time() . '_' . $_FILES['photo']['name'];
            
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }
            
            $this->load->library('upload');
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('photo')) {
                $uploadData = $this->upload->data();
                $update_data['photo'] = 'assets/uploads/' . $uploadData['file_name'];
            }
        }

        $this->db->where('id', $id);
        if ($this->db->update('family_members', $update_data)) {
            // Update spouses
            $spouses = $this->input->post('spouses') ?? [];
            if (!is_array($spouses)) {
                $spouses = explode(',', $spouses);
            }
            $this->load->model('Silsilah_model');
            $this->Silsilah_model->sync_marriages($id, $update_data['gender'], $spouses);

            echo json_encode(['status' => true, 'message' => 'Data berhasil diperbarui.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal memperbarui data.']);
        }
    }
}
