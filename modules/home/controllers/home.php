<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class home extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('references/reference_model', 'reference');
        $this->load->helper('text');

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
            $value[] = array('v' => $v->status_success);
            $value[] = array('v' => $v->status_failed);

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
            $value[] = array('v' => $v->status_success);
            $value[] = array('v' => $v->status_failed);

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