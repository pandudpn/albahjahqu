<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class officers extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('officers/officer_model', 'officer');
        $this->load->model('user/user_admin_model', 'user_admin');
        $this->load->model('customers/customer_model', 'customer');

        $this->app_id   = $this->session->userdata('user')->app_id;
        $this->check_login();
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('title', 'Data-data petugas')
    		 ->build('index');
    }

    public function add()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Petugas Baru')
            ->build('form');
    }

    public function save()
    {
        $app_id     	= $this->session->userdata('user')->app_id;
        $cus_phone      = $this->input->post('cus_phone');
        $email          = $this->input->post('email');
        $password       = sha1($this->input->post('password').$this->config->item('password_salt'));
        $type           = $this->input->post('type');

        $data = array(
            'app_id'            => $app_id,
            'name'              => "Petugas",
            'phone'             => $cus_phone,
            'email'             => $email,
            'password'          => $password,
            'officer'           => 'yes'
        );

        $insert = $this->user_admin->insert($data);

        if($insert) {
            $customer   = $this->customer->find_by($cus_phone);

            if($customer) {
                $ins    = $this->officer->insert([
                    'app_id'    => $app_id,
                    'admin_id'  => $insert,
                    'cus_id'    => $customer->id,
                    'cus_phone' => $customer->phone,
                    'cus_name'  => $customer->name,
                    'type'      => $type
                ]);
            } else {
                $delete = $this->user_admin->delete($insert);
                $this->session->set_flashdata('alert', ['msg' => 'Nomer Telepon tidak ditemukan di Aplikasi. Silahkan masukan nomer telepon dengan benar.', 'type' => 'danger']);
                redirect(site_url('officers/add'), 'refresh');
            }
        }
        
        redirect(site_url('officers'), 'refresh');
    }

    public function delete($id)
    {
        $find   = $this->officer->find($id);

        if($find) {
            $delete = $this->user_admin->delete($find->admin_id);
            $delee  = $this->officer->delete($id);
        }

        redirect(site_url('officers'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->officer->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['email']   = $l->email;
            $row['phone']   = $l->cus_phone;
            $row['name']    = $l->cus_name;
            // $row['edit']    = site_url('officers/edit/'.$l->id);
            $row['delete']  = site_url('officers/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->officer->count_all($this->app_id),
            "recordsFiltered"   => $this->officer->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}