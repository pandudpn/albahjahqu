<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class geo_services_model extends MY_Model {

    protected $province         = 'geo_pronvices';
    protected $city             = 'geo_cities';
    protected $district         = 'geo_districts';
    protected $village          = 'geo_villages';
    
    protected $key              = 'id';
    protected $date_format      = 'datetime';
    protected $set_created      = true;
    protected $soft_deletes     = true;

    public function __construct()
    {
        parent::__construct();
        $this->geo  = $this->load->database('geo', TRUE);
    }

    public function find_all_by($table, $data=array()){
        $this->geo->from($table);

        if(!empty($data)){
            foreach($data AS $key => $val){
                $this->geo->where($key, $val);
            }
        }

        $this->geo->order_by('name', 'asc');
        $query  = $this->geo->get();

        return $query->result();
    }
    
}