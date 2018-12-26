<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class usages extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('dealers/usage_model', 'usage');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
    	$this->template->set('alert', $this->session->flashdata('alert'))
    					->build('usage');
    }

    public function datatables()
    {
        $list = $this->usage->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->trx;
            $row[] = $l->ipbox;
            $row[] = $l->slot;
            $row[] = $l->denom;
            $row[] = $l->status;
            $row[] = $l->mkios_response;
            $row[] = $l->created_on;
            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->usage->count_all(),
            "recordsFiltered"   => $this->usage->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}