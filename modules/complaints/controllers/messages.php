<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class messages extends Admin_Controller {
    
    public function __construct() {
        parent::__construct();

        $this->check_login();
    }

    public function index()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->build('message');
    }
}