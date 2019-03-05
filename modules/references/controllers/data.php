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

    public function service_code($alias='', $category='')
    {
        if($category == ''){
            $param = array('provider' => $alias, 'deleted' => '0');
        }else{
            $param = array('provider' => $alias, 'type' => $category, 'deleted' => '0');
        }

        $data = $this->service_code->find_all_by($param);

        foreach ($data as $key => $d) 
        {
            $data[$key]->biller = $this->ref_biller->find($d->by);

            if(!$data[$key]->biller)
            {
                $data[$key]->biller->name = '';
            }
            else
            {
                $data[$key]->biller->name = ' | '.$data[$key]->biller->name;
            }
        }

        echo json_encode($data);
    }

    public function denom($alias='', $category='')
    {
        if($category == ''){
            $param = array('provider' => $alias, 'deleted' => '0');
        }else{
            $param = array('provider' => $alias, 'type' => $category, 'deleted' => '0');
        }
        $data = $this->ref_denom->find_all_by($param);

        foreach ($data as $key => $d) 
        {
            $data[$key]->biller = $this->ref_biller->find($d->supplier_id);

            if(!$data[$key]->biller)
            {
                $data[$key]->biller->name = '';
            }
            else
            {
                $data[$key]->biller->name = ' | '.$data[$key]->biller->name;
            }
        }

        echo json_encode($data);
    }
}