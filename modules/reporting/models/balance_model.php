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
        // $from   = $this->input->get('from');
        // $to     = $this->input->get('to');

        $eva    = $this->load->database('eva', TRUE);
        $trans  = $this->load->database('transactions', TRUE);

        $dbt    = $trans->database;
        $dbe    = $eva->database;

        $eva->select($this->table.'.*');
        $eva->from($dbe.'.'.$this->table);
        $eva->join($dbt.'.'.$this->table, $dbt.'.'.$this->table.'.phone = '.$dbe.'.'.$this->table.'.account_no');
        $eva->join($dbt.'.'.$this->tablePartner, $dbt.'.'.$this->tablePartner.'.partner_branch_eva = '.$dbt.'.'.$this->table.'.phone');
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $eva->open_bracket(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $eva->like_not_and($item, $_POST['search']['value']);
                }
                else
                {
                    $eva->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $eva->close_bracket(); //close bracket
            }
            $i++;
        }

        //deleted = 0
        $eva->where($dbt.'.'.$this->tablePartner.'.partner_id', 3);

        // if(!empty($from) && !empty($to)){
        //     $eva->where($this->table.'.created_on >=', $from.' 00:00:01');
        //     $eva->where($this->table.'.created_on <=', $to.' 23:59:59');
        // }else{
        //     $eva->where('year('.$this->table.'.created_on) =', 'year(curdate())', false);
        //     $eva->where('month('.$this->table.'.created_on) =', 'month(curdate())', false);
        // }
         
        if(isset($_POST['order'])) // here order processing
        {
            $eva->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $eva->order_by(key($order), $order[key($order)]);
        }
        if($_POST['length'] != -1)
            $eva->limit($_POST['length'], $_POST['start']);
        $query = $eva->get();
        return $query->result();
    }
 
    public function count_filtered()
    {
        $eva    = $this->load->database('eva', TRUE);
        $trans  = $this->load->database('transactions', TRUE);

        $dbt    = $trans->db->database;
        $dbe    = $eva->db->database;

        $eva->select($dbe.'.'.$this->table.'.*');
        $eva->from($dbe.'.'.$this->table);
        $eva->join($dbt.'.'.$this->table, $dbt.'.'.$this->table.'.phone = '.$dbe.'.'.$this->table.'.account_no');
        $eva->join($dbt.'.'.$this->tablePartner, $dbt.'.'.$this->tablePartner.'.partner_branch_eva = '.$dbt.'.'.$this->table.'.phone');
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $eva->open_bracket(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $eva->like_not_and($item, $_POST['search']['value']);
                }
                else
                {
                    $eva->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $eva->close_bracket(); //close bracket
            }
            $i++;
        }

        //deleted = 0
        $eva->where($dbt.'.'.$this->tablePartner.'.partner_id', 3);

        // if(!empty($from) && !empty($to)){
        //     $eva->where($this->table.'.created_on >=', $from.' 00:00:01');
        //     $eva->where($this->table.'.created_on <=', $to.' 23:59:59');
        // }else{
        //     $eva->where('year('.$this->table.'.created_on) =', 'year(curdate())', false);
        //     $eva->where('month('.$this->table.'.created_on) =', 'month(curdate())', false);
        // }
         
        if(isset($_POST['order'])) // here order processing
        {
            $eva->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $eva->order_by(key($order), $order[key($order)]);
        }
        $query = $eva->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $eva    = $this->load->database('eva', TRUE);
        $trans  = $this->load->database('transactions', TRUE);

        $dbt    = $trans->db->database;
        $dbe    = $eva->db->database;

        $eva->select($dbe.'.'.$this->table.'.*');
        $eva->from($dbe.'.'.$this->table);
        $eva->join($dbt.'.'.$this->table, $dbt.'.'.$this->table.'.phone = '.$dbe.'.'.$this->table.'.account_no');
        $eva->join($dbt.'.'.$this->tablePartner, $dbt.'.'.$this->tablePartner.'.partner_branch_eva = '.$dbt.'.'.$this->table.'.phone');

        $eva->where($dbt.'.'.$this->tablePartner.'.partner_id', 3);

        return $eva->get()->num_rows();
    }

}