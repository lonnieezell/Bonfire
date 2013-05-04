<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Permissions_to_manage_activities extends Migration {
	
	public function up() 
	{
		$this->load->library('session');
		
		$prefix = $this->db->dbprefix;
		
		// add the soft deletes column, made it (12) to accomodate time stamp change coming
		$sql = "ALTER TABLE `{$prefix}activities` ADD COLUMN `deleted` TINYINT(12) DEFAULT '0' NOT NULL AFTER `created_on`";
		$this->db->query($sql);
		
		$data = array(
		   array(
		      'name' 		=> 'Bonfire.Activities.Manage' ,
		      'description' => 'Allow users to access the Activities Reports' 
		   ),
		   array(
		      'name' 		=> 'Activities.Own.View' ,
		      'description' => 'To view the users own activity logs' 
		   ),
		   array(
		      'name' 		=> 'Activities.Own.Delete' ,
		      'description' => 'To delete the users own activity logs' 
		   ),
		   array(
		      'name' 		=> 'Activities.User.View' ,
		      'description' => 'To view the user activity logs' 
		   ),
		   array(
		      'name' 		=> 'Activities.User.Delete' ,
		      'description' => 'To delete the user activity logs, except own' 
		   ),
		   array(
		      'name' 		=> 'Activities.Module.View' ,
		      'description' => 'To view the module activity logs' 
		   ),
		   array(
		      'name' 		=> 'Activities.Module.Delete' ,
		      'description' => 'To delete the module activity logs' 
		   ),
		   array(
		      'name' 		=> 'Activities.Date.View' ,
		      'description' => 'To view the users own activity logs' 
		   ),
		   array(
		      'name' 		=> 'Activities.Date.Delete' ,
		      'description' => 'To delete the dated activity logs' 
		   )
		);
		
		$this->db->insert_batch("{$prefix}permissions", $data);
		
		// give current role (or administrators if fresh install) full right to manage permissions
		$assign_role = $this->session->userdata('role_id') ? $this->session->userdata('role_id') : 1;
		
		$permissions = $this->db->select('permission_id')->where("(name = 'Bonfire.Activities.Manage') OR (name LIKE 'Activities.%.View') OR (name LIKE 'Activities.%.Delete')")->get($prefix.'permissions')->result();
		if (isset($permissions) && is_array($permissions) && count($permissions)) {
			foreach ($permissions as $perm) {
				$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(".$assign_role.",".$perm->permission_id.")");
			}
		}
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		$roles = $this->role_model->find_all();
		if (isset($roles) && is_array($roles) && count($roles)) {
			foreach ($roles as $role) {
				// delete any but that has any of these permissions from the role_permissions table
				$query = $this->db->query("SELECT `permission_id` FROM `{$prefix}permissions` WHERE `name` LIKE 'Activities.%.View' or `name` LIKE 'Activities.%.Delete' or `name` = 'Bonfire.Activities.Manage'");
				foreach ($query->result_array() as $row)
				{
					$permission_id = $row['permission_id'];
					$this->db->query("DELETE FROM `{$prefix}role_permissions` WHERE `permission_id` = '$permission_id';");
				}
				//delete the role
				$this->db->query("DELETE FROM `{$prefix}permissions` WHERE `name` LIKE 'Activities.%.View' or `name` LIKE 'Activities.%.Delete' or `name` = 'Bonfire.Activities.Manage'");
			}
		}
		
		// drop the added deleted column
		$sql = "ALTER TABLE `{$prefix}activities` DROP COLUMN `deleted`";
		$this->db->query($sql);	
	}
	
	//--------------------------------------------------------------------
	
}