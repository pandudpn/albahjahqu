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
        $this->load->model('customers/customer_session_model', 'customer_session');
        $this->load->model('references/ref_service_codes_model', 'ref_service_code');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        $status = $this->input->get('status');
        $outlet = $this->input->get('outlet');
        $type   = $this->input->get('type');
        
    	$this->template->set('alert', $this->session->flashdata('alert'))
                        ->set('from', $from)
                        ->set('to', $to)
                        ->set('status', $status)
                        ->set('outlet', $outlet)
                        ->set('type', $type)
    					->build('index');
    }

    public function edit($id)
    {
        if(!empty($this->input->post('ref_code')))
        {
            $data_status['ref_code']   = $this->input->post('ref_code');
        }
        
        if(!empty($this->input->post('token_code')))
        {
            $data_status['token_code'] = $this->input->post('token_code');
        }


        $update = $this->transaction->update($id, $data_status);

        redirect(site_url('transactions'), 'refresh');
    }

    public function changestatus($status, $id)
    {
        if($status == 'reapproved')
        {
            $status             = 'approved';
            $status_reapproved  = 'reapproved';
        }

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

        if($status_reapproved == 'reapproved')
        {
            if($this->session->userdata('user')->role != 'dekape')
            {
                die;
            }
            
            $data_log['remarks'] = 'Change status from rejected to reapproved';
        }

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

        if($status_reapproved == 'reapproved')
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
                'remarks'           => 'Re-Approved '.$transaction->remarks, 
                'starting_balance'  => intval($eva_customer->account_balance), 
                'debit'             => intval($transaction->selling_price), 
                'ending_balance'    => intval(($eva_customer->account_balance - $transaction->selling_price))
            );

            $mutation_id = $this->eva_customer_mutation->insert($data);
        }

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


            if($transaction->status == 'approved')
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
                            'transaction_ref'   => $transaction->trx_code, 
                            'transaction_code'  => $transaction->service_code, 
                            'purchase_ref'      => $transaction->ref_code, 
                            'remarks'           => 'Rollback Transaction fee '.$service_code->remarks.' ('.$transaction->destination_no.')', 
                            'starting_balance'  => intval($dealer_account->account_balance), 
                            'debit'             => intval($transaction->base_price),
                            'ending_balance'    => intval($dealer_account->account_balance - $transaction->base_price)
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
                        'transaction_ref'   => $transaction->trx_code, 
                        'transaction_code'  => $transaction->service_code, 
                        'purchase_ref'      => $transaction->ref_code, 
                        'remarks'           => 'Rollback Transaction fee '.$service_code->remarks.' ('.$transaction->destination_no.')', 
                        'starting_balance'  => intval($biller_account->account_balance), 
                        'debit'             => intval($transaction->biller_fee),
                        'ending_balance'    => intval($biller_account->account_balance - $transaction->biller_fee)
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
                        'transaction_ref'   => $transaction->trx_code, 
                        'transaction_code'  => $transaction->service_code, 
                        'purchase_ref'      => $transaction->ref_code, 
                        'remarks'           => 'Rollback Transaction fee '.$service_code->remarks.' ('.$transaction->destination_no.')', 
                        'starting_balance'  => intval($dealer_account->account_balance), 
                        'debit'             => intval($transaction->dealer_fee),
                        'ending_balance'    => intval($dealer_account->account_balance - $transaction->dealer_fee)
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
                        'remarks'           => 'Rollback Transaction fee '.$service_code->remarks.' ('.$transaction->destination_no.')', 
                        'starting_balance'  => intval($dealer_account->account_balance), 
                        'debit'             => intval($transaction->dekape_fee),
                        'ending_balance'    => intval($dealer_account->account_balance - $transaction->dekape_fee)
                    );

                    $mutation_id = $this->eva_corporate_mutation->insert($data);
                }
            }

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

        if($status == 'rejected')
        {
            $title   = 'OKBABE+';
            $session = $this->customer_session->order_by('id', 'desc');
            $session = $this->customer_session->find_by(array('cus_id' => $transaction->cus_id));
            $reason  = $this->input->post('reason');
            $message = 'Transaksi dengan kode '.$transaction->trx_code.' GAGAL dengan alasan : '. $reason;

            $fcm_id  = Array();
            array_push($fcm_id, $session->cus_fcm_id);

            $this->push_notification($fcm_id, $title, $message, 'popup', '');
        }
        else
        {
            $title   = 'OKBABE+';
            $session = $this->customer_session->order_by('id', 'desc');
            $session = $this->customer_session->find_by(array('cus_id' => $transaction->cus_id));

            $fcm_id = Array();
            array_push($fcm_id, $session->cus_fcm_id);

            $this->push_notification($fcm_id, $title, $message, 'transaction', '');
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

            $btn = '<div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Action <span class="caret"></span></button>
                        <div class="dropdown-menu">';

            if(($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') && ($l->provider != 'TSL' || $l->by > 0) || ($l->provider == 'TSL' && $l->prepaid == '02') )
            {
                // $btn .= '-';
            }
            elseif ($this->session->userdata('user')->role != 'viewer' )
            {
                $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert_edit(\''.site_url('transactions/edit/'.$l->id).'\')">edit</a>';

                if($l->status == 'rejected' || $l->status_provider == '5') 
                {
                    if($this->session->userdata('user')->role == 'dekape')
                    {
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert_approve(\''.site_url('transactions/changestatus/reapproved/'.$l->id).'\')">reapprove</a>';
                    }
                }

                if($l->status == 'approved') 
                {
                    if(($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') && ($l->provider != 'TSL' || $l->by > 0))
                    {
                        // $btn .= '';
                    }
                    else
                    {
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert_reject(\''.site_url('transactions/changestatus/rejected/'.$l->id).'\')">reject</a>';
                    }
                }
                else if($l->status == 'rejected')
                {
                    if(($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') && ($l->provider != 'TSL' || $l->by > 0))
                    {
                        // $btn .= '';
                    }
                    else
                    {
                        // $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert_approve(\''.site_url('transactions/changestatus/approved/'.$l->id).'\')">approve</a>';
                    }
                }
                else
                {
                    if(($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') && ($l->provider != 'TSL' || $l->by > 0))
                    {
                        // $btn .= '';
                    }
                    else
                    {
                        if($l->status != 'payment')
                        {
                            $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert_approve(\''.site_url('transactions/changestatus/approved/'.$l->id).'\')" >approve</a>';
                        }
                        
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert_reject(\''.site_url('transactions/changestatus/rejected/'.$l->id).'\')">reject</a>';
                    }
                }
            } else {
                $btn = $l->status
            }

            $btn .= '</div>
                    </div>';

            if($this->session->userdata('user')->app_id != 'com.dekape.okbabe' || $this->session->userdata('user')->role == 'viewer')
            {
                $btn = '-';
            }

            $row[] = $btn;

            if($l->status == 'payment')
            {
                $status = '<span class="btn btn-pill btn-success" style="font-size: medium; border-radius: 15px 15px 15px 15px;">'.$l->status.'</span>';
            }else if($l->status == 'reversal')
            {
                $status = '<span class="btn btn-pill btn-danger" style="font-size: medium; border-radius: 15px 15px 15px 15px;">'.$l->status.'</span>';
            }else if($l->status == 'approved')
            {
                $status = '<span class="btn btn-pill btn-primary" style="font-size: medium; border-radius: 15px 15px 15px 15px;">'.$l->status.'</span>';
            }else if($l->status == 'rejected')
            {
                $status = '<span class="btn btn-pill btn-warning" style="font-size: medium; border-radius: 15px 15px 15px 15px;">'.$l->status.'</span>';
            }else if($l->status == 'dispute')
            {
                $status = '<span class="btn btn-pill btn-secondary" style="font-size: medium; border-radius: 15px 15px 15px 15px;">'.$l->status.'</span>';
            }

            $row[] = $status;
            $row[] = $l->created_on;
            $row[] = $l->cus_phone;
            $row[] = $l->destination_no;
            $row[] = $l->destination_meter;
            $row[] = $l->remarks;
            $row[] = (empty($l->slot) ? ' - ' : $l->slot) .' / '. (empty($l->denom) ? ' - ' : $l->denom.'K');
            $row[] = (empty($l->ref_code) ? ' - ' : $l->ref_code) .' / '. (empty($l->token_code) ? ' - ' : $l->token_code);
            $row[] = 'Rp. '.number_format($l->selling_price);
            $row[] = $l->dealer_name;
            $row[] = $l->trx_code;

            $assignees  = $this->transaction_log->find_all_by(array('transaction_code' => $l->trx_code));

            $assignee   = '';
            $assignee   = '<ul style="margin: 5px; padding: 5px;">';
            foreach ($assignees as $a) 
            {
                $assignee .= '<li>'.$a->user_name.' <br>'.substr($a->remarks, 19, 40).'</li>';
            }

            $assignee .= '</ul>';
            $row[]     = $assignee;

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