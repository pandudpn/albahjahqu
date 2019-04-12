<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sms extends Front_Controller {
	protected $sms_api_clientID;
	protected $sms_api_clientSecret;
	protected $sms_api_clientCredentials;
	protected $sms_api_clientToken;
	protected $sms_api_clientExpired;
	protected $sms_api_url;
	protected $sms_api_token_url;


	public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('sms/sms_blast_kijp_model', 'sms_kijp');
        $this->load->model('sms/sms_blast_message_model', 'sms_message');
        $this->sms_api_url               = 'https://api.mainapi.net/smsnotification/1.0.0';
        $this->sms_api_token_url         = 'https://api.mainapi.net';
        $this->sms_api_clientID          = 'cLryt52hBlyqv7OCL8IIMDZhn1Qa';
        $this->sms_api_clientSecret      = 'wIIJe7Qsukp6NIuRtn_9Bzn7GR0a';
        $this->sms_api_clientCredentials = 'Y0xyeXQ1MmhCbHlxdjdPQ0w4SUlNRFpobjFRYTp3SUlKZTdRc3VrcDZOSXVSdG5fOUJ6bjdHUjBh'; 
    }

    public function index(){
    	$this->template->build('index');
    }

    public function blast($topic='frans_kijp_kapten'){
    	$this->load->library('guzzle'); 
    	//[1] Getting Access Token IFF Null/Expired
    	if(empty($this->sms_api_clientExpired) || ($this->sms_api_clientExpired < time())){
    		$status = $this->set_access_token();
    	}
    	//[2] Getting Recipients
        // $recipient  = (object) array('name' => 'fahmi', 'phone' => '+6281321503872');
		// $recipients = array(); array_push($recipients, $recipient);
        
        // $recipients = $this->sms_kijp->find_all();
        $recipients = $this->sms_kijp->find_all_by(array('sent' => null));
        //[3] Sending Message Out
        $message_template  = $this->sms_message->find_by(array('topic' => $topic));
        foreach ($recipients as $num => $r) {
        	$message_to_send  = str_replace('{Relawan}', ucwords($r->name), $message_template->text);
        	$send_result = $this->send_message($this->sms_api_clientToken, $r->phone, $message_to_send);
        	//echo '['.$r->id.'] ' . $message_to_send . '<br/><br/>';
        	if(empty($send_result->fault)){
        		$sent_data = array(
        			'sent'        => $send_result->code, 
        			'sent_result' => $send_result->status . ': ' . $send_result->message
        		);
        	}
        	else{
        		$sent_data = array(
        			'sent'        => $send_result->fault->code, 
        			'sent_result' => $send_result->fault->message . ': ' . $send_result->fault->description
        		);
        	}
        	$sent = $this->sms_kijp->update($r->id, $sent_data);
        }
    }  

    private function set_access_token(){
    	try{
	        $url         = $this->sms_api_token_url; 
	        $resource    = '/token';
	        $query       = '';
	        $token_url   = $url . $resource . $query;

	        $client      = new GuzzleHttp\Client([ 
		        				'base_uri'   => $url, 
		        				'headers'    => [ 'Authorization' => 'Basic ' . $this->sms_api_clientCredentials ]
	        				]);
	        $response    = $client->request('POST', $token_url, [
	                            'form_params' => 
	                            [ 
	                            	'grant_type' => 'client_credentials'
	                            ]
	                       ]);
	        $result     = $response->getBody()->getContents();
	        $result     = json_decode($result);
	        //RESULT: scope, token_type, expires_in, access_token
	        if($result->error){
	        	throw new \Exception($result->error);
	        }else{
	        	$this->sms_api_clientToken   = $result->access_token;
	        	$this->sms_api_clientExpired = time() + $result->expires_in;
	    	}
	    	return true;
	    }catch(Exception $e){ 
	    	echo $e;
	    	return false;
	    }
    }

    private function send_message($access_token='', $no_phone='', $message=''){
    	try{
	        $url         = $this->sms_api_url; 
	        $resource    = '/messages';
	        $query       = '';
	        $token_url   = $url . $resource . $query;

	        $client      = new GuzzleHttp\Client([ 
		        				'base_uri'   => $url, 
		        				'headers'    => [ 
		        					'Accept'        => 'application/json',
		        					'Content-Type'  => 'application/x-www-form-urlencoded',
		        					'Authorization' => 'Bearer ' . $access_token 
		        				]
	        				]);
	        $response    = $client->request('POST', $token_url, [
	                            'form_params' => 
	                            [ 
	                            	'msisdn'  => $no_phone,
	                            	'content' => $message
	                            ]
	                       ]);
	        $result     = $response->getBody()->getContents();
	        $result     = json_decode($result);
	        return $result;
	    }catch(Exception $e){ 
	    	echo $e;
	    	return (object) array( 'code' => 9, 'status' => 'error', 'message' => 'error' ); 
	    }
    } 

    public function send_main_api_sms(){
        require_once APPPATH.'third_party/Mainapi.php';
        
        $phone 		='085295703112';
        $message 	='TEST Mainapi.net di admin';
        //ZvYR5mcrKWcRRrKeC909Wmresjoa:d4Ti1aDjwAq6rHWsVxM0nG36Xcsa
        $mainAPI = new Mainapi();
        $mainAPI->setClient_id("ZvYR5mcrKWcRRrKeC909Wmresjoa");
        $mainAPI->setSecret_id("d4Ti1aDjwAq6rHWsVxM0nG36Xcsa");
        
        $res = $mainAPI->SendSMS($phone, $message);
        var_dump($res);
    }
}

?>