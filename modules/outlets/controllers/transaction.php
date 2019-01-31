<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class transaction extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('outlets/transaction_model', 'transaction');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index($outlet_number)
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        
    	$this->template->set('alert', $this->session->flashdata('alert'))
                        ->set('from', $from)
                        ->set('to', $to)
                        ->set('outlet_number', $outlet_number)
    					->build('transaction');
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
            $row[] = $l->created_on;
            $row[] = $l->trx_code;
            $row[] = $l->remarks;

            if(empty($l->slot))
            {
                $row[] = '-';
            }
            else
            {
                $row[] = $l->slot .' / '.$l->denom.'K';
            }

            if(empty($l->biller_name))
            {
                $row[] = '-';
            }
            else
            {
                $row[] = $l->biller_name;
            }

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

            $row[] = $ref_code.' / '.$token;
            $row[] = $l->cus_phone;
            $row[] = $l->destination_no;

            $row[] = 'Rp. '.number_format($l->selling_price);
            $row[] = 'Rp. '.number_format($l->base_price);
            $row[] = 'Rp. '.number_format($l->dealer_fee);
            $row[] = 'Rp. '.number_format($l->biller_fee);
            $row[] = 'Rp. '.number_format($l->dekape_fee);
            $row[] = 'Rp. '.number_format($l->partner_fee);
            $row[] = 'Rp. '.number_format($l->user_fee);
            $row[] = 'Rp. '.number_format($l->user_cashback);
            $row[] = $l->status;

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