<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class balance_model extends MY_Model {

    protected $table         = 'customers';
    protected $tablePartner  = 'partner_branch_accounts';

    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;
    protected $soft_deletes  = true;

    protected $column_order  = array(null, 'obb_eva.customers.account_holder', 'obb_eva.customers.account_balance'); //set column field database for datatable orderable
    protected $column_search = array('obb_eva.customers.account_holder'); //set column field database for datatable searchable 
    protected $order         = array('obb_eva.customers.account_holder' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }
 
    public function get_datatables()
    {
        $dbt    = 'obb_transactions';
        $dbe    = 'obb_eva';

        $this->db->select($dbe.".".$this->table.".*", false);
        $this->db->from($dbe.".".$this->table);
        $this->db->join($dbt.".".$this->table, $dbt.".".$this->table.".phone = ".$dbe.".".$this->table.".account_no");
        $this->db->join($dbt.".".$this->tablePartner, $dbt.".".$this->table.".phone = ".$dbt.".".$this->tablePartner.".partner_branch_eva");
 
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
        $this->db->where($dbt.'.'.$this->tablePartner.'.partner_id', 3);
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered()
    {
        $dbt    = 'obb_transactions';
        $dbe    = 'obb_eva';

        $this->db->select($dbe.".".$this->table.".*", false);
        $this->db->from($dbe.".".$this->table);
        $this->db->join($dbt.".".$this->table, $dbt.".".$this->table.".phone = ".$dbe.".".$this->table.".account_no");
        $this->db->join($dbt.".".$this->tablePartner, $dbt.".".$this->table.".phone = ".$dbt.".".$this->tablePartner.".partner_branch_eva");
 
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
        $this->db->where($dbt.'.'.$this->tablePartner.'.partner_id', 3);
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $dbt    = 'obb_transactions';
        $dbe    = 'obb_eva';

        $this->db->select($dbe.".".$this->table.".*", false);
        $this->db->from($dbe.".".$this->table);
        $this->db->join($dbt.".".$this->table, $dbt.".".$this->table.".phone = ".$dbe.".".$this->table.".account_no");
        $this->db->join($dbt.".".$this->tablePartner, $dbt.".".$this->table.".phone = ".$dbt.".".$this->tablePartner.".partner_branch_eva");

        //deleted = 0
        $this->db->where($dbt.'.'.$this->tablePartner.'.partner_id', 3);

        return $this->db->get()->num_rows();
    }

}