<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class log extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();

        $this->load->config('mongo_db');
        $this->load->library('mongo_db');
    }

    public function index()
    {
    	$this->check_login();

    	$this->template->build('index');
    }

    public function request()
    {
    	$this->check_login();

    	$this->template->build('request');
    }

    public function migration()
    {
    	$this->check_login();

    	$this->template->build('migration');
    }

    public function get_data($collection=null, $offset=0)
    {
    	$trx_code = $this->input->post('trx_code');

    	if(!empty($trx_code))
    		$log_trx  = $this->mongo_db->where(array('reference'=>$trx_code))->order_by(array('created_on'=>'DESC'))->get($collection);
    	else
    		$log_trx  = $this->mongo_db->order_by(array('created_on'=>'DESC'))->limit(20)->offset($offset)->get($collection);

    	$this->rest->set_data($log_trx);
    	$this->rest->render();
    }

    public function get_user_migration($collection='user_migration', $offset=0)
    {
    	$phone = $this->input->post('phone');

    	if(!empty($phone))
    		$log_trx  = $this->mongo_db->where(array('phone'=>$phone))->order_by(array('created_on'=>'DESC'))->get($collection);
    	else
    		$log_trx  = $this->mongo_db->order_by(array('created_on.$date.$numberLong'=>'DESC'))->limit(20)->offset($offset)->get($collection);

    	$this->rest->set_data($log_trx);
    	$this->rest->render();
    }
}