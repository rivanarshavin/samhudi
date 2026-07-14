<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Familytree extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Family_model');
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

        $data = $this->Family_model->get_member_full_details($id);

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
        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('silsilah/add_member_view');
        $this->load->view('templates/footer');
    }
    
    public function api_search_members()
    {
        header('Content-Type: application/json; charset=utf-8');
        $term = $this->input->get('term');
        if (empty($term)) {
            echo json_encode([]);
            return;
        }
        $results = $this->Family_model->search_members_for_wizard($term);
        echo json_encode($results);
    }
    
    public function api_save_member()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        $role = $this->input->post('role'); // 'anak', 'pasangan', 'orangtua'
        $rel_id = $this->input->post('rel_id'); // ID dari anggota yang dipilih
        
        $data = [
            'full_name' => $this->input->post('full_name'),
            'birth_date' => $this->input->post('birth_date'),
            'gender' => $this->input->post('gender'), // 'L' atau 'P'
            // Default stat
            'is_alive' => 1
        ];
        
        if (empty($role) || empty($rel_id) || empty($data['full_name']) || empty($data['gender'])) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap.']);
            return;
        }
        
        // Handle photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['name']) {
            $config['upload_path']   = FCPATH . 'assets/uploads/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['file_name']     = time() . '_' . $_FILES['photo']['name'];
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('photo')) {
                $uploadData = $this->upload->data();
                $data['photo'] = $uploadData['file_name'];
            }
        }
        
        $result = $this->Family_model->insert_new_member($data, $role, $rel_id);
        
        if ($result['status']) {
            $this->session->set_flashdata('success', $result['message']);
        }
        
        echo json_encode($result);
    }
}
