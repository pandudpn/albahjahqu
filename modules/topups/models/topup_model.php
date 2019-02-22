<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class topup_model extends MY_Model {

    protected $table            = 'transactions';
    protected $table_customer   = 'customers';
    protected $table_dealer     = 'dealers';
    protected $table_service    = 'ref_service_codes';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;

    protected $column_order  = array(null, 'customers.name', 'customers.phone', 'dealers.name', 'base_price', 'ref_service_codes.remarks','transactions.created_on'); //set column field database for datatable orderable
    protected $column_search = array('customers.name', 'customers.phone', 'dealers.name', 'base_price', 'ref_service_codes.remarks', 'transactions.created_on'); //set column field database for datatable searchable 
    protected $order 		 = array('transactions.id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query()
    {
        $this->db->select('*');
        $this->db->select($this->table.'.id as id');
        $this->db->select($this->table.'.created_on as created_on');
        $this->db->select($this->table_customer.'.name as customer_name');
        $this->db->select($this->table_customer.'.phone as customer_phone');
        $this->db->select($this->table_customer.'.email as customer_email');
        $this->db->select($this->table_dealer.'.name as dealer_name');
        $this->db->select($this->table.'.remarks as note');
        $this->db->from($this->table);
        $this->db->join($this->table_customer, $this->table_customer.'.id = '.$this->table.'.cus_id', 'left');
        $this->db->join($this->table_dealer, $this->table_dealer.'.id = '.$this->table.'.dealer_id', 'left');
        $this->db->join($this->table_service, $this->table_service.'.id = '.$this->table.'.service_id', 'left');
        
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
        $this->db->where('LEFT('.$this->table.'.service_code, 3) = ', 'TOP');
        $this->db->where($this->table.'.status <>', 'inquiry');
        // $this->db->where($this->table.'.status <>', 'approved');

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        $dealer = $this->input->get('dealer');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($dealer) 
        {
            $this->db->where($this->table.'.dealer_id', $dealer);
        }

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops' || $this->session->userdata('user')->role == 'dealer_spv')
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
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
        $this->db->where('deleted', '0');

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        $dealer = $this->input->get('dealer');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($dealer) 
        {
            $this->db->where($this->table.'.dealer_id', $dealer);
        }

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
        }

        $this->db->where('LEFT('.$this->table.'.service_code, 3) = ', 'TOP');
        $this->db->where($this->table.'.status <>', 'inquiry');
        // $this->db->where($this->table.'.status <>', 'approved');

        return $this->db->count_all_results();
    }

    public function total_sum()
    {
        $this->db->select('SUM(base_price) as total_sum', false);
        $this->db->from($this->table);
        $this->db->where('deleted', '0');

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        $dealer = $this->input->get('dealer');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($dealer) 
        {
            $this->db->where($this->table.'.dealer_id', $dealer);
        }

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
        }

        $this->db->where('LEFT('.$this->table.'.service_code, 3) = ', 'TOP');
        $this->db->where($this->table.'.status <>', 'inquiry');
        // $this->db->where($this->table.'.status <>', 'approved');

        return $this->db->get()->row();
    }

    public function download()
    {
        $this->db->select('customers.name as cus_name, customers.phone as cus_phone, dealers.name as dealer_name, base_price as topup, ref_service_codes.remarks, transactions.created_on');
        $this->db->from($this->table);
        $this->db->join($this->table_customer, $this->table_customer.'.id = '.$this->table.'.cus_id', 'left');
        $this->db->join($this->table_dealer, $this->table_dealer.'.id = '.$this->table.'.dealer_id', 'left');
        $this->db->join($this->table_service, $this->table_service.'.id = '.$this->table.'.service_id', 'left');
        
        $i = 0;
     
        $this->db->where($this->table.'.deleted', '0');
        $this->db->where('LEFT('.$this->table.'.service_code, 3) = ', 'TOP');
        $this->db->where($this->table.'.status <>', 'inquiry');
        // $this->db->where($this->table.'.status <>', 'approved');

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        $dealer = $this->input->get('dealer');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($dealer) 
        {
            $this->db->where($this->table.'.dealer_id', $dealer);
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