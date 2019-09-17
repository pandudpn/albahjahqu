<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class notification_model extends MY_Model {

    protected $table         = 'notifications';
    protected $tableDealer   = 'dealers';

    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;
    protected $soft_deletes  = true;

    protected $column_order;
    protected $column_search;
    protected $order         = array('created_on' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        if($this->session->userdata('user')->dealer_id == 3){
            $this->column_order = [null, 'dealer_id', 'title'];
            $this->column_search= ['title', 'message', 'dealer_id'];
        }else{
            $this->column_order = [null, 'title'];
            $this->column_search= ['title', 'message'];
        }
    }

    public function insert($data=array()){
        $transaction    = $this->load->database('transactions', TRUE);
        $query  = $transaction->insert($this->table, $data);

        return $transaction->insert_id();
    }

    public function _get_datatables_query($dealer)
    {
        $transaction    = $this->load->database('transactions', TRUE);
        $transaction->select('title, message, '.$this->table.'.created_on, name, dealer_id, '.$this->table.'.id');
        $transaction->from($this->table);
        $transaction->join($this->tableDealer, $this->tableDealer.'.id = '.$this->table.'.dealer_id', 'left');
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $transaction->open_bracket(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $transaction->like_not_and($item, $_POST['search']['value']);
                }
                else
                {
                    $transaction->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $transaction->close_bracket(); //close bracket
            }
            $i++;
        }

        //deleted = 0
        $transaction->where($this->table.'.deleted', '0');

        if($dealer != 3){
            $transaction->where('dealer_id', $dealer);
        }
        
         
        if(isset($_POST['order'])) // here order processing
        {
            $transaction->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $transaction->order_by(key($order), $order[key($order)]);
        }

        return $transaction;

    }
 
    public function get_datatables($dealer)
    {
        $transaction    = $this->_get_datatables_query($dealer);

        if($_POST['length'] != -1)
            $transaction->limit($_POST['length'], $_POST['start']);

        $query = $transaction->get();
        return $query->result();
    }
 
    public function count_filtered($dealer)
    {
        $transaction    = $this->_get_datatables_query($dealer);
        $query = $transaction->get();
        return $query->num_rows();
    }
 
    public function count_all($dealer)
    {
        $transaction    = $this->load->database('transactions', TRUE);

        $transaction->from($this->table);
        $transaction->where('deleted', 0);
        
        if($dealer != 3){
            $transaction->where('dealer_id', $dealer);
        }
        
        return $transaction->count_all_results();
    }
}