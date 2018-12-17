<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class transaction_model extends MY_Model {

	protected $table         	= 'transactions';
	protected $table_code       = 'ref_service_codes';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;

    protected $column_order  = array(null, 'trx_code', 'destination_no', 'selling_price', 'status', 'transactions.created_on', 'ref_service_codes.remarks'); //set column field database for datatable orderable
    protected $column_search = array('trx_code', 'destination_no', 'selling_price', 'status', 'transactions.created_on', 'ref_service_codes.remarks'); //set column field database for datatable searchable 
    protected $order 		 = array('transactions.id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query($type=null)
    {
        $this->db->select('transactions.id, trx_code, destination_no, selling_price, status, status_provider');
        $this->db->select($this->table.'.created_on');
        $this->db->select($this->table_code.'.remarks');
        $this->db->from($this->table);
        $this->db->join($this->table_code, $this->table_code.'.id = '.$this->table.'.service_id', 'left');
        
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

        if($this->session->userdata('user')->role == 'dealer') 
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
        }

        if($type == 'pending')
        {
            $this->db->where("(status_provider = '68' OR status_provider = '82' OR status_provider = '96')");
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
 
    public function get_datatables($type=null)
    {
        $this->_get_datatables_query($type);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered($type=null)
    {
        $this->_get_datatables_query($type);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($type=null)
    {
        $this->db->from($this->table);
        $this->db->where('deleted', '0');

        if($this->session->userdata('user')->role == 'dealer') 
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
        }

        if($type == 'pending')
        {
            $this->db->where("(status_provider = '68' OR status_provider = '82' OR status_provider = '96')");
        }
        
        return $this->db->count_all_results();
    }

}