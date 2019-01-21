<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class transaction_model extends MY_Model {

    protected $table            = 'transactions';
    protected $table_biller     = 'billers';
	protected $table_customer   = 'customers';
	protected $table_code       = 'ref_service_codes';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;

    protected $column_order  = array(null, null, 'transactions.created_on', 'trx_code', 'ref_service_codes.remarks', 'biller_name', 'ref_code', 'cus_phone', 'destination_no', 'selling_price', 'base_price', 'dealer_fee', 'biller_fee', 'dekape_fee', 'partner_fee', 'user_fee', 'user_cashback', 'status'); //set column field database for datatable orderable
    protected $column_search = array('trx_code', 'ref_service_codes.remarks', 'billers.name', 'ref_code', 'customers.phone', 'destination_no', 'selling_price', 'base_price', 'dealer_fee', 'biller_fee', 'dekape_fee', 'partner_fee', 'user_fee', 'user_cashback', 'status', 'transactions.created_on'); //set column field database for datatable searchable 
    protected $order 		 = array('transactions.id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query($type=null)
    {
        $this->db->select('transactions.id, ref_code, trx_code, destination_no, token_code, selling_price, status, status_provider');
        $this->db->select($this->table.'.created_on');
        $this->db->select($this->table.'.base_price');
        $this->db->select($this->table.'.dealer_fee');
        $this->db->select($this->table.'.biller_fee');
        $this->db->select($this->table.'.partner_fee');
        $this->db->select($this->table.'.dekape_fee');
        $this->db->select($this->table.'.user_fee');
        $this->db->select($this->table.'.user_cashback');
        $this->db->select($this->table_code.'.remarks');
        $this->db->select($this->table_customer.'.name as cus_name');
        $this->db->select($this->table_customer.'.phone as cus_phone');
        $this->db->select($this->table_biller.'.name as biller_name');
        $this->db->from($this->table);
        $this->db->join($this->table_code, $this->table_code.'.id = '.$this->table.'.service_id', 'left');
        $this->db->join($this->table_biller, $this->table_biller.'.id = '.$this->table.'.biller_id', 'left');
        $this->db->join($this->table_customer, $this->table_customer.'.id = '.$this->table.'.cus_id', 'left');
        
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
        $this->db->where($this->table.'.status <>', 'inquiry');

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
        }

        if($type == 'pending')
        {
            $this->db->where("(status_provider = '68' OR status_provider = '82' OR status_provider = '96')");
            $this->db->where($this->table.'.created_on >= DATE_ADD(NOW(), INTERVAL -1 WEEK)');
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
 
    public function get_datatables($type=null)
    {
        $this->_get_datatables_query($type);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered($type=null)
    {
        $this->_get_datatables_query($type);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($type=null)
    {
        $this->db->from($this->table);
        $this->db->where('deleted', '0');

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
        }

        if($type == 'pending')
        {
            $this->db->where("(status_provider = '68' OR status_provider = '82' OR status_provider = '96')");
        }
        
        return $this->db->count_all_results();
    }

    public function download()
    {
        $this->db->select('transactions.created_on, trx_code, ref_service_codes.remarks, billers.name as biller_name, ref_code, customers.phone as cus_phone, destination_no, selling_price, base_price, dealer_fee, biller_fee, dekape_fee, partner_fee, user_fee, user_cashback, status', false);
        $this->db->from($this->table);
        $this->db->join($this->table_code, $this->table_code.'.id = '.$this->table.'.service_id', 'left');
        $this->db->join($this->table_biller, $this->table_biller.'.id = '.$this->table.'.biller_id', 'left');
        $this->db->join($this->table_customer, $this->table_customer.'.id = '.$this->table.'.cus_id', 'left');
        
        $i = 0;
     
        $this->db->where($this->table.'.deleted', '0');
        $this->db->where($this->table.'.status <>', 'inquiry');

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
        }

        $result = $this->db->get();

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        date_default_timezone_set("Asia/Jakarta");
        $filename  = "export_".date('Y-m-d H:i:s').".csv";

        $delimiter = ",";
        $newline   = "\r\n";
        $csv_file  = $this->dbutil->csv_from_result($result, $delimiter, $newline);

        force_download($filename, $csv_file);
    }

}