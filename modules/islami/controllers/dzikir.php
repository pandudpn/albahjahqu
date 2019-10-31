<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dzikir extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('islami/dzikir_model', 'dzikir');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('dzikir');
    }

    public function add()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Tambah Dzikir Harian')
            ->build('dzikir_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->dzikir->find($id);

        if($is_exist){
            $dzikir = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Dzikir')
                ->set('data', $dzikir)
                ->build('dzikir_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $app_id     	= $this->session->userdata('user')->app_id;
        $title          = $this->input->post('title');
        $content        = $this->input->post('content');
        $type           = $this->input->post('type');

        $data = array(
            'app_id'    => $app_id,
            'title'     => $title,
            'type'      => $type
        );

        if(!empty($_FILES['pdf']['name'])) {
            $config['upload_path']      = './data/pdf/';
	        $config['allowed_types']    = 'pdf';
	        $config['encrypt_name']     = true;
	        
	        $this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('pdf')) {
                $this->session->set_flashdata('alert', ['msg' => 'Ekstension file tidak bisa. Hanya PDF', 'type' => 'danger']);
                redirect(site_url('islami/dzikir/add'), 'refresh');
	        } else {
                $file   = $this->upload->data();
	            $data['pdf'] = site_url('data/pdf').'/'.$file['file_name'];
            }

        }else {
            $data['content_dzikir'] = $content;
        }
        
        if(!$id){
            $insert = $this->dzikir->insert($data);
        }else{
            $update = $this->dzikir->update($id, $data);
        }

        redirect(site_url('islami/dzikir'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->dzikir->delete($id);;

        redirect(site_url('islami/dzikir'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->dzikir->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['title']   = word_limiter($l->title, 7);
            $row['text']    = word_limiter($l->content_dzikir, 30);
            $row['edit']    = site_url('islami/dzikir/edit/'.$l->id);
            $row['delete']  = site_url('islami/dzikir/delete/'.$l->id);
            $row['pdf']     = $l->pdf;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->dzikir->count_all($this->app_id),
            "recordsFiltered"   => $this->dzikir->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
