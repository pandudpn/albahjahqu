<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class reference_model extends MY_Model {

	protected $table        = 'reference';
    protected $key          = 'id';
    protected $date_format  = 'datetime';
    protected $set_created  = true;
    protected $soft_deleted = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all(){
        
        $this->db->select('name, alias');
        $this->db->from($this->table);
        $this->db->where('deleted', '0');
        
        return $this->db->get()->result();
    }

}
