<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class students_bill extends MY_Model {

    protected $table            = 'student_bills';
    protected $tableStudent     = 'students';
    protected $tableUnit        = 'units';

    protected $key              = 'id';
    protected $date_format      = 'datetime';

    protected $set_created      = true;
    protected $soft_deletes     = true;

    protected $column_order  = array(null, 'units.id', 'students.name', null, null, 'bill_due_date', 'bill_status'); //set column field database for datatable orderable
    protected $column_search = array('students.name', 'bill_type', 'bill_period_type', 'bill_period'); //set column field database for datatable searchable 
    protected $order         = array('bill_due_date' => 'ASC', 'bill_status' => 'ASC'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query($app_id)
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $this->db->select($this->table.'.*, '.$this->tableStudent.'.name AS student_name, '.$this->tableUnit.'.name AS school_name');
        $this->db->from($this->table);
        $this->db->join($this->tableStudent, $this->tableStudent.'.id = '.$this->table.'.student_id');
        $this->db->join($this->tableUnit, $this->tableUnit.'.id = '.$this->tableStudent.'.unit_id');
 
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

        $this->db->where($this->table.'.deleted', 0);
        $this->db->where($this->tableUnit.'.app_id', $app_id);

        if(!empty($from)) {
            $this->db->where($this->table.'.created_on >=', $from);
        }

        if(!empty($to)) {
            $this->db->where($this->table.'.created_on <=', $to);
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

        // if($_POST['length'] != -1)
        //     $this->db->limit($_POST['length'], $_POST['start']);

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
        $this->db->join($this->tableStudent, $this->tableStudent.'.id = '.$this->table.'.student_id');
        $this->db->join($this->tableUnit, $this->tableUnit.'.id = '.$this->tableStudent.'.unit_id');
        $this->db->where($this->table.'.deleted', 0);
        $this->db->where($this->tableUnit.'.app_id', $app_id);
        
        return $this->db->count_all_results();
    }
    
}