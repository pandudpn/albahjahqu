<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class user extends Admin_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('user/user_model', 'user');
        $this->load->model('user/work_group_model', 'work_group');
        $this->load->model('user/work_unit_model', 'work_unit');

        $this->check_login();
    }

    public function index($id=null){

         $this->template->set('alert', $this->session->flashdata('alert'))
                        ->build('index');
    }

    public function create(){

        if($this->input->post())
        {
            $data               = $this->input->post();
            $data['password']   = sha1($data['password']);

            unset($data['work_group']);

            $user_id = $this->user->insert($data);

            if($user_id)
            {
                $msg = 'save user success';
                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

                redirect(site_url('user/edit/'.$user_id), 'refresh');
            }
            else
            {
                $msg = 'save user failed';
                $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));

                redirect(site_url('user/create'), 'refresh');
            }
        }

        $work_groups    = $this->work_group->find_all_by(array('deleted' => '0'));
        $work_units     = $this->work_unit->find_all_by(array('deleted' => '0'));

         $this->template->set('alert', $this->session->flashdata('alert'))
                        ->set('title', 'Create User')
                        ->set('work_groups', $work_groups)
                        ->set('work_units', $work_units)
                        ->build('form');
    }

    public function edit($id=null){

        $data = $this->user->find_by(array('id' => $id, 'deleted' => '0'));

        if(!$id || !$data)
        {
            $error = 'Error: data failed';
            $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $error));

            redirect(site_url('user'), 'refresh');
        }

        if($this->input->post())
        {
            $data               = $this->input->post();

            if(empty($data['password']))
            {
                unset($data['password']);
            }
            else
            {
                $data['password']   = sha1($data['password']);    
            }
            
            unset($data['work_group']);

            $user_id = $this->user->update($id, $data);

            if($user_id)
            {
                $msg = 'save user success';
                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

                redirect(site_url('user/edit/'.$id), 'refresh');
            }
            else
            {
                $msg = 'save user failed';
                $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));

                redirect(site_url('user/create'), 'refresh');
            }
        }

        $work_groups    = $this->work_group->find_all_by(array('deleted' => '0'));
        $work_units     = $this->work_unit->find_all_by(array('deleted' => '0'));
        $data           = $this->user->find($id);

         $this->template->set('alert', $this->session->flashdata('alert'))
                        ->set('title', 'Edit User')
                        ->set('work_groups', $work_groups)
                        ->set('work_units', $work_units)
                        ->set('d', $data)
                        ->build('form');
    }

    public function upload()
    {
        if($this->input->post())
        {
            $config['upload_path']      = './data/files/';
            $config['allowed_types']    = 'csv';
            $config['max_size']         = 0;

            $this->load->library('upload', $config);

            if(!empty($_FILES['file']['name']))
            {
                
                if(!$this->upload->do_upload('file')) {
                    $msg = $this->upload->display_errors('','');
                    $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
                    redirect(site_url('user/upload'), 'refresh');
                } 
                else 
                {
                    $file       = $this->upload->data();

                    $row = 1;
                    if (($handle = fopen('./data/files/'.$file['file_name'], "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $num = count($data);
                            // echo "<p> $num fields in line $row: <br /></p>\n";
                            $row++;

                            if($row > 2){

                                if(count($data) != 6)
                                {
                                    $msg = 'field count is wrong. please check again';
                                    $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
                                    redirect(site_url('user/upload'), 'refresh');
                                    exit;
                                }

                                if(!empty($this->work_group->find_by('name', strtoupper(trim($data[2])))) || !empty($this->work_unit->find_by('unit', strtoupper(trim($data[3])))))
                                {
                                    if($this->user->find_by('npp', trim($data[1])) == true)
                                    {
                                        $msg = 'row '.($row - 1).' is using npp that already used. please check again';
                                        $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
                                        redirect(site_url('user/upload'), 'refresh');
                                    }
                                    else
                                    {
                                        $user_data[] = array(
                                            'name'      => trim($data[0]), 
                                            'npp'       => trim($data[1]), 
                                            'group'     => $this->work_group->find_by('name', strtoupper(trim($data[2])))->id, 
                                            'uker'      => $this->work_unit->find_by('unit', strtoupper(trim($data[3])))->id, 
                                            'email'     => trim($data[4]), 
                                            'password'  => sha1(trim($data[5]))
                                        );
                                    }
                                }
                                else
                                {
                                    $msg = 'row '.($row - 1).' is using work group or work unit wrong. please check again';
                                    $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
                                    redirect(site_url('user/upload'), 'refresh');
                                }
                            }
                        }

                        fclose($handle);
                    }

                    $insert_batch = $this->db->insert_batch('users', $user_data); 

                    if($insert_batch)
                    {
                        $msg = 'insert '.($row - 2).' row(s) success';
                        $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

                        redirect(site_url('user/upload'), 'refresh');
                    }
                    else
                    {
                        $msg = 'something is wrong. please try again';
                        $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
                        redirect(site_url('user/upload'), 'refresh');
                    }
                }
            }
            else
            {
                $msg = 'please upload csv file.';
                $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
                redirect(site_url('user/upload'), 'refresh');
            }

            
        }
            
         $this->template->set('alert', $this->session->flashdata('alert'))
                        ->set('title', 'Upload data user')
                        ->set('work_groups', $work_groups)
                        ->set('work_units', $work_units)
                        ->build('upload');
    }

    public function work_group_unit()
    {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        date_default_timezone_set("Asia/Jakarta");

        $filename  = "work_group_unit.csv";
        $query     = "SELECT work_groups.name, work_units.unit FROM work_groups LEFT JOIN work_units ON work_groups.code = work_units.group ORDER BY work_groups.id, work_units.unit asc";

        $delimiter = ",";
        $newline   = "\r\n";

        $result    = $this->db->query($query);
        $csv_file  = $this->dbutil->csv_from_result($result, $delimiter, $newline);

        force_download($filename, $csv_file);
    }

    public function delete($id=null)
    {
        $data = $this->user->find_by(array('id' => $id, 'deleted' => '0'));

        if(!$id || !$data)
        {
            $error = 'Error: data failed';
            $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $error));

            redirect(site_url('user'), 'refresh');
        }
        else
        {
            $delete = $this->user->set_soft_deletes(TRUE);
            $delete = $this->user->delete($id);

            if($delete)
            {
                $msg = 'delete success';
                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

                redirect(site_url('user'), 'refresh');
            }
            else
            {
                $error = 'Error: something error while deleting';
                $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $error));

                redirect(site_url('user'), 'refresh');
            }
        }
    }

    public function datatables()
    {
        $list = $this->user->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->name;
            $row[] = $l->npp;
            $row[] = $l->unit;
            $row[] = $l->email;
            $row[] = $l->status;
            $row[] = $l->role;

            //button edit & delete
            $btn   = '<a href="'.site_url('user/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="#" onclick="alert_delete(\''.site_url('user/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[] = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->user->count_all(),
            "recordsFiltered"   => $this->user->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}