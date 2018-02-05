<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2018, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License.
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Bonfire Base Model
 *
 * The Base model implements standard CRUD functions that can be used and
 * overriden by module models. This helps to maintain a standard interface to
 * program to, and makes module creation faster.
 *
 * @package Bonfire\Core\BF_Model
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/bonfire/bonfire_models
 */
class BF_Model extends CI_Model
{
    /** @var string Error messagess that can be used in UI error reporting. */
    public $error = '';

    /** @var string The name of the db table this model primarily uses. */
    protected $table_name = '';

    /** @var string The primary key of the table. Used as the 'id' throughout. */
    protected $key = 'id';

    /**
     * @var string Field name to use for the created time column in the DB table
     * if $set_created is enabled.
     */
    protected $created_field = 'created_on';

    /**
     * @var string Field name to use for the modified time column in the DB
     * table if $set_modified is enabled.
     */
    protected $modified_field = 'modified_on';

    /**
     * @var string Field name to use for the deleted column in the DB table if
     * $soft_deletes is enabled.
     */
    protected $deleted_field = 'deleted';

    /** @var bool Whether to auto-fill the $created_field on inserts. */
    protected $set_created = true;

    /** @var bool Whether to auto-fill the $modified_field on updates. */
    protected $set_modified = true;

    /**
     * @var bool If true, will log user id in $created_by_field, $modified_by_field,
     * and $deleted_by_field.
     */
    protected $log_user = false;

    /** @var string Field name to use as the created by column in the DB table. */
    protected $created_by_field = 'created_by';

    /** @var string Field name to use as the modified by column in the DB table. */
    protected $modified_by_field = 'modified_by';

    /** @var string Field name to use as the deleted by column in the DB table. */
    protected $deleted_by_field = 'deleted_by';

    /**
     * @var string The type of date/time field used for $created_field and $modified_field.
     * Valid values are 'int', 'datetime', 'date'.
     */
    protected $date_format = 'int';

    /**
     * @var bool Enable/Disable soft deletes.
     * If false, the delete() method will perform a delete of that row.
     * If true, the value in $deleted_field will be set to 1.
     */
    protected $soft_deletes = false;

    /** @var string Stores any selects here for use by the find* methods. */
    protected $selects = '';

    /**
     * @var bool Protect field/table names.
     * If false, the select() method will not try to protect field or table names
     * with backticks.
     * This is useful when creating a compound select statement.
     */
    protected $escape = true;

    /** @var string|array DB Connection details. */
    protected $db_con = '';

    /**
     * Observer Arrays
     *
     * Each array can contain the names of callback functions within the
     * extending model which will be called during each event.
     *
     * <code>
     *  $before_insert = array('set_created', 'validate_fields');
     * </code>
     *
     * @var string[] The names of callback methods within the extending model which
     * will be called before the insert method.
     */
    protected $before_insert = [];

    /**
     * @var string[] The names of callback methods within the extending model which
     * will be called after the insert method.
     *
     * @see $before_insert
     */
    protected $after_insert = [];

    /**
     * @var string[] The names of callback methods within the extending model which
     * will be called before the update method.
     *
     * @see $before_insert
     */
    protected $before_update = [];

    /**
     * @var string[] The names of callback methods within the extending model which
     * will be called after the update method.
     *
     * @see $before_insert
     */
    protected $after_update = [];

    /**
     * @var string[] The names of callback methods within the extending model which
     * will be called before the find method.
     *
     * @see $before_insert
     */
    protected $before_find = [];

    /**
     * @var string[] The names of callback methods within the extending model which
     * will be called after the find method.
     *
     * @see $before_insert
     */
    protected $after_find = [];

    /**
     * @var string[] The names of callback methods within the extending model which
     * will be called before the delete method.
     *
     * @see $before_insert
     */
    protected $before_delete = [];

    /**
     * @var string[] The names of callback methods within the extending model which
     * will be called after the delete method.
     *
     * @see $before_insert
     */
    protected $after_delete = [];

    /**
     * @var string[] The names of callback methods within the extending model which
     * will be called if $validation_rules is empty (or not an array) when requested
     * via the get_validation_rules() method.
     *
     * Note: These methods should not add $insert_validation_rules, as they are
     * added to the $validation_rules after these methods return.
     *
     * @see $before_insert
     */
    protected $empty_validation_rules = [];

    /** @var string[] Protected, non-modifiable attributes. */
    protected $protected_attributes = [];

    /**
     * @var string Data type of the records returned from find* methods.
     * By default, results are returned as objects. This can be changed class-wide
     * by setting this value to 'array' instead of 'object'.
     * Alternatively, it can be done on a per-instance basis using the 'as_array()'
     * and 'as_object()' methods.
     */
    protected $return_type = 'object';

    /**
     * @var string Holds the return type temporarily when using the as_array()
     * and as_object() methods
     */
    protected $temp_return_type = null;

    /**
     * @var array Rules used to validate the model.
     * This array needs to be in the same format as validation rules passed to
     * the Form_validation library.
     *
     * @see http://www.codeigniter.com/user_guide/libraries/form_validation.html#validationrulesasarray
     */
    protected $validation_rules = [];

    /** @var array Additional validation rules only used on insert. */
    protected $insert_validation_rules = [];

    /**
     * @var bool Skip the model's validation. Used in conjunction with skip_validation()
     * to skip data validation for any future calls.
     */
    protected $skip_validation = false;

