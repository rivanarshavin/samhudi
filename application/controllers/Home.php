<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
    }

    public function index()
    {
        $data['families'] = [
            ['img' => 'family1.png', 'label' => 'Keluarga (a)', 'rot' => -10],
            ['img' => 'family2.png', 'label' => 'Keluarga (b)', 'rot' => 10],
            ['img' => 'family3.png', 'label' => 'Keluarga (c)', 'rot' => -5],
            ['img' => 'family4.png', 'label' => 'Keluarga (d)', 'rot' => 8],
            ['img' => 'family5.png', 'label' => 'Keluarga (e)', 'rot' => -13],
            ['img' => 'family6.png', 'label' => 'Keluarga (f)', 'rot' => 7],
            ['img' => 'family7.png', 'label' => 'Keluarga (g)', 'rot' => -4],
        ];

        // Ambil berita highlight (is_highlight=1, status publish)
        $highlighted = $this->Admin_model->get_highlighted_news();

        // Ambil berita dari database (status publish, urutkan terbaru, maksimal 5 untuk layout grid)
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(5);
        $data['news_items'] = $this->db->get_where('news', ['status' => 'publish'])->result_array();
        $data['highlighted_news'] = $highlighted;

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('home/hero');
        $this->load->view('home/carousel', ['families' => $data['families']]);
        $this->load->view('home/sambutan');
        $this->load->view('home/intro');
        $this->load->view('home/berita', ['news_items' => $data['news_items'], 'highlighted_news' => $highlighted]);
        $this->load->view('home/lokasi_pemakaman');
        $this->load->view('templates/footer');
    }

    /**
     * Halaman daftar semua berita
     */
    public function berita($page = 0)
    {
        $per_page    = 10;
        $offset      = (int) $this->uri->segment(2, 0); // segment 2 = offset angka

        $total       = $this->Admin_model->count_published_news();
        $news_items  = $this->Admin_model->get_all_published_news($per_page, $offset);
        $other_news  = $this->Admin_model->get_other_news(0, 5);

        // Konfigurasi Pagination CI
        $this->load->library('pagination');
        $config = [
            'base_url'             => base_url('berita'),
            'total_rows'           => $total,
            'per_page'             => $per_page,
            'uri_segment'          => 2,
            'use_page_numbers'     => FALSE,
            'reuse_query_string'   => FALSE,
            // Wrapper & tag
            'full_tag_open'        => '<nav aria-label="Pagination berita"><ul class="pagination custom-pagination justify-content-center mt-4">',
            'full_tag_close'       => '</ul></nav>',
            'first_tag_open'       => '<li class="page-item">',
            'first_tag_close'      => '</li>',
            'last_tag_open'        => '<li class="page-item">',
            'last_tag_close'       => '</li>',
            'next_tag_open'        => '<li class="page-item">',
            'next_tag_close'       => '</li>',
            'prev_tag_open'        => '<li class="page-item">',
            'prev_tag_close'       => '</li>',
            'cur_tag_open'         => '<li class="page-item active"><a class="page-link" href="#">',
            'cur_tag_close'        => '</a></li>',
            'num_tag_open'         => '<li class="page-item">',
            'num_tag_close'        => '</li>',
            'attributes'           => ['class' => 'page-link'],
            'first_link'           => '&laquo;',
            'last_link'            => '&raquo;',
            'next_link'            => '&rsaquo;',
            'prev_link'            => '&lsaquo;',
        ];
        $this->pagination->initialize($config);

        $data['news_items']       = $news_items;
        $data['other_news']       = $other_news;
        $data['pagination_links'] = $this->pagination->create_links();
        $data['total_news']       = $total;
        $data['current_offset']   = $offset;
        $data['per_page']         = $per_page;
        $data['highlighted_news'] = $this->Admin_model->get_highlighted_news();

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('home/berita_list', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Halaman detail berita berdasarkan slug
     */
    public function berita_detail($slug = '')
    {
        if (empty($slug)) {
            redirect('berita');
            return;
        }

        $news = $this->Admin_model->get_news_by_slug($slug);
        if (!$news) {
            show_404();
            return;
        }

        // Increment view count
        $this->Admin_model->increment_news_views($news['id']);
        $news['views'] = ($news['views'] ?? 0) + 1;

        $other_news = $this->Admin_model->get_other_news($news['id'], 4);

        // Check if logged-in user has liked this news
        $user_id = $this->session->userdata('user_id');
        $is_liked = false;
        if ($user_id) {
            $liked_row = $this->db->get_where('news_likes', [
                'news_id' => $news['id'],
                'user_id' => $user_id
            ])->row();
            $is_liked = !empty($liked_row);
        }

        $data['news']       = $news;
        $data['other_news'] = $other_news;
        $data['is_liked']   = $is_liked;

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('home/berita_detail', $data);
        $this->load->view('templates/footer');
    }

    /**
     * API Endpoint untuk Like/Unlike berita (AJAX)
     */
    public function like_berita($id)
    {
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Login required']));
            return;
        }

        // Use toggle_news_like which properly inserts/deletes from news_likes table
        $result = $this->Admin_model->toggle_news_like($id, $user_id);

        $news = $this->Admin_model->get_news_by_id($id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'action' => $result, // 'like' or 'unlike'
                'likes'  => $news['likes']
            ]));
    }
}
