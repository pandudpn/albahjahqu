<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class home extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('reporting/zakat_model', 'zakat');
        setlocale(LC_TIME, 'id_ID');

        $this->apps_name = $this->session->userdata('user')->apps_name;
        $this->apps      = $this->session->userdata('user')->alias;

        $this->check_login();
    }

    public function index(){
    	$this->template->build('index');
    }

    public function chart_zakat(){
        $now    = date('d');

        $array_date = array();
        for($i = 1; $i <= $now; $i++){
            $array_date[]   = $i;
        }

        $zakat  = $this->zakat->trx_zakat($this->apps);
        $data   = array();

        $cols = [
            [
                'id'        => '',
                'label'     => 'Tanggal',
                'pattern'   => '',
                'type'      => 'string'
            ],
            [
                'id'        => '',
                'label'     => 'Total',
                'pattern'   => '',
                'type'      => 'number'
            ]
        ];

        foreach($array_date AS $key => $val){
            $row    = array();
            $arr    = array();
            $cost   = 0;
            $array  = array();

            $row[]  = [
                'v' => $val.' '.date('M')
            ];

            foreach($zakat AS $z){
                $dt = date('j', strtotime($z->created_on));
                if($dt == $val){
                    $cost   = $z->total_credit;
                }
            }

            $array['v']    = $cost;

            $row[]  = $array;

            $data[] = ['c' => $row];
        }

        $json = array(
            'cols' => $cols,
            'rows' => $data
        );

        // print "<pre>";
        // print_r($json);
        // print "</pre>";
        // die;

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    // public function chart_zakat_group(){
    //     $now    = date('d');

    //     $array_date = array();
    //     for($i = 1; $i <= $now; $i++){
    //         $array_date[]   = $i;
    //     }

    //     $zakat  = $this->zakat->trx_zakat($this->apps);
    //     $yays   = $this->zakat->get_all($this->apps);
    //     $data   = array();
    //     $cols   = array();

    //     $cols[] = [
    //         'id'        => '',
    //         'label'     => 'Tanggal',
    //         'pattern'   => '',
    //         'type'      => 'string'
    //     ];
    //     foreach($yays AS $ya){
    //         $cols[] = [
    //             'id'        => '',
    //             'label'     => $ya->account_holder,
    //             'pattern'   => '',
    //             'type'      => 'number'
    //         ];
    //     }

    //     foreach($array_date AS $key => $val){
    //         $row    = array();
    //         $arr    = array();
    //         $cost   = 0;

    //         $row[]  = [
    //             'v' => $val.' '.date('M')
    //         ];

    //         foreach($yays AS $y){
    //             $cost   = 0;
    //             $array  = array();

    //             foreach($zakat AS $z){
    //                 $dt = date('j', strtotime($z->created_on));

    //                 if($z->account_id == $y->id){
    //                     if($dt == $val){
    //                         $cost   = $z->total_credit;
    //                     }
    //                 }
    //             }

    //             $array['name']    = $y->account_holder;
    //             $array['cost']    = $cost;
    //             $arr[]      = $array;
    //         }

    //         $i  = 0;
    //         foreach($yays AS $a){
    //             $row[]  = [
    //                 'v' => $arr[$i++]['cost']
    //             ];
    //         }
    //         $data[] = ['c' => $row];
    //     }

    //     $json = array(
    //         'cols' => $cols,
    //         'rows' => $data
    //     );

    //     header('Content-Type: application/json');
    //     echo json_encode($json);
    // }
}