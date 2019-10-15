<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class achievement_student extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('community/achievement_student_model', 'achievement');
        $this->load->model('community/units_model', 'unit');
        $this->load->model('community/student_model', 'student');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $unit = $this->unit->get_type(['app_id' => $this->app_id]);

        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('unit', $unit)
    		 ->build('achievement_student');
    }

    public function add()
    {
        $unit = $this->unit->get_all(['app_id' => $this->app_id, 'deleted' => 0, 'type !=' => 'kantor']);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Penghargaan Sekolah / Kantor')
            ->set('unit', $unit)
            ->build('achievement_student_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->achievement->find($id);
        $unit       = $this->achievement->get_unit($id, ['app_id' => $this->app_id, 'type !=' => 'kantor']);
        $student    = $this->student->get_all(['app_id' => $this->app_id, 'students.deleted' => 0]);

        if($is_exist){
            $achievement    = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Penghargaan')
                ->set('data', $achievement)
                ->set('student', $student)
                ->set('unit', $unit)
                ->build('achievement_student_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $student        = $this->input->post('student');
        $name           = $this->input->post('name');
        $date           = $this->input->post('date');
        $rank           = $this->input->post('rank');

        $data = array(
            'student_id'    => $student,
            'name'          => $name,
            'date'          => $date,
            'rank'          => $rank
        );
        
        if(!$id){
            $image  = site_url('data/images/icon/trophy.png');

            if(!empty($_FILES['image']['name']))
            {
                $config['upload_path']      = './data/images/achievements/';
                $config['allowed_types']    = '*';
                $config['encrypt_name']     = true;
                
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('image')) {
                    
                } else {
                    $file = $this->upload->data();
                    $image = site_url('data/images/achievements').'/'.$file['file_name'];
                }
            }

            $data['photo']  = $image;

            $insert = $this->achievement->insert($data);
        }else{
            $image  = $this->input->post('old_photo');
            if(!empty($_FILES['image']['name']))
            {
                $config['upload_path']      = './data/images/achievements/';
                $config['allowed_types']    = '*';
                $config['encrypt_name']     = true;
                
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('image')) {
                    
                } else {
                    $file = $this->upload->data();
                    $image = site_url('data/images/achievements').'/'.$file['file_name'];
                }
            }

            $data['photo']  = $image;

            $update = $this->achievement->update($id, $data);
        }

        redirect(site_url('community/achievement_student'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->achievement->delete($id);

        redirect(site_url('community/achievement_student'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->achievement->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['unit']    = $l->unit_name;
            $row['student'] = $l->student_name;
            $row['name']    = $l->name;
            $row['rank']    = $l->rank;
            $row['date']    = date('Y-m-d', strtotime($l->date));
            $row['edit']    = site_url('community/achievement_student/edit/'.$l->id);
            $row['delete']  = site_url('community/achievement_student/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->achievement->count_all($this->app_id),
            "recordsFiltered"   => $this->achievement->count_filtered($this->app_id),
            "data"              => $data,
        );

        //output to json format
        echo json_encode($output);
    }

    public function getStudent() {
        $unit   = $this->input->post('unit');

        $student    = $this->student->get_all(['app_id' => $this->app_id, 'unit_id' => $unit, 'students.deleted' => 0]);

        $status = FALSE;
        $data   = '';

        if($student) {
            $status = TRUE;
            $data   = $student;
        }

        echo json_encode([
            'status' => $status,
            'data'   => $data
        ]);
    }

}
