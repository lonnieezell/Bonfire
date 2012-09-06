<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Banning_users_fix extends Migration
{
	//--------------------------------------------------------------------

	private $permission_array = array(
		array('name' => 'Site.Signin.Allow', 'description' => 'Allow users to login to the site', 'status' => 'active'),
	);

	//--------------------------------------------------------------------

	public function up()
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

	public function down()
	{
		/*
		HACK.

		Ensure current role (administrators if fresh install)
		can still log in.  If you downgrade the code as well,
		the _other_ roles will be treated as banned :).

		This is a courtesy to Bonfire developers tracking down regressions.
		In general, downgrading in production would not be a good idea.
		*/

		$prefix = $this->db->dbprefix;

		foreach ($this->permission_array as $permission_value)
		{
			$this->db->insert("permissions", $permission_value);

			$role_permissions_data = array('role_id' => '1', 'permission_id' => $this->db->insert_id(),);
			$this->db->insert("role_permissions", $role_permissions_data);
		}
	}

	//--------------------------------------------------------------------

}
