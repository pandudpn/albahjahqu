<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class denom extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('prices/denom_model', 'denom');
        $this->load->model('references/ref_service_providers_model', 'service_provider');
        $this->load->model('references/dealers_model', 'dealer');
        $this->load->model('references/billers_model', 'biller');
        $this->load->model('references/ref_service_codes_model', 'service_code');
        $this->load->model('references/ref_denoms_model', 'ref_denom');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
    	$this->template->set('alert', $this->session->flashdata('alert'))
    					->build('denom/index');
    }

    public function add()
    {
        $service_provider = $this->service_provider->get_all();
        $dealer           = $this->dealer->get_all();
        $biller           = $this->biller->get_all();
        $service_code     = $this->service_code->get_all();
        $ref_denom        = $this->ref_denom->get_all();
        
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Denom')
            ->set('provider', $service_provider)
            ->set('dealer', $dealer)
            ->set('biller', $biller)
            ->set('ref_denom', $ref_denom)
            ->set('service_code', $service_code)
    		->build('denom/form');
    }

    public function edit($id)
    {
        $is_exist = $this->denom->find($id);

        if($is_exist){
            $denom_data = $is_exist;
            
            $service_provider = $this->service_provider->get_all();
            $dealer           = $this->dealer->get_all();
            $biller           = $this->biller->get_all();
            $service_code     = $this->service_code->get_all();
            $ref_denom        = $this->ref_denom->get_all();
            
            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit denom')
                ->set('provider', $service_provider)
                ->set('dealer', $dealer)
                ->set('biller', $biller)
                ->set('ref_denom', $ref_denom)
                ->set('service_code', $service_code)
                ->set('denom', $denom_data)
                ->build('denom/form');
        }
    }

    public function save()
    {
        $id   = $this->input->post('id');

        $operator   = $this->input->post('operator');
        $dealership = $this->input->post('dealership');
        $dealer_id  = $this->input->post('dealer');
        $biller_id  = $this->input->post('biller');
        $service_id = $this->input->post('service');
        $category   = $this->input->post('category');
        $type       = $this->input->post('type');
        $denom      = $this->input->post('denom');

        $base_price  = $this->input->post('base_price');
        $dealer_fee  = $this->input->post('dealer_fee');
        $dekape_fee  = $this->input->post('dekape_fee');
        $biller_fee  = $this->input->post('biller_fee');
        $partner_fee = $this->input->post('partner_fee');
        $user_fee    = $this->input->post('user_fee');
        $description = $this->input->post('description');
        $status      = $this->input->post('status');

        $data = array(
                'operator'    => $operator,
                'dealership'  => $dealership,
                'service_id'  => $service_id,
                'category'    => $category,
                'type'        => $type == '' ? NULL : $type,
                'denom_id'    => $denom,
                'base_price'  => $base_price,
                'dealer_fee'  => $dealer_fee,
                'dekape_fee'  => $dekape_fee,
                'biller_fee'  => $biller_fee,
                'partner_fee' => $partner_fee,
                'user_fee'    => $user_fee,
                'description' => $description,
                'status'      => $status
            );

        if($dealership == 'dealer'){
            $data['dealer_id']   = $dealer_id;
            $data['dealer_name'] = $this->dealer->find($dealer_id)->name;
        }else if($dealership == 'biller'){
            $data['biller_id']   = $biller_id;
            $data['biller_code'] = $this->biller->find($biller_id)->code;
        }
        
        if(!$id){

            $insert = $this->denom->insert($data);

            redirect(site_url('prices/denom'), 'refresh');
        }else{

            $update = $this->denom->update($id, $data);

            redirect(site_url('prices/denom'), 'refresh');
        }

    }

    public function delete($id)
    {
        $delete = $this->denom->delete($id);

        redirect(site_url('prices/denom'), 'refresh');
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
            $row[] = $l->provider_name;
            $row[] = $l->description;
            $row[] = $l->dealer_name;
            $row[] = $l->biller_code;
            $row[] = $l->type;
            $row[] = number_format($l->base_price);
            $row[] = number_format($l->dealer_fee);
            $row[] = number_format($l->dekape_fee);
            $row[] = number_format($l->biller_fee);
            // $row[] = $l->partner_fee;
            $row[] = number_format($l->user_fee);

            $btn   = '<a href="'.site_url('prices/denom/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('prices/denom/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
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