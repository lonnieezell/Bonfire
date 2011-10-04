<?php

class test_permission_model extends CodeIgniterUnitTestCase {

	private $role_id = null;
	private $permission_id = null;

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		$this->ci->load->model('permissions/permission_model', 'permission_model', true);
		$this->ci->load->model('roles/role_model', 'role_model', true);
	}
	
	//--------------------------------------------------------------------
	
	public function test_permission_model_is_loaded() 
	{
		$this->assertTrue(class_exists('Permission_model'));
	}
	
	//--------------------------------------------------------------------
	
	public function test_role_model_is_loaded() 
	{
		$this->assertTrue(class_exists('Role_model'));
	}		
		
	//--------------------------------------------------------------------
	
	public function test_update_returns_true_on_success_if_permission_status_is_inactive() 
	{
		$role = $this->ci->role_model->find(1);
		$perm = $this->ci->permission_model->find(1);
		
		$data = array(
			'permission_id' => $perm->permission_id,
			'status' => 'inactive'
		);
		
		$this->assertTrue($this->permission_model->update($role->role_id, $data));
	}		
		
	//--------------------------------------------------------------------
	
	public function test_update_returns_true_on_success_if_permission_status_is_active() 
	{
		$role = $this->ci->role_model->find(1);
		$perm = $this->ci->permission_model->find(1);
		
		$data = array(
			'permission_id' => $perm->permission_id,
			'status' => 'active'
		);
		
		$this->assertTrue($this->permission_model->update($role->role_id, $data));
	}
	
	//--------------------------------------------------------------------
	
}