<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rest {

	var $main;
	var $error;
	var $data;
    var $request_param;
    var $next;

	function set_error($error_message = ''){
		$this->error = $error_message;
    }

    function set_data($params){
    	$this->data = $params;
    }

    function set_requestparam($params){
        $this->request_param = $params;
    }

    function set_next($params){
        $this->next = $params;
    }

    function render(){
        $config['code']  = '1';
        $config['name']  = '0.1.0';

        $this->request_param = (empty($this->request_param) ? "" : $this->request_param);
        $this->next = (empty($this->next) ? "" : $this->next);

    	if(is_null($this->data)){
    		$data = array(
                'request_param' => $this->request_param,
    			'status' => 'error',
    			'error_message' => $this->error,
    			'data' => null,
                'next' => $this->next,
                'version' => $config
    		);
    	}else{
    		$data = array(
                'request_param' => $this->request_param,
	    		'status' => 'success',
	    		'error_message' => null,
	    		'data' => $this->data,
                'next' => $this->next,
                'version' => $config
    		);
    	}
    	
        header('Access-Control-Allow-Origin: *'); 
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');
    	echo json_encode($data);

    }
}