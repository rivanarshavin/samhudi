<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    // Daftar tipe captcha yang valid + nama session key masing-masing
    private $captcha_map = [
        'login'  => 'captcha_login_word',
        'signup' => 'captcha_signup_word',
        'forgot' => 'captcha_forgot_word',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(['session', 'form_validation', 'email']);
        $this->load->helper(['url', 'form', 'otp', 'captcha']);
    }

    // ==========================================================
    // LOGIN / REGISTER PAGE (satu halaman, 2 form, kayak view lo)
    // ==========================================================
    public function index()
    {
        $mode = $this->input->get('mode') === 'signup' ? 'signup' : 'login';

        $data = [
            'mode'            => $mode,
            'errors'          => $this->session->flashdata('errors') ?: [],
            'old'             => $this->session->flashdata('old') ?: [],
            'captcha_login'   => $this->_make_captcha('login', 'captcha-login-img'),
            'captcha_signup'  => $this->_make_captcha('signup', 'captcha-signup-img'),
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
        $this->form_validation->set_rules('captcha_code', 'Captcha', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->_fail_login(['Email, password, dan captcha wajib diisi dengan benar.']);
            return;
        }

        if (!$this->_verify_captcha('login', $this->input->post('captcha_code', TRUE))) {
            $this->_fail_login(['Kode captcha salah, coba lagi.']);
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
        $this->form_validation->set_rules('captcha_code', 'Captcha', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', [strip_tags($this->form_validation->error_string())]);
            $this->session->set_flashdata('old', [
                'full_name' => $this->input->post('full_name'),
                'email'     => $this->input->post('email'),
            ]);
            redirect('auth?mode=signup');
            return;
        }

        if (!$this->_verify_captcha('signup', $this->input->post('captcha_code', TRUE))) {
            $this->session->set_flashdata('errors', ['Kode captcha salah, coba lagi.']);
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
            'is_verified' => 0,
        ]);

        $user = $this->User_model->get_by_id($user_id);
        $this->_send_otp($user);

        $this->session->set_userdata('pending_user_id', $user_id);
        redirect('auth/verify_otp');
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

            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('captcha_code', 'Captcha', 'required|trim');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('errors', ['Email dan captcha wajib diisi dengan benar.']);
                redirect('auth/forgot_password');
                return;
            }

            if (!$this->_verify_captcha('forgot', $this->input->post('captcha_code', TRUE))) {
                $this->session->set_flashdata('errors', ['Kode captcha salah, coba lagi.']);
                redirect('auth/forgot_password');
                return;
            }

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
            'message'        => $this->session->flashdata('message'),
            'errors'         => $this->session->flashdata('errors') ?: [],
            'captcha_forgot' => $this->_make_captcha('forgot', 'captcha-forgot-img'),
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

    // ==========================================================
    // CAPTCHA (bawaan CI3, GD, case-sensitive huruf+angka)
    // ==========================================================

    /**
     * Config dasar captcha. Butuh ekstensi GD aktif & folder
     * assets/captcha/ writable di webroot (chmod 755/775).
     */
    private function _captcha_vals($img_id)
    {
        return [
            'img_path'    => FCPATH . 'assets/captcha/',
            'img_url'     => base_url('assets/captcha/'),
            'img_width'   => 160,
            'img_height'  => 45,
            'font_size'   => 18,
            'word_length' => 6,
            'expiration'  => 300, // detik, file lama otomatis dihapus tiap generate baru
            'img_id'      => $img_id,
            // pool campur huruf besar, kecil, angka -> case-sensitive
            'pool'        => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
            'colors'      => [
                'background' => [38, 53, 48],
                'border'     => [212, 181, 113],
                'text'       => [255, 255, 255],
                'grid'       => [68, 94, 89],
            ],
        ];
    }

    /**
     * Generate captcha baru, simpan kode ke session, return <img> tag HTML.
     */
    private function _make_captcha($type, $img_id)
    {
        if (!isset($this->captcha_map[$type])) {
            return '';
        }

        $cap = create_captcha($this->_captcha_vals($img_id));

        if ($cap === false) {
            log_message('error', 'Gagal generate captcha. Cek GD aktif & folder assets/captcha/ writable.');
            return '';
        }

        $this->session->set_userdata($this->captcha_map[$type], $cap['word']);

        return $cap['image'];
    }

    /**
     * Cek input user vs kode di session. Case-sensitive (strcmp).
     * Sekali dicek langsung dihapus dari session (one-time use).
     */
    private function _verify_captcha($type, $input)
    {
        if (!isset($this->captcha_map[$type])) {
            return false;
        }

        $session_key = $this->captcha_map[$type];
        $expected    = $this->session->userdata($session_key);
        $this->session->unset_userdata($session_key);

        if ($expected === null || $input === null || $input === '') {
            return false;
        }

        return strcmp(trim($input), $expected) === 0;
    }

    /**
     * Endpoint AJAX buat tombol "Ganti Kode" di view.
     * GET auth/captcha_refresh/login | signup | forgot
     */
    public function captcha_refresh($type = null)
    {
        if (!isset($this->captcha_map[$type])) {
            show_404();
            return;
        }

        $img_id = 'captcha-' . $type . '-img';
        $cap = create_captcha($this->_captcha_vals($img_id));

        if ($cap === false) {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Gagal generate captcha']));
            return;
        }

        $this->session->set_userdata($this->captcha_map[$type], $cap['word']);

        preg_match('/src="([^"]+)"/', $cap['image'], $m);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['image_url' => $m[1] ?? '']));
    }
}