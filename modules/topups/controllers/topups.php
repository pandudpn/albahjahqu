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
        $this->load->model('user/eva_customer_mutation_model', 'eva_customer_mutation');
        $this->load->model('transactions/transaction_model', 'transaction');
        $this->load->model('user/eva_customer_model', 'eva_customer');
        $this->load->model('customers/customer_session_model', 'customer_session');

        $this->load->model('services/service_model', 'service_code');
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

            if($bank == 'mandiri_manual' || $bank == 'bni_manual' || $bank == 'bri_manual')
            {
                $amount                 = $amount - 4950;
                $bank_code              = explode('_', $bank);
                $bank_code              = strtoupper($bank_code[0]);

                // $customer               = $this->customer->find($customer_id);
                $customer_eva           = $this->eva_customer->find_by(array('account_user' => $customer->id));

                $service_apps           = $this->service_apps($bank_code);
                $trxleftpad             = (25 - strlen($customer->id.$service_apps));
                $trx_id                 = $customer->id.$service_apps.str_pad(time(), $trxleftpad, "0", STR_PAD_LEFT);
                $trx_okbabe             = $trx_id;
                
                //insert trx

                $sc                     = $this->service_code->find_by(array('provider' => $service_apps));

                $trx_data = array(
                    'trx_code'      => $trx_id, 
                    'ref_code'      => $data->id,
                    'service_code'  => $sc->service.$sc->value.$sc->provider.$sc->prepaid.$sc->type,
                    'service_id'    => $sc->id,
                    'service_menu'  => '9999',
                    'service_type'  => '01',
                    'cus_id'        => $customer->id,
                    'cus_phone'     => $customer->phone,
                    'destination_no'        => $data->account_number,
                    'destination_holder'    => $customer->name,
                    'location_type' => 'none',
                    'base_price'    => $amount,
                    'selling_price' => intval($amount),
                    'biller_fee'    => 0,
                    'biller_id'     => NULL,
                    'dealer_id'     => $customer->dealer_id,
                    'remarks'       => 'TOPUP Manual Via '.$bank_code,
                    'status'        => 'payment',
                    'status_level'  => '2'
                );

                $trx_id        = $this->transaction->insert($trx_data);
                $transaction   = $this->transaction->find($trx_id);

                $mts_data = array(
                    'account_id'        => $customer_eva->id,
                    'account_eva'       => $customer_eva->account_no,
                    'account_user'      => $customer->id,
                    'transaction_ref'   => $trx_data['trx_code'],
                    'remarks'           => 'TOPUP Manual Via '.$bank_code,
                    'starting_balance'  => $customer_eva->account_balance,
                    'credit'            => intval($amount),
                    'ending_balance'    => $customer_eva->account_balance + intval($amount)
                );

                $mts_id = $this->eva_customer_mutation->insert($mts_data);

                $msg = 'Topup manual berhasil';
                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

                redirect(site_url('topups/user'), 'refresh');
                die;
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

    private function service_apps($bank)
    {
        switch ($bank) {
            case 'MANDIRI':
                $service_apps = 'BMR';
                break;
            case 'BNI':
                $service_apps = 'BNI';
                break;
            case 'BRI':
                $service_apps = 'BRI';
                break;
        }

        return $service_apps;
    }

    public function approve($trx_code=null)
    {
    	if($trx_code)
    	{
            //var_dump($trx_code);die;
            $amount             = $this->input->post('base_price');

            $transaction        = $this->transaction->find_by(array('trx_code' => $trx_code));
            $customer           = $this->customer->find($transaction->cus_id);
            $customer_eva       = $this->eva_customer->find_by(array('account_user' => $customer->id));
            $service_code       = $this->service_code->find($transaction->service_id);

            $mts_data = array(
                'account_id'        => $customer_eva->id,
                'account_eva'       => $customer_eva->account_no,
                'account_user'      => $customer->id,
                'transaction_ref'   => $transaction->trx_code,
                'remarks'           => $service_code->remarks,
                'starting_balance'  => $customer_eva->account_balance,
                'credit'            => intval($amount),
                'ending_balance'    => $customer_eva->account_balance + intval($amount)
            );

            $update = $this->transaction->update($transaction->id, array(
                'base_price' => intval($amount),
                'selling_price' => intval($amount),
                'status' => 'approved', 
                'status_level' => '4', 
                'status_provider' => '00'
            ));

            $mts_id = $this->eva_customer_mutation->insert($mts_data);

    		// $update 		= $this->topup->update($id, array('status' => 'approved', 'status_level' => 4));
    		// $transaction 	= $this->topup->find($id);

    		// //MUTASI fee ke dealer
      //       $dealer_eva     = $this->dealer->find($transaction->dealer_id)->eva;
      //       $dealer_account = $this->eva_corporate->find_by(array('account_no' => $dealer_eva));

      //       $data = array(
      //           'account_id'        => $dealer_account->id, 
      //           'account_eva'       => $dealer_eva, 
      //           'account_role'      => 'dealer', 
      //           'account_role_id'   => $dealer_account->account_user, 
      //           'transaction_type'  => 'W',
      //           'transaction_ref'   => $transaction->trx_code, 
      //           'transaction_code'  => $transaction->service_code, 
      //           'purchase_ref'      => $transaction->ref_code, 
      //           'remarks'           => 'Withdraw via Admin by '.$this->session->userdata('user')->name, 
      //           'starting_balance'  => intval($dealer_account->account_balance), 
      //           'debit'             => intval($transaction->base_price),
      //           'ending_balance'    => intval($dealer_account->account_balance - $transaction->base_price)
      //       );

      //       $mutation_id = $this->eva_corporate_mutation->insert($data);

    		if($update && $mts_id)
    		{
                $title   = 'OKBABE+';
                $session = $this->customer_session->order_by('id', 'desc');
                $session = $this->customer_session->find_by(array('cus_id' => $transaction->cus_id));

                $fcm_id = Array();
                array_push($fcm_id, $session->cus_fcm_id);

                $this->push_notification($fcm_id, $title, 'Topup manual anda sudah diterima senilai Rp. '.number_format($amount), 'transaction', '');

                $msg = 'Topup approve success.';
                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

    			redirect(site_url('topups'), 'refresh');
    		}
    	}
    }

    public function reject($trx_code=null)
    {
    	if($trx_code)
    	{
    		$transaction        = $this->transaction->find_by(array('trx_code' => $trx_code));

            $update = $this->transaction->update($transaction->id, array(
                'status' => 'rejected', 
                'status_level' => '5', 
                'status_provider' => '00'
            ));

    		if($update)
    		{
                $title   = 'OKBABE+';
                $session = $this->customer_session->order_by('id', 'desc');
                $session = $this->customer_session->find_by(array('cus_id' => $transaction->cus_id));

                $fcm_id = Array();
                array_push($fcm_id, $session->cus_fcm_id);

                $this->push_notification($fcm_id, $title, 'Topup manual anda dibatalkan', 'transaction', '');

                $msg = 'Topup reject success.';
                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

                redirect(site_url('topups'), 'refresh');

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

            if($l->image != NULL)
            {
                $row[] = '<a href="javascript:;" onclick="show_image(\''.$l->image.'\')">[view image]</a>';
            }
            else
            {
                $row[] = '-';
            }

            $row[] = $l->created_on;

            // $btn   = '<a href="'.site_url('menu/edit/'.$l->id).'" class="btn btn-success btn-sm">
            //             <i class="fa fa-pencil"></i>
            //           </a> &nbsp;';

            // $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('menu/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
            //             <i class="fa fa-trash"></i>
            //           </a>';

            
            if($l->status == 'dispute')
            {
            	$btn =  '<a href="javascript:;" id="btn-'.$l->id.'" class="btn btn-success btn-sm" onclick="approve_topup(\''.site_url('topups/approve/'.$l->trx_code).'\',\''.$l->base_price.'\')" title="">
	            		 <i class="fa fa-check"></i>
	            		 </a>';

                $btn .=  '<a href="'.site_url('topups/reject/'.$l->trx_code).'" id="btn-'.$l->id.'" class="btn btn-danger btn-sm" title="">
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
            "recordsTotal"      => $this->topup->count_all(),
            "recordsFiltered"   => $this->topup->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function push_notification($gcm_ids, $title, $msg, $action='transaction', $id='')
    {
        $url     = 'https://fcm.googleapis.com/fcm/send';
        $message = array("title" => $title, "body" => $msg, "click_action" => $action);
        $fields  = array(
              'registration_ids'  => $gcm_ids,
              'notification'      => $message,
              'data'              => array("title" => $title, "message" => $msg)
        );

        $api_key = 'AAAAf_Rr2ig:APA91bGe0MVf85hli70S__JHZMjIhZILomI9WkEv_wyLqf6K8mm2A4oHsmKGsS9UJr4CniLF518W9ECdncTtUhc-f-h8NFPRDCLU0M5nAM_bpeDxYPRk2U_OA1b8F3zUBOQHiMWmVMud';

        $headers = array(
             'Authorization: key='.$api_key,
             'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
    }
}