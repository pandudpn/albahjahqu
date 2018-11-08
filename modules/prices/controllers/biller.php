<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class biller extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('prices/biller_model', 'biller');
        $this->load->model('references/billers_model', 'ref_biller');
        $this->load->model('references/ref_service_codes_model', 'service_code');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Biller Price')
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
                'user_fee'      => $user_fee
            );
        
        if(!$id){

            $insert = $this->biller->insert($data);

            redirect(site_url('prices/biller'), 'refresh');
        }else{

            $update = $this->biller->update($id, $data);

            redirect(site_url('prices/biller'), 'refresh');
        }

    }

    public function delete($id)
    {
        $delete = $this->biller->delete($id);

        redirect(site_url('prices/biller'), 'refresh');
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
            $row[] = number_format($l->dealer_fee);
            $row[] = number_format($l->dekape_fee);
            $row[] = number_format($l->biller_fee);
            $row[] = number_format($l->user_fee);

            $btn   = '<a href="'.site_url('prices/biller/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('prices/biller/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
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