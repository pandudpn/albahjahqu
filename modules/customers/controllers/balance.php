<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class balance extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('customers/customer_model', 'customer');
        $this->load->model('customers/customer_balance_log_model', 'customer_balance_log');
        $this->load->model('user/eva_customer_model', 'eva_customer');
        $this->load->model('user/eva_customer_mutation_model', 'eva_customer_mutation');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index($customer_id)
    {
    	$customer 		= $this->customer->find($customer_id);
        $eva_customer 	= $this->eva_customer->find_by(array('account_user' => $customer->id));

    	if($this->input->post())
    	{
    		$amount = $this->input->post('amount');
    		$action = $this->input->post('action');
    		$remarks = $this->input->post('remarks');

    		//INSERT LOG
            $log_data = array(
                'admin_id'      => $this->session->userdata('user')->id,
                'admin_name'    => $this->session->userdata('user')->name,
                'amount'        => $amount,
                'to'            => $customer->phone,
                'cus_id'        => $customer->id,
                'cus_name'      => $customer->name,
                'method'		=> $action,
                'remarks' 		=> $remarks
            );

            $this->customer_balance_log->insert($log_data);

            $data = array(
            	'account_id' 		=> $eva_customer->id,
            	'account_eva' 		=> $eva_customer->account_no,
            	'account_user'		=> $customer->id,
            	'transaction_ref' 	=> $this->session->userdata('user')->id.'MNL'.time(),
            	'remarks' 			=> $remarks,
            	'starting_balance' 	=> $eva_customer->account_balance
            );

            if($action == 'credit')
            {
            	$data['credit'] 		= $amount;
            	$data['ending_balance'] = $eva_customer->account_balance + $amount;
            }
            else if($action == 'debit')
            {
            	$data['debit'] 			= $amount;
            	$data['ending_balance'] = $eva_customer->account_balance - $amount;
            }

            $insert = $this->eva_customer_mutation->insert($data);

            if($insert)
            {
            	$msg = 'success insert '.$action.' amount Rp. '.number_format($amount).' to '.$customer->name.' ('.$customer->phone.') ';

                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));
                redirect(site_url('customers/balance/'.$customer->id), 'refresh');
            }
            else
            {
            	$msg = 'something error.';

            	$this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
                redirect(site_url('customers/balance/'.$customer->id), 'refresh');
            }
    	}

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('customer_id', $customer_id)
            ->set('customer', $customer)
            ->set('eva_customer', $eva_customer)
            ->set('title', $customer->name)
    		->build('balance');
    }
}