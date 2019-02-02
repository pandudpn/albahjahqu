<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class eva_customer_model extends MY_Model {

	protected $table        = 'customers';
    protected $key          = 'id';
    protected $date_format  = 'datetime';
    protected $set_created  = true;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function find($id='')
	{
		$eva = $this->load->database('eva', TRUE);

		if ($this->_function_check($id) === FALSE)
		{
			return false;
		}
		
		$query = $eva->get_where($this->table, array($this->table.'.'. $this->key => $id));

		if ($query->num_rows())
		{
			return $query->row();
		}
		
		return false;
	}

	public function find_by($field='', $value='', $type='and')
	{
		$eva = $this->load->database('eva', TRUE);

		if (empty($field) || (!is_array($field) && empty($value)))
		{
			$this->error = $this->lang->line('bf_model_find_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_find_error'));
			return false;
		}
	
		if (is_array($field))
		{
			foreach ($field as $key => $value)
			{
				if ($type == 'or')
				{
					$eva->or_where($key, $value);
				}
				else
				{
					$eva->where($key, $value);
				}
			}
		}
		else
		{
			$eva->where($field, $value);
		}
		
		$query = $eva->get($this->table);
		
		if ($query && $query->num_rows() > 0)
		{
			return $query->row();
		}
		
		return false;
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

	public function update_where($field=null, $value=null, $data=null) 
	{
		$eva = $this->load->database('eva', TRUE);

		if (empty($field) || empty($value) || !is_array($data))
		{
			$this->error = $this->lang->line('bf_model_no_data');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_no_data'));
			return false;
		}
			
		return $eva->update($this->table, $data, array($field => $value));
	}
}
