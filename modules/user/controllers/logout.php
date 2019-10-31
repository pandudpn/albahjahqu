<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class logout extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
    }

    public function index()
    {
    	$this->session->set_userdata('admin_logged_in', false);
    	$this->session->set_userdata('user', false);

    	redirect(site_url('user/login'), 'refresh');
    }

}