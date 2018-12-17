<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class admin extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('user/user_admin_model', 'user_admin');
        $this->load->model('dealers/dealer_model', 'dealer');

        $this->check_login();
    }

    public function index($id=null){

    	$this->template->set('alert', $this->session->flashdata('alert'))
                        ->set('id', $id)
                        ->set('d', $data)
                        ->set('title', $title)
    					->build('user_admin');
    }

    public function add()
    {
        $dealers = $this->dealer->find_all_by(array('deleted' => '0'));

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add User Admin')
            ->set('dealers', $dealers)
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->user_admin->find($id);

        if($is_exist){
            $user_admin = $is_exist;
            $dealers    = $this->dealer->find_all_by(array('deleted' => '0'));

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit User admin')
                ->set('data', $user_admin)
                ->set('dealers', $dealers)
                ->build('form');
        }
    }

    public function save()
    {
        $id         = $this->input->post('id');
        $password   = $this->input->post('password');
        $name       = $this->input->post('name');
        $email      = strtolower($this->input->post('email'));
        $phone      = $this->input->post('phone');
        $role       = $this->input->post('role');
        $dealer_id  = $this->input->post('dealer_id');

        $data = array(
            'name'      => $name,
            'email'     => $email,
            'phone'     => $phone,
            'role'      => $role,
            'account_user' => 0
        );

        if(!empty($dealer_id))
        {
            $data['dealer_id']   = $dealer_id;
        }
        else
        {
            $data['dealer_id']   = NULL;
        }
        
        if(!$id)
        {
            $data['password']   = sha1($password.$this->config->item('password_salt'));
            $insert             = $this->user_admin->insert($data);

            redirect(site_url('user/admin'), 'refresh');
        }
        else
        {
            if(!empty($password))
            {
                $data['password'] = sha1($password.$this->config->item('password_salt'));
            }

            $update = $this->user_admin->update($id, $data);
            redirect(site_url('user/admin'), 'refresh');
        }
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
            $row[] = $l->email;

            //button edit & delete
            $btn   = '<a href="'.site_url('user/admin/edit/'.$l->id).'" class="btn btn-success btn-sm">
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