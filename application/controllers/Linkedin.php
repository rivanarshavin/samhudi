<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Linkedin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Linkedin_model');
        $this->load->model('User_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
    }

    private function require_login()
    {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            redirect('auth/');
        }
        return $user_id;
    }

    public function index()
    {
        $user_id = $this->require_login();
        $user   = $this->User_model->get_by_id($user_id);

        $search   = $this->input->get('search');
        $location = $this->input->get('location');
        $type     = $this->input->get('type');

        $data['user']    = $user;
        $data['jobs']    = $this->Linkedin_model->get_approved_jobs($search, $location, $type);
        $data['workers'] = $this->Linkedin_model->get_open_to_work_users();
        $data['filters'] = [
            'search'   => $search,
            'location' => $location,
            'type'     => $type,
        ];

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('linkedin/index', $data);
        $this->load->view('templates/footer');
    }

    public function create_job()
    {
        $user_id = $this->require_login();

        $this->form_validation->set_rules('company_name', 'Nama Perusahaan', 'required');
        $this->form_validation->set_rules('job_title', 'Jenis Pekerjaan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_msg', validation_errors());
            redirect('linkedin');
        } else {
            $data = [
                'user_id'        => $user_id,
                'publisher_name' => $this->session->userdata('full_name'),
                'company_name'   => $this->input->post('company_name'),
                'job_title'      => $this->input->post('job_title'),
                'salary'         => $this->input->post('salary'),
                'job_type'       => $this->input->post('job_type'),
                'working_hours'  => $this->input->post('working_hours'),
                'location'       => $this->input->post('location'),
                'description'    => $this->input->post('description'),
                'status'         => 'pending',
            ];

            $this->Linkedin_model->create_job($data);
            $this->session->set_flashdata('success_msg', 'Lowongan pekerjaan berhasil dikirim dan sedang menunggu verifikasi admin.');
            redirect('linkedin');
        }
    }

    public function get_job_detail($id)
    {
        $user_id = $this->require_login();
        $job = $this->Linkedin_model->get_job_by_id($id);
        if ($job) {
            $has_applied = $this->Linkedin_model->has_applied($user_id, $id);
            $is_owner = ($job->user_id == $user_id);
            echo json_encode([
                'status'      => 'success',
                'data'        => $job,
                'has_applied' => $has_applied,
                'is_owner'    => $is_owner,
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Job not found']);
        }
    }

    public function apply_job()
    {
        $user_id = $this->require_login();

        $job_id = $this->input->post('job_id');
        if (!$job_id) {
            $this->session->set_flashdata('error_msg', 'Lowongan tidak valid.');
            redirect('linkedin');
            return;
        }

        $job = $this->Linkedin_model->get_job_by_id($job_id);
        if (!$job) {
            $this->session->set_flashdata('error_msg', 'Lowongan tidak ditemukan.');
            redirect('linkedin');
            return;
        }

        // Prevent owner from applying
        if ($job->user_id == $user_id) {
            $this->session->set_flashdata('error_msg', 'Anda tidak dapat melamar pekerjaan yang Anda terbitkan sendiri.');
            redirect('linkedin');
            return;
        }

        if ($this->Linkedin_model->has_applied($user_id, $job_id)) {
            $this->session->set_flashdata('error_msg', 'Anda sudah melamar pekerjaan ini.');
            redirect('linkedin');
            return;
        }

        // Handle CV upload
        $cv_path = null;
        if (!empty($_FILES['cv']['name'])) {
            $upload_dir = FCPATH . 'assets/uploads/cv/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $config['upload_path']   = $upload_dir;
            $config['allowed_types'] = 'pdf|doc|docx|jpg|png|jpeg';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('cv')) {
                $upload_data = $this->upload->data();
                $cv_path     = 'assets/uploads/cv/' . $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('error_msg', 'Gagal upload CV: ' . $this->upload->display_errors('', ''));
                redirect('linkedin');
                return;
            }
        } else {
            $this->session->set_flashdata('error_msg', 'CV wajib diunggah.');
            redirect('linkedin');
            return;
        }

        $data = [
            'job_id'     => $job_id,
            'user_id'    => $user_id,
            'cv_path'    => $cv_path,
            'keterangan' => $this->input->post('keterangan'),
        ];

        $this->Linkedin_model->create_application($data);
        $this->session->set_flashdata('success_msg', 'Lamaran berhasil dikirim! Tim perusahaan akan menghubungi Anda.');
        redirect('linkedin');
    }
}
