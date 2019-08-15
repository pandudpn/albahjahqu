<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class topics extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('topics/topics_model', 'topic');
        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('index');
    }

    public function add()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add New Topic')
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->topic->find($id);

        if($is_exist){
            $topic = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Topic')
                ->set('data', $topic)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $name           = $this->input->post('name');
        $app_id     	= $this->session->userdata('user')->app_id;

        $data = array(
            'app_id'    => $app_id,
            'name'      => $name
        );

        if(!$id){
            $insert = $this->topic->insert($data);
            
            redirect(site_url('topics'), 'refresh');
        }else{
            $update = $this->topic->update($id, $data);
            redirect(site_url('topics'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->topic->delete($id);

        redirect(site_url('topics'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->topic->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            if($l->status == 'enabled'){
                $status = '<span class="badge badge-success">Enabled</span>';
            }else{
                $status = '<span class="badge badge-danger">Disabled</span>';
            }

            $row[] = $no;
            $row[] = $l->name;
            $row[] = $status;

            $btn   = '<a href="'.site_url('topics/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('topics/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->topic->count_all($this->app_id),
            "recordsFiltered"   => $this->topic->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
