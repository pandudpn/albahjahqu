<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class clustermaps extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('dealers/dealer_cluster_model', 'dealer_cluster');
        $this->load->model('dealers/dealer_clustermap_model', 'dealer_clustermap');
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
    		->build('clustermaps/index');
    }

    public function add()
    {
        $provinces  = $this->geo_province->find_all();
        $dealers    = $this->dealer->find_all();
        $dealer_clusters    = $this->dealer_cluster->find_all_by(array('dealer_id' => $this->session->userdata('user')->dealer_id));

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Dealer Cluster Maps')
            ->set('provinces', $provinces)
            ->set('dealers', $dealers)
            ->set('dealer_clusters', $dealer_clusters)
            ->build('clustermaps/form');
    }

    public function edit($id)
    {
        $is_exist = $this->dealer_clustermap->find($id);

        if($is_exist){
            $data 		= $is_exist;
            $dealers 	= $this->dealer->find_all();
	        $city 		= $this->geo_city->find($data->city_id);

	        $provinces  = $this->geo_province->find_all();
	        $cities     = $this->geo_city->find_all_by(array('province_id' => $city->province_id));
	        $districts  = $this->geo_district->find_all_by(array('city_id' => $data->city_id));
	        $villages   = $this->geo_village->find_all_by(array('district_id' => $data->district_id));
	        $dealers    = $this->dealer->find_all();
	        $dealer_clusters    = $this->dealer_cluster->find_all_by(array('dealer_id' => $data->dealer_id));
            
            $this->template
	            ->set('alert', $this->session->flashdata('alert'))
	            ->set('title', 'Edit Dealer Cluster Maps')
	            ->set('data', $data)
	            ->set('city', $city)
	            ->set('provinces', $provinces)
	            ->set('cities', $cities)
	            ->set('districts', $districts)
	            ->set('villages', $villages)
	            ->set('dealers', $dealers)
	            ->set('dealer_clusters', $dealer_clusters)
    			->build('clustermaps/form');
        }
    }

    public function delete($id)
    {
        $delete = $this->dealer_clustermap->delete($id);

        redirect(site_url('dealers/clustermaps'), 'refresh');
    }

    public function save()
    {
        $id       = $this->input->post('id');

        $dealer_id     	= $this->input->post('dealer_id');
        $cluster_id    	= $this->input->post('cluster_id');
        $province_id  	= $this->input->post('province_id');
        $city_id     	= $this->input->post('city_id');
        $district_id 	= $this->input->post('district_id');
        $dealer 		= $this->dealer->find($dealer_id);
        $dealer_cluster = $this->dealer_cluster->find($dealer_id);
        $geo_city 		= $this->geo_city->find($city_id);
        $geo_district 	= $this->geo_district->find($district_id);

        $data = array(
            'cluster_id'     		=> $cluster_id,
            'cluster_name'  		=> $dealer_cluster->name,
            'cluster_area'     		=> $dealer_cluster->area,
            'dealer_id'  			=> $dealer_id,
            'dealer_name'  			=> $dealer->name,
            'city_id'  				=> $city_id,
            'city_name'  			=> $geo_city->name,
            'district_id'  			=> $district_id,
            'district_name'  		=> $geo_district->name,
            'district_lat'  		=> $geo_district->lat,
            'district_long'  		=> $geo_district->long
        );
        
        if(!$id){
            $insert = $this->dealer_clustermap->insert($data);
            redirect(site_url('dealers/clustermaps'), 'refresh');
        }else{
            $update = $this->dealer_clustermap->update($id, $data);
            redirect(site_url('dealers/clustermaps'), 'refresh');
        }
    }

    public function datatables()
    {
        $list = $this->dealer_clustermap->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->dealer_name;
            $row[] = $l->cluster_name;
            $row[] = $l->city_name;
            $row[] = $l->district_name;

            $btn   = '<a href="'.site_url('dealers/clustermaps/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('dealers/clustermaps/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->dealer_clustermap->count_all(),
            "recordsFiltered"   => $this->dealer_clustermap->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}