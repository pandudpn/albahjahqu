<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class videos extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('videos/videos_model', 'video');
        $this->load->helper('text');
        $this->load->library('Guzzle');

        $this->api_key  = 'AIzaSyCLRYAzw6_YDUm8kEqUcUWBSyziDbDvRzw';
        $this->url      = 'https://www.googleapis.com/youtube/v3';
        $this->app_id   = $this->session->userdata('user')->app_id;
    }

    public function index()
    {
        $this->check_login();
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('index');
    }

    public function add()
    {
        $this->check_login();
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add New Video')
            ->build('form');
    }

    public function edit($id)
    {
        $this->check_login();
        $is_exist = $this->video->find($id);

        if($is_exist){
            $video = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Video')
                ->set('data', $video)
                ->build('form');
        }
    }

    public function save()
    {
        $this->check_login();
        $id       		= $this->input->post('id');

        $app_id     	= $this->session->userdata('user')->app_id;
        $title     		= $this->input->post('title');
        $desc           = $this->input->post('desc');
        $url            = $this->input->post('url');
        $photo          = $this->input->post('photo');
        $created        = $this->input->post('created');

        $status         = $this->input->post('status');

        $data = array(
            'title'      => $title,
            'app_id'     => $app_id,
            'description'=> $desc,
            'url_video'  => $url,
            'image'      => $photo,
            'created_on' => $created,
            'type'       => 'videos',
            'status'     => $status
        );

        if($status == 'live'){
            $check  = $this->video->find_by(['status' => 'live']);
            
            if($check){
                $this->video->update($check->id, ['status' => 'no']);
            }
        }

        if(!$id){
            $insert = $this->video->insert($data);
        }else{
            $update = $this->video->update($id, $data);
        }
        
        redirect(site_url('videos'), 'refresh');
    }

    public function delete($id)
    {
        $this->check_login();
        $delete = $this->video->delete($id);

        redirect(site_url('videos'), 'refresh');
    }

    public function datatables()
    {
        $this->check_login();
        $list = $this->video->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            if($l->status == 'live'){
                $status = '<span class="badge badge-danger">Live Broadcast</span>';
            }else{
                $status = '';
            }

            $row[] = $no;
            $row[] = "<div title='$l->title'>".word_limiter($l->title, 5)."</div>";
            $row[] = "<div title='$l->description'>".word_limiter($l->description, 5)."</div>";
            $row[] = "<a href='$l->url_video'>".$l->url_video."</a> ".$status;

            $btn   = '<a href="'.site_url('videos/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('videos/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->video->count_all($this->app_id),
            "recordsFiltered"   => $this->video->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    
    public function data(){
        // $strpos     = strpos($this->input->get('video'), '=') + 1;
        $url    = parse_url($this->input->get('video'));
        parse_str($url['query']);
        
        $videoId    = $v;
        
        $query  = [
            'part'      => 'snippet',
            'id'        => $videoId,
            'key'       => $this->api_key
        ];

        try{
            $resources  = '/videos';
            $url        = $this->url.$resources;
            
            $client      = new GuzzleHttp\Client([ 
                'base_uri'   => $this->url, 
                'headers'    => [
                    'Content-Type'  => 'application/json'
                ]
            ]);
            $response   = $client->request('GET', $url, [
                'query' => $query
            ]);

            $json       = json_decode($response->getBody()->getContents());

            $this->rest->set_data($json);
            $this->rest->render();
        }catch(Exception $e){
            $result = (string) $e->getResponse()->getBody();
            $this->rest->set_error($result);
            $this->rest->render();
        }
    }

}
