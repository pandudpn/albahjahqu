<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class advice extends Api_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('transactions/transaction_model', 'transaction');
        $this->load->model('transactions/transaction_log_model', 'transaction_log');
        $this->load->model('user/eva_customer_mutation_model', 'eva_customer_mutation');
        $this->load->model('user/eva_corporate_mutation_model', 'eva_corporate_mutation');
        $this->load->model('user/eva_customer_model', 'eva_customer');
        $this->load->model('user/eva_corporate_model', 'eva_corporate');
        $this->load->model('customers/customer_model', 'customer');
        $this->load->model('dealers/dealer_model', 'dealer');
        $this->load->model('billers/biller_model', 'biller');
        $this->load->model('customers/customer_session_model', 'customer_session');
        $this->load->model('references/ref_service_codes_model', 'ref_service_code');

        $this->load->helper('text');
    }

    public function index()
    {
    	$trx_code 			= $this->input->post('trx_code');
        $transaction        = $this->transaction->find_by(array('trx_code' => $trx_code)); 
        $ref_service_code   = $this->ref_service_code->find($transaction->service_id);

        if(!$transaction)
        {
            $this->rest->set_error('transaction not found');
            $this->rest->render();
            die;
        }

        //URL 
        $url            = 'https://h2hdev.narindo.com:9902/v3/advice'; //DEV

        // $url            = 'https://h2h.narindo.com:9922/v3/advice'; //PROD
        $headers        = $this->input->request_headers();

        // DEVEL
        $userid         = '11111';
        $password       = '123456';

        // PROD
        // $userid         = '22893';
        // $password       = '@d3k4pEh2h';

        //PARAMS

        $reqid          = substr($transaction->trx_code, -13);
        $msisdn         = $transaction->destination_no;
        $product        = $ref_service_code->biller_code;
        $sign           = strtoupper(sha1($reqid.$msisdn.$product.$userid.$password));
        $mid            = '';
        $trx_code       = $transaction->trx_code;

        $params = array(
            'reqid'     => $reqid, 
            'msisdn'    => $msisdn, 
            'product'   => $product, 
            'userid'    => $userid, 
            'sign'      => $sign, 
            'mid'       => $mid, 
            'trx_code'  => $trx_code
        );

        $date   = new DateTime( $transaction->modified_on );
        $date2  = new DateTime( date('Y-m-d H:i:s') );

        $diff   = $date2->getTimestamp() - $date->getTimestamp();

        if($diff < 60)
        {
            $msg = 'silahkan tunggu '.(60 - $diff).' detik lagi untuk pengecekan trx / advice.';
            $this->rest->set_data($msg);
            $this->rest->render();
            die;
        }

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $output = curl_exec($ch); 
        curl_close($ch);  
    
        $response = json_decode($output);

        if($response->status == '1')
        {
            $this->rest->set_data($response);
            $this->rest->render();
            die;
        }
        else
        {
            $this->rest->set_error($response);
            $this->rest->render();
            die;
        }
    }
}