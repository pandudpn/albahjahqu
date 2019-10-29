<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class zakat extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('zakat/zakat_model', 'zakat');
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
            ->set('title', 'Add Zakat')
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->zakat->find($id);

        if($is_exist){
            $zakat = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Zakat')
                ->set('data', $zakat)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $app_id     	= $this->session->userdata('user')->app_id;
        $name           = $this->input->post('name');

        $data = array(
            'app_id'    => $app_id,
            'name'      => $name
        );

        if(!$id){
            $insert = $this->zakat->insert($data);   
        }else{
            $update = $this->zakat->update($id, $data);
        }
        
        redirect(site_url('zakat'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->zakat->delete($id);

        redirect(site_url('zakat'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->zakat->get_datatables($this->app_id);
        // var_dump($list); die;
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row[] = $no;
            $row[] = $l->name;

            $btn   = '<a href="'.site_url('zakat/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('zakat/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->zakat->count_all($this->app_id),
            "recordsFiltered"   => $this->zakat->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
