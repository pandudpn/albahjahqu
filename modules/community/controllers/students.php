<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class students extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('community/student_model', 'student');
        $this->load->model('community/units_model', 'unit');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $unit = $this->unit->get_type(['app_id' => $this->app_id, 'type !=' => 'kantor']);

        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('unit', $unit)
    		 ->build('students');
    }

    public function add()
    {
        $unit = $this->unit->get_all(['app_id' => $this->app_id, 'deleted' => 0, 'type !=' => 'kantor']);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Siswa / Santi Baru')
            ->set('unit', $unit)
            ->build('students_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->student->find($id);
        $unit       = $this->unit->get_all(['app_id' => $this->app_id, 'deleted' => 0, 'type !=' => 'kantor']);

        if($is_exist){
            $student = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Data Siswa / Santri - '.$student->name)
                ->set('data', $student)
                ->set('unit', $unit)
                ->build('students_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $unit           = $this->input->post('unit');
        $nis            = $this->input->post('nis');
        $name           = $this->input->post('name');
        $gender         = $this->input->post('gender');
        $address        = $this->input->post('address');
        $status         = $this->input->post('status');
        $birth          = $this->input->post('place');
        $birthday       = $this->input->post('date');

        $data = array(
            'unit_id'   => $unit,
            'nis'       => $nis,
            'name'      => $name,
            'gender'    => $gender,
            'address'   => $address,
            'status'    => $status,
            'birthplace'=> $birth,
            'birthday'  => $birthday
        );

        
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

            $insert = $this->student->insert($data);
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

            $update = $this->student->update($id, $data);
        }

        redirect(site_url('community/students'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->student->delete($id);

        redirect(site_url('community/students'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->student->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['unit']    = $l->unit_name;
            $row['name']    = $l->name;
            $row['nis']     = $l->nis;
            $row['address'] = $l->address;
            $row['gender']  = $l->gender;
            $row['status']  = $l->status;
            $row['edit']    = site_url('community/students/edit/'.$l->id);
            $row['delete']  = site_url('community/students/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->student->count_all($this->app_id),
            "recordsFiltered"   => $this->student->count_filtered($this->app_id),
            "data"              => $data,
        );

        //output to json format
        echo json_encode($output);
    }

    public function checknis(){
        $nis    = $this->input->post('nis');
        $unit   = $this->input->post('unit');

        $student    = $this->student->find_by(['deleted' => 0, 'nis' => $nis, 'unit_id' => $unit]);

        $status = 'success';
        $data   = '';
        if($student){
            $status = 'error';
            $data   = 'NIS sudah ada.';
        }

        echo json_encode([
            'status' => $status,
            'data'   => $data
        ]);
    }

}
