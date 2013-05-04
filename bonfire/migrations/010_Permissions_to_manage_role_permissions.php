<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Permissions_to_manage_role_permissions extends Migration {
	
	public function up() 
	{
		$this->load->library('session');
	
		$prefix = $this->db->dbprefix;
		
		// name field in permissions table is too short bump it up to 50
		$sql = "ALTER TABLE `{$prefix}permissions` CHANGE `name` `name` VARCHAR(255) NULL";
		$this->db->query($sql);
		
		$roles = $this->db->select('role_name')->get($prefix.'roles')->result();
		if (isset($roles) && is_array($roles) && count($roles)) {
			foreach ($roles as $role) {
				// add the permission
				$this->db->query("INSERT INTO {$prefix}permissions(name, description) VALUES('Permissions.".ucwords($role->role_name).".Manage','To manage the access control permissions for the ".ucwords($role->role_name)." role.')");
				// give current role (or administrators if fresh install) full right to manage permissions
				$assign_role = $this->session->userdata('role_id') ? $this->session->userdata('role_id') : 1;
				$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(".$assign_role.",".$this->db->insert_id().")");
			}
		}		
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		$roles = $this->role_model->find_all();
		if (isset($roles) && is_array($roles) && count($roles)) {
			foreach ($roles as $role) {
				// delete any but that has any of these permissions from the role_permissions table
				$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Permissions.".$role->role_name.".Manage'");
				foreach ($query->result_array() as $row)
				{
					$permission_id = $row['permission_id'];
					$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
				}
				//delete the role
				$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Permissions.".$role->role_name.".Manage')");
			}
		}
		
		// restore the shorter table field size back to 30
		$sql = "ALTER TABLE `{$prefix}permissions` CHANGE `name` `name` VARCHAR(30) NULL";
		$this->db->query($sql);	
	}
	
	//--------------------------------------------------------------------
	
}