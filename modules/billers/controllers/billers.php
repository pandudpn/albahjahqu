<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class billers extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('billers/biller_model', 'biller');

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
        $list = $this->biller->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->name;
            $row[] = $l->pic;
            $row[] = $l->pic_phone;
            $row[] = $l->pic_email;

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