<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class registrations extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('students/students_model', 'students');
        $this->load->model('students/student_profiles_model', 'student_profiles');
        $this->load->model('students/student_documents_model', 'student_documents');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('registrations');
    }

    public function add()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Santri Baru')
            ->build('registration_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->students->find($id);

        if($is_exist){
            $unit = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah data santri')
                ->set('data', $unit)
                ->build('registration_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $app_id         = $this->app_id;

        if($date == ''){
            $date   = null;
        }

        $data = array(
            'app_id'    => $app_id
        );
        
        if(!$id){
            $insert = $this->students->insert($data);
        }else{
            $update = $this->students->update($id, $data);
        }

        redirect(site_url('students/registrations'), 'refresh');
    }

    public function profiles($student_id) {
        $student    = $this->students->find($student_id);
        $students   = $this->student_profiles->find_all_by(['partner_student_id' => $student_id, 'role !=' => 'child']);
        $documents  = $this->student_documents->find_all_by(['partner_student_id' => $student_id]);

        $this->template
             ->set('title', "Profile - $student->name")
             ->set('student', $student)
             ->set('profiles', $students)
             ->set('documents', $documents)
             ->build('profiles');
    }

    public function delete($id)
    {
        $delete = $this->students->delete($id);

        redirect(site_url('students/registrations'), 'refresh');
    }

    public function datatables()
    {
        $list       = $this->students->get_datatables($this->app_id);
        $parents    = $this->students->get_parents($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            //explode the date to get month, day and year
            $birthDate = explode("-", date('m-d-Y', strtotime($l->birthday)));
            //get age from date or birthdate
            $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
                ? ((date("Y") - $birthDate[2]) - 1)
                : (date("Y") - $birthDate[2]));
            
            $dad    = '';
            $mom    = '';
            foreach($parents AS $parent) {
                if($l->id == $parent->partner_student_id) {
                    if($parent->role == 'father') {
                        $dad    = $parent->name;
                    }elseif($parent->role == 'mother') {
                        $mom    = $parent->name;
                    }
                }
            }

            $row['no']      = $no;
            $row['name']    = $l->name;
            $row['age']     = $age;
            $row['address'] = $l->address;
            $row['partner'] = $l->partner_name;
            $row['dad']     = $dad;
            $row['mom']     = $mom;
            $row['details'] = site_url('students/registrations/profiles/'.$l->id);
            $row['edit']    = site_url('students/registrations/edit/'.$l->id);
            $row['delete']  = site_url('students/registrations/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->students->count_all($this->app_id),
            "recordsFiltered"   => $this->students->count_filtered($this->app_id),
            "data"              => $data,
        );

        //output to json format
        echo json_encode($output);
    }

}
