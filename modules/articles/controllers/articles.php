<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class articles extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('articles/articles_model', 'article');

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
            ->set('title', 'Add New Article')
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->article->find($id);

        if($is_exist){
            $article = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Article')
                ->set('data', $article)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $title     		= $this->input->post('title');
        $content     	= $this->input->post('content');
        $app_id     	= $this->session->userdata('user')->app_id;

        $data = array(
            'title'      => $title,
            'description'=> $content,
            'app_id'     => $app_id,
            'type'       => 'articles'
        );

        if(!empty($_FILES['image']['name']))
        {
        	$config['upload_path']      = './data/images/articles/';
	        $config['allowed_types']    = '*';
	        $config['max_size']         = 1024;
	        $config['encrypt_name']     = true;
	        
	        $this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('image')) {
                $this->session->set_flashdata('alert', ['msg' => $this->upload->display_errors(), 'type' => 'danger']);
                redirect(site_url('articles/add'), 'refresh');
	        } else {
	            $file = $this->upload->data();
	            $data['image'] = 'data/images/articles/'.$file['file_name'];
	        }
        }
        
        if(!$id){
            $insert = $this->article->insert($data);
        }else{
            $update = $this->article->update($id, $data);
        }
        
        redirect(site_url('articles'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->article->delete($id);

        redirect(site_url('articles'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->article->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['title']   = $l->title;
            $row['desc']    = word_limiter(htmlspecialchars($l->description), 20);
            $row['created'] = $l->created_on;
            $row['id']      = $l->id;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->article->count_all($this->app_id),
            "recordsFiltered"   => $this->article->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function imgupload()
    {
        $config['upload_path']      = './data/images/articles/';
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
                ->set_output(json_encode(['location' => site_url('data/images/articles/'.$file['file_name'])]))
                ->_display();
            exit;
        }
    }

}
