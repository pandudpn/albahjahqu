<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class units_model extends MY_Model {

    protected $table            = 'units';
    protected $tableProvince    = 'geo_provinces';
    protected $tableCities      = 'geo_cities';
    protected $tableDistricts   = 'geo_districts';
    protected $tableVillages    = 'geo_villages';

    protected $key              = 'id';
    protected $date_format      = 'datetime';

    protected $set_created      = true;
    protected $soft_deletes     = true;

    protected $column_order  = array(null, 'obb_partners.units.name', 'type'); //set column field database for datatable orderable
    protected $column_search = array('obb_partners.units.name', 'type', 'address', 'obb_partners.geo_provinces.name', 'obb_partners.geo_cities.name', 'obb_partners.geo_districts.name'); //set column field database for datatable searchable 
    protected $order         = array('type' => 'ASC', 'obb_partners.units.name' => 'ASC', 'obb_partners.units.level' => 'ASC'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function get_type($where=array()){
        $this->db->select('DISTINCT(type) AS type', FALSE);
        $this->db->from($this->table);
        $this->db->where($this->table.'.deleted', 0);
        if(!empty($where)) {
            foreach($where AS $key => $val) {
                $this->db->where($key, $val);
            }
        }

        $query  = $this->db->get();
        return $query->result();
    }

    public function get_all($where=array()){
        $this->db->where_in('type', ['sekolah', 'pesantren']);
        if(!empty($where)){
            foreach($where AS $key => $val){
                $this->db->where($key, $val);
            }
        }

        $this->db->order_by('name', 'ASC');
        $query  = $this->db->get($this->table);

        return $query->result();
    }

    public function _get_datatables_query($app_id)
    {
        $geo    = $this->load->database('geo', TRUE);

        $type   = $this->input->get('tipe');

        $this->db->select($this->db->database.'.'.$this->table.'.*, '.$geo->database.'.'.$this->tableProvince.'.name AS province_name, '.$geo->database.'.'.$this->tableCities.'.name AS city_name, '.$geo->database.'.'.$this->tableDistricts.'.name AS district_name, '.$geo->database.'.'.$this->tableVillages.'.name AS village_name', FALSE);
        $this->db->from($this->db->database.'.'.$this->table);

        $this->db->join($geo->database.'.'.$this->tableProvince, $geo->database.'.'.$this->tableProvince.'.id = '.$this->db->database.'.'.$this->table.'.province');
        $this->db->join($geo->database.'.'.$this->tableCities, $geo->database.'.'.$this->tableCities.'.id = '.$this->db->database.'.'.$this->table.'.city');
        $this->db->join($geo->database.'.'.$this->tableDistricts, $geo->database.'.'.$this->tableDistricts.'.id = '.$this->db->database.'.'.$this->table.'.district');
        $this->db->join($geo->database.'.'.$this->tableVillages, $geo->database.'.'.$this->tableVillages.'.id = '.$this->db->database.'.'.$this->table.'.village');
 
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
        $this->db->where($this->db->database.'.'.$this->table.'.deleted', 0);
        $this->db->where($this->db->database.'.'.$this->table.'.app_id', $app_id);

        if(!empty($type)){
            $this->db->where($this->db->database.'.'.$this->table.'.type', $type);
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
 
    public function get_datatables($app_id)
    {
        $this->_get_datatables_query($app_id);

        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);

        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered($app_id)
    {
        $this->_get_datatables_query($app_id, $where);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($app_id)
    {
        $this->db->from($this->table);
        $this->db->where('deleted', 0);
        $this->db->where('app_id', $app_id);

        if(!empty($where)){
            foreach($where AS $key => $val){
                $this->db->where($key, $val);
            }
        }
        
        return $this->db->count_all_results();
    }
    
}
