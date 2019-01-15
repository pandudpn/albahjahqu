<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class topups extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('customers/customer_model', 'customer');
        $this->load->model('topups/topup_model', 'topup');
        $this->load->model('dealers/dealer_model', 'dealer');
        $this->load->model('user/eva_corporate_mutation_model', 'eva_corporate_mutation');
        $this->load->model('user/eva_corporate_model', 'eva_corporate');
        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        $dealer = $this->input->get('dealer');

        $dealers = $this->dealer->order_by('name', 'asc');
        $dealers = $this->dealer->find_all_by(array('deleted' => '0'));

        $total_sum = $this->topup->total_sum()->total_sum;

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('from', $from)
            ->set('to', $to)
            ->set('dealer', $dealer)
            ->set('dealers', $dealers)
            ->set('total_sum', $total_sum)
    		->build('index');
    }

    public function set($id=null)
    {
    	if($id)
    	{
    		$update 		= $this->topup->update($id, array('status' => 'approved', 'status_level' => 4));
    		$transaction 	= $this->topup->find($id);

    		//MUTASI fee ke dealer
            $dealer_eva     = $this->dealer->find($transaction->dealer_id)->eva;
            $dealer_account = $this->eva_corporate->find_by(array('account_no' => $dealer_eva));

            $data = array(
                'account_id'        => $dealer_account->id, 
                'account_eva'       => $dealer_eva, 
                'account_role'      => 'dealer', 
                'account_role_id'   => $dealer_account->account_user, 
                'transaction_type'  => 'W',
                'transaction_ref'   => $transaction->trx_code, 
                'transaction_code'  => $transaction->service_code, 
                'purchase_ref'      => $transaction->ref_code, 
                'remarks'           => 'Withdraw via Admin by '.$this->session->userdata('user')->name, 
                'starting_balance'  => intval($dealer_account->account_balance), 
                'debit'             => intval($transaction->base_price),
                'ending_balance'    => intval($dealer_account->account_balance - $transaction->base_price)
            );

            $mutation_id = $this->eva_corporate_mutation->insert($data);

    		if($update)
    		{

    			echo 'success';
    		}
    	}
    }

    public function download()
    {
        $this->topup->download();
    }

    public function datatables()
    {
        $list = $this->topup->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->customer_name;
            $row[] = $l->customer_phone;
            // $row[] = $l->customer_email;
            $row[] = $l->dealer_name;
            $row[] = 'Rp. '.number_format($l->base_price);
            $row[] = $l->note;
            $row[] = $l->created_on;

            // $btn   = '<a href="'.site_url('menu/edit/'.$l->id).'" class="btn btn-success btn-sm">
            //             <i class="fa fa-pencil"></i>
            //           </a> &nbsp;';

            // $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('menu/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
            //             <i class="fa fa-trash"></i>
            //           </a>';

            if($l->status == 'approved')
            {
            	$btn = 'commited <a href="javascript:;" id="btn-'.$l->id.'" class="btn btn-danger btn-sm" onclick="rollback_topup(\''.$l->id.'\')" title="rollback">
	            		 <i class="fa fa-undo"></i>
	            		 </a>';
            }
            else
            {
            	$btn =  '<a href="javascript:;" id="btn-'.$l->id.'" class="btn btn-success btn-sm" onclick="approve_topup(\''.$l->id.'\')" title="">
	            		 <i class="fa fa-check"></i>
	            		 </a>';
            }

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
        	"total"				=> 'hehehe',
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->topup->count_all(),
            "recordsFiltered"   => $this->topup->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}