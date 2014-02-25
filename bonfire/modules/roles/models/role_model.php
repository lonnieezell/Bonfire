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
 * Role Settings Model
 *
 * Provides access and utility methods for handling role storage in the
 * database.
 *
 * @package    Bonfire\Modules\Roles\Models\Role_model
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/roles
 */
class Role_model extends BF_Model
{
	/**
	 * @var string Name of the table
	 */
	protected $table_name = 'roles';

	/**
	 * @var string Name of the primary key
	 */
	protected $key = 'role_id';

	/**
	 * @var bool Use soft deletes (if true)
	 */
	protected $soft_deletes	= true;

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

	//--------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * Will load the permission_model, if it's not already loaded.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		if ( ! class_exists('permission_model')) {
			$this->load->model('permissions/permission_model');
		}
	}

	/**
	 * Returns a single role, with an array of permissions.
	 *
	 * @param int $id An int that matches the role_id of the role in question.
	 *
	 * @return mixed|array An array of information about the role, along with a
	 * sub-array that contains the role's applicable permissions, or false
	 */
	public function find($id = null)
	{
		if (empty($id) || ! is_integer($id)) {
			return false;
		}

		$role = parent::find($id);
		if ($role == false) {
			return false;
		}

		$this->get_role_permissions($role);

		return $role;
	}

	/**
	 * Locates a role based on the role name. Case insensitive.
	 *
	 * @param string $name A string with the name of the role.
	 *
	 * @return object An object with the role and its permissions, or false
	 */
	public function find_by_name($name = null)
	{
		if (empty($name)) {
			return false;
		}

		$role = $this->find_by('role_name', $name);

		$this->get_role_permissions($role);

		return $role;
	}

	/**
	 * A simple update of the role.
	 *
	 * Additionally, this cleans things up when setting this role as the default
	 * role for new users.
	 *
	 * @param int   $id   An int, being the role_id
	 * @param array $data An array of key/value pairs with which to update the db.
	 *
	 * @return bool true on successful update, else false
	 */
	public function update($id = null, $data = null)
	{
		// If this role is set to default, then
        // all others to are reset to NOT be default
		if (isset($data['default']) && $data['default'] == 1) {
			$this->db->set('default', 0);
			$this->db->update($this->table_name);
		}

		return parent::update($id, $data);
	}

	/**
	 * Verifies that a role can be deleted.
	 *
	 * @param int $role_id The role to verify.
	 *
	 * @return bool true if the role can be deleted, else false
	 */
	public function can_delete_role($role_id = 0)
	{
		$this->db->select('can_delete');

		$delete_role = parent::find($role_id);
		if ($delete_role->can_delete == 1) {
			return true;
		}

		return false;
	}

	/**
	 * Deletes a role.
	 *
	 * By default, it will perform a soft_delete and leave the permissions
	 * untouched. However, if $purge == true, then all permissions related to
	 * this role are also deleted.
	 *
	 * @param int  $id    An integer with the role_id to delete.
	 * @param bool $purge If false, will perform a soft_delete. If true, will
	 * remove the role and related permissions from db.
	 *
	 * @return bool true on successful delete, else false
	 */
	function delete($id = 0, $purge = false)
	{
		// Can this role be deleted?
		if ($this->can_delete_role($id) == false) {
			$this->error = 'This role can not be deleted.';
			return false;
		}

		if ($this->default_role_id() == $id) {
			$this->error = 'The default role can not be deleted.';
			return false;
		}

		if ($purge === true) {
			// Temporarily disable soft deletes.
            $tempSoftDeletes = $this->soft_deletes;
			$this->soft_deletes = false;
		}

		// Get the name for permission deletion later
		$role = $this->role_model->find($id);

		// Delete the record
		$deleted = parent::delete($id);
        if ($deleted === true) {
            // Update the users to the default role
            if ( ! class_exists('user_model')) {
                $this->load->model('users/user_model');
            }
            $this->user_model->set_to_default_role($id);

            // Delete the role_permissions for this role
            if ( ! class_exists('role_permission_model')) {
                $this->load->model('roles/role_permission_model');
            }
            $this->role_permission_model->delete_for_role($id);

            // Delete the manage permission for this role
            $permission_name = 'Permissions.' . ucwords($role->role_name) . '.Manage';
            if ( ! class_exists('permission_model')) {
                $this->load->model('permissions/permission_model');
            }

            $perm = $this->permission_model->find_by('name', $permission_name);
            if ($perm) {
                // The permission_model's update/delete will remove the
                // role_permissions for this permission
                if ($purge === true) {
                    $this->permission_model->delete_by_name($permission_name);
                } else {
                    $this->permission_model->update(array('name' => $permission_name), array('status' => 'inactive'));
                }
            }
        }

        // Restore soft_deletes
        if ($purge === true) {
            $this->soft_deletes = $tempSoftDeletes;
        }
        return $deleted;
	}

	/**
	 * Returns the id of the default role.
	 *
	 * @return int|bool ID of the default role or false
	 */
	public function default_role_id()
	{
		$this->db->where('default', 1);

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			return (int)$query->row()->role_id;
		}

		return false;
	}

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Finds the permissions and role_permissions array for a single role.
	 *
	 * @param int $role A reference to an existing role object. This object is
	 * modified directly.
	 *
	 * @return void
	 */
	public function get_role_permissions(&$role)
	{
		if ( ! is_object($role)) {
			return;
		}

		// Grab the active permissions
		$permissions = $this->permission_model->find_all_by('status', 'active');

		// Setup the permissions for the role
		$permission_array = array();
		foreach ($permissions as $key => $permission) {
			$permission_array[$permission->name] = $permission;
		}

		$role->permissions = $permission_array;

		if ( ! class_exists('role_permission_model')) {
			$this->load->model('roles/role_permission_model');
		}

		// Get the role permissions for the role
		$permission_array = array();
		$role_permissions = $this->role_permission_model->find_for_role($role->role_id);
		if (is_array($role_permissions) && count($role_permissions)) {
			foreach ($role_permissions as $key => $permission) {
				$permission_array[$permission->permission_id] = 1;
			}
		}

		$role->role_permissions = $permission_array;
	}
}
/* end /bonfire/modules/roles/models/role_model.php */