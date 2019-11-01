<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class zakat extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/zakat_model', 'zakat');

        $this->check_login();
        $this->alias        = $this->session->userdata('user')->alias;
    }

    public function index()
    {
        $from   = date('Y-m-d', strtotime('first day of this month'));
        $to     = date('Y-m-d', strtotime('last day of this month'));
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('from', $from)
             ->set('to', $to)
    		 ->build('zakat');
    }

    public function download(){
        $this->zakat->download($this->alias);
    }
    
    public function datatables()
    {
        $list = $this->zakat->get_datatables($this->alias);

        $query      = $list['query'];
        $totalData  = $list['totalData'];
        $totalFilter= $list['totalFiltered'];
        
        $data = array();
        $no   = $_POST['start'];

        foreach($query AS $l){
            $no++;
            $row    = array();

            $row['no']      = $no;
            $row['sender']  = $l->sender;
            $row['receiver']= $l->receiver;
            $row['cost']    = "Rp ".number_format($l->cost, 0, '.', '.');
            $row['date']    = date('d M, Y H:i', strtotime($l->created_on));

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
