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
        $quote          = $this->input->post('quote');
        $date           = $this->input->post('date');
        $alarm          = $this->input->post('alarm');

        $data = array(
            'title'     => $title,
            'quote'     => $quote,
            'alarm'     => $alarm
        );
        
        if(!$id){

            $insert = $this->reminder->insert($data);
            
            redirect(site_url('reminder'), 'refresh');
        }else{

            $update = $this->reminder->update($id, $data);
            redirect(site_url('reminder'), 'refresh');
        }
    }

    public function delete($id)
    {
        redirect(site_url('reminder'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->reminder->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['name']    = $l->reminder_name;
            $row['title']   = $l->title;
            $row['quote']   = $l->quote;
            $row['alarm']   = $l->alarm;
            $row['edit']    = site_url('reminder/edit/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->reminder->count_all($this->app_id),
            "recordsFiltered"   => $this->reminder->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
