<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class home extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('references/reference_model', 'reference');
        $this->load->helper('text');

        $this->check_login();
    }

    public function index(){

    	$this->template->build('index');
    }

    public function post(){
    	$this->template->build('post');
    }

    public function post_new(){
    	$this->template->build('post_new');
    }

    public function page(){
    	$this->template->build('page');
    }

    public function page_new(){
    	$this->template->build('page_new');
    }

    public function category(){
    	$this->template->build('category');
    }

}