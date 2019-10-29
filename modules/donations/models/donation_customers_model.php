<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class donation_customers_model extends MY_Model {

    protected $table         = 'donation_customers';

    protected $key           = 'id';
    protected $date_format   = 'datetime';
    protected $set_created   = true;
    protected $soft_deletes  = true;

    public function __construct()
    {
        parent::__construct();
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