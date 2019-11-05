<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class donations_model extends MY_Model {

    protected $table         = 'donations';
    protected $tableCustomer = 'donation_customers';

    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;
    protected $soft_deletes  = true;

    protected $column_order  = array(null, 'customers.account_holder'); //set column field database for datatable orderable
    protected $column_search = array('donations.title', 'donation_customers.cus_name', 'donation_customers.credit'); //set column field database for datatable searchable 
    protected $order         = array('donation_customers.created_on' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function all_category($apps) {
        $this->db->select('DISTINCT(category_donation) AS category', FALSE);
        $this->db->where($this->table.'.app_id', $apps);
        $this->db->where($this->table.'.deleted', 0);
        $this->db->where($this->table.'.status', 'inactive');

        $query  = $this->db->get($this->table);
        return $query->result();
    }
 
    public function _get_datatables($apps)
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        $cat    = $this->input->get('category');

        $this->db->select($this->tableCustomer.'.*, '.$this->table.'.title AS donation_name, '.$this->table.'.category_donation AS category');
        $this->db->from($this->tableCustomer);
        $this->db->join($this->table, $this->table.'.id = '.$this->tableCustomer.'.donation_id');
 
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
        $this->db->where($this->tableCustomer.'.deleted', 0);
        $this->db->where($this->table.'.app_id', $apps);

        if(!empty($from) && !empty($to)){
            $this->db->where($this->tableCustomer.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->tableCustomer.'.created_on <=', $to.' 23:59:59');
        }else{
            $this->db->where('year('.$this->tableCustomer.'.created_on) =', 'year(curdate())', false);
            $this->db->where('month('.$this->tableCustomer.'.created_on) =', 'month(curdate())', false);
        }

        if(!empty($cat)) {
            $this->db->where($this->table.'.category_donation'. $cat);
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

    public function get_datatables($apps) {
        $this->_get_datatables($apps);
        
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);

        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered($apps)
    {
        $this->_get_datatables($apps);

        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($apps)
    {
        $this->db->select($this->tableCustomer.'.*, '.$this->table.'.name AS donation_name, '.$this->table.'.category_donation AS category');
        $this->db->from($this->tableCustomer);
        $this->db->join($this->table, $this->table.'.id = '.$this->tableCustomer.'.donation_id');

        $this->db->where($this->tableCustomer.'.deleted', 0);
        $this->db->where($this->table.'.app_id', $apps);

        return $this->db->count_all_results();
    }

}