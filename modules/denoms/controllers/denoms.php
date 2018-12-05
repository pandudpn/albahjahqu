<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class denoms extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('denoms/denoms_model', 'denom');
        $this->load->model('references/ref_service_providers_model', 'service_provider');
        $this->load->model('references/dealers_model', 'dealer');
        $this->load->model('references/billers_model', 'biller');
        $this->load->model('references/ref_service_codes_model', 'service_code');
        $this->load->model('references/ref_denoms_model', 'ref_denom');

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
        $service_provider = $this->service_provider->get_all();
        $dealer           = $this->dealer->get_all();
        $biller           = $this->biller->get_all();
        
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Denom')
            ->set('provider', $service_provider)
            ->set('dealer', $dealer)
            ->set('biller', $biller)
    		->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->denom->find($id);

        if($is_exist){
            $denom_data = $is_exist;
            
            $service_provider = $this->service_provider->get_all();
            $dealer           = $this->dealer->get_all();
            $biller           = $this->biller->get_all();
            
            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit denom')
                ->set('provider', $service_provider)
                ->set('dealer', $dealer)
                ->set('biller', $biller)
                ->set('denom', $denom_data)
                ->build('form');
        }
    }

    public function save()
    {
        $id   = $this->input->post('id');

        $service       = $this->input->post('service');
        $provider      = $this->input->post('provider');
        $supplier      = $this->input->post('supplier');
        $dealer_id     = $this->input->post('dealer');
        $biller_id     = $this->input->post('biller');
        
        $supplier_code = $this->input->post('supplier_code');
        $type          = $this->input->post('type');
        $code          = $this->input->post('code');
        $value         = $this->input->post('value');

        $data = array(
                'service'       => $service,
                'provider'      => $provider,
                'supplier'      => $supplier,
                'supplier_id'   => $supplier == 'dealer' ? $dealer_id : $biller_id,
                'supplier_code' => $supplier_code,
                'type'          => $type == '' ? NULL : $type,
                'code'          => $code,
                'value'         => $value
            );

        if(!$id){
            $insert = $this->denom->insert($data);
            redirect(site_url('denoms'), 'refresh');
        }else{
            $update = $this->denom->update($id, $data);
            redirect(site_url('denoms'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->denom->delete($id);

        redirect(site_url('denoms'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->denom->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->service;
            $row[] = $l->provider_name;
            $row[] = $l->supplier;
            $row[] = $l->biller_name;
            $row[] = $l->supplier_code;
            $row[] = $l->type;
            $row[] = $l->code;
            $row[] = $l->value;
            
            $btn   = '<a href="'.site_url('denoms/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('denoms/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->denom->count_all(),
            "recordsFiltered"   => $this->denom->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}