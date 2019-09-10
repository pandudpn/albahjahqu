<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class events extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('events/events_model', 'event');

        $this->load->helper('text');
        $this->load->library('Guzzle');

        $this->app_id   = $this->session->userdata('user')->app_id;
        $this->url      = 'http://api.aladhan.com/v1';

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
            ->set('title', 'Create Event')
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->event->find($id);

        if($is_exist){
            $event = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Event')
                ->set('data', $event)
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

    public function getCalendar($date){
        $m      = date('n', strtotime($date));
        $y      = date('Y', strtotime($date));

        $query  = [
            'latitude'      => '-6.242555',
            'longitude'     => '106.854554',
            'method'        => 5,
            'month'         => $m,
            'year'          => $y,
            'adjustment'    => 1
        ];

        try {
            $resources  = '/calendar';
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

            $data   = [];
            foreach($json->data AS $row){
                if(count($row->date->hijri->holidays) > 0){
                    $res[]  = [
                        'date'  => intval(date('j', $row->date->timestamp)),
                        'event' => $row->date->hijri->holidays
                    ];
                }
            }
            $data = $res;

            $this->rest->set_data($data);
            $this->rest->render();
        }catch(Exception $e){
            $result = (string) $e->getResponse()->getBody();
            $this->rest->set_error($result);
            $this->rest->render();
        }
    }

}
