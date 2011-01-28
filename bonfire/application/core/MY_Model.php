<?php
/**
 * Base_Model
 *
 * The Base model implements standard CRUD functions that can be
 * used and overriden by module models. This helps to maintain
 * a standard interface to program to, and makes module creation
 * faster.
 *
 * Database functions make it easy to keep the database structure
 * up to date, and even install the tables initially. All tables
 * are assumed to have a field called 'id', unless $use_id = false.
 * 
 * The model will handle validation of the the objects (through the
 * validate() method) and will use the $rules array.
 *
 *
 * @author Lonnie Ezell
 * @version 1.2
 */
class MY_Model extends CI_Model {
	
	public $error 		= '';		// Stores custom errors 
	
	protected $table 	= '';		// The name of the database table.
	
	protected $key		= 'id';		// the primary key of the table. Used as the 'id' throughout.
	
	protected $set_created	= TRUE;	// Whether or not to auto-create and fill a 'created_on' field 
	protected $set_modified = TRUE;	// Whether or not to auto-create and fill a 'modified_on' field
	
	protected $date_format = 'int';	// Valid types: 'int', 'datetime', 'date'
	
	protected $soft_deletes = FALSE;
	
	//---------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();
	}
	
	//---------------------------------------------------------------
	
	public function find($id='')
	{
		if ($this->_function_check($id) === FALSE)
		{
			return false;
		}
		
		$query = $this->db->get_where($this->table, array($this->table.'.'. $this->key => $id));
		
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		
		return false;
	}
	
	//---------------------------------------------------------------
	
	public function find_all()
	{
		if ($this->_function_check() === FALSE)
		{
			return false;
		}
		
		$this->db->from($this->table);
		
		$query = $this->db->get();
		
		if (!empty($query) && $query->num_rows() > 0)
		{
			return $query->result();
		}
		
		$this->error = 'Invalid selection.';
		return false;
	}
	
	//---------------------------------------------------------------
	
	public function find_all_by($field=null, $value='') 
	{
		if ($this->_function_check() === FALSE)
		{
			return FALSE;
		}
		
		if (empty($field)) return false;

		// Setup our field/value check
		$this->db->where($field, $value);
		
		$this->db->from($this->table);
		
		$query = $this->db->get();
		
		if (!empty($query) && $query->num_rows() > 0)
		{
			return $query->result();
		}
		
		$this->error = 'DB Error: '. mysql_error();
		return false;
	}
	
	//--------------------------------------------------------------------
	
	
	public function find_by($field='', $value='', $type='and')
	{
		if (empty($field) || (!is_array($field) && empty($value)))
		{
			$this->error = 'Not enough information to find by.';
			return false;
		}
	
		if (is_array($field))
		{
			foreach ($field as $key => $value)
			{
				if ($type == 'or')
				{
					$this->db->or_where($key, $value);
				}
				else
				{
					$this->db->where($key, $value);
				}
			}
		}
		else
		{
			$this->db->where($field, $value);
		}
		
		$query = $this->db->get($this->table);
		
		if ($query && $query->num_rows() > 0)
		{
			return $query->result();
		}
		
		return false;
	}
	
	//---------------------------------------------------------------
	
	// $fieldsvalues is an associative array
	
	public function find_by_array($fieldsvalues, $selects=null)
	{
		if (empty($fieldsvalues))
		{
			$this->error = 'Not enough information to find by.';
			return false;
		}
		
		$this->db->where($fieldsvalues);
		$query = $this->db->get($this->table);
		
		if ($query && $query->num_rows() > 0)
		{
			return $query->result();
		}
		
		return false;
	}	
	
	//---------------------------------------------------------------
	
	public function insert($data=null)
	{
		if ($this->_function_check(FALSE, $data) === FALSE)
		{
			return FALSE;
		}
	
		// Add the created field
		if ($this->set_created === TRUE && !array_key_exists('created', $data))
		{
			$data['created_on'] = $this->set_date();
		}
		
		// Insert it
		$status = $this->db->insert($this->table, $data);
		
		if ($status != FALSE)
		{
			return $this->db->insert_id();
		} else
		{
			$this->error = mysql_error();
			return false;
		}

	}
	
	//---------------------------------------------------------------
	
	public function update($id=null, $data=null)
	{
		
		if ($this->_function_check($id, $data) === FALSE)
		{
			return FALSE;
		}
		
		// Add the modified field
		if ($this->set_modified === TRUE && !array_key_exists('modified_on', $data))
		{
			$data['modified_on'] = $this->set_date();
		}
	
		$this->db->where($this->key, $id);
		if ($this->db->update($this->table, $data))
		{
			return true;
		}

	}
	
	//---------------------------------------------------------------
	
	public function update_where($field=null, $value=null, $data=null) 
	{
		if (empty($field) || empty($value) || !is_array($data))
		{
			$this->error = 'Not enough data.';
			return false;
		}
			
		return $this->db->update($this->table, $data, array($field => $value));
	}
	
	//--------------------------------------------------------------------
	
	
	/**
	 * delete()
	 *
	 * Performs a delete on the record specified. If $this->soft_deletes is TRUE,
	 * it will attempt to set a field 'deleted' on the current record
	 * to '1', to allow the data to remain in the database.
	 */
	public function delete($id=null)
	{
		if ($this->_function_check($id) === FALSE)
		{
			return FALSE;
		}
	
		if ($this->soft_deletes === TRUE)
		{
			$this->db->where($this->key, $id);
			$this->db->update($this->table, array('deleted' => 1));
		} 
		else 
		{
			$this->db->delete($this->table, array($this->key => $id));
		}

		$result = $this->db->affected_rows();

		if ($result)
		{
			return true;
		} 
		
		$this->error = 'DB Error: ' . mysql_error();
	
		return false;
	}
	
	//---------------------------------------------------------------
	
	//---------------------------------------------------------------
	// HELPER FUNCTIONS
	//---------------------------------------------------------------
	
	public function is_unique($field='', $value='')
	{
		if (empty($field) || empty($value))
		{
			$this->error = 'Not enough information to check uniqueness.';
			return false;
		}

		$this->db->where($field, $value);			
		$query = $this->db->get($this->table);
					
		if ($query && $query->num_rows() == 0)
		{
			return true;
		}
		
		return false;
	}
	
	//---------------------------------------------------------------
	
	public function count_all()
	{
		return $this->db->count_all($this->table);
	}
	
	//---------------------------------------------------------------
	
	public function count_by($field='', $value='') 
	{
		if (empty($field) || empty($value))
		{
			$this->error = 'Not enough information to count results.';
			return false;
		}
		
		$this->db->where($field, $value);
		
		return $this->db->count_all_results($this->table);
	}
	
	//---------------------------------------------------------------
	
	public function get_field($id=null, $field='') 
	{
		if (!is_numeric($id) || $id === 0 || empty($field))
		{
			$this->error = 'Not enough information to fetch field.';
			return false;
		}
		
		$this->db->select($field);
		$this->db->where($this->key, $id);
		$query = $this->db->get($this->table);
		
		if ($query && $query->num_rows() > 0)
		{
			return $query->row();
		}
		
		return false;
	}
	
	//---------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !CHAINABLE UTILITY METHODS
	//--------------------------------------------------------------------
	
	public function select($selects=null) 
	{
		if (!empty($selects))
		{		
			$this->db->select($selects);
		}
		
		return $this;
	}
	
	//--------------------------------------------------------------------
	
	public function limit($limit=0, $offset=0) 
	{
		$this->db->limit($limit, $offset);
		
		return $this;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * order_by()
	 *
	 * Inserts a chainable order_by method from either a string or an
	 * array of field/order combinations. If the $field value is an array,
	 * it should look like: 
	 *	array(
	 *		'field1' => 'asc',
	 *		'field2' => 'desc'
	 *	);
	 */
	public function order_by($field=null, $order='asc') 
	{
		if (!empty($field))
		{
			if (is_string($field))
			{
				$this->db->order_by($field, $order);
			}
			else if (is_array($order_by))
			{
				foreach ($field as $f => $o)
				{
					$this->db->order_by($f, $o);
				}
			}
		}
		
		return $this;
	}
	
	//--------------------------------------------------------------------
	
	
	//---------------------------------------------------------------
	// FILE FUNCTIONS
	//---------------------------------------------------------------
	
	public function create_file($file=null, $body=null, $ext='.php') 
	{
		if (empty($file))
		{
			$this->error = 'Unable to create file: no filename given.';
			return false;
		}
		
		if (empty($body))
		{
			// Let the user create an empty file...
			$body = '';
		}
		
		// Open the file for writing. Note that the $file should contain
		// the full path WITH the filename (minus extension)
		$handle = $this->fopen_recursive($file . $ext, 'w+');
		
		if ($handle === FALSE)
		{
			$this->error = 'Unable to open file '. $file . $ext .' for writing.';
			return false;
		}
		
		// Grab a lock
		if (flock($handle, LOCK_EX) === FALSE)
		{
			$this->error = 'Unable to acquire lock for file: ' . $file . $ext;
			fclose($handle);
			return false;
		}
		
		// Write the content to the file
		if (fwrite($handle, $body) === FALSE)
		{
			$this->error = 'Unable to write to file: ' . $file . $ext;
		}
		
		fclose($handle);
		
		if (!empty($this->error))
		{
			return false;
		}
		
		return true;
		
	}
	
	//---------------------------------------------------------------
	
	//---------------------------------------------------------------
	// !UTILITY FUNCTIONS
	//---------------------------------------------------------------
	
	protected function _function_check($id=FALSE, &$data=FALSE) 
	{
		// Does the model have a table set?
		if (empty($this->table))
		{
			$this->error = 'Model has unspecified database table.';
			return false;
		}
		
		// Check the ID, but only if it's a non-FALSE value
		if ($id !== FALSE)
		{
			if (!is_numeric($id) || $id == 0)
			{
				$this->error = 'Invalid ID passed to model.';
				return false;
			}
		}
		
		// Check the data
		if ($data !== FALSE)
		{
			if (!is_array($data) || count($data) == 0)
			{
				$this->error = 'No data available to insert.';
				return false;
			}
		}
		
		// Strip the 'submit' field, if set
		if (isset($data['submit']))
		{
			unset($data['submit']);
		}
		// Strip the 'func' field, if set
		if (isset($data['func']))
		{
			unset($data['func']);
		}
		
		return true;
	}
	
	//---------------------------------------------------------------
	
	public function fopen_recursive($path, $mode, $chmod=0755)
    {
        $directory = dirname($path);
        $file = basename($path);
        if (!is_dir($directory)) {
            if (!mkdir($directory, $chmod, 1)) {
                return FALSE;
            }
        }
        
        return fopen ($path, $mode);
    }
    
    //---------------------------------------------------------------
	
	/**
	 * set_date()
	 *
	 * A utility function to allow child models to use the type of
	 * date/time format that they prefer.
	 */
	private function set_date() 
	{
		$curr_date = time();
		
		switch ($this->date_format)
		{
			case 'int':
				return $curr_date;
				break;
			case 'datetime':
				return date('Y-m-d H:i:s', $curr_date);
				break;
			case 'date':
				return date( 'Y-m-d', $curr_date);
				break;
		}
	}
	
	//--------------------------------------------------------------------
	
} 

// END: Class Base_model

/* End of file MY_Model.php */
/* Location: ./app/core/MY_Model.php */


