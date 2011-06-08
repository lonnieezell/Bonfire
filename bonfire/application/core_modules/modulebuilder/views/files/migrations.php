<?php

$migrations = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class Migration_Install_'.$module_name_lower.' extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
	
		// Email Queue
		$this->dbforge->add_field(\'`id` int(11) NOT NULL AUTO_INCREMENT\');';
		for($counter=1; $field_total >= $counter; $counter++)
		{
			//Due to the requiredif rule if the first field is set the the others must be
			if (set_value("view_field_label$counter") == NULL)
			{
				continue; 	// move onto next iteration of the loop
			}

		$migrations .= '
		$this->dbforge->add_field(\'`'.set_value("view_field_name$counter").'` '.set_value("db_field_type$counter");
		
			if (!in_array(set_value("db_field_type$counter"), array('TEXT', 'DATETIME'))) // There are no doubt more types where a value/length isn't possible - needs investigating
			{
				$migrations .= '('.set_value("db_field_length_value$counter").')';
			}
		

		$migrations .= ' NOT NULL\');';
		
		}
		$migrations .= '
		$this->dbforge->add_key(\'id\', true);
		$this->dbforge->create_table(\''.$module_name_lower.'\');

	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$this->dbforge->drop_table(\''.$module_name_lower.'\');
	}
	
	//--------------------------------------------------------------------
	
}';

echo $migrations;
?>
