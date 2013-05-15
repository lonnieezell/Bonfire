<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://cibonfire.com/docs/guides/license.html
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
 * @link       http://cibonfire.com/docs/guides/models.html
 *
 */
class BF_Model extends CI_Model
{

	/**
	 * Stores custom errors that can be used in UI error reporting.
	 *
	 * @var string
	 * @access public
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

	/**
	 * If TRUE, will log user id for 'created_by', 'modified_by', and 'deleted_by'
	 *
	 * @var bool
	 * @access protected
	 */
	protected $log_user = FALSE;

	/**
	 * Field name to use as the created by column in the DB table
	 *
	 * @var string
	 * @access protected
	 */
	protected $created_by_field = 'created_by';

	/**
	 * Field name to use as the modified by column in the DB table
	 *
	 * @var string
	 * @access protected
	 */
	protected $modified_by_field = 'modified_by';

	/**
	 * Field name to use as the deleted by column in the DB table
	 *
	 * @var string
	 * @access protected
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

	/**
	 * If FALSE, the select() method will not try to protect your field or table names with backticks.
	 * This is useful if you need a compound select statement.
	 *
	 * @var bool
	 * @access protected
	 */
	protected $escape = TRUE;

	/**
	 * DB Connection details (string or array)
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $db_con = '';

	/**
	 * Observer Arrays
	 *
	 * Each array can contain the names of callback functions within the extending model
	 * That will be called during each event.
	 *
	 * <code>
	 *	$before_insert = array('set_created', 'validate_fields');
	 * </code>
	 *
	 * $before_insert contains the names of callback functions within the extending model
	 * which will be called before the insert method.
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_insert	= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called after the insert method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_insert		= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called before the update method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_update	= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called after the update method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_update		= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called before the find method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_find		= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called after the find method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_find		= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called before the delete method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_delete	= array();

	/**
	 * Contains the names of callback functions within the extending model which will
	 * be called after the delete method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_delete		= array();

	/**
     * Protected, non-modifiable attributes
     *
     * @var array
     * @access protected
     */
    protected $protected_attributes = array();

    /**
     * By default, we return items as objects. You can change this for the
     * entire class by setting this value to 'array' instead of 'object'.
     * Alternatively, you can do it on a per-instance basis using the
     * 'as_array()' and 'as_object()' methods.
     *
     * @var string
     * @access protected
     */
    protected $return_type      = 'object';

	/**
	 * Holds the return type temporarily when using the
	 * as_array() and as_object() methods
	 *
	 * @var string
	 * @access protected
	 */
    protected $temp_return_type = NULL;

	/**
	 * BF_Model's constructor
	 *
	 * Setup the DB connection if it doesn't exist,
	 * setup the before_insert and before_update events
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// if there are specific DB connection settings used in a model
		// load the database using those settings.
		if (!empty($this->db_con)) {

			$this->db = $this->load->database($this->db_con, TRUE);
		}

		// If we're loading the model, then we probably need the
		// database, so make sure it's loaded.
		if (!isset($this->db))
		{
			$this->load->database();
		}

		// Always protect our attributes
        array_unshift($this->before_insert, 'protect_attributes');
        array_unshift($this->before_update, 'protect_attributes');

		// Check our auto-set features and make sure they are part of
		// our observer system.
		if ($this->set_created === true) array_unshift($this->before_insert, 'created_on');
		if ($this->set_modified === true) array_unshift($this->before_update, 'modified_on');

	}//end __construct()

	//---------------------------------------------------------------

	/**
	 * Searches for a single row in the database.
	 *
	 * @param string $id The primary key of the record to search for.
	 *
	 * @return mixed An object/array representing the db row, or FALSE.
	 */
	public function find($id='')
	{
		$this->trigger('before_find');

		$query = $this->db->get_where($this->table, array($this->table.'.'. $this->key => $id));

		if ( ! $query->num_rows())
		{
			return FALSE;
		}

		$return = $query->{$this->_return_type()}();

		$return = $this->trigger('after_find', $return);

		if ($this->temp_return_type == 'json')
        {
            $return = json_encode($return);
        }

        // Reset our return type
        $this->temp_return_type = $this->return_type;

		return $return;
	}//end find()

	//---------------------------------------------------------------

