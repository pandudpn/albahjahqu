<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class sadaqah_model extends MY_Model {

    protected $table         = 'customer_mutations';
    protected $tableCustomer = 'customers';
    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;
    protected $soft_deletes  = true;

    protected $column_order  = array(null, 'customers.account_holder'); //set column field database for datatable orderable
    protected $column_search = array('customers.account_holder'); //set column field database for datatable searchable 
    protected $order         = array('customer_mutations.created_on' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function trx_zakat($apps){
        $eva    = $this->load->database('eva', TRUE);

        $eva->select('SUM(credit) AS total_credit, account_holder AS name, account_id, '.$this->table.'.created_on', false);
        $eva->from($this->table);
        $eva->join($this->tableCustomer, $this->tableCustomer.'.id = '.$this->table.'.account_id', 'left');
        $eva->where('year('.$this->table.'.created_on) =', 'year(curdate())', false);
        $eva->where('month('.$this->table.'.created_on) =', 'month(curdate())', false);
        $eva->where('SUBSTRING(transaction_code, 1, 2) = ', $apps);
        $eva->where('SUBSTRING(transaction_code, 3, 3) = ', 'zak');
        // $eva->like($this->tableCustomer.'.account_holder', $apps, 'both');
        $eva->group_by('YEAR('.$this->table.'.created_on), MONTH('.$this->table.'.created_on), DAY('.$this->table.'.created_on)');
        $eva->order_by($this->table.'.created_on', 'asc');

        $query  = $eva->get();
        return $query->result();
    }

    public function trx_zakat_group($apps){
        $eva    = $this->load->database('eva', TRUE);

        $eva->select('SUM(credit) AS total_credit, account_holder AS name, account_id, '.$this->table.'.created_on', false);
        $eva->from($this->table);
        $eva->join($this->tableCustomer, $this->tableCustomer.'.id = '.$this->table.'.account_id', 'left');
        $eva->where('year('.$this->table.'.created_on) =', 'year(curdate())', false);
        $eva->where('month('.$this->table.'.created_on) =', 'month(curdate())', false);
        $eva->like($this->tableCustomer.'.account_holder', $apps, 'both');
        $eva->group_by('YEAR('.$this->table.'.created_on), MONTH('.$this->table.'.created_on), DAY('.$this->table.'.created_on), '.$this->tableCustomer.'.id');
        $eva->order_by($this->table.'.created_on', 'asc');

        $query  = $eva->get();
        return $query->result();
    }

    public function get_all($apps_name){
        $eva    = $this->load->database('eva', TRUE);

        $sql    = "SELECT *
                FROM customers
                WHERE deleted = 0";

        $sql .= " AND ";

        if(!empty($_POST['search']['value']))
        {
            $sql .= "
                account_holder LIKE '%".$apps_name." ".$eva->escape_like_str($_POST['search']['value'])."%'
            ";
        }else{
            $sql .= " account_holder LIKE '%".$apps_name."%' ";
        }

        if(isset($_POST['order'])) // here order processing
        {
            $sql .= " ORDER BY ".$this->column_order[$_POST['order'][0]['column']]." ".$_POST['order']['0']['dir'];
        } 
        else
        {
            $sql .= " ORDER BY account_holder ASC";
        }
        if($_POST['length'] != -1)
            $sql .= " LIMIT ".$_POST['start']." ,".$_POST['length'];

        $query  = $eva->query($sql);
        return $query->result();
    }
 
    public function get_datatables($alias)
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $eva    = $this->load->database('eva', TRUE);
        $eva->select('debit, account_holder AS name, account_id, '.$this->table.'.created_on', false);
        $eva->from($this->table);
        $eva->join($this->tableCustomer, $this->tableCustomer.'.id = '.$this->table.'.account_id', 'left');
 
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
        $eva->where($this->table. '.deleted', '0');
        $eva->where('SUBSTRING(transaction_code, 1, 4) = ', $alias);
        $eva->where('SUBSTRING(transaction_code, 5, 3)=','SDQ');
        $eva->where('SUBSTRING(transaction_code, -3)=', 'OUT');

        if(!empty($from) && !empty($to)){
            $eva->where($this->table.'.created_on >=', $from.' 00:00:01');
            $eva->where($this->table.'.created_on <=', $to.' 23:59:59');
        }else{
            $eva->where('year('.$this->table.'.created_on) =', 'year(curdate())', false);
            $eva->where('month('.$this->table.'.created_on) =', 'month(curdate())', false);
        }
         
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
 
    public function count_filtered($alias)
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $eva    = $this->load->database('eva', TRUE);
        $eva->select('credit, account_holder AS name, account_id', false);
        $eva->from($this->table);
        $eva->join($this->tableCustomer, $this->tableCustomer.'.id = '.$this->table.'.account_id', 'left');
 
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
        $eva->where($this->table. '.deleted', '0');
        $eva->where('SUBSTRING(transaction_code, 1, 4) = ', $alias);
        $eva->where('SUBSTRING(transaction_code, 5, 3)=','SDQ');
        $eva->where('SUBSTRING(transaction_code, -3)=', 'OUT');

        if(!empty($from) && !empty($to)){
            $eva->where($this->table.'.created_on >=', $from.' 00:00:01');
            $eva->where($this->table.'.created_on <=', $to.' 23:59:59');
        }else{
            $eva->where('year('.$this->table.'.created_on) =', 'year(curdate())', false);
            $eva->where('month('.$this->table.'.created_on) =', 'month(curdate())', false);
        }
         
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
 
    public function count_all($alias)
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $eva    = $this->load->database('eva', TRUE);
        $eva->from($this->table);
        $eva->join($this->tableCustomer, $this->tableCustomer.'.id = '.$this->table.'.account_id');
        $eva->where($this->table.'.deleted', '0');
        $eva->where('SUBSTRING(transaction_code, 1, 4) = ', $alias);
        $eva->where('SUBSTRING(transaction_code, 5, 3)=','SDQ');
        $eva->where('SUBSTRING(transaction_code, -3)=', 'OUT');

        if(!empty($from) && !empty($to)){
            $eva->where($this->table.'.created_on >=', $from.' 00:00:01');
            $eva->where($this->table.'.created_on <=', $to.' 23:59:59');
        }else{
            $eva->where('year('.$this->table.'.created_on) =', 'year(curdate())', false);
            $eva->where('month('.$this->table.'.created_on) =', 'month(curdate())', false);
        }

        return $eva->get()->num_rows();
    }

    public function download($alias)
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $eva    = $this->load->database('eva', TRUE);
        $eva->select('account_holder AS Nama, debit AS Nominal, '.$this->table.'.created_on AS Tanggal', false);
        $eva->from($this->table);
        $eva->join($this->tableCustomer, $this->tableCustomer.'.id = '.$this->table.'.account_id', 'left');

        //deleted = 0
        $eva->where($this->table. '.deleted', '0');
        $eva->where('SUBSTRING(transaction_code, 1, 4) = ', $alias);
        $eva->where('SUBSTRING(transaction_code, 5, 3)=','SDQ');
        $eva->where('SUBSTRING(transaction_code, -3)=', 'OUT');

        if(!empty($from) && !empty($to)){
            $eva->where($this->table.'.created_on >=', $from.' 00:00:01');
            $eva->where($this->table.'.created_on <=', $to.' 23:59:59');
        }
         
        $eva->order_by($this->table.'.created_on', 'desc');

        $result = $eva->get();

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        date_default_timezone_set("Asia/Jakarta");
        $filename  = "export_".date('Y-m-d H:i').".csv";

        $delimiter = "|";
        $newline   = "\n";
        $csv_file  = $this->dbutil->csv_from_result($result, $delimiter, $newline);

        force_download($filename, $csv_file);
    }

}