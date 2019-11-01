<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class infaq extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/infaq_model', 'infaq');

        $this->check_login();
        $this->alias        = $this->session->userdata('user')->alias;
    }

    public function index()
    {
        $from   = date('Y-m-d', strtotime('first day of this month'));
        $to     = date('Y-m-d', strtotime('last day of this month'));
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('from', $from)
             ->set('to', $to)
    		 ->build('infaq');
    }

    public function download(){
        $this->infaq->download($this->alias);
    }
    
    public function datatables()
    {
        $list = $this->infaq->get_datatables($this->alias);
        
        $data = array();
        $no   = $_POST['start'];

        foreach($list AS $l) {
            $no++;

            $row    = array();

            $row['no']      = $no;
            $row['name']    = $l->name;
            $row['amount']  = number_format($l->debit, 0, '.', '.');
            $row['date']    = date('j', strtotime($l->created_on)). " ". $this->bulan(date('n', strtotime($l->created_on))). ", ".date('Y', strtotime($l->created_on)). " ".date('H:i', strtotime($l->created_on));

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->infaq->count_all($this->alias),
            "recordsFiltered"   => $this->infaq->count_filtered($this->alias),
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
