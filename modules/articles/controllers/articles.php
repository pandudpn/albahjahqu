<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class articles extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('articles/article_model', 'article');
        $this->load->model('references/dealers_model', 'dealer');

        $this->load->helper('text');

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
    	$dealer           = $this->dealer->get_all();

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Article')
            ->set('dealer', $dealer)
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->article->find($id);

        if($is_exist){
            $article = $is_exist;
            $dealer  = $this->dealer->get_all();

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Article')
                ->set('data', $article)
                ->set('dealer', $dealer)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $for     		= $this->input->post('for');
        $for_dealer     = $this->input->post('for_dealer');
        $title     		= $this->input->post('title');
        $content     	= $this->input->post('content');
        $status     	= $this->input->post('status');
        $headlines  	= character_limiter(strip_tags($this->input->post('content')), 50);

        $data = array(
            'for'        => $for,
            'title'      => $title,
            'content'    => $content,
            'status'     => $status,
            'headlines'  => $headlines
        );

        if(!empty($for_dealer))
        {
        	$data['for_dealer'] = $for_dealer;
        }

        // var_dump($_FILES);die;

        if(!empty($_FILES['cover_image']['name']))
        {
        	$config['upload_path']      = './data/images/';
	        $config['allowed_types']    = '*';
	        $config['max_size']         = 0;
	        $config['encrypt_name']     = true;
	        
	        $this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('cover_image')) {
	            
	        } else {
	            $file = $this->upload->data();
	            $data['cover_image'] = $file['file_name'];
	        }
        }
        
        if(!$id){

            $insert = $this->article->insert($data);
            redirect(site_url('articles'), 'refresh');
        }else{
            $update = $this->article->update($id, $data);
            redirect(site_url('articles'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->article->delete($id);

        redirect(site_url('articles'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->article->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->for;
            $row[] = $l->title;
            $row[] = $l->status;
            $row[] = $l->created_on;

            $btn   = '<a href="'.site_url('articles/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('articles/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->article->count_all(),
            "recordsFiltered"   => $this->article->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function imgupload()
    {
        $config['upload_path']      = './data/images/';
        $config['allowed_types']    = '*';
        $config['max_size']         = 0;
        $config['encrypt_name']     = true;
        
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('file')) {
            $this->output->set_header('HTTP/1.0 500 Server Error');
            exit;
        } else {
            $file = $this->upload->data();
            
            $this->output
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(['location' => site_url('data/images/'.$file['file_name'])]))
                ->_display();
            exit;
        }
    }
}