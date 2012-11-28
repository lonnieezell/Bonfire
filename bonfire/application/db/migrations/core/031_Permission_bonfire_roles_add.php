<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Permission_bonfire_roles_add extends Migration
{
	//--------------------------------------------------------------------

	private $permission_array = array(
		array('name' => 'Bonfire.Roles.Add', 'description' => 'To add New Roles', 'status' => 'active'),
	);

	//--------------------------------------------------------------------

	public function up()
	{
		$prefix = $this->db->dbprefix;

		foreach ($this->permission_array as $permission_value)
		{
			$this->db->insert("permissions", $permission_value);
			$role_permissions_data = array('role_id' => '1', 'permission_id' => $this->db->insert_id(),);
			$this->db->insert("role_permissions", $role_permissions_data);
		}
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		foreach($this->permission_array as $permission_value)
		{
			$query = $this->db->select('permission_id')->get_where("permissions", array('name' => $permission_value['name']));
			foreach($query->result_array() as $row)
			{
				$permission_id = $row['permission_id'];
				$this->db->delete("role_permissions", array('permission_id' => $permission_id));
			}
			$this->db->delete("permissions", array('name' => $permission_value['name']));
		}
	}

	//--------------------------------------------------------------------

}