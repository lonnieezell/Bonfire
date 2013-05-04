<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Permissions_for_core_modules extends Migration
{

	private $permission_array = array(
					'Bonfire.Activities.View' => 'To view the Activities menu.',
					'Bonfire.Database.View' => 'To view the Database menu.',
					'Bonfire.Migrations.View' => 'To view the Migrations menu.',
					'Bonfire.Modulebuilder.View' => 'To view the Modulebuilder menu.',
					'Bonfire.Roles.View' => 'To view the Roles menu.',
					'Bonfire.Sysinfo.View' => 'To view the System Information page.',
					'Bonfire.Translate.Manage' => 'To manage the Language Translation.',
					'Bonfire.Translate.View' => 'To view the Language Translate menu.',
					'Bonfire.UI.View' => 'To view the UI/Keyboard Shortcut menu.',
					'Bonfire.Update.Manage' => 'To manage the Bonfire Update.',
					'Bonfire.Update.View' => 'To view the Developer Update menu.',
				);


	public function up()
	{
		$this->load->library('session');

		$prefix = $this->db->dbprefix;

		$query = $this->db->query("UPDATE {$prefix}permissions set name = 'Bonfire.Permissions.Manage' WHERE name = 'Permissions.Settings.Manage'");
		$query = $this->db->query("UPDATE {$prefix}permissions set name = 'Bonfire.Permissions.View' WHERE name = 'Permissions.Settings.View'");

		foreach ($this->permission_array as $name => $description)
		{
			$this->db->query("INSERT INTO {$prefix}permissions(name, description) VALUES('".$name."', '".$description."')");
			// give current role (or administrators if fresh install) full right to manage permissions
			$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1,".$this->db->insert_id().")");
		}

		// remove an old permission
		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Permissions.Banned.Manage'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the role
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Permissions.Banned.Manage')");

		// remove Bonfire.Activities.Manage as is not used now
		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Bonfire.Activities.Manage'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the role
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Bonfire.Activities.Manage')");

	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		foreach ($this->permission_array as $name => $description)
		{
			$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = '".$name."'");
			foreach ($query->result_array() as $row)
			{
				$permission_id = $row['permission_id'];
				$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
			}
			//delete the role
			$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = '".$name."')");
		}

		// add in the Banned permission
		$this->db->query("INSERT INTO {$prefix}permissions(name, description) VALUES('Permissions.Banned.Manage', 'To manage the access control permissions for the Banned role.')");
		// give current role (or administrators if fresh install) full right to manage permissions
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1,".$this->db->insert_id().")");

		// add in the Banned permission
		$this->db->query("INSERT INTO {$prefix}permissions(name, description) VALUES('Bonfire.Activities.Manage', 'Allow users to access the Activities Reports.')");
		// give current role (or administrators if fresh install) full right to manage permissions
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1,".$this->db->insert_id().")");

	}

	//--------------------------------------------------------------------

}