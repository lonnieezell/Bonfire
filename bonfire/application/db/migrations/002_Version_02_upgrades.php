<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Version_02_upgrades extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;

		// email Queue
		$sql = "ALTER TABLE {$prefix}permissions
				ADD COLUMN `Bonfire.Emailer.View` TINYINT(1) DEFAULT 0 NOT NULL";
		$this->db->query($sql);	
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$this->dbforge->drop_column('permissions', 'Bonfire.Emailer.View');
	}
	
	//--------------------------------------------------------------------
	
}