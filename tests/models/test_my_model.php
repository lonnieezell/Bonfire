<?php

class test_my_model extends CodeIgniterUnitTestCase {

	private $model;

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		if (!class_exists('MY_Model'))
		{
			require ROOT .'bonfire/codeigniter/core/Model.php';
			require APP_DIR .'core/MY_Model.php';
		}
		
		$this->model = new BF_Model();
		
		$this->ci->load->database();
	}
	
	//--------------------------------------------------------------------
	
	public function SetUp() 
	{
		$this->model->set_table('users');
		$this->model->set_date_format('datetime');
		$this->model->set_modified(false);
	}
	
	//--------------------------------------------------------------------
	
	public function test_included() 
	{
		$this->assertTrue(class_exists('MY_model'));
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_with_empty_string() 
	{
		$user = $this->model->find();
		$this->assertFalse($user);
	}
	
	//--------------------------------------------------------------------

	public function test_find_with_id() 
	{
		$user = $this->model->find(1);
		$this->assertIsA($user->email, 'string');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_doesnt_work_with_string() 
	{
		$user = $this->model->find('testing');
		$this->assertFalse($user);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_returns_array() 
	{
		$user = $this->model->find_all();
		$this->assertIsA($user, 'array');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_by_returns_array() 
	{
		$user = $this->model->find_all_by('deleted', 0);
		$this->assertIsA($user, 'array');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_with_missing_value() 
	{
		$user = $this->model->find_all_by('deleted');
		$this->assertFalse($user);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_by_returns_class() 
	{
		$user = $this->model->find_by('id', 1);
		$this->assertIsA($user, 'stdClass');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_by_with_missing_value() 
	{
		$user = $this->model->find_by('deleted');
		$this->assertFalse($user);
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert() 
	{
		$data = array(
			'role_id'	=> 2,
			'email'		=> 'darth@starwars.com',
			'username'	=> 'Darth Vader',
			'password_hash'	=> '391496af034ea9def15e832f59e246b760da5fe8',
			'salt'		=> '0123456',
			
		);
		$id = $this->model->insert($data);
		$this->assertIsA($id, 'integer');
		
		// Store for later tests
		$this->test_id = $id;
	}
	
	//--------------------------------------------------------------------
	
	public function test_update_returns_true_on_success() 
	{
		$data =array(
			'deleted'	=> 1, 
		);
		
		$result = $this->model->update($this->test_id, $data);
		$this->assertTrue($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_update_returns_false_on_failure() 
	{
		$result = $this->model->update($this->test_id);
		$this->assertFalse($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_update_where_returns_true_on_success() 
	{
		$data =array(
			'deleted'	=> 2, 
		);
		
		$result = $this->model->update_where('deleted', 1, $data);
		$this->assertTrue($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_update_where_returns_false_on_failure() 
	{
		$result = $this->model->update_where('deleted', 1);
		$this->assertFalse($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_is_unique_returns_true_on_success() 
	{
		$result = $this->model->is_unique('email', 'askdjfaklsdjf');
		$this->assertTrue($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_is_unique_returns_false_on_failure() 
	{
		$result = $this->model->is_unique('email', 'darth@starwars.com');
		$this->assertFalse($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_count_all_returns_int() 
	{
		$result = $this->model->count_all();
		$this->assertIsA($result, 'integer');
	}
	
	//--------------------------------------------------------------------
	
	public function test_count_by_returns_int() 
	{
		$result = $this->model->count_by('email', 'darth@starwars.com');
		$this->assertIsA($result, 'integer');
	}
	
	//--------------------------------------------------------------------
	
	public function test_get_field_returns_value() 
	{
		$result = $this->model->get_field($this->test_id, 'email');
		$this->assertIdentical($result, 'darth@starwars.com');
	}
	
	//--------------------------------------------------------------------
	
	public function test_get_field_returns_false_on_failure() 
	{
		$result = $this->model->get_field($this->test_id);
		$this->assertFalse($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_delete() 
	{
		$result = $this->model->delete($this->test_id);
		$this->assertTrue($result);
	}
	
	//--------------------------------------------------------------------
}