<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class videos extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('videos/videos_model', 'video');
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
            ->set('title', 'Add New Video')
            ->build('form');
    }

    public function edit($id)
    {
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
        $id       		= $this->input->post('id');

        $title     		= $this->input->post('title');
        $desc           = $this->input->post('desc');
        $url            = $this->input->post('url');
        $status         = $this->input->post('status');
        $app_id     	= $this->session->userdata('user')->app_id;

        $data = array(
            'title'      => $title,
            'app_id'     => $app_id,
            'description'=> $desc,
            'url_video'  => $url,
            'status'     => $status,
            'type'       => 'videos'
        );

        if($status == 'live'){
            $check  = $this->video->find_all_by(['status' => 'live']);
            $d  = array(
                'status' => 'no'
            );
            if(count($check) > 0){
                foreach($check AS $r){
                    $this->db->where('id', $r->id);
                    $this->db->update('contents', $d);
                }
            }
        }

        if(!$id){

            $insert = $this->video->insert($data);
            
            redirect(site_url('videos'), 'refresh');
        }else{
            $update = $this->video->update($id, $data);
            redirect(site_url('videos'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->video->delete($id);

        redirect(site_url('videos'), 'refresh');
    }

    public function datatables()
    {
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

}
