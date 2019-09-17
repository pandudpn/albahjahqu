<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pushnotif extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('customers/customer_session_model', 'customer_session');
        $this->load->model('announcements/notification_model', 'notification');

        $this->dealer   = $this->session->userdata('user')->dealer_id;

        $this->check_login();
    }

    public function index()
    {
        if($this->input->post())
        {
            $dealer_id     = $this->session->userdata('user')->dealer_id;
            $title         = $this->input->post('title');
            $message       = $this->input->post('message');

            $data          = array(
                'dealer_id' => $dealer_id,
                'title'     => $title,
                'message'   => $message
            );

            $notification_id = $this->notification->insert($data);

            $max_fcm_users = 1000;
            $users_num     = $this->customer_session->count_all_users($dealer_id); // ALL USERS COUNT
            
            $users_queue   = floor($users_num/$max_fcm_users);

            for ($i=0; $i <= $users_queue; $i++) 
            {     
                $fcm_ids   = Array();
                $offset    = $i * $max_fcm_users;
                $limit     = $max_fcm_users;
                $users     = $this->customer_session->get_all_fcm_users($offset, $limit, $dealer_id); // ALL USER SELECTED

                foreach ($users as $user) {
                    array_push($fcm_ids, $user->cus_fcm_id);
                }

                //Send to All FCM IDs in $i-st Batch of A Thousand.
                if(count($fcm_ids) > 0){
                    $this->push_notification($fcm_ids, $title, $message, $notification_id);
                }
            }

            $msg = 'success sending your push notification';
            $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

            redirect(site_url('announcements/pushnotif'), 'refresh');
        }

        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('pushnotif');
    }

    public function delete($id)
    {
        $delete = $this->notification->set_soft_deletes(TRUE);
        $delete = $this->notification->delete($id);

        redirect(site_url('announcements/pushnotif'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->notification->get_datatables($this->dealer);
        // echo $this->db->last_query();die;
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            
            $row['no']      = $no;
            $row['title']   = $l->title;
            $row['dealer']  = $l->name;
            $row['msg']     = $l->message;
            $row['created'] = date('j M, Y H:i', strtotime($l->created_on));

            $row['delete']  = site_url('announcements/pushnotif/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->notification->count_all($this->dealer),
            "recordsFiltered"   => $this->notification->count_filtered($this->dealer),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
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