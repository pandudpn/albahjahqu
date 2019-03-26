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
    	$this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('graph');
    }

    public function revenue()
    {
        $from   = $this->input->get('from');
        $to 	= $this->input->get('to');

        if(empty($from))
        {
        	$from = date('Y-m-d', strtotime('-30 days'));
        }

        if(empty($to))
        {
        	$to = date('Y-m-d');
        }

        $query = $this->db->query("
        	SELECT SUM(dekape_fee) as revenue, LEFT(created_on, 10) as date
        	FROM `transactions` 
        	WHERE (status = 'payment' OR status = 'approved') 
        	AND status_provider = '00'
        	AND (created_on >= '".$from." 00:00' AND created_on <= '".$to." 23:59')
        	GROUP BY date
        	ORDER BY date ASC
        ")->result();

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

        foreach ($query as $key => $v) {
            $value   = array();
            $value[] = array('v' => date('j M, y', strtotime( $v->date )));
            $value[] = array('v' => intval($v->revenue));

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

        if(empty($from))
        {
        	$from = date('Y-m-d', strtotime('-30 days'));
        }

        if(empty($to))
        {
        	$to = date('Y-m-d');
        }

        $query = $this->db->query("
        	SELECT SUM(dekape_fee) as revenue, stats_title
        	FROM `transactions` 
        	WHERE (status = 'payment' OR status = 'approved') 
        	AND status_provider = '00'
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