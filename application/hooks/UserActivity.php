<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserActivity {

    public function update_last_activity()
    {
        $CI =& get_instance();
        // Make sure the session library and database library are loaded
        if (isset($CI->session) && isset($CI->db)) {
            $user_id = $CI->session->userdata('user_id');
            if ($user_id) {
                // Load User_model if not already loaded
                if (!isset($CI->User_model)) {
                    $CI->load->model('User_model');
                }
                
                // Update last_activity for the logged-in user
                $CI->User_model->update($user_id, ['last_activity' => date('Y-m-d H:i:s')]);
            }
        }
    }
}
