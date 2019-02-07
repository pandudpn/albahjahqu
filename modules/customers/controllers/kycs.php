<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class kycs extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();

        $this->load->model('customers/kyc_model', 'kyc');
        $this->load->model('customers/customer_model', 'customer');
        $this->load->model('customers/customer_notification_model', 'customer_notification');
        $this->load->model('customers/customer_session_model', 'customer_session');
        $this->load->model('user/eva_customer_model', 'eva_customer');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
    	$this->template->set('alert', $this->session->flashdata('alert'))
    					->build('kycs/index');
    }

    public function edit($id)
    {
        if($this->input->post())
        {
            $data = array(
                'name'      => $this->input->post('name'),
                'identity'  => $this->input->post('identity')
            );

            $update = $this->customer->update($id, $data);
            $update = $this->eva_customer->update_where('account_user', $id, array('account_holder' => $data['name']));

            $data_kyc = array(
                'cus_name' => $this->input->post('name'),
                'cus_ktp'  => $this->input->post('identity')
            );

            $update = $this->kyc->update_where('cus_id', $id, $data_kyc);

            if($update)
            {
                redirect(site_url('customers/kycs'), 'refresh');
            }
        }

        $data = $this->customer->find($id);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Edit KYC for '.$data->name)
            ->set('data', $data)
            ->build('kycs/edit');
    }

    public function status()
    {
        $kyc_id  = $this->input->post('kyc_id');
        $status  = $this->input->post('kyc_status');
        $remarks = $this->input->post('kyc_remarks');

        $is_exist = $this->kyc->find($kyc_id);

        if($is_exist){
            $update_remark = $remarks;

            $arr = array();
            if($status == 'approve'){
                $arr = array('decision' => 'approved', 'remarks' => $update_remark);
                $kyc = array('kyc_status' => 'approved');
            }else if($status == 'reject'){
                $arr = array('decision' => 'rejected', 'remarks' => $update_remark);
                $kyc = array('kyc_status' => 'rejected');
            }

            $update = $this->kyc->update($kyc_id, $arr);

            $cust_id = $this->kyc->find($kyc_id)->cus_id;
            $this->customer->update($cust_id, $kyc);

            //insert to notif
            $data = array(
                'cus_id'        => $is_exist->cus_id, 
                'cus_name'      => $is_exist->cus_name, 
                'cus_phone'     => $is_exist->cus_phone, 
                'notif_type'    => 'kyc', 
                'notif_remark'  => 'KYC '.$kyc['kyc_status'].', Reason: '.$remarks
            );

            $this->customer_notification->insert($data);

            //send notif

            $session = $this->customer_session->order_by('id', 'desc');
            $session = $this->customer_session->find_by(array('cus_id' => $is_exist->cus_id));

            $fcm_id = Array();
            array_push($fcm_id, $session->cus_fcm_id);

            if($status == 'approve')
            {
                $message    = 'Data anda telah berhasil di verifikasi';
            }
            else
            {
                $message    = 'Data anda gagal diverifikasi dengan alasan: '.$remarks;
            }
            
            $title      = 'OKBABE+';
            

            $this->push_notification($fcm_id, $title, $message, '', '');

            echo 'success';
        }

        //redirect(site_url('customers/kycs'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->kyc->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->cus_name;
            $row[] = $l->cus_phone;
            $row[] = $l->cus_ktp;
            $row[] = $l->cus_mother;
            $row[] = $l->cus_job;
            $row[] = '<a onclick="showimg(\''.$l->ktp_image.'\')" href="javascript:;">show image</a>';
            $row[] = '<a onclick="showimg(\''.$l->selfie_image.'\')" href="javascript:;">show image</a>';
            $row[] = $l->decision;
            $row[] = $l->remarks;
            $row[] = $l->created_on;
            $row[] = $l->modified_on;

            // $btn   = '<a href="'.site_url('menu/edit/'.$l->id).'" class="btn btn-success btn-sm">
            //             <i class="fa fa-pencil"></i>
            //           </a> &nbsp;';

            // $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('menu/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
            //             <i class="fa fa-trash"></i>
            //           </a>';

            $btn = '<div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Change Status <span class="caret"></span></button>
                        <div class="dropdown-menu">';
            
            //if($l->decision != 'approved'){
                $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert(\'approve\',\''.$l->id.'\')">Approve</a>
                            <div class="dropdown-divider"></div>';
            //}

            //if($l->decision != 'rejected'){
                $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert(\'reject\',\''.$l->id.'\')">Reject</a>
                                <div class="dropdown-divider"></div>';
            //}

                $btn .= '<a class="dropdown-item" href="'.site_url('customers/kycs/edit/'.$l->cus_id).'">Edit</a>
                                <div class="dropdown-divider"></div>';

            $btn .= '</div>
                    </div>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->kyc->count_all(),
            "recordsFiltered"   => $this->kyc->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function push_notification($gcm_ids, $title, $msg, $action='feed', $id='')
    {
        $url     = 'https://fcm.googleapis.com/fcm/send';
        $message = array("title" => $title, "body" => $msg, "click_action" => "topup");
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