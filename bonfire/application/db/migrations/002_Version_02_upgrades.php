<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Version_02_upgrades extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;

		// email Queue
		$sql = "ALTER TABLE {$prefix}permissions
				ADD COLUMN `Bonfire.Emailer.View` TINYINT(1) DEFAULT 0 NOT NULL";
		$this->db->query($sql);	
		
		// Users table changes
		$this->dbforge->modify_column('users', array(
			'temp_password_hash' => array(
				'name'	=> 'reset_hash'
			);
		));
		$this->dbforge->add_column('users', array(
			'reset_by'	=> array(
				'type'			=> 'INT',
				'constraint'	=> 10,
				'null'			=> true
			)
		));
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$this->dbforge->drop_column('permissions', 'Bonfire.Emailer.View');
		
		$this->dbforge->modify_column('users', array(
			'reset_hash' => array(
				'name'	=> 'temp_password_hash'
			);
		));
		$this->dbforge->drop_column('users', 'reset_by');
	}
	
	//--------------------------------------------------------------------
	
}