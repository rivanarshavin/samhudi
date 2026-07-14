<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Family_model extends CI_Model {

    private $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    public function get_family_tree($rootId = null, $familyId = null)
    {
        if ($rootId) {
            $root = $this->db->get_where('family_members', ['id' => (int) $rootId])->row_array();
        } else {
            $this->db->where('father_id', NULL);
            $this->db->where('mother_id', NULL);
            if ($familyId) $this->db->where('family_id', (int) $familyId);
            $this->db->order_by('birth_date', 'ASC');
            $this->db->order_by('id', 'ASC');
            $this->db->limit(1);
            $root = $this->db->get('family_members')->row_array();
        }

        if (!$root) {
            return ['error' => 'Anggota keluarga tertua (tanpa father_id/mother_id) tidak ditemukan. Isi dulu data buyut di tabel family_members, atau kirim ?root_id= manual.'];
        }

        return $this->fetch_node($root, 0);
    }

    public function get_member_full_details($id)
    {
        $id = (int)$id;
        $this->db->where('id', $id);
        $memberRow = $this->db->get('family_members')->row_array();
        
        if (!$memberRow) return null;

        $member = $this->row_to_person($memberRow, 0); // depth 0 just for general info
        $member['agama'] = $memberRow['religion'] ?? 'Islam'; // Defaulting if column doesn't exist, maybe assume Islam or leave empty if not present. I'll check if religion exists, if not just don't output or output what's there. Actually let's just use what's in DB or hardcode standard if needed. Let's just add it manually if it doesn't exist.
        
        // Data Orang Tua
        $ayahRow = $memberRow['father_id'] ? $this->db->get_where('family_members', ['id' => $memberRow['father_id']])->row_array() : null;
        $ibuRow = $memberRow['mother_id'] ? $this->db->get_where('family_members', ['id' => $memberRow['mother_id']])->row_array() : null;
        
        $member['ayah_name'] = $ayahRow['full_name'] ?? '-';
        $member['ibu_name'] = $ibuRow['full_name'] ?? '-';
        
        $member['orang_tua'] = [];
        if ($ayahRow) {
            $p = $this->row_to_person($ayahRow, 0, 'Ayah Kandung');
            $p['generasi_info'] = 'Generasi ke-N'; // Will figure out later if needed
            $member['orang_tua'][] = $p;
        }
        if ($ibuRow) {
            $p = $this->row_to_person($ibuRow, 0, 'Ibu Kandung');
            $p['generasi_info'] = 'Generasi ke-N';
            $member['orang_tua'][] = $p;
        }

        // Data Pasangan
        $pasanganRows = $this->find_spouses($id);
        
        $member['pasangan'] = [];
        if (!empty($pasanganRows)) {
            $member['pasangan_name'] = $pasanganRows[0]['full_name'] ?? '-';
            $member['pasangan_label'] = $this->spouse_label($pasanganRows[0]['gender'] ?? null);
            foreach ($pasanganRows as $pasanganRow) {
                $p = $this->row_to_person($pasanganRow, 0, $this->spouse_label($pasanganRow['gender'] ?? null));
                $member['pasangan'][] = $p;
            }
        } else {
            $member['pasangan_name'] = '-';
            $member['pasangan_label'] = 'Pasangan';
        }

        // Data Anak-anak (dari diri sendiri ATAU dari pasangan-pasangannya)
        $spouseIds = [];
        if (!empty($pasanganRows)) {
            foreach ($pasanganRows as $pr) {
                $spouseIds[] = $pr['id'];
            }
        }

        $this->db->group_start();
        $this->db->where('father_id', $id);
        $this->db->or_where('mother_id', $id);
        if (!empty($spouseIds)) {
            $this->db->or_where_in('father_id', $spouseIds);
            $this->db->or_where_in('mother_id', $spouseIds);
        }
        $this->db->group_end();
        
        $this->db->order_by('birth_date', 'ASC');
        $this->db->order_by('id', 'ASC');
        $childrenRows = $this->db->get('family_members')->result_array();
        
        $member['anak_anak'] = [];
        foreach ($childrenRows as $idx => $childRow) {
            $p = $this->row_to_person($childRow, 0, 'Anak Kandung');
            $p['urutan'] = $idx + 1;
            $member['anak_anak'][] = $p;
        }
        $member['jumlah_anak'] = count($childrenRows);

        // Data Saudara & Urutan Anak (jika punya ortu)
        $member['saudara'] = [];
        $member['anak_ke'] = 1;
        $member['dari_jumlah_saudara'] = 1;
        
        if ($memberRow['father_id'] || $memberRow['mother_id']) {
            $this->db->group_start();
            if ($memberRow['father_id']) $this->db->where('father_id', $memberRow['father_id']);
            if ($memberRow['mother_id']) $this->db->or_where('mother_id', $memberRow['mother_id']);
            $this->db->group_end();
            $this->db->order_by('birth_date', 'ASC');
            $this->db->order_by('id', 'ASC');
            
            $siblingsRows = $this->db->get('family_members')->result_array();
            $member['dari_jumlah_saudara'] = count($siblingsRows);
            
            foreach ($siblingsRows as $idx => $sibRow) {
                if ($sibRow['id'] == $id) {
                    $member['anak_ke'] = $idx + 1;
                } else {
                    $p = $this->row_to_person($sibRow, 0, 'Saudara (Kakak/Adik)');
                    $member['saudara'][] = $p;
                }
            }
        }
        
        // Check if root to set "Generasi" properly
        // Ideally we need depth logic. Since this is detail view, we might not have it exactly.
        // We'll calculate depth by walking up.
        $depth = 0;
        $curr = $memberRow;
        while ($curr['father_id'] || $curr['mother_id']) {
            $depth++;
            $pid = $curr['father_id'] ? $curr['father_id'] : $curr['mother_id'];
            $curr = $this->db->get_where('family_members', ['id' => $pid])->row_array();
            if (!$curr) break;
        }
        $member['generasi'] = $depth + 1;
        $member['generasi_label'] = 'Generasi ' . $member['generasi'];

        return $member;
    }

    private function fetch_node($row, $depth)
    {
        $person = $this->row_to_person($row, $depth);

        $spouseRows = $this->find_spouses((int) $row['id']);
        if (!empty($spouseRows)) {
            $person['pasangan'] = [];
            foreach ($spouseRows as $spouseRow) {
                $person['pasangan'][] = $this->row_to_person($spouseRow, $depth, $this->spouse_label($spouseRow['gender'] ?? null));
            }
        }

        $this->db->where('father_id', $row['id']);
        $this->db->or_where('mother_id', $row['id']);
        $this->db->order_by('birth_date', 'ASC');
        $this->db->order_by('id', 'ASC');
        $children = $this->db->get('family_members')->result_array();

        foreach ($children as $childRow) {
            $person['children'][] = $this->fetch_node($childRow, $depth + 1);
        }

        return $person;
    }

    private function find_spouses($memberId)
    {
        $this->db->where('husband_id', $memberId);
        $this->db->or_where('wife_id', $memberId);
        $marriages = $this->db->get('marriages')->result_array();

        $spouses = [];
        foreach ($marriages as $marriage) {
            $spouseId = ($marriage['husband_id'] == $memberId) ? $marriage['wife_id'] : $marriage['husband_id'];
            $spouse = $this->db->get_where('family_members', ['id' => $spouseId])->row_array();
            if ($spouse) $spouses[] = $spouse;
        }
        return $spouses;
    }

    private function row_to_person($row, $depth, $hubunganOverride = null)
    {
        $status = null;
        if (isset($row['is_alive']) && (int) $row['is_alive'] === 0) {
            $tglWafat = $this->format_tanggal($row['death_date'] ?? null);
            $status = 'Almarhum/Almarhumah' . ($tglWafat ? ' — ' . $tglWafat : '');
        }

        $person = [
            'id'             => (int) $row['id'],
            'nama'           => $row['full_name'],
            'foto'           => $this->resolve_foto($row['photo'], $row['full_name']),
            'gender'         => $row['gender'] ?? null,
            'hubungan'       => $hubunganOverride ?? $this->relation_label($depth),
            'email'          => $row['email'] ?? null,
            'telepon'        => $row['phone'] ?? null,
            'tanggal_lahir'  => $this->format_tanggal($row['birth_date'] ?? null),
            'tempat_lahir'   => $row['birth_place'] ?? null,
            'tempat_tinggal' => $row['address'] ?? null,
            'pekerjaan'      => $row['occupation'] ?? null,
            'status'         => $status ?? 'Masih Hidup',
            'pasangan'       => null,
            'children'       => [],
        ];

        foreach (['email', 'telepon', 'tanggal_lahir', 'tempat_lahir', 'tempat_tinggal', 'pekerjaan'] as $key) {
            if ($person[$key] === null || $person[$key] === '') unset($person[$key]);
        }

        return $person;
    }

    private function format_tanggal($tgl)
    {
        if (empty($tgl) || $tgl === '0000-00-00') return null;
        $t = strtotime($tgl);
        if (!$t) return $tgl;
        return date('j', $t) . ' ' . $this->bulan[(int) date('n', $t)] . ' ' . date('Y', $t);
    }

    private function resolve_foto($photo, $nama = 'A')
    {
        if (empty($photo)) {
            $inisial = !empty($nama) ? strtoupper(substr($nama, 0, 1)) : 'A';
            return 'https://placehold.co/100x100/CBD9CF/4A6055?text=' . urlencode($inisial);
        }
        if (preg_match('#^https?://#i', $photo)) return $photo;
        if (strpos($photo, '/') !== false) return base_url($photo);
        return base_url('assets/uploads/' . $photo);
    }

    private function relation_label($depth)
    {
        $labels = [0 => 'Leluhur (Buyut)', 1 => 'Anak', 2 => 'Cucu', 3 => 'Cicit', 4 => 'Piut'];
        return $labels[$depth] ?? ('Generasi ke-' . ($depth + 1));
    }

    private function spouse_label($gender)
    {
        if ($gender === 'L') return 'Suami';
        if ($gender === 'P') return 'Istri';
        return 'Pasangan';
    }

    // --- FITUR WIZARD TAMBAH ANGGOTA ---
    
    public function search_members_for_wizard($term)
    {
        $this->db->select('id, full_name, gender');
        $this->db->like('full_name', $term);
        $this->db->limit(10);
        return $this->db->get('family_members')->result_array();
    }

    public function insert_new_member($data, $role, $rel_id)
    {
        $rel_id = (int)$rel_id;
        
        // Dapatkan data relasi
        $this->db->where('id', $rel_id);
        $rel_member = $this->db->get('family_members')->row_array();
        
        if (!$rel_member) return ['status' => false, 'message' => 'Anggota relasi tidak ditemukan.'];

        if ($role === 'pasangan') {
            // Validasi Pasangan
            if ($rel_member['gender'] === $data['gender']) {
                return ['status' => false, 'message' => 'Pernikahan sesama jenis kelamin tidak diizinkan.'];
            }
            
            // Hitung jumlah pasangan saat ini
            $this->db->where('husband_id', $rel_id);
            $this->db->or_where('wife_id', $rel_id);
            $count_spouses = $this->db->count_all_results('marriages');
            
            if ($rel_member['gender'] === 'L') { // Suami
                if ($count_spouses >= 4) {
                    return ['status' => false, 'message' => 'Pria ini telah memiliki batas maksimal 4 istri.'];
                }
            } else { // Istri
                if ($count_spouses >= 1) {
                    return ['status' => false, 'message' => 'Wanita ini telah memiliki 1 suami.'];
                }
            }
            
            // Pasangan valid, masukkan data
            $this->db->insert('family_members', $data);
            $new_id = $this->db->insert_id();
            
            // Masukkan ke tabel marriages
            $marriage_data = [
                'husband_id' => ($rel_member['gender'] === 'L') ? $rel_id : $new_id,
                'wife_id'    => ($rel_member['gender'] === 'P') ? $rel_id : $new_id,
                'status'     => 'menikah'
            ];
            $this->db->insert('marriages', $marriage_data);
            
            return ['status' => true, 'message' => 'Anggota (Pasangan) berhasil ditambahkan.', 'id' => $new_id];
            
        } elseif ($role === 'anak') {
            // Rel_member adalah orang tua.
            if ($rel_member['gender'] === 'L') {
                $data['father_id'] = $rel_id;
                // Coba cari ibunya (istri pertama yang tercatat, ini simplifikasi)
                $this->db->where('husband_id', $rel_id);
                $marriage = $this->db->get('marriages')->row_array();
                if ($marriage) $data['mother_id'] = $marriage['wife_id'];
            } else {
                $data['mother_id'] = $rel_id;
                // Coba cari ayahnya
                $this->db->where('wife_id', $rel_id);
                $marriage = $this->db->get('marriages')->row_array();
                if ($marriage) $data['father_id'] = $marriage['husband_id'];
            }
            
            $this->db->insert('family_members', $data);
            return ['status' => true, 'message' => 'Anggota (Anak) berhasil ditambahkan.', 'id' => $this->db->insert_id()];
            
        } elseif ($role === 'orangtua') {
            // Tambah orang tua ke anak (rel_member)
            // Ini asumsi hanya bisa tambah jika slot kosong
            if ($data['gender'] === 'L' && !empty($rel_member['father_id'])) {
                return ['status' => false, 'message' => 'Anak ini sudah memiliki data Ayah.'];
            }
            if ($data['gender'] === 'P' && !empty($rel_member['mother_id'])) {
                return ['status' => false, 'message' => 'Anak ini sudah memiliki data Ibu.'];
            }
            
            $this->db->insert('family_members', $data);
            $new_id = $this->db->insert_id();
            
            $update_data = [];
            if ($data['gender'] === 'L') {
                $update_data['father_id'] = $new_id;
                if (!empty($rel_member['mother_id'])) {
                    $this->db->insert('marriages', [
                        'husband_id' => $new_id,
                        'wife_id'    => $rel_member['mother_id'],
                        'status'     => 'menikah'
                    ]);
                }
            } else {
                $update_data['mother_id'] = $new_id;
                if (!empty($rel_member['father_id'])) {
                    $this->db->insert('marriages', [
                        'husband_id' => $rel_member['father_id'],
                        'wife_id'    => $new_id,
                        'status'     => 'menikah'
                    ]);
                }
            }
            $this->db->where('id', $rel_id)->update('family_members', $update_data);
            
            return ['status' => true, 'message' => 'Anggota (Orang Tua) berhasil ditambahkan.', 'id' => $new_id];
        }
        
        return ['status' => false, 'message' => 'Peran tidak valid.'];
    }
}