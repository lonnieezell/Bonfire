<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2012, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Bonfire Base Model
 *
 * The Base model implements standard CRUD functions that can be
 * used and overriden by module models. This helps to maintain
 * a standard interface to program to, and makes module creation
 * faster.
 *
 * @package    Bonfire
 * @subpackage MY_Model
 * @category   Models
 * @author     Lonnie Ezell
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class BF_Model extends CI_Model
{

	/**
	 * Stores custom errors that can be used in UI error reporting.
	 *
	 * @var string
	 */
	public $error 		= '';

	/**
	 * The name of the db table this model primarily uses.
	 *
	 * @var string
	 * @access protected
	 */
	protected $table 	= '';

	/**
	 * The primary key of the table. Used as the 'id' throughout.
	 *
	 * @var string
	 * @access protected
	 */
	protected $key		= 'id';

	/**
	 * Field name to use to the created time column in the DB table.
	 *
	 * @var string
	 * @access protected
	 */
	protected $created_field = 'created_on';

	/**
	 * Field name to use to the modified time column in the DB table.
	 *
	 * @var string
	 * @access protected
	 */
	protected $modified_field = 'modified_on';

	/**
	 * Whether or not to auto-fill a 'created_on' field on inserts.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $set_created	= TRUE;

	/**
	 * Whether or not to auto-fill a 'modified_on' field on updates.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $set_modified = TRUE;
	
	/*
		Var: $log_user
		If TRUE, will log user id for 'created_by', 'modified_by' and 'deleted_by'.
		
		Access:
			Protected
	*/
	protected $log_user = FALSE;
	
	/*
		Var: $created_by_field
		Field name to use to the created by column in the DB table.

		Access:
			Protected
	*/
	protected $created_by_field = 'created_by';

	/*
		Var: $modified_by_field
		Field name to use to the modified by column in the DB table.

		Access:
			Protected
	*/
	protected $modified_by_field = 'modified_by';
	
	/*
		Var: $deleted_by_field
		Field name to use for the deleted by column in the DB table.

		Access:
			Protected
	*/
	protected $deleted_by_field = 'deleted_by';

	/**
	 * The type of date/time field used for created_on and modified_on fields.
	 * Valid types are: 'int', 'datetime', 'date'
	 *
	 * @var string
	 * @access protected
	 */
	protected $date_format = 'int';

	/**
	 * If FALSE, the delete() method will perform a TRUE delete of that row.
	 * If TRUE, a 'deleted' field will be set to 1.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $soft_deletes = FALSE;

	/**
	 * Stores any selects here for use by the find* functions.
	 *
	 * @var string
	 * @access protected
	 */
	protected $selects = '';

	/*
	Var: $escape
	If FALSE, the select() method will not try to protect your field or table names with backticks.
	This is useful if you need a compound select statement.

	Access:
		Protected
	*/
	protected $escape = TRUE;

	//---------------------------------------------------------------

	/**
	 * Setup the DB connection if it doesn't exist
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		// If we're loading the model, then we probably need the
		// database, so make sure it's loaded.
		if (!isset($this->db))
		{
			$this->load->database();
		}
	}

	//---------------------------------------------------------------

	/*
		Method: find()

		Searches for a single row in the database.

		Parameter:
			$id		- The primary key of the record to search for.

		Return:
			An object representing the db row, or FALSE.
	*/
	public function find($id='')
	{
		if ($this->_function_check($id) === FALSE)
		{
			return FALSE;
		}

		$this->set_selects();

		$query = $this->db->get_where($this->table, array($this->table.'.'. $this->key => $id));

		if ($query->num_rows())
		{
			return $query->row();
		}

		return FALSE;
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
			return FALSE;
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
		return FALSE;
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
	public function find_all_by($field=NULL, $value=NULL)
	{
		if (empty($field)) return FALSE;

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
			return FALSE;
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

		return FALSE;
	}

	//---------------------------------------------------------------

	/*
		Method: insert()

		Inserts a row of data into the database.

		Parameters:
			$data	- an array of key/value pairs to insert.

		Return:
			Either the $id of the row inserted, or FALSE on failure.
	*/
	public function insert($data=NULL)
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
		
		if ($this->set_created === TRUE && $this->log_user === TRUE && !array_key_exists($this->created_by_field, $data))
		{
			$data[$this->created_by_field] = $this->auth->user_id();
		}

		// Insert it
		$status = $this->db->insert($this->table, $data);

		if ($status != FALSE)
		{
			return $this->db->insert_id();
		} else
		{
			$this->error = mysql_error();
			return FALSE;
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
			TRUE/FALSE
	*/
	public function update($id=NULL, $data=NULL)
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
		
		if ($this->set_modified === TRUE && $this->log_user === TRUE && !array_key_exists($this->modified_by_field, $data))
		{
			$data[$this->modified_by_field] = $this->auth->user_id();
		}

		$this->db->where($this->key, $id);
		if ($this->db->update($this->table, $data))
		{
			return TRUE;
		}

		return FALSE;
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
			TRUE/FALSE
	*/
	public function update_where($field=NULL, $value=NULL, $data=NULL)
	{
		if (empty($field) || empty($value) || !is_array($data))
		{
			$this->error = $this->lang->line('bf_model_no_data');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_no_data'));
			return FALSE;
		}

		return $this->db->update($this->table, $data, array($field => $value));
	}

	//---------------------------------------------------------------

	/*
		Method: update_batch()

		Updates a batch of existing rows in the database.

		Parameters:
			$data	- An array of key/value pairs to update.
			$index	- A string value of the db column to use as the where key

		Return:
			TRUE/FALSE
	*/
	public function update_batch($data = NULL, $index = NULL)
	{
		if (is_null($index))
		{
			return FALSE;
		}

		if (!is_null($data))
		{
			// Add the modified field
			if ($this->set_modified === TRUE && !array_key_exists($this->modified_field, $data))
			{
				foreach ($data as $key => $record)
				{
					$data[$key][$this->modified_field] = $this->set_date();
				}
			}

			$result = $this->db->update_batch($this->table, $data, $index);
			if (empty($result))
			{
				return TRUE;
			}
		}

		return FALSE;
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
			TRUE/FALSE
	 */
	public function delete($id=NULL)
	{
		if ($this->_function_check($id) === FALSE)
		{
			return FALSE;
		}

		if ($this->find($id) !== FALSE)
		{
			if ($this->soft_deletes === TRUE)
			{
				$data = array(
					'deleted'	=> 1
				);
				
				if ($this->log_user === TRUE && !array_key_exists($this->deleted_by_field, $data))
				{
					$data[$this->deleted_by_field] = $this->auth->user_id();
				}
			
				$this->db->where($this->key, $id);
				$result = $this->db->update($this->table, $data);
			}
			else
			{
				$result = $this->db->delete($this->table, array($this->key => $id));
			}

			if ($result)
			{
				return TRUE;
			}

			$this->error = $this->lang->line('bf_model_db_error') . mysql_error();
		}
		else
		{
			$this->error = $this->lang->line('bf_model_db_error') . $this->lang->line('bf_model_invalid_id');
		}

		return FALSE;
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
			TRUE/FALSE
	*/
	public function delete_where($data=NULL)
	{
		if (empty($data))
		{
			$this->error = $this->lang->line('bf_model_no_data');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_no_data'));
			return FALSE;
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

		$this->error = $this->lang->line('bf_model_db_error') . mysql_error();

		return FALSE;
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
			TRUE/FALSE
	*/
	public function is_unique($field='', $value='')
	{
		if (empty($field) || empty($value))
		{
			$this->error = $this->lang->line('bf_model_unique_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_unique_error'));
			return FALSE;
		}

		$this->db->where($field, $value);
		$query = $this->db->get($this->table);

		if ($query && $query->num_rows() == 0)
		{
			return TRUE;
		}

		return FALSE;
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
		return $this->db->count_all_results($this->table);
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
			return FALSE;
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
	public function get_field($id=NULL, $field='')
	{
		if (empty($id) || $id === 0 || empty($field))
		{
			$this->error = $this->lang->line('bf_model_fetch_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_fetch_error'));
			return FALSE;
		}

		$this->db->select($field);
		$this->db->where($this->key, $id);
		$query = $this->db->get($this->table);

		if ($query && $query->num_rows() > 0)
		{
			return $query->row()->$field;
		}

		return FALSE;
	}

	//---------------------------------------------------------------

	/*
		Method: format_dropdown()

		A convenience method to return options for form dropdown menus.

		Parameters:
			Can pass either Key ID and Label Table names or Just Label Table name.

		Return:
			array The options for the dropdown.
	*/
	function format_dropdown()
	{
		$args = & func_get_args();

		if (count($args) == 2)
		{
			list($key, $value) = $args;
		}
		else
		{
			$key = $this->key;
			$value = $args[0];
		}

		$query = $this->db->select(array($key, $value))->get($this->table);

		$options = array();
		foreach ($query->result() as $row)
		{
			$options[$row->{$key}] = $row->{$value};
		}

		return $options;
	}

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
	public function where($field=NULL, $value=NULL)
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
	public function select($selects=NULL, $escape=NULL)
	{
		if (!empty($selects))
		{
			$this->selects = $selects;
		}
		if ($escape === FALSE)
		{
			$this->escape = $escape;
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
		Method: join()

		Generates the JOIN portion of the query.

		Parameters:
			$table	- A string containing the table name.
			$cond	- A string with the join condiction.
			$type	- A string containing the type of join - INNER, OUTER etc.

		Return:
			An instance of this class.
	*/
	public function join($table, $cond, $type = '')
	{
		$this->db->join($table, $cond, $type);

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
	public function order_by($field=NULL, $order='asc')
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
			return FALSE;
		}

		// Check the ID, but only if it's a non-FALSE value
		if ($id !== FALSE)
		{
			if (empty($id) || $id == 0)
			{
				$this->error = $this->lang->line('bf_model_invalid_id');
				$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_invalid_id'));
				return FALSE;
			}
		}

		// Check the data
		if ($data !== FALSE)
		{
			if (!is_array($data) || count($data) == 0)
			{
				$this->error = $this->lang->line('bf_model_no_data');
				$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_no_data'));
				return FALSE;
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

		return TRUE;
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
	protected function set_date($user_date=NULL)
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
			$modified	- TRUE/FALSE
	*/
	public function set_modified($modified=TRUE)
	{
		$this->set_modified = $modified;
	}

	//--------------------------------------------------------------------

	/*
		Method: set_soft_deletes()

		Sets whether soft deletes are used by the delete method.

		Parameters:
			$soft	- TRUE/FALSE
	*/
	public function set_soft_deletes($soft=TRUE)
	{
		$this->soft_deletes = $soft;
	}

	//--------------------------------------------------------------------

	/*
		Method: set_selects()

		Takes the string in $this->selects, if not empty, and sets it
		with the ActiveRecord db class. If $this->escape is FALSE it
		will not try to protect your field or table names with backticks.

		Clears the string afterword
		to make sure it's clean for the next call.

		Access:
			Private
	*/
	protected function set_selects()
	{
		if (!empty($this->selects) && $this->escape === FALSE)
		{
			$this->db->select($this->selects, FALSE);

			// Clear it out for the next process.
			$this->selects = NULL;
			$this->escape = NULL;
		}
		elseif (!empty($this->selects))
		{
			$this->db->select($this->selects);

			// Clear it out for the next process.
			$this->selects = NULL;
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
	protected function logit($message='', $level='debug')
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