<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class donation_trackings_model extends MY_Model {

    protected $table         = 'donation_trackings';
    protected $tableDonation = 'donations';

    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;
    protected $soft_deletes  = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($where=array()) {
        foreach($where AS $key => $val) {
            $this->db->where($key, $val);
        }

        $query  = $this->db->get($this->table);
        return $query->result();
    }

    public function get($id) {
        $this->db->select($this->table.'.*, '.$this->tableDonation.'.title AS title_donation');
        $this->db->join($this->tableDonation, $this->table.'.donation_id = '.$this->tableDonation.'.id');
        $this->db->where($this->table.'.id', $id);

        $query  = $this->db->get($this->table);
        return $query->row();
    }

    public function delete_all($donation) {
        $this->db->where('donation_id', $donation);

        $this->db->update($this->table, ['deleted' => 1]);

        if($this->db->affected_rows() > 0 ){
            return true;
        }
        return false;
    }

}