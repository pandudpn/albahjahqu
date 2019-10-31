<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class student_model extends MY_Model {

    protected $table            = 'partner_students';
    protected $tablePartner     = 'partner_branches';

    protected $key              = 'id';
    protected $date_format      = 'datetime';

    protected $set_created      = true;
    protected $soft_deletes     = true;

    protected $column_order  = array(null, 'partner_branch_id', 'partner_students.student_number', 'partner_students.name', 'gender'); //set column field database for datatable orderable
    protected $column_search = array('partner_branches.name', 'partner_students.student_number', 'partner_students.name', 'gender', 'partner_students.address'); //set column field database for datatable searchable 
    protected $order         = array('partner_branch_id' => 'ASC', 'partner_students.name' => 'ASC'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function get_by($app, $term) {
        $this->db->select($this->table.'.*, '.$this->tablePartner.'.name AS partner_name');
        $this->db->from($this->table);
        $this->db->join($this->tablePartner, $this->tablePartner.'.id = '.$this->table.'.partner_branch_id');
        $this->db->where('app_id', $app);
        $this->db->like($this->table.'.name', $term);
        $this->db->or_like($this->table.'.student_number', $term);
        return $this->db->get();
    }

    public function get_all($where=array()) {
        // $this->db->select($this->table.'.*');
        $this->db->from($this->table);
        // $this->db->join($this->tablePartner, $this->tablePartner.'.id = '.$this->table.'.partner_branch_id');

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
        $type = $this->input->get('level');

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
        $this->db->where($this->tablePartner.'.app_id', $app_id);
        $this->db->where($this->table.'.status !=', 'out');

        if(!empty($type)){
            $this->db->where($this->tablePartner.'.level', $type);
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
        $this->db->join($this->tablePartner, $this->tablePartner.'.id = '.$this->table.'.partner_branch_id');
        $this->db->where($this->table.'.deleted', 0);
        $this->db->where($this->tablePartner.'.app_id', $app_id);
        $this->db->where($this->table.'.status !=', 'out');
        
        return $this->db->count_all_results();
    }
    
}