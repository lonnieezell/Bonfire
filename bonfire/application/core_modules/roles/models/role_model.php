<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Role_model extends MY_Model {

	protected $table		= 'roles';
	protected $key			= 'role_id';
	protected $soft_deletes	= false;
	protected $date_format	= 'datetime';
	protected $set_modified = false;
	protected $set_created	= false;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		if (!class_exists('Permission_model'))
		{
			$this->load->model('permission_model');
		}
	}
	
	//--------------------------------------------------------------------

	public function find($id=null) 
	{
		$role = parent::find($id);
		
		if (!$role) { return false; }
		
		// Grab our permissions for the role.
		$permissions = $this->permission_model->find_for_role($id);
		$role->permissions = $permissions[0];
		
		return $role;
	}
	
	//--------------------------------------------------------------------
	
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
	
	
	/** 
	 *	Returns the id of the default role.	
	 */
	public function default_role_id() 
	{
		$this->db->where('default', 1);
		$query = $this->db->get($this->table);
		
		if ($query->num_rows() == 1)
		{
			return $query->row()->role_id;
		}	
		
		return false;
	}
	
	//--------------------------------------------------------------------
	
}