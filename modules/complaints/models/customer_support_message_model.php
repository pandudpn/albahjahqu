<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class customer_support_message_model extends MY_Model {

	protected $table         	= 'customer_support_messages';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;
    protected $soft_deletes     = true;

    public function __construct()
    {
        parent::__construct();
    }
}