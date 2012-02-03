<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_banning_users extends Migration {
	
	public function up() 
	{	
		$prefix = $this->db->dbprefix;
		
		$sql = array();
		
		$sql[] = "ALTER TABLE  `{$prefix}users` ADD  `banned` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `deleted`, ADD  `ban_message` VARCHAR( 255 ) NULL DEFAULT NULL AFTER  `banned`";
		
		$sql[] = "DELETE FROM `{$prefix}roles` WHERE `{$prefix}roles`.`role_name` = 'Banned'";
		
		foreach ($sql as $s)
		{
			$this->db->query($s);
		}
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		$sql = array();
		
		$sql[] = "ALTER TABLE `{$prefix}users` DROP `banned`,  DROP `ban_message`;";
		
		$sql[] = "INSERT INTO `{$prefix}roles` (`role_name`, `description`) VALUES ('Banned', 'Banned users are not allowed to sign into your site.')";
		
		foreach ($sql as $s)
		{
			$this->db->query($s);
		}
	}
	
	//--------------------------------------------------------------------
	
}