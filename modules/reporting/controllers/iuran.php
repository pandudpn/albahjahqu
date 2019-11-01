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
            if($l->modified_on == NULL) {
                $times  = $l->created_on;
            }else {
                $times  = $l->modified_on;
            }

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
            $row['bil_date_pay']= $times;

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

    public function bulan($bulan) {
        switch($bulan) {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Pebuari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "Nopember";
                break;
            case 12:
                return "Desember";
                break;
            default:
                return null;
        }
    }

}
