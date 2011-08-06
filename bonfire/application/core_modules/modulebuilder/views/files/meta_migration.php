<?php

$migrations = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class Migration_Install_'.$module_name_lower.'_meta_table extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;

		$this->dbforge->add_field(\'`id` int(11) NOT NULL AUTO_INCREMENT\');
		$this->dbforge->add_field(\'`link_id` int(11) NOT NULL\');
		$this->dbforge->add_field(\'`property` TEXT\');
		$this->dbforge->add_field(\'`value` TEXT\');
		$this->dbforge->add_key(\'id\', true);
		$this->dbforge->create_table(\''.$module_name_lower.'_meta\');
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;

		$this->dbforge->drop_table(\''.$module_name_lower.'_meta\');
	}
	
	//--------------------------------------------------------------------
	
}';

echo $migrations;
?>
