<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class customer_balance_log_model extends MY_Model {

	protected $table         	= 'customer_balance_logs';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;
    protected $soft_deletes     = true;

    public function __construct()
    {
        parent::__construct();
    }
}