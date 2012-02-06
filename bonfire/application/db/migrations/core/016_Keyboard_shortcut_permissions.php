<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Keyboard_shortcut_permissions extends Migration {
	
	/**
	 * Removing the '/' from the Role login_destination field in the DB so that 
	 * the user will be brought to the last requested url when they login
	 */
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		$data = array(
			'name'        => 'Bonfire.UI.Manage' ,
			'description' => 'Manage the Bonfire UI settings' 
		);
		$this->db->insert("{$prefix}permissions", $data);
		
		$permission_id = $this->db->insert_id();
		
		// change the roles which don't have any specific login_destination set
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1, ".$permission_id.")");

		// add the keys
		$keys = array(
			'form_save' => 'ctrl+s/⌘+s',
			'goto_content' => 'alt+c',
		);
		$this->db->query("INSERT INTO {$prefix}settings VALUES('ui.shortcut_keys', 'core', '".serialize($keys)."')");

	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Bonfire.UI.Manage'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the role
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Bonfire.UI.Manage')");
		
		// remove the keys
		$this->db->query("DELETE FROM {$prefix}settings WHERE (name = 'ui.shortcut_keys')");

	}
	
	//--------------------------------------------------------------------
	
}