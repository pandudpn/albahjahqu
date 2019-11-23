<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class students_model extends MY_Model {

    protected $table         = 'partner_students';
    protected $tableProfiles = 'partner_student_profiles';
    protected $tablePartner  = 'partner_branches';

    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $soft_deletes  = TRUE;

    protected $column_order  = array(null, 'name'); //set column field database for datatable orderable
    protected $column_search = array('name'); //set column field database for datatable searchable 
    protected $order         = array('created_on' => 'DESC'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query($app)
    {
        $this->db->select($this->table.'.*, '.$this->tablePartner.'.name AS partner_name');
        $this->db->from($this->table);
        $this->db->join($this->tablePartner, $this->tablePartner.'.id = '.$this->table.'.partner_branch_id');
 
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
        $this->db->where($this->table.'.status', 'new');
         
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
        $this->db->where($this->table.'.deleted', 0);
        $this->db->where($this->table.'.app_id', $app);
        
        return $this->db->count_all_results();
    }

    public function get_parents($app) {
        $this->db->select($this->tableProfiles.'.*');
        $this->db->from($this->tableProfiles);
        $this->db->join($this->table, $this->table.'.id = '.$this->tableProfiles.'.partner_student_id');

        $this->db->where($this->tableProfiles.'.deleted', 0);
        $this->db->where($this->table.'.app_id', $app);
        $this->db->where($this->tableProfiles.'.role !=', 'child');

        $query  = $this->db->get();
        return $query->result();
    }
}