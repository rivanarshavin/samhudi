<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yayasan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'html']);
    }

    public function index()
    {
        redirect('yayasan/rekapitulasi');
    }

    public function rekapitulasi()
    {
        // Fetch approved candidates for recap
        $this->db->where('status', 'approved');
        $raw_approved = $this->db->get('yayasan_candidates')->result_array();

        $grouped = [];
        $rundayan_detail_map = [];

        foreach ($raw_approved as $c) {
            $key = strtolower(trim($c['candidate_name']));
            $nom = trim($c['nominator_name']);
            $anc = trim($c['ancestor_name']);
            
            // Clean role resolution
            $r_raw = trim($c['description']);
            if (preg_match('/bendahara/i', $r_raw)) {
                $role = 'Bendahara';
            } elseif (preg_match('/sekretaris/i', $r_raw)) {
                $role = 'Sekretaris';
            } else {
                $role = 'Ketua';
            }

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'id'             => $c['id'],
                    'candidate_name' => $c['candidate_name'],
                    'ancestor_name'  => $c['ancestor_name'],
                    'type'           => $c['type'] ?? 'individu',
                    'nominators'     => [$nom],
                    'ancestors'      => [$anc],
                    'votes_count'    => 1,
                    'ancestor_breakdown' => [$anc => 1],
                    'roles'          => [$role],
                    'created_at'     => $c['created_at']
                ];
            } else {
                $grouped[$key]['nominators'][] = $nom;
                $grouped[$key]['ancestors'][]  = $anc;
                $grouped[$key]['votes_count'] += 1;
                
                if (!isset($grouped[$key]['ancestor_breakdown'][$anc])) {
                    $grouped[$key]['ancestor_breakdown'][$anc] = 1;
                } else {
                    $grouped[$key]['ancestor_breakdown'][$anc] += 1;
                }
                $grouped[$key]['roles'][] = $role;
            }

            // Map detail per rundayan for Hover feature
            if (!isset($rundayan_detail_map[$anc])) {
                $rundayan_detail_map[$anc] = [
                    'ancestor_name' => $anc,
                    'nominators'    => [],
                    'candidates'    => [],
                    'total_votes'   => 0
                ];
            }
            $rundayan_detail_map[$anc]['nominators'][] = $nom;
            $rundayan_detail_map[$anc]['candidates'][] = $c['candidate_name'];
            $rundayan_detail_map[$anc]['total_votes'] += 1;
        }

        // Clean & unique detail map per rundayan
        foreach ($rundayan_detail_map as $anc_key => $data) {
            $rundayan_detail_map[$anc_key]['nominators'] = array_values(array_unique($data['nominators']));
            $rundayan_detail_map[$anc_key]['candidates'] = array_values(array_unique($data['candidates']));
        }

        $individu_candidates = [];
        $rundayan_candidates = [];

        foreach ($grouped as $key => $g) {
            $g['nominator_name'] = implode(', ', array_unique($g['nominators']));
            $g['ancestor_name']  = implode(', ', array_unique($g['ancestors']));
            
            $unique_roles = array_values(array_unique($g['roles']));
            $g['roles_text'] = !empty($unique_roles) ? implode(', ', $unique_roles) : 'Ketua';
            
            $breakdowns = [];
            foreach ($g['ancestor_breakdown'] as $anc_name => $count) {
                $breakdowns[] = htmlspecialchars($anc_name) . " (" . $count . " suara)";
            }
            $g['breakdown_text'] = implode(', ', $breakdowns);

            if ($g['type'] === 'rundayan') {
                $rundayan_candidates[] = $g;
            } else {
                $individu_candidates[] = $g;
            }
        }

        usort($individu_candidates, function($a, $b) {
            return $b['votes_count'] <=> $a['votes_count'];
        });
        usort($rundayan_candidates, function($a, $b) {
            return $b['votes_count'] <=> $a['votes_count'];
        });

        // Search filters
        $search_individu = $this->input->get('search_individu', TRUE) ?? '';
        if (!empty($search_individu)) {
            $individu_candidates = array_values(array_filter($individu_candidates, function($c) use ($search_individu) {
                return stripos($c['candidate_name'], $search_individu) !== false ||
                       stripos($c['nominator_name'], $search_individu) !== false ||
                       stripos($c['ancestor_name'], $search_individu) !== false;
            }));
        }

        $search_rundayan = $this->input->get('search_rundayan', TRUE) ?? '';
        if (!empty($search_rundayan)) {
            $rundayan_candidates = array_values(array_filter($rundayan_candidates, function($c) use ($search_rundayan) {
                return stripos($c['candidate_name'], $search_rundayan) !== false ||
                       stripos($c['nominator_name'], $search_rundayan) !== false ||
                       stripos($c['ancestor_name'], $search_rundayan) !== false;
            }));
        }

        // Pagination for Individu Cards (3 per page for mobile optimization)
        $total_cards_individu = count($individu_candidates);
        $limit_cards_individu = 3;
        $page_card_individu   = $this->input->get('page_card_individu') ? (int) $this->input->get('page_card_individu') : 1;
        $offset_card_individu = ($page_card_individu - 1) * $limit_cards_individu;
        $individu_cards_paginated = array_slice($individu_candidates, $offset_card_individu, $limit_cards_individu);

        // Pagination for Rundayan Cards (3 per page for mobile optimization)
        $total_cards_rundayan = count($rundayan_candidates);
        $limit_cards_rundayan = 3;
        $page_card_rundayan   = $this->input->get('page_card_rundayan') ? (int) $this->input->get('page_card_rundayan') : 1;
        $offset_card_rundayan = ($page_card_rundayan - 1) * $limit_cards_rundayan;
        $rundayan_cards_paginated = array_slice($rundayan_candidates, $offset_card_rundayan, $limit_cards_rundayan);

        $search_bagan = $this->input->get('search_bagan', TRUE) ?? '';
        $approved_filtered = $raw_approved;
        if (!empty($search_bagan)) {
            $approved_filtered = array_filter($raw_approved, function($c) use ($search_bagan) {
                return stripos($c['candidate_name'], $search_bagan) !== false ||
                       stripos($c['nominator_name'], $search_bagan) !== false ||
                       stripos($c['ancestor_name'], $search_bagan) !== false;
            });
        }

        // Data for 3D Pie Chart
        $chart_data_individu = [];
        foreach ($individu_candidates as $c) {
            $chart_data_individu[] = [
                'name'       => $c['candidate_name'],
                'y'          => (int) $c['votes_count'],
                'nominators' => $c['nominator_name'],
                'ancestors'  => $c['ancestor_name'],
                'breakdown'  => $c['breakdown_text']
            ];
        }

        $chart_data_rundayan = [];
        foreach ($rundayan_candidates as $c) {
            $chart_data_rundayan[] = [
                'name'       => $c['candidate_name'],
                'y'          => (int) $c['votes_count'],
                'nominators' => $c['nominator_name'],
                'ancestors'  => $c['ancestor_name'],
                'breakdown'  => $c['breakdown_text']
            ];
        }

        // Fetch all distinct candidate names, nominator names, and ancestor names for autocomplete suggestions
        $noms = $this->db->select('nominator_name as name')->get('yayasan_candidates')->result_array();
        $cands = $this->db->select('candidate_name as name')->get('yayasan_candidates')->result_array();
        $ancs = $this->db->select('ancestor_name as name')->get('yayasan_candidates')->result_array();
        
        $all_names_list = [];
        foreach (array_merge($noms, $cands, $ancs) as $r) {
            if (!empty($r['name'])) {
                $all_names_list[] = trim($r['name']);
            }
        }
        $all_names = array_values(array_unique($all_names_list));

        $data = [
            'page_title'            => 'Rekapitulasi Pemilihan Ketua Yayasan - Dewan Pembina',
            'candidates'            => $raw_approved,
            'approved_candidates'   => $approved_filtered,
            'individu_candidates'   => $individu_candidates,
            'rundayan_candidates'   => $rundayan_candidates,
            
            // Paginated Cards
            'individu_cards'        => $individu_cards_paginated,
            'total_cards_individu'  => $total_cards_individu,
            'limit_cards_individu'  => $limit_cards_individu,
            'page_card_individu'   => $page_card_individu,

            'rundayan_cards'        => $rundayan_cards_paginated,
            'total_cards_rundayan'  => $total_cards_rundayan,
            'limit_cards_rundayan'  => $limit_cards_rundayan,
            'page_card_rundayan'   => $page_card_rundayan,

            'search_individu'       => $search_individu,
            'search_rundayan'       => $search_rundayan,
            'search_bagan'          => $search_bagan,
            'all_names'             => $all_names,
            'chart_data_individu'   => $chart_data_individu,
            'chart_data_rundayan'   => $chart_data_rundayan,
            'rundayan_detail_map'   => $rundayan_detail_map
        ];

        $this->load->view('yayasan/rekapitulasi', $data);
    }
}
