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
 * The Base model implements standard CRUD functions that can be used and
 * overriden by module models. This helps to maintain a standard interface to
 * program to, and makes module creation faster.
 *
 * @package    Bonfire
 * @subpackage BF_Model
 * @category   Models
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/bonfire/bonfire_models
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
	public $error = '';

	/**
	 * The name of the db table this model primarily uses.
	 *
	 * @var string
	 * @access protected
	 */
	protected $table_name = '';

	/**
	 * The primary key of the table. Used as the 'id' throughout.
	 *
	 * @var string
	 * @access protected
	 */
	protected $key = 'id';

	/**
	 * Field name to use for the created time column in the DB table if
	 * $set_created is enabled
	 *
	 * @var string
	 * @access protected
	 */
	protected $created_field = 'created_on';

	/**
	 * Field name to use for the modified time column in the DB table if
	 * $set_modified is enabled
	 *
	 * @var string
	 * @access protected
	 */
	protected $modified_field = 'modified_on';

    /**
     * Field name to use for the deleted column in the DB table if $soft_deletes
     * is enabled
     *
     * @var string
     * @access protected
     */
    protected $deleted_field = 'deleted';

	/**
	 * Whether or not to auto-fill the $created_field on inserts.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $set_created = true;

	/**
	 * Whether or not to auto-fill the $modified_field on updates.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $set_modified = true;

	/**
	 * If true, will log user id in $created_by_field, $modified_by_field, and
	 * $deleted_by_field
	 *
	 * @var bool
	 * @access protected
	 */
	protected $log_user = false;

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
	 * The type of date/time field used for $created_field and $modified_field
	 *
	 * Valid values are 'int', 'datetime', 'date'
	 *
	 * @var string
	 * @access protected
	 */
	protected $date_format = 'int';

	/**
	 * If false, the delete() method will perform a delete of that row.
	 * If true, the value in $deleted_field will be set to 1.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $soft_deletes = false;

	/**
	 * Stores any selects here for use by the find* functions.
	 *
	 * @var string
	 * @access protected
	 */
	protected $selects = '';

	/**
	 * If false, the select() method will not try to protect your field or table
	 * names with backticks.
	 *
	 * This is useful if you need a compound select statement.
	 *
	 * @var bool
	 * @access protected
	 */
	protected $escape = true;

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
	 * Each array can contain the names of callback functions within the
	 * extending model which will be called during each event.
	 *
	 * <code>
	 *	$before_insert = array('set_created', 'validate_fields');
	 * </code>
	 *
	 * $before_insert contains the names of callback functions within the
	 * extending model which will be called before the insert method.
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_insert = array();

	/**
	 * Contains the names of callback functions within the extending model which
	 * will be called after the insert method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_insert = array();

	/**
	 * Contains the names of callback functions within the extending model which
	 * will be called before the update method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_update = array();

	/**
	 * Contains the names of callback functions within the extending model which
	 * will be called after the update method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_update = array();

	/**
	 * Contains the names of callback functions within the extending model which
	 * will be called before the find method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_find = array();

	/**
	 * Contains the names of callback functions within the extending model which
	 * will be called after the find method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_find = array();

	/**
	 * Contains the names of callback functions within the extending model which
	 * will be called before the delete method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $before_delete = array();

	/**
	 * Contains the names of callback functions within the extending model which
	 * will be called after the delete method
	 *
	 * @see $before_insert
	 *
	 * @var array
	 * @access protected
	 */
	protected $after_delete = array();

    /**
     * Contains the names of callback functions within the extending model which
     * will be called if $validation_rules is empty (or not an array) when
     * requested via the get_validation_rules() method.
     *
     * Note: These methods should not add $insert_validation_rules, as they are
     * added to the $validation_rules after these functions return.
     *
	 * @see $before_insert
	 *
     * @var array
	 * @access protected
     */
    protected $empty_validation_rules = array();

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
    protected $return_type = 'object';

	/**
	 * Holds the return type temporarily when using the
	 * as_array() and as_object() methods
	 *
	 * @var string
	 * @access protected
	 */
    protected $temp_return_type = null;

    /**
     * @var Array Validation rules used by the model's validation
     *
     * This array needs to be in the same format as validation rules passed to
     * the Form_validation library.
     *
     * @see http://ellislab.com/codeigniter/user-guide/libraries/form_validation.html#validationrulesasarray
     */
    protected $validation_rules = array();

    /**
     * @var Array Additional Validation rules only used on insert
     */
    protected $insert_validation_rules = array();

    /**
     * @var bool Skip the model's validation.
     *
     * Used in conjunction with skip_validation() to skip data validation for
     * any future calls.
     */
    protected $skip_validation = false;

    /**
     * @var bool If true, inserts will return the inserted ID.
     *
     * This can potentially slow down large imports drastically, so you can turn
     * it off with the return_insert_id(false) method.
     *
     * This will also disable $after_insert, since the observer receives the ID
     */
    protected $return_insert_id = true;

    /**
     * @var Array Metadata for the model's database fields
     *
     * This can be set to avoid a database call if using $this->prep_data()
     * and/or $this->get_field_info().
     *
     * @see http://ellislab.com/codeigniter/user-guide/database/fields.html
     *
     * Each field's definition should be as follows:
        array(
            'name'            => $field_name,
            'type'            => $field_data_type,
            'default'         => $field_default_value,
            'max_length'      => $field_max_length,
            'primary_key'     => (1 if the column is a primary key),
        ),
     */
    protected $field_info = array();

    //--------------------------------------------------------------------

	/**
	 * BF_Model's constructor
	 *
	 * Setup the DB connection if it doesn't exist, and setup the $before_insert
	 * and $before_update observers.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// If there are specific DB connection settings used in the model, load
		// the database using those settings.
		if ( ! empty($this->db_con)) {
			$this->db = $this->load->database($this->db_con, true);
		}

		// When loading the model, make sure the db class is loaded
		if ( ! isset($this->db)) {
			$this->load->database();
		}

        // If the $field_info property is set, convert it from an array of
        // arrays to an array of objects
        if ( ! empty($this->field_info)) {
            foreach ($this->field_info as $key => &$field) {
                $this->field_info[$key] = (object) $field;
            }
        }

		// Always protect attributes
        array_unshift($this->before_insert, 'protect_attributes');
        array_unshift($this->before_update, 'protect_attributes');

		// Check the auto-set features and make sure they are loaded into the
		// observer system.
		if ($this->set_created === true) {
            array_unshift($this->before_insert, 'created_on');
        }
		if ($this->set_modified === true) {
            array_unshift($this->before_update, 'modified_on');
        }
	}//end __construct()

	//---------------------------------------------------------------

	/**
	 * Search for a single row in the database.
	 *
	 * @param string $id The primary key of the record to search for.
	 *
	 * @return mixed An object/array representing the db row, or FALSE.
	 */
	public function find($id='')
	{
		$this->trigger('before_find');

		$query = $this->db->get_where($this->table_name, array("{$this->table_name}.{$this->key}" => $id));

		if ( ! $query->num_rows()) {
			return false;
		}

		$return = $query->{$this->_return_type()}();

		$return = $this->trigger('after_find', $return);

		if ($this->temp_return_type == 'json') {
            $return = json_encode($return);
        }

        // Reset the return type
        $this->temp_return_type = $this->return_type;

		return $return;
	}//end find()

	//---------------------------------------------------------------

	/**
	 * Returns all records in the table.
	 *
	 * By default, there is no 'where' clause, but the where clause can be set
	 * by using either CodeIgniter's Active Record functions before calling this
	 * function, or through method chaining with the model's where() method.
	 *
	 * @return mixed An array of objects/arrays representing the results, or
	 * false on failure or empty set.
	 */
	public function find_all()
	{
		$this->trigger('before_find');

		$query = $this->db->get($this->table_name);

		if ( ! $query->num_rows()) {
			return false;
		}

		$return = $query->{$this->_return_type(true)}();
		if (is_array($return)) {
            $last_record = count($return) - 1;
			foreach ($return as $key => &$row) {
				$row = $this->trigger('after_find', $row, ($key == $last_record));
			}
		}

		if ($this->temp_return_type == 'json') {
            $return = json_encode($return);
        }

        // Reset the return type
        $this->temp_return_type = $this->return_type;

		return $return;
	}//end find_all()

	//---------------------------------------------------------------

	/**
	 * A convenience method that combines a where() and find_all() call into a
	 * single call.
	 *
	 * @param mixed  $field The table field to search in.
	 * @param mixed  $value The value that field should be.
	 * @param string $type  The type of where clause to create: 'and' or 'or'.
	 *
	 * @return bool|mixed An array of objects representing the results, or false
	 * on failure or empty set.
	 */
	public function find_all_by($field=null, $value=null, $type='and')
	{
		if (empty($field)) {
			return false;
		}

		// Setup the field/value check
		if ( ! is_array($field)) {
			$field = array($field => $value);
		}

		if ($type == 'or') {
			$this->db->or_where($field);
		} else {
			$this->db->where($field);
		}

		return $this->find_all();
	}//end find_all_by()

	//--------------------------------------------------------------------

	/**
	 * Returns the first result that matches the field/values passed.
	 *
	 * @param string $field Either a string or an array of fields to match
	 * against. If an array is passed it, the $value parameter is ignored since
	 * the array is expected to have key/value pairs in it.
	 * @param string $value The value to match on the $field. Only used when
	 * $field is a string.
	 * @param string $type  The type of where clause to create: 'and' or 'or'.
	 *
	 * @return bool|mixed The first result returned as an array/object, or false
	 */
	public function find_by($field='', $value='', $type='and')
	{
		if (empty($field) || ( ! is_array($field) && empty($value))) {
			$this->error = lang('bf_model_find_error');
			$this->logit('[' . get_class($this) . ': ' . __METHOD__ . '] ' . lang('bf_model_find_error'));
			return false;
		}

		$this->trigger('before_find');

		if ( ! is_array($field)) {
			$field = array($field => $value);
		}

		if ($type == 'or') {
			$this->db->or_where($field);
		} else {
			$this->db->where($field);
		}

		$query = $this->db->get($this->table_name);
		if ( ! $query->num_rows()) {
			return false;
		}

		$return = $query->{$this->_return_type()}();
		$return = $this->trigger('after_find', $return);

		if ($this->temp_return_type == 'json') {
            $return = json_encode($return);
        }

		// Reset the return type
        $this->temp_return_type = $this->return_type;

        return $return;
	}//end find_by()

	//---------------------------------------------------------------

	/**
	 * Insert a row of data into the database.
	 *
	 * @param array $data an array of key/value pairs to insert.
	 *
	 * @return bool|mixed The $id of the row inserted, or false on failure
	 */
	public function insert($data=null)
	{
		if ($this->skip_validation === false) {
		    $data = $this->validate($data, 'insert');
            if ($data === false) {
                return false;
            }
		}

		$data = $this->trigger('before_insert', $data);

		if ($this->set_created === true && $this->log_user === true
            && ! array_key_exists($this->created_by_field, $data)
           ) {
			$data[$this->created_by_field] = $this->auth->user_id();
		}

		// Insert it
		$status = $this->db->insert($this->table_name, $data);
		if ($status == false) {
			$this->error = $this->get_db_error_message();
        } elseif ($this->return_insert_id) {
            $id = $this->db->insert_id();

            $status = $this->trigger('after_insert', $id);
        }

        return $status;
	}//end insert()

	//---------------------------------------------------------------

	/**
	 * Perform a batch insert of data into the database.
	 *
	 * @param array $data an array of key/value pairs to insert.
	 *
	 * @return bool True on success, or false on failure.
	 *
	 * @todo Check the code before the section marked "Insert it".
	 * 'before_insert' should trigger the 'created_on' method, so either this
	 * shouldn't set $this->created_field in $set, or $set should be merged
	 * before triggering 'before_insert'.
	 * Additionally, shouldn't the merge be:
	 *  $data[$key] = array_merge($set, $record)
	 *  or
	 *  $record = array_merge($set, $record)
	 * ?
	 */
	public function insert_batch($data=null)
	{
		$set = array();

		// Add the created field
		if ($this->set_created === true) {
			$set[$this->created_field] = $this->set_date();
		}

		if ($this->set_created === true && $this->log_user === true) {
			$set[$this->created_by_field] = $this->auth->user_id();
		}

		if ( ! empty($set)) {
			foreach ($data as $key => &$record) {
				$record = $this->trigger('before_insert', $record);

				$data[$key] = array_merge($set, $data[$key]);
			}
		}

		// Insert it
		$status = $this->db->insert_batch($this->table_name, $data);

		if ($status === false) {
			$this->error = $this->get_db_error_message();
			return false;
		}

		return true;
	}//end insert_batch()

	//---------------------------------------------------------------

	/**
	 * Update an existing row in the database.
	 *
	 * @param mixed	$where	The primary_key value of the row to update, or an
	 * array to use for the where clause.
	 * @param array $data	An array of key/value pairs to update.
	 *
	 * @return bool True on successful update, else false
	 */
	public function update($where=null, $data=null)
	{
		if ($this->skip_validation === false) {
		    $data = $this->validate($data);
            if ($data === false) {
                return false;
            }
		}

		if ( ! is_array($where)) {
			$where = array($this->key => $where);
		}

		$data = $this->trigger('before_update', $data);

		// Add the user id if using a modified_by field
		if ($this->set_modified === true && $this->log_user === true
            && ! array_key_exists($this->modified_by_field, $data)
           ) {
			$data[$this->modified_by_field] = $this->auth->user_id();
		}

		if ($result = $this->db->update($this->table_name, $data, $where)) {
			$this->trigger('after_update', array($data, $result));
			return true;
		}

		return false;
	}//end update()

	//---------------------------------------------------------------

	/**
	 * A convenience method that allows use of any field/value pair as the
	 * 'where' portion of an update.
	 *
	 * @param string $field The field to match on.
	 * @param string $value The value to search the $field for.
	 * @param array  $data  An array of key/value pairs to update.
	 *
	 * @return bool True on successful update, else false
	 */
	public function update_where($field=null, $value=null, $data=null)
	{
		$where = is_array($field) ? $field : array($field => $value);
		return $this->update($where, $data);
	}//end update_where()

	//---------------------------------------------------------------

	/**
	 * Updates a batch of existing rows in the database.
	 *
	 * @param array  $data  An array of key/value pairs to update.
	 * @param string $index The name of the db column to use as the where key
	 *
	 * @return bool True on successful update, else false
	 */
	public function update_batch($data=null, $index=null)
	{
		if (is_null($index) || is_null($data)) {
			return false;
		}

        // Add the modified field
        if ($this->set_modified === true && ! array_key_exists($this->modified_field, $data)) {
            foreach ($data as $key => $record) {
                $data[$key][$this->modified_field] = $this->set_date();
                if ($this->log_user === true && ! array_key_exists($this->modified_by_field, $data[$key])) {
                    $data[$key][$this->modified_by_field] = $this->auth->user_id();
                }
            }
        }

        $result = $this->db->update_batch($this->table_name, $data, $index);
        if (empty($result)) {
            return true;
        }

		return false;
	}//end update_batch()

	//--------------------------------------------------------------------

	/**
	 * Delete the record with the specified primary key value.
	 *
	 * If $this->soft_deletes is true, it will attempt to set
	 * $this->deleted_field on the specified record to '1', to allow the data to
	 * remain in the database.
	 *
	 * @param mixed $id The primary_key value to match against.
	 *
	 * @return bool true on successful delete, else false.
	 */
	public function delete($id=null)
	{
		$this->trigger('before_delete', $id);

		// Set the where clause to be used in the update/delete below
		$this->db->where($this->key, $id);

		if ($this->soft_deletes === true) {
			$data = array($this->deleted_field => 1);
			if ($this->log_user === true) {
				$data[$this->deleted_by_field] = $this->auth->user_id();
			}

			$result = $this->db->update($this->table_name, $data);
		} else {
			$result = $this->db->delete($this->table_name);
		}

		if ($result) {
			$this->trigger('after_delete', $id);
			return true;
		}

		$this->error = sprintf(lang('bf_model_db_error'), $this->get_db_error_message());

		return false;
	}//end delete()

	//---------------------------------------------------------------

	/**
     * Delete the record(s) specified by the given field/value pair(s).
     *
	 * Performs a delete using the field/value pair(s) as the 'where' portion of
	 * the delete statement.
	 *
	 * If $this->soft_deletes is true, it will attempt to set
	 * $this->deleted_field on the specified record(s) to '1', to allow the data
	 * to remain in the database.
	 *
	 * @param mixed/array $data key/value pairs accepts an associative array or
	 * a string
	 *
	 * @example 1) array('key' => 'value', 'key2' => 'value2')
	 * @example 2) ' (`key` = "value" AND `key2` = "value2") '
	 *
	 * @return bool true on successful delete, else false
	 */
	public function delete_where($where=null)
	{
		$where = $this->trigger('before_delete', $where);

		// Set the where clause to be used in the update/delete below
		$this->db->where($where);

		if ($this->soft_deletes === true) {
			$data = array($this->deleted_field => 1);
			if ($this->log_user === true) {
				$data[$this->deleted_by_field] = $this->auth->user_id();
			}

			$this->db->update($this->table_name, $data);
		} else {
			$this->db->delete($this->table_name);
		}

		$result = $this->db->affected_rows();
		if ($result) {
			$this->trigger('after_delete', $result);

			return $result;
		}

		$this->error = lang('bf_model_db_error') . $this->get_db_error_message();

		return false;
	}//end delete_where()

	//---------------------------------------------------------------

	//---------------------------------------------------------------
	// HELPER FUNCTIONS
	//---------------------------------------------------------------

	/**
	 * Check whether a field/value pair exists within the table.
	 *
	 * @param string $field The name of the field to search
	 * @param string $value The value to match $field against.
	 *
	 * @return bool true if the value does not exist, else false
	 */
	public function is_unique($field='', $value='')
	{
		if (empty($field) || empty($value)) {
			$this->error = lang('bf_model_unique_error');
			$this->logit('[' . get_class($this) . ': ' . __METHOD__ . '] ' . lang('bf_model_unique_error'));
			return false;
		}

		$this->db->where($field, $value);
		$query = $this->db->get($this->table_name);

		if ($query && $query->num_rows() == 0) {
			return true;
		}

		return false;
	}//end is_unique()

	//---------------------------------------------------------------

	/**
	 * Return the number of rows in the table.
	 *
	 * @internal This is potentially confusing given that count_all() and
	 * count_all_results() are different methods on $this->db, with the
	 * difference being that count_all_results() is modified by previous use of
	 * where(), like(), etc., while count_all() is not
	 *
	 * @return int
	 */
	public function count_all()
	{
		return $this->db->count_all_results($this->table_name);
	}//end count_all()

	//---------------------------------------------------------------

	/**
	 * Return the number of elements that match the field/value pair.
	 *
	 * @param string $field The field to search for.
	 * @param string $value The value to match $field against.
	 *
	 * @return bool|int
	 */
	public function count_by($field='', $value=null)
	{
		if (empty($field)) {
			$this->error = lang('bf_model_count_error');
			$this->logit('[' . get_class($this) . ': ' . __METHOD__ . '] ' . lang('bf_model_count_error'));
			return false;
		}

		$this->db->where($field, $value);

		return (int)$this->db->count_all_results($this->table_name);
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
	public function get_field($id=null, $field='')
	{
		if (empty($id) || empty($field)) {
			$this->error = lang('bf_model_fetch_error');
			$this->logit('[' . get_class($this) . ': ' . __METHOD__ . '] ' . lang('bf_model_fetch_error'));
			return false;
		}

		$query = $this->db->select($field)
                          ->where($this->key, $id)
                          ->get($this->table_name);

		if ($query && $query->num_rows() > 0) {
			return $query->row()->$field;
		}

		return false;
	}//end get_field()

	//---------------------------------------------------------------

	/**
	 * A convenience method to return options for form dropdown menus.
	 *
	 * Can pass either Key ID and Label Table names or Just Label Table name.
	 *
	 * @return array The options for the dropdown.
	 */
	public function format_dropdown()
	{
		$args = & func_get_args();
		if (count($args) == 2) {
			list($key, $value) = $args;
		} else {
			$key = $this->key;
			$value = $args[0];
		}

		$query = $this->db->select(array($key, $value))
                          ->get($this->table_name);

		$options = array();
		foreach ($query->result() as $row) {
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
	public function where($field=null, $value=null)
	{
        if (empty($field)) {
            return $this;
        }

        if (is_string($field)) {
            $this->db->where($field, $value);
        } elseif (is_array($field)) {
            $this->db->where($field);
        }

		return $this;
	}//end where()

	//--------------------------------------------------------------------

	/**
	 * Inserts a chainable order_by method from either a string or an
	 * array of field/order combinations.
	 *
	 * If the $field value is an array, it should look like:
	 *
	 * array(
	 *     'field1' => 'asc',
	 *     'field2' => 'desc'
	 * );
	 *
	 * @param mixed  $field The field to order the results by, or an array of
	 * field/order pairs.
	 * @param string $order The direction to order the results ('asc' or 'desc')
	 *
	 * @return BF_Model An instance of this class.
	 */
	public function order_by($field=null, $order='asc')
	{
		if (empty($field)) {
            return $this;
        }

        if (is_string($field)) {
            $this->db->order_by($field, $order);
        } elseif (is_array($field)) {
            foreach ($field as $f => $o) {
                $this->db->order_by($f, $o);
            }
        }

		return $this;
	}//end order_by()

	//--------------------------------------------------------------------
	// Scope Methods
	//--------------------------------------------------------------------

	/**
	 * Set the value of the soft deletes flag.
	 *
	 * <code>
	 *     $this->my_model->soft_delete(true)->delete($id);
	 * </code>
	 *
	 * @param  boolean $val If true, will temporarily use soft_deletes.
	 *
	 * @return BF_Model An instance of this class to allow method chaining
	 */
	public function soft_delete($val=true)
	{
		$this->soft_deletes = (bool)$val;
		return $this;
	}

	//--------------------------------------------------------------------

	/**
     * Temporarily set the return type to an array.
     *
	 * @return BF_Model An instance of this class to allow method chaining
     */
    public function as_array()
    {
        $this->temp_return_type = 'array';

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Temporarily sets the return type to an object.
     *
	 * @return BF_Model An instance of this class to allow method chaining
     */
    public function as_object()
    {
        $this->temp_return_type = 'object';
        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Temporarily sets the object return to a json object.
     *
	 * @return BF_Model An instance of this class to allow method chaining
     */
    public function as_json()
    {
        $this->temp_return_type = 'json';
        return $this;
    }

	//--------------------------------------------------------------------

    /**
     * Sets the value of the return_insert_id flag
     *
     * @param Bool $return (optional) whether insert will return the ID
     *
	 * @return BF_Model An instance of this class to allow method chaining
     */
	public function return_insert_id($return=true)
	{
	    $this->return_insert_id = (bool)$return;
	    return $this;
	}

	//--------------------------------------------------------------------

    /**
     * Sets the value of the skip_validation flag
     *
     * @param Bool $skip (optional) whether to skip validation in the model
     *
	 * @return BF_Model An instance of this class to allow method chaining
     */
	public function skip_validation($skip=true)
	{
	    $this->skip_validation = (bool)$skip;
	    return $this;
	}

	//--------------------------------------------------------------------
	// !OBSERVERS
	//--------------------------------------------------------------------

	/**
	 * Sets the created date for the row
	 *
	 * Uses the current date/time, the model's date_format, and places the value
	 * in the model's created_field.
	 * Will not overwrite existing.
	 *
	 * @param array  $row  The array of data to be inserted
	 *
	 * @return array The row data
	 */
	public function created_on($row)
	{
		if ( ! array_key_exists($this->created_field, $row)) {
			$row[$this->created_field] = $this->set_date();
		}

		return $row;
	} // end created_on()

	//--------------------------------------------------------------------

	/**
	 * Sets the modified date for the row
	 *
	 * Uses the current date/time, the model's date_format, and places the value
	 * in the model's modified_field.
	 * Will not overwrite existing.
	 *
	 * @param array  $row  The array of data to be inserted
	 *
	 * @return array The row data
	 */
	public function modified_on($row)
	{
		if ( ! array_key_exists($this->modified_field, $row)) {
			$row[$this->modified_field] = $this->set_date();
		}

		return $row;
	}

	//---------------------------------------------------------------
	// !UTILITY FUNCTIONS
	//---------------------------------------------------------------

	/**
	 * Triggers a model-specific event and calls each of its observers.
	 *
	 * @param string 	$event 	The name of the event to trigger
	 * @param mixed 	$data 	The data to be passed to the callback functions.
	 *
	 * @return mixed
	 */
	public function trigger($event, $data=false)
	{
		if ( ! isset($this->$event) || ! is_array($this->$event)) {
			return $data;
		}

		foreach ($this->$event as $method) {
			if (strpos($method, '(')) {
				preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);
				$this->callback_parameters = explode(',', $matches[3]);
			}

			$data = call_user_func_array(array($this, $method), array($data));
		}

		return $data;
	}

	//--------------------------------------------------------------------

    /**
     * Get the validation rules for the model
     *
     * @uses $empty_validation_rules Observer to generate validation rules if
     * they are empty
     *
     * @param String $type The type of validation rules to retrieve: 'update' or
     * 'insert'. If 'insert', appends rules set in $insert_validation_rules.
     *
     * @return array    The validation rules for the model or an empty array
     */
    public function get_validation_rules($type='update')
    {
        $temp_validation_rules = $this->validation_rules;

        /*
         * When $validation_rules is empty (or not an array), try to generate
         * them by triggering the $empty_validation_rules observer
         *
         * If the observer returns a non-empty array, set $validation_rules so
         * they aren't re-generated for this instance of the model
         */

        if (empty($temp_validation_rules) || ! is_array($temp_validation_rules)) {
            $temp_validation_rules = $this->trigger('empty_validation_rules', $temp_validation_rules);
            if (empty($temp_validation_rules) || ! is_array($temp_validation_rules)) {
                return array();
            }

            $this->validation_rules = $temp_validation_rules;
        }

        // Any insert additions?
        if ($type == 'insert'
            && is_array($this->insert_validation_rules)
            && ! empty($this->insert_validation_rules)
           ) {
            // Get the index for each field in the validation rules
            $fieldIndexes = array();
            foreach ($temp_validation_rules as $key => $validation_rule) {
                $fieldIndexes[$validation_rule['field']] = $key;
            }

            foreach ($this->insert_validation_rules as $key => $rule) {
                if (is_array($rule)) {
                    $insert_rule = $rule;
                } else {
                    // If $key isn't a field name and $insert_rule isn't an
                    // array, there's nothing useful to do, so skip it
                    if (is_numeric($key)) {
                        continue;
                    }

                    $insert_rule = array(
                        'field' => $key,
                        'rules' => $rule,
                    );
                }

                /*
                 * If the field is already in the validation rules, update the
                 * validation rule to merge the insert rule (replace empty rules)
                 */
                if (isset($fieldIndexes[$insert_rule['field']])) {
                    $fieldKey = $fieldIndexes[$insert_rule['field']];
                    if (empty($temp_validation_rules[$fieldKey]['rules'])) {
                        $temp_validation_rules[$fieldKey]['rules'] = $insert_rule['rules'];
                    } else {
                        $temp_validation_rules[$fieldKey]['rules'] .= "|{$insert_rule['rules']}";
                    }
                } else {
                    // Otherwise, add the insert rule to the validation rules
                    $temp_validation_rules[] = $insert_rule;
                }
            }
        }

        return $temp_validation_rules;
    }

	//--------------------------------------------------------------------

	/**
	 * Validate the $data passed into it
	 *
	 * Uses the form_validation rules setup in $this->validation_rules.
	 *
	 * If $type == 'insert', any additional rules in $insert_validation_rules
	 * for that field will be added to the rules.
	 *
	 * @param  array $data      An array of data to validate
	 * @param  string $type     Either 'update' or 'insert'.
	 * @return array/bool       The original data or false
	 */
	public function validate($data, $type='update')
	{
	    if ($this->skip_validation) {
	        return $data;
	    }

        $current_validation_rules = $this->get_validation_rules($type);
        if (empty($current_validation_rules)) {
            return $data;
        }

        foreach ($data as $key => $val) {
            $_POST[$key] = $val;
        }

        $this->load->library('form_validation');

        /*
         * $current_validation_rules can be an array of rules, which is passed
         * to set_rules(), or a string which is passed to run(). If it is a
         * string, run() will attempt to load the rules from a config file,
         * otherwise, run() will ignore any input.
         */
        if (is_array($current_validation_rules)) {
            $this->form_validation->set_rules($current_validation_rules);
            $valid = $this->form_validation->run();
        } else {
            $valid = $this->form_validation->run($current_validation_rules);
        }

        if ($valid !== true) {
            return false;
        }

        return $data;
	}

	//--------------------------------------------------------------------

	/**
     * Protect attributes by removing them from $row array.
     *
     * Useful for removing the primary key, or submit button names if $_POST is
     * thrown at the model.
     *
     * @param object/array $row The value pair item to remove.
     */
    public function protect_attributes($row)
    {
        foreach ($this->protected_attributes as $attr) {
            if (is_object($row)) {
                unset($row->$attr);
            } else {
                unset($row[$attr]);
            }
        }

        return $row;
    }

    //--------------------------------------------------------------------

	/**
	 * A utility function to allow child models to use the type of
	 * date/time format that they prefer. This is primarily used for
	 * setting created_on and modified_on values, but can be used for other
	 * fields as well.
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
	 * @return int|null|string The current/user time converted to the model's format.
	 */
	protected function set_date($user_date=null)
	{
		$curr_date = empty($user_date) ? time() : $user_date;
        $dateFormat = '';

		switch ($this->date_format) {
			case 'datetime':
                $dateFormat = 'Y-m-d H:i:s';
                break;

			case 'date':
                $dateFormat = 'Y-m-d';
                break;

			case 'int':
			default:
                $dateFormat = 'U';
                break;
		}

        return (strtolower($this->config->item('time_reference')) == 'gmt' ?
                gmdate($dateFormat, $curr_date) :
                date($dateFormat, $curr_date));
	}//end set_date()

	//--------------------------------------------------------------------

	/**
     * Return the method name for the current return type
     *
     * @return string The name of the method
     */
    protected function _return_type($multi=false)
    {
        $method = $multi ? 'result' : 'row';

        // If the type is 'array' or 'json', simply use the array version of the
        // function, since the database library doesn't support json directly.
        return $this->temp_return_type == 'array' ? "{$method}_array" : $method;
    }

    //--------------------------------------------------------------------

	/**
	 * Retrieve error messages from the database
	 *
	 * @return string
	 */
	protected function get_db_error_message()
	{
		switch ($this->db->platform()) {
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
	 * Allows setting the table to use for all methods during runtime.
	 *
	 * @param string $table The table name to use (do not include the prefix!)
	 *
	 * @return void
	 */
	public function set_table($table='')
	{
		$this->table_name = $table;
	}

	//--------------------------------------------------------------------

	/**
	 * Get the table name
	 *
	 * @return string $this->table_name (current model table name)
	 */
	public function get_table()
	{
		return $this->table_name;
	}

	//--------------------------------------------------------------------

	/**
	 * Get the table's primary key
	 *
	 * @return string $this->key (current model table primary key)
	 */
	public function get_key()
	{
		return $this->key;
	}

	//--------------------------------------------------------------------

    /**
     * Get the name of the created by field
     *
     * @return String    The name of the field or an empty string
     */
    public function get_created_by_field()
    {
        if ($this->set_created && $this->log_user) {
            return $this->created_by_field;
        }

        return '';
    }

	//--------------------------------------------------------------------

    /**
     * Get the name of the created field
     *
     * @return String    The name of the field or an empty string
     */
    public function get_created_field()
    {
        if ($this->set_created) {
            return $this->created_field;
        }

        return '';
    }

	//--------------------------------------------------------------------

    /**
     * Get the name of the deleted field
     *
     * @return String    The name of the field if soft_deletes is enabled, else an empty string
     */
    public function get_deleted_field()
    {
        if ($this->soft_deletes) {
            return $this->deleted_field;
        }

        return '';
    }

	//--------------------------------------------------------------------

    /**
     * Get the name of the deleted by field
     *
     * @return String    The name of the field or an empty string
     */
    public function get_deleted_by_field()
    {
        if ($this->soft_deletes && $this->log_user) {
            return $this->deleted_by_field;
        }

        return '';
    }

	//--------------------------------------------------------------------

    /**
     * Get the metadata for the model's database fields
     *
     * Returns the model's database field metadata stored in $this->field_info
     * if set, else it tries to retrieve the metadata from
     * $this->db->field_data($this->table_name);
     *
     * @todo The MongoDB driver is the only one that doesn't appear to support
     * $this->db->field_data, though it's possible other drivers don't support
     * more extensive metadata (such as type/max_length) supported by MySQL
     *
     * @return array    Returns the database field metadata for this model
     */
    public function get_field_info()
    {
        if (empty($this->field_info)) {
            $this->field_info = $this->db->field_data($this->table_name);
        }

        return $this->field_info;
    }

	//--------------------------------------------------------------------

    /**
     * Get the name of the modified by field
     *
     * @return String    The name of the field or an empty string
     */
    public function get_modified_by_field()
    {
        if ($this->set_modified && $this->log_user) {
            return $this->modified_by_field;
        }

        return '';
    }

	//--------------------------------------------------------------------

    /**
     * Get the name of the modified field
     *
     * @return String    The name of the field or an empty string
     */
    public function get_modified_field()
    {
        if ($this->set_modified) {
            return $this->modified_field;
        }

        return '';
    }

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
		if ($format != 'int' && $format != 'datetime' && $format != 'date') {
			return false;
		}
		$this->date_format = $format;

		return true;
	}

	//--------------------------------------------------------------------

	/**
	 * Sets whether to auto-create modified_on dates in the update method.
	 *
	 * @param bool $modified
	 *
	 * @return bool
	 */
	public function set_modified($modified=true)
	{
		// Micro-optimization note: comparison to TRUE and FALSE is faster
		// than is_bool(), because it's a function call
		// === FALSE || === TRUE is faster than !== TRUE && !== FALSE
		// because === TRUE will only be compared for values other than FALSE
		if ($modified === false || $modified === true) {
			$this->set_modified = $modified;

			return true;
		}

		return false;
	}

	//--------------------------------------------------------------------

	/**
	 * Sets whether soft deletes are used by the delete method.
	 *
	 * @deprecated since 0.7
	 *
	 * @param bool $soft
	 *
	 * @return bool
	 */
	public function set_soft_deletes($soft=true)
	{
		if ($modified === false || $modified === true) {
			$this->soft_deletes = $soft;

			return true;
		}

		return false;
	}

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
		if (empty($message)) {
			return false;
		}

		if (class_exists('Console')) {
			Console::log($message);
		}

		log_message($level, $message);
	}//end logit()

	//--------------------------------------------------------------------

    /**
     * Extracts the model's fields (except the key and those handled by
     * Observers) from the $post_data and returns an array of name => value pairs
     *
     * @param Array $post_data The post data, usually $this->input->post() when called from the controller
     *
     * @return Array    An array of name => value pairs containing the data for the model's fields
     */
    public function prep_data($post_data)
    {
        $data = array();
        $skippedFields = array();
        $skippedFields[] = $this->get_created_field();
        $skippedFields[] = $this->get_created_by_field();
        $skippedFields[] = $this->get_deleted_field();
        $skippedFields[] = $this->get_deleted_by_field();
        $skippedFields[] = $this->get_modified_field();
        $skippedFields[] = $this->get_modified_by_field();

        // Though the model doesn't support multiple keys well, $this->key
        // could be an array or a string...
        $skippedFields = array_merge($skippedFields, (array)$this->key);

        // If the field is the primary key, one of the created/modified/deleted
        // fields, or has not been set in the $post_data, skip it
        foreach ($this->get_field_info() as $field) {
            if (isset($field->primary_key) && $field->primary_key
                || in_array($field->name, $skippedFields)
                || ! isset($post_data[$field->name])
               ) {
                continue;
            }

            $data[$field->name] = $post_data[$field->name];
        }

        return $data;
    }

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

/* End of file BF_Model.php */
/* Location: ./bonfire/core/BF_Model.php */