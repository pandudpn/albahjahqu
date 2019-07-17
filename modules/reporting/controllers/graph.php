<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class graph extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->check_login();

        $this->load->model('reporting/reporting_model', 'reporting');
        $this->load->model('references/dealers_model', 'dealer');
    }

    public function index()
    {  
        $admin = $this->session->userdata('user');
        if($admin->role == 'dekape'){
            $dealer = $this->dealer->get_all();
        } else {
            $dealer = $this->dealer->find_all_by(array('id'=>$admin->dealer_id));
        }
    	$this->template
            ->set('dealers', $dealer)
            ->set('alert', $this->session->flashdata('alert'))
    		->build('graph');
    }

    public function revenue()
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        $option = $this->input->get('option');
        $dealer = $this->input->get('revenue');

        $admin = $this->session->userdata('user');
        if($admin->role != 'dekape' && $dealer != $admin->dealer_id){
            $json = array(
                'cols' => array(),
                'rows' => array(),
                'total' => 0
            );

            header('Content-Type: application/json');
            echo json_encode($json); exit;
        }

        if(empty($from))
        {
        	$from = date('Y-m-d', strtotime('-30 days'));
        }

        if(empty($to))
        {
        	$to = date('Y-m-d');
        }

        if($option == 'daily')
        {
            $label = ', LEFT(created_on, 10) as label';
            $group = 'label';
        }
        else if($option == 'weekly')
        {
            $label = ', WEEK(created_on) as label';
            $group = 'label';
        }
        else if($option == 'monthly')
        {
            $label = ', MONTH(created_on) as label';
            $group = 'label';
        }

        $for_dealer = '';
        if($admin->role == 'dekape' && !empty($dealer)) 
            $for_dealer = 'AND dealer_id = "'.$dealer.'"';
        else if($admin->role == 'dekape' && !empty($admin->dealer_id))
            $for_dealer = 'AND dealer_id = "'.$$admin->dealer_id.'"';

        $for_revenue = 'dekape_fee';
        if($admin->role != 'dekape') $for_revenue = 'dealer_fee';

        $query = $this->db->query("
        	SELECT 
                SUM(CASE
                    WHEN dealer_id = 1 THEN (dealer_fee + dekape_fee)
                    ELSE ".$for_revenue."
                END) as revenue
            ".$label.", COUNT(id) AS count,YEAR(created_on) as year
        	FROM `transactions` 
        	WHERE (status = 'payment' OR status = 'approved') 
        	AND status_provider = '00' 
            ".$for_dealer."
        	AND (created_on >= '".$from." 00:00' AND created_on <= '".$to." 23:59')
        	GROUP BY ".$group.", year
        	ORDER BY ".$group." ASC
        ")->result(); //echo json_encode($query); die;
        // echo $this->db->last_query();die;

        $cols = array(
            array(
                'id' => '',
                'label' => 'Tanggal',
                'pattern' => '',
                'type' => 'string'
            ),
            array(
                'id' => '',
                'label' => 'Revenue',
                'pattern' => '',
                'type' => 'number'
            )
        );

        $rows 	= array();
        $total 	= 0;

        foreach ($query as $key => $v) 
        {
            $value   = array();

            if($option == 'daily')
            {
                $value[] = array('v' => date('j M, y', strtotime( $v->label )));
            }
            else if($option == 'weekly')
            {
                $value[] = array('v' => 'Week '.($v->label + 1).', '.$v->year);
            }
            else if($option == 'monthly')
            {
                $value[] = array('v' => date('M, y', strtotime( $v->year.'-'.$v->label.'-01' )));
            }

            $value[] = array('v' => intval($v->revenue),'f' => number_format($v->revenue).' ('.$v->count.' Transaksi)');
            $rows[]  = array('c' => $value); 
            $total 	 = $total + intval($v->revenue);
        }

        $json = array(
            'cols' => $cols,
            'rows' => $rows,
            'total' => $total
        );

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    public function revenue_product()
    {
        $from   = $this->input->get('from');
        $to 	= $this->input->get('to');
        $dealer = $this->input->get('revenue_product');

        $admin = $this->session->userdata('user');
        if($admin->role != 'dekape' && $dealer != $admin->dealer_id){
            $json = array(
                'cols' => array(),
                'rows' => array(),
                'total' => 0
            );

            header('Content-Type: application/json');
            echo json_encode($json); exit;
        }

        if(empty($from))
        {
        	$from = date('Y-m-d', strtotime('-30 days'));
        }

        if(empty($to))
        {
        	$to = date('Y-m-d');
        }

        $for_dealer = '';
        if($admin->role == 'dekape' && !empty($dealer)) 
            $for_dealer = 'AND dealer_id = "'.$dealer.'"';
        else if($admin->role == 'dekape' && !empty($admin->dealer_id))
            $for_dealer = 'AND dealer_id = "'.$$admin->dealer_id.'"';

        $for_revenue = 'dekape_fee';
        if($admin->role != 'dekape') $for_revenue = 'dealer_fee';
        $query = $this->db->query("
        	SELECT 
                SUM(CASE
                    WHEN dealer_id = 1 THEN (dealer_fee + dekape_fee)
                    ELSE ".$for_revenue."
                END) as revenue,
            stats_title
        	FROM `transactions` 
        	WHERE (status = 'payment' OR status = 'approved') 
        	AND status_provider = '00' 
            ".$for_dealer."
        	AND stats_title IS NOT NULL
        	AND (created_on >= '".$from." 00:00' AND created_on <= '".$to." 23:59')
        	GROUP BY stats_title
        ")->result();

        $cols = array(
            array(
                'id' => '',
                'label' => 'Product',
                'pattern' => '',
                'type' => 'string'
            ),
            array(
                'id' => '',
                'label' => 'Revenue',
                'pattern' => '',
                'type' => 'number'
            )
        );

        $rows 	= array();
        $total 	= 0;

        foreach ($query as $key => $v) {
            $value   = array();
            $value[] = array('v' => $v->stats_title);

            if($v->revenue < 0)
            {
                $value[] = array('v' => 0);
            }
            else
            {
                $value[] = array('v' => intval($v->revenue));
            }

            $rows[]  = array('c' => $value); 
            $total 	 = $total + intval($v->revenue);
        }

        $json = array(
            'cols' => $cols,
            'rows' => $rows
        );

        header('Content-Type: application/json');
        echo json_encode($json);
    }
}
