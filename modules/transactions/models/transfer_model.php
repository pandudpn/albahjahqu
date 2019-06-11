<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class transfer_model extends MY_Model {

	protected $table        = 'customer_mutations';
    protected $key          = 'id';
    protected $date_format  = 'datetime';
    protected $set_created  = true;

    protected $column_order  = array(null, 'transaction_ref', 'account_eva', 'transaction_code', 'debit','remarks', 'customer_mutations.created_on'); //set column field database for datatable orderable
    protected $column_search = array('transaction_ref', 'account_eva', 'transaction_code', 'debit', 'remarks', 'customer_mutations.created_on'); //set column field database for datatable searchable 
    protected $order 		 = array('customer_mutations.id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();

        $this->db = $this->load->database('eva', TRUE);
    }

    public function _get_datatables_query()
    {
    	$this->db->select('transaction_ref, account_eva, transaction_code, remarks');
    	$this->db->select('IF(RIGHT(transaction_code, 2) = "IN", "IN", RIGHT(transaction_code, 3)) as type', false);
    	$this->db->select('(debit + credit) as amount', false);
    	$this->db->select($this->table.'.created_on as created_on');
        $this->db->from($this->table);
        $this->db->join('customers', 'customers.id = customer_mutations.account_id');
        
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

        $this->db->where($this->table.'.deleted', '0');
        $this->db->where('LEFT('.$this->table.'.transaction_code, 3) = ', 'TRF');

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops' || $this->session->userdata('user')->role == 'dealer_spv')
        {
            $this->db->where('customers.account_dealer', $this->session->userdata('user')->dealer_id);
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
 
    public function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        $this->db->join('customers', 'customers.id = customer_mutations.account_id');

        $this->db->where('customer_mutations.deleted', '0');
        $this->db->where($this->table.'.account_user', $this->input->get('customer_id'));

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }
        
        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops' || $this->session->userdata('user')->role == 'dealer_spv')
        {
            $this->db->where('customers.account_dealer', $this->session->userdata('user')->dealer_id);
        }

        return $this->db->count_all_results();
    }

    public function download()
    {
        $user = $this->session->userdata('user');

        $this->db->select('transaction_ref, account_eva');
        $this->db->select('IF(RIGHT(transaction_code, 2) = "IN", "IN", RIGHT(transaction_code, 3)) as type', false);
        $this->db->select('(debit + credit) as amount', false);
        $this->db->select($this->table.'.remarks as remarks');
        $this->db->select($this->table.'.created_on as created_on');
        $this->db->from($this->table);
        $this->db->join('customers', 'customers.id = customer_mutations.account_id');

        $this->db->where($this->table.'.deleted', '0');
        $this->db->where('LEFT('.$this->table.'.transaction_code, 3) = ', 'TRF');

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($user->role != 'dekape' && !empty($user->dealer_id))
        {
            $this->db->where('customers.account_dealer', $user->dealer_id);
        }

        $result = $this->db->get();
        // echo $this->db->last_query();die;

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        date_default_timezone_set("Asia/Jakarta");
        $filename  = "export_".date('Y-m-d H:i:s').".csv";

        $delimiter = "|";
        $newline   = "\r\n";
        $csv_file  = $this->dbutil->csv_from_result($result, $delimiter, $newline);

        force_download($filename, $csv_file);
    }
}