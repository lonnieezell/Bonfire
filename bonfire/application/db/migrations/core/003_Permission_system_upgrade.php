<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Permission_system_upgrade extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		/*
			Take care of a few preliminaries before updating: 
			
			- Add new Site.Signin.Offline permission
			- Rename Site.Statistics.View to Site.Reports.View
			- Remove Site.Appearance.View
			
			Then the rest of the update script handles transferring them
			to the new tables. 
		*/
		$sql = "ALTER TABLE {$prefix}permissions ADD `Site.Signin.Offline` TINYINT(1) DEFAULT 0 NOT NULL";
		$this->db->query($sql);
		$this->db->query("UPDATE {$prefix}permissions SET `Site.Signin.Offline`=1 WHERE `role_id`=1");

		$sql = "ALTER TABLE {$prefix}permissions CHANGE `Site.Statistics.View` `Site.Reports.View` TINYINT(1) DEFAULT 0 NOT NULL";
		$this->db->query($sql);
		
		$sql = "ALTER TABLE {$prefix}permissions DROP COLUMN `Site.Appearance.View`";
		$this->db->query($sql);
		
		/*
			Do the actual update.
		*/
		// get the field names in the current bf_permissions table
		$permissions_fields = $this->db->list_fields('permissions');

		// get the current permissions assigned to each role
		$sql = "SELECT * FROM {$prefix}permissions";
		$permission_query = $this->db->query($sql);

		$old_permissions_array = array();
		foreach ($permission_query->result_array() as $row)
		{
			$role_id = $row['role_id'];
			$old_permissions_array[$role_id] = $row;
		}

		// modify the permissions table
		$this->dbforge->rename_table($prefix.'permissions', $prefix.'permissions_old');
		
		$fields = array(
						'permission_id' => array(
												'type' => 'INT',
												'constraint' => 11, 
												'null' => FALSE,
												'auto_increment' => TRUE
										  ),
						'name' => array(
												'type' => 'VARCHAR',
												'constraint' => '30',
										  ),
						'description' => array(
												'type' =>'VARCHAR',
												'constraint' => '100',
										  ),
						'status' => array(
												'type' => 'ENUM',
												'constraint' => "'active','inactive','deleted'",
												'null' => TRUE,
												'default' => 'active'
										  ),
				);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('permission_id', TRUE);
		$this->dbforge->create_table('permissions');
		// add records for each of the old permissions
		foreach ($permissions_fields as $field)
		{
			if($field != 'role_id' && $field != 'permission_id')
			{
				$this->db->query("INSERT INTO {$prefix}permissions VALUES (0,'{$field}','','active');");
			}
		}
		$this->db->query("INSERT INTO {$prefix}permissions VALUES (0,'Permissions.Settings.View','Allow access to view the Permissions menu unders Settings Context','active');");
		$this->db->query("INSERT INTO {$prefix}permissions VALUES (0,'Permissions.Settings.Manage','Allow access to manage the Permissions in the system','active');");
		
		
		// create the new bf_role_permissions table
		$this->dbforge->add_field("role_id int(11) NOT NULL");
		$this->dbforge->add_field("permission_id int(11) NOT NULL ");
		$this->dbforge->add_key('role_id', TRUE);
		$this->dbforge->add_key('permission_id', TRUE);
		$this->dbforge->create_table('role_permissions');
		
		// add records to allow access to the permissions by the roles - adding records to bf_role_permissions
		// get the current list of permissions
		$sql = "SELECT * FROM {$prefix}permissions";
		$new_permission_query = $this->db->query($sql);
		// loop through the current permissions
		foreach ($new_permission_query->result_array() as $permission_rec)
		{
			// loop through the old permissions
			foreach($old_permissions_array as $role_id => $role_permissions)
			{
				// if the role had access to this permission then give it access again
				if(isset($role_permissions[$permission_rec['name']]) && $role_permissions[$permission_rec['name']] == 1)
				{
					$this->db->query("INSERT INTO {$prefix}role_permissions VALUES ({$role_id},{$permission_rec['permission_id']});");
				}
				
				// specific case for the administrator to get access to - Bonfire.Permissions.Manage
				if($role_id == 1 AND $permission_rec['name'] == 'Bonfire.Permissions.Manage')
				{
					$this->db->query("INSERT INTO {$prefix}role_permissions VALUES ({$role_id},{$permission_rec['permission_id']});");
				}
			}
			
			// give the administrator use access to the new "Permissions" permissions
			if($permission_rec['name'] == 'Permissions.Settings.View' || $permission_rec['name'] == 'Permissions.Settings.Manage')
			{
				$this->db->query("INSERT INTO {$prefix}role_permissions VALUES (1,{$permission_rec['permission_id']});");
			}

		}

	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		// Drop our countries table
		$this->dbforge->drop_table('permissions');
		$this->dbforge->drop_table('role_permissions');
		
		$this->dbforge->rename_table($prefix.'permissions_old', $prefix.'permissions');

	}
	
	//--------------------------------------------------------------------
	
}