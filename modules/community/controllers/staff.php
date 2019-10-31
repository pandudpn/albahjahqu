<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class staff extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('community/staff_model', 'staff');
        $this->load->model('community/units_model', 'unit');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $position = $this->staff->get_position(['app_id' => $this->app_id]);

        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('position', $position)
    		 ->build('staff');
    }

    public function add()
    {
        $unit = $this->unit->get_all(['app_id' => $this->app_id, 'deleted' => 0]);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Pengurus / Guru Baru')
            ->set('unit', $unit)
            ->build('staff_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->staff->find($id);
        $unit       = $this->unit->get_all(['app_id' => $this->app_id, 'deleted' => 0]);

        if($is_exist){
            $staff = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Data Pengurus / Guru - '.$staff->name)
                ->set('data', $staff)
                ->set('unit', $unit)
                ->build('staff_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $name           = $this->input->post('name');
        $unit           = $this->input->post('unit');
        $phone          = $this->input->post('phone');
        $gender         = $this->input->post('gender');
        $birthplace     = $this->input->post('place');
        $birthday       = $this->input->post('date');
        $address        = $this->input->post('address');
        $position       = $this->input->post('position');

        $data = array(
            'unit_id'       => $unit,
            'name'          => $name,
            'phone'         => $phone,
            'gender'        => $gender,
            'birthplace'    => $birthplace,
            'birthday'      => $birthday,
            'address'       => $address,
            'position'      => $position,
            'status'        => 'active'
        );

        // print_r($this->input->post()); 
        // print_r($_FILES['image']['name']);
        // die;

        
        if(!$id){
            if($gender == 'Laki-laki') {
                $image  = site_url('data/images/people/default.png');
            }elseif($gender == 'Perempuan'){
                $image  = site_url('data/images/people/default_cw.png');
            }

            if(!empty($_FILES['image']['name']))
            {
                $config['upload_path']      = './data/images/people/';
                $config['allowed_types']    = '*';
                $config['encrypt_name']     = true;
                
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('image')) {
                    
                } else {
                    $file = $this->upload->data();
                    $image = site_url('data/images/people').'/'.$file['file_name'];
                }
            }

            $data['photo']  = $image;

            $insert = $this->staff->insert($data);
        }else{
            $image  = $this->input->post('old_photo');
            if(!empty($_FILES['image']['name']))
            {
                $config['upload_path']      = './data/images/people/';
                $config['allowed_types']    = '*';
                $config['encrypt_name']     = true;
                
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('image')) {
                    
                } else {
                    $file = $this->upload->data();
                    $image = site_url('data/images/people').'/'.$file['file_name'];
                }
            }

            $data['photo']  = $image;

            $update = $this->staff->update($id, $data);
        }

        redirect(site_url('community/staff'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->staff->delete($id);

        redirect(site_url('community/staff'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->staff->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['unit']    = $l->unit_name;
            $row['name']    = $l->name;
            $row['address'] = $l->address;
            $row['phone']   = $l->phone;
            $row['status']  = $l->status;
            $row['edit']    = site_url('community/staff/edit/'.$l->id);
            $row['delete']  = site_url('community/staff/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->staff->count_all($this->app_id),
            "recordsFiltered"   => $this->staff->count_filtered($this->app_id),
            "data"              => $data,
        );

        //output to json format
        echo json_encode($output);
    }

}
