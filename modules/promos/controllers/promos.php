<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class promos extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('promos/promo_model', 'promo');
        $this->load->model('references/dealers_model', 'dealer');

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
        $dealer  = $this->dealer->get_all();

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add promo')
            ->set('dealer', $dealer)
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->promo->find($id);

        if($is_exist){
            $promo = $is_exist;
            $dealer  = $this->dealer->get_all();

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit promo')
                ->set('data', $promo)
                ->set('dealer', $dealer)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		 = $this->input->post('id');

        $dealer          = $this->input->post('dealer');
        $title           = $this->input->post('title');
        $text            = $this->input->post('text');
        $execute_time    = $this->input->post('execute_time');
        $execute_date    = $this->input->post('execute_date');
        $execute_month   = $this->input->post('execute_month');
        $execute_year    = $this->input->post('execute_year');
        $execute_period  = $this->input->post('execute_period');
        $category        = $this->input->post('category');
        $status          = $this->input->post('status');

        $data = array(
            'title'         => $title,
            'text'          => $text,
            'execute_time'  => $execute_time,
            'execute_date'  => $execute_date,
            'execute_month' => $execute_month,
            'execute_year'  => $execute_year,
            'execute_period' => $execute_period,
            'category'      => $category,
            'status'        => $status
        );

        if(!empty($dealer))
        {
        	$data['dealer'] = $dealer;
        }
        
        if(!$id)
        {
            $insert = $this->promo->insert($data);
            redirect(site_url('promos'), 'refresh');
        }
        else
        {
            if(empty($dealer))
            {
                $data['dealer'] = NULL;
            }

            $update = $this->promo->update($id, $data);
            redirect(site_url('promos'), 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->promo->delete($id);

        redirect(site_url('promos'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->promo->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->execute_time;
            $row[] = $l->execute_date;
            $row[] = $l->execute_month;
            $row[] = $l->execute_year;
            $row[] = $l->execute_period;
            $row[] = $l->title;
            $row[] = $l->text;
            $row[] = $l->dealer_name;
            $row[] = $l->category;
            $row[] = $l->status;
            $row[] = $l->created_on;

            $btn   = '<a href="'.site_url('promos/edit/'.$l->id).'" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('promos/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->promo->count_all(),
            "recordsFiltered"   => $this->promo->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}