    /**
     * @var bool If true, inserts will return the inserted ID.
     * This can potentially slow down large imports drastically, so it can be disabled
     * via the return_insert_id(false) method.
     * This will also disable $after_insert, since the observer receives the ID.
     */
    protected $return_insert_id = true;

    /**
     * @var array Metadata for the model's database fields.
     * This can be set to avoid a database call if using $this->prep_data() and/or
     * $this->get_field_info().
     *
     * @see http://www.codeigniter.com/user_guide/database/metadata.html#retrieve-field-metadata
     *
     * Each field's definition should be as follows:
     *  array(
     *      'name'            => $field_name,
     *      'type'            => $field_data_type,
     *      'default'         => $field_default_value,
     *      'max_length'      => $field_max_length,
     *      'primary_key'     => (1 if the column is a primary key),
     *  ),
     */
    protected $field_info = [];

    //--------------------------------------------------------------------------

    /**
     * BF_Model's constructor.
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
        if (! empty($this->db_con)) {
            $this->db = $this->load->database($this->db_con, true);
        }

        // When loading the model, make sure the db class is loaded.
        if (! isset($this->db)) {
            $this->load->database();
        }

        // If the $field_info property is set, convert it from an array of arrays
        // to an array of objects.
        if (! empty($this->field_info)) {
            foreach ($this->field_info as $key => &$field) {
                $this->field_info[$key] = (object) $field;
            }
        }

        // Always protect attributes.
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
    }

    /**
     * Search for a single row in the database.
     *
     * @param string $id The primary key of the record to search for.
     *
     * @return mixed An object/array representing the db row, or false.
     */
    public function find($id = '')
    {
        $this->trigger('before_find');
        $this->where("{$this->table_name}.{$this->key}", $id);

        $query = $this->db->get($this->table_name);
        $return = $query->{$this->_return_type()}();
        if (empty($return)) {
            return false;
        }

        $return = $this->trigger('after_find', $return);
        if ($this->temp_return_type == 'json') {
            $return = json_encode($return);
        }

        // Reset the return type.
        $this->temp_return_type = $this->return_type;

        return $return;
    }

    /**
     * Return all records in the table.
     *
     * By default, there is no 'where' clause, but the where clause can be set
     * using either CodeIgniter's Active Record functions before calling this
     * method, or through method chaining with the model's where() method.
     *
     * @return mixed An array of objects/arrays representing the results, or false
     * on failure or empty set.
     */
    public function find_all()
    {
        $this->trigger('before_find');

        $query = $this->db->get($this->table_name);
        $return = $query->{$this->_return_type(true)}();
        if (empty($return)) {
            return false;
        }

        if (is_array($return)) {
            $last_record = count($return) - 1;
            foreach ($return as $key => &$row) {
                $row = $this->trigger('after_find', $row, ($key == $last_record));
            }
        }

        if ($this->temp_return_type == 'json') {
            $return = json_encode($return);
        }

        // Reset the return type.
        $this->temp_return_type = $this->return_type;

        return $return;
    }

    /**
     * A convenience method combining where() and find_all() into a single call.
     *
     * @param string|array $field The table field to search in, or an array of
     * $field => $value pairs.
     * @param string       $value The value that $field should be, or null if $field
     * is an array.
     * @param string       $type  The type of where clause to create: 'and' or 'or'.
     *
     * @return bool|array An array of objects representing the results, or false
     * on failure or empty set.
     */
    public function find_all_by($field = null, $value = null, $type = 'and')
    {
        if (empty($field)) {
            return false;
        }

        // Setup the field/value check.
        $where = is_array($field) ? $field : [$field => $value];
        if ($type == 'or') {
            $this->or_where($where);
        } else {
            $this->where($where);
        }

        return $this->find_all();
    }

    /**
     * Return the first result that matches the field/values passed.
     *
     * @param string|array $field Either a string or an array of fields to match
     * against. If an array is passed it, the $value parameter is ignored since
     * the array is expected to have key/value pairs in it.
     * @param string       $value The value to match on the $field. Only used when
     * $field is a string.
     * @param string       $type  The type of where clause to create: 'and' or 'or'.
     *
     * @return bool|mixed The first result returned as an array/object, or false.
     */
    public function find_by($field = '', $value = '', $type = 'and')
    {
        if (empty($field) || ( ! is_array($field) && empty($value))) {
            $this->error = lang('bf_model_find_error');
            $this->logit('[' . get_class($this) . ': ' . __METHOD__ . '] ' . lang('bf_model_find_error'));
            return false;
        }

        $this->trigger('before_find');

        $where = is_array($field) ? $field : [$field => $value];
        if ($type == 'or') {
            $this->or_where($where);
        } else {
            $this->where($where);
        }

        $query = $this->db->get($this->table_name);
        $return = $query->{$this->_return_type()}();
        if (empty($return)) {
            return false;
        }

        $return = $this->trigger('after_find', $return);
        if ($this->temp_return_type == 'json') {
            $return = json_encode($return);
        }

        // Reset the return type.
        $this->temp_return_type = $this->return_type;

        return $return;
    }

