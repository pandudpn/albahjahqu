<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class category_ibadah extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('islami/category_ibadah_model', 'cat');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('cat_ibadah');
    }

    public function add()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Tambah Kategori')
            ->build('cat_ibadah_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->cat->find($id);

        if($is_exist){
            $ibadah = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Kategori')
                ->set('data', $ibadah)
                ->build('cat_ibadah_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $app            = $this->app_id;
        $name           = $this->input->post('name');

        $data = array(
            'app_id'    => $app,
            'name'      => $name
        );
        
        if(!$id){
            $insert = $this->cat->insert($data);
        }else{
            $update = $this->cat->update($id, $data);
        }

        redirect(site_url('islami/category_ibadah'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->cat->delete($id);;

        redirect(site_url('islami/category_ibadah'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->cat->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['name']    = $l->name;
            $row['edit']    = site_url('islami/category_ibadah/edit/'.$l->id);
            $row['delete']  = site_url('islami/category_ibadah/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->cat->count_all($this->app_id),
            "recordsFiltered"   => $this->cat->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
