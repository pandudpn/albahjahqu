<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class balance extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/balance_model', 'balance');

        $this->check_login();
        $this->alias        = $this->session->userdata('user')->alias;
    }

    public function index()
    {
        $from   = date('Y-m-d', strtotime('first day of this month'));
        $to     = date('Y-m-d', strtotime('last day of this month'));
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
            //  ->set('from', $from)
            //  ->set('to', $to)
    		 ->build('balance');
    }
    
    public function datatables()
    {
        $list = $this->balance->get_datatables();
        
        $data = array();
        $no   = $_POST['start'];

        foreach($list AS $l) {
            $no++;

            $row    = array();

            $row['no']      = $no;
            $row['name']    = $l->account_holder;
            $row['balance'] = number_format($l->account_balance, 0, '.', '.');

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->balance->count_all(),
            "recordsFiltered"   => $this->balance->count_filtered(),
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
                return "Febuari";
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
                return "November";
                break;
            case 12:
                return "Desember";
                break;
            default:
                return null;
        }
    }

}
