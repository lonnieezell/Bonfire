<?php

$acl_migrations = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class Migration_Install_'.$module_name_lower.'_permissions extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;


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
					$acl_migrations .= '
					$this->db->query("INSERT INTO {$prefix}permissions VALUES (0,\''.$action_permission.'\',\'\',\'active\');");
					$this->db->query("INSERT INTO {$prefix}role_permissions VALUES ('.$role_id.',".$this->db->insert_id().");");';
				}
			}
		}

		$acl_migrations .= '
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;

        // permissions';
		foreach($contexts as $context) {
			if ($context != 'public')
			{
				$permission = '';
				$permission = ucfirst($module_name) .".";
				$permission .= lang('bf_context_'.strtolower($context)).".";
				foreach($action_names as $action_name) {
					$action_permission = '';
					$action_name = ucfirst($action_name);
					if($action_name == 'Index') {
						$action_name = 'View';
					}
					$action_permission = $permission . $action_name;
					$acl_migrations .= '
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

		$acl_migrations .= '
	}
	
	//--------------------------------------------------------------------
	
}';

echo $acl_migrations;
?>
