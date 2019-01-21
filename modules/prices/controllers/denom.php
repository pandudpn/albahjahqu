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
        $this->load->model('prices/price_log_model', 'price_log');
        $this->load->model('dealers/dealer_model', 'dealer_list');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $dealer     = $this->input->get('dealer');
        $provider   = $this->input->get('provider');
        $type       = $this->input->get('type');
        $category   = $this->input->get('category');

        $provider_lists = $this->denom->provider_list();
        $category_lists = $this->denom->category_list();

        $dealers        = $this->dealer_list->order_by('name', 'asc');
        $dealers        = $this->dealer_list->find_all_by(array('deleted' => '0'));

        $this->template->set('alert', $this->session->flashdata('alert'))
                        ->set('provider_lists', $provider_lists)
                        ->set('dealers', $dealers)
                        ->set('dealer', $dealer)
                        ->set('provider', $provider)
                        ->set('type', $type)
                        ->set('category_lists', $category_lists)
                        ->set('category', $category)
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
        $quota       = $this->input->post('quota');
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
                'quota'       => $quota,
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
            $this->price_log_insert('create', 'denom', $description, $insert, $data);

            redirect(site_url('prices/denom?'.$_SERVER["QUERY_STRING"]), 'refresh');
        }else{

            $update = $this->denom->update($id, $data);
            $this->price_log_insert('edit', 'denom', $description, $id, $data);

            redirect(site_url('prices/denom?'.$_SERVER["QUERY_STRING"]), 'refresh');
        }

    }

    public function delete($id)
    {
        $delete = $this->denom->delete($id);
        $denom = $this->denom->find($id);

        $this->price_log_insert('delete', 'denom', $denom->description, $id, $data);

        redirect(site_url('prices/denom?'.$_SERVER["QUERY_STRING"]), 'refresh');
    }

    public function download()
    {
        $this->denom->download();
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
        $list = $this->denom->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $btn   = '<a href="'.site_url('prices/denom/edit/'.$l->id).'?'.$_SERVER["QUERY_STRING"].'" class="btn btn-success btn-sm" style="margin-bottom: 5px;">
                        <i class="fa fa-pencil"></i>
                      </a>';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('prices/denom/delete/'.$l->id).'?'.$_SERVER["QUERY_STRING"].'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[] = $btn;
            $row[] = $no;
            $row[] = $l->provider_name;
            $row[] = $l->description;
            $row[] = $l->quota;
            $row[] = $l->category;
            $row[] = $l->dealer_name;
            $row[] = $l->biller_code;
            $row[] = $l->type;
            $row[] = $l->denom;
            $row[] = number_format(($l->base_price + $l->dealer_fee + $l->dekape_fee + $l->biller_fee + $l->user_fee));
            $row[] = number_format($l->base_price);
            $row[] = number_format($l->dealer_fee);
            $row[] = number_format($l->dekape_fee);
            $row[] = number_format($l->biller_fee);
            // $row[] = $l->partner_fee;
            $row[] = number_format($l->user_fee);

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