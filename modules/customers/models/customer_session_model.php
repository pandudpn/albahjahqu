<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class customer_session_model extends MY_Model {

	protected $table         	= 'customer_sessions';
	protected $table_customer   = 'customers';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;
    protected $soft_deletes     = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function count_all_users($dealer_id, $level)
    {
    	$this->db->from($this->table_customer);
    	$this->db->where('customers.deleted', '0');

    	if(!empty($dealer_id))
    	{
    		$this->db->where('customers.dealer_id', $dealer_id);
    	}

        if($level == 'outlet')
        {
            $this->db->where('customers.level', 'outlet');
        }

    	return $this->db->count_all_results();
    	
    }

    public function get_all_fcm_users($offset, $limit, $dealer_id, $level)
    {
    	$this->db->select('customer_sessions.cus_fcm_id');
    	$this->db->from($this->table_customer);
    	$this->db->join('customer_sessions', 'customers.id = customer_sessions.cus_id');

    	$this->db->where('customer_sessions.deleted', '0');
    	$this->db->where('customers.deleted', '0');

    	if(!empty($dealer_id))
    	{
    		$this->db->where('customers.dealer_id', $dealer_id);
    	}

        if($level == 'outlet')
        {
            $this->db->where('customers.level', 'outlet');
        }
		
		$this->db->limit($limit);
		$this->db->offset($offset);

		return $this->db->get()->result();
    }
}