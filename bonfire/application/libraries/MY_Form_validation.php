<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * Form Validation
 *
 * This class extends the CodeIgniter core Form_validation library to add
 * extra functionality used in Bonfire.
 *
 * @package    Bonfire
 * @subpackage Libraries
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/core/form_validation.html
 *
 */
class MY_Form_validation extends CI_Form_validation
{


	/**
	 * Stores the CodeIgniter core object.
	 *
	 * @access public
	 *
	 * @var object
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
		// Merged super-global $_FILES to $_POST to allow for better file validation inside of Form_validation library
		$_POST = (isset($_FILES) && is_array($_FILES) && count($_FILES) > 0) ? array_merge($_POST,$_FILES) : $_POST;

		parent::__construct($config);

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Returns Form Validation Errors in a HTML Un-ordered list format.
	 *
	 * @access public
	 *
	 * @return string Returns Form Validation Errors in a HTML Un-ordered list format.
	 */
	public function validation_errors_list()
	{
		if (is_array($this->CI->form_validation->_error_array))
		{
			$errors = (array) $this->CI->form_validation->_error_array;
			$error  = '<ul>' . PHP_EOL;

			foreach ($errors as $error)
			{
				$error .= "	<li>{$error}</li>" . PHP_EOL;
			}

			$error .= '</ul>' . PHP_EOL;
			return $error;
		}

		return FALSE;

	}//end validation_errors_list()

	//--------------------------------------------------------------------

	/**
	 * Performs the actual form validation
	 *
	 * @access public
	 *
	 * @param string $module Name of the module
	 * @param string $group  Name of the group array containing the rules
	 *
	 * @return bool Success or Failure
	 */
	public function run($module='', $group='')
	{
		(is_object($module)) AND $this->CI =& $module;
		return parent::run($group);

	}//end run()

	//--------------------------------------------------------------------



