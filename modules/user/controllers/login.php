<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class login extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('user/user_admin_model', 'user');
    }

    public function index(){

    	if($this->input->post())
    	{
    		$email 	    = ($this->input->post('email') != '') ? $this->input->post('email') : NULL;
    		$password 	= ($this->input->post('password') != '') ? $this->input->post('password') : NULL;

    		$data = array(
    			'email' 	=> strtolower($email),
    			'password' 	=> sha1($password.$this->config->item('password_salt'))
            );

            $user = $this->user->find_by($data);

    		if($user)
    		{
    			$this->session->set_userdata('admin_logged_in', true);
                $this->session->set_userdata('user', $user);
                redirect(site_url(), 'refresh');
    		}
    		else
    		{
    			$error = 'Error: email or password incorrect';
        		$this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $error));

        		redirect(site_url('user/login'), 'refresh');
    		}
    	}

    	 $this->template->set('alert', $this->session->flashdata('alert'))
    	 				->set_layout('barebone')
    					->build('login');
    }

}