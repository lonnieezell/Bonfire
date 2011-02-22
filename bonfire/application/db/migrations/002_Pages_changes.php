<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Pages_changes extends Migration {

	var $migration_type = 'sql';
	
	public function up() 
	{
		$sql =<<<SQL
ALTER TABLE  `bf_pages` ADD  `rte_type` VARCHAR( 20 ) NULL DEFAULT NULL AFTER  `rich_text`;
SQL;

		return $sql;
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$sql =<<<SQL
ALTER TABLE  `bf_pages` DROP COLUMN `rte_type`;
SQL;

		return $sql;
	}
	
	//--------------------------------------------------------------------
	
}