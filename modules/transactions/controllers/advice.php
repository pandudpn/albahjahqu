<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class advice extends Api_Controller {
	
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
        $this->load->model('customers/customer_session_model', 'customer_session');
        $this->load->model('references/ref_service_codes_model', 'ref_service_code');

        $this->load->helper('text');
    }

    public function index()
    {
    	$trx_code 			= $this->input->post('trx_code');
        $transaction        = $this->transaction->find_by(array('trx_code' => $trx_code)); 
        $ref_service_code   = $this->ref_service_code->find($transaction->service_id);

        if(!$transaction)
        {
            $this->rest->set_error('transaction not found');
            $this->rest->render();
            die;
        }

        //URL 
        // $url            = 'https://h2hdev.narindo.com:9902/v3/advice'; //DEV

        $url            = 'https://h2h.narindo.com:9922/v3/advice'; //PROD
        $headers        = $this->input->request_headers();

        // DEVEL
        // $userid         = '11111';
        // $password       = '123456';

        // PROD
        $userid         = '22893';
        $password       = '@d3k4pEh2h';

        //PARAMS

        $reqid          = substr($transaction->trx_code, -13);
        $msisdn         = $transaction->destination_no;
        $product        = $ref_service_code->biller_code;
        $sign           = strtoupper(sha1($reqid.$msisdn.$product.$userid.$password));
        $mid            = 'dekape';
        $trx_code       = $transaction->trx_code;

        $params = array(
            'reqid'     => $reqid, 
            'msisdn'    => $msisdn, 
            'product'   => $product, 
            'userid'    => $userid, 
            'sign'      => $sign, 
            'mid'       => $mid, 
            'trx_code'  => $trx_code
        );

        $date   = new DateTime( $transaction->modified_on );
        $date2  = new DateTime( date('Y-m-d H:i:s') );

        $diff   = $date2->getTimestamp() - $date->getTimestamp();

        if($diff < 60)
        {
            $msg = 'silahkan tunggu '.(60 - $diff).' detik lagi untuk pengecekan trx / advice.';
            $this->rest->set_data($msg);
            $this->rest->render();
            die;
        }

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $output = curl_exec($ch); 
        curl_close($ch);  
    
        $response = json_decode($output);

        if($response->status == '1')
        {
            // $this->rest->set_data($response);
            // $this->rest->render();
            // die;

            // redirect(site_url('transactions/advice/changestatus/approved/'.$id), 'refresh');
            // die;

            $url = site_url('transactions/advice/changestatus/approved/'.$transaction->id);

            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
            $output = curl_exec($ch); 
            curl_close($ch);  
        }
        else if($response->status == '0')
        {
            // redirect(site_url('transactions/advice/changestatus/rejected/'.$id), 'refresh');
            // die;

            $url = site_url('transactions/advice/changestatus/rejected/'.$transaction->id);

            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
            $output = curl_exec($ch); 
            curl_close($ch);  
        }
        else if($response->status == '13')
        {
            // redirect(site_url('transactions/advice/changestatus/rejected/'.$id), 'refresh');
            // die;

            $url = site_url('transactions/advice/changestatus/rejected/'.$transaction->id);

            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
            $output = curl_exec($ch); 
            curl_close($ch);  
        }
        else if($response->message)
        {
            // $msg = $response->message;
            // $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
            // redirect(site_url('transactions/pending'), 'refresh');
            // die;

            $this->rest->set_error($response->message);
            $this->rest->render();
            die;
        }
        else
        {
            // $msg = 'ada sesuatu yang salah. silahkan coba kembali';
            // $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
            // redirect(site_url('transactions/pending'), 'refresh');
            // die;

            $this->rest->set_error('ada sesuatu yang salah. silahkan coba kembali');
            $this->rest->render();
            die;
        }

        $this->rest->set_data($response);
        $this->rest->render();
        die;
    }

    public function changestatus($status, $id)
    {
        $transaction  = $this->transaction->find($id);
        $service_code = $this->ref_service_code->find($transaction->service_id);
        $reason  = $this->input->post('reason');

        $data_log = array(
            'transaction_id'    => $transaction->id, 
            'transaction_code'  => $transaction->trx_code, 
            'user_id'           => $this->session->userdata('user')->id, 
            'user_name'         => $this->session->userdata('user')->name, 
            'user_role'         => $this->session->userdata('user')->role, 
            'user_phone'        => $this->session->userdata('user')->phone,
            'user_dealer_id'    => $this->session->userdata('user')->dealer_id,
            'user_dealer_name'  => $this->dealer->find($this->session->userdata('user')->dealer_id)->name,
            'remarks'           => 'Change status from '.$transaction->status.' ('.$transaction->status_level.': '.$transaction->status_provider.') to '.$status,
            'reason'            => $reason
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

        if($status == 'rejected')
        {
            $data_status['status_level'] = 5;
        }

        $update = $this->transaction->update($id, $data_status);

        if($status == 'approved')
        {
            if(($transaction->status_level != '2') || ($transaction->status_level == '2' && $transaction->status_provider != '00'))
            {

                if($transaction->dealer_id != '1')
                {
                    $first  = substr($transaction->service_code, 0, 3);
                    $second = substr($transaction->service_code, 7, 5);

                    if($first.$second == 'PLSTSL01')
                    {
                        //MUTASI fee ke dealer
                        $dealer_eva     = $this->dealer->find($transaction->dealer_id)->eva;
                        $dealer_account = $this->eva_corporate->find_by(array('account_no' => $dealer_eva));

                        $data = array(
                            'account_id'        => $dealer_account->id, 
                            'account_eva'       => $dealer_eva, 
                            'account_role'      => 'dealer', 
                            'account_role_id'   => $dealer_account->account_user, 
                            'transaction_type'  => 'S', 
                            'transaction_ref'   => $transaction->trx_code, 
                            'transaction_code'  => $transaction->service_code, 
                            'purchase_ref'      => $transaction->ref_code, 
                            'remarks'           => 'Transaction fee '.$service_code->remarks.' ('.$transaction->destination_no.')', 
                            'starting_balance'  => intval($dealer_account->account_balance), 
                            'credit'            => intval($transaction->base_price),
                            'ending_balance'    => intval($dealer_account->account_balance + $transaction->base_price)
                        );

                        $mutation_id = $this->eva_corporate_mutation->insert($data);
                    }
                }

                if(intval($transaction->biller_fee) > 0)
                {
                    //MUTASI fee ke biller
                    $biller_eva     = $this->biller->find($transaction->biller_id)->eva;
                    $biller_account = $this->eva_corporate->find_by(array('account_no' => $biller_eva));

                    $data = array(
                        'account_id'        => $biller_account->id, 
                        'account_eva'       => $biller_eva, 
                        'account_role'      => 'biller', 
                        'account_role_id'   => $biller_account->account_user,
                        'transaction_type'  => 'F', 
                        'transaction_ref'   => $transaction->trx_code, 
                        'transaction_code'  => $transaction->service_code, 
                        'purchase_ref'      => $transaction->ref_code, 
                        'remarks'           => 'Transaction fee '.$service_code->remarks.' ('.$transaction->destination_no.')', 
                        'starting_balance'  => intval($biller_account->account_balance), 
                        'credit'            => intval($transaction->biller_fee),
                        'ending_balance'    => intval($biller_account->account_balance + $transaction->biller_fee)
                    );

                    $mutation_id = $this->eva_corporate_mutation->insert($data);
                }

                if(intval($transaction->dealer_fee) > 0)
                {
                    //MUTASI fee ke dealer
                    $dealer_eva     = $this->dealer->find($transaction->dealer_id)->eva;
                    $dealer_account = $this->eva_corporate->find_by(array('account_no' => $dealer_eva));

                    $data = array(
                        'account_id'        => $dealer_account->id, 
                        'account_eva'       => $dealer_eva, 
                        'account_role'      => 'dealer', 
                        'account_role_id'   => $dealer_account->account_user, 
                        'transaction_type'  => 'F', 
                        'transaction_ref'   => $transaction->trx_code, 
                        'transaction_code'  => $transaction->service_code, 
                        'purchase_ref'      => $transaction->ref_code, 
                        'remarks'           => 'Transaction fee '.$service_code->remarks.' ('.$transaction->destination_no.')', 
                        'starting_balance'  => intval($dealer_account->account_balance), 
                        'credit'            => intval($transaction->dealer_fee),
                        'ending_balance'    => intval($dealer_account->account_balance + $transaction->dealer_fee)
                    );

                    $mutation_id = $this->eva_corporate_mutation->insert($data);
                }

                if(intval($transaction->dekape_fee) > 0)
                {
                    //MUTASI fee ke dealer
                    $dealer_account = $this->eva_corporate->find(6);

                    $data = array(
                        'account_id'        => '6', 
                        'account_eva'       => 'P0001DEKAPE', 
                        'account_role'      => 'dekape', 
                        'account_role_id'   => '3', 
                        'transaction_type'  => 'F', 
                        'transaction_ref'   => $transaction->trx_code, 
                        'transaction_code'  => $transaction->service_code, 
                        'purchase_ref'      => $transaction->ref_code, 
                        'remarks'           => 'Transaction fee '.$service_code->remarks.' ('.$transaction->destination_no.')', 
                        'starting_balance'  => intval($dealer_account->account_balance), 
                        'credit'            => intval($transaction->dekape_fee),
                        'ending_balance'    => intval($dealer_account->account_balance + $transaction->dekape_fee)
                    );

                    $mutation_id = $this->eva_corporate_mutation->insert($data);
                }
            }
        }
        else if($status == 'rejected')
        {
            $customer               = $this->customer->find($transaction->cus_id);
            $eva_customer           = $this->eva_customer->find_by(array('account_user' => $transaction->cus_id));
            // // echo $this->db->last_query();die;

            // //MUTASI selling 
            $data = array(
                'account_id'        => $eva_customer->id, 
                'account_eva'       => $eva_customer->account_no, 
                'account_user'      => $customer->id, 
                'transaction_ref'   => $transaction->trx_code, 
                'transaction_code'  => $transaction->service_code, 
                'purchase_ref'      => $transaction->ref_code, 
                'remarks'           => 'Reversal '.$transaction->remarks, 
                'starting_balance'  => intval($eva_customer->account_balance), 
                'credit'            => intval($transaction->selling_price), 
                'ending_balance'    => intval(($eva_customer->account_balance + $transaction->selling_price))
            );

            $mutation_id = $this->eva_customer_mutation->insert($data);

            // //MUTASI fee ke biller
            // $biller_eva     = $this->biller->find($transaction->biller_id)->eva;
            // $biller_account = $this->eva_corporate->find_by(array('account_no' => $biller_eva));

            // $data = array(
            //     'account_id'        => $biller_account->id, 
            //     'account_eva'       => $biller_eva, 
            //     'account_role'      => 'biller', 
            //     'account_role_id'   => $biller_account->account_user, 
            //     'transaction_ref'   => $transaction->trx_code, 
            //     'transaction_code'  => $transaction->service_code, 
            //     'purchase_ref'      => $transaction->ref_code, 
            //     'remarks'           => 'Refund rejected transaction', 
            //     'starting_balance'  => intval($biller_account->account_balance), 
            //     'debit'             => intval($transaction->biller_fee),
            //     'ending_balance'    => intval($biller_account->account_balance - $transaction->biller_fee)
            // );

            // $mutation_id = $this->eva_corporate_mutation->insert($data);

            // //MUTASI fee ke dealer
            // $dealer_eva     = $this->dealer->find($transaction->dealer_id)->eva;
            // $dealer_account = $this->eva_corporate->find_by(array('account_no' => $dealer_eva));

            // $data = array(
            //     'account_id'        => $dealer_account->id, 
            //     'account_eva'       => $dealer_eva, 
            //     'account_role'      => 'dealer', 
            //     'account_role_id'   => $dealer_account->account_user, 
            //     'transaction_ref'   => $transaction->trx_code, 
            //     'transaction_code'  => $transaction->service_code, 
            //     'purchase_ref'      => $transaction->ref_code, 
            //     'remarks'           => 'Refund rejected transaction', 
            //     'starting_balance'  => intval($dealer_account->account_balance), 
            //     'debit'             => intval($transaction->dealer_fee),
            //     'ending_balance'    => intval($dealer_account->account_balance - $transaction->dealer_fee)
            // );

            // $mutation_id = $this->eva_corporate_mutation->insert($data);
        }

        if($status == 'approved')
        {

            $message    = 'Pembayaran '.$service_code->remarks.' dengan invoice '.$transaction->trx_code.' berhasil di proses. Silahkan akses Riwayat untuk informasi lebih lanjut.';
        }
        else
        {
            $message    = 'Pembayaran '.$service_code->remarks.' dengan invoice '.$transaction->trx_code.' gagal di proses. Silahkan akses Riwayat untuk informasi lebih lanjut.';
        }
        
        $title   = 'OKBABE+';
        $session = $this->customer_session->order_by('id', 'desc');
        $session = $this->customer_session->find_by(array('cus_id' => $transaction->cus_id));

        $fcm_id = Array();
        array_push($fcm_id, $session->cus_fcm_id);
        

        $this->push_notification($fcm_id, $title, $message, '', '');

        if($update)
        {
            // redirect(site_url('transactions/pending'), 'refresh');
        }
    }

    private function push_notification($gcm_ids, $title, $msg, $action='feed', $id='')
    {
        $url     = 'https://fcm.googleapis.com/fcm/send';
        $message = array("title" => $title, "body" => $msg, "click_action" => "transaction");
        $fields  = array(
              'registration_ids'  => $gcm_ids,
              'notification'      => $message
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