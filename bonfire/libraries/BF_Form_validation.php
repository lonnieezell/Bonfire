<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Form Validation
 *
 * This class extends the CodeIgniter core Form_validation library to add extra
 * functionality used in Bonfire.
 *
 * @package    Bonfire\Libraries\BF_Form_validation
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/guides
 */
class BF_Form_validation extends CI_Form_validation
{
    /**
     * @var object The CodeIgniter core object.
     */
    public $CI;

    //--------------------------------------------------------------------

    /**
     * Constructor
     *
     * @return void
     */
    function __construct($config = array())
    {
        // Merged super-global $_FILES to $_POST to allow for better file
        // validation inside of Form_validation library
        if ( ! empty($_FILES) && is_array($_FILES)) {
            $_POST = array_merge($_POST, $_FILES);
        }

        parent::__construct($config);
    }

    /**
     * Check if the field has an error associated with it.
     *
     * @param string $field The name of the field
     *
     * @return bool
     */
    public function has_error($field = null)
    {
        if (empty($field)) {
            return false;
        }

        return ! empty($this->_field_data[$field]['error']);
    }

    /**
     * Performs the actual form validation
     *
     * @param string $module Name of the module
     * @param string $group  Name of the group array containing the rules
     *
     * @return bool Success or Failure
     */
    public function run($module = '', $group = '')
    {
        is_object($module) && $this->CI =& $module;
        return parent::run($group);
    }

    /**
     * Returns Form Validation Errors in an HTML Un-ordered list format.
     *
     * @return string|bool Form Validation Errors in an HTML Un-ordered list, or
     * false when no errors are returned.
     */
    public function validation_errors_list()
    {
        $errors = $this->CI->form_validation->error_string('<li>', '</li>');
        if (empty($errors)) {
            return false;
        }

        return '<ul>' . PHP_EOL . "{$errors}</ul>";
    }

    //--------------------------------------------------------------------------
    // Validation Rules
    //--------------------------------------------------------------------------

    /**
     * Set allowed file-types in your form_validation rules.
     *
     * Please separate the allowed file types with a pipe or |.
     *
     * @author Shawn Crigger <support@s-vizion.com>
     *
     * @param string $str   String field name to validate
     * @param string $types String allowed types
     *
     * @return bool If files are in the allowed type array then TRUE else FALSE
     */
    public function allowed_types($str, $types = null)
    {
        if ( ! $types) {
            log_message('debug', 'form_validation method allowed_types was called without any allowed types.');
            $this->CI->form_validation->set_message('allowed_types', lang('bf_form_allowed_types_none'));
            return false;
        }

        $type = explode('|', $types);
        $filetype = pathinfo($str['name'], PATHINFO_EXTENSION);

        if (in_array($filetype, $type)) {
            return true;
        }

        $this->CI->form_validation->set_message('allowed_types', lang('bf_form_allowed_types'));
        return false;
    }

    /**
     * Check that a string only contains Alpha-numeric characters with periods,
     * underscores, spaces, and dashes
     *
     * @param string $str The string value to check
     *
     * @return	bool
     */
    function alpha_extra($str)
    {
        if (preg_match("/^([\.\s-a-z0-9_-])+$/i", $str)) {
            return true;
        }

        $this->CI->form_validation->set_message('alpha_extra', lang('bf_form_alpha_extra'));
        return false;
    }

    /**
     * Check that the string matches a specific regex pattern
     *
     * @param string $str     The string to check
     * @param string $pattern The pattern used to check the string
     *
     * @return bool
     */
    function matches_pattern($str, $pattern)
    {
        if (preg_match('/^' . $pattern . '$/', $str)) {
            return true;
        }

        $this->CI->form_validation->set_message('matches_pattern', lang('bf_form_matches_pattern'));
        return false;
    }

    /**
     * Set maximum file upload size in your form validation rules.
     *
     * @author Shawn Crigger <support@s-vizion.com>
     *
     * @param string  $str  String field name to validate
     * @param integer $size Integer maximum upload size in bytes
     *
     * @return bool
     */
    public function max_file_size($str, $size = 0)
    {
        if (empty($size)) {
            log_message('error', 'Form_validation rule, max_file_size was called without setting an allowable file size.');
            $this->CI->form_validation->set_message('max_file_size', str_replace('{max_size}', '0', lang('bf_form_max_file_size')));
            return false;
        }

        if ($str['size'] <= $size) {
            return true;
        }

        $this->CI->form_validation->set_message('max_file_size', str_replace('{max_size}', $size, lang('bf_form_max_file_size')));
        return false;
    }

