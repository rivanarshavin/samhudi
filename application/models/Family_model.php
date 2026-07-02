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

    private function fetch_node($row, $depth)
    {
        $person = $this->row_to_person($row, $depth);

        $spouseRow = $this->find_spouse((int) $row['id']);
        if ($spouseRow) {
            $person['pasangan'] = $this->row_to_person($spouseRow, $depth, $this->spouse_label($spouseRow['gender'] ?? null));
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

    private function find_spouse($memberId)
{
    $this->db->where('id', $memberId);
    $member = $this->db->get('family_members')->row_array();

    if (empty($member['spouse_id'])) return null;

    return $this->db->get_where('family_members', ['id' => $member['spouse_id']])->row_array();
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
            'foto'           => $this->resolve_foto($row['photo']),
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

    // Sesuaikan ini kalau struktur folder foto kamu di CI beda
    private function resolve_foto($photo)
    {
        if (empty($photo)) return base_url('assets/img/placeholder.png');
        if (preg_match('#^https?://#i', $photo)) return $photo;
        if (strpos($photo, '/') !== false) return base_url($photo);
        return base_url('uploads/' . $photo);
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
}