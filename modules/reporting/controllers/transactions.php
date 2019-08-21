<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class transactions extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/transactions_model', 'trans');

        $this->check_login();
        $this->apps_name    = $this->session->userdata('user')->apps_name;
        $this->apps         = $this->session->userdata('user')->alias;
    }

    public function index()
    {
        $from   = date('Y-m-d', strtotime('first day of this month'));
        $to     = date('Y-m-d', strtotime('last day of this month'));
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('from', $from)
             ->set('to', $to)
    		 ->build('transactions');
    }
    
    public function datatables()
    {
        $list = $this->trans->get_datatables($this->apps, $this->apps_name);

        $query      = $list['query'];
        $totalData  = $list['totalData'];
        $totalFilter= $list['totalFiltered'];
        
        $data = array();
        $no   = $_POST['start'];

        foreach($query AS $l){
            $no++;
            $row    = array();

            $row[]  = $no;
            $row[]  = $l->sender;
            $row[]  = $l->receiver;
            $row[]  = "Rp ".number_format($l->cost, 0, '.', '.');
            $row[]  = date('d M, Y H:i', strtotime($l->created_on));

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $totalData,
            "recordsFiltered"   => $totalFilter,
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
