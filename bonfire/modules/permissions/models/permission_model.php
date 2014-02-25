<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * Permissions Settings Model
 *
 * Provides access and utility methods for handling permission storage in the
 * database.
 *
 * Permissions are a simple string made up of 3 parts:
 * - Domain	 - A generic classification system
 * - Context - Typically the module name
 * - Action	 - The testable action (View, Manage, etc)
 *
 * Examples permissions would be:
 * - Site.Signin.Allow
 * - Site.Developer.View
 * - Bonfire.Users.Manage
 *
 * @package    Bonfire\Modules\Permissions\Models\Permission_model
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/permissions
 *
 */
class Permission_model extends BF_Model
{
	/**
	 * @var string Name of the table
	 */
	protected $table_name = 'permissions';

	/**
	 * @var string Name of the primary key
	 */
	protected $key = 'permission_id';

	/**
	 * @var bool Use soft deletes (if true)
	 */
	protected $soft_deletes = false;

	/**
	 * @var string The date format to use
	 */
	protected $date_format = 'datetime';

	/**
	 * @var bool Set the created time automatically on a new record (if true)
	 */
	protected $set_created = false;

	/**
	 * @var bool Set the modified time automatically on editing a record (if true)
	 */
	protected $set_modified = false;

	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Delete a particular permission from the database
	 *
	 * @param int  $id    Permission ID to delete
	 *
	 * @return bool true if the permission was deleted successfully, else false
	 */
	function delete($id = 0)
	{
		// Delete the record
		$deleted = parent::delete($id);

		// If the delete succeeded, delete the role_permissions for this $id
		if ($deleted === true) {
			$this->role_permission_model->delete_for_permission($id);
		}

		return $deleted;
	}

	/**
	 * Delete a particular permission from the database by name.
	 *
	 * @param str	$name	The name of the permission to delete
	 * @param bool	$purge	Whether to use soft delete or not.
	 *
	 * @return bool true if the permission was deleted successfully, else false
	 */
	public function delete_by_name($name = null, $purge = false)
	{
		$perm = $this->find_by('name', $name);

		return $this->delete($perm->permission_id, $purge);
	}

	/**
	 * Update a particular permission in the database.
	 *
	 * Remove it from role_permissions if set to inactive
	 *
	 * @param int   $id   The primary_key value or an array of key/value pairs
	 * for the where clause to determine the row to update.
	 * @param array $data An array of key/value pairs to update.
	 *
	 * @return bool true if the permission was updated, else false
	 */
	function update($id, $data)
	{
		$updated = parent::update($id, $data);

        // If the permission is set to inactive, delete its role_permissions
		if ($updated === true
            && isset($data['status']) && $data['status'] == 'inactive'
           ) {
			$id_is_array = is_array($id);

			// If $id is an array and we don't have the key
			if ($id_is_array && ! isset($id[$this->key])) {
				// Find the key(s) and perform the delete
				$result = $this->select($this->key)->find_all_by($id);
				if ($result) {
					foreach ($result as $permission_key) {
						$deleted = $this->role_permission_model->delete_for_permission($permission_key);
						if ($deleted === false) {
							return $deleted;
						}
					}
				}
			}
			// If $id is an array, we have the key, otherwise, $id is the key
			else {
				$id_key = $id_is_array ? $id[$this->key] : $id;
				$updated = $this->role_permission_model->delete_for_permission($id_key);
			}
		}

		return $updated;
	}

	/**
	 * Check whether a permission is in the system
	 *
	 * @param string $permission The name of the permission to check
	 *
	 * @return bool true if the permission was found, null if no permission was
	 * passed, else false
	 */
	public function permission_exists($permission = null)
	{
		if (empty($permission)) {
			return null;
		}

		if ($this->find_by('name', $permission)) {
			return true;
		}

		return false;
	}
}
/* end /bonfire/modules/permissions/models/permission_model.php */