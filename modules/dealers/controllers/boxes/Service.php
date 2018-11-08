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
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('box_id', $box_id)
    		->build('boxes/service/index');
    }

    public function add($box_id)
    {
        $dealer           = $this->dealer->get_all();
        $biller           = $this->biller->get_all();
        
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Box Service')
            ->set('dealer', $dealer)
        	->build('boxes/service/form');
    }

    public function edit($box_id, $servbox_id)
    {
        $is_exist = $this->dealer_box_services->find($id);

        if($is_exist){

            $dealer_box_services_data = $is_exist;
            $dealer           = $this->dealer->get_all();
            
            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Box Service')
                ->set('dealer', $dealer)
                ->set('boxes', $dealer_box_services_data)
                ->build('boxes/form');
        }
    }

    public function save()
    {
        $id     = $this->input->post('id');
        $box_id = $this->input->post('box_id');

        $dealer_id = $this->input->post('dealer');
        $ipbox     = $this->input->post('ipbox');
        $type      = $this->input->post('type');
        $slot_max  = $this->input->post('slot_max');
        $status    = $this->input->post('status');

        $serv_reg    = $this->input->post('serv_reg');
        $serv_dat    = $this->input->post('serv_dat');
        $serv_pkd    = $this->input->post('serv_pkd');
        $serv_pkt    = $this->input->post('serv_pkt');
        $serv_blk    = $this->input->post('serv_blk');
        $serv_dlk    = $this->input->post('serv_dlk');
        $serv_tlk    = $this->input->post('serv_tlk');
        $serv_nap    = $this->input->post('serv_nap');

        $dealer_name = $this->dealer->find($dealer_id)->name;

        $data = array(
                'dealer_id'   => $dealer_id,
                'dealer_name' => $dealer_name,
                'ipbox'       => $ipbox,
                'type'        => $type,
                'slot_max'    => $slot_max,
                'status'      => $status
            );

        if(!$id){
            $insert = $this->dealer_boxes->insert($data);

            redirect(site_url('dealers/boxes'), 'refresh');
        }else{
            $update = $this->dealer_boxes->update($id, $data);
            redirect(site_url('dealers/boxes'), 'refresh');
        }

    }


    public function delete($id)
    {
        $delete = $this->dealer_boxes->delete($id);

        redirect(site_url('dealers/boxes/boxes'), 'refresh');
    }

    public function datatables($box_id)
    {
        $list = $this->dealer_box_services->get_datatables();

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
            $row[] = $l->pinsim;
            $row[] = $l->status;
            // $row[] = $l->partner_fee;

            $btn   = '<a href="'.site_url('dealers/boxes/service/'.$l->id).'" class="btn btn-primary btn-sm">
                        <i class="fa fa-eye"></i>
                      </a> &nbsp;';

            $btn   .= '<a href="'.site_url('dealers/boxes/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('dealers/boxes/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->dealer_box_services->count_all(),
            "recordsFiltered"   => $this->dealer_box_services->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}