<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class events extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('events/events_model', 'event');
        $this->load->model('customers/customer_session_model', 'customer_session');
        $this->load->model('customers/customer_notification_model', 'customer_notification');

        $this->load->helper('text');
        $this->load->library('Guzzle');

        $this->app_id   = $this->session->userdata('user')->app_id;
        $this->dealer_id= $this->session->userdata('user')->dealer_id;

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
        $old_date       = $this->input->post('old_date');

        $data = array(
            'app_id'    => $app_id,
            'title'     => $title,
            'message'   => $message,
            'date'      => $date
        );
        
        if(!$id){

            $insert = $this->event->insert($data);

            $dealer     = $this->dealer_id;
            $n_type     = 'general';
            $n_title    = $this->input->post('titlenotif');
            $n_msg      = $this->input->post('notifmsg');

            $ins        = [
                'dealer_id'     => $dealer,
                'notif_type'    => $n_type,
                'notif_title'   => $n_title,
                'notif_remark'  => $n_msg
            ];

            $notification  = $this->customer_notification->insert($ins);

            $max_fcm_users = 1000;
            $users_num     = $this->customer_session->count_all_users($dealer); // ALL USERS COUNT
            
            $users_queue   = floor($users_num/$max_fcm_users);

            for ($i=0; $i <= $users_queue; $i++) 
            {     
                $fcm_ids   = Array();
                $offset    = $i * $max_fcm_users;
                $limit     = $max_fcm_users;
                $users     = $this->customer_session->get_all_fcm_users($offset, $limit, $dealer); // ALL USER SELECTED

                foreach ($users as $user) {
                    array_push($fcm_ids, $user->cus_fcm_id);
                }

                //Send to All FCM IDs in $i-st Batch of A Thousand.
                if(count($fcm_ids) > 0){
                    $this->push_notification($fcm_ids, $n_title, $n_msg, $notification);
                }
            }
            
            redirect(site_url('events'), 'refresh');
        }else{

            $update = $this->event->update($id, $data);

            if($update){
                if($old_date != $date){
                    $notif  = "Perubahan jadwal untuk kegiatan ".$title.", menjadi tanggal ".$date;
                }else{
                    $notif  = $title;
                }
                
                $dealer = $this->dealer_id;
                $type   = 'all';
                $n_type = 'general';
                $n_title= 'Perubahan Jadwal Kegiatan';

                $in     = [
                    'dealer_id'     => $dealer,
                    'type'          => $type,
                    'notif_type'    => $n_type,
                    'notif_title'   => $n_title,
                    'notif_remark'  => $notif
                ];

                $notification  = $this->customer_notification->insert($in);

                $max_fcm_users = 1000;
                $users_num     = $this->customer_session->count_all_users($dealer); // ALL USERS COUNT
                
                $users_queue   = floor($users_num/$max_fcm_users);

                for ($i=0; $i <= $users_queue; $i++) 
                {     
                    $fcm_ids   = Array();
                    $offset    = $i * $max_fcm_users;
                    $limit     = $max_fcm_users;
                    $users     = $this->customer_session->get_all_fcm_users($offset, $limit, $dealer); // ALL USER SELECTED

                    foreach ($users as $user) {
                        array_push($fcm_ids, $user->cus_fcm_id);
                    }

                    //Send to All FCM IDs in $i-st Batch of A Thousand.
                    if(count($fcm_ids) > 0){
                        $this->push_notification($fcm_ids, $title, $notif, $notification);
                    }
                }
            }

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

    private function push_notification($gcm_ids, $title, $msg, $notification_id)
    {
        $url     = 'https://fcm.googleapis.com/fcm/send';
        $message = array("title" => $title, "body" => $msg, "click_action" => 'popup');
        $fields  = array(
            'registration_ids'  => $gcm_ids,
            'notification'      => $message,
            'data'              => array("notification_id" => $notification_id, "title" => $title, "message" => $msg)
        );

        $api_key = 'AAAAf_Rr2ig:APA91bGe0MVf85hli70S__JHZMjIhZILomI9WkEv_wyLqf6K8mm2A4oHsmKGsS9UJr4CniLF518W9ECdncTtUhc-f-h8NFPRDCLU0M5nAM_bpeDxYPRk2U_OA1b8F3zUBOQHiMWmVMud';

        $headers = array(
            'Authorization: key='.$api_key,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
    }

}
