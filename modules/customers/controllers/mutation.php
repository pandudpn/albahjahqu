<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mutation extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('customers/customer_model', 'customer');
        $this->load->model('user/eva_customer_model', 'eva_customer');
        $this->load->model('user/eva_customer_mutation_model', 'eva_customer_mutation');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index($customer_id)
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $customer = $this->customer->find($customer_id);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('from', $from)
            ->set('to', $to)
            ->set('customer_id', $customer_id)
            ->set('customer', $customer)
    		->build('mutation');
    }

    public function datatables()
    {
        $list = $this->eva_customer_mutation->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->transaction_ref;
            $row[] = $l->starting_balance;
            $row[] = $l->credit;
            $row[] = $l->debit;
            $row[] = $l->ending_balance;
            $row[] = $l->remarks;
            $row[] = $l->created_on;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->eva_customer_mutation->count_all(),
            "recordsFiltered"   => $this->eva_customer_mutation->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}