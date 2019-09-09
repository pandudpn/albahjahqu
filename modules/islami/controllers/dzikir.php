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
            ->set('title', 'New Dzikir Today')
            ->build('dzikir_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->hadist->find($id);

        // $this->print_array($ayat); die;

        if($is_exist){
            $doa = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Dzikir')
                ->set('data', $doa)
                ->build('dzikir_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $app_id     	= $this->session->userdata('user')->app_id;
        $title          = $this->input->post('title');
        $content        = $this->input->post('content');

        $data = array(
            'app_id'    => $app_id,
            'title'     => $title,
            'content_dzikir' => $content
        );


        
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
