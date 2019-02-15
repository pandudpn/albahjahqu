<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class usermigration extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();

        $this->load->model('log/eva_customer_migration_model', 'eva_customer_migration');
    }

    public function index()
    {
    	$this->template
            ->set('alert', $this->session->flashdata('alert'))
    		->build('usermigration');
    }

    public function datatables()
    {
        $list = $this->eva_customer_migration->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->name;
            $row[] = $l->eva;
            $row[] = $l->old_balance;
            $row[] = $l->migration_balance;
            // $row[] = $l->equal;
            $row[] = $l->modified_on;
            $row[] = $l->created_on;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->eva_customer_migration->count_all(),
            "recordsFiltered"   => $this->eva_customer_migration->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}