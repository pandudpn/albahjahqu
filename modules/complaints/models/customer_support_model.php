<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class customer_support_model extends MY_Model {

	protected $table         	= 'customer_supports';
    protected $key           	= 'id';
    protected $date_format   	= 'datetime';
    protected $set_created   	= true;
    protected $soft_deletes     = true;

    protected $column_order  = array(null, 'ticket', 'cus_name', 'dealer_name', 'subject', 'status', 'created_on', 'modified_on'); //set column field database for datatable orderable
    protected $column_search = array('ticket', 'cus_name', 'status', 'subject', 'created_on'); //set column field database for datatable searchable 
    protected $order 		 = array('created_on' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function _get_datatables_query($type)
    {
        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $where_user = "AND customer_support_members.user_id = '".$this->session->userdata('user')->dealer_id."'";
        }

        
        $this->db->select('*');
        $this->db->select("((SELECT count(customer_support_messages.id)
                            FROM customer_support_messages
                            JOIN customer_support_members ON customer_support_members.ticket = customer_support_messages.ticket
                            WHERE customer_support_members.role = 'dealer'
                            ".$where_user."
                            AND customer_support_messages.ticket = customer_supports.ticket
                            AND customer_supports.status = 'open'
                            ) - 
                            (SELECT count(customer_support_message_reads.id) FROM customer_support_message_reads 
                             JOIN customer_support_messages ON customer_support_messages.id = customer_support_message_reads.message_id
                             WHERE customer_support_message_reads.user_id = '".$this->session->userdata('user')->id."'
                             AND role = '".$this->session->userdata('user')->role."'
                             AND customer_support_messages.ticket = customer_supports.ticket
                             AND customer_supports.status = 'open'
        )) as unread", false);

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

        if($type == 'report')
        {
            $this->db->where($this->table.'.type', 'laporan');    
        }
        else if($type == 'help')
        {
            $this->db->where($this->table.'.type', 'bantuan');    
        }
        

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
 
    public function get_datatables($type)
    {
        $this->_get_datatables_query($type);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered($type)
    {
        $this->_get_datatables_query($type);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($type)
    {
        $this->db->from($this->table);
        
        if($type == 'report')
        {
            $this->db->where($this->table.'.type', 'laporan');    
        }
        else if($type == 'help')
        {
            $this->db->where($this->table.'.type', 'bantuan');    
        }

        $this->db->where('deleted', '0');
        
        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $this->db->where($this->table.'.dealer_id', $this->session->userdata('user')->dealer_id);
        }

        return $this->db->count_all_results();
    }

    public function unread()
    {
        if($this->session->userdata('user')->role == 'dealer' || $this->session->userdata('user')->role == 'dealer_ops') 
        {
            $where_user = "AND customer_support_members.user_id = '".$this->session->userdata('user')->dealer_id."'";
        }

        $this->db->select("SUM(((SELECT count(customer_support_messages.id)
                            FROM customer_support_messages
                            JOIN customer_support_members ON customer_support_members.ticket = customer_support_messages.ticket
                            WHERE customer_support_members.role = 'dealer'
                            ".$where_user."
                            AND customer_support_messages.ticket = customer_supports.ticket
                            AND customer_supports.status = 'open'
                            ) - 
                            (SELECT count(customer_support_message_reads.id) FROM customer_support_message_reads 
                             JOIN customer_support_messages ON customer_support_messages.id = customer_support_message_reads.message_id
                             WHERE customer_support_message_reads.user_id = '".$this->session->userdata('user')->id."'
                             AND role = '".$this->session->userdata('user')->role."'
                             AND customer_support_messages.ticket = customer_supports.ticket
                             AND customer_supports.status = 'open'
        ))) as unread", false);

        $this->db->from($this->table);
        return $this->db->get()->row();
    }

}