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
        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('silsilah/familytree_view');
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

        foreach ($rel_ids as $r_id) {
            if (strpos($r_id, 'new_') === 0) {
                // Format: new_Nama_Gender[_ParentID]
                $parts = explode('_', $r_id);
                $parent_id = null;
                if (count($parts) >= 4 && is_numeric(end($parts))) {
                    $parent_id = array_pop($parts);
                }
                
                if (count($parts) >= 3) {
                    $gender = array_pop($parts);
                    array_shift($parts); // hapus awalan 'new'
                    $name = urldecode(implode('_', $parts));

                    // Buat relasi baru (pending)
                    $new_member_data = [
                        'full_name' => $name,
                        'gender'    => $gender,
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
                }
            } else {
                $processed_rel_ids[] = (int)$r_id;
            }
        }

        $this->load->library('session');
        $pending_user_id = $this->session->userdata('pending_user_id');

        $data = [
            'full_name' => $this->input->post('full_name'),
            'birth_date' => $this->input->post('birth_date'),
            'gender' => $this->input->post('gender'), // 'L' atau 'P'
            'generasi' => $this->input->post('generasi') ? (int)$this->input->post('generasi') : NULL,
            'is_alive' => 1,
            'status' => 'pending'
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
}
