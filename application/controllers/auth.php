<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(['session', 'form_validation', 'email']);
        $this->load->helper(['url', 'form', 'otp']);
    }

    // ==========================================================
    // LOGIN / REGISTER PAGE (satu halaman, 2 form, kayak view lo)
    // ==========================================================
    public function index()
    {
        $mode = $this->input->get('mode') === 'signup' ? 'signup' : 'login';

        $data = [
            'mode'   => $mode,
            'errors' => $this->session->flashdata('errors') ?: [],
            'old'    => $this->session->flashdata('old') ?: [],
        ];

        $this->load->view('auth/login', $data);
    }

    // ==========================================================
    // LOGIN
    // ==========================================================
    public function login()
    {
        $this->form_validation->set_rules('identifier', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->_fail_login(['Email dan password wajib diisi dengan benar.']);
            return;
        }

        $email    = $this->input->post('identifier', TRUE);
        $password = $this->input->post('password', TRUE);

        $user = $this->User_model->get_by_email($email);

        if (!$user || !password_verify($password, $user->password)) {
            $this->_fail_login(['Email atau password salah.']);
            return;
        }

        if ($user->status !== 'active') {
            $this->_fail_login(['Akun kamu nonaktif, hubungi admin.']);
            return;
        }

        if ((int) $user->is_verified === 0) {
            // belum verifikasi OTP -> kirim OTP baru & arahkan ke halaman verifikasi
            $this->_send_otp($user);
            $this->session->set_userdata('pending_user_id', $user->id);
            redirect('auth/verify_otp');
            return;
        }

        // Sukses login
        $this->session->set_userdata([
            'user_id'    => $user->id,
            'full_name'  => $user->full_name,
            'role'       => $user->role,
            'logged_in'  => TRUE,
        ]);

        if ($this->input->post('remember')) {
            // opsional: set cookie remember-me token kalau lo mau dikembangin lagi
        }

        if (in_array($user->role, ['admin', 'super_admin'])) {
            redirect('admin');
        } else {
            redirect('/');
        }
    }

    private function _fail_login($errors)
    {
        $this->session->set_flashdata('errors', $errors);
        $this->session->set_flashdata('old', ['identifier' => $this->input->post('identifier')]);
        redirect('auth?mode=login');
    }

    // ==========================================================
    // REGISTER
    // ==========================================================
    public function register()
    {
        $this->form_validation->set_rules('full_name', 'Nama', 'required|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('password_confirmation', 'Konfirmasi Password', 'required|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', [strip_tags($this->form_validation->error_string())]);
            $this->session->set_flashdata('old', [
                'full_name' => $this->input->post('full_name'),
                'email'     => $this->input->post('email'),
            ]);
            redirect('auth?mode=signup');
            return;
        }

        $user_id = $this->User_model->create([
            'full_name' => $this->input->post('full_name', TRUE),
            'email'     => $this->input->post('email', TRUE),
            'password'  => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'role'      => 'member',
            'status'    => 'active',
            'is_verified' => 1, // Bypass OTP: langsung verified
        ]);

        $user = $this->User_model->get_by_id($user_id);
        
        // Bypass OTP: Langsung login
        $this->session->set_userdata([
            'user_id'    => $user->id,
            'full_name'  => $user->full_name,
            'role'       => $user->role,
            'logged_in'  => TRUE,
        ]);

        redirect('home');
    }

    // ==========================================================
    // VERIFY OTP
    // ==========================================================
    public function verify_otp()
    {
        $user_id = $this->session->userdata('pending_user_id');
        if (!$user_id) {
            redirect('auth');
            return;
        }

        if ($this->input->method() === 'post') {
            $otp_code = trim($this->input->post('otp_code'));
            $result = $this->User_model->verify_otp($user_id, $otp_code);

            if (!$result['status']) {
                $this->session->set_flashdata('errors', [$result['message']]);
                redirect('auth/verify_otp');
                return;
            }

            $user = $this->User_model->get_by_id($user_id);
            $this->session->unset_userdata('pending_user_id');
            $this->session->set_userdata([
                'user_id'   => $user->id,
                'full_name' => $user->full_name,
                'role'      => $user->role,
                'logged_in' => TRUE,
            ]);

            if (in_array($user->role, ['admin', 'super_admin'])) {
                redirect('admin');
            } else {
                redirect('/');
            }
            return;
        }

        $data = ['errors' => $this->session->flashdata('errors') ?: []];
        $this->load->view('auth/verify_otp', $data);
    }

    public function resend_otp()
    {
        $user_id = $this->session->userdata('pending_user_id');
        if (!$user_id) {
            redirect('auth');
            return;
        }
        $user = $this->User_model->get_by_id($user_id);
        $this->_send_otp($user);
        $this->session->set_flashdata('errors', ['Kode OTP baru udah dikirim ke email kamu.']);
        redirect('auth/verify_otp');
    }

    private function _send_otp($user)
    {
        $otp = generate_otp(6);
        $expired_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $this->User_model->save_otp($user->id, $otp, $expired_at);

        $this->email->clear(TRUE); // reset biar gak numpuk dari request sebelumnya
        $this->email->from('no-reply@example.com', 'Keluarga H.M Samhudi');
        $this->email->to($user->email);
        $this->email->subject('Kode Verifikasi Akun Kamu');
        $this->email->message("Halo {$user->full_name},<br><br>Kode OTP kamu: <b>{$otp}</b><br>Berlaku 10 menit.");

        if (!$this->email->send()) {
            log_message('error', 'OTP email gagal kirim ke ' . $user->email . ': ' . $this->email->print_debugger(['headers']));
        }
    }

    // ==========================================================
    // FORGOT PASSWORD
    // ==========================================================
    public function forgot_password()
    {
        if ($this->input->method() === 'post') {
            $email = $this->input->post('email', TRUE);
            $user = $this->User_model->get_by_email($email);

            // Selalu tampilkan pesan sama, biar gak bocorin email terdaftar/nggak
            $generic_message = 'Kalau email kamu terdaftar, link reset password sudah dikirim.';

            if ($user) {
                $token = generate_reset_token();
                $expired_at = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                $this->User_model->save_reset_token($user->id, $token, $expired_at);

                $reset_link = base_url('auth/reset_password/' . $token);

                $this->email->from('no-reply@example.com', 'Keluarga H.M Samhudi');
                $this->email->to($email);
                $this->email->subject('Reset Password');
                $this->email->message("Klik link berikut buat reset password (berlaku 30 menit):<br><a href=\"{$reset_link}\">{$reset_link}</a>");
                $this->email->send();
            }

            $this->session->set_flashdata('message', $generic_message);
            redirect('auth/forgot_password');
            return;
        }

        $data = [
            'message' => $this->session->flashdata('message'),
            'errors'  => $this->session->flashdata('errors') ?: [],
        ];
        $this->load->view('auth/forgot_password', $data);
    }

    // ==========================================================
    // RESET PASSWORD
    // ==========================================================
    public function reset_password($token = null)
    {
        if (!$token) {
            show_404();
            return;
        }

        $reset = $this->User_model->get_valid_reset($token);
        if (!$reset) {
            $this->session->set_flashdata('errors', ['Link reset password sudah tidak valid atau kadaluarsa.']);
            redirect('auth/forgot_password');
            return;
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
            $this->form_validation->set_rules('password_confirmation', 'Konfirmasi Password', 'required|matches[password]');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('errors', [strip_tags($this->form_validation->error_string())]);
                redirect('auth/reset_password/' . $token);
                return;
            }

            $hashed = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $this->User_model->update_password($reset->user_id, $hashed);
            $this->User_model->mark_reset_used($token);

            $this->session->set_flashdata('message', 'Password berhasil diganti, silakan login.');
            redirect('auth?mode=login');
            return;
        }

        $data = [
            'token'  => $token,
            'errors' => $this->session->flashdata('errors') ?: [],
        ];
        $this->load->view('auth/reset_password', $data);
    }

    // ==========================================================
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
