<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class reminder extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reminder/reminder_model', 'reminder');

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
        redirect(site_url('reminder'), 'refresh');
    }

    public function edit($id)
    {
        $is_exist = $this->reminder->find($id);

        if($is_exist){
            $reminder = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Reminder')
                ->set('data', $reminder)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $app_id         = $this->app_id;
        $title          = $this->input->post('title');
        $message        = $this->input->post('message');
        $date           = $this->input->post('date');

        $data = array(
            'app_id'    => $app_id,
            'title'     => $title,
            'message'   => $message,
            'date'      => $date
        );
        
        if(!$id){

            $insert = $this->event->insert($data);
            
            redirect(site_url('events'), 'refresh');
        }else{

            $update = $this->event->update($id, $data);
            redirect(site_url('events'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->event->delete($id);

        redirect(site_url('events'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->event->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['title']   = $l->title;
            $row['msg']     = word_limiter($l->message);
            $row['date']    = date('j F, Y', strtotime($l->date));
            $row['edit']    = site_url('events/edit/'.$l->id);
            $row['delete']  = site_url('events/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->event->count_all($this->app_id),
            "recordsFiltered"   => $this->event->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
