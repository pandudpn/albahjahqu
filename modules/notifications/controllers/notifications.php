<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class notifications extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('notifications/notifications_model', 'notification');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function update(){
        $id = $this->input->post('id');

        $data   = [
            'status'    => 'read'
        ];

        $update = $this->notification->update($id, $data);
    }

    public function data(){
        $notif  = $this->notification->get_all([
            'app_id'    => 'com.dekape.okbabe.albahjah',
            'status'    => 'no',
            'deleted'   => 0
        ]);

        $data   = [];
        $result = [];
        foreach($notif AS $row){
            $data[] = [
                'id'        => intval($row->id),
                'title'     => $row->title,
                'msg'       => word_limiter($row->message, 3),
                'text'      => $row->message,
                'created'   => date('Y-m-d H:i', strtotime($row->created_on)),
                'url'       => site_url('streaming')
            ];
        }

        $result = $data;

        $this->rest->set_data($result);
        $this->rest->render();
    }

}
