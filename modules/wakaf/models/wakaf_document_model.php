<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class wakaf_document_model extends MY_Model {

    protected $table         = 'wakaf_documents';

    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $soft_deletes  = TRUE;

    protected $column_order  = array(null, 'name'); //set column field database for datatable orderable
    protected $column_search = array('name'); //set column field database for datatable searchable 
    protected $order         = array('created_on' => 'DESC'); // default order 

    public function __construct()
    {
        parent::__construct();
    }
}