    /**
     * Verify that the entered string is one of the values entered as the second
     * parameter.
     *
     * Please separate the allowed values with a comma.
     *
     * @param string $str     String field name to validate
     * @param string $options String allowed values
     *
     * @return bool If files are in the allowed type array then TRUE else FALSE
     */
    public function one_of($str, $options = null)
    {
        if ( ! $options) {
            log_message('debug', 'form_validation method one_of was called without any possible values.');
            $this->CI->form_validation->set_message('one_of', lang('bf_form_one_of_none'));
            return false;
        }

        log_message('debug', "form_validation one_of options: {$options}");
        $possible_values = explode(',', $options);
        if (in_array($str, $possible_values)) {
            return true;
        }

        $this->CI->form_validation->set_message('one_of', lang('bf_form_one_of'));
        return false;
    }

    /**
     * Checks that a value is unique in the database.
     *
     * i.e. '…|required|unique[users.name,users.id]|trim…'
     *
     * <code>
     * "unique[tablename.fieldname,tablename.(primaryKey-used-for-updates)]"
     * </code>
     *
     * @author Adapted from Burak Guzel <http://net.tutsplus.com/tutorials/php/6-codeigniter-hacks-for-the-masters/>
     *
     * @param mixed $value  The value to be checked.
     * @param mixed $params The table and field to check against, if a second
     * field is passed in this is used as "AND NOT EQUAL".
     *
     * @return bool True if the value is unique for that field, else false.
     */
    function unique($value, $params)
    {
        // Allow for more than 1 parameter.
        $fields = explode(",", $params);

        // Extract the table and field from the first parameter.
        list($table, $field) = explode('.', $fields[0], 2);

        // Setup the db request.
        $this->CI->db->select($field)
                     ->from($table)
                     ->where($field, $value)
                     ->limit(1);

        // Check whether a second parameter was passed to be used as an
        // "AND NOT EQUAL" where clause
        // eg "select * from users where users.name='test' AND users.id != 4
        if (isset($fields[1])) {
            // Extract the table and field from the second parameter
            list($where_table, $where_field) = explode('.', $fields[1], 2);

            // Get the value from the post's $where_field. If the value is set,
            // add "AND NOT EQUAL" where clause.
            $where_value = $this->CI->input->post($where_field);
            if (isset($where_value)) {
                $this->CI->db->where("{$where_table}.{$where_field} !=", $where_value);
            }
        }

        // If any rows are returned from the database, validation fails
        $query = $this->CI->db->get();
        if ($query->row()) {
            $this->CI->form_validation->set_message('unique', lang('bf_form_unique'));
            return false;
        }

        return true;
    }

    /**
     * Check the entered password against the password strength settings.
     *
     * @param string $str The password string to check
     *
     * @return bool
     */
    public function valid_password($str)
    {
        // Get the password strength settings from the database
        $min_length	= $this->CI->settings_lib->item('auth.password_min_length');
        $use_nums   = $this->CI->settings_lib->item('auth.password_force_numbers');
        $use_syms   = $this->CI->settings_lib->item('auth.password_force_symbols');
        $use_mixed  = $this->CI->settings_lib->item('auth.password_force_mixed_case');

        // Check length
        if (strlen($str) < $min_length) {
            $this->CI->form_validation->set_message('valid_password', str_replace('{min_length}', $min_length, lang('bf_form_valid_password')));
            return false;
        }

        // Check numbers
        if ($use_nums && 0 === preg_match('/[0-9]/', $str)) {
            $this->CI->form_validation->set_message('valid_password', lang('bf_form_valid_password_nums'));
            return false;
        }

        // Check symbols
        if ($use_syms && 0 === preg_match('/[!@#$%^&*()._]/', $str)) {
            $this->CI->form_validation->set_message('valid_password', lang('bf_form_valid_password_syms'));
            return false;
        }

        // Mixed Case?
        if ($use_mixed) {
            if (0 === preg_match('/[A-Z]/', $str)) {
                $this->CI->form_validation->set_message('valid_password', lang('bf_form_valid_password_mixed_1'));
                return false;
            }

            if (0 === preg_match('/[a-z]/', $str)) {
                $this->CI->form_validation->set_message('valid_password', lang('bf_form_valid_password_mixed_2'));
                return false;
            }
        }

        return true;
    }
}
/* End of file : /libraries/BF_Form_validation.php */