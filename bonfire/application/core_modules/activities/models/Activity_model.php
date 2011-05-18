<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activity_model extends MY_Model {

	protected $table		= 'activities';
	protected $key			= 'activity_id';
	protected $soft_deletes	= false;
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
			$this->db->where('module', $module);
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
	public function log_activity($user_id=null, $activity='', $module='') 
	{
		if (!is_numeric($user_id) || empty($activity) || empty($module))
		{
			logit('Not enough information provided to insert activity.');
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