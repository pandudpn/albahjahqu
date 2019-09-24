<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class ibadah_model extends MY_Model {

    protected $table         = 'prayer';
    protected $tableCategory = 'prayer_category';

    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;
    protected $set_modified  = true;

    protected $column_order  = array(null, 'title', null, 'prayer_category.name'); //set column field database for datatable orderable
    protected $column_search = array('name', 'prayer_category.name', 'text'); //set column field database for datatable searchable 
    protected $order         = array('prayer_category.name' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function prayer_category($app){
        $this->db->where('app_id', $app);
        
        $query  = $this->db->get($this->tableCategory);
        return $query->result();
    }

    public function _get_datatables_query($app)
    {
        $type   = $this->input->get('type');

        $this->db->select($this->table.'.*, '.$this->tableCategory.'.name AS cat_name');
        $this->db->from($this->table);
        $this->db->join($this->tableCategory, $this->tableCategory.'.id = '.$this->table.'.cat_prayer_id');
 
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

        $this->db->where($this->tableCategory.'.app_id', $app);
        $this->db->where($this->table.'.deleted', 0);
        if(!empty($type)){
            $this->db->where('cat_prayer_id', $type);
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
        $this->db->join($this->tableCategory, $this->tableCategory.'.id = '.$this->table.'.cat_prayer_id');
        $this->db->where($this->tableCategory.'.app_id', $app);
        $this->db->where($this->table.'.deleted', 0);
        
        return $this->db->count_all_results();
    }
}