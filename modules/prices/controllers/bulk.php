<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class bulk extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('prices/bulk_model', 'bulk');
        $this->load->model('references/ref_service_providers_model', 'service_provider');
        $this->load->model('references/dealers_model', 'dealer');
        $this->load->model('references/billers_model', 'biller');
        $this->load->model('references/ref_service_codes_model', 'service_code');
        
        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
    	$this->template->set('alert', $this->session->flashdata('alert'))
    					->build('bulk/index');
    }

    public function add()
    {
        $service_provider = $this->service_provider->get_all();
        $dealer           = $this->dealer->get_all();
        $biller           = $this->biller->get_all();
        $service_code     = $this->service_code->get_all();
        
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Bulk')
            ->set('provider', $service_provider)
            ->set('dealer', $dealer)
            ->set('biller', $biller)
            ->set('service_code', $service_code)
    		->build('bulk/form');
    }

    public function edit($id)
    {
        $is_exist = $this->bulk->find($id);

        if($is_exist){
            $bulk_data = $is_exist;
            
            $service_provider = $this->service_provider->get_all();
            $dealer           = $this->dealer->get_all();
            $biller           = $this->biller->get_all();
            $service_code     = $this->service_code->get_all();
            
            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Bulk')
                ->set('provider', $service_provider)
                ->set('dealer', $dealer)
                ->set('biller', $biller)
                ->set('service_code', $service_code)
                ->set('bulk', $bulk_data)
                ->build('bulk/form');
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

        $margin_dealer        = $this->input->post('margin_dealer');
        $margin_reseller_user = $this->input->post('margin_reseller_user');
        $margin_end_user      = $this->input->post('margin_end_user');
        $dekape_fee           = $this->input->post('dekape_fee');
        $biller_fee           = $this->input->post('biller_fee');
        $partner_fee          = $this->input->post('partner_fee');
        $description          = $this->input->post('description');
        $status               = $this->input->post('status');

        $data = array(
                'operator' => $operator,
                'dealership' => $dealership,
                'service_id' => $service_id,
                'category' => $category,
                'type' => $type,
                'margin_dealer' => $margin_dealer,
                'margin_reseller_user' => $margin_reseller_user,
                'margin_end_user' => $margin_end_user,
                'dekape_fee' => $dekape_fee,
                'biller_fee' => $biller_fee,
                'partner_fee' => $partner_fee,
                'description' => $description,
                'status' => $status
            );

        if($dealership == 'dealer'){
            $data['dealer_id']   = $dealer_id;
            $data['dealer_name'] = $this->dealer->find($dealer_id)->name;
        }else if($dealership == 'biller'){
            $data['biller_id']   = $biller_id;
            $data['biller_name'] = $this->biller->find($biller_id)->code;
        }
        
        if(!$id){

            $insert = $this->bulk->insert($data);

            redirect(site_url('prices/bulk'), 'refresh');
        }else{

            $update = $this->bulk->update($id, $data);

            redirect(site_url('prices/bulk'), 'refresh');
        }

    }

    public function delete($id)
    {
        $delete = $this->bulk->delete($id);

        redirect(site_url('prices/bulk'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->bulk->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->provider_name;
            $row[] = $l->description;
            $row[] = $l->dealer_name;
            $row[] = $l->type;
            $row[] = $l->margin_dealer;
            $row[] = $l->margin_reseller_user;
            $row[] = $l->margin_end_user;
            $row[] = $l->dekape_fee;
            // $row[] = $l->partner_fee;

            $btn   = '<a href="'.site_url('prices/bulk/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('prices/bulk/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->bulk->count_all(),
            "recordsFiltered"   => $this->bulk->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}