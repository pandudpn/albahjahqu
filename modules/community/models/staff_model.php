<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class staff_model extends MY_Model {

    protected $table            = 'staff';
    protected $tableUnit        = 'units';

    protected $key              = 'id';
    protected $date_format      = 'datetime';

    protected $set_created      = true;
    protected $soft_deletes     = true;

    protected $column_order  = array(null, 'unit_id', 'staff.name'); //set column field database for datatable orderable
    protected $column_search = array('units.name', 'nis', 'staff.name', 'gender', 'staff.address'); //set column field database for datatable searchable 
    protected $order         = array('unit_id' => 'ASC', 'staff.name' => 'ASC'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function get_position($where=array()) {
        $this->db->select('DISTINCT(position) AS position', FALSE);
        $this->db->from($this->table);
        $this->db->join($this->tableUnit, $this->tableUnit.'.id = '.$this->table.'.unit_id');
        $this->db->where($this->table.'.deleted', 0);

        if(!empty($where)){
            foreach($where AS $key => $val) {
                $this->db->where($key, $val);
            }
        }

        $query  = $this->db->get();
        return $query->result();
    }

    public function get_all($where=array()) {
        $this->db->select($this->table.'.*');
        $this->db->from($this->table);
        $this->db->join($this->tableUnit, $this->tableUnit.'.id = '.$this->table.'.unit_id');

        if(!empty($where)) {
            foreach($where AS $key => $val) {
                $this->db->where($key, $val);
            }
        }

        $query  = $this->db->get();
        return $query->result();
    }

    public function _get_datatables_query($app_id)
    {
        $position = $this->input->get('position');

        $this->db->select($this->table.'.*, '.$this->tableUnit.'.name AS unit_name');
        $this->db->from($this->table);
        $this->db->join($this->tableUnit, $this->tableUnit.'.id = '.$this->table.'.unit_id');
 
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
        $this->db->where($this->tableUnit.'.app_id', $app_id);
        $this->db->where($this->table.'.status !=', 'no');

        if(!empty($position)){
            $this->db->where($this->table.'.position', $position);
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
 
    public function get_datatables($app_id)
    {
        $this->_get_datatables_query($app_id);

        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);

        $query = $this->db->get();

        return $query->result();
    }
 
    public function count_filtered($app_id)
    {
        $this->_get_datatables_query($app_id);

        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($app_id)
    {
        $this->db->from($this->table);
        $this->db->join($this->tableUnit, $this->tableUnit.'.id = '.$this->table.'.unit_id');
        $this->db->where($this->table.'.deleted', 0);
        $this->db->where($this->tableUnit.'.app_id', $app_id);
        $this->db->where($this->table.'.status !=', 'no');
        
        return $this->db->count_all_results();
    }
    
}