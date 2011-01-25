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
			
}