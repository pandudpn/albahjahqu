<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require FCPATH.'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class sadaqah extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/sadaqah_model', 'sadaqah');

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
    		 ->build('sadaqah');
    }

    public function export(){
        $title          = "report_shodaqoh_".date('Ymd');
        $sadaqah_list   = $this->sadaqah->get_data($this->alias);

        $this->load->view('sadaqah_excel', [
            'title'     => $title,
            'sadaqah'   => $sadaqah_list
        ]);
        
    }

    public function download(){
        $this->sadaqah->download($this->alias);
    }

    public function excel() {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set("memory_limit",-1);

    	$spreadsheet 	= new Spreadsheet();
		$sheet 			= $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Nominal');
        $sheet->setCellValue('D1', 'Tanggal');
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);

        $list 		= 2;
        $number 	= 1;

        $sadaqah_list = $this->sadaqah->get_data($this->alias);

        foreach ($sadaqah_list as $sada) 
        {
            $sheet->setCellValue('A'.$list, $number);
            $sheet->setCellValue('B'.$list, $sada->name);
            $sheet->setCellValue('C'.$list, $sada->amount);
            $sheet->setCellValue('D'.$list, date('Y-m-d H:i', strtotime($sada->created_on)));

            $list++;
            $number++;
        }

        $spreadsheet->getActiveSheet()->freezePane('C2');

        $export 		= 'data/excel/report_shodaqoh_'.date('Ymd').'.xlsx';

		$writer 	= new Xlsx($spreadsheet);
		$writer->save(FCPATH.$export);

		redirect(site_url($export));
    }
    
    public function datatables()
    {
        $list = $this->sadaqah->get_datatables($this->alias);
        
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
            "recordsTotal"      => $this->sadaqah->count_all($this->alias),
            "recordsFiltered"   => $this->sadaqah->count_filtered($this->alias),
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
