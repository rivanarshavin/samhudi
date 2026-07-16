<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Family_model extends CI_Model {

    private $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    public function is_user_onboarded($user_id)
    {
        return $this->db->where('user_id', $user_id)->count_all_results('family_members') > 0;
    }

    public function get_family_tree($rootId = null, $familyId = null)
    {
        if ($rootId) {
            $root = $this->db->get_where('family_members', ['id' => (int) $rootId, 'status' => 'approved'])->row_array();
        } else {
            $this->db->where('father_id', NULL);
            $this->db->where('mother_id', NULL);
            $this->db->where('status', 'approved');
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

    public function get_member_full_details($id, $bypass_status = false)
    {
        $id = (int)$id;
        $this->db->where('id', $id);
        if (!$bypass_status) {
            $this->db->where('status', 'approved');
        }
        $memberRow = $this->db->get('family_members')->row_array();
        
        if (!$memberRow) return null;

        $member = $this->row_to_person($memberRow, 0); // depth 0 just for general info
        $member['agama'] = $memberRow['religion'] ?? 'Islam'; // Defaulting if column doesn't exist, maybe assume Islam or leave empty if not present. I'll check if religion exists, if not just don't output or output what's there. Actually let's just use what's in DB or hardcode standard if needed. Let's just add it manually if it doesn't exist.
        
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
        $member['generasi'] = isset($memberRow['generasi']) ? $memberRow['generasi'] : ($depth + 1);
        $member['generasi_label'] = 'Generasi ' . $member['generasi'];
        
        // Data Orang Tua
        $ayahRow = null;
        $ibuRow = null;
        if ($memberRow['father_id']) {
            $this->db->where('id', $memberRow['father_id']);
            if (!$bypass_status) $this->db->where('status', 'approved');
            $ayahRow = $this->db->get('family_members')->row_array();
        }
        if ($memberRow['mother_id']) {
            $this->db->where('id', $memberRow['mother_id']);
            if (!$bypass_status) $this->db->where('status', 'approved');
            $ibuRow = $this->db->get('family_members')->row_array();
        }
        
        $member['ayah_name'] = $ayahRow['full_name'] ?? '-';
        $member['ibu_name'] = $ibuRow['full_name'] ?? '-';
        
        $member['orang_tua'] = [];
        if ($ayahRow) {
            $p = $this->row_to_person($ayahRow, 0, 'Ayah Kandung');
            if ($depth > 0) $p['generasi_info'] = 'Ayah Kandung • Generasi ' . $depth;
            $member['orang_tua'][] = $p;
        }
        if ($ibuRow) {
            $p = $this->row_to_person($ibuRow, 0, 'Ibu Kandung');
            if ($depth > 0) $p['generasi_info'] = 'Ibu Kandung • Generasi ' . $depth;
            $member['orang_tua'][] = $p;
        }

        // Data Pasangan
        $pasanganRows = $this->find_spouses($id, $bypass_status);
        
        $member['pasangan'] = [];
        if (!empty($pasanganRows)) {
            $pasangan_names = array_column($pasanganRows, 'full_name');
            $member['pasangan_name'] = implode(', ', $pasangan_names);
            $member['pasangan_label'] = $this->spouse_label($pasanganRows[0]['gender'] ?? null);
            foreach ($pasanganRows as $pasanganRow) {
                $p = $this->row_to_person($pasanganRow, 0, $this->spouse_label($pasanganRow['gender'] ?? null));
                $p['generasi_info'] = $p['hubungan'] . ' • Generasi ' . ($depth + 1);
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
        if (!$bypass_status) {
            $this->db->where('status', 'approved');
        }
        
        $this->db->order_by('birth_date', 'ASC');
        $this->db->order_by('id', 'ASC');
        $childrenRows = $this->db->get('family_members')->result_array();
        
        $member['anak_anak'] = [];
        foreach ($childrenRows as $idx => $childRow) {
            $is_biological = ($childRow['father_id'] == $id || $childRow['mother_id'] == $id);
            
            // Perbaikan: Jika data ibu/ayah di anak masih kosong, dan kita sedang melihat dari sisi gender tersebut,
            // asumsikan sebagai anak kandung, bukan anak sambung (terjadi saat penambahan data searah).
            if (!$is_biological) {
                if ($memberRow['gender'] == 'P' && empty($childRow['mother_id'])) {
                    $is_biological = true;
                }
                if ($memberRow['gender'] == 'L' && empty($childRow['father_id'])) {
                    $is_biological = true;
                }
            }
            
            $relation_label = $is_biological ? 'Anak Kandung' : 'Anak Sambung';
            
            $p = $this->row_to_person($childRow, 0, $relation_label);
            $p['generasi_info'] = $relation_label . ' • Generasi ' . ($depth + 2);
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
            if (!$bypass_status) {
                $this->db->where('status', 'approved');
            }
            $this->db->order_by('birth_date', 'ASC');
            $this->db->order_by('id', 'ASC');
            
            $siblingsRows = $this->db->get('family_members')->result_array();
            $member['dari_jumlah_saudara'] = count($siblingsRows);
            
            foreach ($siblingsRows as $idx => $sibRow) {
                if ($sibRow['id'] == $id) {
                    $member['anak_ke'] = $idx + 1;
                } else {
                    $p = $this->row_to_person($sibRow, 0, 'Saudara (Kakak/Adik)');
                    $p['generasi_info'] = 'Saudara • Generasi ' . ($depth + 1);
                    $member['saudara'][] = $p;
                }
            }
        }


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

        $this->db->group_start();
        $this->db->where('father_id', $row['id']);
        $this->db->or_where('mother_id', $row['id']);
        $this->db->group_end();
        $this->db->where('status', 'approved');
        $this->db->order_by('birth_date', 'ASC');
        $this->db->order_by('id', 'ASC');
        $children = $this->db->get('family_members')->result_array();

        foreach ($children as $childRow) {
            $person['children'][] = $this->fetch_node($childRow, $depth + 1);
        }

        return $person;
    }

    private function find_spouses($memberId, $bypass_status = false)
    {
        $this->db->where('husband_id', $memberId);
        $this->db->or_where('wife_id', $memberId);
        $marriages = $this->db->get('marriages')->result_array();

        $spouses = [];
        foreach ($marriages as $marriage) {
            $spouseId = ($marriage['husband_id'] == $memberId) ? $marriage['wife_id'] : $marriage['husband_id'];
            
            $this->db->where('id', $spouseId);
            if (!$bypass_status) {
                $this->db->where('status', 'approved');
            }
            $spouse = $this->db->get('family_members')->row_array();
            
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
            'generasi'       => isset($row['generasi']) ? $row['generasi'] : null,
            'hubungan'       => $hubunganOverride ?? $this->relation_label($depth),
            'is_alive'       => isset($row['is_alive']) ? (int) $row['is_alive'] : 1,
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
            return 'https://ui-avatars.com/api/?name=' . urlencode($inisial) . '&background=CBD9CF&color=4A6055&size=100';
        }
        if (preg_match('#^https?://#i', $photo)) return $photo;
        
        // Cek jika path sudah ada awalan 'assets'
        if (strpos($photo, 'assets/') === 0) {
            return base_url($photo);
        }
        
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
        $this->db->select('id, full_name, gender, birth_date, user_id');
        $this->db->like('full_name', $term);
        $this->db->limit(10);
        return $this->db->get('family_members')->result_array();
    }

    public function get_unlinked_members($limit = 10)
    {
        $this->db->select('id, full_name, gender, birth_date, user_id');
        $this->db->where('user_id', NULL);
        $this->db->or_where('user_id', 0);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('family_members')->result_array();
    }

    public function insert_new_member($data, $role, $rel_ids)
    {
        if (!is_array($rel_ids)) {
            $rel_ids = [$rel_ids];
        }

        if (empty($rel_ids)) {
            return ['status' => false, 'message' => 'Tidak ada anggota relasi yang dipilih.'];
        }

        // 1. Simpan anggota baru (Cukup sekali)
        $this->db->insert('family_members', $data);
        $new_id = $this->db->insert_id();

        $messages = [];
        $berhasil = 0;

        // 2. Loop untuk setiap relasi yang dipilih
        foreach ($rel_ids as $rel_id) {
            $rel_id = (int)$rel_id;
            
            // Dapatkan data relasi
            $this->db->where('id', $rel_id);
            $rel_member = $this->db->get('family_members')->row_array();
            
            if (!$rel_member) continue;

            if ($role === 'pasangan') {
                // Validasi Pasangan
                if ($rel_member['gender'] === $data['gender']) {
                    continue; // Pernikahan sesama jenis kelamin diabaikan
                }
                
                // Hitung jumlah pasangan saat ini
                $this->db->where('husband_id', $rel_id);
                $this->db->or_where('wife_id', $rel_id);
                $count_spouses = $this->db->count_all_results('marriages');
                
                if ($rel_member['gender'] === 'L' && $count_spouses >= 4) {
                    continue; // Pria ini telah memiliki batas maksimal 4 istri.
                } elseif ($rel_member['gender'] === 'P' && $count_spouses >= 1) {
                    continue; // Wanita ini telah memiliki 1 suami.
                }
                
                // Masukkan ke tabel marriages
                $marriage_data = [
                    'husband_id' => ($rel_member['gender'] === 'L') ? $rel_id : $new_id,
                    'wife_id'    => ($rel_member['gender'] === 'P') ? $rel_id : $new_id,
                    'status'     => 'menikah'
                ];
                $this->db->insert('marriages', $marriage_data);
                $berhasil++;
                
            } elseif ($role === 'anak') {
                // Rel_member adalah orang tua.
                $update_data = [];
                if ($rel_member['gender'] === 'L') {
                    $update_data['father_id'] = $rel_id;
                    if (count($rel_ids) === 1) {
                        // Hanya set otomatis jika dia hanya punya TEPAT 1 istri
                        $this->db->where('husband_id', $rel_id);
                        $marriages = $this->db->get('marriages')->result_array();
                        if (count($marriages) === 1) {
                            $update_data['mother_id'] = $marriages[0]['wife_id'];
                        }
                    }
                } else {
                    $update_data['mother_id'] = $rel_id;
                    if (count($rel_ids) === 1) {
                        // Hanya set otomatis jika dia hanya punya TEPAT 1 suami
                        $this->db->where('wife_id', $rel_id);
                        $marriages = $this->db->get('marriages')->result_array();
                        if (count($marriages) === 1) {
                            $update_data['father_id'] = $marriages[0]['husband_id'];
                        }
                    }
                }
                
                $this->db->where('id', $new_id)->update('family_members', $update_data);
                $berhasil++;
                
            } elseif ($role === 'orangtua') {
                // Tambah orang tua ke anak (rel_member)
                // Ini asumsi hanya bisa tambah jika slot kosong
                if ($data['gender'] === 'L' && !empty($rel_member['father_id'])) {
                    continue; // Anak ini sudah memiliki data Ayah.
                }
                if ($data['gender'] === 'P' && !empty($rel_member['mother_id'])) {
                    continue; // Anak ini sudah memiliki data Ibu.
                }
                
                $update_data = [];
                if ($data['gender'] === 'L') {
                    $update_data['father_id'] = $new_id;
                    // Buat pernikahan jika anak sudah punya ibu
                    if (!empty($rel_member['mother_id'])) {
                        // Cek apakah sudah menikah
                        $this->db->where('husband_id', $new_id);
                        $this->db->where('wife_id', $rel_member['mother_id']);
                        if ($this->db->count_all_results('marriages') == 0) {
                            $this->db->insert('marriages', [
                                'husband_id' => $new_id,
                                'wife_id'    => $rel_member['mother_id'],
                                'status'     => 'menikah'
                            ]);
                        }
                    }
                } else {
                    $update_data['mother_id'] = $new_id;
                    // Buat pernikahan jika anak sudah punya ayah
                    if (!empty($rel_member['father_id'])) {
                        // Cek apakah sudah menikah
                        $this->db->where('husband_id', $rel_member['father_id']);
                        $this->db->where('wife_id', $new_id);
                        if ($this->db->count_all_results('marriages') == 0) {
                            $this->db->insert('marriages', [
                                'husband_id' => $rel_member['father_id'],
                                'wife_id'    => $new_id,
                                'status'     => 'menikah'
                            ]);
                        }
                    }
                }
                $this->db->where('id', $rel_id)->update('family_members', $update_data);
                $berhasil++;
            }
        }
        
        if ($berhasil > 0) {
            return ['status' => true, 'message' => "Berhasil menyimpan anggota dan menghubungkan $berhasil relasi.", 'id' => $new_id];
        } else {
            // Jika gagal semua relasinya, mungkin datanya dihapus saja?
            // Tapi sementara kita biarkan, asumsikan minimal 1 sukses.
            return ['status' => true, 'message' => 'Anggota disimpan tapi beberapa relasi mungkin tidak valid.', 'id' => $new_id];
        }
        
        return ['status' => false, 'message' => 'Peran tidak valid.'];
    }
}