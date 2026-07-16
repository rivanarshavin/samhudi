<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Linkedin_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_jobs()
    {
        $this->db->select('*');
        $this->db->from('job_listings');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_approved_jobs($search = NULL, $location = NULL, $type = NULL)
    {
        $this->db->select('*');
        $this->db->from('job_listings');
        $this->db->where('status', 'approved');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('job_title', $search);
            $this->db->or_like('company_name', $search);
            $this->db->or_like('description', $search);
            $this->db->group_end();
        }

        if (!empty($location)) {
            $this->db->like('location', $location);
        }

        if (!empty($type)) {
            $this->db->like('job_type', $type);
        }

        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_job_by_id($id)
    {
        return $this->db->where('id', $id)->get('job_listings')->row();
    }

    public function create_job($data)
    {
        return $this->db->insert('job_listings', $data);
    }

    public function get_open_to_work_users()
    {
        $this->db->select('id, full_name, avatar, open_to_work, work_role, is_fresh_graduate');
        $this->db->from('users');
        $this->db->where('open_to_work', 1);
        return $this->db->get()->result();
    }

    public function has_applied($user_id, $job_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('job_id', $job_id);
        return $this->db->count_all_results('job_applications') > 0;
    }

    public function create_application($data)
    {
        return $this->db->insert('job_applications', $data);
    }

    public function get_applications_by_job($job_id)
    {
        $this->db->select('ja.*, u.full_name, u.avatar');
        $this->db->from('job_applications ja');
        $this->db->join('users u', 'u.id = ja.user_id', 'left');
        $this->db->where('ja.job_id', $job_id);
        $this->db->order_by('ja.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function update_job_status($id, $status)
    {
        return $this->db->where('id', $id)->update('job_listings', ['status' => $status]);
    }

    public function delete_job($id)
    {
        $this->db->where('job_id', $id)->delete('job_applications');
        return $this->db->where('id', $id)->delete('job_listings');
    }
}
