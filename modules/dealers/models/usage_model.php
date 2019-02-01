<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class usage_model extends MY_Model {

	protected $table         	= 'dealer_box_stock_usages';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;
    protected $soft_deletes     = true;

    protected $column_order  = array(null, 'dealers.name', 'trx', 'ipbox', 'slot', 'denom', 'dealer_box_stock_usages.status', 'mkios_response', 'dealer_box_stock_usages.created_on'); //set column field database for datatable orderable
    protected $column_search = array('dealers.name', 'trx', 'ipbox', 'slot', 'denom', 'dealer_box_stock_usages.status', 'mkios_response', 'dealer_box_stock_usages.created_on'); //set column field database for datatable searchable 
    protected $order 		 = array('dealer_box_stock_usages.id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query()
    {
        
        $this->db->select('dealers.name as dealer_name, trx, ipbox, slot, denom, dealer_box_stock_usages.status, mkios_response, dealer_box_stock_usages.created_on');
        $this->db->from($this->table);
        $this->db->join('transactions', 'transactions.trx_code = dealer_box_stock_usages.trx', 'left');
        $this->db->join('dealers', 'dealers.id = transactions.dealer_id', 'left');
        
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

        $this->db->where($this->table.'.deleted', '0');

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $this->db->where('dealers.id', $this->session->userdata('user')->dealer_id);
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
        $this->db->join('transactions', 'transactions.trx_code = dealer_box_stock_usages.trx', 'left');
        $this->db->join('dealers', 'dealers.id = transactions.dealer_id', 'left');

        $this->db->where('transactions.deleted', '0');
        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $this->db->where('dealers.id', $this->session->userdata('user')->dealer_id);
        }

        return $this->db->count_all_results();
    }

    public function last_id()
    {
        $this->db->select_max('id');
        $this->db->from($this->table);
        $query = $this->db->get();
        
        return $query->row();
    }

}