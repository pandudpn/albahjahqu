<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ibadah extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('islami/ibadah_model', 'ibadah');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $cat    = $this->ibadah->prayer_category($this->app_id);
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('cat', $cat)
    		 ->build('ibadah');
    }

    public function add()
    {
        $cat    = $this->ibadah->prayer_category($this->app_id);
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('cat', $cat)
            ->set('title', 'Tambah Data Panduan Ibadah')
            ->build('ibadah_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->ibadah->find($id);
        $cat    = $this->ibadah->prayer_category($this->app_id);

        if($is_exist){
            $ibadah = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Data Panduan Ibadah')
                ->set('cat', $cat)
                ->set('data', $ibadah)
                ->build('ibadah_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $title          = $this->input->post('title');
        $text           = $this->input->post('text');

        $data = array(
            'title'     => $title,
            'text'      => $text
        );
        
        if(!$id){
            $insert = $this->ibadah->insert($data);
        }else{
            $update = $this->ibadah->update($id, $data);
        }

        redirect(site_url('islami/ibadah'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->ibadah->delete($id);;

        redirect(site_url('islami/ibadah'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->ibadah->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['title']   = $l->title;
            $row['text']    = word_limiter($l->text, 10);
            $row['type']    = ucfirst($l->cat_name);
            $row['edit']    = site_url('islami/ibadah/edit/'.$l->id);
            $row['delete']  = site_url('islami/ibadah/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->ibadah->count_all($this->app_id),
            "recordsFiltered"   => $this->ibadah->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
