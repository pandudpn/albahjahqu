<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class registrations extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('students/students_model', 'students');
        $this->load->model('students/student_profiles_model', 'student_profiles');
        $this->load->model('students/student_documents_model', 'student_documents');
        $this->load->model('community/partner_branch_models', 'partner_branch');

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
        $dad        = $this->student_profiles->find_by(['partner_student_id' => $id, 'role' => 'father']);
        $mom        = $this->student_profiles->find_by(['partner_student_id' => $id, 'role' => 'mother']);
        $address    = $this->student_profiles->find_by(['partner_student_id' => $id, 'role' => 'child']);
        $branch     = $this->partner_branch->find_all_by(['app_id' => $this->app_id, 'type' => 'HO', 'deleted' => 0]);

        if($is_exist){
            $student = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah data santri')
                ->set('data', $student)
                ->set('dad', $dad)
                ->set('mom', $mom)
                ->set('address', $address)
                ->set('branch', $branch)
                ->build('registration_form');
        }
    }

    public function save()
    {
        $partner        = $this->input->post('partner');
        $partner_branch = $this->partner_branch->find_by(['id' => $partner]);

        $id       		= $this->input->post('id');
        $momid          = $this->input->post('momid');
        $dadid          = $this->input->post('dadid');

        $name           = $this->input->post('name');
        $class          = $this->input->post('class');
        $birthplace     = $this->input->post('birthplace');
        $birthday       = $this->input->post('birthday');
        $city           = $this->input->post('city');
        $district       = $this->input->post('district');
        $village        = $this->input->post('village');
        $address        = $this->input->post('address');
        $rt             = $this->input->post('rt');
        $rw             = $this->input->post('rw');

        // dad
        $dadname        = $this->input->post('dadname');
        $dadnum         = $this->input->post('dadnum');
        $dadwork        = $this->input->post('dadwork');
        // mom
        $momname        = $this->input->post('momname');
        $momnum         = $this->input->post('momnum');
        $momwork        = $this->input->post('momwork');

        $data = array(
            'partner_branch_id'     => $partner_branch->id,
            'partner_branch_eva'    => $partner_branch->partner_branch_eva,
            'partner_branch_code'   => $partner_branch->partner_branch_code,
            'name'                  => $name,
            'grade'                 => $class,
            'birthplace'            => $birthplace,
            'birthday'              => $birthday
        );

        $dataDad    = array(
            'partner_branch_id'     => $partner_branch->id,
            'name'                  => $dadname,
            'phone'                 => $dadnum,
            'work'                  => $dadwork,
            'city'                  => $city,
            'district'              => $district,
            'village'               => $village,
            'neighborhood'          => $rt,
            'citizens'              => $rw
        );

        $dataMom    = array(
            'partner_branch_id'     => $partner_branch->id,
            'name'                  => $momname,
            'phone'                 => $momnum,
            'work'                  => $momwork,
            'city'                  => $city,
            'district'              => $district,
            'village'               => $village,
            'neighborhood'          => $rt,
            'citizens'              => $rw
        );

        $update_student = $this->students->update($id, $data);
        if($update_student) {
            $update_dad = $this->student_profiles->update($dadid, $dataDad);
            $update_mom = $this->student_profiles->update($momid, $dataMom);
        } else {
            $this->session->set_flashdata('alert', ['msg' => 'Terjadi kesalahan ketika merubah data. Silahkan coba beberapa saat lagi.', 'type' => 'danger']);
            redirect(site_url('students/registrations/edit/'.$id), 'refresh');
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

    public function approve($id) {
        $nis    = $this->input->post('nis');
        $update = $this->students->update($id, ['status' => 'active', 'student_number' => $nis]);

        if($update) {
            $response   = [
                'status'    => TRUE,
                'data'      => site_url('students/registrations')
            ];
        } else {
            $response   = [
                'status'    => FALSE,
                'data'      => 'Terjadi kesalahan ketika mengubah data. Silahkan coba kembali.'
            ];
        }

        echo json_encode($response);
    }

    public function check_student_number() {
        $nis    = $this->input->post('nis');
        $branch = $this->input->post('branch');

        $check  = $this->students->find_by(['app_id' => $this->app_id, 'partner_branch_id' => $branch, 'student_number' => $nis, 'deleted' => 0]);

        if($check) {
            $response   = [
                "status"    => TRUE,
                "data"      => "Maaf, nomer induk siswa tersebut sudah terdaftar. Silahkan gunakan nomer yang lainnya. Terima kasih!"
            ];
        } else {
            $response   = [
                "status"    => FALSE
            ];
        }

        echo json_encode($response);
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
            $row['branch']  = $l->partner_branch_id;
            $row['dad']     = $dad;
            $row['mom']     = $mom;
            $row['details'] = site_url('students/registrations/profiles/'.$l->id);
            $row['edit']    = site_url('students/registrations/edit/'.$l->id);
            $row['delete']  = site_url('students/registrations/delete/'.$l->id);
            $row['approve'] = site_url('students/registrations/approve/'.$l->id);

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
