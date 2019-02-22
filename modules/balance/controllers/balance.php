<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class balance extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->check_login();

        $this->load->model('balance/imburse_log_model', 'imburse_log');
        $this->load->library('guzzle');
        $this->xendit_secret_key = 'xnd_production_NIqCfL4tg7H7xcM4euYaHTPEYNempIIvkXbm+Rxg+mTR/7KlCgJ+hQ==';
    }

    public function index(){

    	if($this->input->post())
    	{
    		$data = array(
    			'admin_id' 			=> $this->session->userdata('user')->id,
                'admin_name'    	=> $this->session->userdata('user')->name,
    			'amount' 			=> $this->input->post('amount'), 
    			'bank_code' 		=> $this->input->post('bank_code'), 
    			'account_holder_name' => $this->input->post('account_holder_name'), 
    			'account_number' 	=> $this->input->post('account_number'), 
    			'description' 		=> $this->input->post('description')
    		);

    		$this->imburse_log->insert($data);

    		$url          = 'https://api.xendit.co';
	        $endpoint     = '/disbursements';
	        $api_url      = $url . $endpoint;
	        $access_64key = base64_encode($this->xendit_secret_key.":");
	        
	        try
	        {
	        	$key =  $this->session->userdata('user')->id.'-'.
	        			str_replace(' ', '_', strtolower($this->session->userdata('user')->name)).'-'.
	        			$this->input->post('account_number').'-'.
	        			time();
	            
	            $headers  = [
	                'Authorization' 	=> 'Basic ' . $access_64key,        
	                'Accept'        	=> 'application/json; charset=utf-8',
	                'X-IDEMPOTENCY-KEY' => $key
	            ];

	            $client   = new GuzzleHttp\Client([ 'base_uri' => $url ]);
	            $response = $client->request( 'POST' , $api_url, [ 
	                'headers' => $headers,
	                'json'    => [ 
	                	'external_id'  			=> $key,
	                    'bank_code'    			=> $this->input->post('bank_code'),
	                    'account_holder_name'   => $this->input->post('account_holder_name'),
	                    'account_number'    	=> $this->input->post('account_number'),
	                    'description'    		=> $this->input->post('description'),
	                    'amount'    			=> $this->input->post('amount')
	                ]
	            ]);
	            
	        } catch(Exception $e){ 

	        	$msg = 'ada sesuatu yang error. cek kembali.';
	            $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));

	            redirect(site_url('balance'), 'refresh');

	        }
	          
	        $result = $response->getBody()->getContents();
	        $result = json_decode($result); 

	        $msg = 'berhasil imburse Rp. '.number_format($this->input->post('amount')).' ke '.$this->input->post('account_holder_name');
            $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

            redirect(site_url('balance'), 'refresh');
    	}

        $url          = 'https://api.xendit.co';
        $endpoint     = '/balance';
        $api_url      = $url . $endpoint;
        $access_64key = base64_encode($this->xendit_secret_key.":");
        
        try
        {
            $headers  = [
                'Authorization' => 'Basic ' . $access_64key,        
                'Accept'        => 'application/json; charset=utf-8'
            ];

            $client   = new GuzzleHttp\Client([ 'base_uri' => $url ]);
            $response = $client->request( 'GET' , $api_url, [ 
                'headers' => $headers,
                'json'    => [ ]
            ]);
            
        } catch(Exception $e){ echo $e; }
          
        $result = $response->getBody()->getContents();
        $result = json_decode($result); 

        // var_dump($result->balance);die;

        // var_dump($this->session->userdata('user'));die;
    	$this->template->set('alert', $this->session->flashdata('alert'));
    	$this->template->build('index');
    }
}
