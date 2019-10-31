<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class albums extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('albums/albums_model', 'album');

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
            ->set('title', 'Add New Album')
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->album->find($id);

        if($is_exist){
            $album = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Album')
                ->set('data', $album)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $title     		= $this->input->post('title');
        $app_id     	= $this->session->userdata('user')->app_id;

        $data = array(
            'title'      => $title,
            'app_id'     => $app_id,
            'type'       => 'albums'
        );

        if(!empty($_FILES['image']['name']))
        {
        	$config['upload_path']      = './data/images/albums/';
	        $config['allowed_types']    = 'jpg|jpeg|png|gif';
	        $config['max_size']         = 1024;
	        $config['encrypt_name']     = true;
	        
	        $this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('image')) {
                $this->session->set_flashdata('alert', ['msg' => $this->upload->display_errors(), 'type' => 'danger']);
                redirect(site_url('albums/add'), 'refresh');
	        } else {
	            $file = $this->upload->data();
	            $data['image'] = site_url('data/images/albums').'/'.$file['file_name'];
	        }
        }
        
        if(!$id){

            $insert = $this->album->insert($data);
            
            redirect(site_url('albums'), 'refresh');
        }else{

            $update = $this->album->update($id, $data);
            redirect(site_url('albums'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->album->delete($id);

        redirect(site_url('albums'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->album->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['title']   = $l->title;
            $row['image']   = $l->image;
            $row['created'] = $l->created_on;
            $row['id']      = $l->id;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->album->count_all($this->app_id),
            "recordsFiltered"   => $this->album->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
