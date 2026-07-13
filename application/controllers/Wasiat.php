<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wasiat extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('wasiat_view');
        $this->load->view('templates/footer');
    }
}