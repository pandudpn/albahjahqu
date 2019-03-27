<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class log extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();

        $this->load->model('log/log_tms_model', 'log_tms');

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

    public function tms()
    {
        $this->check_login();

        $this->template->build('tms');
    }

    public function tms_datatables()
    {
        $list = $this->log_tms->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->trx;
            $row[] = $l->ipbox;
            $row[] = $l->slot;
            $row[] = $l->ussd_response;
            $row[] = $l->ip_address;
            $row[] = $l->created_on;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->log_tms->count_all(),
            "recordsFiltered"   => $this->log_tms->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function get_data($collection=null, $offset=0)
    {
        error_reporting(E_ALL);
        $trx_code = $this->input->post('trx_code');
    	$remarks  = $this->input->post('remarks');

    	if(!empty($trx_code))
    	{
            $log_trx  = $this->mongo_db->where(array('reference'=>$trx_code))->order_by(array('created_on'=>'DESC'))->get($collection);
        }
        else if(!empty($remarks))
        {
            $log_trx  = $this->mongo_db->like('remarks', $remarks, 'i', true, true)->order_by(array('created_on'=>'DESC'))->get($collection);
        }
    	else
        {
    		$log_trx  = $this->mongo_db->order_by(array('created_on'=>'DESC'))->limit(20)->offset($offset)->get($collection);
        }

    	$this->rest->set_data($log_trx);
    	$this->rest->render();
    }

    public function get_user_migration($collection='user_migration', $offset=0)
    {
    	$phone = $this->input->post('phone');

    	if(!empty($phone))
    		$log_trx  = $this->mongo_db->where(array('phone'=>$phone))->order_by(array('created_on'=>'DESC'))->get($collection);
    	else
    		$log_trx  = $this->mongo_db->order_by(array('created_on'=>'DESC'))->offset($offset)->get($collection);

    	$this->rest->set_data($log_trx);
    	$this->rest->render();
    }
}