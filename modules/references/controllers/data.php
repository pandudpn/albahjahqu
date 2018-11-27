<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class data extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('prices/biller_model', 'biller');
        $this->load->model('references/billers_model', 'ref_biller');
        $this->load->model('references/ref_service_codes_model', 'service_code');
        $this->load->model('references/ref_denoms_model', 'ref_denom');
        

        $this->check_login();
    }

    public function services($biller='')
    {
        $data = $this->service_code->find_all_by(array('by' => $biller, 'deleted' => 0));

        echo json_encode($data);
    }

    public function service_code($alias='', $category='REG')
    {
        if($category == ''){
            $param = array('provider' => $alias);
        }else{
            $param = array('provider' => $alias, 'type' => $category);
        }
        $data = $this->service_code->find_all_by($param);

        echo json_encode($data);
    }

    public function denom($alias='', $category='')
    {
        if($category == ''){
            $param = array('provider' => $alias);
        }else{
            $param = array('provider' => $alias, 'type' => $category);
        }
        $data = $this->ref_denom->find_all_by($param);

        echo json_encode($data);
    }
}