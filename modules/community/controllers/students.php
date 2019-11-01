<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class students extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('community/student_model', 'student');
        $this->load->model('community/partner_branch_models', 'branch');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;
        // $this->url      = "https://partner.okbabe.technology/";
        $this->url      = "localhost:8080/";

        // $this->check_login();
    }

    public function index()
    {
        $branch = $this->branch->get_type(['app_id' => $this->app_id, 'type !=' => 'kantor']);

        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('branch', $branch)
    		 ->build('students');
    }

    public function add()
    {
        $branch = $this->branch->find_all_by(['app_id' => $this->app_id, 'type !=' => 'kantor']);
        // print_r($branch); die;

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Siswa / Santi Baru')
            ->set('branch', $branch)
            ->build('students_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->student->find($id);
        $branch     = $this->branch->find_all_by(['app_id' => $this->app_id, 'type !=' => 'kantor']);

        if($is_exist){
            $student = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Data Siswa / Santri - '.$student->name)
                ->set('data', $student)
                ->set('branch', $branch)
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
            'partner_branch_id'     => $unit,
            'partner_id'            => 3,
            'student_number'        => $nis,
            'name'                  => $name,
            'gender'                => $gender,
            'address'               => $address,
            'status'                => $status,
            'birthplace'            => $birth,
            'birthday'              => $birthday
        );

        $partner_branch = $this->branch->find($unit);

        $data['partner_branch_eva']     = $partner_branch->partner_branch_eva;
        $data['partner_branch_code']    = $partner_branch->partner_branch_code;
        
        if(!$id){
            if($gender == 'L') {
                $image  = site_url('data/images/people/default.png');
            }elseif($gender == 'P'){
                $image  = site_url('data/images/people/default_cw.png');
            }

            if(!empty($_FILES['image']['name']))
            {
                $config['upload_path']      = './data/images/people/';
                $config['allowed_types']    = '*';
                $config['encrypt_name']     = true;
                
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('image')) {
                    $this->session->set_flashdata('alert', ['msg' => $this->upload->display_errors(), 'type' => 'danger']);
                    redirect(site_url('community/students'), 'refresh');
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
                    $this->session->set_flashdata('alert', ['msg' => $this->upload->display_errors(), 'type' => 'danger']);
                    redirect(site_url('community/students'), 'refresh');
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

    public function import(){
        $this->load->library('Guzzle');
        if(!empty($_FILES['file']['name']))
        {
            // print_r($_FILES['file']); die;
            $config['upload_path']      = './data/excel/';
            $config['allowed_types']    = 'csv';
            $config['encrypt_name']     = true;
            
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('file')) {
                $this->session->set_flashdata('alert', ['msg' => $this->upload->display_errors(), 'type' => 'danger']);
                redirect(site_url('community/students'), 'refresh');
            } else {
                $file = $this->upload->data();
                $file_name  = $file['file_name'];
                $image = site_url('data/excel').'/'.$file['file_name'];
            }
        }

        $partner        = $this->input->post('partner');

        $partner_branch = $this->branch->find($partner);

        try {
            $resources  = 'file/student';
            $url        = $this->url.$resources;
            
            $client      = new GuzzleHttp\Client([ 
                'base_uri'   => $this->url,
                'Accept'     => 'application/json'
            ]);
            $response   = $client->post($url, [
                'multipart' => [
                    // 'Content-type'          => 'multipart/form-data',
                    [
                        'app_id'                => $this->app_id,
                        'partner_branch_id'     => $partner,
                        'partner_id'            => 3,
                        'partner_branch_eva'    => $partner_branch->partner_branch_eva,
                        'partner_branch_code'   => $partner_branch->partner_branch_code,
                        // 'name'                  => $file_name,
                        'student_file'          => fopen($image, 'r')
                    ]
                ]
                // 'form_params'   => [
                    
                // ],
                // 'headers'   => [
                //     'Content-type'          => 'multipart/form-data'
                // ]
            ]);

            $json       = $response->getBody();
            echo json_encode($json);
        } catch(GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            $result   = $response->getBody()->getContents();
            echo json_encode($result);
        }
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
            $row['partner'] = $l->partner_name;
            $row['name']    = $l->name;
            $row['nis']     = $l->student_number;
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

        $student    = $this->student->find_by(['deleted' => 0, 'student_number' => $nis, 'partner_branch_id' => $unit]);

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
