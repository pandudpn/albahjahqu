<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class streaming extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('streaming/streaming_model', 'streaming');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();

        if($this->session->userdata('user')->dealer_id == 41) {
            redirect(site_url());
        }
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
            ->set('title', 'Tambah Link Streaming Terbaru')
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist   = $this->streaming->find($id);

        if($is_exist){
            $streaming = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Link Streaming')
                ->set('data', $streaming)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $app_id         = $this->app_id;
        $url            = $this->input->post('url');
        $type           = $this->input->post('type');
        $name           = $this->input->post('name');

        $data = array(
            'app_id'    => $app_id,
            'url'       => $url,
            'type'      => $type,
            'name'      => $name
        );
        
        if(!$id){
            $insert = $this->streaming->insert($data);
        }else{
            $update = $this->streaming->update($id, $data);
        }

        redirect(site_url('streaming'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->streaming->delete($id);

        redirect(site_url('streaming'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->streaming->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['name']    = ($l->name == NULL) ? '-' : $l->name;
            $row['url']     = $l->url;
            $row['type']    = strtoupper($l->type);
            $row['status']  = $l->status;
            $row['edit']    = site_url('streaming/edit/'.$l->id);
            $row['delete']  = site_url('streaming/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->streaming->count_all($this->app_id),
            "recordsFiltered"   => $this->streaming->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
