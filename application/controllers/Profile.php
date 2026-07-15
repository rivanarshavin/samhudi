<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Forum_model');
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

    /**
     * Halaman profil user yang login
     */
    public function index()
    {
        $user_id = $this->require_login();

        $user   = $this->User_model->get_by_id($user_id);
        if (!$user) {
            redirect('auth/');
        }

        $data['user']             = $user;
        $data['available_banners']= $this->db->get('profile_banners')->result_array();
        $data['user_forums']      = $this->Forum_model->get_user_forums($user_id, 30);
        $data['user_comments']    = $this->Forum_model->get_user_comments($user_id, 30);
        $data['user_news_likes']  = $this->Admin_model->get_user_news_likes($user_id, 30);
        $data['user_forum_likes'] = $this->Forum_model->get_user_liked_forums($user_id, 30);
        $data['most_viewed_news'] = $this->Admin_model->get_most_viewed_news(6);

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('home/profile', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Update profil user (form edit)
     */
    public function update()
    {
        $user_id = $this->require_login();

        $bio      = $this->input->post('bio');
        $location = $this->input->post('location');

        $update_data = [
            'bio'      => $bio,
            'location' => $location,
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
                $this->session->set_userdata('avatar', $update_data['avatar']);
            }
        }

        // Handle cover banner string (dari radio select grid)
        $selected_banner = $this->input->post('cover_banner');
        if (!empty($selected_banner)) {
            $update_data['cover_banner'] = $selected_banner;
        }

        $this->User_model->update($user_id, $update_data);
        $this->session->set_flashdata('success_msg', 'Profil berhasil diperbarui!');
        redirect('profile');
    }
}
