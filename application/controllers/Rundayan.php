<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rundayan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
        $this->load->model('Admin_model');
    }

    public function index()
    {
        $search = $this->input->get('search', TRUE);
        if ($search) {
            $this->db->group_start();
            $this->db->like('candidate_name', $search);
            $this->db->or_like('nominator_name', $search);
            $this->db->or_like('ancestor_name', $search);
            $this->db->group_end();
        }
        
        $this->db->where('status', 'approved');
        $this->db->where('type', 'rundayan');
        $raw_candidates = $this->db->get('yayasan_candidates')->result_array();
        $data['search'] = $search;

        // Group candidates by name
        $grouped = [];
        foreach ($raw_candidates as $c) {
            $key = strtolower(trim($c['candidate_name']));
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'id'             => $c['id'],
                    'candidate_name' => $c['candidate_name'],
                    'ancestor_name'  => $c['ancestor_name'],
                    'type'           => 'rundayan',
                    'nominators'     => [trim($c['nominator_name'])],
                    'ancestors'      => [trim($c['ancestor_name'])],
                    'votes_count'    => 1,
                    'ancestor_breakdown' => [trim($c['ancestor_name']) => 1],
                    'roles'          => [trim($c['description'])]
                ];
            } else {
                $grouped[$key]['nominators'][] = trim($c['nominator_name']);
                $grouped[$key]['ancestors'][] = trim($c['ancestor_name']);
                $grouped[$key]['votes_count'] += 1;
                
                $anc = trim($c['ancestor_name']);
                if (!isset($grouped[$key]['ancestor_breakdown'][$anc])) {
                    $grouped[$key]['ancestor_breakdown'][$anc] = 1;
                } else {
                    $grouped[$key]['ancestor_breakdown'][$anc] += 1;
                }
                $grouped[$key]['roles'][] = trim($c['description']);
            }
        }

        $candidates = [];
        foreach ($grouped as $g) {
            $g['nominator_name'] = implode(', ', array_unique($g['nominators']));
            $g['ancestor_name'] = implode(', ', array_unique($g['ancestors']));
            
            $unique_roles = array_filter(array_unique($g['roles']));
            $g['roles_text'] = !empty($unique_roles) ? implode(', ', $unique_roles) : '-';
            
            $breakdowns = [];
            foreach ($g['ancestor_breakdown'] as $anc_name => $count) {
                $breakdowns[] = htmlspecialchars($anc_name) . " (" . $count . " suara)";
            }
            $g['breakdown_text'] = implode(', ', $breakdowns);
            $candidates[] = $g;
        }

        // Sort by votes_count DESC
        usort($candidates, function($a, $b) {
            return $b['votes_count'] <=> $a['votes_count'];
        });

        $data['candidates'] = $candidates;
        $data['page_type'] = 'rundayan';

        // Determine if user is Dewan Pembina, admin, super_admin, or "Teguh" (administrator)
        $is_authorized = false;
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            $username = strtolower($this->session->userdata('username') ?? '');
            $full_name = strtolower($this->session->userdata('full_name') ?? '');
            if (in_array($role, ['admin', 'super_admin', 'dewan_pembina']) || strpos($username, 'teguh') !== false || strpos($full_name, 'teguh') !== false) {
                $is_authorized = true;
            }
        }
        $data['is_authorized'] = $is_authorized;

        $voted_cookie = $this->input->cookie('voted_candidates');
        $data['voted_ids'] = $voted_cookie ? explode(',', $voted_cookie) : [];

        $data['ancestors'] = $this->db->select('DISTINCT(ancestor_name)')->get_where('yayasan_candidates', ['status' => 'approved', 'type' => 'rundayan'])->result_array();

        $cands = $this->db->select('candidate_name AS name')->get_where('yayasan_candidates', ['status' => 'approved', 'type' => 'rundayan'])->result_array();
        $noms = $this->db->select('nominator_name AS name')->get_where('yayasan_candidates', ['status' => 'approved', 'type' => 'rundayan'])->result_array();
        
        $merged_names = array_merge($cands, $noms);
        $unique_names = [];
        foreach ($merged_names as $row) {
            $cleaned = trim($row['name']);
            if (!empty($cleaned) && !in_array($cleaned, $unique_names)) {
                $unique_names[] = $cleaned;
            }
        }
        sort($unique_names);
        $data['all_names'] = $unique_names;

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('yayasan/index', $data);
        $this->load->view('templates/footer');
    }

    public function nominate()
    {
        $this->form_validation->set_rules('nominator_name', 'Nama Pencalon', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('ancestor_name', 'Undayan / Buyut', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('candidate_name_1', 'Nama yang Dicalonkan 1', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('candidate_name_2', 'Nama yang Dicalonkan 2', 'trim|max_length[255]');
        $this->form_validation->set_rules('candidate_name_3', 'Nama yang Dicalonkan 3', 'trim|max_length[255]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('rundayan');
        } else {
            $nominator = trim($this->input->post('nominator_name', TRUE));
            $ancestor = trim($this->input->post('ancestor_name', TRUE));
            
            $candidates_input = [];
            $raw_inputs = [];
            for ($i = 1; $i <= 3; $i++) {
                $cand = trim($this->input->post('candidate_name_' . $i, TRUE));
                if (!empty($cand)) {
                    $raw_inputs[] = strtolower($cand);
                    $candidates_input[] = $cand;
                }
            }

            if (empty($candidates_input)) {
                $this->session->set_flashdata('error', 'Silakan isi minimal 1 nama calon.');
                redirect('rundayan');
            }

            if (count(array_unique($raw_inputs)) < count($raw_inputs)) {
                $this->session->set_flashdata('error', 'Pencalonan dibatalkan. Nama calon formatur 1, 2, dan 3 tidak boleh sama.');
                redirect('rundayan');
            }

            $success_count = 0;
            $failed_names = [];

            $roles_map = [
                1 => 'Ketua',
                2 => 'Bendahara',
                3 => 'Sekretaris'
            ];

            for ($i = 1; $i <= 3; $i++) {
                $cand = trim($this->input->post('candidate_name_' . $i, TRUE));
                if (empty($cand)) {
                    continue;
                }

                $this->db->where('LOWER(nominator_name) =', strtolower($nominator));
                $this->db->where('LOWER(candidate_name) =', strtolower($cand));
                $existing = $this->db->get('yayasan_candidates')->row_array();

                if ($existing) {
                    $failed_names[] = $cand;
                    continue;
                }

                $insert_data = [
                    'nominator_name'  => $nominator,
                    'ancestor_name'   => $ancestor,
                    'type'            => 'rundayan',
                    'candidate_name'  => $cand,
                    'description'     => $roles_map[$i],
                    'votes_count'     => 1,
                    'status'          => 'approved'
                ];

                $this->db->insert('yayasan_candidates', $insert_data);
                $success_count++;
            }

            if ($success_count > 0) {
                $msg = $success_count . ' calon berhasil didaftarkan.';
                if (!empty($failed_names)) {
                    $msg .= ' Beberapa nama dilewati karena sudah diusulkan sebelumnya.';
                }
                $this->session->set_flashdata('success', $msg);
            } else {
                $this->session->set_flashdata('error', 'Gagal mendaftarkan calon. Semua nama calon yang Anda isi sudah diusulkan sebelumnya.');
            }
            redirect('rundayan');
        }
    }

    public function vote($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        
        $candidate = $this->db->get_where('yayasan_candidates', ['id' => $id, 'status' => 'approved', 'type' => 'rundayan'])->row_array();
        if (!$candidate) {
            echo json_encode(['status' => 'error', 'message' => 'Calon tidak ditemukan atau belum disetujui.']);
            return;
        }

        $ip = $this->input->ip_address();
        $ua = $this->input->user_agent();

        $voted_cookie = $this->input->cookie('voted_candidates');
        $voted_ids = $voted_cookie ? explode(',', $voted_cookie) : [];
        if (in_array($id, $voted_ids)) {
            echo json_encode(['status' => 'error', 'message' => 'Anda sudah memberikan suara untuk calon ini.']);
            return;
        }

        $this->db->where('candidate_id', $id);
        $this->db->where('ip_address', $ip);
        $this->db->where('voted_at >', date('Y-m-d H:i:s', time() - 86400));
        $log_exists = $this->db->get('yayasan_votes_log')->num_rows() > 0;

        if ($log_exists) {
            echo json_encode(['status' => 'error', 'message' => 'Anda sudah memberikan suara untuk calon ini hari ini.']);
            return;
        }

        $this->db->insert('yayasan_votes_log', [
            'candidate_id' => $id,
            'ip_address'   => $ip,
            'user_agent'   => $ua
        ]);

        $this->db->where('id', $id);
        $this->db->set('votes_count', 'votes_count+1', FALSE);
        $this->db->update('yayasan_candidates');

        $voted_ids[] = $id;
        $cookie_value = implode(',', $voted_ids);
        $this->input->set_cookie([
            'name'   => 'voted_candidates',
            'value'  => $cookie_value,
            'expire' => 30 * 86400,
            'secure' => FALSE
        ]);

        $updated_candidate = $this->db->get_where('yayasan_candidates', ['id' => $id])->row_array();

        echo json_encode([
            'status' => 'success',
            'message' => 'Terima kasih! Suara Anda telah berhasil direkam.',
            'votes_count' => $updated_candidate['votes_count']
        ]);
    }

    public function detail($id)
    {
        $candidate = $this->db->get_where('yayasan_candidates', ['id' => $id, 'status' => 'approved', 'type' => 'rundayan'])->row_array();
        if (!$candidate) {
            show_404();
            return;
        }

        $all_nominations = $this->db->get_where('yayasan_candidates', [
            'candidate_name' => $candidate['candidate_name'],
            'ancestor_name'  => $candidate['ancestor_name'],
            'status'         => 'approved',
            'type'           => 'rundayan'
        ])->result_array();

        $candidate['votes_count'] = count($all_nominations);

        $nominators = array_unique(array_filter(array_map('trim', array_column($all_nominations, 'nominator_name'))));
        $candidate['nominator_name'] = implode(', ', $nominators);

        $descriptions = array_unique(array_filter(array_map('trim', array_column($all_nominations, 'description'))));
        $candidate['description'] = implode(' / ', $descriptions);

        $data['candidate'] = $candidate;

        $is_authorized = false;
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            $username = strtolower($this->session->userdata('username') ?? '');
            $full_name = strtolower($this->session->userdata('full_name') ?? '');
            if (in_array($role, ['admin', 'super_admin', 'dewan_pembina']) || strpos($username, 'teguh') !== false || strpos($full_name, 'teguh') !== false) {
                $is_authorized = true;
            }
        }
        $data['is_authorized'] = $is_authorized;
        
        $voted_cookie = $this->input->cookie('voted_candidates');
        $data['voted_ids'] = $voted_cookie ? explode(',', $voted_cookie) : [];
        $data['page_type'] = 'rundayan';

        $parent_chain = [];
        $visited = [];
        
        foreach ($nominators as $nominator) {
            $current_nominator = $nominator;
            $sub_chain = [];
            while (!empty($current_nominator)) {
                $parent = $this->db->get_where('yayasan_candidates', [
                    'candidate_name' => $current_nominator,
                    'status'         => 'approved',
                    'type'           => 'rundayan'
                ])->row_array();
                
                if ($parent) {
                    if (!in_array($parent['id'], $visited)) {
                        $sub_chain[] = $parent;
                        $visited[] = $parent['id'];
                        $current_nominator = $parent['nominator_name'];
                    } else {
                        break;
                    }
                } else {
                    $virtual_key = 'virtual_' . strtolower(trim($current_nominator));
                    if (!in_array($virtual_key, $visited)) {
                        $sub_chain[] = [
                            'id'             => null,
                            'candidate_name' => $current_nominator,
                            'nominator_name' => '',
                            'ancestor_name'  => $candidate['ancestor_name'],
                            'virtual'        => true
                        ];
                        $visited[] = $virtual_key;
                    }
                    break;
                }
            }
            $parent_chain = array_merge($parent_chain, array_reverse($sub_chain));
        }
        $data['parent_chain'] = $parent_chain;

        $data['nominated_by_this'] = $this->db->get_where('yayasan_candidates', [
            'nominator_name' => $candidate['candidate_name'],
            'status'         => 'approved',
            'type'           => 'rundayan'
        ])->result_array();

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('yayasan/detail', $data);
        $this->load->view('templates/footer');
    }
}
