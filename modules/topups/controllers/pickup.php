<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pickup extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('topups/topup_pickup_model', 'topup_pickup');
        $this->load->model('dealers/dealer_model', 'dealer');
        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        $dealer = $this->input->get('dealer');
        $status = $this->input->get('status');

        $dealers = $this->dealer->order_by('name', 'asc');
        $dealers = $this->dealer->find_all_by(array('deleted' => '0'));

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('from', $from)
            ->set('to', $to)
            ->set('dealer', $dealer)
            ->set('dealers', $dealers)
            ->set('status', $status)
            ->set('total_sum', $total_sum)
    		->build('pickup');
    }

    public function approve($id=null)
    {
    	$this->topup_pickup->update($id, array('status' => 'approved'));

    	$msg = 'Topup approve success.';
        $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

		redirect(site_url('topups/pickup'), 'refresh');
    }

    public function reject($id=null)
    {
    	$this->topup_pickup->update($id, array('status' => 'rejected'));

    	$msg = 'Topup reject success.';
        $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

		redirect(site_url('topups/pickup'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->topup_pickup->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->customer_name;
            $row[] = $l->customer_phone;
            $row[] = $l->customer_email;
            $row[] = $l->lat.', '.$l->lng;
            $row[] = $l->dealer_name;
            $row[] = 'Rp. '.number_format($l->amount);
            $row[] = $l->status;
            $row[] = $l->created_on;

            // $btn   = '<a href="'.site_url('menu/edit/'.$l->id).'" class="btn btn-success btn-sm">
            //             <i class="fa fa-pencil"></i>
            //           </a> &nbsp;';

            // $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('menu/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
            //             <i class="fa fa-trash"></i>
            //           </a>';

            
            if($l->status == 'open')
            {
            	$btn =  '<a href="'.site_url('topups/pickup/approve/'.$l->id).'" class="btn btn-success btn-sm" title="">
	            		 <i class="fa fa-check"></i>
	            		 </a>';

                $btn .=  '<a href="'.site_url('topups/pickup/reject/'.$l->id).'" id="btn-'.$l->id.'" class="btn btn-danger btn-sm" title="">
                         <i class="fa fa-close"></i>
                         </a>';
            }
            else 
            {
                $btn = $l->status;
            }

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
        	"total"				=> 'hehehe',
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->topup_pickup->count_all(),
            "recordsFiltered"   => $this->topup_pickup->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}