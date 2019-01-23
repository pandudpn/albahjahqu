<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class log extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();

        $this->check_login();

        $this->load->config('mongo_db');
        $this->load->library('mongo_db');
    }

    public function index()
    {
    	$this->template->build('index');
    }

    public function get_data($collection=null)
    {
    	if(!empty($json['trx_code']))
    		$log_trx  = $this->mongo_db->where(array('reference'=>$json['trx_code']))->order_by(array('created_on'=>'DESC'))->get($collection);
    	else
    		$log_trx  = $this->mongo_db->order_by(array('created_on'=>'DESC'))->get($collection);

    	$this->rest->set_data($log_trx);
    	$this->rest->render();
    }
}