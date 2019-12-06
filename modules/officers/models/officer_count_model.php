<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class officer_count_model extends MY_Model {

    protected $table         = 'wakaf_officer_counts';

    protected $key           = 'id';
    protected $date_format   = 'datetime';

    public function __construct()
    {
        parent::__construct();
    }

}