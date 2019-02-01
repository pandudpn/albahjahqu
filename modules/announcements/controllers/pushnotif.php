<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pushnotif extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('dealers/dealer_model', 'dealer');
        $this->load->model('customers/customer_model', 'customer');
        $this->load->model('customers/customer_session_model', 'customer_session');

        $this->check_login();
    }

    public function index()
    {
        if($this->input->post())
        {
            //Populating FCM IDs

            $dealer_id     = $this->input->post('dealer');

            $max_fcm_users = 1000;
            $users_num     = $this->customer_session->count_all_users($dealer_id); // ALL USERS COUNT
            // var_dump($users_num);die;
            $users_queue   = floor($users_num/$max_fcm_users);
            
            $title          = $this->input->post('title');
            $message        = $this->input->post('message');

            for ($i=0; $i <= $users_queue; $i++) { 
                
                $fcm_ids   = Array();
                $offset    = $i * $max_fcm_users;
                $limit     = $max_fcm_users;
                $users     = $this->customer_session->get_all_fcm_users($offset, $limit, $dealer_id); // ALL USER SELECTED

                foreach ($users as $user) {
                    array_push($fcm_ids, $user->cus_fcm_id);
                }

                //Send to All FCM IDs in $i-st Batch of A Thousand.
                if(count($fcm_ids) > 0){
                    $this->push_notification($fcm_ids, $title, $message);
                }
            }

            $msg = 'success sending your push notification';
            $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

            redirect(site_url('announcements/pushnotif'), 'refresh');
        }

        $dealers = $this->dealer->find_all_by(array('deleted' => '0'));
        $dealer  = $this->dealer->find($this->session->userdata('user')->dealer_id);

        $this->template
             ->set('alert', $this->session->flashdata('alert'))
             ->set('dealers', $dealers)
             ->set('dealer', $dealer)
    		 ->build('pushnotif');
    }

    private function push_notification($gcm_ids, $title, $msg)
    {
        $url     = 'https://fcm.googleapis.com/fcm/send';
        $message = array("title" => $title, "body" => $msg, "click_action" => '');
        $fields  = array(
              'registration_ids'  => $gcm_ids,
              'notification'      => $message
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