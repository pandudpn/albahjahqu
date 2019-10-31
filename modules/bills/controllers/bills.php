<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class bills extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/students_bill', 'bill');
        $this->load->model('community/student_model', 'student');

        $this->app_id   = $this->session->userdata('user')->app_id;

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
            ->set('title', 'Buat Pembayaran Baru')
            ->build('form');
    }

    public function edit($id)
    {
        $is_exist = $this->bill->find($id);
        $student  = $this->student->find($is_exist->student_id);

        if($is_exist){
            $bills = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Pembayaran')
                ->set('data', $bills)
                ->set('student', $student)
                ->build('form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $student_id     = $this->input->post('student_id');
        $student_number = $this->input->post('nis');
        $bill_type      = $this->input->post('type');
        $bill_period_ty = $this->input->post('period_type');
        $bill_period    = $this->input->post('period');
        $bill_year      = $this->input->post('bill_year');
        $bill_amount   = $this->input->post('amount');
        $due_date       = $this->input->post('due_date');

        $data = array(
            'student_id'        => $student_id,
            'student_number'    => $student_number,
            'bill_type'         => $bill_type,
            'bill_period_type'  => $bill_period_ty,
            'bill_period'       => $bill_period,
            'bill_year'         => $bill_year,
            'bill_amount'       => $bill_amount,
            'bill_due_date'     => $due_date
        );
        
        if(!$id){
            $insert = $this->bill->insert($data);
        }else{
            $update = $this->bill->update($id, $data);
        }
        
        redirect(site_url('bills'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->bill->delete($id);

        redirect(site_url('bills'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->bill->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']          = $no;
            $row['school']      = $l->school_name;
            $row['student']     = $l->student_name;
            $row['bil_type']    = ucwords($l->bill_type);
            $row['bil_period_t']= ucwords($l->bill_period_type);
            $row['bil_period']  = $l->bill_period;
            $row['bil_year']    = $l->bill_year;
            $row['bil_date']    = date('j F, Y', strtotime($l->bill_due_date));
            $row['status']      = $l->bill_status;
            $row['edit']        = site_url('bills/edit/'.$l->id);
            $row['delete']      = site_url('bills/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->bill->count_all($this->app_id),
            "recordsFiltered"   => $this->bill->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function autoComplete(){
        $term   = $this->input->get('term');
        if(isset($term)){
            $query  = $this->student->get_by($this->app_id, $term);

            if($query->num_rows() > 0){
                foreach($query->result() AS $row){
                    $data[] = array(
                        'id'        => $row->id,
                        'student'   => $row->name,
                        'unit'      => $row->unit_name,
                        'nis'       => $row->nis
                    );
                }
                echo json_encode($data);
            }
        }
    }

}
