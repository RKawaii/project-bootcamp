<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{


    public function index()
    {
        $data["title"] = "Dashboard";
        $this->load->view('core/head', $data);
        $this->load->view('templates/navbar');
        $this->load->view('pages/Dashboard');

        $this->load->view('core/end');
    }
}

/* End of file Controllername.php */
