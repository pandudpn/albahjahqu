<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require FCPATH.'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class reporting extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->check_login();

        $this->load->model('reporting/reporting_model', 'reporting');
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('index');
    }

    public function generate()
    {
    	set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set("memory_limit",-1);

    	// echo $this->toAlpha(1);die;
    	$spreadsheet 	= new Spreadsheet();
		$sheet 			= $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'NO');
		$sheet->mergeCells("A1:A2");
		$sheet->setCellValue('B1', 'NAMA PRODUK');
		$sheet->mergeCells("B1:B2");
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(50);

		$begin 			= new DateTime( $this->input->post('from') );
		$end   			= new DateTime( $this->input->post('to') );

		$num 			= 2;
		$charnum 		= -1;

		for($i = $begin; $i <= $end; $i->modify('+1 day'))
		{    
			$char 		= $this->toAlpha($num);
			$char_next 	= $this->toAlpha($num + 1);
			// echo $char.'2:'.$char_next.'2';
			// die;

			if($num > 25)
			{
				$num  = 0;
				$charnum++;
			}

			if($charnum >= 0)
			{
				$char 		= $this->toAlpha($charnum).$this->toAlpha($num);
				$char_next 	= $this->toAlpha($charnum).$this->toAlpha($num + 1);
			}

			// echo $char_next.' - ';

			$sheet->setCellValue($char.'1', $i->format("D, d M y"));
			$sheet->mergeCells($char.'1:'.$char_next.'1');
			$sheet->setCellValue($char.'2', 'QTY');
			$sheet->setCellValue($char_next.'2', 'RP');
			
			$num  		= $num + 2;

			$list 		= 3;
			$number 	= 1;
			$total_row_qty 	= 0;
			$total_row_sum 	= 0;

			// $total_column_qty 	= array();
			// $total_column_sum 	= array();

			$pls_lists = $this->reporting->pls_lists();

			// echo $i->format('Y-m-d');

			foreach ($pls_lists as $p) 
			{
				$pls_values = $this->reporting->pls_values($p->provider, $p->type, $i->format('Y-m-d'));
				// echo $this->db->last_query();die;
				// var_dump($pls_values);die;

				$total_row_qty 				= $total_row_qty + $pls_values->count_trx;
				$total_row_sum 				= $total_row_sum + $pls_values->sum_trx;
				$total_column_qty[$number] 	= $total_column_qty[$number] + $pls_values->count_trx;
				$total_column_sum[$number]	= $total_column_sum[$number] + $pls_values->sum_trx;

				$sheet->setCellValue('A'.$list, $number);
				$sheet->setCellValue('B'.$list, $p->name .' '. $p->description.' ('.$p->type.')');
				$sheet->setCellValue($char.$list, $pls_values->count_trx);
				$sheet->setCellValue($char_next.$list, $pls_values->sum_trx);

				$spreadsheet->getActiveSheet()->getColumnDimension($char)->setWidth(15);
				$spreadsheet->getActiveSheet()->getColumnDimension($char_next)->setWidth(20);

				$list++;
				$number++;
			}

			$topup_lists = $this->reporting->topup_lists();

			// echo $i->format('Y-m-d');

			foreach ($topup_lists as $t) 
			{
				$topup_values = $this->reporting->topup_values($t->id, $i->format('Y-m-d'));
				// echo $this->db->last_query();die;
				// var_dump($topup_values);die;

				$total_row_qty 				= $total_row_qty + $topup_values->count_trx;
				$total_row_sum 				= $total_row_sum + $topup_values->sum_trx;
				$total_column_qty[$number] 	= $total_column_qty[$number] + $topup_values->count_trx;
				$total_column_sum[$number]	= $total_column_sum[$number] + $topup_values->sum_trx;

				$sheet->setCellValue('A'.$list, $number);
				$sheet->setCellValue('B'.$list, $t->remarks);
				$sheet->setCellValue($char.$list, $topup_values->count_trx);
				$sheet->setCellValue($char_next.$list, $topup_values->sum_trx);

				$spreadsheet->getActiveSheet()->getColumnDimension($char)->setWidth(15);
				$spreadsheet->getActiveSheet()->getColumnDimension($char_next)->setWidth(20);

				$list++;
				$number++;
			}

			$sheet->setCellValue($char.($list + 1), $total_row_qty);
			$sheet->setCellValue($char_next.($list + 1), $total_row_sum);
		}

		// var_dump($total_column_qty);die;

		//TOTAL SUM

		$num  		= $num + 1;

		$char 		= $this->toAlpha($num);
		$char_next 	= $this->toAlpha($num + 1);
		// echo $char.'2:'.$char_next.'2';
		// die;

		if($num > 25)
		{
			$num  = 0;
			$charnum++;
		}

		if($charnum >= 0)
		{
			$char 		= $this->toAlpha($charnum).$this->toAlpha($num);
			$char_next 	= $this->toAlpha($charnum).$this->toAlpha($num + 1);
		}

		// echo $char;die;

		$sheet->setCellValue($char.'1', 'TOTAL');
		$sheet->mergeCells($char.'1:'.$char_next.'1');
		$sheet->setCellValue($char.'2', 'QTY');
		$sheet->setCellValue($char_next.'2', 'RP');

		$list = 3;

		$total_row_qty = 0;
		$total_row_sum = 0;

		foreach ($total_column_qty as $idx => $val) 
		{
			$total_row_qty 				= $total_row_qty + $total_column_qty[$idx];
			$total_row_sum 				= $total_row_sum + $total_column_sum[$idx];

			$sheet->setCellValue($char.$list, $total_column_qty[$idx]);
			$sheet->setCellValue($char_next.$list, $total_column_sum[$idx]);
			$spreadsheet->getActiveSheet()->getColumnDimension($char)->setWidth(15);
			$spreadsheet->getActiveSheet()->getColumnDimension($char_next)->setWidth(30);

			$list++;
		}

		$sheet->setCellValue($char.($list + 1), $total_row_qty);
		$sheet->setCellValue($char_next.($list + 1), $total_row_sum);

		$sheet->setCellValue('B'.($list + 1), 'TOTAL');
		$spreadsheet->getActiveSheet()->freezePane('C3');

		//END TOTAL SUM
		// die;

		$writer 	= new Xlsx($spreadsheet);
		$writer->save(FCPATH.'data/report.xlsx');

		redirect(site_url('data/report.xlsx'));

		// header('Content-Type: application/vnd.ms-excel');
		// header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); /*-- $filename is  xsl filename ---*/
		// header('Cache-Control: max-age=0');
		// $writer->save('php://output');

		// header('Content-disposition: attachment; filename=report.xlsx');
		// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		// readfile(FCPATH.'data/report.xlsx');

		// echo "<script>window.close</script>";
    }

    private function toAlpha($num)
    {
	    return chr($num+65);
	}

}