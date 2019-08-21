<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class transactions_model extends MY_Model {

    protected $column_order  = array(null, 'c.account_holder', 't1.account_holder', 't1.debit', 't1.created_on'); //set column field database for datatable orderable
    protected $column_search = array('c.account_holder', 't1.account_holder', 't1.debit', 't1.created_on'); //set column field database for datatable searchable

    public function __construct()
    {
        parent::__construct();
    }
 
    public function get_datatables($apps, $apps_name)
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $eva    = $this->load->database('eva', TRUE);

		$sql = "SELECT t1.id, c.account_holder AS sender, t1.account_holder AS receiver, t1.debit AS cost, t1.created_on
                FROM
                (
                SELECT account_holder, account_no, account_id, debit, a.created_on, a.id
                FROM customer_mutations AS a INNER JOIN customers AS b
                ON b.account_no = CAST(SUBSTRING(transaction_code, 6) AS unsigned)
                WHERE substring(transaction_code, 1, 2) = '$apps' AND substring(transaction_code, 3, 3) = 'zak' AND substring(transaction_code, -3) = 'out'
                AND account_holder LIKE '%$apps_name%'
                ) AS t1
                INNER JOIN
                customers AS c ON c.id = t1.account_id
        ";
        
        if(!empty($from) && !empty($to)){
            $sql    .= "WHERE t1.created_on >= '$from 00:00:01'";
            $sql    .= "AND t1.created_on <= '$to 23:59:59'";
        }
		
		if(!empty($_POST['search']['value']))
		{
			$sql .= " AND ( ";    
			$sql .= "
                c.account_holder LIKE '%".$eva->escape_like_str($_POST['search']['value'])."%'
				OR t1.account_holder LIKE '%".$eva->escape_like_str($_POST['search']['value'])."%'
				OR t1.debit LIKE '%".$eva->escape_like_str($_POST['search']['value'])."%'
			";
			$sql .= " ) ";
        }

        if(isset($_POST['order'])){
            $sql .= " ORDER BY ".$this->column_order[$_POST['order'][0]['column']]." ".$_POST['order']['0']['dir'];
        }else{
            $sql .= " ORDER BY year(t1.created_on) DESC, month(t1.created_on) DESC, day(t1.created_on) DESC, hour(t1.created_on) DESC, minute(t1.created_on) DESC, c.account_holder ASC ";
        }

        $data['totalData']      = $eva->query($sql)->num_rows();
        $data['totalFiltered']	= $eva->query($sql)->num_rows();

        if($_POST['length'] != -1)
            $sql .= " LIMIT ".$_POST['start']." ,".$_POST['length'];
		
        $data['query']          = $eva->query($sql)->result();
		return $data;
    }

}