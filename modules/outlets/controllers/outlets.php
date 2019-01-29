<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class outlets extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('outlets/outlet_model', 'outlet');
        $this->check_login();
    }

    public function index()
    {
        $outlets = $this->outlet->outlet_list();
        $outlet = $this->input->get('outlet');

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('outlets', $outlets)
            ->set('outlet', $outlet)
    		->build('index');
    }

    // public function add()
    // {
    //     $cities           = $this->city->get_all();
    //     $province         = $this->province->get_all();

    //     $this->template
    //         ->set('alert', $this->session->flashdata('alert'))
    //         ->set('title', 'Add Partner')
    //         ->set('cities', $cities)
    //         ->set('province', $province)
    // 		->build('form');
    // }

    public function edit($id)
    {
        $is_exist = $this->outlet->find($id);

        if($is_exist){
            $outlet = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit outlet')
                ->set('data', $outlet)
                ->build('form');
        }
    }

    // public function delete($id)
    // {
    //     $delete = $this->partner->delete($id);

    //     redirect(site_url('partners'), 'refresh');
    // }

    public function save($id)
    {
        if($this->input->post())
        {
            $data = array(
                'outlet_number' 	=> $this->input->post('outlet_number'),
                'outlet_name' 		=> $this->input->post('outlet_name'),
                'level'     		=> $this->input->post('level')
            );

            $update = $this->outlet->update($id, $data);

            if($update)
            {
                redirect(site_url('outlets'), 'refresh');
            }
        }

    }

    public function datatables()
    {
        $list = $this->outlet->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->name;
            $row[] = $l->outlet_number;
            $row[] = $l->outlet_name;
            $row[] = $l->level;
            $row[] = $l->dealer_name;

            $btn   = '<a href="'.site_url('outlets/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="'.site_url('outlets/transaction/'.$l->outlet_number).'" class="btn btn-primary btn-sm" title="Transaction Lists">
                        <i class="fa fa-list-ul"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->outlet->count_all(),
            "recordsFiltered"   => $this->outlet->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}