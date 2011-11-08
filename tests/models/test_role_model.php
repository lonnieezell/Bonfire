<?php 

class test_role_model extends CodeIgniterUnitTestCase {

	private $role 		= false;
	private $role_id	= 0;

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		$this->ci->load->model('roles/role_model', 'role_model', true);
	}
	
	//--------------------------------------------------------------------
	
	public function test_is_loaded() 
	{
		$this->assertTrue(class_exists('Role_model'));
	}
	
	//--------------------------------------------------------------------

	public function test_permission_model_loaded() 
	{
		$this->assertTrue(class_exists('Permission_model'));
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_returns_false_with_invalid_id() 
	{
		$role = $this->ci->role_model->find(1955840);
		$this->assertFalse($role);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_returns_false_with_string_id() 
	{
		$role = $this->ci->role_model->find('testing');
		$this->assertFalse($role);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_returns_stdClass() 
	{
		$role = $this->ci->role_model->find(1);
		$this->assertIsA($role, 'stdClass');
		$this->role = $role;
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_returns_role_info() 
	{
		//echo '<pre>'; print_r($this->role);
		$this->assertTrue($this->role->role_name == 'Administrator');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_returns_permission_array() 
	{
		$this->assertTrue(isset($this->role->permissions));
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_returns_role_permission_array() 
	{
		$this->assertTrue(isset($this->role->role_permissions));
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_by_name_returns_false_on_empty() 
	{
		$role = $this->ci->role_model->find_by_name();
		$this->assertFalse($role);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_by_name_returns_object() 
	{
		$role = $this->ci->role_model->find_by_name('Banned');
		$this->assertIsA($role, 'stdClass');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_by_name_ignores_case() 
	{
		$role = $this->ci->role_model->find_by_name('banned');
		$this->role = $role;
		$this->assertIsA($role, 'stdClass');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_by_name_returns_permissions() 
	{
		$this->assertTrue(isset($this->role->permissions));
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_by_name_returns_role_permissions() 
	{
		//echo '<pre>'; print_r($this->role);
		$this->assertTrue(isset($this->role->role_permissions));
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_by_name_has_right_permissions() 
	{
		// Permission for Site.Signin.Allow = 1
		$this->assertTrue(!isset($this->role->role_permissions[1]));
	}
	
	//--------------------------------------------------------------------
	
	public function test_default_role_id_returns_integer() 
	{
		$role_id = $this->ci->role_model->default_role_id();
		$this->role_id = $role_id;
		$this->assertIsA($role_id, 'Integer');
	}
	
	//--------------------------------------------------------------------
	
	public function test_update_changes_default_role() 
	{
		// $this->role_id stores the current default role
		// $this->role currently has the Banned role.
		$data = array(
			'default'	=> 1
		);
		$this->ci->role_model->update($this->role->role_id, $data);
		
		$role_id = $this->ci->role_model->default_role_id();
		
		$this->assertEqual($role_id, $this->role->role_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_update_returns_true_on_success() 
	{
		$data = array(
			'default' => 1
		);
		
		$this->assertTrue($this->ci->role_model->update($this->role_id, $data));
	}
	
	//--------------------------------------------------------------------
	
	public function test_can_delete_returns_false_for_admin() 
	{
		$this->assertFalse($this->ci->role_model->can_delete_role(1));
	}
	
	//--------------------------------------------------------------------
	
	public function test_can_delete_returns_false_for_banned() 
	{
		$this->assertFalse($this->ci->role_model->can_delete_role($this->role_id));
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_returns_id() 
	{
		$data = array(
			'role_name'	=> 'Test Role',
			'description'	=> 'A simple role to test deleting.'
		);
		
		$this->role_id = $this->ci->role_model->insert($data);
		
		$this->assertTrue($this->role_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_delete_uses_soft_deletes() 
	{
		$this->ci->role_model->delete($this->role_id);
		
		$role = $this->ci->role_model->find($this->role_id);
		$this->assertEqual($role->deleted, 1);
	}
	
	//--------------------------------------------------------------------
	
	public function test_delete_purges_role() 
	{
		$this->ci->role_model->delete($this->role_id, true);
		
		$role = $this->ci->role_model->find($this->role_id);
		$this->assertFalse($role);
	}
	
	//--------------------------------------------------------------------
	
}