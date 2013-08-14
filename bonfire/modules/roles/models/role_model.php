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
 * Role Settings Model
 *
 * Provides access and utility methods for handling role storage
 * in the database.
 *
 * @package    Bonfire
 * @subpackage Modules_Roles
 * @category   Models
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Role_model extends BF_Model
{

	/**
	 * Name of the table
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $table_name	= 'roles';

	/**
	 * Name of the primary key
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $key			= 'role_id';

	/**
	 * Use soft deletes or not
	 *
	 * @access protected
	 *
	 * @var bool
	 */
	protected $soft_deletes	= TRUE;

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
	 * Class constructor. Will load the permission_model, if it's not
	 * already loaded.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		if ( ! class_exists('Permission_model'))
		{
			$this->load->model('permissions/permission_model');
		}

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Returns a single role, with an array of permissions.
	 *
	 * @access public
	 *
	 * @param int $id An int that matches the role_id of the role in question.
	 *
	 * @return mixed|array An array of information about the role, along with a sub-array that contains the role's applicable permissions, or FALSE
	 */
	public function find($id=NULL)
	{
		if (empty($id) || ! is_integer($id))
		{
			return FALSE;
		}

		$role = parent::find($id);

		if ($role == FALSE)
		{
			return FALSE;
		}

		$this->get_role_permissions($role);

		return $role;

	}//end find()

	//--------------------------------------------------------------------

	/**
	 * Locates a role based on the role name. Case doesn't matter.
	 *
	 * @access public
	 *
	 * @param string $name A string with the name of the role.
	 *
	 * @return object An object with the role and its permissions, or FALSE
	 */
	public function find_by_name($name=NULL)
	{
		if (empty($name))
		{
			return FALSE;
		}

		$role = $this->find_by('role_name', $name);

		$this->get_role_permissions($role);

		return $role;

	}//end find_by_name()

	//--------------------------------------------------------------------


	/**
	 * A simple update of the role. This does, however, clean things up
	 * when setting this role as the default role for new users.
	 *
	 * @access public
	 *
	 * @param int   $id   An int, being the role_id
	 * @param array $data An array of key/value pairs to update the db with.
	 *
	 * @return bool TRUE on successful update, else FALSE
	 */
	public function update($id=NULL, $data=NULL)
	{
		// If this one is set to default, then we need to
		// reset all others to NOT be default
		if (isset($data['default']) && $data['default']  == 1)
		{
			$this->db->set('default', 0);
			$this->db->update($this->table_name);
		}

		return parent::update($id, $data);

	}//end update()

	//--------------------------------------------------------------------

	/**
	 * Verifies that a role can be deleted.
	 *
	 * @param int $role_id The role to verify.
	 *
	 * @return bool TRUE if the role can be deleted, else FALSE
	 */
	public function can_delete_role($role_id=0)
	{
		$this->db->select('can_delete');
		$delete_role = parent::find($role_id);

		if ($delete_role->can_delete == 1)
		{
			return TRUE;
		}

		return FALSE;

	}//end can_delete_role()

	//--------------------------------------------------------------------

	/**
	 * Deletes a role. By default, it will perform a soft_delete and
	 * leave the permissions untouched. However, if $purge == TRUE, then
	 * all permissions related to this role are also deleted.
	 *
	 * @access public
	 *
	 * @param int  $id    An integer with the role_id to delete.
	 * @param bool $purge If FALSE, will perform a soft_delete. If TRUE, will remove the role and related permissions from db.
	 *
	 * @return bool TRUE/FALSE
	 */
	function delete($id=0, $purge=FALSE)
	{
		// We might not be allowed to delete this role.
		if ($this->can_delete_role($id) == FALSE)
		{
			$this->error = 'This role can not be deleted.';
			return FALSE;
		}

		if ($this->default_role_id() == $id)
		{
			$this->error = 'The default role can not be deleted.';
			return FALSE;
		}

		if ($purge === TRUE)
		{
			// temporarily set the soft_deletes to TRUE.
			$this->soft_deletes = FALSE;
		}

		// get the name for management deletion later
		$role = $this->role_model->find($id);

		// delete the record
		$deleted = parent::delete($id);

		if ($deleted === TRUE)
		{
			// Now update the users to the default role
			if ( ! class_exists('User_model'))
			{
				$this->load->model('users/User_model','user_model');
			}

			$this->user_model->set_to_default_role($id);

			// now delete the role_permissions for this role
			$this->role_permission_model->delete_for_role($id);

			// now delete the manage permission for this role
			$permission_name = 'Permissions.' . ucwords($role->role_name) . '.Manage';

			if ( ! class_exists('Permission_model'))
			{
				$this->load->model('permissions/permission_model');
			}

			$perm = $this->permission_model->find_by('name', $permission_name);
			if ($perm)
			{
				// remove the role_permissions for this permission
				$this->db->delete('role_permissions', array('permission_id' => $perm->permission_id));

				if ($purge === TRUE)
				{
					$this->db->delete('permissions', array('name' => $permission_name));
				}
				else
				{
					$this->db->update('permissions', array('status' => 'inactive'), array('name' => $permission_name));
				}
			}
		}//end if

		return $deleted;

	}//end delete()

	//--------------------------------------------------------------------

	/**
	 * Returns the id of the default role.
	 *
	 * @access public
	 *
	 * @return mixed ID of the default role or FALSE
	 */
	public function default_role_id()
	{
		$this->db->where('default', 1);
		$query = $this->db->get($this->table_name);

		if ($query->num_rows() == 1)
		{
			return (int)$query->row()->role_id;
		}

		return FALSE;

	}//end default_role_id()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Finds the permissions and role_permissions array for a single role.
	 *
	 * @access public
	 *
	 * @param int $role A reference to an existing role object. This object is modified directly.
	 *
	 * @return void
	 */
	public function get_role_permissions(&$role)
	{
		if ( ! is_object($role))
		{
			return;
		}

		$permission_array = array();

		// Grab our permissions for the role.
		$permissions = $this->permission_model->find_all_by('status','active');

		// Permissions
		foreach ($permissions as $key => $permission)
		{
			$permission_array[$permission->name] = $permission;
		}

		$role->permissions = $permission_array;

		if ( ! class_exists('Role_permission_model'))
		{
			$this->load->model('roles/role_permission_model');
		}

		// Role Permissions
		$permission_array = array();
		$role_permissions = $this->role_permission_model->find_for_role($role->role_id);

		if (is_array($role_permissions) && count($role_permissions))
		{
			foreach ($role_permissions as $key => $permission)
			{
				$permission_array[$permission->permission_id] = 1;
			}
		}

		$role->role_permissions = $permission_array;
		unset($permission_array);

	}//end get_role_permissions()

	//--------------------------------------------------------------------

}//end Role_model