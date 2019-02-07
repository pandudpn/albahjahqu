<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class transfers extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('customers/customer_model', 'customer');
        $this->load->model('user/eva_customer_model', 'eva_customer');
        $this->load->model('transactions/transfer_model', 'transfer');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('from', $from)
            ->set('to', $to)
            ->set('customer', $customer)
    		->build('transfers');
    }

    public function download()
    {
        $this->transfer->download();
    }

    public function datatables()
    {
        $list = $this->transfer->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->transaction_ref;
            $row[] = $l->account_eva;
            $row[] = $l->type;
            $row[] = 'Rp. '.number_format($l->amount);
            $row[] = $l->remarks;
            $row[] = $l->created_on;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->transfer->count_all(),
            "recordsFiltered"   => $this->transfer->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}