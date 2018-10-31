<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class customers extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('customers/customer_model', 'customer');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
    	$this->template->set('alert', $this->session->flashdata('alert'))
    					->build('index');
    }

    public function datatables()
    {
        $list = $this->customer->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->name;
            $row[] = $l->phone;
            $row[] = $l->email;
            $row[] = $l->dealer_name;
            $row[] = 'Rp. '.number_format($l->balance);
            $row[] = $l->account_status;
            $row[] = $l->kyc_status;

            // $btn   = '<a href="'.site_url('menu/edit/'.$l->id).'" class="btn btn-success btn-sm">
            //             <i class="fa fa-pencil"></i>
            //           </a> &nbsp;';

            // $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('menu/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
            //             <i class="fa fa-trash"></i>
            //           </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->customer->count_all(),
            "recordsFiltered"   => $this->customer->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}