    /**
     * Insert a row of data into the database.
     *
     * @param array $data   An array of key/value pairs to insert.
     *
     * @return bool|int The $id of the inserted row or true (if $this->return_insert_id
     * is false), or false on failure.
     */
    public function insert($data = null)
    {
        if ($this->skip_validation === false) {
            $data = $this->validate($data, 'insert');
            if ($data === false) {
                return false;
            }
        }

        $data = $this->trigger('before_insert', $data);

        if ($this->set_created === true
            && $this->log_user === true
            && ! array_key_exists($this->created_by_field, $data)
        ) {
            $data[$this->created_by_field] = $this->auth->user_id();
        }

        // Insert it
        if ($this->db->insert($this->table_name, $data)) {
            if ($this->return_insert_id) {
                $id = $this->db->insert_id();
                return $this->trigger('after_insert', $id);
            }
            return true;
        }

        $this->error = $this->get_db_error_message();
        return false;
    }

    /**
     * Perform a batch insert of data into the database.
     *
     * @param array $data   An array of key/value pairs to insert.
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
    public function insert_batch($data = null)
    {
        $set = [];

        // Add the created field.
        if ($this->set_created === true) {
            $set[$this->created_field] = $this->set_date();

            if ($this->log_user === true) {
                $set[$this->created_by_field] = $this->auth->user_id();
            }
        }

        if (! empty($set)) {
            foreach ($data as $key => &$record) {
                $record = $this->trigger('before_insert', $record);
                $data[$key] = array_merge($set, $data[$key]);
            }
        }

        // Insert it.
        if ($this->db->insert_batch($this->table_name, $data)) {
            return true;
        }

        $this->error = sprintf(lang('bf_model_db_error'), $this->get_db_error_message());
        return false;
    }

    /**
     * Update an existing row in the database.
     *
     * @param mixed $where The primary_key value of the row to update, or an array
     * to use for the where clause.
     * @param array $data  An array of key/value pairs to update.
     *
     * @return bool True on successful update, else false.
     */
    public function update($where = null, $data = null)
    {
        if ($this->skip_validation === false) {
            $data = $this->validate($data);
            if ($data === false) {
                return false;
            }
        }

        if (! is_array($where)) {
            $where = [$this->key => $where];
        }

        $data = $this->trigger('before_update', $data);

        // Add the user id if using a modified_by field.
        if ($this->set_modified === true
            && $this->log_user === true
            && ! array_key_exists($this->modified_by_field, $data)
        ) {
            $data[$this->modified_by_field] = $this->auth->user_id();
        }

        if ($result = $this->db->update($this->table_name, $data, $where)) {
            $this->trigger('after_update', [$data, $result]);
            return true;
        }

        $this->error = sprintf(lang('bf_model_db_error'), $this->get_db_error_message());
        return false;
    }

    /**
     * A convenience method that allows use of any field/value pair as the
     * 'where' portion of an update.
     *
     * @param string $field The field to match on.
     * @param string $value The value to search the $field for.
     * @param array  $data  An array of key/value pairs to update.
     *
     * @return bool True on successful update, else false.
     */
    public function update_where($field = null, $value = null, $data = null)
    {
        $where = is_array($field) ? $field : [$field => $value];
        return $this->update($where, $data);
    }

    /**
     * Update a batch of existing rows in the database.
     *
     * @param array  $data  An array of key/value pairs to update.
     * @param string $index The name of the db column to use as the where key.
     *
     * @return bool True on successful update, else false.
     */
    public function update_batch($data = null, $index = null)
    {
        if (is_null($index) || is_null($data)) {
            return false;
        }

        // Add the modified field
        if ($this->set_modified === true
            && ! array_key_exists($this->modified_field, $data)
        ) {
            foreach ($data as $key => $record) {
                $data[$key][$this->modified_field] = $this->set_date();
                if ($this->log_user === true
                    && ! array_key_exists($this->modified_by_field, $data[$key])
                ) {
                    $data[$key][$this->modified_by_field] = $this->auth->user_id();
                }
            }
        }

        if ($this->db->update_batch($this->table_name, $data, $index) === false) {
            $this->error = sprintf(lang('bf_model_db_error'), $this->get_db_error_message());
            return false;
        }

        return true;
    }

    /**
     * Delete the record with the specified primary key value.
     *
     * If $this->soft_deletes is true, it will attempt to set $this->deleted_field
     * on the specified record to '1', to allow the data to remain in the database.
     *
     * @param mixed $id The primary_key value to match against.
     *
     * @return bool True on successful delete, else false.
     */
    public function delete($id = null)
    {
        return $this->delete_where([$this->key => $id]);
    }

    /**
     * Delete the record(s) specified by the given field/value pair(s).
     *
     * Performs a delete using the field/value pair(s) as the 'where' portion of
     * the delete statement.
     *
     * If $this->soft_deletes is true, it will attempt to set $this->deleted_field
     * on the specified record(s) to '1', to allow the data to remain in the database.
     *
     * @param string|array $data key/value pairs accepts an associative array or
     * a string.
     *
     * @return bool True on successful delete, else false.
     */
    public function delete_where($where = null)
    {
        $where = $this->trigger('before_delete', $where);
        $this->where($where);

        if ($this->soft_deletes !== true) {
            $result = $this->db->delete($this->table_name);
        } else {
            $data = [$this->deleted_field => 1];
            if ($this->log_user === true) {
                $data[$this->deleted_by_field] = $this->auth->user_id();
            }

            $result = $this->db->update($this->table_name, $data);
        }

        if ($result) {
            $result = $this->db->affected_rows();
            $this->trigger('after_delete', $result);
            return $result;
        }

        $this->error = sprintf(lang('bf_model_db_error'), $this->get_db_error_message());

        return false;
    }

