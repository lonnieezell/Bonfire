<?php

class test_role_permission_model extends CodeIgniterUnitTestCase {

	private $role = null;
	private $role_id = null;
	private $permission_id = null;

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		$this->ci->load->model('roles/role_model', 'role_model', true);
		$this->ci->load->model('roles/role_permission_model', 'role_permission_model', true);
	}
	
	//--------------------------------------------------------------------
	
	public function test_is_loaded() 
	{
		$this->assertTrue(class_exists('Role_permission_model'));
	}
	
	//--------------------------------------------------------------------
	
	public function test_create_returns_false_on_empty_role_id() 
	{
		$this->assertFalse($this->ci->role_permission_model->create(null, 1));
	}
	
	//--------------------------------------------------------------------
	
	public function test_create_returns_false_on_empty_permission_id() 
	{
		$this->assertFalse($this->ci->role_permission_model->create(1));
	}
	
	//--------------------------------------------------------------------
	
	public function test_create_returns_int_on_success() 
	{
		// First create a temp role to use…
		$this->role_id = $this->ci->role_model->insert(array(
			'role_name'	=> 'Test Role'
		));
		$id = $this->ci->role_permission_model->create($this->role_id, 1);
		
		$this->assertIsA($id, 'Integer');
	}
	
	//--------------------------------------------------------------------
	
	public function test_create_actually_creates_relationship() 
	{
		$this->ci->db->where('role_id', $this->role_id);
		$query = $this->db->get('role_permissions');
		
		$this->assertNotEqual($query->num_rows(), 0);
	}
	
	//--------------------------------------------------------------------
	
	public function test_delete_for_role_returns_false_on_empty_role_id() 
	{
		$this->assertFalse($this->ci->role_permission_model->delete_for_role());
	}
	
	//--------------------------------------------------------------------
	
	public function test_delete_for_role_requires_integer_role_id() 
	{
		$this->assertFalse($this->ci->role_permission_model->delete_for_role('hungary'));
	}
	
	//--------------------------------------------------------------------
	
	
	
	// This method must be last since it deletes our working data.
	public function test_delete_deletes_entry() 
	{
		$this->ci->role_permission_model->delete($this->role_id, 1);
		$this->assertEqual($this->ci->db->affected_rows(), 1);
		
		// Delete our test role now…
		$this->ci->db->where('role_id', $this->role_id);
		$this->ci->db->delete('roles');
	}
	
	//--------------------------------------------------------------------
	
}