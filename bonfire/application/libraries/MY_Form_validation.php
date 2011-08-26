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
	 * @param mixed $params the table and field to check against
	 * @return bool
	 */
	function unique($value, $params) {
		$this->CI->form_validation->set_message('unique', 'The value in &quot;%s&quot; is already being used.');

		list($table, $field, $key, $id) = explode(".", $params, 4);

		$query = $this->CI->db->select($field)->from($table)
			->where($field, $value)->where($key.' != '.$id)->limit(1)->get();

		if ($query->row()) {
			return false;
		} else {
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
}

/* Author :  http://net.tutsplus.com/tutorials/php/6-codeigniter-hacks-for-the-masters/ */
/* End of file : ./libraries/MY_Form_validation.php */