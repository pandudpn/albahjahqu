<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class topups extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('customers/customer_model', 'customer');
        $this->load->model('topups/topup_model', 'topup');
        $this->load->model('topups/topup_log_model', 'topup_log');
        $this->load->model('dealers/dealer_model', 'dealer');
        $this->load->model('user/eva_corporate_mutation_model', 'eva_corporate_mutation');
        $this->load->model('user/eva_corporate_model', 'eva_corporate');
        $this->load->model('user/eva_customer_va_model', 'eva_customer_va');
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

    public function user()
    {
        if($this->input->post())
        {
            $phone      = $this->input->post('phone');
            $bank       = $this->input->post('bank');
            $amount     = intval($this->input->post('amount')) + 4950; //FEE TOPUP

            $customer   = $this->customer->find_by(array('phone' => $phone));

            if(!$customer)
            {
                $msg = 'customer not found.';
                $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));

                redirect(site_url('topups/user'), 'refresh');
                // die;
            }

            ///GENERATE or REGENERATE VA BEFORE EXECUTE CALLBACK
            $url = 'https://topup.okbabe.technology/virtual-account/'.strtolower($bank);

            $ch = curl_init( $url );
            $payload = json_encode( $data );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-ID: '.$customer->id));
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            $result = curl_exec($ch);
            curl_close($ch);

            /// END REGENERATE

            $customer_va = $this->eva_customer_va->find_by(array('account_user' => $customer->id));

            switch ($customer_va->bank_code) {
                case 'BNI':
                    $account_number = substr($customer_va->va, 4, 100);
                    $merchant_code  = substr($customer_va->va, 0, 4);
                    break;
                case 'MANDIRI':
                    $account_number = substr($customer_va->va, 5, 100);
                    $merchant_code  = substr($customer_va->va, 0, 5);
                    break;
                case 'BRI':
                    $account_number = substr($customer_va->va, 5, 100);
                    $merchant_code  = substr($customer_va->va, 0, 5);
                    break;
                
                default:
                    # code...
                    break;
            }


            // WAJIB ISI
            // external_id => customer_id-bank 
            // type => 'manual'
            // payment_id => user+unixtimestamp
            // amount 
            // bank_code 
            // id
            // account_number

            //CURL CALLBACK
            $data = array(
                'updated'                       => date('Y-m-d H:i:s'),
                'created'                       => date('Y-m-d H:i:s'),
                'amount'                        => $amount,
                'callback_virtual_account_id'   => 'manual-'.time(),
                'payment_id'                    => 'manual-'.time(),
                'external_id'                   => $customer->id.'-'.strtolower($customer_va->bank_code),
                'account_number'                => $account_number,
                'merchant_code'                 => $merchant_code,
                'bank_code'                     => $customer_va->bank_code,
                'transaction_timestamp'         => date('Y-m-d H:i:s'),
                'id'                            => 'manual-'.time(),
                'owner_id'                      => 'manual-'.time(),
                'type'                          => 'manual'
            );

            //INSERT LOG
            $log_data = array(
                'admin_id'      => $this->session->userdata('user')->id,
                'admin_name'    => $this->session->userdata('user')->name,
                'amount'        => $amount,
                'to'            => $phone,
                'cus_id'        => $customer->id,
                'cus_name'      => $customer->name
            );

            $this->topup_log->insert($log_data);


            //CALL CALLBACK
            // $url = 'http://localhost:8080/obb-new-isi-ulang/virtual-account/xendit/callback';
            $url = 'https://topup.okbabe.technology/virtual-account/xendit/callback';

            $ch = curl_init( $url );
            $payload = json_encode( $data );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            $result = curl_exec($ch);
            curl_close($ch);

            $msg = 'Topup success.';
            $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

            redirect(site_url('topups/user'), 'refresh');
        }

        $this->template->set('alert', $this->session->flashdata('alert'))
                        ->build('user');

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

    public function rollback($id=null)
    {
    	if($id)
    	{
    		$update 		= $this->topup->update($id, array('status' => 'payment', 'status_level' => 2));
    		$transaction 	= $this->topup->find($id);

    		//MUTASI fee ke dealer
            $dealer_eva     = $this->dealer->find($transaction->dealer_id)->eva;
            $dealer_account = $this->eva_corporate->find_by(array('account_no' => $dealer_eva));

            $data = array(
                'account_id'        => $dealer_account->id, 
                'account_eva'       => $dealer_eva, 
                'account_role'      => 'dealer', 
                'account_role_id'   => $dealer_account->account_user, 
                'transaction_type'  => 'RW',
                'transaction_ref'   => $transaction->trx_code, 
                'transaction_code'  => $transaction->service_code, 
                'purchase_ref'      => $transaction->ref_code, 
                'remarks'           => 'Rollback Withdraw via Admin by '.$this->session->userdata('user')->name, 
                'starting_balance'  => intval($dealer_account->account_balance), 
                'credit'            => intval($transaction->base_price),
                'ending_balance'    => intval($dealer_account->account_balance + $transaction->base_price)
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