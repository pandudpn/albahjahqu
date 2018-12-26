<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class customer_model extends MY_Model {

	protected $table         	= 'customers';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;
    protected $soft_deletes     = true;

    protected $column_order  = array(null, 'name', 'phone', 'email', 'outlet_number', 'outlet_name', 'dealer_name', 'balance', 'account_status', 'kyc_status'); //set column field database for datatable orderable
    protected $column_search = array('name', 'phone', 'email', 'outlet_number', 'outlet_name', 'dealer_name', 'balance', 'account_status', 'kyc_status'); //set column field database for datatable searchable 
    protected $order 		 = array('customers.name' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query()
    {
        $this->db->from($this->table);
        
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

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($this->session->userdata('user')->role == 'dealer') 
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

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }
        
        if($this->session->userdata('user')->role == 'dealer') 
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
        }

        return $this->db->count_all_results();
    }

    public function download()
    {
        $this->db->select('name, phone, email, outlet_number, outlet_name, dealer_name, account_status, kyc_status');
        $this->db->from($this->table);
        $this->db->where($this->table.'.deleted', '0');

        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        if(!empty($from) && !empty($to))
        {
            $this->db->where($this->table.'.created_on >=', $from.' 00:00:01');
            $this->db->where($this->table.'.created_on <=', $to.' 23:59:59');
        }

        if($this->session->userdata('user')->role == 'dealer') 
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
        }

        $result = $this->db->get();

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        date_default_timezone_set("Asia/Jakarta");
        $filename  = "export_".date('Y-m-d H:i:s').".xls";

        $delimiter = ",";
        $newline   = "\r\n";
        $csv_file  = $this->dbutil->csv_from_result($result, $delimiter, $newline);

        force_download($filename, $csv_file);
    }

}