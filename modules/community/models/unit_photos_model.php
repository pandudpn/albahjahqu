<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class unit_photos_model extends MY_Model {

    protected $table            = 'unit_photos';
    protected $key              = 'id';
    protected $date_format      = 'datetime';

    protected $set_created      = true;

    public function __construct()
    {
        parent::__construct();
    }
    
}
