<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Add_role_delete_permissions extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		// Add the new permission
		$ci =& get_instance();
		//$ci->load->model('permissions/permission_model');
		
		$pid = $ci->permission_model->insert(array(
			'name'			=> 'Bonfire.Roles.Delete',
			'description'	=> '',
			'status'		=> 'active'
		));
		
		if ($pid)
		{
			// Add the permission to the admin role.
			$ci->role_permission_model->create(1, $pid);
		}
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;

		// Delete the permissions assigned to roles
		$ci =& get_instance();
		
		$perm = $ci->permission_model->find_by('name', 'Bonfire.Roles.Delete');
		
		if ($perm)
		{
			$ci->permission_model->delete($perm->permission_id);
		}
		
	}
	
	//--------------------------------------------------------------------
	
}