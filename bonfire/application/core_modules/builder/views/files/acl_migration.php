<?php

$acl_migrations = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class Migration_Install_'.$module_name_lower.'_permissions extends Migration {

	// permissions to migrate
	private $permission_values = array(';

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

			$acl_migrations .= "
		array('name' => '".$action_permission."', 'description' => '".$action_description."', 'status' => '".$action_status."',),";
		}
	}
}

$acl_migrations .= '
	);

	//--------------------------------------------------------------------

	public function up()
	{
		$prefix = $this->db->dbprefix;

		// permissions
		foreach ($this->permission_values as $permission_value)
		{
			$permissions_data = $permission_value;
			$this->db->insert("permissions", $permissions_data);
			$role_permissions_data = array(\'role_id\' => \''.$role_id.'\', \'permission_id\' => $this->db->insert_id(),);
			$this->db->insert("role_permissions", $role_permissions_data);
		}
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

        // permissions
		foreach ($this->permission_values as $permission_value)
		{
			$query = $this->db->select(\'permission_id\')->get_where("permissions", array(\'name\' => $permission_value[\'name\'],));
			foreach ($query->result_array() as $row)
			{
				$permission_id = $row[\'permission_id\'];
				$this->db->delete("role_permissions", array(\'permission_id\' => $permission_id));
			}
			$this->db->delete("permissions", array(\'name\' => $permission_value[\'name\']));

		}
	}

	//--------------------------------------------------------------------

}';

echo $acl_migrations;
?>
