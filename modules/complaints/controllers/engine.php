<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class engine extends Api_Controller {
	
	public function __construct() {
        parent::__construct();

        $this->load->model('user/user_admin_model', 'user');
    }

    public function index()
    {
        echo '';
    }

    public function fcm()
    {
    	$id 		= $this->input->get_post('id');
    	$web_fcm 	= $this->input->get_post('web_fcm');

    	if(empty($id))
    	{
    		$id = 1;
    	}

    	$update = $this->user->update($id, array('web_fcm' => $web_fcm));

    	if($update)
    	{
    		$this->rest->set_data('success');
    		$this->rest->render();
    	}
    	else
    	{
    		$this->rest->set_error('something error');
    		$this->rest->render();
    	}
    }
}