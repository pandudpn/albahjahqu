<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class alumni extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('alumni/alumni_model', 'alumni');
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
    		 ->build('index');
    }

    public function edit($id)
    {
        $is_exist   = $this->alumni->find($id);
        $school = $this->school->find_all_by(['app_id' => $this->app_id, 'deleted' => 0]);

        if($is_exist){
            $student = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Data Siswa / Santri - '.$student->name)
                ->set('data', $student)
                ->set('school', $school)
                ->build('form');
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
        $birth          = $this->input->post('place');
        $birthday       = $this->input->post('date');
        $graduate       = $this->input->post('graduate');

        $data = array(
            'school_id' => $school,
            'nis'       => $nis,
            'name'      => $name,
            'gender'    => $gender,
            'address'   => $address,
            'birth_place'=> $birth,
            'birthday'  => $birthday,
            'graduate_date' => $graduate
        );
        
        $update = $this->alumni->update($id, $data);

        redirect(site_url('alumni'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->alumni->delete($id);

        redirect(site_url('alumni'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->alumni->get_datatables($this->app_id);
        
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
            $row['month_grad']= date('n', strtotime($l->graduate_date));
            $row['year_grad'] = date('Y', strtotime($l->graduate_date));
            $row['edit']    = site_url('alumni/edit/'.$l->id);
            $row['delete']  = site_url('alumni/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->alumni->count_all($this->app_id),
            "recordsFiltered"   => $this->alumni->count_filtered($this->app_id),
            "data"              => $data,
        );

        //output to json format
        echo json_encode($output);
    }

}
