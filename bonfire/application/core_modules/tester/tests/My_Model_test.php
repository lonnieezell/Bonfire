<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_Model_test extends Unit_tester {

	private $model;

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		if (!class_exists('MY_Model'))
		{
			require APPPATH .'core/MY_Model.php';
		}
		
		$this->model = new MY_Model();
		
		$this->ci->load->database();
	}
	
	//--------------------------------------------------------------------
	
	public function pre() 
	{
		$this->model->set_table('users');
		$this->model->set_date_format('datetime');
		$this->model->set_modified(false);
	}
	
	//--------------------------------------------------------------------
	
	public function test_is_loaded() 
	{
		$this->assert_true(class_exists('MY_Model'));
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_with_empty_string() 
	{
		$user = $this->model->find();
		$this->assert_false($user);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_with_id() 
	{
		$user = $this->model->find(1);
		$this->assert_is_type($user->email, 'string');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_doesnt_work_with_string() 
	{
		$user = $this->model->find('testing');
		$this->assert_false($user);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all() 
	{
		$user = $this->model->find_all();
		$this->assert_is_type($user, 'array');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_by() 
	{
		$user = $this->model->find_all_by('deleted', 0);
		$this->assert_is_type($user, 'array');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_with_missing_value() 
	{
		$user = $this->model->find_all_by('deleted');
		$this->assert_false($user);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_by() 
	{
		$user = $this->model->find_by('id', 1);
		$this->assert_is_type($user, 'object');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_by_with_missing_value() 
	{
		$user = $this->model->find_by('deleted');
		$this->assert_false($user);
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
		$this->assert_is_type($id, 'integer');
		
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
		$this->assert_true($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_update_returns_false_on_failure() 
	{
		$result = $this->model->update($this->test_id);
		$this->assert_false($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_update_where_returns_true_on_success() 
	{
		$data =array(
			'deleted'	=> 2, 
		);
		
		$result = $this->model->update_where('deleted', 1, $data);
		$this->assert_true($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_update_where_returns_false_on_failure() 
	{
		$result = $this->model->update_where('deleted', 1);
		$this->assert_false($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_is_unique_returns_true_on_success() 
	{
		$result = $this->model->is_unique('email', 'askdjfaklsdjf');
		$this->assert_true($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_is_unique_returns_false_on_failure() 
	{
		$result = $this->model->is_unique('email', 'darth@starwars.com');
		$this->assert_false($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_count_all_returns_int() 
	{
		$result = $this->model->count_all();
		$this->assert_is_type($result, 'integer');
	}
	
	//--------------------------------------------------------------------
	
	public function test_count_by_returns_int() 
	{
		$result = $this->model->count_by('email', 'darth@starwars.com');
		$this->assert_is_type($result, 'integer');
	}
	
	//--------------------------------------------------------------------
	
	public function test_get_field_returns_value() 
	{
		$result = $this->model->get_field($this->test_id, 'email');
		$this->assert_identical($result, 'darth@starwars.com');
	}
	
	//--------------------------------------------------------------------
	
	public function test_get_field_returns_false_on_failure() 
	{
		$result = $this->model->get_field($this->test_id);
		$this->assert_false($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_delete() 
	{
		$result = $this->model->delete($this->test_id);
		$this->assert_true($result);
	}
	
	//--------------------------------------------------------------------
	
	
}