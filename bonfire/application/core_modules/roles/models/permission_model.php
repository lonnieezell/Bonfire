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
	
	/**
	 * Sets the permissions for a single role. 
	 * 
	 * The permissions array is a simple array with the values being equal
	 * to the name of the permission to set. All other permissions are set
	 * to 0.
	 */
	public function set_for_role($role_id=null, $permissions = array()) 
	{
		if (empty($role_id) || !is_numeric($role_id))
		{
			return false;
		}
		
		$role = $this->find_by('role_id', $role_id);
		if (!$role)
		{
			// No permissions set for this role yet, so
			// create an empty set.
			$this->insert(array('role_id'=>$role_id));		
		}

		if (is_array($role)) { $role = (array)$role[0]; }
				
		// ActiveRecord doesn't like this style of parameters (with the '.' as dividers)
		// so we build it ourself.
		$sets = '';
		
		
		foreach ($role as $name => $value)
		{
			if ($name == 'permission_id' || $name == 'role_id') { continue; }
			
			$sets .= ", `$name`=";
			$sets .= in_array($name, $permissions) ? '1' : '0';
		}
		
		$sets = trim($sets, ', ');
				
		$sql = "UPDATE {$this->db->dbprefix}{$this->table} 
				SET $sets
				WHERE `role_id`=$role_id
		";		
		
		return $this->db->query($sql);
	}
	
	//--------------------------------------------------------------------
	
}

// End Permission Model