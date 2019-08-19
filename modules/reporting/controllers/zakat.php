<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class zakat extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/zakat_model', 'zakat');

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
    		 ->build('zakat');
    }
    
    public function datatables()
    {
        $list = $this->zakat->get_datatables();
        // print "<pre>";
        // print_r($list);
        // print "</pre>"; die;
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row[] = $no;
            $row[] = $l->name;
            $row[] = "Rp ".number_format($l->total_credit, 0, '.', '.');

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->zakat->count_all(),
            "recordsFiltered"   => $this->zakat->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
