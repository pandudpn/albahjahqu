<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class transactions extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('transactions/transaction_model', 'transaction');
        $this->load->model('transactions/transaction_log_model', 'transaction_log');
        $this->load->model('user/eva_customer_mutation_model', 'eva_customer_mutation');
        $this->load->model('user/eva_corporate_mutation_model', 'eva_corporate_mutation');
        $this->load->model('user/eva_customer_model', 'eva_customer');
        $this->load->model('user/eva_corporate_model', 'eva_corporate');
        $this->load->model('customers/customer_model', 'customer');
        $this->load->model('dealers/dealer_model', 'dealer');
        $this->load->model('billers/biller_model', 'biller');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        
    	$this->template->set('alert', $this->session->flashdata('alert'))
                        ->set('from', $from)
                        ->set('to', $to)
    					->build('index');
    }

    public function changestatus($status, $id)
    {
        $transaction = $this->transaction->find($id);

        $data_log = array(
            'transaction_id'    => $transaction->id, 
            'transaction_code'  => $transaction->trx_code, 
            'user_id'           => $this->session->userdata('user')->id, 
            'user_name'         => $this->session->userdata('user')->name, 
            'user_role'         => $this->session->userdata('user')->role, 
            'user_phone'        => $this->session->userdata('user')->phone,
            'user_dealer_id'    => $this->session->userdata('user')->dealer_id,
            'user_dealer_name'  => $this->dealer->find($this->session->userdata('user')->dealer_id)->name,
            'remarks'           => 'Change status from '.$transaction->status.' to '.$status
        );

        $this->transaction_log->insert($data_log);

        $data_status = array(
            'status_level'      => 4,
            'status_provider'   => '00',
            'status'            => $status
        );

        if(!empty($this->input->post('ref_code')))
        {
            $data_status['ref_code']   = $this->input->post('ref_code');
        }
        
        if(!empty($this->input->post('token_code')))
        {
            $data_status['token_code'] = $this->input->post('token_code');
        }

        $update = $this->transaction->update($id, $data_status);

        if($status == 'rejected')
        {
            $customer               = $this->customer->find($transaction->cus_id);
            $eva_customer           = $this->eva_customer->find_by(array('account_user' => $transaction->cus_id));
            // echo $this->db->last_query();die;

            //MUTASI selling 
            $data = array(
                'account_id'        => $eva_customer->id, 
                'account_eva'       => $eva_customer->account_no, 
                'account_user'      => $customer->id, 
                'transaction_ref'   => $transaction->trx_code, 
                'transaction_code'  => $transaction->service_code, 
                'purchase_ref'      => $transaction->ref_code, 
                'remarks'           => 'Refund rejected transaction', 
                'starting_balance'  => intval($eva_customer->account_balance), 
                'credit'            => intval($transaction->selling_price), 
                'ending_balance'    => intval(($eva_customer->account_balance + $transaction->selling_price))
            );

            $mutation_id = $this->eva_customer_mutation->insert($data);

            //MUTASI fee ke biller
            $biller_eva     = $this->biller->find($transaction->biller_id)->eva;
            $biller_account = $this->eva_corporate->find_by(array('account_no' => $biller_eva));

            $data = array(
                'account_id'        => $biller_account->id, 
                'account_eva'       => $biller_eva, 
                'account_role'      => 'biller', 
                'account_role_id'   => $biller_account->account_user, 
                'transaction_ref'   => $transaction->trx_code, 
                'transaction_code'  => $transaction->service_code, 
                'purchase_ref'      => $transaction->ref_code, 
                'remarks'           => 'Refund rejected transaction', 
                'starting_balance'  => intval($biller_account->account_balance), 
                'debit'             => intval($transaction->biller_fee),
                'ending_balance'    => intval($biller_account->account_balance - $transaction->biller_fee)
            );

            $mutation_id = $this->eva_corporate_mutation->insert($data);

            //MUTASI fee ke dealer
            $dealer_eva     = $this->dealer->find($transaction->dealer_id)->eva;
            $dealer_account = $this->eva_corporate->find_by(array('account_no' => $dealer_eva));

            $data = array(
                'account_id'        => $dealer_account->id, 
                'account_eva'       => $dealer_eva, 
                'account_role'      => 'dealer', 
                'account_role_id'   => $dealer_account->account_user, 
                'transaction_ref'   => $transaction->trx_code, 
                'transaction_code'  => $transaction->service_code, 
                'purchase_ref'      => $transaction->ref_code, 
                'remarks'           => 'Refund rejected transaction', 
                'starting_balance'  => intval($dealer_account->account_balance), 
                'debit'             => intval($transaction->dealer_fee),
                'ending_balance'    => intval($dealer_account->account_balance - $transaction->dealer_fee)
            );

            $mutation_id = $this->eva_corporate_mutation->insert($data);
        }

        if($update)
        {
            redirect(site_url('transactions'), 'refresh');
        }
    }

    public function download()
    {
        $this->transaction->download();
    }

    public function datatables()
    {
        $list = $this->transaction->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->trx_code;
            $row[] = $l->remarks;

            if(empty($l->biller_name))
            {
                $row[] = '-';
            }
            else
            {
                $row[] = $l->biller_name;
            }

            if(empty($l->token_code))
            {
                $token = '-';
            }
            else
            {
                $token = $l->token_code;
            }

            if(empty($l->ref_code))
            {
                $ref_code = '-';
            }
            else
            {
                $ref_code = $l->ref_code;
            }

            $row[] = $ref_code.' / '.$token;
            $row[] = $l->cus_phone;
            $row[] = $l->destination_no;

            $row[] = 'Rp. '.number_format($l->selling_price);
            $row[] = 'Rp. '.number_format($l->base_price);
            $row[] = 'Rp. '.number_format($l->dealer_fee);
            $row[] = 'Rp. '.number_format($l->biller_fee);
            $row[] = 'Rp. '.number_format($l->dekape_fee);
            $row[] = 'Rp. '.number_format($l->partner_fee);
            $row[] = 'Rp. '.number_format($l->user_fee);
            $row[] = 'Rp. '.number_format($l->user_cashback);
            $row[] = $l->status;
            $row[] = $l->created_on;

            $btn   = '';

            if($l->status != 'approved' && $l->status != 'rejected') 
            {
                $btn  .= '<a href="javascript:void(0)" onclick="alert_approve(\''.site_url('transactions/changestatus/approved/'.$l->id).'\')" class="btn btn-primary btn-sm" style="margin-bottom: 5px;">
                      <i class="fa fa-check"></i>  Approve
                      </a> <br/>';

                $btn  .= '<a href="javascript:void(0)" onclick="alert(\''.site_url('transactions/changestatus/rejected/'.$l->id).'\')" class="btn btn-danger btn-sm">
                      <i class="fa fa-close"></i>  Reject
                      </a>';
            }
            else
            {
                $btn .= '-';
            }
            

            // $btn   = '<a href="'.site_url('menu/edit/'.$l->id).'" class="btn btn-success btn-sm">
            //             <i class="fa fa-pencil"></i>
            //           </a> &nbsp;';

            // $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('menu/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
            //             <i class="fa fa-trash"></i>
            //           </a>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->transaction->count_all(),
            "recordsFiltered"   => $this->transaction->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}