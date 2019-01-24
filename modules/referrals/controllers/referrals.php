<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class referrals extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('referrals/referral_model', 'referral');
        $this->load->model('dealers/dealer_cluster_model', 'dealer_cluster');
        $this->load->model('dealers/dealer_model', 'dealer');

        $this->load->model('references/geo_cities_model', 'geo_city');
        $this->load->model('references/geo_provinces_model', 'geo_province');
        $this->load->model('references/geo_districts_model', 'geo_district');
        $this->load->model('references/geo_villages_model', 'geo_village');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('index');
    }

    public function add()
    {
    	$provinces  		= $this->geo_province->find_all();
    	$dealers    		= $this->dealer->find_all();
        $dealer_clusters    = $this->dealer_cluster->find_all_by(array('dealer_id' => $this->session->userdata('user')->dealer_id));

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Code')
            ->set('provinces', $provinces)
            ->set('dealers', $dealers)
            ->set('dealer_clusters', $dealer_clusters)
            ->build('form');
    }

    public function edit($id)
    {

    	$is_exist = $this->referral->find($id);

    	$provinces  		= $this->geo_province->find_all();
    	$dealers    		= $this->dealer->find_all();
        $dealer_clusters    = $this->dealer_cluster->find_all_by(array('dealer_id' => $is_exist->dealer_id));

        if($is_exist){
            $data = $is_exist;
            $data->city_id 		= $this->geo_district->find($data->district_id)->city_id;
            $data->province_id  = $this->geo_city->find($data->city_id)->province_id;
            
            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Code')
                ->set('data', $data)
                ->set('provinces', $provinces)
	            ->set('dealers', $dealers)
	            ->set('dealer_clusters', $dealer_clusters)
                ->build('form');
        }
    }

    public function save()
    {
        $id       			= $this->input->post('id');

        $dealer_id          = $this->input->post('dealer_id');
        $dealer_name        = $this->dealer->find($dealer_id)->name;
        $cluster_id         = $this->input->post('cluster_id');
        $district_id     	= $this->input->post('district_id');
        $village_id     	= $this->input->post('village_id');
        $referral_code 		= strtoupper($this->input->post('referral_code'));
        $referral_phone 	= $this->input->post('referral_phone');

        $data = array(
            'dealer_id'    => $dealer_id,
            'dealer_name'  => $dealer_name,
            'cluster_id'   => $cluster_id,
            'village_id'   => $village_id,
            'referral_code'  => $referral_code,
            'referral_phone' => $referral_phone
        );

        // var_dump($data);die;

        if(empty($district_id))
        {
            $data['district_id'] = 5380;
        }
        else
        {
            $data['district_id'] = $this->input->post('district_id');
        }
        
        if(!$id){

            $check  = $this->referral->find_by(array('referral_code' => $referral_code, 'deleted' => '0'));

            if($check)
            {
                $msg = 'code already exists; please choose another referral code.';
                $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));

                redirect(site_url('referrals/add'), 'refresh');
            }

            $insert = $this->referral->insert($data);
            redirect(site_url('referrals'), 'refresh');
        }else{
            $update = $this->referral->update($id, $data);
            redirect(site_url('referrals'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->referral->delete($id);

        redirect(site_url('referrals'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->referral->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->dealer_name;
            $row[] = $l->referral_phone;
            $row[] = $l->referral_code;
            $row[] = $l->cluster_alias;
            $row[] = $l->district_name;
            // $row[] = $l->village_name;

            $btn   = '<a href="'.site_url('referrals/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('referrals/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->referral->count_all(),
            "recordsFiltered"   => $this->referral->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}