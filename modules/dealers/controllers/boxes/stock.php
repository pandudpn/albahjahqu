<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class stock extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('dealers/dealer_boxes_model', 'dealer_boxes');
        $this->load->model('dealers/dealer_box_services_model', 'dealer_box_services');
        $this->load->model('dealers/dealer_box_stocks_model', 'dealer_box_stocks');
        
        $this->load->model('references/ref_service_providers_model', 'service_provider');
        $this->load->model('references/ref_service_codes_model', 'service_code');
        $this->load->model('references/dealers_model', 'dealer');
        $this->load->model('references/billers_model', 'biller');
        
        $this->load->helper('text');

        $this->check_login();
    }

    public function index($box_id)
    {  
        $boxes = $this->dealer_boxes->find($box_id);
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('boxes', $boxes)
            ->set('box_id', $box_id)
    		->build('boxes/stock/index');
    }

    public function add($box_id)
    {
        $dealer   = $this->dealer->get_all();
        $biller   = $this->biller->get_all();
        $provider = $this->service_provider->get_all();

        $boxes = $this->dealer_boxes->find($box_id);
        
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Stock')
            ->set('dealer', $dealer)
            ->set('boxes', $boxes)
            ->set('provider', $provider)
        	->build('boxes/stock/form');
    }

    public function edit($box_id, $stock_id)
    {
        $is_exist = $this->dealer_box_stocks->find($stock_id);

        if($is_exist){

            $dealer_box_stocks_data = $is_exist;

            $provider = $this->service_provider->get_all();

            $boxes = $this->dealer_boxes->find($box_id);
            
            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Box Service')
                ->set('stock', $dealer_box_stocks_data)
                ->set('boxes', $boxes)
                ->set('provider', $provider)
                ->build('boxes/stock/form');
        }
    }

    public function save()
    {
        $box_id = $this->input->post('box_id');
        $id     = $this->input->post('id');
        
        $v1   = $this->input->post('v1');
        $v5   = $this->input->post('v5');
        $v10  = $this->input->post('v10');
        $v15  = $this->input->post('v15');
        $v20  = $this->input->post('v20');
        $v25  = $this->input->post('v25');
        $v40  = $this->input->post('v40');
        $v50  = $this->input->post('v50');
        $v80  = $this->input->post('v80');
        $v100 = $this->input->post('v100');
        $v200 = $this->input->post('v200');
        $v300 = $this->input->post('v300');
        
        $dealer_box = $this->dealer_boxes->find($box_id);

        $max_slot = $this->dealer_box_stocks->max_slot($dealer_box->ipbox);
        $slot = $max_slot[0]->slot;
        
        if(empty($slot)){
            $slot = 0;
        }

        $data = array(
            'dealer_id'   => $dealer_box->dealer_id,
            'dealer_name' => $dealer_box->dealer_name,
            'ipbox'       => $dealer_box->ipbox,
            'v1'          => $v1,
            'v5'          => $v5,
            'v10'         => $v10,
            'v15'         => $v15,
            'v20'         => $v20,
            'v25'         => $v25,
            'v40'         => $v40,
            'v50'         => $v50,
            'v80'         => $v80,
            'v100'        => $v100,
            'v200'        => $v200,
            'v300'        => $v300
        );

        if(!$id){
            $data['slot'] = $slot + 1;
            $insert = $this->dealer_box_stocks->insert($data);
            redirect(site_url('dealers/boxes/'.$box_id.'/stock'), 'refresh');
        }else{
            $update = $this->dealer_box_stocks->update($id, $data);
            redirect(site_url('dealers/boxes/'.$box_id.'/stock'), 'refresh');
        }

    }

    public function delete($box_id, $id)
    {
        $delete = $this->dealer_box_stocks->delete($id);

        redirect(site_url('dealers/boxes/'.$box_id.'/stock'), 'refresh');
    }

    public function datatables($box_id)
    {
        $ip_box = $this->dealer_boxes->find($box_id)->ipbox;
        $list   = $this->dealer_box_stocks->get_datatables($ip_box);
        
        $data = array();
        $no   = $_POST['start'];
        
        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->dealer_name;
            $row[] = $l->ipbox;
            $row[] = $l->slot;
            $row[] = $l->v1;
            $row[] = $l->v5;
            $row[] = $l->v10;
            $row[] = $l->v15;
            $row[] = $l->v20;
            $row[] = $l->v25;
            $row[] = $l->v40;
            $row[] = $l->v50;
            $row[] = $l->v80;
            $row[] = $l->v100;
            $row[] = $l->v200;
            $row[] = $l->v300;

            $btn  = '<a href="'.site_url('dealers/boxes/'.$box_id.'/stock/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('dealers/boxes/'.$box_id.'/stock/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->dealer_box_stocks->count_all($ip_box),
            "recordsFiltered"   => $this->dealer_box_stocks->count_filtered($ip_box),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}