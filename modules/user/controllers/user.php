<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class user extends Admin_Controller {
    
    public function __construct() {
        parent::__construct();

        $this->check_login();
    }

    public function index($id=null){

        redirect(site_url('user/admin'), 'refresh');
    }

}