	/**
	 * Checks that a value is unique in the database
	 *
	 * i.e. '…|required|unique[users.name.id.4]|trim…'
	 *
	 * @abstract Rule to force value to be unique in table
	 * @usage "unique[tablename.fieldname.(primaryKey-used-for-updates).(uniqueID-used-for-updates)]"
	 * @access public
	 *
	 * @param mixed $value  The value to be checked
	 * @param mixed $params The table and field to check against, if a second field is passed in this is used as "AND NOT EQUAL"
	 *
	 * @return bool
	 */
	function unique($value, $params)
	{
		$this->CI->form_validation->set_message('unique', 'The value in &quot;%s&quot; is already being used.');

		// allow for more than 1 parameter
		$fields = explode(",", $params);

		// extract the first parameter
		list($table, $field) = explode(".", $fields[0], 2);

		// setup the db request
		$this->CI->db->select($field)->from($table)
			->where($field, $value)->limit(1);

		// check if there is a second field passed in
		if (isset($fields[1]))
		{
			// this field is used to check that it is not the current record
			// eg select * from users where username='test' AND id != 4

			list($where_table, $where_field) = explode(".", $fields[1], 2);

			$where_value = $this->CI->input->post($where_field);
			if (isset($where_value))
			{
				// add the extra where condition
				$this->CI->db->where($where_field.' !=', $this->CI->input->post($where_field));
			}
		}

		// make the db request
		$query = $this->CI->db->get();

		if ($query->row())
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}

	}//end unique()

	// --------------------------------------------------------------------

	/**
	 * Check that a string only contains Alpha-numeric characters with
	 * periods, underscores, spaces and dashes
	 *
	 * @abstract Alpha-numeric with periods, underscores, spaces and dashes
	 * @access public
	 *
	 * @param string $str The string value to check
	 *
	 * @return	bool
	 */
	function alpha_extra($str)
	{
		$this->CI->form_validation->set_message('alpha_extra', 'The %s field may only contain alpha-numeric characters, spaces, periods, underscores, and dashes.');
		return ( ! preg_match("/^([\.\s-a-z0-9_-])+$/i", $str)) ? FALSE : TRUE;

	}//end alpha_extra()

	// --------------------------------------------------------------------

	/**
	 * Check that the string matches a specific regex pattern
	 *
	 * @access public
	 *
	 * @param string $str     The string to check
	 * @param string $pattern The pattern used to check the string
	 *
	 * @return bool
	 */
	function matches_pattern($str, $pattern)
	{
		if (preg_match('/^' . $pattern . '$/', $str))
		{
			return TRUE;
		}

		$this->CI->form_validation->set_message('matches_pattern', 'The %s field does not match the required pattern.');

		return FALSE;

	}//end matches_pattern()

	// --------------------------------------------------------------------

	/**
	 * Check if the field has an error associated with it.
	 *
	 * @access public
	 *
	 * @param string $field The name of the field
	 *
	 * @return bool
	 */
	public function has_error($field=null)
	{
		if (empty($field))
		{
			return FALSE;
		}

		return !empty($this->_field_data[$field]['error']) ? TRUE : FALSE;

	}//end has_error()

	//--------------------------------------------------------------------


	/**
	 * Check the entered password against the password strength settings.
	 *
	 * @access public
	 *
	 * @param string $str The password string to check
	 *
	 * @return bool
	 */
	public function valid_password($str)
	{
		// get the password strength settings from the database
		$min_length	= $this->CI->settings_lib->item('auth.password_min_length');
		$use_nums   = $this->CI->settings_lib->item('auth.password_force_numbers');
		$use_syms   = $this->CI->settings_lib->item('auth.password_force_symbols');
		$use_mixed  = $this->CI->settings_lib->item('auth.password_force_mixed_case');

		// Check length
		if (strlen($str) < $min_length)
		{
			$this->CI->form_validation->set_message('valid_password', 'The %s field must be at least '. $min_length .' characters long');
			return FALSE;
		}

		// Check numbers
		if ($use_nums)
		{
			if (0 === preg_match('/[0-9]/', $str))
			{
				$this->CI->form_validation->set_message('valid_password', '%s must contain at least 1 number.');
				return FALSE;
			}
		}

		// Check Symbols
		if ($use_syms)
		{
			if (0 === preg_match('/[!@#$%^&*()._]/', $str))
			{
				$this->CI->form_validation->set_message('valid_password', '%s must contain at least 1 punctuation mark.');
				return FALSE;
			}
		}

		// Mixed Case?
		if ($use_mixed)
		{
			if (0 === preg_match('/[A-Z]/', $str))
			{
				$this->CI->form_validation->set_message('valid_password', '%s must contain at least 1 uppercase characters.');
				return FALSE;
			}

			if (0 === preg_match('/[a-z]/', $str))
			{
				$this->CI->form_validation->set_message('valid_password', '%s must contain at least 1 lowercase characters.');
				return FALSE;
			}
		}

		return TRUE;

	}//end valid_password()

	//--------------------------------------------------------------------

	/**
	 * Allows setting allowed file-types in your form_validation rules.
	 * Please separate the allowed file types with a pipe or |.
	 *
	 * @author Shawn Crigger <support@s-vizion.com>
	 * @access public
	 *
	 * @param string $str   String field name to validate
	 * @param string $types String allowed types
	 *
	 * @return bool If files are in the allowed type array then TRUE else FALSE
	 */
	public function allowed_types($str, $types = NULL)
	{
		if (!$types)
		{
			log_message('debug', 'form_validation method allowed_types was called without any allowed types.');
			return FALSE;
		}

		$type = explode('|', $types);
		$filetype = pathinfo($str['name'],PATHINFO_EXTENSION);

		if (!in_array($filetype, $type))
		{
			$this->CI->form_validation->set_message('allowed_types', '%s must contain one of the allowed selections.');
			return FALSE;
		}

		return TRUE;

	}//end allowed_types()

	//--------------------------------------------------------------------

	/**
	 * Checks that the entered string is one of the values entered as the second parameter.
	 * Please separate the allowed file types with a comma.
	 *
	 * @access public
	 *
	 * @param string $str      String field name to validate
	 * @param string $options String allowed values
	 *
	 * @return bool If files are in the allowed type array then TRUE else FALSE
	 */
	public function one_of($str, $options = NULL)
	{
		if (!$options)
		{
			log_message('debug', 'form_validation method one_of was called without any possible values.');
			return FALSE;
		}

		log_message('debug', 'form_validation one_of options:'.$options);

		$possible_values = explode(',', $options);

		if (!in_array($str, $possible_values))
		{
			$this->CI->form_validation->set_message('one_of', '%s must contain one of the available selections.');
			return FALSE;
		}

		return TRUE;

	}//end one_of()

	//--------------------------------------------------------------------

	/**
	 * Allows Setting maximum file upload size in your form validation rules.
	 *
	 * @author Shawn Crigger <support@s-vizion.com>
	 * @access public
	 *
	 * @param string  $str  String field name to validate
	 * @param integer $size Integer maximum upload size in bytes
	 *
	 * @return bool
	 */
	public function max_file_size($str, $size = 0)
	{
		if ($size == 0)
		{
			log_message('error', 'Form_validation rule, max_file_size was called without setting a allowable file size.');
			return FALSE;
		}

		return (bool) ($str['size']<=$size);

	}//end max_file_size()

	//--------------------------------------------------------------------

}//end class

//--------------------------------------------------------------------
// Helper Functions for Form Validation LIbrary
//--------------------------------------------------------------------

	/**
	 * Check if the form has an error
	 *
	 * @access public
	 *
	 * @param string $field Name of the field
	 *
	 * @return bool
	 */
	function form_has_error($field=null)
	{

		if (FALSE === ($OBJ =& _get_validation_object()))
		{
			return FALSE;
		}

		$return = $OBJ->has_error($field);

		return $return;
	}//end form_has_error()

//--------------------------------------------------------------------


/* Author :  http://net.tutsplus.com/tutorials/php/6-codeigniter-hacks-for-the-masters/ */
/* End of file : ./libraries/MY_Form_validation.php */