<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

/*
	Class: BF_Model
	
	The Base model implements standard CRUD functions that can be
	used and overriden by module models. This helps to maintain
	a standard interface to program to, and makes module creation
	faster.
	
	Author:
		Lonnie Ezell
 */
class BF_Model extends CI_Model {
	
	/*
		Var: $error
		Stores custom errors that can be used in UI error reporting.
	*/
	public $error 		= '';
	
	/*
		Var: $table
		The name of the db table this model primarily uses.
		
		Access:
			Protected
	*/
	protected $table 	= '';
	
	/*
		Var: $key
		The primary key of the table. Used as the 'id' throughout.
		
		Access:
			Protected
	*/
	protected $key		= 'id';
	
	/*
		Var: $created_field
		Field name to use to the created time column in the DB table.
		
		Access:
			Protected
	*/
	protected $created_field = 'created_on';
	
	/*
		Var: $modified_field
		Field name to use to the modified time column in the DB table.
		
		Access:
			Protected
	*/
	protected $modified_field = 'modified_on';
	
	/*
		Var: $set_created
		Whether or not to auto-fill a 'created_on' field on inserts.
		
		Access: 
			Protected
	*/
	protected $set_created	= TRUE;
	
	/*
		Var: $set_modified
		Whether or not to auto-fill a 'modified_on' field on updates.
		
		Access:
			Protected
	*/
	protected $set_modified = TRUE;
	
	/*
		Var: $date_format
		The type of date/time field used for created_on and modified_on fields. 
		Valid types are: 'int', 'datetime', 'date'
		
		Access: 
			protected
	*/
	protected $date_format = 'int';
	
	/*
		Var: $soft_deletes
		If false, the delete() method will perform a true delete of that row.
		If true, a 'deleted' field will be set to 1.
		
		Access:
			Protected
	*/
	protected $soft_deletes = FALSE;
	
	/*
		Var: $selects
		Stores any selects here for use by the find* functions.
		
		Access:
			Protected
	*/
	protected $selects = '';
	
