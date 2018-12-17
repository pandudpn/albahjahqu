<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class eva_corporate_mutation_model extends MY_Model {

	protected $table        = 'corporate_mutations';
    protected $key          = 'id';
    protected $date_format  = 'datetime';
    protected $set_created  = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function insert($data=null)
	{
		$eva = $this->load->database('eva', TRUE);

		if ($this->_function_check(FALSE, $data) === FALSE)
		{
			return FALSE;
		}
	
		// Add the created field
		if ($this->set_created === TRUE && !array_key_exists('created', $data))
		{
			$data['created_on'] = date("Y-m-d H:i:s");
		}
		
		// Insert it
		$status = $eva->insert($this->table, $data);
		
		if ($status != FALSE)
		{
			return $eva->insert_id();
		} else
		{
			$this->error = mysqli_error();
			return false;
		}

    }
    
    public function update($id=null, $data=null)
	{
		$eva = $this->load->database('eva', TRUE);

		if ($this->_function_check($id, $data) === FALSE)
		{
			return FALSE;
		}
		
		// Add the modified field
		if ($this->set_modified === TRUE && !array_key_exists('modified_on', $data))
		{
			$data['modified_on'] = date("Y-m-d H:i:s");
		}
	
		$eva->where($this->key, $id);
		if ($eva->update($this->table, $data))
		{
			return true;
		}
	
		return false;
	}

}