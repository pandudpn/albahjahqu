<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class alert extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('dealers/dealer_boxes_model', 'dealer_boxes');
        $this->load->model('dealers/dealer_box_services_model', 'dealer_box_services');
        $this->load->model('dealers/dealer_box_stocks_model', 'dealer_box_stocks'); 
        $this->load->model('dealers/dealer_box_stock_alert_model', 'dealer_box_stock_alert'); 
        
        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {  
        $boxes = $this->dealer_boxes->find($box_id);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('boxes', $boxes)
            ->set('box_id', $box_id)
    		->build('boxes/alert/index');
    }

    public function add()
    {
        if($this->session->userdata('user')->role == 'dealer') 
        {
            $box_services = $this->dealer_box_services->where('dealer_id', $this->session->userdata('user')->dealer_id);
        }

        $box_services   = $this->dealer_box_services->find_all_by(array('deleted' => '0'));
        
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Alert')
            ->set('box_services', $box_services)
        	->build('boxes/alert/form');
    }

    public function edit($id)
    {
        $is_exist = $this->dealer_box_stock_alert->find($id);

        if($is_exist){

            if($this->session->userdata('user')->role == 'dealer') 
            {
                $box_services = $this->dealer_box_services->where('dealer_id', $this->session->userdata('user')->dealer_id);
            }

            $box_services   = $this->dealer_box_services->find_all_by(array('deleted' => '0'));
            

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Alert')
                ->set('box_services', $box_services)
                ->set('data', $is_exist)
                ->build('boxes/alert/form');
        }
    }

    public function save()
    {
        $id     = $this->input->post('id');
        
        $slotbox        = $this->input->post('slotbox');
        $denom          = $this->input->post('denom');
        $first_alert    = $this->input->post('first_alert');
        $second_alert   = $this->input->post('second_alert');
        $third_alert    = $this->input->post('third_alert');
        $status         = $this->input->post('status');

        $data = array(
            'slotbox'       => $slotbox,
            'denom'         => $denom,
            'first_alert'   => $first_alert,
            'second_alert'  => $second_alert,
            'third_alert'   => $third_alert,
            'status'        => $status
        );

        if(!$id){
            
            $insert = $this->dealer_box_stock_alert->insert($data);
            redirect(site_url('dealers/boxes/alert'), 'refresh');
        }else{
            $update = $this->dealer_box_stock_alert->update($id, $data);
            redirect(site_url('dealers/boxes/alert'), 'refresh');
        }

    }

    public function delete($id)
    {
        $delete = $this->dealer_box_stock_alert->delete($id);

        redirect(site_url('dealers/boxes/alert'), 'refresh');
    }

    public function datatables()
    {
        $list   = $this->dealer_box_stock_alert->get_datatables();
        
        $data = array();
        $no   = $_POST['start'];
        
        foreach ($list as $l) {

            $box_service = $this->dealer_box_services->find($l->slotbox);
            $slotbox = $box_service->dealer_name.' / '.$box_service->ipbox.' / '.$box_service->type.' / '.$box_service->slot.' / '.$box_service->service_type.' / '.$box_service->service_coverage;

            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $slotbox;
            $row[] = $l->denom;
            $row[] = $l->first_alert;
            $row[] = $l->second_alert;
            $row[] = $l->third_alert;
            $row[] = $l->last_alert;
            $row[] = $l->status;
            // $row[] = $l->partner_fee;

            $btn  = '<a href="'.site_url('dealers/boxes/alert/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('dealers/boxes/alert/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->dealer_box_stock_alert->count_all($ip_box),
            "recordsFiltered"   => $this->dealer_box_stock_alert->count_filtered($ip_box),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}