    //--------------------------------------------------------------------------
    // HELPER METHODS
    //--------------------------------------------------------------------------

    /**
     * Check whether a field/value pair exists within the table.
     *
     * @param string $field The name of the field to search
     * @param string $value The value to match $field against.
     *
     * @return bool True if the value does not exist, else false.
     */
    public function is_unique($field = '', $value = '')
    {
        if (empty($field) || empty($value)) {
            $this->error = lang('bf_model_unique_error');
            $this->logit('[' . get_class($this) . ': ' . __METHOD__ . '] ' . lang('bf_model_unique_error'));
            return false;
        }

        $this->where($field, $value);
        $query = $this->db->get($this->table_name);

        if ($query && $query->num_rows() == 0) {
            return true;
        }

        return false;
    }

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
    }

    /**
     * Return the number of elements that match the field/value pair.
     *
     * @param string $field The field to search for.
     * @param string $value The value to match $field against.
     *
     * @return bool|int
     */
    public function count_by($field = '', $value = null)
    {
        if (empty($field)) {
            $this->error = lang('bf_model_count_error');
            $this->logit('[' . get_class($this) . ': ' . __METHOD__ . '] ' . lang('bf_model_count_error'));
            return false;
        }

        $this->where($field, $value);

        return (int) $this->count_all();
    }

    /**
     * A convenience method to return only a single field of the specified row.
     *
     * @param mixed  $id    The primary_key value to match against.
     * @param string $field The field to search for.
     *
     * @return bool|mixed The value of the field, or false.
     */
    public function get_field($id = null, $field = '')
    {
        if (empty($id) || empty($field)) {
            $this->error = lang('bf_model_fetch_error');
            $this->logit('[' . get_class($this) . ': ' . __METHOD__ . '] ' . lang('bf_model_fetch_error'));
            return false;
        }

        $this->select($field)
            ->where($this->key, $id);

        $query = $this->db->get($this->table_name);
        if ($query && $query->num_rows() > 0) {
            return $query->row()->$field;
        }

        return false;
    }

    /**
     * A convenience method to return options for form dropdown menus.
     *
     * Can pass either Key ID and Label Table names or Just Label Table name.
     *
     * @return array The options for the dropdown.
     */
    public function format_dropdown()
    {
        $args = func_get_args();
        if (count($args) == 2) {
            list($key, $value) = $args;
        } else {
            $key = $this->key;
            $value = $args[0];
        }

        $this->select([$key, $value]);
        $query = $this->db->get($this->table_name);

        $options = [];
        foreach ($query->result() as $row) {
            $options[$row->{$key}] = $row->{$value};
        }

        return $options;
    }

    //--------------------------------------------------------------------------
    // !CHAINABLE UTILITY METHODS
    //--------------------------------------------------------------------------

    /**
     * Set the where portion of the query in a chainable format.
     *
     * @param string|array $field  The field to search the db on. Can be either a string
     * with the field name to search, or an associative array of key/value pairs.
     * @param string       $value  The value to match the field against. If $field is an
     * array, this value is ignored.
     *
     * @return BF_Model An instance of this class.
     */
    public function where($field = null, $value = null)
    {
        if (empty($field)) {
            return $this;
        }

        $this->db->where($field, $value);

        return $this;
    }

    /**
     * Insert a chainable order_by clause from either a string or an array of field/order
     * combinations.
     *
     * If the $field value is an array, it should look like:
     *
     * array(
     *     'field1' => 'asc',
     *     'field2' => 'desc'
     * );
     *
     * @param string|array $field     The field to order the results by, or an array
     * of field/direction pairs.
     * @param string       $order The direction to order the results, case-insensitive
     * ('asc', 'desc', or 'random').
     *
     * @return BF_Model An instance of this class.
     */
    public function order_by($field = null, $order = 'asc')
    {
        if (empty($field)) {
            return $this;
        }

        if (is_array($field)) {
            foreach ($field as $f => $o) {
                $this->order_by($f, $o);
            }
        }

        if (is_string($field)) {
            $this->db->order_by($field, $order);
        }

        return $this;
    }

    //--------------------------------------------------------------------------
    // Scope Methods
    //--------------------------------------------------------------------------

    /**
     * Set the value of the soft deletes flag.
     *
     * <code>
     *     $this->my_model->soft_delete(true)->delete($id);
     * </code>
     *
     * @param  bool $val If true, will temporarily use soft_deletes.
     *
     * @return BF_Model An instance of this class to allow method chaining.
     */
    public function soft_delete($val = true)
    {
        $this->soft_deletes = (bool) $val;
        return $this;
    }

    /**
     * Temporarily set the return type to an array.
     *
     * @return BF_Model An instance of this class to allow method chaining.
     */
    public function as_array()
    {
        $this->temp_return_type = 'array';
        return $this;
    }

    /**
     * Temporarily set the return type to an object.
     *
     * @return BF_Model An instance of this class to allow method chaining.
     */
    public function as_object()
    {
        $this->temp_return_type = 'object';
        return $this;
    }

    /**
     * Temporarily set the object return to a json object.
     *
     * @return BF_Model An instance of this class to allow method chaining.
     */
    public function as_json()
    {
        $this->temp_return_type = 'json';
        return $this;
    }

    /**
     * Set the value of the return_insert_id flag.
     *
     * @param bool $return Whether insert will return the ID (true by default).
     *
     * @return BF_Model An instance of this class to allow method chaining.
     */
    public function return_insert_id($return = true)
    {
        $this->return_insert_id = (bool) $return;
        return $this;
    }

    /**
     * Set the value of the skip_validation flag.
     *
     * @param bool $skip Whether to skip validation in the model (true by default).
     *
     * @return BF_Model An instance of this class to allow method chaining.
     */
    public function skip_validation($skip = true)
    {
        $this->skip_validation = (bool) $skip;
        return $this;
    }

    //--------------------------------------------------------------------------
    // !OBSERVERS
    //--------------------------------------------------------------------------

    /**
     * Set the created date for the row.
     *
     * Uses the current date/time, the model's $date_format, and places the value
     * in the model's $created_field. Will not overwrite existing.
     *
     * @param array $row The array of data to be inserted.
     *
     * @return array The row data.
     */
    public function created_on($row)
    {
        if (! array_key_exists($this->created_field, $row)) {
            $row[$this->created_field] = $this->set_date();
        }

        return $row;
    }

    /**
     * Set the modified date for the row.
     *
     * Uses the current date/time, the model's $date_format, and places the value
     * in the model's $modified_field. Will not overwrite existing.
     *
     * @param array $row The array of data to be inserted.
     *
     * @return array The row data
     */
    public function modified_on($row)
    {
        if (! array_key_exists($this->modified_field, $row)) {
            $row[$this->modified_field] = $this->set_date();
        }

        return $row;
    }

    //--------------------------------------------------------------------------
    // !UTILITY FUNCTIONS
    //--------------------------------------------------------------------------

    /**
     * Trigger a model-specific event and calls each of its observers.
     *
     * @param string $event The name of the event to trigger.
     * @param mixed  $data  The data to be passed to the callback functions.
     *
     * @return mixed
     */
    public function trigger($event, $data = false)
    {
        if (! isset($this->$event) || ! is_array($this->$event)) {
            return $data;
        }

        foreach ($this->$event as $method) {
            if (strpos($method, '(')) {
                preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);
                $this->callback_parameters = explode(',', $matches[3]);
            }

            $data = call_user_func_array([$this, $method], [$data]);
        }

        return $data;
    }

    /**
     * Get the validation rules for the model.
     *
     * @uses $empty_validation_rules Observer to generate validation rules if
     * they are empty.
     *
     * @param string $type The type of validation rules to retrieve: 'update' or
     * 'insert'. If 'insert', appends rules set in $insert_validation_rules.
     *
     * @return array The validation rules for the model or an empty array.
     */
    public function get_validation_rules($type = 'update')
    {
        $temp_validation_rules = $this->validation_rules;

        // When $validation_rules is empty (or not an array), try to generate the
        // rules by triggering the $empty_validation_rules observer.
        if (empty($temp_validation_rules) || ! is_array($temp_validation_rules)) {
            $temp_validation_rules = $this->trigger('empty_validation_rules', $temp_validation_rules);
            if (empty($temp_validation_rules) || ! is_array($temp_validation_rules)) {
                return [];
            }

            // If the observer returns a non-empty array, prevent calling it again
            // for this instance of the model.
            $this->validation_rules = $temp_validation_rules;
        }

        // If this is not an insert or there are no insert rules, it's time to go.
        if ($type != 'insert'
            || ! is_array($this->insert_validation_rules)
            || empty($this->insert_validation_rules)
        ) {
            return $temp_validation_rules;
        }

        // Update the validation rules with the insert rules.

        // Get the index for each field in the validation rules.
        $fieldIndexes = [];
        foreach ($temp_validation_rules as $key => $validation_rule) {
            $fieldIndexes[$validation_rule['field']] = $key;
        }

        foreach ($this->insert_validation_rules as $key => $rule) {
            if (is_array($rule)) {
                $insert_rule = $rule;
            } else {
                // If $key isn't a field name and $insert_rule isn't an array,
                // there's nothing useful to do, so skip it.
                if (is_numeric($key)) {
                    continue;
                }

                $insert_rule = [
                    'field' => $key,
                    'rules' => $rule,
                ];
            }

            // If the field is already in the validation rules, update the
            // validation rule to merge the insert rule (replace empty rules).
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

        return $temp_validation_rules;
    }

    /**
     * Validate the $data passed into it.
     *
     * Uses the form_validation rules setup in $this->validation_rules.
     *
     * If $type == 'insert', any additional rules in $insert_validation_rules for
     * that field will be added to the rules.
     *
     * @param array $data  An array of data to validate.
     * @param string $type Either 'update' or 'insert'.
     *
     * @return array/bool The original data or false.
     */
    public function validate($data, $type = 'update')
    {
        if ($this->skip_validation) {
            return $data;
        }

        $current_validation_rules = $this->get_validation_rules($type);
        if (empty($current_validation_rules)) {
            return $data;
        }

        // @todo: considering replacing this with form_validation->set_data($data),
        // but what if some of the data is in $_POST but not in $data? Maybe we
        // should loop through $current_validation_rules and retrieve $_POST data
        // for any fields which are not in $data... Don't forget to move this below
        // $this->load->library('form_validation') if changing it to set_data().
        foreach ($data as $key => $val) {
            $_POST[$key] = $val;
        }

        $this->load->library('form_validation');

        // $current_validation_rules can be an array of rules, which is passed to
        // set_rules(), or a string which is passed to run(). If it is a string,
        // run() will attempt to load the rules from a config file, otherwise, run()
        // will ignore any input.
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

    /**
     * Protect attributes by removing them from $row array.
     *
     * Useful for removing the primary key, or submit button names if $_POST is
     * thrown at the model.
     *
     * @param object|array $row The data from which $this->protected_attributes
     * will be removed.
     *
     * @return array The data without the protected attributes.
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

    /**
     * A utility function to allow child models to use the preferred date/time format.
     * This is primarily used for setting $created_on and $modified_on values, but
     * can be used for other fields as well.
     *
     * The available time formats are:
     * 'int'      - Stores the date as an integer timestamp.
     * 'datetime' - Stores the date and time in the SQL datetime format.
     * 'date'     - Stores teh date (only) in the SQL date format.
     *
     * @param mixed $user_date An optional PHP timestamp to be converted.
     *
     * @return int|null|string The current/user time converted to the model's format.
     */
    protected function set_date($user_date = null)
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
                // no break;
            default:
                $dateFormat = 'U';
                break;
        }

        return (strtolower($this->config->item('time_reference')) == 'gmt' ?
                gmdate($dateFormat, $curr_date) :
                date($dateFormat, $curr_date));
    }

    /**
     * Return the method name for the current return type.
     *
     * @todo custom_result_object support.
     *
     * @param bool $multi Whether to return a single row (false, by default) or
     * an array of rows (true).
     *
     * @return string The name of the method.
     */
    protected function _return_type($multi = false)
    {
        $method = $multi ? 'result' : 'row';

        // If the type is 'array' or 'json', simply use the array version of the
        // function, since the database library doesn't support json directly.
        return $this->temp_return_type == 'array' ? "{$method}_array" : $method;
    }

    /**
     * Retrieve error messages from the database
     *
     * @return string
     */
    protected function get_db_error_message()
    {
        $error = $this->db->error();
        return isset($error['message']) ? $error['message'] : '';
    }

    /**
     * Allow setting the table to use for all methods during runtime.
     *
     * @param string $table The table name to use (do not include the prefix!).
     *
     * @return void
     */
    public function set_table($table = '')
    {
        $this->table_name = $table;
    }

    /**
     * Get the table name.
     *
     * @return string $this->table_name (current model table name).
     */
    public function get_table()
    {
        return $this->table_name;
    }

    /**
     * Get the table's primary key.
     *
     * @return string $this->key (current model table primary key).
     */
    public function get_key()
    {
        return $this->key;
    }

    /**
     * Get the name of the created by field.
     *
     * @return string The name of the field or an empty string.
     */
    public function get_created_by_field()
    {
        if ($this->set_created && $this->log_user) {
            return $this->created_by_field;
        }

        return '';
    }

    /**
     * Get the name of the created field.
     *
     * @return string The name of the field or an empty string.
     */
    public function get_created_field()
    {
        if ($this->set_created) {
            return $this->created_field;
        }

        return '';
    }

    /**
     * Get the name of the deleted field.
     *
     * @return string The name of the field if $soft_deletes is enabled, else an
     * empty string.
     */
    public function get_deleted_field()
    {
        if ($this->soft_deletes) {
            return $this->deleted_field;
        }

        return '';
    }

    /**
     * Get the name of the deleted by field.
     *
     * @return string The name of the field or an empty string.
     */
    public function get_deleted_by_field()
    {
        if ($this->soft_deletes && $this->log_user) {
            return $this->deleted_by_field;
        }

        return '';
    }

    /**
     * Get the metadata for the model's database fields.
     *
     * Returns the model's database field metadata stored in $this->field_info if
     * set, else it tries to retrieve the metadata from $this->db->field_data($this->table_name);
     *
     * @todo The MongoDB driver is the only one that doesn't appear to support
     * $this->db->field_data, though it's possible other drivers don't support
     * more extensive metadata (such as type/max_length) supported by MySQL.
     *
     * @return array Returns the database field metadata for this model.
     */
    public function get_field_info()
    {
        if (empty($this->field_info)) {
            $this->field_info = $this->db->field_data($this->table_name);
        }

        return $this->field_info;
    }

    /**
     * Get the name of the modified by field.
     *
     * @return string The name of the field or an empty string.
     */
    public function get_modified_by_field()
    {
        if ($this->set_modified && $this->log_user) {
            return $this->modified_by_field;
        }

        return '';
    }

    /**
     * Get the name of the modified field.
     *
     * @return string The name of the field or an empty string.
     */
    public function get_modified_field()
    {
        if ($this->set_modified) {
            return $this->modified_field;
        }

        return '';
    }

    /**
     * Set the $date_format to use for setting created_on and modified_on values.
     *
     * @param string $format The date format to use: 'int', 'datetime', or 'date'.
     *
     * @return bool False if $format is not supported, otherwise true.
     */
    public function set_date_format($format = 'int')
    {
        if ($format != 'int' && $format != 'datetime' && $format != 'date') {
            return false;
        }
        $this->date_format = $format;

        return true;
    }

    /**
     * Set whether $modified_on dates should be auto-created in the update method.
     *
     * @param bool $modified If true, the $modified_on date will be set.
     *
     * @return bool If $modified is a value other than true or false, returns false,
     * otherwise returns true.
     */
    public function set_modified($modified = true)
    {
        // Micro-optimization note:
        // Comparison to true and false is faster than is_bool(), because it's a
        // function call.
        // === false || === true is faster than !== true && !== false, because
        // === true will only be compared for values other than false.
        if ($modified === false || $modified === true) {
            $this->set_modified = $modified;

            return true;
        }

        return false;
    }

    /**
     * Log an error to the Console (if loaded) and to the log files.
     *
     * @param string $message The string to write to the logs.
     * @param string $level   The log level, as per CI log_message method.
     *
     * @return bool|void False if $message is empty.
     */
    protected function logit($message = '', $level = 'debug')
    {
        if (empty($message)) {
            return false;
        }

        if (class_exists('Console', false)) {
            Console::log($message);
        }

        log_message($level, $message);
    }

    /**
     * Extract the model's fields (except the key and those handled by Observers)
     * from the $post_data and return an array of name => value pairs.
     *
     * @param array $post_data The post data, usually $this->input->post() when
     * called from the controller.
     *
     * @return array An array of name => value pairs containing the data for the
     * model's fields.
     */
    public function prep_data($post_data)
    {
        $data = [];
        $skippedFields = [];
        $skippedFields[] = $this->get_created_field();
        $skippedFields[] = $this->get_created_by_field();
        $skippedFields[] = $this->get_deleted_field();
        $skippedFields[] = $this->get_deleted_by_field();
        $skippedFields[] = $this->get_modified_field();
        $skippedFields[] = $this->get_modified_by_field();

        // Though the model doesn't support multiple keys well, $this->key could
        // be an array or a string.
        $skippedFields = array_merge($skippedFields, (array) $this->key);

        // If the field is the primary key, one of the created/modified/deleted
        // fields, or has not been set in the $post_data, skip it.
        foreach ($this->get_field_info() as $field) {
            if (isset($field->primary_key)
                && $field->primary_key
                || in_array($field->name, $skippedFields)
                || ! isset($post_data[$field->name])
            ) {
                continue;
            }

            $data[$field->name] = $post_data[$field->name];
        }

        return $data;
    }

    //--------------------------------------------------------------------------
    // CI Database  Wrappers
    //--------------------------------------------------------------------------
    //
    // To allow for more expressive syntax, wrapper functions are provided for most
    // of the query builder methods.
    //
    // This allows for calls such as:
    //
    //      $result = $this->model->select('...')
    //                            ->where('...')
    //                            ->having('...')
    //                            ->get();
    //

    /**
     * Generates the select portion of the query.
     *
     * @param string $select The select portion of the query.
     * @param bool   $escape Whether to escape the select portion of the query.
     *
     * @return BF_Model
     */
    public function select($select = '*', $escape = null)
    {
        $this->db->select($select, $escape);
        return $this;
    }

    /**
     * Generates a select max($select) portion of a query.
     *
     * @param string $select The field to put inside the max() portion of the query.
     * @param string $alias  An alias for the max() value.
     *
     * @return BF_Model
     */
    public function select_max($select = '', $alias = '')
    {
        $this->db->select_max($select, $alias);
        return $this;
    }

    /**
     * Generates a select min($select) portion of a query.
     *
     * @param string $select The field to put inside the min() portion of the query.
     * @param string $alias  An alias for the min() value.
     *
     * @return BF_Model
     */
    public function select_min($select = '', $alias = '')
    {
        $this->db->select_min($select, $alias);
        return $this;
    }

    /**
     * Generates a select avg($select) portion of a query.
     *
     * @param string $select The field to put inside the avg() portion of the query.
     * @param string $alias  An alias for the avg() value.
     *
     * @return BF_Model
     */
    public function select_avg($select = '', $alias = '')
    {
        $this->db->select_avg($select, $alias);
        return $this;
    }

    /**
     * Generates a select sum($select) portion of a query.
     *
     * @param  string $select The field to put inside the sum() portion of the query.
     * @param  string $alias  An alias for the sum() value.
     *
     * @return BF_Model
     */
    public function select_sum($select = '', $alias = '')
    {
        $this->db->select_sum($select, $alias);
        return $this;
    }

    /**
     * Set a flag which tells the query string compiler to add a distinct clause.
     *
     * @param bool $val Whether to add the distinct clause (true by default).
     *
     * @return BF_Model
     */
    public function distinct($val = true)
    {
        $this->db->distinct($val);
        return $this;
    }

    /**
     * Generates the from portion of the query.
     *
     * @param  string|array $from The from clause.
     *
     * @return BF_Model
     */
    public function from($from)
    {
        $this->db->from($from);
        return $this;
    }

    /**
     * Generates the join portion of the query.
     *
     * @param string $table  The name of the table to join.
     * @param string $cond   The join condition.
     * @param string $type   The type of join, case insensitive (valid values are
     * 'left', 'right', 'inner', 'outer', 'left outer', or 'right outer').
     *
     * @return BF_Model
     */
    public function join($table, $cond, $type = '')
    {
        $this->db->join($table, $cond, $type);
        return $this;
    }

    /**
     * Generates the where clause of the query, combining multiple clauses with
     * 'or'.
     *
     * @param string|array $key The key/field name or an array of key/value pairs.
     * @param string       $value  The value for the where clause if $key is a string.
     * @param bool         $escape Whether to escape identifiers.
     *
     * @return BF_Model
     */
    public function or_where($key, $value = null, $escape = null)
    {
        $this->db->or_where($key, $value, $escape);
        return $this;
    }

    /**
     * Generates a where $key in($values) clause.
     *
     * @param string|array $key    The key/field name or an array of key/value pairs.
     * @param string|array $values The value(s).
     *
     * @return BF_Model
     */
    public function where_in($key = null, $values = null)
    {
        $this->db->where_in($key, $values);
        return $this;
    }

    /**
     * Generates a where $key in($values) clause, combining multiple clauses with
     * 'or'.
     *
     * @param string|array $key    The key/field name or an array of key/value pairs.
     * @param string|array $values The value(s).
     *
     * @return BF_Model
     */
    public function or_where_in($key = null, $values = null)
    {
        $this->db->or_where_in($key, $values);
        return $this;
    }

    /**
     * Generates a where $key not in ($values) clause.
     *
     * @param string|array $key    The key/field name or an array of key/value pairs.
     * @param string|array $values The value(s).
     *
     * @return BF_Model
     */
    public function where_not_in($key = null, $values = null)
    {
        $this->db->where_not_in($key, $values);
        return $this;
    }

    /**
     * Generates a where $key not in ($values) clause, combining multiple clauses
     * with 'or'.
     *
     * @param string|array $key    The key/field name or an array of key/value pairs.
     * @param string|array $values The value(s).
     *
     * @return BF_Model
     */
    public function or_where_not_in($key = null, $values = null)
    {
        $this->db->or_where_not_in($key, $values);
        return $this;
    }

    /**
     * Generates a like clause.
     *
     * @param string|array $field  The field name or an array of key/value pairs.
     * @param string       $match  The value to match in the $field (if $field is
     * a string).
     * @param string       $side   The type of comparison to use, case insensitive
     * (valid values are 'none', 'before', 'after', anything else defaults to 'both').
     *
     * @return BF_Model
     */
    public function like($field, $match = '', $side = 'both')
    {
        $this->db->like($field, $match, $side);
        return $this;
    }

    /**
     * Generates a not like clause.
     *
     * @param string|array $field  The field name or an array of key/value pairs.
     * @param string       $match  The value to match in the $field (if $field is
     * a string).
     * @param string       $side   The type of comparison to use, case insensitive
     * (valid values are 'none', 'before', 'after', anything else defaults to 'both').
     *
     * @return BF_Model
     */
    public function not_like($field, $match = '', $side = 'both')
    {
        $this->db->not_like($field, $match, $side);
        return $this;
    }

    /**
     * Generates a like clause, separating multiple clauses with 'or'.
     *
     * @param string|array $field  The field name or an array of key/value pairs.
     * @param string       $match  The value to match in the $field (if $field is
     * a string).
     * @param string       $side   The type of comparison to use, case insensitive
     * (valid values are 'none', 'before', 'after', anything else defaults to 'both').
     *
     * @return BF_Model
     */
    public function or_like($field, $match = '', $side = 'both')
    {
        $this->db->or_like($field, $match, $side);
        return $this;
    }

    /**
     * Generates a not like clause, separating multiple clauses with 'or'.
     *
     * @param string|array $field  The field name or an array of key/value pairs.
     * @param string       $match  The value to match in the $field (if $field is
     * a string).
     * @param string       $side   The type of comparison to use, case insensitive
     * (valid values are 'none', 'before', 'after', anything else defaults to 'both').
     *
     * @return BF_Model
     */
    public function or_not_like($field, $match = '', $side = 'both')
    {
        $this->db->or_not_like($field, $match, $side);
        return $this;
    }

    /**
     * Generates a group by clause.
     *
     * @param string|array $by     The field(s) to group by.
     *
     * @return BF_Model
     */
    public function group_by($by)
    {
        $this->db->group_by($by);
        return $this;
    }

    /**
     * Generates a having clause.
     * @param string|array $key    The field or an array of field/value pairs.
     * @param string       $value  The value if $key is a string.
     * @param bool         $escape Whether to escape identifiers.
     *
     * @return BF_Model
     */
    public function having($key, $value = null, $escape = null)
    {
        $this->db->having($key, $value, $escape);
        return $this;
    }

    /**
     * Generates a having clause, separating multiple clauses with 'or'.
     *
     * @param string|array $key    The field or an array of field/value pairs.
     * @param string       $value  The value if $key is a string.
     * @param bool         $escape Whether to escape identifiers.
     *
     * @return BF_Model
     */
    public function or_having($key, $value = null, $escape = null)
    {
        $this->db->or_having($key, $value, $escape);
        return $this;
    }

    /**
     * Generates a limit clause.
     *
     * @param int $value The limit value.
     * @param int $offset The offset value.
     *
     * @return BF_Model
     */
    public function limit($value, $offset = 0)
    {
        $this->db->limit($value, $offset);
        return $this;
    }

    /**
     * Generates an offset clause.
     *
     * @param int $offset The offset value.
     *
     * @return BF_Model
     */
    public function offset($offset)
    {
        $this->db->offset($offset);
        return $this;
    }

    /**
     * Generates a set clause for an insert/update.
     *
     * @param mixed $key    The field name, or an object/array of field/values.
     * @param mixed $value  The value if $key is a field name.
     * @param bool  $escape Whether to escape identifiers.
     *
     * @return BF_Model
     */
    public function set($key, $value = '', $escape = null)
    {
        $this->db->set($key, $value, $escape);
        return $this;
    }
}
