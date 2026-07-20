<?php
class Temp extends CI_Controller {
    public function index() {
        $this->load->database();
        if (!$this->db->field_exists('created_by', 'family_members')) {
            $this->load->dbforge();
            $fields = [
                'created_by' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    'after' => 'user_id'
                ]
            ];
            $this->dbforge->add_column('family_members', $fields);
            echo "Column 'created_by' added.";
        } else {
            echo "Column 'created_by' already exists.";
        }
    }
}
