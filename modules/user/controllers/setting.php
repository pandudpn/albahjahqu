<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class setting extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('user/user_admin_model', 'user');

        $this->check_login();
    }

    public function index(){

    	if($this->input->post())
    	{
    		$password 	    = ($this->input->post('password') != '') ? $this->input->post('password') : NULL;

    		$data = array(
    			'password' 	=> sha1($password.$this->config->item('password_salt'))
    		);

    		$update = $this->user->update($this->input->post('id'), $data);

    		if($update)
    		{
    			$msg = 'change password success';
        		$this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

        		redirect(site_url('user/setting'), 'refresh');
    		}
    	}

    	 $this->template->set('alert', $this->session->flashdata('alert'))
    	 				->build('setting');
    }

}