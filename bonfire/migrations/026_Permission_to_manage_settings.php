<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Add Permissions for the Settings Context
 */
class Migration_Permission_to_manage_settings extends Migration
{
	/**
	 * @var string Name of the Permissions table
	 */
	private $table = 'permissions';

	/**
	 * @var string Name of the Role permissions table
	 */
	private $ref_table = 'role_permissions';

	/**
	 * @var array New permissions
	 */
	private $data = array(
		array(
			'name' => 'Bonfire.Settings.View',
			'description' => 'To view the site settings page.',
		),
		array(
			'name' => 'Bonfire.Settings.Manage',
			'description' => 'To manage the site settings.',
		),
	);

	/**
	 * @var array Structure of the role data
	 */
	private $role_data = array(
		'role_id' => 1,
		'permission_id' => 0,
	);

	/****************************************************************
	 * Migration methods
	 */
	/**
	 * Install this migration
	 */
	public function up()
	{
		// add the permissions and store the permission_id values
		$roles = array();
		foreach ($this->data as $data)
		{
			$this->db->insert($this->table, $data);
			$this->role_data['permission_id'] = $this->db->insert_id();
			$roles[] = $this->role_data;
		}

		// add the permission to the admin
		if ( ! empty($roles))
		{
			$this->db->insert_batch($this->ref_table, $roles);
		}
	}

	/**
	 * Uninstall this migration
	 */
	public function down()
	{
		$permission_names = array();
		$permission_ids = array();

		foreach ($this->data as $permission)
		{
			$permission_names[] = $permission['name'];
		}

		if ( ! empty($permission_names))
		{
			$query = $this->db->select('permission_id')
				->where_in('name', $permission_names)
				->get($this->table);

			foreach ($query->result() as $row)
			{
				$permission_ids[] = $row->permission_id;
			}

			if ( ! empty($permission_ids))
			{
				$this->db->where_in('permission_id', $permission_ids)
					->delete($this->ref_table);
			}

			$this->db->where_in('name', $permission_names)
				->delete($this->table);
		}
	}
}