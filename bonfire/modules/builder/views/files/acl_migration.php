<?php

/*
 * Values passed from calling method:
 * $module_name_lower
 * $module_name
 * $contexts
 * $action_names
 * $role_id
 */

$ucModuleName = ucfirst($module_name_lower);
$action_status = 'active';
$permissionValues = '';

foreach ($contexts as $context) {
	if ($context != 'public') {
        $ucContextName = ucfirst($context);
		$permission = "{$ucModuleName}.{$ucContextName}.";

		foreach ($action_names as $action_name) {
			$action_name = ucfirst($action_name);
			if ($action_name == 'Index') {
				$action_name = 'View';
			}

			$action_permission = $permission . $action_name;
			$action_description = "{$action_name} {$ucModuleName} {$ucContextName}";

			$permissionValues .= "
		array(
			'name' => '{$action_permission}',
			'description' => '{$action_description}',
			'status' => '{$action_status}',
		),";
		}
	}
}

$permissionValues = "array({$permissionValues}
    );";

echo "<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_{$module_name_lower}_permissions extends Migration
{
	/**
	 * @var array Permissions to Migrate
	 */
	private \$permissionValues = {$permissionValues}

    /**
     * @var string The name of the permission key in the role_permissions table
     */
    private \$permissionKey = 'permission_id';

    /**
     * @var string The name of the permission name field in the permissions table
     */
    private \$permissionNameField = 'name';

	/**
	 * @var string The name of the role/permissions ref table
	 */
	private \$rolePermissionsTable = 'role_permissions';

    /**
     * @var numeric The role id to which the permissions will be applied
     */
    private \$roleId = '{$role_id}';

    /**
     * @var string The name of the role key in the role_permissions table
     */
    private \$roleKey = 'role_id';

	/**
	 * @var string The name of the permissions table
	 */
	private \$tableName = 'permissions';

	//--------------------------------------------------------------------

	/**
	 * Install this version
	 *
	 * @return void
	 */
	public function up()
	{
		\$rolePermissionsData = array();
		foreach (\$this->permissionValues as \$permissionValue) {
			\$this->db->insert(\$this->tableName, \$permissionValue);

			\$rolePermissionsData[] = array(
                \$this->roleKey       => \$this->roleId,
                \$this->permissionKey => \$this->db->insert_id(),
			);
		}

		\$this->db->insert_batch(\$this->rolePermissionsTable, \$rolePermissionsData);
	}

	/**
	 * Uninstall this version
	 *
	 * @return void
	 */
	public function down()
	{
        \$permissionNames = array();
		foreach (\$this->permissionValues as \$permissionValue) {
            \$permissionNames[] = \$permissionValue[\$this->permissionNameField];
        }

        \$query = \$this->db->select(\$this->permissionKey)
                          ->where_in(\$this->permissionNameField, \$permissionNames)
                          ->get(\$this->tableName);

        if ( ! \$query->num_rows()) {
            return;
        }

        \$permissionIds = array();
        foreach (\$query->result() as \$row) {
            \$permissionIds[] = \$row->{\$this->permissionKey};
        }

        \$this->db->where_in(\$this->permissionKey, \$permissionIds)
                 ->delete(\$this->rolePermissionsTable);

        \$this->db->where_in(\$this->permissionNameField, \$permissionNames)
                 ->delete(\$this->tableName);
	}
}";