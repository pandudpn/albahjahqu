<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class service_model extends MY_Model {

	protected $table         	= 'ref_service_codes';
	protected $table_provider 	= 'ref_service_providers';
	protected $table_biller   	= 'billers';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;
    protected $soft_deletes     = true;

    protected $column_order  = array(null, 'ref_service_providers.name', 'ref_service_codes.remarks', 'billers.name'); //set column field database for datatable orderable
    protected $column_search = array('ref_service_providers.name', 'ref_service_codes.remarks', 'billers.name'); //set column field database for datatable searchable 
    protected $order 		 = array('ref_service_codes.id' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query()
    {
        $this->db->select($this->table.'.id', false);
        $this->db->select($this->table_provider.'.name as provider_name', false);
        $this->db->select($this->table.'.remarks', false);
        $this->db->select($this->table.'.deleted', false);
        $this->db->select("IFNULL(".$this->table_biller.".name, '-') as biller_name", false);
        $this->db->from($this->table);
        $this->db->join($this->table_provider, $this->table_provider.'.alias = '.$this->table.'.provider', 'left');
        $this->db->join($this->table_biller, $this->table_biller.'.id = '.$this->table.'.by', 'left');
 
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

        // $this->db->where($this->table.'.deleted', '0');
         
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
        // $this->db->where('deleted', '0');
        return $this->db->count_all_results();
    }

}