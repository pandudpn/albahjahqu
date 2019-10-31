<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class customer_notification_model extends MY_Model {

	protected $table         	= 'customer_notifications';
	protected $table_customer   = 'customers';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;
    protected $soft_deletes     = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function count_all_users($dealer_id)
    {
        $transaction    = $this->load->database('transactions', TRUE);

    	$transaction->from($this->table_customer);
    	$transaction->where('customers.deleted', '0');

    	if(!empty($dealer_id))
    	{
    		$transaction->where('customers.dealer_id', $dealer_id);
    	}

    	return $transaction->count_all_results();
    	
    }

    public function get_all_fcm_users($offset, $limit, $dealer_id)
    {
			$transaction    = $this->load->database('transactions', TRUE);

    	$transaction->select('customer_sessions.cus_fcm_id');
    	$transaction->from($this->table_customer);
    	$transaction->join('customer_sessions', 'customers.id = customer_sessions.cus_id');

    	$transaction->where('customer_sessions.deleted', '0');
    	$transaction->where('customers.deleted', '0');

    	if(!empty($dealer_id))
    	{
    		$transaction->where('customers.dealer_id', $dealer_id);
    	}
		
			$transaction->limit($limit);
			$transaction->offset($offset);

			return $transaction->get()->result();
		}
		
		public function insert($data=array()){
			if(!empty($data)){
				$transaction	= $this->load->database('transactions', TRUE);

				$transaction->insert($this->table, $data);

				if($transaction->affected_rows() > 0){
					return $transaction->insert_id();
				}
				return false;
			}
			return false;
		}
}