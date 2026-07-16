<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wasiat extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Wasiat_model');
    }

    public function index()
    {
        $wasiat_list = $this->Wasiat_model->get_all_wills();
        
        // Auto-seed dummy data so there are exactly 15 points
        $current_count = count($wasiat_list);
        if ($current_count < 15) {
            $dummy_content = "Jagalah selalu hubunganmu dengan Allah SWT. Dirikan shalat lima waktu tepat pada waktunya, tunaikan zakat untuk membersihkan hartamu, dan jadikan Al-Qur'an sebagai pedoman hidup di setiap langkahmu. Ingatlah bahwa dunia ini hanyalah sementara, sedangkan akhirat adalah tempat kembali yang abadi. Jangan biarkan kesibukan dunia melalaikanmu dari mengingat Sang Pencipta.";
            for ($i = $current_count + 1; $i <= 15; $i++) {
                $this->Wasiat_model->insert_will([
                    'title' => 'Point ' . $i,
                    'content' => $dummy_content
                ]);
            }
            // Fetch again after inserting
            $wasiat_list = $this->Wasiat_model->get_all_wills();
        }

        $data['wasiat_list'] = $wasiat_list;
        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('wasiat_view', $data);
        $this->load->view('templates/footer');
    }

    // --- TEMPORARY CRUD ---

    private function check_admin()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $role = $this->session->userdata('role');
        if ($role !== 'admin' && $role !== 'superadmin') {
            redirect('wasiat');
        }
    }

    public function manage()
    {
        $this->check_admin();
        $data['wills'] = $this->Wasiat_model->get_all_wills();
        $this->load->view('wasiat_manage', $data);
    }

    public function add()
    {
        $this->check_admin();
        $this->load->view('wasiat_form');
    }

    public function store()
    {
        $this->check_admin();
        $data = [
            'title' => $this->input->post('title'),
            'content' => $this->input->post('content')
        ];
        $this->Wasiat_model->insert_will($data);
        redirect('wasiat');
    }

    public function edit($id)
    {
        $this->check_admin();
        $data['will'] = $this->Wasiat_model->get_will_by_id($id);
        $this->load->view('wasiat_form', $data);
    }

    public function update($id)
    {
        $this->check_admin();
        $data = [
            'title' => $this->input->post('title'),
            'content' => $this->input->post('content')
        ];
        $this->Wasiat_model->update_will($id, $data);
        redirect('wasiat');
    }

    public function delete($id)
    {
        $this->check_admin();
        $this->Wasiat_model->delete_will($id);
        redirect('wasiat/manage');
    }
}