<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class notification_read_model extends MY_Model {

    protected $table            = 'notification_reads';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;

    public function __construct()
    {
        parent::__construct();
    }

}