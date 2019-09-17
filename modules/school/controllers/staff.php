<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class staff extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('school/staff_model', 'staff');
        $this->load->model('school/school_model', 'school');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $school = $this->school->find_all_by(['app_id' => $this->app_id, 'deleted' => 0]);
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('school', $school)
    		 ->build('staff');
    }

    public function add()
    {
        $school = $this->school->find_all_by(['app_id' => $this->app_id, 'deleted' => 0]);
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Buat Staff Baru')
            ->set('school', $school)
            ->build('staff_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->staff->find($id);
        $school = $this->school->find_all_by(['app_id' => $this->app_id, 'deleted' => 0]);

        if($is_exist){
            $staff = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Data Staff - '.$staff->name)
                ->set('data', $staff)
                ->set('school', $school)
                ->build('staff_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $school         = $this->input->post('school');
        $name           = $this->input->post('name');
        $position       = $this->input->post('position');
        $phone          = $this->input->post('phone');
        $address        = $this->input->post('address');

        $data = array(
            'school_id' => $school,
            'name'      => $name,
            'position'  => $position,
            'phone'     => $phone,
            'address'   => $address
        );
        
        if(!$id){
            $image  = site_url('data/images/people/default.png');

            if(!empty($_FILES['image']['name']))
            {
                $config['upload_path']      = './data/images/people/';
                $config['allowed_types']    = '*';
                $config['max_size']         = 1024;
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
                $config['max_size']         = 1024;
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

        redirect(site_url('school/staff'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->staff->delete($id);

        redirect(site_url('school/staff'), 'refresh');
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
            $row['school']  = $l->school_name;
            $row['name']    = $l->name;
            $row['position']= ucfirst($l->position);
            $row['phone']   = $l->phone;
            $row['address'] = $l->address;
            $row['edit']    = site_url('school/staff/edit/'.$l->id);
            $row['delete']  = site_url('school/staff/delete/'.$l->id);

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
