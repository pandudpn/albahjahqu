<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class admin extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('user/user_admin_model', 'user_admin');

        $this->check_login();
    }

    public function index($id=null){

        if($this->input->post())
        {
            $data = array(
                'name' 		=> trim($this->input->post('name')), 
                'username' 	=> strtolower(trim($this->input->post('username'))), 
                'email' 	=> strtolower(trim($this->input->post('email')))
            );

            if(!empty(trim($this->input->post('password'))))
            {
                $data['password'] = sha1(trim($this->input->post('password')).$this->config->item('password_salt'));
            }

            if($id == null)
            {
                $msg = 'insert success';
                
                $user_admin_id = $this->user_admin->insert($data);
            }
            else
            {
                $msg = 'update success';
                $user_admin_id = $this->user_admin->update($id, $data);
                // echo $this->db->last_query();die;
            }

            if($user_admin_id)
            {
                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));
            }
            else
            {
                $msg = 'insert failed';
                $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
            }

            redirect(site_url('user/admin/'.$id), 'refresh');
        }

        if($id != null)
        {
            $data   = $this->user_admin->find($id);
            $title  = 'Edit Data';
        }
        else
        {
            $title  = 'Add new data';
        }

    	$this->template->set('alert', $this->session->flashdata('alert'))
                        ->set('id', $id)
                        ->set('d', $data)
                        ->set('title', $title)
    					->build('user_admin');
    }

    public function delete($id)
    {
        $data = $this->user_admin->find_by(array('id' => $id, 'deleted' => '0'));

        if(!$id || !$data)
        {
            $error = 'Error: data failed';
            $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $error));

            redirect(site_url('user/admin'), 'refresh');
        }
        else
        {
            $delete = $this->user_admin->set_soft_deletes(TRUE);
            $delete = $this->user_admin->delete($id);

            if($delete)
            {
                $msg = 'delete success';
                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

                redirect(site_url('user/admin'), 'refresh');
            }
            else
            {
                $error = 'Error: something error while deleting';
                $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $error));

                redirect(site_url('user/admin'), 'refresh');
            }
        }
    }

    public function datatables()
    {
        $list = $this->user_admin->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->name;
            $row[] = $l->username;
            $row[] = $l->email;

            //button edit & delete
            $btn   = '<a href="'.site_url('user/admin/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="#" onclick="alert_delete(\''.site_url('user/admin/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[] = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->user_admin->count_all(),
            "recordsFiltered"   => $this->user_admin->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}