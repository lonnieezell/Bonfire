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
 * Activities
 *
 * Provides a simple and consistent way to record and display user-related activities
 * in both core- and custom-modules.
 *
 * @package    Bonfire
 * @subpackage Modules_Activities
 * @category   Models
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Activity_model extends BF_Model
{

	/**
	 * Name of the table
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $table_name = 'activities';

	/**
	 * Name of the primary key
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $key = 'activity_id';

	/**
	 * Use soft deletes or not
	 *
	 * @access protected
	 *
	 * @var bool
	 */
	protected $soft_deletes = TRUE;

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
	protected $set_created = TRUE;

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
	 * Returns all activities created by one or more modules.
	 *
	 * @access public
	 *
	 * @param array $modules Either a string or an array of module names.
	 *
	 * @return bool/array An array of activity objects.
	 */
	public function find_by_module($modules=array())
	{
		if (empty($modules))
		{
			logit('No module name given to `find_by_module`.');
			return FALSE;
		}

		if (!is_array($modules))
		{
			$modules = array($modules);
		}

		$this->db->where_in('module', $modules);
		$this->db->where('activities.deleted', 0);

		$this->db->select('activity_id, activities.user_id, activity, module, activities.created_on, display_name, username, email, last_login');
		$this->db->join('users', 'activities.user_id = users.id', 'left');

		return $this->find_all();

	}//end find_by_module()

	//--------------------------------------------------------------------

	/**
	 * Logs a new activity.
	 *
	 * @access public
	 *
	 * @param int    $user_id  An int id of the user that performed the activity.
	 * @param string $activity A string detailing the activity. Max length of 255 chars.
	 * @param string $module   The name of the module that set the activity.
	 *
	 * @return bool An int with the ID of the new object, or FALSE on failure.
	 */
	public function log_activity($user_id=null, $activity='', $module='any')
	{
		if (empty($user_id) || !is_integer($user_id) || $user_id == 0 )
		{
			Template::set_message('You must provide a numeric user id to log activity.','error');
			return FALSE;
		}
		else if (empty($activity))
		{
			Template::set_message('Not enough information provided to insert activity.','error');
			return FALSE;
		}

		$data = array(
			'user_id'	=> $user_id,
			'activity'	=> $activity,
			'module'	=> $module
		);

		return parent::insert($data);
	}//end log_activity()

	//--------------------------------------------------------------------

}//end class
