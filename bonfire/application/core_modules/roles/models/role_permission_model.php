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
	Class: Role_permission_model
	
	Provides access and utility methods for handling permission storage
	in the database.
	
	Permissions are a simple string made up of 3 parts: 
		
	- Domain	- A generic classification system
	- Context	- Typically the module name
	- Action	- The testable action (View, Manage, etc)
	
	Examples permissions would be: 
	
	- Site.Signin.Allow
	- Site.Developer.View
	- Bonfire.Users.Manage
	
	Extends:
		BF_Model
		
	Package:
		Roles
*/
class Role_permission_model extends BF_Model {

	protected $table		= 'role_permissions';
	protected $key			= 'permission_id';
	protected $soft_deletes	= false;
	protected $date_format	= 'datetime';
	protected $set_modified = false;
	protected $set_created	= false;
	
	//--------------------------------------------------------------------
	
	/*
		Method: create()
		
		Creates a new role permission entry
		
		Parameter:
			$role_id			- ID of the role
			$permission_id		- ID of the permission
	*/
	public function create($role_id, $permission_id) 
	{
		if (empty($role_id))
		{
			$this->error = 'No Role given.';
			return false;
		}
		if (empty($permission_id))
		{
			$this->error = 'No Permission given.';
			return false;
		}
		
		$data['role_id'] = $role_id;
		$data['permission_id'] = $permission_id;
		
		$id = parent::insert($data);
		
		return $id;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: delete()
		
		Removes a permission record from the role permissions table.
		
		Parameters:
			$role_id			- ID of the role
			$permission_id		- ID of the permission
				
		Returns:
			true/false
	*/
	public function delete($role_id, $permission_id) 
	{
		if (empty($role_id))
		{
			$this->error = 'No Role given.';
			return false;
		}
		if (empty($permission_id))
		{
			$this->error = 'No Permission given.';
			return false;
		}
		
		$this->db->delete($this->table, array('role_id' => $role_id, 'permission_id' => $permission_id));

		$result = $this->db->affected_rows();

		if ($result)
		{
			return true;
		} 
		
		$this->error = 'DB Error: ' . mysql_error();
	
		return false;
	}

	/*
		Method: delete_for_role()
		
		Removes a permission record from the role permissions table.
		
		Parameters:
			$role_id			- ID of the role
				
		Returns:
			true/false
	*/
	public function delete_for_role($role_id) 
	{
		if (empty($role_id))
		{
			$this->error = 'No Role given.';
			return false;
		}
		$this->db->delete($this->table, array('role_id' => $role_id));

		$result = $this->db->affected_rows();

		if ($result)
		{
			return true;
		} 
		
		$this->error = 'DB Error: ' . mysql_error();
	
		return false;
	}
	
	/*
		Method: delete_for_permission()
		
		Removes a permission record from the role permissions table.
		
		Parameters:
			$permission_id		- ID of the permission
				
		Returns:
			true/false
	*/
	public function delete_for_permission($permission_id) 
	{
		if (empty($permission_id))
		{
			$this->error = 'No Permission given.';
			return false;
		}
		
		$this->db->delete($this->table, array('permission_id' => $permission_id));

		$result = $this->db->affected_rows();

		if ($result)
		{
			return true;
		} 
		
		$this->error = 'DB Error: ' . mysql_error();
	
		return false;
	}

	//--------------------------------------------------------------------
			
	/*
		Method: set_for_role()
		
		Sets the permissions for a single role. 
		
		Parameters:
			$role_id		- The int id of the target role.
			$permissions	- A simple array with the values being equal
							to the name of the permission to set. All other 
							permissions are set to 0.
	*/
	public function set_for_role($role_id=null, $permissions = array()) 
	{
		if (empty($role_id) || !is_numeric($role_id))
		{
			return false;
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
	}
	
	//--------------------------------------------------------------------

	/*
		Method: find_for_role()
		
		Returns the permissions array for a single role.
		
		Parameters:
			$role_id	- The int id of the role to find permissions for.
			
		Returns:
			object	- The list of permissions
	 */
	public function find_for_role($role_id=null) 
	{
		parent::select('permission_id');
		return parent::find_all_by('role_id', $role_id);
	}
	
	// --------------------------------------------------------------------
	
	/*
		Method: find_all_role_permissions()

		Finds all the role permissions (role_id and permission_id)
		
		Parameters:
			none
										
		Return:
			object
	*/	
	function find_all_role_permissions()
	{		
		return $this->role_permission_model->find_all();
	}
	
	// --------------------------------------------------------------------
	
	/*
		Method: find_all_roles()

		Finds all the roles
		
		Parameters:
			none
							
		Return:
			object
	*/
	function find_all_roles()
	{		
		return $this->role_model->find_all();
	}
	
	// --------------------------------------------------------------------
	
	/*
		Method: create_role_permissions()
		
		Creates a new role permission entry
		
		Parameter:
			$role_id			- ID of the role
			$permission_id		- ID of the permission
										
		Return:
			true / false
	*/	
	function create_role_permissions($role_id, $permission_id)
	{		
		return $this->role_permission_model->create($role_id, $permission_id);
	}
	
	// --------------------------------------------------------------------
	
	/*
		Method: delete_role_permissions()
		
		Parameters:
			$role_id			- ID of the role
			$permission_id		- ID of the permission
				
		Returns:
			true/false
	*/	
	function delete_role_permissions($role_id, $permission_id)
	{		
		return $this->role_permission_model->delete($role_id, $permission_id);
	}

	//--------------------------------------------------------------------
	
}

// End Permission Model