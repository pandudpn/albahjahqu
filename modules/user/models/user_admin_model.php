<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class user_admin_model extends MY_Model {

    protected $table         = 'admins';
    protected $tableApps     = 'apps';
    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;
    protected $soft_deletes  = true;

    protected $column_order  = array(null,'name', 'phone', 'email'); //set column field database for datatable orderable
    protected $column_search = array('name', 'email'); //set column field database for datatable searchable 
    protected $order 		 = array('name' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function get_login($data=array()){
        $this->db->select($this->table.'.*, '.$this->tableApps.'.name AS apps_name, '.$this->tableApps.'.alias_name AS alias');
        $this->db->from($this->table);
        $this->db->join($this->tableApps, $this->tableApps.'.package_name = '.$this->table.'.app_id', 'left');

        foreach($data AS $key => $val){
            $this->db->where($key, $val);
        }

        return $this->db->get()->row();
    }

    public function _get_datatables_query()
    {
         
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

        //deleted = 0
        $this->db->where('deleted', '0');
         
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
        return $this->db->count_all_results();
    }

}