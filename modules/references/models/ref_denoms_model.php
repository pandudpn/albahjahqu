<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class ref_denoms_model extends MY_Model {

	protected $table        = 'ref_denoms';
    protected $key          = 'id';
    protected $date_format  = 'datetime';
    protected $set_created  = true;
    protected $soft_deleted = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all(){
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('deleted', '0');
        
        
        return $this->db->get()->result();
    }

}
