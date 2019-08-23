<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class news extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('news/news_model', 'news');

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
            ->set('title', 'Create News')
            ->set('topics', $topics)
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->news->find($id);

        if($is_exist){
            $news = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit News')
                ->set('data', $news)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $title     		= $this->input->post('title');
        $content     	= $this->input->post('content');
        $status         = $this->input->post('status');
        $app_id     	= $this->session->userdata('user')->app_id;

        $data = array(
            'title'      => $title,
            'description'=> $content,
            'status'     => $status,
            'app_id'     => $app_id,
            'type'       => 'news'
        );

        if(!empty($_FILES['image']['name']))
        {
        	$config['upload_path']      = './data/images/news/';
	        $config['allowed_types']    = '*';
	        $config['max_size']         = 1024;
	        $config['encrypt_name']     = true;
	        
	        $this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('image')) {
	            
	        } else {
	            $file = $this->upload->data();
	            $data['image'] = site_url('data/images/news').'/'.$file['file_name'];
	        }
        }
        
        if(!$id){

            $insert = $this->news->insert($data);
            
            redirect(site_url('news'), 'refresh');
        }else{

            $update = $this->news->update($id, $data);
            redirect(site_url('news'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->news->delete($id);

        redirect(site_url('news'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->news->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row[] = $no;
            $row[] = $l->title;
            $row[] = word_limiter(htmlspecialchars($l->description), 20);
            $row[] = $l->status;
            $row[] = $l->created_on;

            $btn   = '<a href="'.site_url('news/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('news/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->news->count_all($this->app_id),
            "recordsFiltered"   => $this->news->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
