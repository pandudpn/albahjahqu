<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require FCPATH.'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function download(){
        $this->iuran->download($this->app_id);
    }

    public function excel() {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set("memory_limit",-1);

    	$spreadsheet 	= new Spreadsheet();
		$sheet 			= $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIS');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Sekolah');
        $sheet->setCellValue('E1', 'Kode Cabang');
        $sheet->setCellValue('F1', 'Nominal');
        $sheet->setCellValue('G1', 'Tanggal Bayar');
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);

        $list 		= 2;
        $number 	= 1;

        $iuran_list = $this->iuran->get_data($this->app_id);

        foreach ($iuran_list as $iur) 
        {
            $sheet->setCellValue('A'.$list, $number);
            $sheet->setCellValue('B'.$list, $iur->nis);
            $sheet->setCellValue('C'.$list, $iur->nama);
            $sheet->setCellValue('D'.$list, $iur->sekolah);
            $sheet->setCellValue('E'.$list, $iur->kode_cabang);
            $sheet->setCellValue('F'.$list, $iur->nominal);
            $sheet->setCellValue('G'.$list, date('Y-m-d H:i', strtotime($iur->tanggal)));

            $list++;
            $number++;
        }

        $spreadsheet->getActiveSheet()->freezePane('C2');

        $export 		= 'data/excel/report_iuran_spp_'.date('Ymd').'.xlsx';

		$writer 	= new Xlsx($spreadsheet);
		$writer->save(FCPATH.$export);

		redirect(site_url($export));
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
            $row['nis']         = $l->student_number;
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
