<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class customers extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('customers/kyc_model', 'kyc');
        $this->load->model('customers/customer_model', 'customer');
        $this->load->model('customers/customer_session_model', 'customer_session');
        $this->load->model('user/eva_customer_model', 'eva_customer');
        $this->load->model('references/geo_cities_model', 'geo_city');
        $this->load->model('references/geo_provinces_model', 'geo_province');
        $this->load->model('references/geo_districts_model', 'geo_district');
        $this->load->model('references/geo_villages_model', 'geo_village');
        $this->load->model('dealers/dealer_model', 'dealer');
        $this->load->model('dealers/dealer_cluster_model', 'dealer_cluster');
        $this->load->model('transactions/transaction_model', 'transaction');

        $this->load->helper('text');

        $this->check_login();
    }

    public function index()
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('from', $from)
            ->set('to', $to)
    		->build('index');
    }

    public function download()
    {
        $this->customer->download();
    }

    public function profile($id)
    {
        if($this->input->post())
        {
            $data = array(
                'outlet_number' => $this->input->post('outlet_number'),
                'outlet_name' => $this->input->post('outlet_name'),
                'level'     => $this->input->post('level'),
                'name'     => $this->input->post('name'),
                'email'     => $this->input->post('email'),
                'identity'  => $this->input->post('identity')
            );

            $update = $this->customer->update($id, $data);
            $update = $this->eva_customer->update_where('account_user', $id, array('account_holder' => $data['name']));

            $data_kyc = array(
                'cus_name' => $this->input->post('name'),
                'cus_ktp'  => $this->input->post('identity')
            );

            $update = $this->kyc->update_where('cus_id', $id, $data_kyc);

            //password update

            $pin        = $this->input->post('pin');
            $password   = $this->input->post('password');

            if(!empty($pin))
            {
                $update = $this->customer->update($id, array('pin' => md5($pin)));
            }

            if(!empty($password))
            {
                $pass   = sha1($password.$this->config->item('password_salt_customer'));
                $eva    = $this->eva_customer->find_by(array('account_user' => $id));
                $update = $this->eva_customer->update($eva->id, array('account_password' => $pass));
            }

            if($update)
            {
                redirect(site_url('customers'), 'refresh');
            }
        }

        $data = $this->customer->find($id);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Edit Profile for '.$data->name)
            ->set('data', $data)
            ->build('profile');
    }

    public function password($id)
    {
        if($this->input->post())
        {
            $pin        = $this->input->post('pin');
            $password   = $this->input->post('password');

            if(!empty($pin))
            {
                $update = $this->customer->update($id, array('pin' => md5($pin)));
            }

            if(!empty($password))
            {
                $pass   = sha1($this->config->item('password_salt_customer').$password);
                $eva    = $this->eva_customer->find_by(array('account_user' => $id));
                $update = $this->eva_customer->update($eva->id, array('account_password' => $pass));
            }

            redirect(site_url('customers'), 'refresh');
        }

        $data = $this->customer->find($id);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Edit Password / Pin for '.$data->name)
            ->set('data', $data)
            ->build('password');
    }

    public function geography($id)
    {
        if($this->input->post())
        {
            $data = array(
                'village'       => $this->input->post('village'),
                'district'      => $this->input->post('district'),
                'city'          => $this->input->post('city'),
                'dealer_id'     => $this->input->post('dealer_id'),
                'dealer_name'   => $this->dealer->find($this->input->post('dealer_id'))->name,
                'dealership'    => $this->input->post('dealership')
            );

            $cluster = $this->input->post('cluster');

            if(!empty($cluster))
            {
                $data['cluster'] = $cluster;
            }

            $update = $this->customer->update($id, $data);

            if($update)
            {
                redirect(site_url('customers'), 'refresh');
            }
        }

        $data = $this->customer->find($id);
        $city = $this->geo_city->find($data->city);

        $provinces  = $this->geo_province->find_all();
        $cities     = $this->geo_city->find_all_by(array('province_id' => $city->province_id));
        $districts  = $this->geo_district->find_all_by(array('city_id' => $data->city));
        $villages   = $this->geo_village->find_all_by(array('district_id' => $data->district));
        $dealers    = $this->dealer->find_all();
        $dealer_clusters    = $this->dealer_cluster->find_all_by(array('dealer_id' => $data->dealer_id));

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Edit Geography for '.$data->name)
            ->set('data', $data)
            ->set('city', $city)
            ->set('provinces', $provinces)
            ->set('cities', $cities)
            ->set('districts', $districts)
            ->set('villages', $villages)
            ->set('dealers', $dealers)
            ->set('dealer_clusters', $dealer_clusters)
            ->build('dealer');
    }

    public function status($status, $id)
    {
        $arr = array();
        if($status == 'active'){
            $arr = array('account_status' => 'active');
        }else if($status == 'suspend'){
            $arr = array('account_status' => 'suspended');
        }else if($status == 'block'){
            $arr = array('account_status' => 'blocked');
        }

        $arr['verified']    = 'phone';
        $update             = $this->customer->update($id, $arr);

        $customer           = $this->customer->find($id);
        $token_session      = sha1($customer->id.floor(time()/1000).$customer->phone);
        $expired_on         = date("Y-m-d H:i:s", strtotime('+1 Months', time()));
        $expired_on         = date("Y-m-d H:i:s", strtotime('+15 Days', strtotime($expired_on)));

        $customer_session_data = array(
            'cus_lat'           => '-6.2428442',
            'cus_long'          => '106.8520604',
            'cus_session_token' => $token_session,
            'expired_on'        => $expired_on,
        );

        $update = $this->customer_session->update_where('cus_id', $customer->id, $customer_session_data);

        redirect(site_url('customers'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->customer->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $l->name;
            $row[] = $l->phone;
            $row[] = $l->email;
            $row[] = $l->level;

            if(empty($l->outlet_name))
            {
                $outlet_name = ' NULL ';
            }
            else
            {
                $outlet_name = $l->outlet_name;
            }

            if(empty($l->outlet_number))
            {
                $outlet_number = ' NULL ';
            }
            else
            {
                $outlet_number = $l->outlet_number;
            }

            $row[] = $outlet_name . ' / '.$outlet_number;
            $row[] = $l->dealer_name;
            // $row[] = 'Rp.' .number_format($this->eva_customer->find_by(array('account_user' => $l->id))->account_balance);
            $row[] = $l->account_status;
            $row[] = $l->kyc_status;
            $row[] = $l->referral_code;
            
            $last_trx = $this->transaction->order_by('id', 'desc');
            $last_trx = $this->transaction->find_by(array('cus_id' => $l->id, 'status <>' => 'inquiry'))->created_on;
            
            $row[] = $last_trx;
            $row[] = $l->created_on;

            // $btn   = '<a href="'.site_url('menu/edit/'.$l->id).'" class="btn btn-success btn-sm">
            //             <i class="fa fa-pencil"></i>
            //           </a> &nbsp;';

            // $btn  .= '<a href="javascript:void(0)" onclick="alert_delete(\''.site_url('menu/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
            //             <i class="fa fa-trash"></i>
            //           </a>';

            $btn = '<div class="btn-group cst-button">
                        <button type="button" class="btn btn-info dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Action <span class="caret"></span></button>
                        <div class="dropdown-menu">';
            
            // if($l->account_status != 'active'){
                if($this->session->userdata('user')->role != 'dealer_ops') {
                    $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert(\''.site_url('customers/status/active/'.$l->id).'\')">Active</a>
                            <div class="dropdown-divider"></div>';
                }
            // }

            if($l->account_status != 'suspended'){
                if($this->session->userdata('user')->role != 'dealer_ops') {
                    $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert(\''.site_url('customers/status/suspend/'.$l->id).'\')">Suspend</a>
                                <div class="dropdown-divider"></div>';
                }
            }

            if($l->account_status != 'blocked'){
                if($this->session->userdata('user')->role != 'dealer_ops') {
                    $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="alert(\''.site_url('customers/status/block/'.$l->id).'\')">Block!</a>
                                <div class="dropdown-divider"></div>';
                }
            }

            $btn .= '<a class="dropdown-item" href="'.site_url('customers/geography/'.$l->id).'" >Edit Geography & Dealer</a>
                                <div class="dropdown-divider"></div>';

            $btn .= '<a class="dropdown-item" href="'.site_url('customers/profile/'.$l->id).'" >Edit Profile</a>
                                <div class="dropdown-divider"></div>';

            // $btn .= '<a class="dropdown-item" href="'.site_url('customers/password/'.$l->id).'" >Edit Password / Pin</a>
            //                     <div class="dropdown-divider"></div>';

            $btn .= '<a class="dropdown-item" href="'.site_url('customers/mutation/'.$l->id).'" >View Mutation</a>
                                <div class="dropdown-divider"></div>';

            if($this->session->userdata('user')->role == 'dekape') {
                $btn .= '<a class="dropdown-item" href="'.site_url('customers/balance/'.$l->id).'" >Balance Manipulation</a>
                                <div class="dropdown-divider"></div>';
            }

            $btn .= '</div>
                    </div>';

            $row[]  = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->customer->count_all(),
            "recordsFiltered"   => $this->customer->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function lists_city($id)
    {
        $data  = $this->geo_city->find_all_by(array('province_id' => $id));

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function lists_district($id)
    {
        $data  = $this->geo_district->find_all_by(array('city_id' => $id));
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function lists_village($id)
    {
        $data  = $this->geo_village->find_all_by(array('district_id' => $id));
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function lists_cluster($id)
    {
        $data  = $this->dealer_cluster->find_all_by(array('dealer_id' => $id));
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}