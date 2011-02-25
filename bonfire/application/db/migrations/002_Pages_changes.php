<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Pages_changes extends Migration {

	var $migration_type = 'sql';
	
	public function up() 
	{
		$sql =<<<SQL
ALTER TABLE  `bf_pages` ADD  `rte_type` VARCHAR( 20 ) NULL DEFAULT NULL AFTER  `rich_text`;

CREATE TABLE  `bonfire2_dev`.`bf_versions` (
`version_id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`page_id` BIGINT( 20 ) NOT NULL DEFAULT  '0',
`revision` INT NOT NULL DEFAULT  '0',
`body` TEXT NOT NULL ,
`rte_type` VARCHAR( 10 ) NOT NULL DEFAULT 'html',
`created_on` DATETIME NOT NULL ,
`created_by` BIGINT( 20 ) NOT NULL DEFAULT 0,	
INDEX (  `page_id` )
) ENGINE = INNODB;
SQL;

		return $sql;
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$sql =<<<SQL
ALTER TABLE  `bf_pages` DROP COLUMN `rte_type`;
DROP TABLE `bf_versions`;
SQL;

		return $sql;
	}
	
	//--------------------------------------------------------------------
	
}