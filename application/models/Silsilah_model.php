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
    public function get_all_members($search = '', $gender = '', $is_alive = '', $generasi = '')
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

        $this->db->order_by('fm.full_name', 'ASC');
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
            $m['generasi'] = $depth + 1;

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
}
