<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class geo extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();

        $this->load->model('references/geo_cities_model', 'city');
        $this->load->model('references/geo_provinces_model', 'province');
        

        $this->check_login();
    }

    public function city($prov_id)
    {
        $data = $this->city->find_all_by(array('province_id' => $prov_id));

        echo json_encode($data);
    }
    
}