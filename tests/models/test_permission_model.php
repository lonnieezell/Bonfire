<?php

class test_permission_model extends CodeIgniterUnitTestCase {

	private $tmp_tables = array("permissions","role_permissions","roles");
	private $pf = null; // database prefix
	private $mpf = null; // database + mock prefix

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		//load the models 
		$this->ci->load->model('permissions/permission_model', 'permission_model', true);
		$this->ci->load->model('roles/role_model', 'role_model', true);
		$this->ci->load->model('roles/role_permission_model', 'role_permission_model', true);
		
		// use the temp tables, not production tables
		$this->ci->role_model->set_table('mock_roles');
		$this->ci->permission_model->set_table('mock_permissions');
		$this->ci->role_permission_model->set_table('mock_role_permissions');
		
		// set the database prefix and the mock prefix
		$this->pf = $this->ci->db->dbprefix;
		$this->mpf = $this->pf . 'mock_';
	}
	
	//--------------------------------------------------------------------
	
	public function test_create_tmp_tables() 
	{	
		foreach($this->tmp_tables as $table) {
			$this->assertTrue($this->ci->db->query("CREATE TEMPORARY TABLE IF NOT EXISTS ".$this->mpf.$table." SELECT * FROM ".$this->pf.$table), "Temporary Table ".$this->mpf.$table." created");
		}
	}
	
	//--------------------------------------------------------------------
	
	public function test_permission_model_is_loaded() 
	{
		$this->assertTrue(class_exists('Permission_model',false), 'Permission Model is fully loaded');
	}
	
	//--------------------------------------------------------------------
	
	public function test_role_model_is_loaded() 
	{
		$this->assertTrue(class_exists('Role_model',false), 'Role Model is fully loaded');
	}
	
	//--------------------------------------------------------------------
	
	public function test_role_permission_model_is_loaded() 
	{
		$this->assertTrue(class_exists('Role_permission_model',false), 'Role Permission Model is fully loaded');
	}			
		
	//--------------------------------------------------------------------
	
	public function test_update_returns_true_on_success_if_permission_status_is_inactive() 
	{				
		$role = $this->ci->role_model->find_all();
		$perm = $this->ci->permission_model->find_all();
		$data = array(
			'permission_id' => $perm[0]->permission_id,
			'status' => 'inactive'
		);
		$role_id = $role[0]->role_id;
		
		$this->assertTrue($this->ci->permission_model->update($role_id, $data), "Verified setting permission status to inactive");
	}		
		
	//--------------------------------------------------------------------
	
	public function test_update_returns_true_on_success_if_permission_status_is_active() 
	{		
		$role = $this->ci->role_model->find_all();
		$perm = $this->ci->permission_model->find_all();
		$data = array(
			'permission_id' => $perm[0]->permission_id,
			'status' => 'active'
		);
		$role_id = $role[0]->role_id;
		
		$this->assertTrue($this->ci->permission_model->update($role_id, $data), "Verified setting permission status to active");
	}
	
	//--------------------------------------------------------------------
	
	/* this should be the last function to be sure all testing is finished with the temporary tables */
	public function test_remove_tmp_tables() 
	{		
		foreach($this->tmp_tables as $table) {
			$this->assertTrue($this->ci->db->query("DROP TABLE ".$this->mpf.$table), "Temporary Table ".$this->mpf.$table." deleted");	
		}		
	}
	
	//--------------------------------------------------------------------
	
}