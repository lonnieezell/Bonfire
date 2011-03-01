<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_tracking_table extends Migration {

	var $migration_type = 'sql';
	
	public function up() 
	{
		$sql =<<<SQL
CREATE TABLE `bf_tracking` (
`tracking_id` BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`resource_id` BIGINT( 20 ) NOT NULL DEFAULT '0',
`ip_address` CHAR( 15 ) NOT NULL ,
`created_on` DATETIME NOT NULL ,
INDEX ( `resource_id` )
) ENGINE = MYISAM ;
SQL;

		return $sql;
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$sql =<<<SQL
DROP TABLE `bf_tracking`;
SQL;

		return $sql;
	}
	
	//--------------------------------------------------------------------
	
}