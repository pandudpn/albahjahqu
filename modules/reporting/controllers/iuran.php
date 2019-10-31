<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class iuran extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/students_bill', 'iuran');

        $this->check_login();
        $this->app_id       = $this->session->userdata('user')->app_id;
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('iuran');
    }
    
    public function datatables()
    {
        $list = $this->iuran->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach($list AS $l){
            $no++;
            $row    = array();

            $row['no']          = $no;
            $row['student']     = $l->student_name;
            $row['partner']     = $l->school_name;
            $row['bil_period_t']= ucwords($l->deposit_period_type);
            $row['bil_period']  = $l->deposit_period;
            $row['bil_year']    = $l->deposit_year;
            $row['bil_amount']  = number_format($l->deposit_amount, 0, '.', '.');
            $row['branch_code'] = $l->branch_code;
            $row['bil_date_pay']= date('d M, Y', strtotime($l->modified_on));

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->iuran->count_all($this->app_id),
            "recordsFiltered"   => $this->iuran->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