	/**
	 * Returns all records in the table.
	 *
	 * By default, there is no 'where' clause, but you can filter
	 * the results that are returned by using either CodeIgniter's
	 * Active Record functions before calling this function, or
	 * through method chaining with the where() method of this class.
	 *
	 * @return mixed An array of objects/arrays representing the results, or FALSE on failure or empty set.
	 */
	public function find_all()
	{
		$this->trigger('before_find');

		$query = $this->db->get($this->table);

		if (!$query->num_rows())
		{
			return FALSE;
		}

		$return = $query->{$this->_return_type(true)}();

		if (is_array($return))
		{
			foreach ($return as $key => &$row)
			{
				$row = $this->trigger('after_find', $row, ($key == count($return) - 1));
			}
		}

		if ($this->temp_return_type == 'json')
        {
            $return = json_encode($return);
        }

        // Reset our return type
        $this->temp_return_type = $this->return_type;

		return $return;
	}//end find_all()

	//---------------------------------------------------------------

	/**
	 * A convenience method that combines a where() and find_all() call into a single call.
	 *
	 * @param mixed  $field The table field to search in.
	 * @param mixed  $value The value that field should be.
	 * @param string $type  The type of where clause to create. Either 'and' or 'or'.
	 *
	 * @return bool|mixed An array of objects representing the results, or FALSE on failure or empty set.
	 */
	public function find_all_by($field=NULL, $value=NULL, $type='and')
	{
		if (empty($field))
		{
			return FALSE;
		}

		// Setup our field/value check
		if ( ! is_array($field))
		{
			$field = array($field => $value);
		}

		if ($type == 'or')
		{
			$this->db->or_where($field);
		}
		else
		{
			$this->db->where($field);
		}

		return $this->find_all();

	}//end find_all_by()

	//--------------------------------------------------------------------

