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
	Class: Activities
	
	Provides a simple and consistent way to record and display user-related activities
	in both core- and custom-modules.
*/

class Activity_model extends BF_Model {

	protected $table		= 'activities';
	protected $key			= 'activity_id';
	protected $soft_deletes	= true;
	protected $date_format	= 'datetime';
	protected $set_created	= true;
	protected $set_modified = false;
	
	//--------------------------------------------------------------------
	
	/*
		Method: find_by_module()
		
		Returns all activities created by one or more modules.
		
		Parameters:
			$modules	- Either a string or an array of module names.
			
		Returns:
			An array of activity objects.
	*/
	public function find_by_module($modules=array()) 
	{
		if (empty($modules))
		{
			logit('No module name given to `find_by_module`.');
			return false;
		}
		
		if (!is_array($modules))
		{
			$modules = array($modules);
		}
		
		foreach ($modules as $module)
		{
			$this->db->or_where('module', $module);
		}
		
		$this->db->select('activity_id, activities.user_id, activity, module, activities.created_on, first_name, last_name, username, email, last_login');
		$this->db->join('users', 'activities.user_id = users.id', 'left');
		
		return $this->find_all();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: log_activity()
		
		Logs a new activity.
		
		Parameters: 
			$user_id	- An int id of the user that performed the activity.
			$activity	- A string detailing the activity. Max length of 255 chars.
			$module		- The name of the module that set the activity.
			
		Returns:
			An int with the ID of the new object, or false on failure.
	*/
	public function log_activity($user_id=null, $activity='', $module='any') 
	{
		if (empty($user_id) || !is_integer($user_id))
		{
			Template::set_message('You must provide a numeric user id to log activity.','error');
			return false;
		}
		else if (empty($activity))
		{
			Template::set_message('Not enough information provided to insert activity.','error');
			return false;
		}
		
		$data = array(
			'user_id'	=> $user_id,
			'activity'	=> $activity,
			'module'	=> $module
		);
		
		return parent::insert($data);
	}
	
	//--------------------------------------------------------------------
	
}