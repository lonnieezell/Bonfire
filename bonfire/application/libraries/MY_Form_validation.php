<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    
    /**
     * MY_Form_validation::__construct()
     * 
     * @return
     */
    function __construct() {
        parent::__construct();      
    }

	// --------------------------------------------------------------------

	public function run($module='', $group='') 
	{
		(is_object($module)) AND $this->CI =& $module;
		return parent::run($group);
	}
	
	//--------------------------------------------------------------------
	


	/**
	 * MY_Form_validation::unique()
	 * 
	 * i.e. '…|required|unique[bf_users.name.id.4]|trim…'
	 * 
	 * @abstract Rule to force value to be unique in table
	 * @usage "unique[tablename.fieldname.(primaryKey-used-for-updates).(uniqueID-used-for-updates)]"
	 * @param mixed $value the value to be checked
	 * @param mixed $params the table and field to check against, if a second field is passed in this is used as "AND NOT EQUAL"
	 * @return bool
	 */
	function unique($value, $params) {
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
			if (isset($where_value)) {
				// add the extra where condition
				$this->CI->db->where($where_field.' !=', $this->CI->input->post($where_field));
			}
		}

		// make the db request
		$query = $this->CI->db->get();
		
		if ($query->row())
		{
			return false;
		}
		else
		{
			return true;
		}

	}

	// --------------------------------------------------------------------

	/**
	 * MY_Form_validation::alpha_extra()
	 * 
	 * @abstract Alpha-numeric with periods, underscores, spaces and dashes
	 * @param string $str
	 * @return	bool
	 */
	function alpha_extra($str)
	{
		$this->CI->form_validation->set_message('alpha_extra', 'The %s field may only contain alpha-numeric characters, spaces, periods, underscores, and dashes.');
		return ( ! preg_match("/^([\.\s-a-z0-9_-])+$/i", $str)) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------
	
	/*
		Method: matches_pattern()
		
		Ensures a string matches a basic pattern
		
		Return: 
			bool
	*/
	function matches_pattern($str, $pattern)
	{
		if (preg_match('/^' . $pattern . '$/', $str)) return TRUE;
	
		$this->CI->form_validation->set_message('matches_pattern', 'The %s field does not match the required pattern.');
		return FALSE;
		
	}

	// --------------------------------------------------------------------
}

/* Author :  http://net.tutsplus.com/tutorials/php/6-codeigniter-hacks-for-the-masters/ */
/* End of file : ./libraries/MY_Form_validation.php */