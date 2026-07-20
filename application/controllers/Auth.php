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
        $this->load->model('Log_model');
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
            'success_msg'     => $this->session->flashdata('success_msg'),
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
            if ((int)$user->is_verified === 1) {
                $this->_fail_login(['Akun Anda sedang menunggu persetujuan/verifikasi dari Admin.']);
            } else {
                $this->_fail_login(['Akun kamu nonaktif, hubungi admin.']);
            }
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

        $this->Log_model->insert_log($user->id, $user->full_name, $user->role, 'Login berhasil');

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
        $this->form_validation->set_rules('full_name', 'Nama', 'required|min_length[3]|is_unique[family_members.full_name]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('phone', 'Nomor Telepon', 'required|min_length[10]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('password_confirmation', 'Konfirmasi Password', 'required|matches[password]');
        $this->form_validation->set_rules('captcha_code', 'Captcha', 'required|trim');
        $this->form_validation->set_message('is_unique', '{field} tersebut sudah terdaftar. Mohon gunakan data yang berbeda.');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', [strip_tags($this->form_validation->error_string())]);
            $this->session->set_flashdata('old', [
                'full_name' => $this->input->post('full_name'),
                'email'     => $this->input->post('email'),
                'phone'     => $this->input->post('phone'),
            ]);
            redirect('auth?mode=signup');
            return;
        }

        if (!$this->_verify_captcha('signup', $this->input->post('captcha_code', TRUE))) {
            $this->session->set_flashdata('errors', ['Kode captcha salah, coba lagi.']);
            $this->session->set_flashdata('old', [
                'full_name' => $this->input->post('full_name'),
                'email'     => $this->input->post('email'),
                'phone'     => $this->input->post('phone'),
            ]);
            redirect('auth?mode=signup');
            return;
        }

        $signup_data = [
            'full_name'   => $this->input->post('full_name', TRUE),
            'email'       => $this->input->post('email', TRUE),
            'phone'       => $this->input->post('phone', TRUE),
            'password'    => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'role'        => 'member',
            'status'      => 'inactive',
            'is_verified' => 0,
        ];

        // Save registration details in session (database write happens ONLY after OTP verification)
        $this->session->set_userdata('signup_basic_info', $signup_data);

        redirect('auth/trigger_otp');
    }

    // ==========================================================
    // VERIFY OTP
    // ==========================================================
    public function verify_otp()
    {
        $signup_basic = $this->session->userdata('signup_basic_info');
        if ($signup_basic) {
            if ($this->input->method() === 'post') {
                $otp_code = trim($this->input->post('otp_code'));
                $signup_otp = $this->session->userdata('signup_otp');
                
                if (!$signup_otp || $signup_otp['code'] !== $otp_code) {
                    $this->session->set_flashdata('errors', ['Kode OTP salah.']);
                    redirect('auth/verify_otp');
                    return;
                }
                
                if (strtotime($signup_otp['expired_at']) < time()) {
                    $this->session->set_flashdata('errors', ['OTP sudah kadaluarsa, minta kirim ulang.']);
                    redirect('auth/verify_otp');
                    return;
                }

                // Create the user account now that OTP is verified!
                $user_id = $this->User_model->create([
                    'full_name'   => $signup_basic['full_name'],
                    'email'       => $signup_basic['email'],
                    'phone'       => $signup_basic['phone'],
                    'password'    => $signup_basic['password'],
                    'role'        => $signup_basic['role'],
                    'status'      => 'inactive', // Menunggu persetujuan admin
                    'is_verified' => 1,
                ]);

                // Clear temporary registration session variables
                $this->session->unset_userdata('signup_basic_info');
                $this->session->unset_userdata('signup_member_info');
                $this->session->unset_userdata('signup_otp');

                redirect('auth/pending_approval');
                return;
            }

            $data = ['errors' => $this->session->flashdata('errors') ?: []];
            $this->load->view('auth/verify_otp', $data);
            return;
        }

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

            // If account is still inactive (waiting for admin verification), redirect to notice page
            if ($user->status === 'inactive') {
                redirect('auth/pending_approval');
                return;
            }

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
        $signup_basic = $this->session->userdata('signup_basic_info');
        if ($signup_basic) {
            $this->_send_otp_temp($signup_basic['full_name'], $signup_basic['email']);
            $this->session->set_flashdata('errors', ['Kode OTP baru udah dikirim ke email kamu.']);
            redirect('auth/verify_otp');
            return;
        }

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
        if ($this->session->userdata('logged_in')) {
            $user_id = $this->session->userdata('user_id');
            $nama = $this->session->userdata('full_name') ?? 'User';
            $role = $this->session->userdata('role');
            $this->Log_model->insert_log($user_id, $nama, $role, 'Logout berhasil');
        }
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

    // ==========================================================
    // TRIGGER AND SEND OTP EMAIL BEFORE REDIRECT
    // ==========================================================
    public function trigger_otp()
    {
        $signup_basic = $this->session->userdata('signup_basic_info');
        if (!$signup_basic) {
            redirect('auth');
            return;
        }

        $this->_send_otp_temp($signup_basic['full_name'], $signup_basic['email']);
        redirect('auth/verify_otp');
    }

    // ==========================================================
    // PENDING ADMIN APPROVAL NOTICE PAGE
    // ==========================================================
    public function pending_approval()
    {
        $this->load->view('auth/pending_approval');
    }

    // ==========================================================
    // ONBOARDING (SETELAH LOGIN, SEBELUM BISA MASUK SILSILAH)
    // ==========================================================
    public function onboarding()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
            return;
        }

        // Cek apakah sudah onboarded
        $this->load->model('Family_model');
        $user_id = $this->session->userdata('user_id');
        if ($this->Family_model->is_user_onboarded($user_id)) {
            redirect('/');
            return;
        }

        $this->load->view('templates/header');
        $this->load->view('auth/onboarding');
        $this->load->view('templates/footer');
    }

    public function onboarding_wizard()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
            return;
        }

        // Cek apakah sudah onboarded
        $this->load->model('Family_model');
        $user_id = $this->session->userdata('user_id');
        if ($this->Family_model->is_user_onboarded($user_id)) {
            redirect('/');
            return;
        }

        $data['is_onboarding'] = true;
        
        $this->load->view('templates/header');
        $this->load->view('silsilah/add_member_view', $data);
        $this->load->view('templates/footer');
    }

    public function api_link_member()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['status' => false, 'message' => 'Silakan login terlebih dahulu.']);
            return;
        }

        $member_id = $this->input->post('member_id');
        $user_id   = $this->session->userdata('user_id');

        if (!$member_id) {
            echo json_encode(['status' => false, 'message' => 'Pilih anggota keluarga terlebih dahulu.']);
            return;
        }

        $this->load->model('Family_model');
        $this->db->where('id', $member_id);
        $member = $this->db->get('family_members')->row_array();

        if (!$member) {
            echo json_encode(['status' => false, 'message' => 'Anggota keluarga tidak ditemukan.']);
            return;
        }

        if (!empty($member['user_id'])) {
            echo json_encode(['status' => false, 'message' => 'Anggota keluarga ini sudah tertaut dengan akun lain.']);
            return;
        }

        // Link
        $this->db->where('id', $member_id)->update('family_members', [
            'user_id' => $user_id
        ]);

        echo json_encode(['status' => true, 'message' => 'Berhasil menautkan profil.']);
    }

    public function api_add_self_member()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['status' => false, 'message' => 'Silakan login terlebih dahulu.']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $role    = $this->input->post('role'); // 'anak', 'pasangan', 'orangtua'
        $rel_ids = $this->input->post('rel_id'); // Bisa string tunggal atau array
        
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

        $data = [
            'full_name'  => $full_name,
            'birth_date' => $this->input->post('birth_date'),
            'gender'     => $this->input->post('gender'), // 'L' atau 'P'
            'generasi'   => $this->input->post('generasi') ? (int)$this->input->post('generasi') : NULL,
            'is_alive'   => 1,
            'status'     => 'pending',
            'user_id'    => $user_id
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
            
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('photo')) {
                $uploadData = $this->upload->data();
                $data['photo'] = 'assets/uploads/' . $uploadData['file_name'];
            }
        }

        $result = $this->Family_model->insert_new_member($data, $role, $processed_rel_ids);

        if (isset($result['status']) && $result['status']) {
            echo json_encode(['status' => true, 'id' => $result['id'] ?? null, 'message' => 'Berhasil menambahkan diri ke silsilah.']);
        } else {
            $msg = $result['message'] ?? 'Gagal menambahkan data, pastikan relasi valid.';
            echo json_encode(['status' => false, 'message' => $msg]);
        }
    }

    // ==========================================================
    // TEMPORARY OTP GENERATION AND EMAIL SENDER
    // ==========================================================
    private function _send_otp_temp($name, $email)
    {
        // helper random_string
        $this->load->helper('string');
        $otp = random_string('numeric', 6);
        $expired_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        // Save OTP code in session since there is no user_id yet
        $this->session->set_userdata('signup_otp', [
            'code' => $otp,
            'expired_at' => $expired_at
        ]);

        $this->load->library('email');
        $this->email->clear(TRUE);
        $this->email->from('no-reply@example.com', 'Keluarga H.M Samhudi');
        $this->email->to($email);
        $this->email->subject('Kode Verifikasi Akun Kamu');
        $this->email->message("Halo {$name},<br><br>Kode OTP kamu: <b>{$otp}</b><br>Berlaku 10 menit.");

        if (!$this->email->send()) {
            log_message('error', 'OTP email gagal kirim ke ' . $email . ': ' . $this->email->print_debugger(['headers']));
        }
    }
}