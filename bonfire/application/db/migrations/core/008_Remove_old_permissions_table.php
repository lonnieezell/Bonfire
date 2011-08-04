<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Remove_old_permissions_table extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		$this->dbforge->drop_table('permissions_old');
		
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		// Permissions
		$this->dbforge->add_field("`permission_id` int(11) NOT NULL AUTO_INCREMENT");
		$this->dbforge->add_field("`role_id` int(11) NOT NULL");
		$this->dbforge->add_field("`Site.Signin.Allow` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Site.Signin.Offline` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Site.Content.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Site.Reports.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Site.Settings.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Site.Developer.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Roles.Manage` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Users.Manage` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Users.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Users.Add` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Database.Manage` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Emailer.Manage` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Emailer.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Logs.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Logs.Manage` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_key('permission_id', true);
		$this->dbforge->add_key('role_id');
		$this->dbforge->create_table('permissions_old');
		
		$this->db->query("INSERT INTO {$prefix}permissions_old VALUES(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)");
		$this->db->query("INSERT INTO {$prefix}permissions_old VALUES(2, 2, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
		$this->db->query("INSERT INTO {$prefix}permissions_old VALUES(3, 6, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0)");
		$this->db->query("INSERT INTO {$prefix}permissions_old VALUES(4, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
		$this->db->query("INSERT INTO {$prefix}permissions_old VALUES(5, 4, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
	}
	
	//--------------------------------------------------------------------
	
}