<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class ayat_doa_model extends MY_Model {

    protected $table         = 'ayat_doa';
    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = false;
    protected $set_modified  = false;

    protected $column_order  = array(null, 'no', 'latin', 'translate'); //set column field database for datatable orderable
    protected $column_search = array('no', 'latin', 'translate'); //set column field database for datatable searchable 
    protected $order         = array('doa_id' => 'ASC'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function delete_all($column, $value){
        $this->db->where($column, $value);
        return $this->db->delete($this->table);
    }
}