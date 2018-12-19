<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class complaints extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->check_login();
    }

    public function index()
    {
        redirect(site_url('complaints/report'), 'refresh');
    }
}