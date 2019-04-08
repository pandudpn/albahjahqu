<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dealers extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('dealers/dealer_model', 'dealer');

        $this->load->model('references/geo_cities_model', 'city');
        $this->load->model('references/geo_provinces_model', 'province');
        $this->load->model('references/ref_service_providers_model', 'service_provider');
        $this->load->model('user/eva_corporate_model', 'corporate');

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
        $cities           = $this->city->get_all();
        $province         = $this->province->get_all();
        $service_provider = $this->service_provider->get_all();

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Dealer')
            ->set('cities', $cities)
            ->set('province', $province)
            ->set('provider', $service_provider)
    		->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->dealer->find($id);

        if($is_exist){
            $dealer = $is_exist;
            
            $cities           = $this->city->get_all();
            $province         = $this->province->get_all();
            $service_provider = $this->service_provider->get_all();

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Dealer')
                ->set('cities', $cities)
                ->set('province', $province)
                ->set('provider', $service_provider)
                ->set('data', $dealer)
                ->build('form');
        }
    }

    public function save()
    {
        $id       = $this->input->post('id');

        $name     = $this->input->post('name');
        $legal_name     = $this->input->post('legal_name');
        $address  = $this->input->post('address');
        $city     = $this->input->post('city');
        $province = $this->input->post('province');
        $zipcode  = $this->input->post('zipcode');
        $phone    = $this->input->post('phone');
        $email    = $this->input->post('email');
        $fax      = $this->input->post('fax');
        $operator = $this->input->post('operator');
        $date_joined = $this->input->post('date_joined');
        $note = $this->input->post('note');

        if(empty($date_joined))
        {
            $date_joined = NULL;
        }

        $data = array(
                'name'     => $name,
                'legal_name'     => $legal_name,
                'address'  => $address,
                'city'     => $city,
                'zipcode'  => $zipcode,
                'province' => $province,
                'phone'    => $phone,
                'email'    => $email,
                'fax'      => $fax,
                'operator' => $operator,
                'date_joined' => $date_joined,
                'note' => $note
            );
        
        if(!$id){
            
            $last_id = $this->dealer->last_id()->id;
            $leftPad = str_pad(intval($last_id + 1), 4, "0", STR_PAD_LEFT);
            
            $data['eva'] = 'D'.$leftPad.str_replace(' ', '', strtoupper($name));
            
            $insert = $this->dealer->insert($data);

            $data_eva = array(
                'account_no'        => $data['eva'], 
                'account_role'      => 'dealer', 
                'account_user'      => $insert, 
                'account_holder'    => $name
            );

            $insert_eva = $this->corporate->insert($data_eva);

            redirect(site_url('dealers'), 'refresh');
        }else{
            $update = $this->dealer->update($id, $data);
            redirect(site_url('dealers'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->dealer->delete($id);

        redirect(site_url('dealers'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->dealer->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->name;
            $row[] = $l->phone;
            $row[] = $l->email;
            $row[] = number_format($l->total_customer);
            $row[] = $l->date_joined;
            $row[] = $l->note;

            $btn   = '<a href="'.site_url('dealers/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('dealers/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->dealer->count_all(),
            "recordsFiltered"   => $this->dealer->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}