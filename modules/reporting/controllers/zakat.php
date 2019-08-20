<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class zakat extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/zakat_model', 'zakat');

        $this->check_login();
        $this->apps = $this->session->userdata('user')->apps_name;
    }

    public function index()
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('from', $from)
             ->set('to', $to)
    		 ->build('zakat');
    }
    
    public function datatables()
    {
        $yayasan    = $this->zakat->get_all($this->apps);
        $list = $this->zakat->get_datatables($this->apps);
        // print "<pre>";
        // print_r($list);
        // print "</pre>"; die;
        
        $data = array();
        $no   = $_POST['start'];

        foreach($yayasan AS $y){
            $cost   = 0;
            $no++;
            $row    = array();

            foreach($list AS $l){
                if($l->account_id == $y->id){
                    $cost   = $l->total_credit;
                }
            }

            $row[]  = $no;
            $row[]  = $y->account_holder;
            $row[]  = "Rp ".number_format($cost, 0, '.', '.');

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->zakat->count_all($this->apps),
            "recordsFiltered"   => $this->zakat->count_filtered($this->apps),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
