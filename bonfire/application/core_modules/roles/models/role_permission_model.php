<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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
 * Role Permissions Settings Model
 *
 * Provides access and utility methods for handling permission storage
 * in the database.
 *
 * Permissions are a simple string made up of 3 parts:
 * * Domain	- A generic classification system
 * * Context	- Typically the module name
 * * Action	- The testable action (View, Manage, etc)
 *
 * Examples permissions would be:
 * * Site.Signin.Allow
 * * Site.Developer.View
 * * Bonfire.Users.Manage
 *
 * @package    Bonfire
 * @subpackage Modules_Roles
 * @category   Models
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Role_permission_model extends BF_Model
{

	/**
	 * Name of the table
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $table		= 'role_permissions';

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
	 * Creates a new role permission entry
	 *
	 * @access public
	 *
	 * @param int $role_id       ID of the role
	 * @param int $permission_id ID of the permission
	 *
	 * @return mixed ID of the new record or FALSE if an error
	 */
	public function create($role_id, $permission_id=NULL)
	{
		if (empty($role_id))
		{
			$this->error = 'No Role given.';
			return FALSE;
		}

		if (empty($permission_id))
		{
			$this->error = 'No Permission given.';
			return FALSE;
		}

		$data['role_id'] = $role_id;
		$data['permission_id'] = $permission_id;

		$id = parent::insert($data);

		return $id;

	}//end create()

	//--------------------------------------------------------------------

	/**
	 * Removes a permission record from the role permissions table.
	 *
	 * @access public
	 *
	 * @param int $role_id       ID of the role
	 * @param int $permission_id ID of the permission
	 *
	 * @return bool TRUE/FALSE
	 */
	public function delete($role_id, $permission_id)
	{
		if (empty($role_id))
		{
			$this->error = 'No Role given.';
			return FALSE;
		}

		if (empty($permission_id))
		{
			$this->error = 'No Permission given.';
			return FALSE;
		}

		$this->db->delete($this->table, array('role_id' => $role_id, 'permission_id' => $permission_id));

		$result = $this->db->affected_rows();

		if ($result)
		{
			return TRUE;
		}

		$this->error = 'DB Error: ' . mysql_error();

		return FALSE;

	}//end delete()


	//--------------------------------------------------------------------

	/**
	 * Removes a permission record from the role permissions table.
	 *
	 * @param int $role_id ID of the role
	 *
	 * @return bool TRUE/FALSE
	 */
	public function delete_for_role($role_id=NULL)
	{
		if (empty($role_id))
		{
			$this->error = 'No Role given.';
			return FALSE;
		}

		$this->db->delete($this->table, array('role_id' => $role_id));

		$result = $this->db->affected_rows();

		if ($result)
		{
			return TRUE;
		}

		$this->error = 'DB Error: ' . mysql_error();

		return FALSE;

	}//end delete_for_role()

	//--------------------------------------------------------------------

	/**
	 * Removes a permission record from the role permissions table.
	 *
	 * @access public
	 *
	 * @param int $permission_id ID of the permission
	 *
	 * @return bool TRUE/FALSE
	 */
	public function delete_for_permission($permission_id)
	{
		if (empty($permission_id))
		{
			$this->error = 'No Permission given.';
			return FALSE;
		}

		$this->db->delete($this->table, array('permission_id' => $permission_id));

		$result = $this->db->affected_rows();

		if ($result)
		{
			return TRUE;
		}

		$this->error = 'DB Error: ' . mysql_error();

		return FALSE;

	}//end delete_for_permission()

	//--------------------------------------------------------------------

	/**
	 * Sets the permissions for a single role.
	 *
	 * @param int   $role_id     The int id of the target role.
	 * @param array $permissions A simple array with the values being equal to the name of the permission to set. All other permissions are set to 0.
	 *
	 * @return void
	 */
	public function set_for_role($role_id=NULL, $permissions = array())
	{
		if (empty($role_id) || !is_numeric($role_id))
		{
			return FALSE;
		}

		$role = $this->find_by('role_id', $role_id);
		if ($role)
		{
			// remove existing permissions
			$this->delete_for_role($role_id);
		}

		// set the permissions
		foreach( $permissions as $key => $permission_id)
		{
			$data = array('role_id' => $role_id, 'permission_id' => $permission_id);
			$id = parent::insert($data);
		}

	}//end set_for_role()

	//--------------------------------------------------------------------

	/**
	 *	A convenience method to assign a single permission to a role by
	 *	names, rather than by ids.
	 *
	 * @access	public
	 *
	 * @param	str	$role_name			The name of the role
	 * @param	str	$permission_name	The name of the permission to assign.
	 */
	public function assign_to_role($role_name=null, $permission_name=null)
	{
		$this->load->model('roles/role_model');

		$role	= $this->role_model->find_by('role_name', $role_name);
		$perm	= $this->permission_model->find_by('name', $permission_name);

		if (!$role || !$perm)
		{
			return false;
		}

		return $this->create($role->role_id, $perm->permission_id);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the permissions array for a single role.
	 *
	 * @access public
	 *
	 * @param int $role_id The int id of the role to find permissions for.
	 *
	 * @return object
	 */
	public function find_for_role($role_id=NULL)
	{
		parent::select('permission_id');
		return parent::find_all_by('role_id', $role_id);

	}//end find_for_role()

	// --------------------------------------------------------------------

	/**
	 * Finds all the role permissions (role_id and permission_id)
	 *
	 * @access public
	 *
	 * @return object
	 */
	function find_all_role_permissions()
	{
		return $this->role_permission_model->find_all();

	}//end find_all_role_permissions()

	// --------------------------------------------------------------------

	/**
	 * Finds all the roles
	 *
	 * @access public
	 *
	 * @return object
	 */
	function find_all_roles()
	{
		return $this->role_model->find_all();

	}//end find_all_roles()

	// --------------------------------------------------------------------

	/**
	 * Creates a new role permission entry
	 *
	 * @access public
	 *
	 * @param int $role_id       ID of the role
	 * @param int $permission_id ID of the permission#
	 *
	 * @return bool TRUE/FALSE
	 */
	function create_role_permissions($role_id, $permission_id)
	{
		return $this->role_permission_model->create($role_id, $permission_id);

	}//end create_role_permissions()

	// --------------------------------------------------------------------

	/**
	 * Delete the permission for the role
	 *
	 * @param int $role_id       ID of the role
	 * @param int $permission_id ID of the permission#
	 *
	 * @return bool TRUE/FALSE
	 */
	function delete_role_permissions($role_id, $permission_id)
	{
		return $this->role_permission_model->delete($role_id, $permission_id);

	}//end delete_role_permissions()

	//--------------------------------------------------------------------

}//end Role_permission_model
