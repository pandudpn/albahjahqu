<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class logs extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('prices/price_log_model', 'price_log');
        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Price Logs')
            ->set('from', $from)
            ->set('to', $to)
            ->build('logs/index');
    }

    public function datatables()
    {
        $list = $this->price_log->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->admin_name;
            $row[] = $l->dealer_name;
            $row[] = $l->type;
            $row[] = $l->action;
            $row[] = $l->remarks;
            $row[] = $l->created_on;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->price_log->count_all(),
            "recordsFiltered"   => $this->price_log->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}