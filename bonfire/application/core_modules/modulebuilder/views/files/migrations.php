<?php

$migrations = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class Migration_Install_'.$module_name_lower.' extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;

		$this->dbforge->add_field(\'`'.$primary_key_field.'` int(11) NOT NULL AUTO_INCREMENT\');';
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
		$this->dbforge->add_key(\''.$primary_key_field.'\', true);
		$this->dbforge->create_table(\''.$module_name_lower.'\');

		// permissions';
		if ($this->db->table_exists("role_permissions") == false) {
			// original permissions
		}
		else {
			// new permissions system
			
			foreach($contexts as $context) {
				$permission = '';
				if( $permission_details[0] == "Context") {
					$permission = lang('bf_context_'.strtolower($context)).".";
				}
				elseif($permission_details[0] == "Module") {
					$permission = $module_name.".";
				}
				else {
					$permission = $permission_details[0].".";
				}
				if( $permission_details[1] == "Context") {
					$permission .= lang('bf_context_'.strtolower($context)).".";
				}
				elseif($permission_details[1] == "Module") {
					$permission .= $module_name.".";
				}
				else {
					$permission .= $permission_details[1].".";
				}
				foreach($action_names as $action_name) {
					$action_permission = '';
					$action_name = ucfirst($action_name);
					if($action_name == 'Index') {
						$action_name = 'View';
					}
					if( $permission_details[2] == "Action") {
						$action_permission = $permission . $action_name;
					}
					elseif($permission_details[2] == "Method") {
						$action_permission = $permission . $action_name;
					}
					else {
						$action_permission = $permission . $permission_details[1];
					}
					$migrations .= '
					$this->db->query("INSERT INTO {$prefix}permissions VALUES (0,\''.$action_permission.'\',\'\',\'active\');");';
				}
			}
		}
		$migrations .= '

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
