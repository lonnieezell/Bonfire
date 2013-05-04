<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Permission_to_manage_settings extends Migration {
	
	public function up() 
	{
		$this->load->library('session');
	
		$prefix = $this->db->dbprefix;

		$this->db->query("INSERT INTO {$prefix}permissions(name, description) VALUES('Bonfire.Settings.View', 'To view the site settings page.')");
		// give current role (or administrators if fresh install) full right to manage permissions
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1,".$this->db->insert_id().")");

		// add the permission
		$this->db->query("INSERT INTO {$prefix}permissions(name, description) VALUES('Bonfire.Settings.Manage', 'To manage the site settings.')");
		// give current role (or administrators if fresh install) full right to manage permissions
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1,".$this->db->insert_id().")");
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Site.Settings.View'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the role
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Site.Settings.View')");

		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Site.Settings.Manage'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the role
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Site.Settings.Manage')");

	}
	
	//--------------------------------------------------------------------
	
}