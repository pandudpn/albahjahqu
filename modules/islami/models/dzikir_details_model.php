<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class dzikir_details_model extends MY_Model {

    protected $table         = 'dzikir_details';
    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;
    protected $soft_deletes  = true;

    protected $column_order  = array(null, 'title'); //set column field database for datatable orderable
    protected $column_search = array('title', 'content_dzikir'); //set column field database for datatable searchable 
    protected $order         = array('created_on' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }
}