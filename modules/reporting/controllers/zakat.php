<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require FCPATH.'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class zakat extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/zakat_model', 'zakat');

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
    		 ->build('zakat');
    }

    public function export(){
        $title          = "report_zakat_".date('Ymd');
        $zakat_list     = $this->zakat->get_data($this->alias);

        $this->load->view('zakat_excel', [
            'title'     => $title,
            'zakat'     => $zakat_list
        ]);
        
    }

    public function download(){
        $this->zakat->download($this->alias);
    }

    public function excel() {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set("memory_limit",-1);

    	$spreadsheet 	= new Spreadsheet();
		$sheet 			= $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Pengirim');
        $sheet->setCellValue('C1', 'Penerima');
        $sheet->setCellValue('D1', 'Nominal');
        $sheet->setCellValue('E1', 'Tanggal');
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);

        $list 		= 2;
        $number 	= 1;

        $zakat_list = $this->zakat->get_data($this->alias);

        foreach ($zakat_list as $zak) 
        {
            $sheet->setCellValue('A'.$list, $number);
            $sheet->setCellValue('B'.$list, $zak->sender);
            $sheet->setCellValue('C'.$list, $zak->receiver);
            $sheet->setCellValue('D'.$list, $zak->amount);
            $sheet->setCellValue('E'.$list, date('Y-m-d H:i', strtotime($zak->date_pay)));

            $list++;
            $number++;
        }

        $spreadsheet->getActiveSheet()->freezePane('C2');

        $export 		= 'data/excel/report_zakat_'.date('Ymd').'.xlsx';

		$writer 	= new Xlsx($spreadsheet);
		$writer->save(FCPATH.$export);

		redirect(site_url($export));
    }
    
    public function datatables()
    {
        $list = $this->zakat->get_datatables($this->alias);

        $query      = $list['query'];
        $totalData  = $list['totalData'];
        $totalFilter= $list['totalFiltered'];
        
        $data = array();
        $no   = $_POST['start'];

        foreach($query AS $l){
            $no++;
            $row    = array();

            $row['no']      = $no;
            $row['sender']  = $l->sender;
            $row['receiver']= $l->receiver;
            $row['cost']    = "Rp ".number_format($l->cost, 0, '.', '.');
            $row['date']    = date('d M, Y H:i', strtotime($l->created_on));

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $totalData,
            "recordsFiltered"   => $totalFilter,
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
