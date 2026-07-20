<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Silsilah_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all families
     */
    public function get_all_families()
    {
        return $this->db->get('families')->result_array();
    }

    /**
     * Get family members with filter and parent names
     */
    public function get_all_members($search = '', $gender = '', $is_alive = '', $generasi = '', $status = '')
    {
        $this->db->select('fm.*, f.family_name, 
            fat.full_name as father_name, 
            mot.full_name as mother_name,
            u.username as linked_username');
        $this->db->from('family_members fm');
        $this->db->join('families f', 'fm.family_id = f.id', 'left');
        $this->db->join('family_members fat', 'fm.father_id = fat.id', 'left');
        $this->db->join('family_members mot', 'fm.mother_id = mot.id', 'left');
        $this->db->join('users u', 'fm.user_id = u.id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('fm.full_name', $search);
            $this->db->or_like('fm.email', $search);
            $this->db->or_like('fm.occupation', $search);
            $this->db->group_end();
        }

        if ($gender === 'L' || $gender === 'P') {
            $this->db->where('fm.gender', $gender);
        }

        if ($is_alive !== '') {
            $this->db->where('fm.is_alive', $is_alive);
        }

        if ($status !== '') {
            $this->db->where('fm.status', $status);
        }

        $this->db->order_by('fm.id', 'DESC');
        $members = $this->db->get()->result_array();

        // Ambil semua parent_id untuk hitung kedalaman (generasi)
        $this->db->select('id, father_id, mother_id');
        $all_raw = $this->db->get('family_members')->result_array();
        $parent_map = [];
        foreach ($all_raw as $raw) {
            $parent_map[$raw['id']] = ['father_id' => $raw['father_id'], 'mother_id' => $raw['mother_id']];
        }

        $filtered_members = [];
        foreach ($members as &$m) {
            $depth = 0;
            $curr_id = $m['id'];
            while (isset($parent_map[$curr_id]) && ($parent_map[$curr_id]['father_id'] || $parent_map[$curr_id]['mother_id'])) {
                $depth++;
                $curr_id = $parent_map[$curr_id]['father_id'] ?: $parent_map[$curr_id]['mother_id'];
            }
            
            // Prioritaskan nilai generasi dari database (jika ada), jika tidak gunakan depth + 1
            $m['generasi'] = isset($m['generasi']) && $m['generasi'] !== null && $m['generasi'] !== '' ? $m['generasi'] : ($depth + 1);

            if ($generasi !== '' && $m['generasi'] != $generasi) {
                continue;
            }
            $filtered_members[] = $m;
        }

        return $filtered_members;
    }

    /**
     * Get maximum generation dynamically
     */
    public function get_max_generation()
    {
        $this->db->select('id, father_id, mother_id');
        $all_raw = $this->db->get('family_members')->result_array();
        
        if (empty($all_raw)) return 1;

        $parent_map = [];
        foreach ($all_raw as $raw) {
            $parent_map[$raw['id']] = ['father_id' => $raw['father_id'], 'mother_id' => $raw['mother_id']];
        }

        $max_depth = 0;
        foreach ($all_raw as $m) {
            $depth = 0;
            $curr_id = $m['id'];
            while (isset($parent_map[$curr_id]) && ($parent_map[$curr_id]['father_id'] || $parent_map[$curr_id]['mother_id'])) {
                $depth++;
                $curr_id = $parent_map[$curr_id]['father_id'] ?: $parent_map[$curr_id]['mother_id'];
            }
            if ($depth > $max_depth) {
                $max_depth = $depth;
            }
        }
        return $max_depth + 1;
    }

    /**
     * Get member by ID
     */
    public function get_member_by_id($id)
    {
        $this->db->select('fm.*, f.family_name, u.full_name as user_fullname');
        $this->db->from('family_members fm');
        $this->db->join('families f', 'fm.family_id = f.id', 'left');
        $this->db->join('users u', 'fm.user_id = u.id', 'left');
        $this->db->where('fm.id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Get parent options (L for fathers, P for mothers)
     */
    public function get_parent_options($gender)
    {
        return $this->db->select('id, full_name')
                        ->where('gender', $gender)
                        ->order_by('full_name', 'ASC')
                        ->get('family_members')
                        ->result_array();
    }

    /**
     * Get users who are not linked to a family member yet, or linked to this specific member
     */
    public function get_unlinked_users($current_user_id = null)
    {
        // Select users who do not have their ID in family_members.user_id
        $this->db->select('id, full_name, email');
        $this->db->from('users');
        $this->db->where('role', 'member');
        
        $subquery_sql = "SELECT user_id FROM family_members WHERE user_id IS NOT NULL";
        if ($current_user_id !== null) {
            $subquery_sql .= " AND user_id != " . $this->db->escape($current_user_id);
        }
        
        $this->db->where("id NOT IN ($subquery_sql)", NULL, FALSE);
        return $this->db->get()->result_array();
    }

    /**
     * Insert family member
     */
    public function insert_member($data)
    {
        return $this->db->insert('family_members', $data);
    }

    /**
     * Update family member
     */
    public function update_member($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('family_members', $data);
    }

    /**
     * Delete family member
     */
    public function delete_member($id)
    {
        // Set children's father_id or mother_id to NULL
        $this->db->where('father_id', $id)->update('family_members', ['father_id' => null]);
        $this->db->where('mother_id', $id)->update('family_members', ['mother_id' => null]);
        
        // Delete marriages involving this member
        $this->db->where('husband_id', $id)->or_where('wife_id', $id)->delete('marriages');

        // Delete the member
        $this->db->where('id', $id);
        return $this->db->delete('family_members');
    }

    /**
     * Get Spouses by Member ID
     */
    public function get_spouses_by_member_id($id)
    {
        $this->db->where('husband_id', $id);
        $this->db->or_where('wife_id', $id);
        $marriages = $this->db->get('marriages')->result_array();

        $spouse_ids = [];
        foreach ($marriages as $m) {
            $spouse_ids[] = ($m['husband_id'] == $id) ? $m['wife_id'] : $m['husband_id'];
        }
        return $spouse_ids;
    }

    /**
     * Get Spouse Options (Opposite Gender)
     */
    public function get_spouse_options($my_gender, $my_id)
    {
        $target_gender = ($my_gender === 'L') ? 'P' : 'L';
        
        $sql = "SELECT fm.id, fm.full_name, fm.birth_date 
                FROM family_members fm 
                WHERE fm.gender = ? AND fm.status = 'approved'";
                
        if ($target_gender === 'P') {
            // Target is female. She can only have 1 husband.
            $sql .= " AND fm.id NOT IN (
                        SELECT wife_id FROM marriages WHERE husband_id != ?
                      )";
        } else {
            // Target is male. He can have up to 4 wives.
            $sql .= " AND (
                        (SELECT COUNT(*) FROM marriages WHERE husband_id = fm.id) < 4
                        OR 
                        fm.id IN (SELECT husband_id FROM marriages WHERE wife_id = ?)
                      )";
        }
        
        $sql .= " ORDER BY fm.full_name ASC";
        
        return $this->db->query($sql, [$target_gender, $my_id])->result_array();
    }

    /**
     * Sync Marriages
     */
    public function sync_marriages($member_id, $gender, $spouse_ids)
    {
        // 1. Hapus relasi lama
        $this->db->where('husband_id', $member_id)->or_where('wife_id', $member_id)->delete('marriages');

        // 2. Masukkan relasi baru
        if (!empty($spouse_ids) && is_array($spouse_ids)) {
            foreach ($spouse_ids as $spouse_id) {
                if (empty($spouse_id)) continue;
                
                $data = [
                    'husband_id' => ($gender === 'L') ? $member_id : $spouse_id,
                    'wife_id'    => ($gender === 'P') ? $member_id : $spouse_id,
                    'status'     => 'menikah'
                ];
                $this->db->insert('marriages', $data);
            }
        }
    }
}
