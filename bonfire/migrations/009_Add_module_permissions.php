<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Add Bonfire.Modules.Add and Bonfire.Modules.Delete to the permissions
 */
class Migration_Add_module_permissions extends Migration
{
	/****************************************************************
	 * Table names
	 */
	/**
	 * @var string The name of the permissions table
	 */
	private $permissions_table = 'permissions';

	/**
	 * @var string The name of the role_permissions table
	 */
	private $role_permissions_table = 'role_permissions';

	/****************************************************************
	 * Data for Insert
	 */
	/**
	 * @var array The permissions data to insert
	 */
	private $permissions_data = array(
		array(
			'name' => 'Bonfire.Modules.Add',
			'description' => 'Allow creation of modules with the builder.',
		),
		array(
			'name' => 'Bonfire.Modules.Delete',
			'description' => 'Allow deletion of modules.',
		),
	);

	/**
	 * @var int The role_id of the Administrator role
	 */
	private $admin_role_id = 1;

	/****************************************************************
	 * Migration methods
	 */
	/**
	 * Install this migration
	 */
	public function up()
	{
		$this->load->library('session');

		// add administrators to module permissions
		$assign_role = $this->session->userdata('role_id') ? $this->session->userdata('role_id') : $this->admin_role_id;

		$role_data = array();
		foreach ($this->permissions_data as $data)
		{
			$this->db->insert($this->permissions_table, $data);
			$role_data[] = array(
				'role_id' => $assign_role,
				'permission_id' => $this->db->insert_id(),
			);
		}
		$this->db->insert_batch($this->role_permissions_table, $role_data);
	}

	/**
	 * Uninstall this migration
	 */
	public function down()
	{
		$permission_names = array();
		foreach ($this->permissions_data as $data)
		{
			$permission_names[] = $data['name'];
		}

		$query = $this->db->select('permission_id')
			->where_in('name', $permission_names)
			->get($this->permissions_table);

		$permission_ids = array();
		foreach ($query->result_array() as $row)
		{
			$permission_ids[] = $row['permission_id'];
		}
		$this->db->where_in('permission_id', $permission_ids)
			->delete($this->role_permissions_table);

		$this->db->where_in('name', $permission_names)
			->delete($this->permissions_table);
	}
}