<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model {

    private $table = 'activity_logs';

    public function __construct() {
        parent::__construct();
        $this->_ensure_table();
    }

    /**
     * Buat tabel activity_logs jika belum ada
     */
    private function _ensure_table() {
        if (!$this->db->table_exists($this->table)) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `{$this->table}` (
                    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `user_id` INT(11) NULL,
                    `nama` VARCHAR(100) NULL,
                    `role` VARCHAR(50) NULL,
                    `action` VARCHAR(255) NOT NULL,
                    `ip_address` VARCHAR(45) NULL,
                    `user_agent` TEXT NULL,
                    `created_at` DATETIME NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }
    }

    /**
     * Deteksi IP address asli pengunjung.
     * Mendukung proxy/reverse proxy melalui header X-Forwarded-For & X-Real-IP.
     */
    public function get_real_ip() {
        // Cek header proxy terlebih dahulu (untuk hosting dengan Nginx/Apache proxy)
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // X-Forwarded-For bisa berisi beberapa IP (chain), ambil yang pertama
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ips[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
        if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = trim($_SERVER['HTTP_X_REAL_IP']);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = trim($_SERVER['HTTP_CLIENT_IP']);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        // Gunakan IP koneksi langsung dari CodeIgniter
        $ip = $this->input->ip_address();

        // Jika masih loopback (localhost), kembalikan IP Publik dengan menembak API eksternal
        if ($ip === '::1' || $ip === '127.0.0.1') {
            $ctx = stream_context_create(['http' => ['timeout' => 2]]);
            $public_ip = @file_get_contents('https://api.ipify.org', false, $ctx);
            if ($public_ip && filter_var($public_ip, FILTER_VALIDATE_IP)) {
                return $public_ip;
            }
            return '127.0.0.1 (localhost)';
        }

        return $ip;
    }

    /**
     * Insert log aktivitas baru
     */
    public function insert_log($user_id, $nama, $role, $action) {
        $this->load->library('user_agent');
        
        // Dapatkan nama browser
        $agent = 'Unknown Browser';
        if ($this->agent->is_browser()) {
            $agent = $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = $this->agent->mobile();
        }

        // Deteksi OS (Platform)
        $os = $this->agent->platform(); 

        // Fix untuk Windows 11 (Browser mengirimkan "Windows NT 10.0" untuk Win 10 dan Win 11)
        // Kita gunakan Client Hints header (didukung Chrome/Edge modern) untuk mendeteksinya
        if (isset($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) && isset($_SERVER['HTTP_SEC_CH_UA_PLATFORM_VERSION'])) {
            $ch_platform = trim($_SERVER['HTTP_SEC_CH_UA_PLATFORM'], '"');
            $ch_version = trim($_SERVER['HTTP_SEC_CH_UA_PLATFORM_VERSION'], '"');
            
            if ($ch_platform === 'Windows') {
                $major_version = (int) explode('.', $ch_version)[0];
                if ($major_version >= 13) {
                    $os = 'Windows 11';
                } else {
                    $os = 'Windows 10';
                }
            }
        } elseif ($os === 'Windows 10') {
            // Jika browser tidak mengirim Client Hints (seperti Firefox), 
            // kita beri label gabungan karena secara teknis tidak bisa dibedakan murni dari User-Agent
            $os = 'Windows 10 / 11'; 
        }

        $browser_os = $agent . ' on ' . $os;

        $data = array(
            'user_id'    => $user_id,
            'nama'       => $nama,
            'role'       => $role,
            'action'     => $action,
            'ip_address' => $this->get_real_ip(),
            'user_agent' => $browser_os,
            'created_at' => date('Y-m-d H:i:s')
        );
        return $this->db->insert($this->table, $data);
    }

    /**
     * Ambil semua data log diurutkan berdasarkan waktu terbaru
     */
    public function get_all_logs($limit = 1000) {
        $this->db->order_by('created_at', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        return $this->db->get($this->table)->result();
    }
}