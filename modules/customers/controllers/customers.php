<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class customers extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('customers/customer_model', 'customer');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
    		->build('index');
    }

    public function status($status, $id)
    {
        $arr = array();
        if($status == 'active'){
            $arr = array('account_status' => 'active');
        }else if($status == 'suspend'){
            $arr = array('account_status' => 'suspended');
        }else if($status == 'block'){
            $arr = array('account_status' => 'blocked');
        }

        $update = $this->customer->update($id, $arr);

        redirect(site_url('customers'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->customer->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->name;
            $row[] = $l->phone;
            $row[] = $l->email;
            $row[] = $l->dealer_name;
            $row[] = 'Rp. '.number_format($l->balance);
            $row[] = $l->account_status;
            $row[] = $l->kyc_status;

            // $btn   = '<a href="'.site_url('menu/edit/'.$l->id).'" class="btn btn-success btn-sm">
            //             <i class="fa fa-pencil"></i>
            //           </a> &nbsp;';

            // $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('menu/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
            //             <i class="fa fa-trash"></i>
            //           </a>';

            $btn = '<div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Change Status <span class="caret"></span></button>
                        <div class="dropdown-menu">';
            
            if($l->account_status != 'active'){
                $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert(\''.site_url('customers/status/active/'.$l->id).'\')">Active</a>
                            <div class="dropdown-divider"></div>';
            }

            if($l->account_status != 'suspended'){
                $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert(\''.site_url('customers/status/suspend/'.$l->id).'\')">Suspend</a>
                                <div class="dropdown-divider"></div>';
            }

            if($l->account_status != 'blocked'){
                $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert(\''.site_url('customers/status/block/'.$l->id).'\')">Block!</a>
                                <div class="dropdown-divider"></div>';
            }

            $btn .= '</div>
                    </div>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->customer->count_all(),
            "recordsFiltered"   => $this->customer->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}