<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class messages extends Admin_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->check_login();

        $this->config->load('pusher');

        $this->load->model('complaints/customer_support_model', 'cus_support');
        $this->load->model('complaints/customer_support_message_model', 'cus_support_message');
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
            $response = $this->pusher->trigger( $ticket , 'dekape_reply', $data);
            if($response['status'] == 200 || $response){ $data['push'] = 1; }
            $this->rest->set_data($data);
        }else{
            $this->rest->set_error('Error Creating Message.'); 
        }

        $this->rest->render();
    }
}