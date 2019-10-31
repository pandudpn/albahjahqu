<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class hadits_daily extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('islami/hadits_daily_model', 'hadist');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('hadits_daily');
    }

    public function add()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Hadits Harian')
            ->build('hadits_daily_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->hadist->find($id);

        // $this->print_array($ayat); die;

        if($is_exist){
            $doa = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Hadits Harian')
                ->set('data', $doa)
                ->build('hadits_daily_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $app_id     	= $this->session->userdata('user')->app_id;
        $title          = $this->input->post('title');
        $ar             = $this->input->post('text_ar');
        $latin          = $this->input->post('latin');
        $translate      = $this->input->post('translate');

        $data = array(
            'app_id'    => $app_id,
            'title'     => $title,
            // 'text_ar'   => $ar,
            // 'latin'     => $latin,
            'translate' => $translate
        );

        if(!empty($_FILES['image']['name']))
        {
        	$config['upload_path']      = './data/images';
	        $config['allowed_types']    = '*';
	        $config['max_size']         = 1024;
	        $config['encrypt_name']     = true;
	        
	        $this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('image')) {
	            
	        } else {
	            $file   = $this->upload->data();
	            $data['image']  = site_url('data/images').'/'.$file['file_name'];
	        }
        }
        
        if(!$id){
            $insert = $this->hadist->insert($data);
        }else{
            $update = $this->hadist->update($id, $data);
        }

        redirect(site_url('islami/hadits_daily'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->hadist->delete($id);;

        redirect(site_url('islami/hadits_daily'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->hadist->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['title']   = word_limiter($l->title, 5);
            $row['arab']    = word_limiter($l->text_ar, 5);
            $row['latin']   = word_limiter($l->latin, 5);
            $row['translate']   = word_limiter($l->translate, 5);
            $row['image']   = $l->image;
            $row['edit']    = site_url('islami/hadits_daily/edit/'.$l->id);
            $row['delete']  = site_url('islami/hadits_daily/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->hadist->count_all($this->app_id),
            "recordsFiltered"   => $this->hadist->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
