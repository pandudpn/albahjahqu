<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class denom_model extends MY_Model {

	protected $table         	= 'prepaid_denom_prices';
    protected $table_provider   = 'ref_service_providers';
	protected $table_denom 	    = 'ref_denoms';
	protected $table_biller   	= 'billers';
	protected $table_dealer   	= 'dealers';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;
    protected $soft_deletes     = true;

    protected $column_order  = array(null, 'ref_service_providers.name', 'prepaid_denom_prices.description', 'prepaid_denom_prices.quota', 'prepaid_denom_prices.category','prepaid_denom_prices.base_price', 'prepaid_denom_prices.dealer_name', 'prepaid_denom_prices.biller_code', 'prepaid_denom_prices.type', 'ref_denoms.value'); //set column field database for datatable orderable
    protected $column_search = array('ref_service_providers.name', 'prepaid_denom_prices.description', 'prepaid_denom_prices.quota', 'prepaid_denom_prices.category', 'prepaid_denom_prices.base_price', 'prepaid_denom_prices.dealer_name', 'prepaid_denom_prices.biller_code', 'ref_denoms.value'); //set column field database for datatable searchable 
    protected $order 		 = array('prepaid_denom_prices.id' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query()
    {
        $this->db->select($this->table.'.id');
        $this->db->select($this->table.'.operator');
        $this->db->select($this->table_provider.'.name as provider_name', false);
        $this->db->select($this->table.'.description', false);
        $this->db->select("IFNULL(".$this->table.".dealer_name, '-') as dealer_name", false);
        $this->db->select("IFNULL(".$this->table.".biller_code, '-') as biller_code", false);
        $this->db->select($this->table.'.type', false);
        $this->db->select($this->table_denom.'.value as denom', false);
        $this->db->select('quota, category, prepaid_denom_prices.base_price, dealer_fee, dekape_fee, biller_fee, partner_fee, user_fee, status', false);
        $this->db->from($this->table);
        $this->db->join($this->table_provider, $this->table_provider.'.alias = '.$this->table.'.operator', 'left');
        $this->db->join($this->table_denom, $this->table_denom.'.id = '.$this->table.'.denom_id', 'left');
 
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

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $this->db->where("(prepaid_denom_prices.dealer_id = '".$this->session->userdata('user')->dealer_id."' OR prepaid_denom_prices.dealer_id IS NULL)");
        }

        $dealer     = $this->input->get('dealer');
        $biller     = $this->input->get('biller');
        $provider   = $this->input->get('provider');
        $type       = $this->input->get('type');
        $category   = $this->input->get('category');
         
        if($dealer) 
        {
            $this->db->where($this->table.'.dealer_id', $dealer);
        }

        if($biller) 
        {
            $this->db->where($this->table.'.biller_id', $biller);
        }

        if($provider) 
        {
            $this->db->where($this->table.'.operator', $provider);
        }

        if($type) 
        {
            $this->db->where($this->table.'.type', $type);
        }

        if($category) 
        {
            $this->db->where($this->table.'.category', $category);
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

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $this->db->where("(prepaid_denom_prices.dealer_id = '".$this->session->userdata('user')->dealer_id."' OR prepaid_denom_prices.dealer_id IS NULL)");
        }

        $dealer     = $this->input->get('dealer');
        $provider   = $this->input->get('provider');
        $type       = $this->input->get('type');
        $category   = $this->input->get('category');
         
        if($dealer) 
        {
            $this->db->where($this->table.'.dealer_id', $dealer);
        }

        if($provider) 
        {
            $this->db->where($this->table.'.operator', $provider);
        }

        if($type) 
        {
            $this->db->where($this->table.'.type', $type);
        }

        if($category) 
        {
            $this->db->where($this->table.'.category', $category);
        }
        
        return $this->db->count_all_results();
    }

    public function download()
    {
        $this->db->select($this->table.'.id');
        $this->db->select($this->table_provider.'.name as provider_name', false);
        $this->db->select('REPLACE('.$this->table.'.description, TRIM(","), " ") as description', false);
        $this->db->select("IFNULL(".$this->table.".dealer_name, '-') as dealer_name", false);
        $this->db->select("IFNULL(".$this->table.".biller_code, '-') as biller_code", false);
        $this->db->select($this->table.'.type', false);
        $this->db->select('REPLACE('.$this->table.'.quota, TRIM(","), " ") as quota', false);
        $this->db->select('category, prepaid_denom_prices.base_price, dealer_fee, dekape_fee, biller_fee, partner_fee, user_fee', false);
        $this->db->from($this->table);
        $this->db->join($this->table_provider, $this->table_provider.'.alias = '.$this->table.'.operator', 'left');
     
        $this->db->where($this->table.'.deleted', '0');

        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
        }

        $dealer     = $this->input->get('dealer');
        $provider   = $this->input->get('provider');
        $type       = $this->input->get('type');
        $category   = $this->input->get('category');
         
        if($dealer) 
        {
            $this->db->where($this->table.'.dealer_id', $dealer);
        }

        if($provider) 
        {
            $this->db->where($this->table.'.operator', $provider);
        }

        if($type) 
        {
            $this->db->where($this->table.'.type', $type);
        }

        if($category) 
        {
            $this->db->where($this->table.'.category', $category);
        }

        $result = $this->db->get();

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

    public function provider_list()
    {
        return $this->db->query('SELECT DISTINCT prepaid_denom_prices.operator, ref_service_providers.name
                                 FROM prepaid_denom_prices 
                                 JOIN ref_service_providers ON prepaid_denom_prices.operator = ref_service_providers.alias
                                 ORDER BY ref_service_providers.name')->result();
    }

    public function category_list()
    {
        return $this->db->query('SELECT DISTINCT category FROM prepaid_denom_prices')->result();
    }

}