<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class reporting_model extends MY_Model {

	protected $table         	= 'billers';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;
    protected $soft_deletes     = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function pls_lists()
    {
    	return $this->db->query("SELECT distinct service, provider, type, ref_service_providers.name, ref_service_types.description
			FROM ref_service_codes
			JOIN ref_service_providers ON ref_service_providers.alias = ref_service_codes.provider
			JOIN ref_service_types ON ref_service_types.code = ref_service_codes.type
			WHERE service = 'PLS'
			AND ref_service_codes.deleted = '0'
			AND ref_service_providers.deleted = '0'
			ORDER BY provider, type")->result();
    }

    public function pls_values($provider, $type, $date)
    {
    	return $this->db->query("
    		SELECT IFNULL(COUNT(transactions.id), 0) AS count_trx, IFNULL(SUM(selling_price), 0) AS sum_trx 
    		FROM transactions 
			WHERE SUBSTRING(service_code, 8, 3) = '".$provider."'
			AND RIGHT(service_code, 3) = '".$type."'
			AND LEFT(service_code, 3) = 'PLS'
			AND (status = 'payment' OR status = 'approved')
			AND transactions.created_on >= '".$date." 00:00'
			AND transactions.created_on <= '".$date." 23:59'")->row();
    }

    public function topup_lists()
    {
    	return $this->db->query("select * FROM ref_service_codes WHERE service = 'TOP' AND deleted = '0'")->result();
    }

    public function topup_values($id, $date)
    {
    	return $this->db->query("
    		SELECT service_id, IFNULL(COUNT(transactions.id), 0) AS count_trx, IFNULL(SUM(selling_price), 0) AS sum_trx 
    		FROM transactions 
			WHERE service_id = '".$id."'
			AND (status = 'payment' OR status = 'approved')
			AND transactions.created_on >= '".$date." 00:00'
			AND transactions.created_on <= '".$date." 23:59'")->row();
    }
}