<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Permissions_for_profiler extends Migration
{
	//--------------------------------------------------------------------

	private $permission_array = array(
					'Bonfire.Profiler.View' => 'To view the Console Profiler Bar.',
					);

	//--------------------------------------------------------------------

	public function up()
	{
		$prefix = $this->db->dbprefix;


		foreach ($this->permission_array as $name => $description)
		{
			$this->db->query("INSERT INTO {$prefix}permissions(name, description) VALUES('".$name."', '".$description."')");
			
			$insert_id = $this->db->insert_id();
			// gives administrators and developer roles full right to manage permissions		
			$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1,{$insert_id})");
			$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(6,{$insert_id})");

			unset($insert_id);
		}

	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		foreach ($this->permission_array as $name => $description)
		{
			$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = '".$name."'");
			foreach ($query->result_array() as $row)
			{
				$permission_id = $row['permission_id'];
				$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
			}
			//delete the role
			$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = '".$name."')");
		}

	}

	//--------------------------------------------------------------------

}