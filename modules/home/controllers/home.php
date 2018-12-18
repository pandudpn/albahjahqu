<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class home extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('references/reference_model', 'reference');
        setlocale(LC_TIME, 'id_ID');

        $this->check_login();
    }

    public function index(){
        // var_dump($this->session->userdata('user'));die;
    	$this->template->build('index');
    }

    public function trx_chart()
    {
         $query = $this->db->query("SELECT COUNT(id) as total, SUBSTRING(created_on, 1, 10) as date,
                                    SUM(CASE
                                        WHEN status = 'payment' THEN 1
                                        WHEN status = 'approved' THEN 1
                                        WHEN status = 'inquiry' THEN 0
                                        ELSE 0
                                    END) as status_success,
                                    SUM(CASE
                                        WHEN status = 'payment' THEN 0
                                        WHEN status = 'approved' THEN 0
                                        WHEN status = 'inquiry' THEN 0
                                        ELSE 1
                                    END) as status_failed
                                    FROM transactions 
                                    WHERE SUBSTRING(created_on, 1, 7) = '".date('Y-m')."'
                                    AND LEFT(service_code, 3) <> 'TOP'
                                    GROUP BY SUBSTRING(created_on, 1, 10)")->result();

        $cols = array(
            array(
                'id' => '',
                'label' => 'Tanggal',
                'pattern' => '',
                'type' => 'string'
            ),
            array(
                'id' => '',
                'label' => 'Transaksi Berhasil',
                'pattern' => '',
                'type' => 'number'
            ),
            array(
                'id' => '',
                'label' => 'Transaksi Gagal',
                'pattern' => '',
                'type' => 'number'
        ));

        $rows = array();

        foreach ($query as $key => $v) {
            $value   = array();
            $value[] = array('v' => date("j F", strtotime($v->date)));
            $value[] = array('v' => intval($v->status_success));
            $value[] = array('v' => intval($v->status_failed));

            $rows[]  = array('c' => $value); 
        }

        $json = array(
            'cols' => $cols,
            'rows' => $rows
        );

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    public function trx_total_chart()
    {
         $query = $this->db->query("SELECT COUNT(id) as total, SUBSTRING(created_on, 1, 10) as date,
                                    SUM(CASE
                                        WHEN status = 'payment' THEN selling_price
                                        WHEN status = 'approved' THEN selling_price
                                        WHEN status = 'inquiry' THEN 0
                                        ELSE 0
                                    END) as status_success,
                                    SUM(CASE
                                        WHEN status = 'payment' THEN 0
                                        WHEN status = 'approved' THEN 0
                                        WHEN status = 'inquiry' THEN 0
                                        ELSE selling_price
                                    END) as status_failed
                                    FROM transactions 
                                    WHERE SUBSTRING(created_on, 1, 7) = '".date('Y-m')."' 
                                    AND LEFT(service_code, 3) <> 'TOP'
                                    GROUP BY SUBSTRING(created_on, 1, 10)")->result();

        $cols = array(
            array(
                'id' => '',
                'label' => 'Tanggal',
                'pattern' => '',
                'type' => 'string'
            ),
            array(
                'id' => '',
                'label' => 'Transaksi Berhasil',
                'pattern' => '',
                'type' => 'number'
            ),
            array(
                'id' => '',
                'label' => 'Transaksi Gagal',
                'pattern' => '',
                'type' => 'number'
        ));

        $rows = array();

        foreach ($query as $key => $v) {
            $value   = array();
            $value[] = array('v' => date("j F", strtotime($v->date)));
            $value[] = array('v' => intval($v->status_success));
            $value[] = array('v' => intval($v->status_failed));

            $rows[]  = array('c' => $value); 
        }

        $json = array(
            'cols' => $cols,
            'rows' => $rows
        );

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    public function dealer_chart()
    {
         $query = $this->db->query("SELECT dealers.name AS dealer_name, SUM(selling_price) AS sales
                                    FROM dealers
                                    LEFT JOIN transactions ON transactions.dealer_id = dealers.id
                                    WHERE SUBSTRING(transactions.created_on, 1, 7) = '".date('Y-m')."' 
                                    AND (status = 'payment' OR status = 'approved')
                                    GROUP BY dealers.name")->result();
        $cols = array(
            array(
                'id' => '',
                'label' => 'Dealer',
                'pattern' => '',
                'type' => 'string'
            ),
            array(
                'id' => '',
                'label' => 'Sales',
                'pattern' => '',
                'type' => 'number'
        ));

        $rows = array();

        foreach ($query as $key => $v) {
            $value   = array();
            $value[] = array('v' => $v->dealer_name);
            $value[] = array('v' => intval($v->sales));

            $rows[]  = array('c' => $value); 
        }

        $json = array(
            'cols' => $cols,
            'rows' => $rows
        );

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    public function topup_chart()
    {
         $topup = $this->db->query("SELECT sum(selling_price) as topup FROM transactions
                                    WHERE LEFT(service_code, 3) = 'TOP' 
                                    AND (status = 'payment' OR status = 'approved')
                                    AND SUBSTRING(transactions.created_on, 1, 7) = '".date('Y-m')."'")->row()->topup;

         $sales = $this->db->query("SELECT sum(selling_price) as sales FROM transactions
                                    WHERE LEFT(service_code, 3) <> 'TOP' 
                                    AND (status = 'payment' OR status = 'approved')
                                    AND SUBSTRING(transactions.created_on, 1, 7) = '".date('Y-m')."'")->row()->sales;
        
        $cols = array(
            array(
                'id' => '',
                'label' => 'Keterangan',
                'pattern' => '',
                'type' => 'string'
            ),
            array(
                'id' => '',
                'label' => 'Jumlah',
                'pattern' => '',
                'type' => 'number'
        ));

        $rows = array();

        $value   = array();
        $value[] = array('v' => 'Topup');
        $value[] = array('v' => intval($topup));

        $rows[]  = array('c' => $value); 
        ///// ********* ///////

        $value   = array();
        $value[] = array('v' => 'Sales');
        $value[] = array('v' => intval($sales));

        $rows[]  = array('c' => $value); 

        $json = array(
            'cols' => $cols,
            'rows' => $rows
        );

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    public function product_sales_chart()
    {
        $query = $this->db->query("SELECT COUNT(transactions.id) as total, service_menus.name as menu,
                                    SUM(CASE
                                        WHEN status = 'payment' THEN selling_price
                                        WHEN status = 'approved' THEN selling_price
                                        WHEN status = 'inquiry' THEN 0
                                        ELSE 0
                                    END) as sales
                                    FROM transactions 
                                    JOIN service_menus ON service_menus.id = transactions.service_menu
                                    WHERE SUBSTRING(transactions.created_on, 1, 7) = '".date('Y-m')."' 
                                    AND LEFT(service_code, 3) <> 'TOP'
                                    GROUP BY service_menus.id")->result();

        $cols = array(
            array(
                'id' => '',
                'label' => 'Product',
                'pattern' => '',
                'type' => 'string'
            ),
            array(
                'id' => '',
                'label' => 'Total',
                'pattern' => '',
                'type' => 'number'
        ));

        $rows = array();

        foreach ($query as $key => $v) {
            $value   = array();
            $value[] = array('v' => $v->menu);
            $value[] = array('v' => intval($v->sales));

            $rows[]  = array('c' => $value); 
        }

        $json = array(
            'cols' => $cols,
            'rows' => $rows
        );

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    public function post(){
    	$this->template->build('post');
    }

    public function post_new(){
    	$this->template->build('post_new');
    }

    public function page(){
    	$this->template->build('page');
    }

    public function page_new(){
    	$this->template->build('page_new');
    }

    public function category(){
    	$this->template->build('category');
    }

}