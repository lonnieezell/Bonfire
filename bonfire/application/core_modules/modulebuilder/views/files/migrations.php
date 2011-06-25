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
		
			if (!in_array(set_value("db_field_type$counter"), array('TEXT', 'DATE', 'DATETIME'))) // There are no doubt more types where a value/length isn't possible - needs investigating
			{
				$migrations .= '('.set_value("db_field_length_value$counter").')';
			}
		

		$migrations .= ' NOT NULL\');';
		
		}
		$migrations .= '
		$this->dbforge->add_key(\''.$primary_key_field.'\', true);
		$this->dbforge->create_table(\''.$module_name_lower.'\');

		// permissions';
		foreach($contexts as $context) {
			if ($context != 'public')
			{
				$permission = '';
				$permission = ucfirst($module_name).".";
				$permission .= lang('bf_context_'.strtolower($context)).".";
				foreach($action_names as $action_name) {
					$action_permission = '';
					$action_name = ucfirst($action_name);
					if($action_name == 'Index') {
						$action_name = 'View';
					}
					$action_permission = $permission . $action_name;
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
		$prefix = $this->db->dbprefix;

		$this->dbforge->drop_table(\''.$module_name_lower.'\');
		// permissions';
		foreach($contexts as $context) {
			if ($context != 'public')
			{
				$permission = '';
				$permission = $module_name.".";
				$permission .= lang('bf_context_'.strtolower($context)).".";
				foreach($action_names as $action_name) {
					$action_permission = '';
					$action_name = ucfirst($action_name);
					if($action_name == 'Index') {
						$action_name = 'View';
					}
					$action_permission = $permission . $action_name;
					$migrations .= '
					$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name=\''.$action_permission.'\';");
					foreach ($query->result_array() as $row)
					{
						$permission_id = $row[\'permission_id\'];
						$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id=\'$permission_id\';");
					}
					$this->db->query("DELETE FROM {$prefix}permissions WHERE name=\''.$action_permission.'\';");';
				}
			}
		}

		$migrations .= '
	}
	
	//--------------------------------------------------------------------
	
}';

echo $migrations;
?>
