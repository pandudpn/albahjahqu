<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class user_share_model extends MY_Model {

	protected $table         = 'user_sharings';
    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;

    public function __construct()
    {
        parent::__construct();
    }
}