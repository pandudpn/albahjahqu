<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class billers extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('billers/biller_model', 'biller');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('index');
    }

    public function add()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add Biller')
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->biller->find($id);

        if($is_exist){
            $biller = $is_exist;
            
            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Biller')
                ->set('data', $biller)
                ->build('form');
        }
    }

    public function save()
    {
        $id       = $this->input->post('id');

        $name          = $this->input->post('name');
        $code          = $this->input->post('code');
        $pic           = $this->input->post('pic');
        $pic_phone     = $this->input->post('pic_phone');
        $pic_email     = $this->input->post('pic_email');
        $date_joined = $this->input->post('date_joined');
        $note = $this->input->post('note');

        if(empty($date_joined))
        {
            $date_joined = NULL;
        }

        $data = array(
                'name'          => $name,
                'code'          => $code,
                'pic'           => $pic,
                'pic_email'     => $pic_email,
                'pic_phone'     => $pic_phone,
                'date_joined'   => $date_joined,
                'note'          => $note
            );
        
        if(!$id){
            
            $last_id = $this->biller->last_id()->id;
            $leftPad = str_pad(intval($last_id + 1), 4, "0", STR_PAD_LEFT);
            
            $data['eva'] = 'B'.$leftPad.str_replace(' ', '', strtoupper($code));
            
            $insert = $this->biller->insert($data);
            redirect(site_url('billers'), 'refresh');
        }else{
            $update = $this->biller->update($id, $data);
            redirect(site_url('billers'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->biller->delete($id);

        redirect(site_url('billers'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->biller->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->name;
            $row[] = $l->pic;
            $row[] = $l->pic_phone;
            $row[] = $l->pic_email;
            $row[] = $l->date_joined;
            $row[] = $l->note;

            $btn   = '<a href="'.site_url('billers/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('billers/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->biller->count_all(),
            "recordsFiltered"   => $this->biller->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}