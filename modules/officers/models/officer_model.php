<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class officer_model extends MY_Model {

    protected $table         = 'officers';
    protected $tableAdmin    = 'admins';

    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $soft_deletes  = TRUE;

    protected $column_order  = array(null); //set column field database for datatable orderable
    protected $column_search = array('cus_name', 'cus_phone', 'email'); //set column field database for datatable searchable 
    protected $order         = array('created_on' => 'DESC'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query($app)
    {
        $this->db->select($this->table.'.*, '.$this->tableAdmin.'.email');
        $this->db->from($this->table);
        $this->db->join($this->tableAdmin, $this->tableAdmin.'.id = '.$this->table.'.admin_id');
 
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

        //deleted = 0
        $this->db->where($this->table.'.deleted', 0);
        $this->db->where($this->table.'.app_id', $app);
         
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
 
    public function get_datatables($app)
    {
        $this->_get_datatables_query($app);

        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);

        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered($app)
    {
        $this->_get_datatables_query($app);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($app)
    {
        $this->db->from($this->table);
        $this->db->join($this->tableAdmin, $this->tableAdmin.'.id = '.$this->table.'.admin_id');
        $this->db->where($this->table.'.deleted', 0);
        $this->db->where($this->table.'.app_id', $app);
        
        return $this->db->count_all_results();
    }
}