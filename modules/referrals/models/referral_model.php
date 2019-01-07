<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class referral_model extends MY_Model {

	protected $table         	= 'dealer_referral_codes';
	protected $table_cluster    = 'dealer_clusters';
	protected $table_district   = 'geo_districts';
	protected $table_village    = 'geo_villages';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;
    protected $soft_deletes     = true;

    protected $column_order  = array(null, 'dealer_referral_codes.dealer_name', 'referral_phone', 'referral_code', 'dealer_clusters.alias', 'geo_districts.name', 'geo_villages.name'); //set column field database for datatable orderable
    protected $column_search = array('dealer_referral_codes.dealer_name', 'referral_phone', 'referral_code', 'dealer_clusters.alias', 'geo_districts.name', 'geo_villages.name'); //set column field database for datatable searchable 
    protected $order 		 = array('dealer_referral_codes.dealer_name' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query()
    {
        $this->db->select($this->table.'.*');
        $this->db->select($this->table_cluster.'.alias as cluster_alias');
        $this->db->select($this->table_district.'.name as district_name');
        $this->db->select($this->table_village.'.name as village_name');
        $this->db->from($this->table);
        $this->db->join($this->table_cluster, 'dealer_clusters.id = dealer_referral_codes.cluster_id', 'left');
        $this->db->join($this->table_district, 'geo_districts.id = dealer_referral_codes.district_id', 'left');
        $this->db->join($this->table_village, 'geo_villages.id = dealer_referral_codes.village_id', 'left');
        
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
        return $this->db->count_all_results();
    }

    public function last_id()
    {
        $this->db->select_max('id');
        $this->db->from($this->table);
        $query = $this->db->get();
        
        return $query->row();
    }

}