<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class biller extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('prices/biller_model', 'biller');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
    	$this->template->set('alert', $this->session->flashdata('alert'))
    					->build('biller/index');
    }

    public function datatables()
    {
        $list = $this->biller->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->provider_name;
            $row[] = $l->remarks;
            $row[] = $l->biller_name;
            $row[] = $l->dealer_fee;
            $row[] = $l->dekape_fee;
            $row[] = $l->biller_fee;
            $row[] = $l->user_fee;

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
            "recordsTotal"      => $this->biller->count_all(),
            "recordsFiltered"   => $this->biller->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}