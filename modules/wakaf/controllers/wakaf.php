<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class wakaf extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('wakaf/wakaf_model', 'wakaf');
        $this->load->model('wakaf/wakaf_document_model', 'wakaf_document');
        $this->load->model('wakaf/wakaf_photo_model', 'wakaf_photo');
        $this->load->model('wakaf/wakaf_type_model', 'wakaf_type');

        $this->app_id   = $this->session->userdata('user')->app_id;
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
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'New Wakaf')
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->wakaf->find($id);

        if($is_exist){
            $wakaf = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Wakaf')
                ->set('data', $wakaf)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $app_id     	= $this->session->userdata('user')->app_id;
        $officer        = $this->input->post('officer');
        $partner_branch = $this->input->post('partner');
        $cus_id         = $this->input->post('cus_id');
        $cus_phone      = $this->input->post('cus_phone');
        $type           = $this->input->post('type');
        $ktp            = $this->input->post('ktp');
        $npwp           = $this->input->post('npwp');
        $recipient      = $this->input->post('recipient');
        $give           = $this->input->post('give');
        $title          = $this->input->post('title');
        $desc           = $this->input->post('desc');
        $birthplace     = $this->input->post('birthplace');
        $birthday       = $this->input->post('birthday');
        $address        = $this->input->post('address');
        $wakaf_address  = $this->input->post('address_wakaf');
        $date_wakaf     = $this->input->post('date');
        $status         = $this->input->post('status');

        $data = array(
            'app_id'            => $app_id,
            'officer_id'        => $officer,
            'partner_branch_id' => $partner_branch,
            'cus_id'            => $cus_id,
            'cus_phone'         => $cus_phone,
            'wakaf_type_id'     => $type,
            'id_card_number'    => $ktp,
            'npwp_number'       => $npwp,
            'recipient_name'    => $recipient,
            'give_name'         => $give,
            'title'             => $title,
            'description'       => $desc,
            'birthplace'        => $birthplace,
            'birthday'          => $birthday,
            'address'           => $address,
            'address_wakaf'     => $wakaf_address,
            'date_wakaf'        => $date_wakaf,
            'status'            => $status
        );

        if(!$id){
            $insert = $this->wakaf->insert($data);
        }else{
            $update = $this->wakaf->update($id, $data);
        }
        
        redirect(site_url('wakaf'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->video->delete($id);

        redirect(site_url('videos'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->video->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['title']   = $l->title;
            $row['desc']    = $l->description;
            $row['url']     = $l->url_video;
            $row['status']  = $l->status;
            $row['id']      = $l->id;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->video->count_all($this->app_id),
            "recordsFiltered"   => $this->video->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}