<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class logs extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('transactions/transaction_log_model', 'transaction_log');
        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        
    	$this->template->set('alert', $this->session->flashdata('alert'))
                        ->set('from', $from)
                        ->set('to', $to)
    					->build('logs');
    }

    public function datatables()
    {
        $list = $this->transaction_log->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->transaction_code;
            $row[] = $l->user_name;
            $row[] = $l->user_role;
            $row[] = $l->user_phone;
            $row[] = $l->user_dealer_name;
            $row[] = $l->remarks;
            $row[] = $l->created_on;
            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->transaction_log->count_all(),
            "recordsFiltered"   => $this->transaction_log->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}