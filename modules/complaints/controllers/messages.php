<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class messages extends Admin_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->check_login();

        $this->config->load('pusher');

        $this->load->model('user/user_admin_model', 'user_admin');
        $this->load->model('complaints/customer_support_model', 'cus_support');
        $this->load->model('complaints/customer_support_message_model', 'cus_support_message');
        $this->load->model('complaints/customer_support_member_model', 'customer_support_member');
        $this->load->model('customers/customer_session_model', 'customer_session');
    }

    public function index($id=null)
    {
        $cus_support = $this->cus_support->find($id);
        // var_dump($cus_support);die;

        $cus_support_message = $this->cus_support_message->order_by('id', 'asc');
        $cus_support_message = $this->cus_support_message->find_all_by(array('ticket' => $cus_support->ticket));

        // var_dump($cus_support_message);die;

        if(!$cus_support)
        {
            redirect(site_url('complaints/report'), 'refresh');
        }

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('pusher_app_id', $this->config->item('pusher_app_id'))
            ->set('pusher_app_key', $this->config->item('pusher_app_key'))
            ->set('pusher_app_secret', $this->config->item('pusher_app_secret'))
            ->set('pusher_app_cluster', $this->config->item('pusher_app_cluster'))
            ->set('cus_support', $cus_support)
            ->set('cus_support_message', $cus_support_message)
            ->set('user', $this->session->userdata('user'))
            ->set('id', $id)
            ->build('message');
    }

    public function create()
    {
        $this->load->library('ci_pusher');
        $this->pusher = $this->ci_pusher->getPusher();

        $cus_support    = $this->cus_support->find($this->input->post('id'));

        $reference      = $cus_support->reference_code;
        $ticket         = $cus_support->ticket;
        $sender         = $this->session->userdata('user')->id;
        $sender_role    = $this->session->userdata('user')->role;
        $sender_name    = $this->session->userdata('user')->name;
        $message        = $this->input->post('message');

        $data           = array(
            'reference'     => $reference, 
            'ticket'        => $ticket, 
            'sender'        => $sender, 
            'sender_role'   => $sender_role, 
            'sender_name'   => $sender_name, 
            'message'       => $message,
            'created_on'    => date('Y-m-d H:i:s')
        );

        $insert_id = $this->cus_support_message->insert($data);

        if($insert_id)
        {
            $response = $this->pusher->trigger( $ticket , 'dealer_reply', $data);

            if($response['status'] == 200 || $response){ $data['push'] = 1; }

            $customer_support_member = $this->customer_support_member->order_by('id', 'asc');
            $customer_support_member = $this->customer_support_member->find_all_by(array('ticket' => $cus_support->ticket));

            //PUSH NOTIF

            $fcm_id  = Array();

            foreach ($customer_support_member as $key => $c) 
            {
                if($c->role == 'customer')
                {
                    $session = $this->customer_session->order_by('id', 'desc');
                    $session = $this->customer_session->find_by(array('cus_id' => $c->user_id));

                    
                    array_push($fcm_id, $session->cus_fcm_id);
                }
                else
                {
                    $admin   = $this->user_admin->find($c->user_id);

                    array_push($fcm_id, $admin->web_fcm);
                }
            }
            
            $title      = 'Chat Baru dari '. $this->session->userdata('user')->name;
            $message    = $message;

            $this->push_notification($fcm_id, $title, $message, '', '');

            $this->rest->set_data($data);
        }else{
            $this->rest->set_error('Error Creating Message.'); 
        }

        $this->rest->render();
    }

    private function push_notification($gcm_ids, $title, $msg, $action='feed', $id='')
    {
        $url     = 'https://fcm.googleapis.com/fcm/send';
        $message = array("title" => $title, "body" => $msg, "subject" => 'topup');
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