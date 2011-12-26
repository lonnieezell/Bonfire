<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Emailer_Custom_From extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;

		$sql = "ALTER TABLE `{$prefix}email_queue` ADD COLUMN `from_email` VARCHAR( 128 ) collate utf8_unicode_ci NOT NULL AFTER `id`";
		$this->db->query($sql);
		$sql = "ALTER TABLE `{$prefix}email_queue` ADD COLUMN `from_name` VARCHAR( 128 ) collate utf8_unicode_ci NOT NULL AFTER `from_email`";
		$this->db->query($sql);
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		$sql = "ALTER TABLE `{$prefix}email_queue` DROP `from_email` ";
		$this->db->query($sql);
		$sql = "ALTER TABLE `{$prefix}email_queue` DROP `from_name` ";
		$this->db->query($sql);
		
	}
	
	//--------------------------------------------------------------------
	
}