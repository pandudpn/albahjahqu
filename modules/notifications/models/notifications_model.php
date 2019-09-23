<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class notifications_model extends MY_Model {

    protected $table         = 'notifications';

    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;
    protected $soft_deletes  = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($where=array()){
        if(!empty($where)){
            foreach($where AS $key => $val){
                $this->db->where($key, $val);
            }
        }

        $this->db->order_by('created_on', 'desc');

        $query  = $this->db->get($this->table);
        return $query->result();
    }
}