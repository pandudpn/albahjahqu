<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class denom extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('prices/denom_model', 'denom');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
    	$this->template->set('alert', $this->session->flashdata('alert'))
    					->build('denom/index');
    }

    public function datatables()
    {
        $list = $this->denom->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->provider_name;
            $row[] = $l->description;
            $row[] = $l->dealer_name;
            $row[] = $l->biller_code;
            $row[] = $l->type;
            $row[] = $l->base_price;
            $row[] = $l->dealer_fee;
            $row[] = $l->dekape_fee;
            $row[] = $l->biller_fee;
            // $row[] = $l->partner_fee;
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
            "recordsTotal"      => $this->denom->count_all(),
            "recordsFiltered"   => $this->denom->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}