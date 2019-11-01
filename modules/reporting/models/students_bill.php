<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class students_bill extends MY_Model {

    protected $table            = 'partner_student_deposits';
    protected $tableStudent     = 'partner_students';
    protected $tablePartner     = 'partner_branches';

    protected $key              = 'id';
    protected $date_format      = 'datetime';

    protected $set_created      = true;
    protected $soft_deletes     = true;

    protected $column_order  = array(null, 'partner_students.name', null, null, 'deposit_due_date', 'deposit_status'); //set column field database for datatable orderable
    protected $column_search = array('students.name', 'deposit_type', 'deposit_period_type', 'deposit_period'); //set column field database for datatable searchable 
    protected $order         = array('partner_student_deposits.modified_on' => 'DESC'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query($app_id)
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $this->db->select($this->table.'.*, '.$this->tableStudent.'.name AS student_name, '.$this->tablePartner.'.name AS school_name, '.$this->tableStudent.'.partner_branch_code AS branch_code');
        $this->db->from($this->table);
        $this->db->join($this->tableStudent, $this->tableStudent.'.id = '.$this->table.'.student_id');
        $this->db->join($this->tablePartner, $this->tablePartner.'.id = '.$this->tableStudent.'.partner_branch_id');
 
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

        $this->db->where($this->table.'.deleted', 0);
        $this->db->where($this->tablePartner.'.app_id', $app_id);
        $this->db->where($this->table.'.deposit_status', 'paid');

        if(!empty($from)) {
            $this->db->where($this->table.'.created_on >=', $from);
        }

        if(!empty($to)) {
            $this->db->where($this->table.'.created_on <=', $to);
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
        $this->_get_datatables_query($app_id);

        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($app_id)
    {
        $this->db->from($this->table);
        $this->db->join($this->tableStudent, $this->tableStudent.'.id = '.$this->table.'.student_id');
        $this->db->join($this->tablePartner, $this->tablePartner.'.id = '.$this->tableStudent.'.partner_branch_id');
        $this->db->where($this->table.'.deleted', 0);
        $this->db->where($this->tablePartner.'.app_id', $app_id);
        $this->db->where($this->table.'.deposit_status', 'paid');
        
        return $this->db->count_all_results();
    }

    public function download($app_id)
    {
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');

        $this->db->select($this->table.'.student_number AS NIS, '.$this->tableStudent.'.name AS Nama, '.$this->tablePartner.'.name AS Sekolah, '.$this->tableStudent.'.partner_branch_code AS Kode_Cabang');
        $this->db->select($this->table.'.deposit_amount AS Nominal, IFNULL('.$this->table.'.modified_on, '.$this->table.'.created_on) AS Tanggal_Bayar', false);
        $this->db->from($this->table);
        $this->db->join($this->tableStudent, $this->tableStudent.'.id = '.$this->table.'.student_id');
        $this->db->join($this->tablePartner, $this->tablePartner.'.id = '.$this->tableStudent.'.partner_branch_id');

        $this->db->where($this->table.'.deleted', 0);
        $this->db->where($this->tablePartner.'.app_id', $app_id);
        $this->db->where($this->table.'.deposit_status', 'paid');

        if(!empty($from)) {
            $this->db->where($this->table.'.created_on >=', $from);
        }

        if(!empty($to)) {
            $this->db->where($this->table.'.created_on <=', $to);
        }

        $result = $this->db->get();

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        date_default_timezone_set("Asia/Jakarta");
        $filename  = "export_".date('Y-m-d H:i').".csv";

        $delimiter = "|";
        $newline   = "\n";
        $csv_file  = $this->dbutil->csv_from_result($result, $delimiter, $newline);

        force_download($filename, $csv_file);
    }
    
}