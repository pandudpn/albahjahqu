<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class kycs extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();

        $this->load->model('customers/kyc_model', 'kyc');
        $this->load->model('customers/customer_model', 'customer');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
    	$this->template->set('alert', $this->session->flashdata('alert'))
    					->build('kycs/index');
    }

    public function status()
    {
        $kyc_id  = $this->input->post('kyc_id');
        $status  = $this->input->post('kyc_status');
        $remarks = $this->input->post('kyc_remarks');

        $is_exist = $this->kyc->find($kyc_id);

        if($is_exist){
            $update_remark = $is_exist->remarks." -> ".$remarks;

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
}