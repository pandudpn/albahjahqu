<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class transaction_log_model extends MY_Model {

    protected $table            = 'transaction_logs';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;

    public function __construct()
    {
        parent::__construct();
    }

    protected $column_order  = array(null, 'transaction_code', 'user_name', 'user_role', 'user_phone', 'user_dealer_name', 'remarks', 'created_on'); //set column field database for datatable orderable
    protected $column_search = array('transaction_code', 'user_name', 'user_role', 'user_phone', 'user_dealer_name', 'remarks', 'created_on'); //set column field database for datatable searchable 
    protected $order 		 = array('id' => 'desc'); // default order 

    public function _get_datatables_query()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->open_bracket(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like_not_and($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->close_bracket(); //close bracket
            }

            $i++;
        }

        $this->db->where('deleted', '0');

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops' || $this->session->userdata('user')->role == 'dealer_spv')
        {
            $this->db->where($this->table.'.user_dealer_id', $this->session->userdata('user')->dealer_id);
        }

        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    public function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        $this->db->where('deleted', '0');

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops' || $this->session->userdata('user')->role == 'dealer_spv')
        {
            $this->db->where($this->table.'.user_dealer_id', $this->session->userdata('user')->dealer_id);
        }

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }
        
        return $this->db->count_all_results();
    }

}