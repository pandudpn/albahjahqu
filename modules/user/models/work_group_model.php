<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class work_group_model extends MY_Model {

	protected $table        = 'work_groups';
    protected $key          = 'id';
    protected $date_format  = 'datetime';
    protected $set_created  = true;
    protected $soft_deleted = true;

    public function __construct()
    {
        parent::__construct();
    }

}
