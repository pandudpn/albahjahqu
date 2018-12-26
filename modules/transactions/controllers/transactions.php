<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class transactions extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('transactions/transaction_model', 'transaction');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        
    	$this->template->set('alert', $this->session->flashdata('alert'))
                        ->set('from', $from)
                        ->set('to', $to)
    					->build('index');
    }

    public function download()
    {
        $this->transaction->download();
    }

    public function datatables()
    {
        $list = $this->transaction->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->trx_code;
            $row[] = $l->remarks;

            if(empty($l->biller_name))
            {
                $row[] = '-';
            }
            else
            {
                $row[] = $l->biller_name;
            }

            $row[] = $ref_code.' / '.$token;
            $row[] = $l->cus_phone;
            $row[] = $l->destination_no;

            if(empty($l->token_code))
            {
                $token = '-';
            }
            else
            {
                $token = $l->token_code;
            }

            if(empty($l->ref_code))
            {
                $ref_code = '-';
            }
            else
            {
                $ref_code = $l->ref_code;
            }

            $row[] = 'Rp. '.number_format($l->selling_price);
            $row[] = 'Rp. '.number_format($l->dealer_fee);
            $row[] = 'Rp. '.number_format($l->biller_fee);
            $row[] = $l->status;
            $row[] = $l->created_on;

            // $btn   = '<a href="'.site_url('menu/edit/'.$l->id).'" class="btn btn-success btn-sm">
            //             <i class="fa fa-pencil"></i>
            //           </a> &nbsp;';

            // $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('menu/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
            //             <i class="fa fa-trash"></i>
            //           </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->transaction->count_all(),
            "recordsFiltered"   => $this->transaction->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}