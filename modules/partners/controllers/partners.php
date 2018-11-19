<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class partners extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('partners/partner_model', 'partner');

        $this->load->model('references/geo_cities_model', 'city');
        $this->load->model('references/geo_provinces_model', 'province');

        $this->load->helper('text');

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
        $cities           = $this->city->get_all();
        $province         = $this->province->get_all();

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Partner')
            ->set('cities', $cities)
            ->set('province', $province)
    		->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->partner->find($id);

        if($is_exist){
            $partner = $is_exist;
            
            $cities           = $this->city->get_all();
            $province         = $this->province->get_all();

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Partner')
                ->set('cities', $cities)
                ->set('province', $province)
                ->set('data', $partner)
                ->build('form');
        }
    }

    public function save()
    {
        $id       = $this->input->post('id');

        $type        = $this->input->post('type');
        $description = $this->input->post('description');
        $name        = $this->input->post('name');
        $address     = $this->input->post('address');
        $city        = $this->input->post('city');
        $province    = $this->input->post('province');
        $zipcode     = $this->input->post('zipcode');
        $phone       = $this->input->post('phone');
        $email       = $this->input->post('email');
        $fax         = $this->input->post('fax');

        $data = array(
                'type'        => $type,
                'description' => $description,
                'name'        => $name,
                'address'     => $address,
                'city'        => $city,
                'zipcode'     => $zipcode,
                'province'    => $province,
                'phone'       => $phone,
                'email'       => $email,
                'fax'         => $fax
            );
        
        if(!$id){
            
            $last_id = $this->partner->last_id()->id;
            $leftPad = str_pad(intval($last_id + 1), 4, "0", STR_PAD_LEFT);
            
            $data['eva'] = 'P'.$leftPad.str_replace(' ', '', strtoupper($name));
            
            $insert = $this->partner->insert($data);
            redirect(site_url('partners'), 'refresh');
        }else{
            $update = $this->partner->update($id, $data);
            redirect(site_url('partners'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->partner->delete($id);

        redirect(site_url('partners'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->partner->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->name;
            $row[] = $l->description;
            $row[] = $l->phone;
            $row[] = $l->email;

            $btn   = '<a href="'.site_url('partners/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('partners/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->partner->count_all(),
            "recordsFiltered"   => $this->partner->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}