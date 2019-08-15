<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class home extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        
        setlocale(LC_TIME, 'id_ID');

        $this->check_login();
    }

    public function index(){
        // var_dump($this->session->userdata('user')->app_id);die;
    	$this->template->build('index');
    }   
}