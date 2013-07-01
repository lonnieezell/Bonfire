<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Settings Module Model
 *
 * Provides methods to retrieve and update settings in the database
 *
 * @package    Bonfire
 * @subpackage Modules_Settings
 * @category   Models
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Settings_model extends BF_Model
{

	/**
	 * Name of the table
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $table_name	= 'settings';

	/**
	 * Name of the primary key
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $key			= 'name';

	/**
	 * Use soft deletes or not
	 *
	 * @access protected
	 *
	 * @var bool
	 */
	protected $soft_deletes	= FALSE;

	/**
	 * The date format to use
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $date_format = 'datetime';

	/**
	 * Set the created time automatically on a new record
	 *
	 * @access protected
	 *
	 * @var bool
	 */
	protected $set_created = FALSE;

	/**
	 * Set the modified time automatically on editing a record
	 *
	 * @access protected
	 *
	 * @var bool
	 */
	protected $set_modified = FALSE;

	//--------------------------------------------------------------------


	/**
	 * A convenience method that combines a where() and find_all()
	 * call into a single call.
	 *
	 * @access public
	 *
	 * @param string $field The table field to search in.
	 * @param string $value The value that field should be.
	 * @param string $type	The type of where clause to use, either 'and' or 'or'
	 *
	 * @return array
	 */
	public function find_all_by($field=NULL, $value=NULL, $type='and')
	{
		if (empty($field)) return FALSE;

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

		$results = $this->find_all();

		$return_array = array();

		if (is_array($results) && count($results))
		{
			foreach ($results as $record)
			{
				$return_array[$record->name] = $record->value;
			}
		}

		return $return_array;

	}//end find_all_by()

}//end Settings_model
