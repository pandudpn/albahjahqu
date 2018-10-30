<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class notification_model extends MY_Model {

    protected $table        = 'gcm_users';
    protected $key          = 'id';
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';
    protected $set_created  = true;
    protected $set_modified = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_user_devices($device_id)
    {
        return $this->db->select('gcm_regid')
                    ->from($this->table)
                    ->where('device_id',$device_id)
                    ->where('deleted','0')
                    ->get()
                    ->result();
    }

    public function get_all_gcm_users($offset='0', $limit='0')
    {
        if($limit=='0'){
            return $this->db->select('device_id, device_type, gcm_regid')
                    ->from($this->table)
                    ->where('deleted','0')
                    ->order_by('modified_on', 'desc')
                    ->get()
                    ->result();
        }
        else{
            return $this->db->select('device_id, device_type, gcm_regid')
                    ->from($this->table)
                    ->where('deleted','0')
                    ->limit($limit ,$offset)
                    ->order_by('modified_on', 'desc')
                    ->get()
                    ->result();
        }
    }

    public function count_all_gcm_users()
    {
        return $this->db->select("count(*) as numrows")
                        ->from($this->table)
                        ->where('deleted','0')
                        ->get()
                        ->row()
                        ->numrows;
    }

}
