<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
	Class: Permission_model
	
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
		MY_Model
		
	Package:
		Roles
*/
class Permission_model extends BF_Model {

	protected $table		= 'permissions';
	protected $key			= 'permission_id';
	protected $soft_deletes	= false;
	protected $date_format	= 'datetime';
	protected $set_modified = false;
	protected $set_created	= false;

	function __construct()
	{
		parent::__construct();
	}
	
	// --------------------------------------------------------------------
	
	/*
		Method: delete()

		Delete a particular permission from the database
		
		Parameters:
			$id			- Permission ID
			$purge		- Whether to use soft delete or not
							
		Return:
			true/false
	*/
	function delete($id=0, $purge=false) 
	{
		if ($purge === true)
		{
			// temporarily set the soft_deletes to true.
			$this->soft_deletes = false;
		}
		
		// delete the ercord
		$deleted = parent::delete($id);
		
		// if the delete was successful then delete the role_permissions for this permission_id
		if( TRUE === $deleted )
		{
			// now delete the role_permissions for this permission
			$this->role_permission_model->delete_for_permission($id);
		}
		
		return $deleted;
	}
	
	// --------------------------------------------------------------------
	
	/*
		Method: update()

		Update a particular permission from the database
		Remove it from role_permissions if set to inactive
		
		Parameters:
			$id		- The primary_key value of the row to update.
			$data	- An array of key/value pairs to update.
							
		Return:
			true/false
	*/
	function update($id, $data)
	{
		$updated = parent::update($id, $data);
		
		if ($data['status'] == 'inactive' && $updated === TRUE)
		{
			// now delete the role_permissions for this permission since it is no longer active
			$updated = $this->role_permission_model->delete_for_permission($id);
		}
		
		return $updated;
	}

	// --------------------------------------------------------------------
	
}
