<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class report extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('complaints/customer_support_model', 'customer_support');
        $this->load->model('customers/customer_model', 'customer');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
    		->build('report/index');
    }

    public function status($status, $id, $position='outside')
    {
        if($status == 'closed')
        {
            $data = array(
                'status' => $status,
                'status_closed_time' => date('Y-m-d H:i:s') 
            );
        }
        else
        {
            $data = array(
                'status' => $status
            );
        }

        $update = $this->customer_support->update($id, $data);

    	if($update)
    	{
            if($position == 'outside')
            {
                redirect(site_url('complaints/report'), 'refresh');
            }
    		else
            {
                redirect(site_url('complaints/messages/'.$id), 'refresh');
            }
    	}
    }

    public function data($id)
    {
    	$data = $this->customer_support->find($id);
    	$data->customer = $this->customer->find($data->cus_id);

    	header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function datatables()
    {
        $list = $this->customer_support->get_datatables('report');
        $data = array();
        $no   = $_POST['start'];

        // echo $this->db->last_query();die;

        foreach ($list as $l) {

            if($l->unread == 0 || empty($l->unread))
            {
                $unread = '';
            }
            else
            {
                $unread = '&nbsp; <span class="label label-pill label-danger float-right">'.$l->unread.'</span>';
            }

            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = '<a href="javascript:;" onclick="data(\''.$l->id.'\')">'.$l->ticket.'</a> <br/> 
                      <a style="margin-top: 10px;" href="'.site_url('complaints/messages/'.$l->id).'" class="btn btn-primary btn-sm">
                        <i class="fa fa-comments"></i> '.$unread.'
                      </a>';

            $row[] = $l->cus_name;
            $row[] = $l->dealer_name;
            $row[] = word_limiter($l->subject, 4);
            $row[] = $l->status;
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
            
            if($l->status != 'open'){
                $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert(\''.site_url('complaints/report/status/open/'.$l->id).'\')">Open</a>
                            <div class="dropdown-divider"></div>';
            }

            if($l->status != 'closed'){
                $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert(\''.site_url('complaints/report/status/closed/'.$l->id).'\')">Close</a>
                                <div class="dropdown-divider"></div>';
            }

            $btn .= '</div>
                    </div>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->customer_support->count_all('report'),
            "recordsFiltered"   => $this->customer_support->count_filtered('report'),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}	