<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berita extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->library('pagination');

        $limit  = 8; // jumlah berita per halaman
        $page   = ($this->input->get('page')) ? (int) $this->input->get('page') : 1;
        $offset = ($page - 1) * $limit;

        $total_rows = $this->db->where('status', 'publish')->count_all_results('news');

        // ===== Konfigurasi Pagination =====
        $config['base_url']             = base_url('berita');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $limit;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers']     = TRUE;
        $config['reuse_query_string']   = TRUE;

        $config['full_tag_open']   = '<ul class="pagination custom-pagination justify-content-center mt-4">';
        $config['full_tag_close']  = '</ul>';
        $config['first_link']      = 'Awal';
        $config['first_tag_open']  = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link']       = 'Akhir';
        $config['last_tag_open']   = '<li class="page-item">';
        $config['last_tag_close']  = '</li>';
        $config['next_link']       = '<i class="bi bi-chevron-right"></i>';
        $config['next_tag_open']   = '<li class="page-item">';
        $config['next_tag_close']  = '</li>';
        $config['prev_link']       = '<i class="bi bi-chevron-left"></i>';
        $config['prev_tag_open']   = '<li class="page-item">';
        $config['prev_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']   = '</span></li>';
        $config['num_tag_open']    = '<li class="page-item">';
        $config['num_tag_close']   = '</li>';
        $config['attributes']      = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        // ===== Ambil Daftar Berita (sesuai halaman aktif) =====
        $this->db->order_by('created_at', 'DESC');
        $data['news_items'] = $this->db->get_where('news', ['status' => 'publish'], $limit, $offset)->result_array();

        // ===== Berita Lainnya untuk Sidebar =====
        $data['other_news'] = $this->db->order_by('created_at', 'DESC')
                                        ->limit(5)
                                        ->get_where('news', ['status' => 'publish'])
                                        ->result_array();

        $data['pagination_links'] = $this->pagination->create_links();
        $data['total_rows']       = $total_rows;

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('berita/index', $data);
        $this->load->view('templates/footer');
    }
}