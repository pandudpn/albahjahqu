<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class biller extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('prices/biller_model', 'biller');
        $this->load->model('references/dealers_model', 'dealer');
        $this->load->model('references/billers_model', 'ref_biller');
        $this->load->model('references/ref_service_codes_model', 'service_code');
        $this->load->model('prices/price_log_model', 'price_log');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $biller     = $this->input->get('biller');
        $billers    = $this->ref_biller->find_all_by(array('deleted' => '0'));

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Biller Price')
            ->set('biller', $biller)
            ->set('billers', $billers)
            ->build('biller/index');
    }

    public function add()
    {
        $ref_biller   = $this->ref_biller->get_all();
        $service_code = $this->service_code->get_all();
        
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Biller Price')
            ->set('ref_biller', $ref_biller)
            ->set('service_code', $service_code)
    		->build('biller/form');
    }

    public function edit($id)
    {
        $is_exist = $this->biller->find($id);

        if($is_exist){
            $biller_data = $is_exist;
            
            $ref_biller   = $this->ref_biller->get_all();
            $service_code = $this->service_code->get_all();
            
            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Biller Price')
                ->set('ref_biller', $ref_biller)
                ->set('service_code', $service_code)
                ->set('biller', $biller_data)
                ->build('biller/form');
        }
    }

    public function save()
    {
        $id   = $this->input->post('id');

        $biller_id     = $this->input->post('biller');
        $service_id    = $this->input->post('service');
        $provider_code = $this->input->post('provider_code');

        $base_price    = $this->input->post('base_price');
        $partner_fee   = $this->input->post('partner_fee');
        $dealer_fee    = $this->input->post('dealer_fee');
        $dekape_fee    = $this->input->post('dekape_fee');
        $biller_fee    = $this->input->post('biller_fee');
        $user_fee      = $this->input->post('user_fee');
        $user_cashback = $this->input->post('user_cashback');

        $serv = $this->service_code->find($service_id);
        $service_code = $serv->service.$serv->value.$serv->provider.$serv->prepaid.$serv->type;

        $data = array(
                'biller_id'     => $biller_id,
                'service_id'    => $service_id,
                'service_code'  => $service_code,
                'provider_code' => $provider_code,
                'base_price'    => $base_price,
                'biller_fee'    => $biller_fee,
                'dekape_fee'    => $dekape_fee,
                'dealer_fee'    => $dealer_fee,
                'partner_fee'   => $partner_fee,
                'user_cashback' => $user_cashback
                // 'user_fee'      => $user_fee
            );
        
        if(!$id){

            $insert = $this->biller->insert($data);
            $this->price_log_insert('create', 'biller', $service_code, $insert, $data);

            redirect(site_url('prices/biller?'.$_SERVER["QUERY_STRING"]), 'refresh');
        }else{

            $update = $this->biller->update($id, $data);
            $this->price_log_insert('edit', 'biller', $service_code, $id, $data);

            redirect(site_url('prices/biller?'.$_SERVER["QUERY_STRING"]), 'refresh');
        }

    }

    public function delete($id)
    {
        $delete = $this->biller->delete($id);
        $biller = $this->biller->find($id);
        
        $this->price_log_insert('delete', 'biller', $biller->service_code, $id, $data);

        redirect(site_url('prices/biller?'.$_SERVER["QUERY_STRING"]), 'refresh');
    }

    public function download()
    {
        $this->biller->download();
    }

    public function price_log_insert($action, $type, $remarks, $price_id, $json_data)
    {
        $admin_id   = $this->session->userdata('user')->id;
        $admin_name = $this->session->userdata('user')->name;

        $data = array(
            'admin_id'      => $admin_id,
            'admin_name'    => $admin_name,
            'dealer_id'     => $this->session->userdata('user')->dealer_id,
            'dealer_name'   => $this->dealer->find($this->session->userdata('user')->dealer_id)->name,
            'action'        => $action,
            'type'          => $type,
            'remarks'       => $remarks,
            'price_id'      => $price_id,
            'data'          => json_encode($json_data)
        );

        $this->price_log->insert($data);
    }

    public function datatables()
    {
        $list = $this->biller->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->provider_name;
            $row[] = $l->remarks;
            $row[] = $l->biller_name;
            $row[] = number_format($l->base_price);
            $row[] = number_format($l->dealer_fee);
            $row[] = number_format($l->dekape_fee);
            $row[] = number_format($l->biller_fee);
            $row[] = number_format($l->user_cashback);
            // $row[] = number_format($l->user_fee);

            $btn   = '<a href="'.site_url('prices/biller/edit/'.$l->id).'?'.$_SERVER["QUERY_STRING"].'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('prices/biller/delete/'.$l->id).'?'.$_SERVER["QUERY_STRING"].'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->biller->count_all(),
            "recordsFiltered"   => $this->biller->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}