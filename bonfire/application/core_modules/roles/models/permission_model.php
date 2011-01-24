<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Permission_model extends MY_Model {

	protected $table		= 'permissions';
	protected $key			= 'permission_id';
	protected $soft_deletes	= false;
	protected $date_format	= 'datetime';
	protected $set_modified = false;
	protected $set_created	= false;
	
	//--------------------------------------------------------------------
	
	/**
	 * Creates a new permission field on the permissions table.
	 */
	public function create($permission_name=null) 
	{
		if (empty($permission_name))
		{
			$this->error = 'No Permission Name given.';
			return false;
		}
		
		// To make sure we can accept arrays of permissions,
		// treat a single permission like an array.
		if (is_string($permission_name))
		{
			$permission_name = array($permission_name);
		}
		
		// Create the new fields.
		$this->load->dbforge();
		$columns = array();
		
		foreach ($permission_name as $permission)
		{
			$columns[$permission] = array(
				'type'			=> 'tinyint',
				'constraint'	=> 1,
				'default'		=> 0
			);
		}
		
		return $this->dbforge->add_column($this->table, $columns);
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Removes a permission field from the permissions table.
	 */
	public function delete($permission_name=null) 
	{
		if (empty($permission_name))
		{
			$this->error = 'No Permission Name given.';
			return false;
		}
		
		// To make sure we can accept arrays of permissions,
		// treat a single permission like an array.
		if (is_string($permission_name))
		{
			$permission_name = array($permission_name);
		}
		
		$this->load->dbforge();
		
		foreach ($permission_name as $permission)
		{
			$this->dbforge->drop_column($this->table, $permission);
		}
		
		return true;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	Returns the permissions array for a single role.
	 *
	 * @access	public
	 * @param	int		$role_id		
	 * @returns	object	The list of permissions
	 */
	public function find_for_role($role_id=null) 
	{
		if (empty($role_id) || !is_numeric($role_id))
		{
			return false;
		}
		
		return parent::find_by('role_id', $role_id);
	}
	
	//--------------------------------------------------------------------
	
}

// End Permission Model