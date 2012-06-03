<?php

$acl_migrations = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class Migration_Install_'.$module_name_lower.'_permissions extends Migration {

	public function up()
	{
		$prefix = $this->db->dbprefix;

		// permissions';
foreach($contexts as $context)
{
	if ($context != 'public')
	{
		$permission = '';
		$permission = ucfirst($module_name).".";
		$permission .= ucfirst($context).".";
		foreach($action_names as $action_name) {
			$action_permission = '';
			$action_name = ucfirst($action_name);
			if($action_name == 'Index') {
				$action_name = 'View';
			}
			$action_permission = $permission . $action_name;
			$action_status = 'active';
			$action_description = '';
			$acl_migrations .= '
		$permissions_data = array(\'name\' => \''.$action_permission.'\', \'description\' => \''.$action_description.'\', \'status\' => \''.$action_status.'\',);
		$this->db->insert("{$prefix}permissions", $permissions_data);
		$role_permissions_data = array(\'role_id\' => \''.$role_id.'\', \'permission_id\' => $this->db->insert_id(),);
		$this->db->insert("{$prefix}role_permissions", $role_permissions_data);';

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
		$permission .= ucfirst($context).".";
		foreach($action_names as $action_name) {
			$action_permission = '';
			$action_name = ucfirst($action_name);
			if($action_name == 'Index') {
				$action_name = 'View';
			}
			$action_permission = $permission . $action_name;
			$acl_migrations .= '
		$query = $this->db->select(\'permission_id\')->get_where("{$prefix}permissions", array(\'name\' => \''.$action_permission.'\',));
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row[\'permission_id\'];
			$this->db->delete("{$prefix}role_permissions", array(\'permission_id\' => $permission_id));
		}
		$this->db->delete("{$prefix}permissions", array(\'name\' => \''.$action_permission.'\'));';

		}
	}
}

$acl_migrations .= '
	}

	//--------------------------------------------------------------------

}';

echo $acl_migrations;
?>