	/**
	 * Returns the first result that matches the field/values passed.
	 *
	 * @param string $field Either a string or an array of fields to match against. If an array is passed it, the $value parameter is ignored since the array is expected to have key/value pairs in it.
	 * @param string $value The value to match on the $field. Only used when $field is a string.
	 * @param string $type  The type of where clause to create. Either 'and' or 'or'.
	 *
	 * @return bool|mixed An object representing the first result returned.
	 */
	public function find_by($field='', $value='', $type='and')
	{
		if (empty($field) || ( ! is_array($field) && empty($value)))
		{
			$this->error = lang('bf_model_find_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. lang('bf_model_find_error'));
			return FALSE;
		}

		$this->trigger('before_find');

		if ( ! is_array($field))
		{
			$field = array($field => $value);
		}

		if ($type == 'or')
		{
			$this->db->or_where($field);
		}
		else
		{
			$this->db->where($field);
		}

		$query = $this->db->get($this->table);

		if ( ! $query->num_rows())
		{
			return FALSE;
		}

		$return = $query->{$this->_return_type()}();

		$return = $this->trigger('after_find', $return);

		if ($this->temp_return_type == 'json')
        {
            $return = json_encode($return);
        }

		// Reset our return type
        $this->temp_return_type = $this->return_type;

        return $return;
	}//end find_by()

	//---------------------------------------------------------------

	/**
	 * Inserts a row of data into the database.
	 *
	 * @param array $data an array of key/value pairs to insert.
	 *
	 * @return bool|mixed Either the $id of the row inserted, or FALSE on failure.
	 */
	public function insert($data=NULL)
	{
		$data = $this->trigger('before_insert', $data);

		if ($this->set_created === TRUE && $this->log_user === TRUE && !array_key_exists($this->created_by_field, $data))
		{
			$data[$this->created_by_field] = $this->auth->user_id();
		}

		// Insert it
		$status = $this->db->insert($this->table, $data);

		if ($status != FALSE)
		{
			$id = $this->db->insert_id();

			$id = $this->trigger('after_insert', $id);
			return $id;
		}
		else
		{
			$this->error = $this->get_db_error_message();
			return FALSE;
		}

	}//end insert()

	//---------------------------------------------------------------

	/**
	 * Inserts a batch of data into the database.
	 *
	 * @param array $data an array of key/value pairs to insert.
	 *
	 * @return bool|mixed Either the $id of the row inserted, or FALSE on failure.
	 */
	public function insert_batch($data=NULL)
	{
		$set = array();

		// Add the created field
		if ($this->set_created === TRUE )
		{
			$set[$this->created_field] = $this->set_date();
		}

		if ($this->set_created === TRUE && $this->log_user === TRUE)
		{
			$set[$this->created_by_field] = $this->auth->user_id();
		}

		if ( ! empty($set))
		{
			foreach ($data as $key => &$record)
			{
				$record = $this->trigger('before_insert', $record);

				$data[$key] = array_merge($set,$data[$key]);
			}
		}

		// Insert it
		$status = $this->db->insert_batch($this->table, $data);

		if ($status === FALSE)
		{
			$this->error = $this->get_db_error_message();
			return FALSE;
		}

		return TRUE;

	}//end insert_batch()

	//---------------------------------------------------------------

	/**
	 * Updates an existing row in the database.
	 *
	 * @param mixed	$where	The primary_key value of the row to update or the where clause.
	 * @param array $data	An array of key/value pairs to update.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function update($where=NULL, $data=NULL)
	{
		if ( ! is_array($where))
		{
			$where = array($this->key => $where);
		}

		$data = $this->trigger('before_update', $data);

		// Add the user id if using a modified_by field
		if ($this->set_modified === TRUE && $this->log_user === TRUE && !array_key_exists($this->modified_by_field, $data))
		{
			$data[$this->modified_by_field] = $this->auth->user_id();
		}

		if ($result = $this->db->update($this->table, $data, $where))
		{
			$this->trigger('after_update', array($data, $result));
			return TRUE;
		}

		return FALSE;

	}//end update()

	//---------------------------------------------------------------

	/**
	 * A convenience method that allows you to use any field/value pair as the 'where' portion of your update.
	 *
	 * @param string $field The field to match on.
	 * @param string $value The value to search the $field for.
	 * @param array  $data  An array of key/value pairs to update.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function update_where($field=NULL, $value=NULL, $data=NULL)
	{
		return $this->update(array($field => $value), $data);
	}//end update_where()

	//---------------------------------------------------------------

	/**
	 * Updates a batch of existing rows in the database.
	 *
	 * @param array  $data  An array of key/value pairs to update.
	 * @param string $index A string value of the db column to use as the where key
	 *
	 * @return bool TRUE/FALSE
	 */
	public function update_batch($data = NULL, $index = NULL)
	{
		if (is_null($index))
		{
			return FALSE;
		}

		if ( ! is_null($data))
		{
			// Add the modified field
			if ($this->set_modified === TRUE && !array_key_exists($this->modified_field, $data))
			{
				foreach ($data as $key => $record)
				{
					$data[$key][$this->modified_field] = $this->set_date();
					if ($this->log_user === TRUE && !array_key_exists($this->modified_by_field, $data[$key]))
					{
						$data[$key][$this->modified_by_field] = $this->auth->user_id();
					}
				}
			}

			$result = $this->db->update_batch($this->table, $data, $index);
			if (empty($result))
			{
				return TRUE;
			}
		}

		return FALSE;

	}//end update_batch()

	//--------------------------------------------------------------------


	/**
	 * Performs a delete on the record specified. If $this->soft_deletes is TRUE,
	 * it will attempt to set a field 'deleted' on the current record
	 * to '1', to allow the data to remain in the database.
	 *
	 * @param mixed $id The primary_key value to match against.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function delete($id=NULL)
	{
		$this->trigger('before_delete', $id);

		// set the where clause to be used in the update/delete below
		$this->db->where($this->key, $id);

		if ($this->soft_deletes === TRUE)
		{
			$data = array(
				'deleted' => 1,
			);

			if ($this->log_user === TRUE)
			{
				$data[$this->deleted_by_field] = $this->auth->user_id();
			}

			$result = $this->db->update($this->table, $data);
		}
		else
		{
			$result = $this->db->delete($this->table);
		}

		if ($result)
		{
			$this->trigger('after_delete', $result);
			return TRUE;
		}

		$this->error = sprintf(lang('bf_model_db_error'), $this->get_db_error_message());

		return FALSE;

	}//end delete()

	//---------------------------------------------------------------

	/**
	 * Performs a delete using any field/value pair(s) as the 'where'
	 * portion of your delete statement. If $this->soft_deletes is
	 * TRUE, it will attempt to set a field 'deleted' on the current
	 * record to '1', to allow the data to remain in the database.
	 *
	 * @param mixed/array $data key/value pairs accepts an associative array or a string
	 *
	 * @example 1) array( 'key' => 'value', 'key2' => 'value2' )
	 * @example 2) ' (`key` = "value" AND `key2` = "value2") '
	 *
	 * @return bool TRUE/FALSE
	 */
	public function delete_where($where=NULL)
	{
		$where = $this->trigger('before_delete', $where);

		// set the where clause to be used in the update/delete below
		$this->db->where($where);

		if ($this->soft_deletes === TRUE)
		{
			$data = array(
				'deleted' => 1,
			);

			if ($this->log_user === TRUE)
			{
				$data[$this->deleted_by_field] = $this->auth->user_id();
			}

			$this->db->update($this->table, $data);
		}
		else
		{
			$this->db->delete($this->table);
		}

		$result = $this->db->affected_rows();

		if ($result)
		{
			$this->trigger('after_delete', $result);

			return $result;
		}

		$this->error = lang('bf_model_db_error') . $this->get_db_error_message();

		return FALSE;

	}//end delete_where()

	//---------------------------------------------------------------

	//---------------------------------------------------------------
	// HELPER FUNCTIONS
	//---------------------------------------------------------------

	/**
	 * Checks whether a field/value pair exists within the table.
	 *
	 * @param string $field The field to search for.
	 * @param string $value The value to match $field against.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function is_unique($field='', $value='')
	{
		if (empty($field) || empty($value))
		{
			$this->error = lang('bf_model_unique_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. lang('bf_model_unique_error'));
			return FALSE;
		}

		$this->db->where($field, $value);
		$query = $this->db->get($this->table);

		if ($query && $query->num_rows() == 0)
		{
			return TRUE;
		}

		return FALSE;

	}//end is_unique()

	//---------------------------------------------------------------

	/**
	 * Returns the number of rows in the table.
	 *
	 * @internal This is potentially confusing given that count_all()
	 * and count_all_results() are different methods on $this->db,
	 * with the difference being that count_all_results() is
	 * modified by previous use of where(), like(), etc., while
	 * count_all() is not
	 *
	 * @return int
	 */
	public function count_all()
	{
		return $this->db->count_all_results($this->table);

	}//end count_all()

	//---------------------------------------------------------------

	/**
	 * Returns the number of elements that match the field/value pair.
	 *
	 * @param string $field The field to search for.
	 * @param string $value The value to match $field against.
	 *
	 * @return bool|int
	 */
	public function count_by($field='', $value=NULL)
	{
		if (empty($field))
		{
			$this->error = lang('bf_model_count_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. lang('bf_model_count_error'));
			return FALSE;
		}

		$this->db->where($field, $value);

		return (int)$this->db->count_all_results($this->table);

	}//end count_by()

	//---------------------------------------------------------------

	/**
	 * A convenience method to return only a single field of the specified row.
	 *
	 * @param mixed  $id    The primary_key value to match against.
	 * @param string $field The field to search for.
	 *
	 * @return bool|mixed The value of the field.
	 */
	public function get_field($id=NULL, $field='')
	{
		if (empty($id) || empty($field))
		{
			$this->error = lang('bf_model_fetch_error');
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. lang('bf_model_fetch_error'));
			return FALSE;
		}

		$query = $this->db->select($field)
			->where($this->key, $id)
			->get($this->table);

		if ($query && $query->num_rows() > 0)
		{
			return $query->row()->$field;
		}

		return FALSE;

	}//end get_field()

	//---------------------------------------------------------------

	/**
	 * A convenience method to return options for form dropdown menus.
	 *
	 * Can pass either Key ID and Label Table names or Just Label Table name.
	 *
	 * @return array The options for the dropdown.
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

	}//end format_dropdown()

	//--------------------------------------------------------------------
	// !CHAINABLE UTILITY METHODS
	//--------------------------------------------------------------------

	/**
	 * Sets the where portion of the query in a chainable format.
	 *
	 * @param mixed  $field The field to search the db on. Can be either a string with the field name to search, or an associative array of key/value pairs.
	 * @param string $value The value to match the field against. If $field is an array, this value is ignored.
	 *
	 * @return BF_Model An instance of this class.
	 */
	public function where($field=NULL, $value=NULL)
	{
		if ( ! empty($field))
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

	}//end where()

	//--------------------------------------------------------------------

	/**
	 * Inserts a chainable order_by method from either a string or an
	 * array of field/order combinations. If the $field value is an array,
	 * it should look like:
	 *
	 * array(
	 *     'field1' => 'asc',
	 *     'field2' => 'desc'
	 * );
	 *
	 * @param mixed  $field The field to order the results by, or an array of field/order pairs.
	 * @param string $order Which direction to order the results ('asc' or 'desc')
	 *
	 * @return BF_Model An instance of this class.
	 */
	public function order_by($field=NULL, $order='asc')
	{
		if ( ! empty($field))
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

	}//end order_by()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// Scope Methods
	//--------------------------------------------------------------------

	/**
	 * Sets the value of the soft deletes flag.
	 *
	 * <code>
	 *     $this->my_model->soft_delete(true)->delete($id);
	 * </code>
	 *
	 * @param  boolean $val If TRUE, will temporarily use soft_deletes.
	 *
	 * @return BF_Model 	An instance of this class.
	 */
	public function soft_delete($val=TRUE)
	{
		$this->soft_deletes = (boolean)$val;

		return $this;
	}

	//--------------------------------------------------------------------

	/**
     * Temporarily sets our return type to an array.
     */
    public function as_array()
    {
        $this->temp_return_type = 'array';

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Temporarily sets our return type to an object.
     */
    public function as_object()
    {
        $this->temp_return_type = 'object';

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Temporarily sets our object return to a json object.
     */
    public function as_json()
    {
        $this->temp_return_type = 'json';

        return $this;
    }

    //--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !OBSERVERS
	//--------------------------------------------------------------------

	/**
	 * Sets the created on date for the object based on the
	 * current date/time and date_format. Will not overwrite existing.
	 *
	 * @param array  $row  The array of data to be inserted
	 *
	 * @return array
	 */
	public function created_on($row)
	{
		if ( ! array_key_exists($this->created_field, $row))
		{
			$row[$this->created_field] = $this->set_date();
		}

		return $row;
	} // end created_on()

	//--------------------------------------------------------------------

	/**
	 * Sets the modified_on date for the object based on the
	 * current date/time and date_format. Will not overwrite existing.
	 *
	 * @param array  $row  The array of data to be inserted
	 *
	 * @return array
	 */
	public function modified_on($row)
	{
		if ( ! array_key_exists($this->modified_field, $row))
		{
			$row[$this->modified_field] = $this->set_date();
		}

		return $row;
	}

	//--------------------------------------------------------------------


	//---------------------------------------------------------------
	// !UTILITY FUNCTIONS
	//---------------------------------------------------------------

	/**
	 * Triggers a model-specific event and call each of it's observers.
	 *
	 * @param string 	$event 	The name of the event to trigger
	 * @param mixed 	$data 	The data to be passed to the callback functions.
	 *
	 * @return mixed
	 */
	public function trigger($event, $data=false)
	{
		if ( ! isset($this->$event) || ! is_array($this->$event))
		{
			return $data;
		}

		foreach ($this->$event as $method)
		{
			if (strpos($method, '('))
			{
				preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);
				$this->callback_parameters = explode(',', $matches[3]);
			}

			$data = call_user_func_array(array($this, $method), array($data));
		}

		return $data;
	}

	//--------------------------------------------------------------------

	/**
     * Protect attributes by removing them from $row array. Useful for
     * removing id, or submit buttons names if you simply throw your $_POST
     * array at your model. :)
     *
     * @param object/array $row The value pair item to remove.
     */
    public function protect_attributes($row)
    {
        foreach ($this->protected_attributes as $attr)
        {
            if (is_object($row))
            {
                unset($row->$attr);
            }
            else
            {
                unset($row[$attr]);
            }
        }

        return $row;
    }

    //--------------------------------------------------------------------

	/**
	 * A utility function to allow child models to use the type of
	 * date/time format that they prefer. This is primarily used for
	 * setting created_on and modified_on values, but can be used by
	 * inheriting classes.
	 *
	 * The available time formats are:
	 * * 'int'		- Stores the date as an integer timestamp.
	 * * 'datetime'	- Stores the date and time in the SQL datetime format.
	 * * 'date'		- Stores teh date (only) in the SQL date format.
	 *
	 * @param mixed $user_date An optional PHP timestamp to be converted.
	 *
	 * @access protected
	 *
	 * @return int|null|string The current/user time converted to the proper format.
	 */
	protected function set_date($user_date=NULL)
	{
		$curr_date = empty($user_date) ? time() : $user_date;

		switch ($this->date_format)
		{
			case 'int':
				return $curr_date;

			case 'datetime':
				return date('Y-m-d H:i:s', $curr_date);

			case 'date':
				return date( 'Y-m-d', $curr_date);
		}

	}//end set_date()

	//--------------------------------------------------------------------

	/**
     * Return the method name for the current return type
     */
    protected function _return_type($multi = FALSE)
    {
        $method = $multi ? 'result' : 'row';

        // If our type is either 'array' or 'json', we'll simply use the array version
        // of the function, since the database library doesn't support json.
        return $this->temp_return_type == 'array' ? $method . '_array' : $method;
    }

    //--------------------------------------------------------------------

	/**
	 * Allows you to retrieve error messages from the database
	 *
	 * @return string
	 */
	protected function get_db_error_message()
	{
		switch ($this->db->platform())
		{
			case 'cubrid':
				return cubrid_errno($this->db->conn_id);
			case 'mssql':
				return mssql_get_last_message();
			case 'mysql':
				return mysql_error($this->db->conn_id);
			case 'mysqli':
				return mysqli_error($this->db->conn_id);
			case 'oci8':
				// If the error was during connection, no conn_id should be passed
				$error = is_resource($this->db->conn_id) ? oci_error($this->db->conn_id) : oci_error();
				return $error['message'];
			case 'odbc':
				return odbc_errormsg($this->db->conn_id);
			case 'pdo':
				$error_array = $this->db->conn_id->errorInfo();
				return $error_array[2];
			case 'postgre':
				return pg_last_error($this->db->conn_id);
			case 'sqlite':
				return sqlite_error_string(sqlite_last_error($this->db->conn_id));
			case 'sqlsrv':
				$error = array_shift(sqlsrv_errors());
				return !empty($error['message']) ? $error['message'] : null;
			default:
				/*
				 * !WARNING! $this->db->_error_message() is supposed to be private and
				 * possibly won't be available in future versions of CI
				 */
				return $this->db->_error_message();
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Allows you to set the table to use for all methods during runtime.
	 *
	 * @param string $table The table name to use (do not include the prefix!)
	 *
	 * @return void
	 */
	public function set_table($table='')
	{
		$this->table = $table;

	}//end set_table()

	//--------------------------------------------------------------------

	/**
	 * Allows you to get the table name
	 *
	 * @return string $this->table (current model table name)
	 */
	public function get_table()
	{
		return $this->table;

	}//end get_table()

	//--------------------------------------------------------------------

	/**
	 * Allows you to get the table primary key
	 *
	 * @return string $this->key (current model table primary key)
	 */
	public function get_key()
	{
		return $this->key;

	}//end get_key()

	//--------------------------------------------------------------------

	/**
	 * Sets the date_format to use for setting created_on and modified_on values.
	 *
	 * @param string $format String describing format. Valid values are: 'int', 'datetime', 'date'
	 *
	 * @return bool
	 */
	public function set_date_format($format='int')
	{
		if ($format != 'int' && $format != 'datetime' && $format != 'date')
		{
			return FALSE;
		}

		$this->date_format = $format;

		return TRUE;

	}//end set_date_format()

	//--------------------------------------------------------------------

	/**
	 * Sets whether to auto-create modified_on dates in the update method.
	 *
	 * @param bool $modified
	 *
	 * @return bool
	 */
	public function set_modified($modified=TRUE)
	{
		// micro-optimization note: comparison to TRUE and FALSE is faster
		// than is_bool(), because it's a function call
		// === FALSE || === TRUE is faster than !== TRUE && !== FALSE
		// because === TRUE will only be compared for values other than FALSE
		if ($modified === FALSE || $modified === TRUE)
		{
			$this->set_modified = $modified;

			return TRUE;
		}

		return FALSE;

	}//end set_modified()

	//--------------------------------------------------------------------

	/**
	 * Sets whether soft deletes are used by the delete method.
	 *
	 * @deprecated This method is deprecated as of version 0.7.
	 *
	 * @param bool $soft
	 *
	 * @return bool
	 */
	public function set_soft_deletes($soft=TRUE)
	{
		if ($modified === FALSE || $modified === TRUE)
		{
			$this->soft_deletes = $soft;

			return TRUE;
		}

		return FALSE;

	}//end set_soft_deletes()

	//--------------------------------------------------------------------

	/**
	 * Logs an error to the Console (if loaded) and to the log files.
	 *
	 * @param string $message The string to write to the logs.
	 * @param string $level   The log level, as per CI log_message method.
	 *
	 * @access protected
	 *
	 * @return mixed
	 */
	protected function logit($message='', $level='debug')
	{
		if (empty($message))
		{
			return FALSE;
		}

		if (class_exists('Console'))
		{
			Console::log($message);
		}

		log_message($level, $message);

	}//end logit()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
    // CI Database  Wrappers
    //--------------------------------------------------------------------
    // To allow for more expressive syntax, we provide wrapper functions
    // for most of the query builder methods here.
    //
    // This allows for calls such as:
    //      $result = $this->model->select('...')
    //                            ->where('...')
    //                            ->having('...')
    //                            ->get();
    //

    public function select ($select = '*', $escape = NULL) { $this->db->select($select, $escape); return $this; }
    public function select_max ($select = '', $alias = '') { $this->db->select_max($select, $alias); return $this; }
    public function select_min ($select = '', $alias = '') { $this->db->select_min($select, $alias); return $this; }
    public function select_avg ($select = '', $alias = '') { $this->db->select_avg($select, $alias); return $this; }
    public function select_sum ($select = '', $alias = '') { $this->db->select_sum($select, $alias); return $this; }
    public function distinct ($val=TRUE) { $this->db->distinct($val); return $this; }
    public function from ($from) { $this->db->from($from); return $this; }
    public function join($table, $cond, $type = '') { $this->db->join($table, $cond, $type); return $this; }
    //public function where($key, $value = NULL, $escape = TRUE) { $this->db->where($key, $value, $escape); return $this; }
    public function or_where($key, $value = NULL, $escape = TRUE) { $this->db->or_where($key, $value, $escape); return $this; }
    public function where_in($key = NULL, $values = NULL) { $this->db->where_in($key, $values); return $this; }
    public function or_where_in($key = NULL, $values = NULL) { $this->db->or_where_in($key, $values); return $this; }
    public function where_not_in($key = NULL, $values = NULL) { $this->db->where_not_in($key, $values); return $this; }
    public function or_where_not_in($key = NULL, $values = NULL) { $this->db->or_where_not_in($key, $values); return $this; }
    public function like($field, $match = '', $side = 'both') { $this->db->like($field, $match, $side); return $this; }
    public function not_like($field, $match = '', $side = 'both') { $this->db->not_like($field, $match, $side); return $this; }
    public function or_like($field, $match = '', $side = 'both') { $this->db->or_like($field, $match, $side); return $this; }
    public function or_not_like($field, $match = '', $side = 'both') { $this->db->or_not_like($field, $match, $side); return $this; }
    public function group_by($by) { $this->db->group_by($by); return $this; }
    public function having($key, $value = '', $escape = TRUE) { $this->db->having($key, $value, $escape); return $this; }
    public function or_having($key, $value = '', $escape = TRUE) { $this->db->or_having($key, $value, $escape); return $this; }
    public function limit($value, $offset = '') { $this->db->limit($value, $offset); return $this; }
    public function offset($offset) { $this->db->offset($offset); return $this; }
    public function set($key, $value = '', $escape = TRUE) { $this->db->set($key, $value, $escape); return $this; }

}//end BF_model

//--------------------------------------------------------------------

/**
 * MY_Model
 *
 * This simply extends BF_Model for backwards compatibility,
 * and to provide a placeholder class that your project can customize
 * extend as needed.
 *
 * @package    Bonfire
 * @subpackage MY_Model
 * @category   Models
 * @author     Lonnie Ezell
 * @link       http://cibonfire.com/docs/guides/models.html
 *
 */

class MY_Model extends BF_Model { }

// END: Class MY_model

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */
