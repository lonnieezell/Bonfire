<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

/*
	Class: Role_model
	
	Provides access and utility methods for handling role storage
	in the database.
	
	Extends:
		MY_Model
	
	Package:
		Roles
*/
class Role_model extends BF_Model {

	protected $table		= 'roles';
	protected $key			= 'role_id';
	protected $soft_deletes	= true;
	protected $date_format	= 'datetime';
	protected $set_modified = false;
	protected $set_created	= false;
	
	//--------------------------------------------------------------------
	
	/*
		Method: __construct()
		
		Class constructor. Will load the permission_model, if it's not 
		already loaded.
	*/
	public function __construct() 
	{
		parent::__construct();
		
		if (!class_exists('Permission_model'))
		{
			$this->load->model('permissions/permission_model');
		}
	}
	
	//--------------------------------------------------------------------

	/*
		Method: find()
		
		Returns a single role, with an array of permissions.
		
		Parameters:
			$id		- An int that matches the role_id of the role in question.
			
		Returns:
			An array of information about the role, along with a sub-array
			that contains the role's applicable permissions.
	*/
	public function find($id=null) 
	{
		if (empty($id) || !is_integer($id))
		{
			return false;
		}
	
		$role = parent::find($id);
		
		if ($role == false) 
		{
			return false;
		}
		
		$this->get_role_permissions($role);
		
		return $role;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: find_by_name()
		
		Locates a role based on the role name. Case doesn't matter.
		
		Parameters:
			$name	- A string with the name of the role.
			
		Returns:
			An object with the role and it's permissions.
	*/
	public function find_by_name($name=null) 
	{
		if (empty($name))
		{
			return false;
		}
		
		$role = $this->find_by('role_name', $name);
		
		$this->get_role_permissions($role);
		
		return $role;
	}
	
	//--------------------------------------------------------------------
	
	
	/*
		Method: update()
		
		A simple update of the role. This does, however, clean things up
		when setting this role as the default role for new users.
		
		Parameters:
			$id		- An int, being the role_id
			$data	- An array of key/value pairs to update the db with.
			
		Returns:
			true/false
	*/
	public function update($id=null, $data=null) 
	{
		// If this one is set to default, then we need to
		// reset all others to NOT be default
		if (isset($data['default']) && $data['default']  == 1)
		{
			$this->db->set('default', 0);
			$this->db->update($this->table);
		}
		
		return parent::update($id, $data);
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: can_delete_role()
		
		Verifies that a role can be deleted.
		
		Parameters:
			$role_id	- The role to verify.
			
		Returns:
			true/false
	*/
	public function can_delete_role($role_id=0) 
	{
		$this->db->select('role_id, can_delete');
		$delete_role = parent::find($role_id);
		
		if ($delete_role->can_delete == 1)
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: delete()
		
		Deletes a role. By default, it will perform a soft_delete and 
		leave the permissions untouched. However, if $purge == TRUE, then
		all permissions related to this role are also deleted. 
		
		Parameters:
			$id		- An integer with the role_id to delete.
			$purge	- If false, will perform a soft_delete. 
					  If true, will remove the role and related permissions from db.
					  
		Returns:
			true/false
	*/
	function delete($id=0, $purge=false) 
	{
		if ($purge === true)
		{
			// temporarily set the soft_deletes to true.
			$this->soft_deletes = false;
		}
		
		// We might not be allowed to delete this role.
		if ($this->can_delete_role($id) == false)
		{
			$this->error = 'This role can not be deleted.';
			return false;
		}
		
		// get the name for management deletion later
		$role = $this->role_model->find($id);
		
		// delete the record
		$deleted = parent::delete($id);
		
		if ($deleted === TRUE && $purge === TRUE)
		{
			// now delete the role_permissions for this permission
			$this->role_permission_model->delete_for_role($id);
			
			// now delete the manage permission for this role
			$prefix = $this->db->dbprefix;
			
			if (!class_exists('Permission_model'))
			{
				$this->load->model('permissions/permission_model');
			}
			
			$perm = $this->permission_model->find_by('name','Permissions.'.ucwords($role->role_name).'.Manage');
			
			if ($perm)
			{
				$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Permissions.".ucwords($role->role_name).".Manage')");
				$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='".$perm->permission_id."';");
			}
		}
		
		return $deleted;
	}

	//--------------------------------------------------------------------
	
	/*
		Method: default_role_id()
		
		Returns the id of the default role.	
		
		Return:
			An int with the default role_id, or false if none found.
	*/
	public function default_role_id() 
	{
		$this->db->where('default', 1);
		$query = $this->db->get($this->table);
		
		if ($query->num_rows() == 1)
		{
			return (int)$query->row()->role_id;
		}	
		
		return false;
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	/*
		Method: get_role_permissions()
		
		Finds the permissions and role_permissions array for a single role.
		
		Parameters:
			$role	- A reference to an existing role object. This object
					  is modified directly.
			
		Returns: 
			void
			
		Access:
			Private
	*/
	public function get_role_permissions(&$role) 
	{
		if (!is_object($role))
		{
			return;
		}
		
		$permission_array = array();
		
		// Grab our permissions for the role.
		$permissions = $this->permission_model->find_all_by('status','active');
		
		// Permissions
		foreach($permissions as $key => $permission)
		{
			$permission_array[$permission->name] = $permission;
		}
		$role->permissions = $permission_array;
		
		if (!class_exists('Role_permission_model'))
		{
			$this->load->model('roles/role_permission_model');
		}
		
		// Role Permissions
		$permission_array = array();
		$role_permissions = $this->role_permission_model->find_for_role($role->role_id);
		
		if (is_array($role_permissions) && count($role_permissions))
		{
			foreach($role_permissions as $key => $permission)
			{
				$permission_array[$permission->permission_id] = 1;
			}
		}
		
		$role->role_permissions = $permission_array;
		unset($permission_array);
	}
	
	//--------------------------------------------------------------------
	
}