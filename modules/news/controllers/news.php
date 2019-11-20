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
	            $data['image'] = $this->url_package().'/data/images/news/'.$file['file_name'];
	        }
        }

        $headline   = $this->checkingHeadline();

        if($headline){
            $news   = $this->news->getheadline();

            $newsId = end($news);

            $update = $this->news->update($newsId->id, ['status' => 'no']);
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

            $row['no']      = $no;
            $row['title']   = $l->title;
            $row['desc']    = word_limiter(htmlspecialchars($l->description), 20);
            $row['status']  = $l->status;
            $row['created'] = $l->created_on;
            $row['id']      = $l->id;

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

    private function checkingHeadline(){
        $news   = $this->news->getheadline();

        if(count($news) > 4){
            return true;
        }

        return false;
    }

    public function imgupload()
    {
        $config['upload_path']      = './data/images/news/';
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
                ->set_output(json_encode(['location' => site_url('data/images/news/'.$file['file_name'])]))
                ->_display();
            exit;
        }
    }

}
