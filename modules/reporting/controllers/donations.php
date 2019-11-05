<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class donations extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/donations_model', 'donations');

        $this->check_login();
        $this->alias        = $this->session->userdata('user')->alias;
        $this->app_id       = $this->session->userdata('user')->app_id;
    }

    public function index()
    {
        $from   = date('Y-m-d', strtotime('first day of this month'));
        $to     = date('Y-m-d', strtotime('last day of this month'));
        $cat    = $this->donations->all_category($this->app_id);
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('from', $from)
             ->set('to', $to)
             ->set('category', $cat)
    		 ->build('donations');
    }
    
    public function datatables()
    {
        $list = $this->donations->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach($list AS $l) {
            $no++;

            if($l->anonymous == 'yes') {
                $name   = 'Hamba Allah';
            }else {
                $name   = $l->cus_name;
            }

            $row    = array();

            $row['no']      = $no;
            $row['name']    = $name;
            $row['donation']= $l->donation_name;
            $row['category']= $l->category;
            $row['amount']  = number_format($l->credit, 0, '.', '.');
            $row['date']    = date('j', strtotime($l->created_on)). " ". $this->bulan(date('n', strtotime($l->created_on))). ", ".date('Y', strtotime($l->created_on)). " ".date('H:i', strtotime($l->created_on));

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->donations->count_all($this->app_id),
            "recordsFiltered"   => $this->donations->count_filtered($this->app_id),
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
