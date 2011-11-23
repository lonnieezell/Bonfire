<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Role_login_destination_change extends Migration {
	
	/**
	 * Removing the '/' from the Role login_destination field in the DB so that 
	 * the user will be brought to the last requested url when they login
	 */
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		// change the roles which don't have any specific login_destination set
		$this->db->where('login_destination', '/');
		$this->db->update('roles', array('login_destination' => ''));

	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		// change the roles which don't have any specific login_destination set
		$this->db->where('login_destination', '');
		$this->db->update('roles', array('login_destination' => '/'));

	}
	
	//--------------------------------------------------------------------
	
}