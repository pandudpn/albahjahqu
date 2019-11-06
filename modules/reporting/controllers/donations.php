<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require FCPATH.'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function export(){
        $category       = $this->input->get('category');
        if($category == 'donation') {
            $category   = 'donasi';
        }
        $title          = "report_donation_kategori_".$category."_".date('Ymd');
        $donation_list  = $this->donations->get_data($this->app_id);

        $this->load->view('donation_excel', [
            'title'     => $title,
            'donation'  => $donation_list
        ]);
        
    }

    public function excel() {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set("memory_limit",-1);

    	$spreadsheet 	= new Spreadsheet();
		$sheet 			= $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Donasi Ke');
        $sheet->setCellValue('D1', 'Kategori');
        $sheet->setCellValue('E1', 'Nominal');
        $sheet->setCellValue('F1', 'Tanggal');
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);

        $list 		= 2;
        $number 	= 1;

        $donation_list = $this->donations->get_data($this->app_id);

        foreach ($donation_list as $don) 
        {
            $name   = $don->cus_name;
            if($don->anonymous == 'yes') {
                $name   = 'Hamba Allah';
            }

            $category   = ucfirst($don->category);
            if($don->category == 'donation') {
                $category   = 'Donasi';
            }

            $sheet->setCellValue('A'.$list, $number);
            $sheet->setCellValue('B'.$list, $name);
            $sheet->setCellValue('C'.$list, $don->donation_name);
            $sheet->setCellValue('D'.$list, $category);
            $sheet->setCellValue('E'.$list, $don->credit);
            $sheet->setCellValue('F'.$list, date('Y-m-d H:i', strtotime($don->created_on)));

            $list++;
            $number++;
        }

        $spreadsheet->getActiveSheet()->freezePane('C2');

        $cat    = $this->input->get('category');
        
        $catName    = $cat;
        if($cat == 'donation') {
            $catName    = 'donasi';
        }elseif($cat == '') {
            $catName    = 'semua';
        }

        $export 		= 'data/excel/report_donasi_kategori_'.$catName.'_'.date('Ymd').'.xlsx';

		$writer 	= new Xlsx($spreadsheet);
		$writer->save(FCPATH.$export);

		redirect(site_url($export));
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

            if($l->category == 'infaq'){
                $category   = 'Infaq';
            }elseif($l->category == 'waqaf') {
                $category   = 'Waqaf';
            }elseif($l->category == 'zakat') {
                $category   = 'Zakat';
            }elseif($l->category == 'shodaqoh') {
                $category   = 'Shodaqoh';
            }else{
                $category   = 'Donasi';
            }

            $row    = array();

            $row['no']      = $no;
            $row['name']    = $name;
            $row['donation']= $l->donation_name;
            $row['category']= $category;
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
