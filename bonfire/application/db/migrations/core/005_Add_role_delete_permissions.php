<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Add_role_delete_permissions extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		// Add the new permission
		$ci =& get_instance();
		$ci->load->model('permissions/permission_model');
		$ci->load->model('roles/role_permission_model');
		
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
		
		// Add the deleted field to the roles table
		$this->dbforge->add_column('roles', array(
			'deleted'	=> array(
				'type'			=> 'INT',
				'constraint'	=> 1,
				'default'		=> 0
			)
		));
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		// Delete the permissions assigned to roles
		$ci =& get_instance();
		$ci->load->model('permissions/permission_model');
		
		$perm = $ci->permission_model->find_by('name', 'Bonfire.Roles.Delete');
		
		if ($perm)
		{
			$ci->permission_model->delete($perm->permission_id);
		}
		
		// Remove the deleted column from roles
		$this->dbforge->drop_column('roles', 'deleted');
	}
	
	//--------------------------------------------------------------------
	
}