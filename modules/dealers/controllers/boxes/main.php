<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends Admin_Controller {
	
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

    public function index()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
    		->build('boxes/main/index');
    }

    public function add()
    {
        $dealer           = $this->dealer->get_all();
        $biller           = $this->biller->get_all();
        
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Dealer Boxes')
            ->set('dealer', $dealer)
        	->build('boxes/main/form');
    }

    public function edit($id)
    {
        $is_exist = $this->dealer_boxes->find($id);

        if($is_exist){

            $dealer_boxes_data = $is_exist;
            $dealer           = $this->dealer->get_all();
            
            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Dealer Boxes')
                ->set('dealer', $dealer)
                ->set('boxes', $dealer_boxes_data)
                ->build('boxes/main/form');
        }
    }

    public function save()
    {
        $id   = $this->input->post('id');

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

            $ipbox_is_exist = $this->dealer_boxes->find_by(array('ipbox' => $ipbox));
            
            if(!$ipbox_is_exist){
                $insert = $this->dealer_boxes->insert($data);

                // PREPARE DATA SERVICE BOX
                if($serv_reg == 'on'){
                    $this->generate_box_service($dealer_id, $dealer_name, $ipbox, $type, 'REG', $slot_max);
                }

                if($serv_dat == 'on'){
                    $this->generate_box_service($dealer_id, $dealer_name, $ipbox, $type, 'DAT', $slot_max);
                }
                
                if($serv_pkd == 'on'){
                    $this->generate_box_service($dealer_id, $dealer_name, $ipbox, $type, 'PKD', $slot_max);
                }

                if($serv_pkt == 'on'){
                    $this->generate_box_service($dealer_id, $dealer_name, $ipbox, $type, 'PKT', $slot_max);
                }

                if($serv_blk == 'on'){
                    $this->generate_box_service($dealer_id, $dealer_name, $ipbox, $type, 'BLK', $slot_max);
                }

                if($serv_dlk == 'on'){
                    $this->generate_box_service($dealer_id, $dealer_name, $ipbox, $type, 'DLK', $slot_max);
                }

                if($serv_tlk == 'on'){
                    $this->generate_box_service($dealer_id, $dealer_name, $ipbox, $type, 'TLK', $slot_max);
                }

                if($serv_nap == 'on'){
                    $this->generate_box_service($dealer_id, $dealer_name, $ipbox, $type, 'NAP', $slot_max);
                }

                $this->generate_box_stock($dealer_id, $dealer_name, $ipbox, $slot_max);
            }

            redirect(site_url('dealers/boxes'), 'refresh');
        }else{
            $update = $this->dealer_boxes->update($id, $data);
            redirect(site_url('dealers/boxes'), 'refresh');
        }

    }

    private function generate_box_service($dealer_id, $dealer_name, $ipbox, $type, $serv_type, $maxslot){
        $data = array();

        for($i=1;$i<=$maxslot; $i++){
            $service = array(
                'dealer_id' => $dealer_id,
                'dealer_name' => $dealer_name,
                'ipbox' => $ipbox,
                'type' => $type,
                'slot' => $i,
                'operator' => 'TSL',
                'service_type' => $serv_type
            );

            array_push($data, $service);
        }

        $this->db->insert_batch('dealer_box_services', $data);

        return true;
    }

    private function generate_box_stock($dealer_id, $dealer_name, $ipbox, $maxslot){
        $data = array();

        for($i=1;$i<=$maxslot; $i++){
            $stock = array(
                'dealer_id' => $dealer_id,
                'dealer_name' => $dealer_name,
                'ipbox' => $ipbox,
                'slot' => $i
            );

            array_push($data, $stock);
        }

        $this->db->insert_batch('dealer_box_stocks', $data);

        return true;
    }

    public function delete($id)
    {
        $delete = $this->dealer_boxes->delete($id);

        redirect(site_url('dealers/boxes'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->dealer_boxes->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->dealer_name;
            $row[] = $l->ipbox;
            $row[] = $l->type;
            $row[] = $l->slot_max;
            $row[] = $l->status;
            // $row[] = $l->partner_fee;

            $detail   = '<a href="'.site_url('dealers/boxes/'.$l->id.'/service').'" title="Service" class="btn btn-primary btn-sm">
                        <i class="fa fa-sitemap"></i>
                      </a> &nbsp;';

            $detail   .= '<a href="'.site_url('dealers/boxes/'.$l->id.'/stock').'" title="Stock" class="btn btn-primary btn-sm">
                        <i class="fa fa-bar-chart-o"></i>
                      </a> &nbsp;';

            $btn   = '<a href="'.site_url('dealers/boxes/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('dealers/boxes/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $detail;
            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->dealer_boxes->count_all(),
            "recordsFiltered"   => $this->dealer_boxes->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}