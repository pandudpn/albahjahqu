<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class officer_notif_model extends MY_Model {

    protected $table         = 'officer_notifications';

    protected $key           = 'id';
    protected $date_format   = 'datetime';

    public function __construct()
    {
        parent::__construct();
    }

}