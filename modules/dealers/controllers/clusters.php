<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class clusters extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('dealers/dealer_cluster_model', 'dealer_cluster');
        $this->load->model('dealers/dealer_model', 'dealer');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
    	$this->template
            ->set('alert', $this->session->flashdata('alert'))
    		->build('cluster/index');
    }

    public function add()
    {
        $dealers = $this->dealer->find_all();

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Dealer Cluster')
            ->set('dealers', $dealers)
    		->build('cluster/form');
    }

    public function edit($id)
    {
        $is_exist = $this->dealer_cluster->find($id);

        if($is_exist){
            $data 		= $is_exist;
            $dealers 	= $this->dealer->find_all();
            
            $this->template
	            ->set('alert', $this->session->flashdata('alert'))
	            ->set('title', 'Edit Dealer Cluster')
	            ->set('dealers', $dealers)
	            ->set('data', $data)
    			->build('cluster/form');
        }
    }

    public function delete($id)
    {
        $delete = $this->dealer_cluster->delete($id);

        redirect(site_url('dealers/clusters'), 'refresh');
    }

    public function save()
    {
        $id       = $this->input->post('id');

        $name     = $this->input->post('name');
        $alias    = $this->input->post('alias');
        $address  = $this->input->post('address');
        $area     = $this->input->post('area');
        $dealer_id = $this->input->post('dealer_id');

        $data = array(
            'name'     		=> $name,
            'alias'  		=> $alias,
            'area'     		=> $area,
            'dealer_id'  	=> $dealer_id,
            'dealer_name' 	=> $this->dealer->find($dealer_id)->name
        );
        
        if(!$id){
            $insert = $this->dealer_cluster->insert($data);
            redirect(site_url('dealers/clusters'), 'refresh');
        }else{
            $update = $this->dealer_cluster->update($id, $data);
            redirect(site_url('dealers/clusters'), 'refresh');
        }
    }

    public function datatables()
    {
        $list = $this->dealer_cluster->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->dealer_name;
            $row[] = $l->name;
            $row[] = $l->alias;
            $row[] = $l->area;

            $btn   = '<a href="'.site_url('dealers/clusters/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('dealers/clusters/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->dealer_cluster->count_all(),
            "recordsFiltered"   => $this->dealer_cluster->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}