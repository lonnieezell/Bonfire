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
	protected $soft_deletes = true;

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
	protected $set_created = true;

	/**
	 * Set the modified time automatically on editing a record
	 *
	 * @access protected
	 *
	 * @var bool
	 */
	protected $set_modified = false;

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
		if (empty($modules)) {
			logit('No module name given to `find_by_module`.');
			return false;
		}

		if ( ! is_array($modules)) {
			$modules = array($modules);
		}

		$this->db->select(array(
                            'activity_id',
                            "{$this->table_name}.user_id",
                            'activity',
                            'module',
                            "{$this->table_name}.{$this->created_field}",
                            'display_name',
                            'username',
                            'email',
                            'last_login',
                         ))
                 ->where_in('module', $modules)
                 ->where("{$this->table_name}.{$this->deleted_field}", 0)
                 ->join('users', "{$this->table_name}.user_id = users.id", 'left');

		return $this->find_all();
	}

    /**
     * Find the top modules
     *
     * @param Number $limit The number of modules to return
     *
     * @return Array    An array of results
     */
    public function findTopModules($limit = 5)
    {
        return $this->select(array(
                                'module',
                                'COUNT(module) AS activity_count',
                            ))
                    ->group_by('module')
                    ->where("{$this->table_name}.{$this->deleted_field}", 0)
                    ->limit($limit)
                    ->order_by('activity_count', 'desc')
                    ->find_all();
    }

    /**
     * Find the top users
     *
     * @param Number $limit The number of users to return
     *
     * @return Array    An array of results
     */
    public function findTopUsers($limit = 5)
    {
        if ( ! class_exists('user_model')) {
            $this->load->model('users/user_model');
        }
        $usersTable = $this->user_model->get_table();
        $usersKey = $this->user_model->get_key();

        return $this->select(array(
                                'username',
                                'user_id',
                                'COUNT(user_id) AS activity_count',
                            ))
                    ->where("{$this->table_name}.{$this->deleted_field}", 0)
                    ->join($usersTable, "{$this->table_name}.user_id = {$usersTable}.{$usersKey}", 'left')
                    ->group_by('user_id')
                    ->order_by('activity_count', 'desc')
                    ->limit($limit)
                    ->find_all();
    }

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
		if (empty($user_id) || ! is_integer($user_id)) {
			Template::set_message('You must provide a numeric user id to log activity.', 'error');
			return false;
		}

        if (empty($activity)) {
			Template::set_message('Not enough information provided to insert activity.', 'error');
			return false;
		}

		return $this->insert(array(
			'user_id'	=> $user_id,
			'activity'	=> $activity,
			'module'	=> $module
		));
	}
}//end class