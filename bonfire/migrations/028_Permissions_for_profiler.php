<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Add view permissions for the Profiler
 */
class Migration_Permissions_for_profiler extends Migration
{
	/**
	 * @var string The name of the permissions table
	 */
	private $table = 'permissions';

	/**
	 * @var string The name of the Role permissions table
	 */
	private $ref_table = 'role_permissions';

	/**
	 * @var array The permission to insert
	 */
	private $data = array(
		array(
			'name' => 'Bonfire.Profiler.View',
			'description' => 'To view the Console Profiler Bar.',
		),
	);

	/****************************************************************
	 * Migration methods
	 */
	/**
	 * Install this migration
	 */
	public function up()
	{
		$roles = array();
		foreach ($this->data as $permission)
		{
			$this->db->insert($this->table, $permission);
			$permission_id = $this->db->insert_id();

			$roles[] = array(
				'role_id' => 1,
				'permission_id' => $permission_id,
			);
			$roles[] = array(
				'role_id' => 6,
				'permission_id' => $permission_id,
			);
		}

		$this->db->insert_batch($this->ref_table, $roles);
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