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
		$this->ci->load->model('roles/role_permission_model', 'role_permission_model', true);
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
		/*$role = $this->ci->role_model->find_all();
		$perm = $this->ci->permission_model->find_all();
		$data = array(
			'permission_id' => $perm[0]->permission_id,
			'status' => 'inactive'
		);
		$role_id = $role[0]->role_id;
		
		$this->assertTrue($this->ci->permission_model->update($role_id, $data));
		
		// restore the actual status. Oh crap. It is actually deleting the permission entry in role_permissions
		// have to capture which roles have the permission before delete so they can be restored.
		$data['status'] = $perm[0]->status;
		$this->ci->permission_model->update($role_id, $data);*/
	}		
		
	//--------------------------------------------------------------------
	
	public function test_update_returns_true_on_success_if_permission_status_is_active() 
	{
		/* $role = $this->ci->role_model->find_all();
		$perm = $this->ci->permission_model->find_all();
		$data = array(
			'permission_id' => $perm[0]->permission_id,
			'status' => 'active'
		);
		$role_id = $role[0]->role_id;
		
		$this->assertTrue($this->ci->permission_model->update($role_id, $data));
		
		// restore the actual status and role_permissions
		$data['status'] = $perm[0]->status;
		$this->ci->permission_model->update($role_id, $data); */
	}
	
	//--------------------------------------------------------------------
	
}