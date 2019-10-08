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

    public function updateAll(){
        $where  = [
            'app_id'    => $this->app_id,
            'status'    => 'no',
            'deleted'   => 0
        ];

        $data   = [
            'status'    => 'read'
        ];

        $update = $this->notification->updateAll($where, $data);

        $status = 500;

        if($update) {
            $status  = 204;
        }

        echo json_encode(['status' => $status]);
    }

    public function data(){
        $notif  = $this->notification->get_all([
            'app_id'    => $this->app_id,
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
