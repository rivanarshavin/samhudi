<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wasiat_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_wills() {
        $this->db->order_by('id', 'ASC');
        return $this->db->get('wills')->result_array();
    }



    public function get_will_by_id($id) {
        return $this->db->get_where('wills', ['id' => $id])->row_array();
    }

    public function insert_will($data) {
        return $this->db->insert('wills', $data);
    }

    public function update_will($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('wills', $data);
    }

    public function delete_will($id) {
        $this->db->where('id', $id);
        return $this->db->delete('wills');
    }
}