	//---------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();
	}
	
	//---------------------------------------------------------------
	
	/*
		Method: find()
		
		Searches for a single row in the database.
		
		Parameter:
			$id		- The primary key of the record to search for.
			
		Return:
			An object representing the db row, or false.
	*/
	public function find($id='')
	{
		if ($this->_function_check($id) === FALSE)
		{
			return false;
		}
		
		$this->set_selects();
		
		$query = $this->db->get_where($this->table, array($this->table.'.'. $this->key => $id));

		if ($query->num_rows())
		{
			return $query->row();
		}
		
		return false;
	}
	
	//---------------------------------------------------------------
	
	/*
		Method: find_all()
		
		Returns all records in the table. 
		
		By default, there is no 'where' clause, but you can filter
		the results that are returned by using either CodeIgniter's
		Active Record functions before calling this function, or 
		through method chaining with the where() method of this class.
		
		Return:
			An array of objects representing the results, or FALSE on failure or empty set.
	*/
	public function find_all()
	{
		if ($this->_function_check() === FALSE)
		{
			return false;
		}
		
		$this->set_selects();
		
		$this->db->from($this->table);
		
		$query = $this->db->get();
		
		if (!empty($query) && $query->num_rows() > 0)
		{
			return $query->result();
		}
		
		$this->error = $this->lang->line('bf_model_bad_select');
		$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_bad_select'));
		return false;
	}
	
	//---------------------------------------------------------------
	
	/*
		Method: find_all_by()
		
		A convenience method that combines a where() and find_all()
		call into a single call.
		
		Paremeters:
			$field	- The table field to search in.
			$value	- The value that field should be.
			
		Return:
			An array of objects representing the results, or FALSE on failure or empty set.
	*/
	public function find_all_by($field=null, $value=null) 
	{		
		if (empty($field)) return false;

		$this->set_selects();

		// Setup our field/value check
		$this->db->where($field, $value);
		
		return $this->find_all();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: find_by()
		
		Returns the first result that matches the field/values passed.
		
		Parameters:
			$field	- Either a string or an array of fields to match against.
					  If an array is passed it, the $value parameter is ignored
					  since the array is expected to have key/value pairs in it.
			$value	- The value to match on the $field. Only used when $field is a string.
			$type	- The type of where clause to create. Either 'and' or 'or'. 
			
		Return: 
			An object representing the first result returned.
	*/
	public function find_by($field='', $value='', $type='and')
	{
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
		
		$this->set_selects();
		
		$query = $this->db->get($this->table);
		
		if ($query && $query->num_rows() > 0)
		{
			return $query->row();
		}
		
		return false;
	}
	
	//---------------------------------------------------------------
	
	/*
		Method: insert()
		
		Inserts a row of data into the database.
		
		Parameters:
			$data	- an array of key/value pairs to insert.
			
		Return:
			Either the $id of the row inserted, or false on failure.
	*/
	public function insert($data=null)
	{
		if ($this->_function_check(FALSE, $data) === FALSE)
		{
			return FALSE;
		}
	
		// Add the created field
		if ($this->set_created === TRUE && !array_key_exists($this->created_field, $data))
		{
			$data[$this->created_field] = $this->set_date();
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
	
	/*
		Method: update()
		
		Updates an existing row in the database.
		
		Parameters:
			$id		- The primary_key value of the row to update.
			$data	- An array of key/value pairs to update.
			
		Return:
			true/false
	*/
	public function update($id=null, $data=null)
	{
		
		if ($this->_function_check($id, $data) === FALSE)
		{
			return FALSE;
		}
		
		// Add the modified field
		if ($this->set_modified === TRUE && !array_key_exists($this->modified_field, $data))
		{
			$data[$this->modified_field] = $this->set_date();
		}
	
		$this->db->where($this->key, $id);
		if ($this->db->update($this->table, $data))
		{
			return true;
		}
	
		return false;
	}
	
	//---------------------------------------------------------------
	
	/*
		Method: update_where()
		
		A convenience method that allows you to use any field/value pair
		as the 'where' portion of your update.
		
		Parameters:
			$field	- The field to match on.
			$value	- The value to search the $field for.
			$data	- An array of key/value pairs to update.
			
		Return:
			true/false
	*/
	public function update_where($field=null, $value=null, $data=null) 
	{
		if (empty($field) || empty($value) || !is_array($data))
		{
			$this->error = $this->lang->line('bf_model_no_data');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_no_data'));
			return false;
		}
			
		return $this->db->update($this->table, $data, array($field => $value));
	}
	
	//--------------------------------------------------------------------
	
	
	/*
		Method: delete()
		
		Performs a delete on the record specified. If $this->soft_deletes is TRUE,
		it will attempt to set a field 'deleted' on the current record
		to '1', to allow the data to remain in the database.
		
		Parameters:
			$id		- The primary_key value to match against.
			
		Return:
			true/false
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
			$result = $this->db->update($this->table, array('deleted' => 1));
		} 
		else 
		{
			$result = $this->db->delete($this->table, array($this->key => $id));
		}

		if ($result)
		{
			return true;
		} 
		
		$this->error = 'DB Error: ' . mysql_error();
	
		return false;
	}
	
	//---------------------------------------------------------------
	
	/*
		Method: delete_where()
		
		Performs a delete using any field/value pair(s) as the 'where'
		portion of your delete statement. If $this->soft_deletes is 
		TRUE, it will attempt to set a field 'deleted' on the current
		record to '1', to allow the data to remain in the database.
		
		Parameters:
			$data	- key/value pairs accepts an associative 
					  array or a string
					
				ie.  1) array( 'key' => 'value', 'key2' => 'value2' ) 
				     2) ' (`key` = "value" AND `key2` = "value2") '
			
		Return:
			true/false
	*/
	public function delete_where($data=null) 
	{
		if (empty($data))
		{
			$this->error = $this->lang->line('bf_model_no_data');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_no_data'));
			return false;
		}
		
		if (is_array($data))
		{
			foreach($data as $field => $value) 
			{
				$this->db->where($field,$value);
			}			
		}
		else
		{
			$this->db->where($data);	
		}
	
		if ($this->soft_deletes === TRUE)
		{
			$this->db->update($this->table, array('deleted' => 1));
		} 
		else 
		{
			$this->db->delete($this->table);
		}

		$result = $this->db->affected_rows();

		if ($result)
		{
			return $result;
		} 
		
		$this->error = 'DB Error: ' . mysql_error();
	
		return false;
	}
	
	//---------------------------------------------------------------
	
	//---------------------------------------------------------------
	// HELPER FUNCTIONS
	//---------------------------------------------------------------
	
	/*
		Method: is_unique()
		
		Checks whether a field/value pair exists within the table.
		
		Parameters:
			$field	- The field to search for.
			$value	- The value to match $field against.
			
		Return:
			true/false
	*/
	public function is_unique($field='', $value='')
	{
		if (empty($field) || empty($value))
		{
			$this->error = $this->lang->line('bf_model_unique_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_unique_error'));
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
	
	/*
		Method: count_all()
		
		Returns the number of rows in the table.
		
		Return:
			int
	*/
	public function count_all()
	{
		return $this->db->count_all($this->table);
	}
	
	//---------------------------------------------------------------
	
	/*
		Method: count_by()
		
		Returns the number of elements that match the field/value pair.
		
		Parameters:
			$field	- The field to search for.
			$value	- The value to match $field against.
			
		Return:
			int
	*/
	public function count_by($field='', $value=NULL) 
	{
		if (empty($field))
		{
			$this->error = $this->lang->line('bf_model_count_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_count_error'));
			return false;
		}
		
		$this->set_selects();
		
		$this->db->where($field, $value);
		
		return (int)$this->db->count_all_results($this->table);
	}
	
	//---------------------------------------------------------------
	
	/*
		Method: get_field()
		
		A convenience method to return only a single field of the specified row.
		
		Parameters:
			$field	- The field to search for.
			$value	- The value to match $field against.
			
		Return:
			The value of the field.
	*/
	public function get_field($id=null, $field='') 
	{
		if (!is_numeric($id) || $id === 0 || empty($field))
		{
			$this->error = $this->lang->line('bf_model_fetch_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_fetch_error'));
			return false;
		}
		
		$this->db->select($field);
		$this->db->where($this->key, $id);
		$query = $this->db->get($this->table);
		
		if ($query && $query->num_rows() > 0)
		{
			return $query->row()->$field;
		}
		
		return false;
	}
	
	//---------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !CHAINABLE UTILITY METHODS
	//--------------------------------------------------------------------
	
	/*
		Method: where()
		
		Sets the where portion of the query in a chainable format.
		
		Parameters:
			$field	- The field to search the db on. Can be either a string with the field
					  name to search, or an associative array of key/value pairs. 
			$value	- The value to match the field against. If $field is an array,
					  this value is ignored.
			
		Return:
			An instance of this class.
	*/
	public function where($field=null, $value=null) 
	{
		if (!empty($field))
		{
			if (is_string($field))
			{
				$this->db->where($field, $value);
			} 
			else if (is_array($field))
			{
				$this->db->where($field);
			}
		}
		
		return $this;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: select()
		
		Sets the select portion of the query in a chainable format. The value
		is stored for use in the find* methods so that child classes can
		have more flexibility in joins and what is selected.
		
		Parameters:
			$selects	- A string representing the selection.
			
		Return:
			An instance of this class.
	*/
	public function select($selects=null) 
	{
		if (!empty($selects))
		{		
			$this->selects = $selects;
		}
		
		return $this;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: limit()
		
		Sets the limit portion of the query in a chainable format.
		
		Parameters:
			$limit	- An int showing the max results to return.
			$offset	- An in showing how far into the results to start returning info.
			
		Return:
			An instance of this class.
	*/
	public function limit($limit=0, $offset=0) 
	{
		$this->db->limit($limit, $offset);
		
		return $this;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: order_by()
		
		Inserts a chainable order_by method from either a string or an
		array of field/order combinations. If the $field value is an array,
		it should look like:
		 
		 array(
		 	'field1' => 'asc',
		 	'field2' => 'desc'
		 );
		 
		 Parameters:
		 	$field	- The field to order the results by.
		 	$order	- Which direction to order the results ('asc' or 'desc')
		 	
		 Return:
		 	An instance of this class.
	 */
	public function order_by($field=null, $order='asc') 
	{
		if (!empty($field))
		{
			if (is_string($field))
			{
				$this->db->order_by($field, $order);
			}
			else if (is_array($field))
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
	// !UTILITY FUNCTIONS
	//---------------------------------------------------------------
	
	/*
		Method: _function_check()
		
		A utility method that does some error checking and cleanup for other methods:
		
		- Makes sure that a table has been set at $this->table.
		- If passed in, will make sure that $id is of the valid type.
		- If passed in, will verify the $data is not empty.
	*/
	protected function _function_check($id=FALSE, &$data=FALSE) 
	{
		// Does the model have a table set?
		if (empty($this->table))
		{
			$this->error = $this->lang->line('bf_model_no_table');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_no_table'), 'error');
			return false;
		}
		
		// Check the ID, but only if it's a non-FALSE value
		if ($id !== FALSE)
		{
			if (!is_numeric($id) || $id == 0)
			{
				$this->error = $this->lang->line('bf_model_invalid_id');
				$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_invalid_id'));
				return false;
			}
		}
		
		// Check the data
		if ($data !== FALSE)
		{
			if (!is_array($data) || count($data) == 0)
			{
				$this->error = $this->lang->line('bf_model_no_data');
				$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_no_data'));
				return false;
			}
		}

		return true;
	}
    
    //---------------------------------------------------------------
	
	/*
		Method: set_date()
		
		A utility function to allow child models to use the type of
		date/time format that they prefer. This is primarily used for
		setting created_on and modified_on values, but can be used by
		inheriting classes.
		
		The available time formats are:
			'int'		- Stores the date as an integer timestamp.
			'datetime'	- Stores the date and time in the SQL datetime format.
			'date'		- Stores teh date (only) in the SQL date format.
			
		Parameters:
			$user_date	- An optional PHP timestamp to be converted.
			
		Return:
			The current/user time converted to the proper format.
	*/
	protected function set_date($user_date=null) 
	{
		$curr_date = !empty($user_date) ? $user_date : time();
		
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
	
	/*
		Method: set_table()
		
		Allows you to set the table to use for all methods during runtime.
		
		Parameters:
			$table	- The table name to use (do not include the prefix!)
	*/
	public function set_table($table='') 
	{
		$this->table = $table;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: get_table()
		
		Allows you to get the table name 
		
		Parameters:
			none
		
		Returns:
			string $this->table (current model table name)
	*/
	public function get_table() 
	{
		return $this->table;
	}
	
	//--------------------------------------------------------------------

	/*
		Method: set_date_format()
		
		Sets the date_format to use for setting created_on and modified_on values.
		
		Parameters:
			$format	- String describing format.
						Valid values are: 'int', 'datetime', 'date'
	*/
	public function set_date_format($format='int') 
	{
		$this->date_format = $format;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: set_modified()
		
		Sets whether to auto-create modified_on dates in the update method.
		
		Parameters:
			$modified	- true/false
	*/
	public function set_modified($modified=true) 
	{
		$this->set_modified = $modified;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: set_soft_deletes()
		
		Sets whether soft deletes are used by the delete method.
		
		Parameters:
			$soft	- true/false
	*/
	public function set_soft_deletes($soft=true) 
	{
		$this->soft_deletes = $soft;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: set_selects()
		
		Takes the string in $this->selects, if not empty, and sets it
		with the ActiveRecord db class. Clears the string afterword
		to make sure it's clean for the next call.
		
		Access:
			Private
	*/
	private function set_selects() 
	{
		if (!empty($this->selects))
		{
			$this->db->select($this->selects);
		
			// Clear it out for the next process.
			$this->selects = null;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: logit()
		
		Logs an error to the Console (if loaded) and to the log files.
		
		Parameters:
			$message	- The string to write to the logs.
			$level		- The log level, as per CI log_message method.
	*/
	private function logit($message='', $level='debug') 
	{
		if (empty($message))
		{
			return;
		}
		
		if (class_exists('Console'))
		{
			Console::log($message);
		}
		
		log_message($level, $message);
	}
	
	//--------------------------------------------------------------------
	
} 

// END: Class BF_model

//--------------------------------------------------------------------

/*
	Class: MY_Model
	
	This simply extends BF_Model for backwards compatibility, 
	and to provide a placeholder class that your project can customize
	extend as needed.
*/
class MY_Model extends BF_Model { }

// END: Class MY_model

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */