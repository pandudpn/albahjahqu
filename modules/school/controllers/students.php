<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class students extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('school/student_model', 'student');
        $this->load->model('school/school_model', 'school');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $school = $this->school->get_all(['app_id' => $this->app_id, 'deleted' => 0]);
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('school', $school)
    		 ->build('student');
    }

    public function add()
    {
        $school = $this->school->get_all(['app_id' => $this->app_id, 'deleted' => 0]);
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Siswa / Santi Baru')
            ->set('school', $school)
            ->build('student_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->student->find($id);
        $school = $this->school->get_all(['app_id' => $this->app_id, 'deleted' => 0]);

        if($is_exist){
            $student = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Data Siswa / Santri - '.$student->name)
                ->set('data', $student)
                ->set('school', $school)
                ->build('student_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $school         = $this->input->post('school');
        $nis            = $this->input->post('nis');
        $name           = $this->input->post('name');
        $gender         = $this->input->post('gender');
        $address        = $this->input->post('address');
        $status         = $this->input->post('status');
        $birth          = $this->input->post('place');
        $birthday       = $this->input->post('date');

        $data = array(
            'school_id' => $school,
            'nis'       => $nis,
            'name'      => $name,
            'gender'    => $gender,
            'address'   => $address,
            'status'    => $status,
            'birth_place'=> $birth,
            'birthday'  => $birthday
        );

        if($status == 'graduate'){
            $data['graduate_date']  = date('Y-m-d');
        }

        
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

            $insert = $this->student->insert($data);
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

            $update = $this->student->update($id, $data);
        }

        redirect(site_url('school/students'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->student->delete($id);

        redirect(site_url('school/students'), 'refresh');
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
            $row['school']  = $l->school_name;
            $row['name']    = $l->name;
            $row['nis']     = $l->nis;
            $row['address'] = $l->address;
            $row['gender']  = $l->gender;
            $row['status']  = $l->status;
            $row['edit']    = site_url('school/students/edit/'.$l->id);
            $row['delete']  = site_url('school/students/delete/'.$l->id);

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

        $student    = $this->student->find_by(['deleted' => 0, 'nis' => $nis]);

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
