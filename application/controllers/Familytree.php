<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Familytree extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('silsilah/familytree_view');
        $this->load->view('templates/footer');
    }

    public function index()
    {
        $this->load->view('silsilah/familytree_view');
    }

    public function get_family_tree()
    {
        header('Content-Type: application/json; charset=utf-8');

        $rootId   = $this->input->get('root_id');
        $familyId = $this->input->get('family_id');

        $data = $this->Family_model->get_family_tree($rootId, $familyId);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
