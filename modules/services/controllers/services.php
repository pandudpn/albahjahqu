<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class services extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('services/service_model', 'service');

        $this->load->model('references/billers_model', 'biller');
        $this->load->model('references/ref_service_providers_model', 'service_provider');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
    	$this->template->set('alert', $this->session->flashdata('alert'))
    					->build('index');
    }

    public function add()
    {
        $biller   = $this->biller->get_all();
        $provider = $this->service_provider->get_all();
        
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Service')
            ->set('biller', $biller)
            ->set('provider', $provider)
    		->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->service->find($id);

        if($is_exist){
            $service = $is_exist;
            
            $biller   = $this->biller->get_all();
            $provider = $this->service_provider->get_all();

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Service')
                ->set('biller', $biller)
                ->set('provider', $provider)
                ->set('data', $service)
                ->build('form');
        }
    }

    public function save()
    {
        $id   = $this->input->post('id');

        $service     = $this->input->post('service');
        $provider    = $this->input->post('provider');
        $prepaid     = $this->input->post('prepaid');
        $type        = $this->input->post('type');
        $by          = $this->input->post('by');
        $biller_code = $this->input->post('biller_code');
        $remarks     = $this->input->post('remarks');
        
        $data = array(
                'service'     => $service,
                'provider'    => $provider,
                'prepaid'     => $prepaid,
                'by'          => $by,
                'type'        => $type,
                'biller_code' => $biller_code,
                'remarks'     => $remarks
            );
        
        if(!$id){
            $insert = $this->service->insert($data);
            redirect(site_url('services'), 'refresh');
        }else{
            $update = $this->service->update($id, $data);
            redirect(site_url('services'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->service->delete($id);

        redirect(site_url('services'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->service->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->provider_name;
            $row[] = $l->remarks;
            $row[] = $l->biller_name;

            $btn   = '<a href="'.site_url('services/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('services/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->service->count_all(),
            "recordsFiltered"   => $this->service->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}