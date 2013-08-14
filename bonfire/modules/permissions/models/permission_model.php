<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * Permissions Settings Model
 *
 * Provides access and utility methods for handling permission storage
 * in the database.
 *
 * Permissions are a simple string made up of 3 parts:
 * - Domain	- A generic classification system
 * - Context	- Typically the module name
 * - Action	- The testable action (View, Manage, etc)
 *
 * Examples permissions would be:
 * - Site.Signin.Allow
 * - Site.Developer.View
 * - Bonfire.Users.Manage
 *
 * @package    Bonfire
 * @subpackage Modules_Permissions
 * @category   Models
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Permission_model extends BF_Model
{

	/**
	 * Name of the table
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $table_name		= 'permissions';

	/**
	 * Name of the primary key
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $key			= 'permission_id';

	/**
	 * Use soft deletes or not
	 *
	 * @access protected
	 *
	 * @var bool
	 */
	protected $soft_deletes = FALSE;

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

	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

	}//end __construct()

	// --------------------------------------------------------------------

	/**
	 * Delete a particular permission from the database
	 *
	 * @access public
	 *
	 * @param int  $id    Permission ID to delete
	 *
	 * @return bool TRUE if the permission was deleted successfully, else FALSE
	 */
	function delete($id=0)
	{
		// delete the ercord
		$deleted = parent::delete($id);

		// if the delete was successful then delete the role_permissions for this permission_id
		if (TRUE === $deleted)
		{
			$this->role_permission_model->delete_for_permission($id);
		}

		return $deleted;

	}//end delete()

	// --------------------------------------------------------------------

	/**
	 * Deletes a particular permission from the database by name.
	 *
	 * @access public
	 *
	 * @param str	$name	The name of the permission to delete
	 * @param bool	$purge	Whether to use soft delete or not.
	 *
	 * @return bool TRUE if the permission was deleted successfully, else FALSE
	 */
	public function delete_by_name($name=null, $purge=false)
	{
		$perm = $this->find_by('name', $name);

		return $this->delete($perm->permission_id, $purge);
	}

	//--------------------------------------------------------------------

	/**
	 * Update a particular permission from the database
	 * Remove it from role_permissions if set to inactive
	 *
	 * @access public
	 *
	 * @param int   $id   The primary_key value of the row to update.
	 * @param array $data An array of key/value pairs to update.
	 *
	 * @return bool TRUE if the permission was updated, else FALSE
	 */
	function update($id, $data)
	{
		$updated = parent::update($id, $data);

		if (isset($data['status']) && $data['status'] == 'inactive' && $updated === TRUE)
		{
			$id_is_array = is_array($id);

			// now delete the role_permissions for this permission since it is no longer active

			// if $id is an array and we don't have the key
			if ($id_is_array && ! isset($id[$key]))
			{
				// find the key(s) and perform the delete
				$result = $this->permission_model->select($key)->find_all_by($id);

				if ($result)
				{
					foreach ($result as $permission_key)
					{
						$deleted = $this->role_permission_model->delete_for_permission($permission_key);

						if ($deleted === FALSE)
						{
							return $deleted;
						}
					}
				}
			}
			// if $id is an array, we have the key,
			// otherwise, $id is the key
			else
			{
				$id_key = $id_is_array ? $id[$key] : $id;

				$updated = $this->role_permission_model->delete_for_permission($id_key);
			}
		}

		return $updated;

	}//end update()

	// --------------------------------------------------------------------

	/**
	 * Checks to see whether a permission is in the system
	 *
	 * @access public
	 *
	 * @param string $permission The name of the permission to check
	 *
	 * @return bool TRUE if the permission was found, NULL if no permission was passed, else FALSE
	 */
	public function permission_exists($permission=null)
	{
		if (empty($permission))
		{
			return null;
		}

		if ($this->find_by('name', $permission))
		{
			return TRUE;
		}

		return FALSE;

	}//end permission_exists()

	//--------------------------------------------------------------------

}//end Permission_model