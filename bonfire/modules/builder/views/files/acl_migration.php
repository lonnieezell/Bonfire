<?php

$acl_migrations = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class Migration_Install_' . $module_name_lower . '_permissions extends Migration
{

	/**
	 * Permissions to Migrate
	 *
	 * @var Array
	 */
	private $permission_values = array(';

foreach ($contexts as $context)
{
	if ($context != 'public')
	{
		$permission = ucfirst($module_name) . '.';
		$permission .= ucfirst($context) . '.';

		foreach ($action_names as $action_name)
		{
			$action_permission = '';
			$action_name = ucfirst($action_name);

			if ($action_name == 'Index')
			{
				$action_name = 'View';
			}

			$action_permission = $permission . $action_name;
			$action_status = 'active';
			$action_description = '';

			$acl_migrations .= '
		array(
			\'name\' => \'' . $action_permission . '\',
			\'description\' => \'' . $action_description . '\',
			\'status\' => \'' . $action_status . '\',
		),';
		}
	}
}

$acl_migrations .= '
	);

	/**
	 * The name of the permissions table
	 *
	 * @var String
	 */
	private $table_name = \'permissions\';

	/**
	 * The name of the role/permissions ref table
	 *
	 * @var String
	 */
	private $roles_table = \'role_permissions\';

	//--------------------------------------------------------------------

	/**
	 * Install this migration
	 *
	 * @return void
	 */
	public function up()
	{
		$role_permissions_data = array();
		foreach ($this->permission_values as $permission_value)
		{
			$this->db->insert($this->table_name, $permission_value);

			$role_permissions_data[] = array(
				\'role_id\' => \'' . $role_id . '\',
				\'permission_id\' => $this->db->insert_id(),
			);
		}

		$this->db->insert_batch($this->roles_table, $role_permissions_data);
	}

	//--------------------------------------------------------------------

	/**
	 * Uninstall this migration
	 *
	 * @return void
	 */
	public function down()
	{
		foreach ($this->permission_values as $permission_value)
		{
			$query = $this->db->select(\'permission_id\')
				->get_where($this->table_name, array(\'name\' => $permission_value[\'name\'],));

			foreach ($query->result() as $row)
			{
				$this->db->delete($this->roles_table, array(\'permission_id\' => $row->permission_id));
			}

			$this->db->delete($this->table_name, array(\'name\' => $permission_value[\'name\']));
		}
	}

	//--------------------------------------------------------------------

}';

echo $acl_migrations;