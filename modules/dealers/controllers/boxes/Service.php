<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service extends Admin_Controller {
	
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
    		->build('boxes/service/index');
    }

    public function add($box_id)
    {
        $dealer   = $this->dealer->get_all();
        $biller   = $this->biller->get_all();
        $provider = $this->service_provider->get_all();

        $boxes = $this->dealer_boxes->find($box_id);
        
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Box Service')
            ->set('dealer', $dealer)
            ->set('boxes', $boxes)
            ->set('provider', $provider)
        	->build('boxes/service/form');
    }

    public function edit($box_id, $servbox_id)
    {
        $is_exist = $this->dealer_box_services->find($servbox_id);

        if($is_exist){

            $dealer_box_services_data = $is_exist;

            $dealer   = $this->dealer->get_all();
            $biller   = $this->biller->get_all();
            $provider = $this->service_provider->get_all();

            $boxes = $this->dealer_boxes->find($box_id);
            
            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Box Service')
                ->set('dealer', $dealer)
                ->set('service_boxes', $dealer_box_services_data)
                ->set('boxes', $boxes)
                ->set('provider', $provider)
                ->build('boxes/service/form');
        }
    }

    public function save()
    {
        $box_id = $this->input->post('box_id');
        $id     = $this->input->post('id');
        
        $operator         = $this->input->post('operator');
        $service_type     = $this->input->post('service_type');
        $service_coverage = $this->input->post('service_coverage');
        $msisdn           = $this->input->post('msisdn');
        $pinsim           = $this->input->post('pinsim');
        $status           = $this->input->post('status');
        
        $dealer_box = $this->dealer_boxes->find($box_id);

        $max_slot = $this->dealer_box_services->max_slot($dealer_box->ipbox, $service_type);
        $slot = $max_slot[0]->slot;
        
        if(empty($slot)){
            $slot = 0;
        }

        $data = array(
            'dealer_id'        => $dealer_box->dealer_id,
            'dealer_name'      => $dealer_box->dealer_name,
            'ipbox'            => $dealer_box->ipbox,
            'type'             => $dealer_box->type,
            'operator'         => $operator,
            'service_type'     => $service_type,
            'service_coverage' => $service_coverage,
            'msisdn'           => $msisdn,
            'pinsim'           => $pinsim,
            'status'           => $status
        );

        if(!$id){
            $data['slot'] = $slot + 1;
            
            $insert = $this->dealer_box_services->insert($data);
            redirect(site_url('dealers/boxes/'.$box_id.'/service'), 'refresh');
        }else{
            $update = $this->dealer_box_services->update($id, $data);
            redirect(site_url('dealers/boxes/'.$box_id.'/service'), 'refresh');
        }

    }

    public function delete($box_id, $id)
    {
        $delete = $this->dealer_box_services->delete($id);

        redirect(site_url('dealers/boxes/'.$box_id.'/service'), 'refresh');
    }

    public function datatables($box_id)
    {
        $ip_box = $this->dealer_boxes->find($box_id)->ipbox;
        $list   = $this->dealer_box_services->get_datatables($ip_box);
        
        $data = array();
        $no   = $_POST['start'];
        
        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->dealer_name;
            $row[] = $l->ipbox;
            $row[] = $l->type;
            $row[] = $l->slot;
            $row[] = $l->operator;
            $row[] = $l->service_type;
            $row[] = $l->service_coverage;
            $row[] = $l->msisdn;
            $row[] = $l->status;
            // $row[] = $l->partner_fee;

            $btn  = '<a href="'.site_url('dealers/boxes/'.$box_id.'/service/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('dealers/boxes/'.$box_id.'/service/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->dealer_box_services->count_all($ip_box),
            "recordsFiltered"   => $this->dealer_box_services->count_filtered($ip_box),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}