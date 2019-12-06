<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class wakaf extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->helper('text');

        $this->load->model('wakaf/wakaf_model', 'wakaf');
        $this->load->model('wakaf/wakaf_document_model', 'wakaf_document');
        $this->load->model('wakaf/wakaf_photo_model', 'wakaf_photo');
        $this->load->model('wakaf/wakaf_type_model', 'wakaf_type');
        $this->load->model('officers/officer_model', 'officer');
        $this->load->model('officers/officer_notif_model', 'officer_notif');
        $this->load->model('officers/officer_count_model', 'officer_count');
        $this->load->model('community/partner_branch_models', 'partner');

        $this->app_id   = $this->session->userdata('user')->app_id;
        $this->check_login();
    }

    public function index()
    {
        $type   = $this->wakaf_type->find_all_by(['app_id' => $this->app_id, 'deleted' => 0]);
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('title', 'Data-data Wakaf')
             ->set('type', $type)
    		 ->build('index');
    }

    public function add()
    {
        $type   = $this->wakaf_type->find_all_by(['app_id' => $this->app_id, 'deleted' => 0]);
        $partner= $this->partner->find_all_by(['app_id' => $this->app_id, 'deleted' => 0]);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'New Wakaf')
            ->set('type', $type)
            ->set('branch', $partner)
            ->build('form');
    }

    public function assignment($id) {
        $data       = array();
        $wakaf      = $this->wakaf->find($id);

        $officer    = $this->officer->find_all_by(['partner_branch_id' => $wakaf->partner_branch_id, 'deleted' => 0]);

        foreach($officer AS $off) {
            $officers   = $this->officer_count->find_by(['officer_id' => $off->id, 'partner_branch_id' => $wakaf->partner_branch_id]);

            $data[]     = [
                'id'    => $off->id,
                'name'  => $off->cus_name,
                'phone' => $off->cus_phone,
                'total' => $officers->count
            ];
        }

        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('title', 'Penugasan Baru')
             ->set('officer', $data)
             ->set('data', $wakaf)
             ->build('assign_form');
    }

    public function edit($id)
    {
        $is_exist = $this->wakaf->find($id);
        $type     = $this->wakaf_type->find_all_by(['app_id' => $this->app_id, 'deleted' => 0]);
        $partner  = $this->partner->find_all_by(['app_id' => $this->app_id, 'deleted' => 0]);
        $docs     = $this->wakaf_document->find_all_by(['wakaf_id' => $id]);
        $photo    = $this->wakaf_photo->find_all_by(['wakaf_id' => $id]);

        if($is_exist){
            $wakaf  = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Wakaf')
                ->set('data', $wakaf)
                ->set('type', $type)
                ->set('docs', $docs)
                ->set('photo', $photo)
                ->set('branch', $partner)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $app_id     	= $this->session->userdata('user')->app_id;
        $partner_branch = $this->input->post('partner');
        $type           = $this->input->post('type');
        $ktp            = $this->input->post('ktp');
        $npwp           = $this->input->post('npwp');
        $recipient      = $this->input->post('recipient');
        $give           = $this->input->post('give');
        $title          = $this->input->post('title');
        $desc           = $this->input->post('desc');
        $address        = $this->input->post('address');
        $wakaf_address  = $this->input->post('address_wakaf');
        $date_wakaf     = $this->input->post('date');
        $status         = $this->input->post('status');

        $data = array(
            'app_id'            => $app_id,
            'partner_branch_id' => $partner_branch,
            'wakaf_type_id'     => $type,
            'id_card_number'    => $ktp,
            'npwp_number'       => $npwp,
            'recipient_name'    => $recipient,
            'give_name'         => $give,
            'title'             => $title,
            'description'       => $desc,
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

    public function assignment_save($id) {
        $officer    = $this->input->post('officer');
        $title      = $this->input->post('title');
        $description= $this->input->post('desc');
        $type       = "wakaf";

        if(!$id) {
            redirect(site_url('wakaf'), 'refresh');
        } else {
            $data   = [
                'title'         => $title,
                'description'   => $description,
                'officer_id'    => $officer
            ];

            $update = $this->wakaf->update($id, $data);

            if($update) {
                $notif  = $this->officer_notif->insert([
                    "join_id"       => $id,
                    "officer_id"    => $officer,
                    "title"         => $title,
                    "message"       => $description,
                    "type"          => $type
                ]);
            }
        }

        $this->session->set_flashdata('alert', ['msg' => 'Penugasan berhasil di pilih', 'type' => 'success']);
        redirect(site_url('wakaf'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->wakaf->delete($id);

        redirect(site_url('wakaf'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->wakaf->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $officer    = $this->officer->find_by(['id' => $l->officer_id]);

            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['type']    = $l->wakaf_type;
            $row['officer'] = $l->officer_id == NULL ? "-" : $officer->cus_name;
            $row['ktp']     = ($l->id_card_number == NULL) ? "-" : $l->id_card_number;
            $row['npwp']    = ($l->npwp_number == NULL) ? "-" : $l->npwp_number;
            $row['receiver']= $l->recipient_name;
            $row['give']    = $l->give_name;
            $row['title']   = $l->title;
            $row['loc_wakaf']   = $l->address_wakaf;
            $row['branch']  = ($l->branch_name == NULL) ? "-" : $l->branch_name;
            $row['date']    = $l->date_wakaf;
            $row['status']  = $l->status;
            $row['edit']    = site_url('wakaf/edit/'.$l->id);
            $row['delete']  = site_url('wakaf/delete/'.$l->id);
            $row['assign']  = site_url('wakaf/assignment/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->wakaf->count_all($this->app_id),
            "recordsFiltered"   => $this->wakaf->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}