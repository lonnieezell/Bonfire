<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model_test extends Unit_Tester {

	private $user_id = 0;
	private $user_rec;

	public function __construct() 
	{
		parent::__construct();
		
		$this->ci->load->model('users/User_model', 'user_model', true);
		
		// Make sure Darth Vader doesn't exist. :)
		$this->ci->db->where('email', 'darth@starwars.com');
		$this->ci->db->delete('users');
	}
	
	//--------------------------------------------------------------------
	
	public function test_is_loaded() 
	{
		$this->assert_true(class_exists('User_model'));
	}
	
	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// Inserts
	//--------------------------------------------------------------------
	
	public function test_insert_fails_with_no_data() 
	{
		$user_id = $this->ci->user_model->insert();
		$this->assert_false($user_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_fails_with_no_password() 
	{
		$user = array(
			'email'	=> 'darth@starwars.com'
		);
		$user_id = $this->ci->user_model->insert($user);
		$this->assert_false($user_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_fails_with_no_email() 
	{
		$user = array(
			'password'	=> 'dierebelscum'
		);
		$user_id = $this->ci->user_model->insert($user);
		$this->assert_false($user_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_user() 
	{
		$user = array(
			'email'	=> 'darth@starwars.com',
			'password'	=> 'dierebelscum'
		);
		$this->user_id = $this->ci->user_model->insert($user);
		$this->assert_is_type($this->user_id, 'Integer');
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_returns_int() 
	{
		$this->assert_not_empty($this->user_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_fails_with_duplicate_email() 
	{
		$user = array(
			'email'	=> 'darth@starwars.com',
			'password'	=> 'dierebelscum'
		);
		$user_id = $this->ci->user_model->insert($user);
		$this->assert_false($user_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_user_has_role() 
	{
		$this->user_rec = $this->ci->user_model->find($this->user_id);
		$this->assert_not_empty($this->user_rec->role_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_user_assigned_default_role() 
	{
		$this->assert_equals($this->user_rec->role_id, $this->ci->role_model->default_role_id());
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_stores_salt() 
	{
		$this->assert_not_empty($this->user_rec->salt);
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_password_does_not_match_original() 
	{
		$this->assert_not_equals($this->user_rec->password_hash, 'dierebelscum');
	}
	
	//--------------------------------------------------------------------
	
	public function test_password_hash_is_correct() 
	{
		if (!function_exists('do_hash'))
		{
			$this->ci->load->helper('security');
		}
	
		$hash = do_hash($this->user_rec->salt . 'dierebelscum');
		$this->assert_equals($hash, $this->user_rec->password_hash);
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_creates_default_country() 
	{
		$this->assert_equals($this->user_rec->country_iso, 'US');
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// Finds
	//--------------------------------------------------------------------
	
	public function test_find_returns_object() 
	{
		$this->assert_is_type($this->user_rec, 'Object');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_returns_role_name() 
	{
		$this->assert_not_null($this->user_rec->role_name);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_returns_array() 
	{
		$users = $this->ci->user_model->find_all();
		$this->assert_is_type($users, 'Array');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_returns_role() 
	{
		$users = $this->ci->user_model->find_all();
		$this->assert_not_empty($users[0]->role_id);
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// Deletes
	//--------------------------------------------------------------------
	
	public function test_delete_user_with_soft_delete() 
	{
		//$this->ci->user_model->set_soft_deletes(true);
		$this->assert_true($this->ci->user_model->delete($this->user_id));
	}
	
	//--------------------------------------------------------------------
	
	public function test_user_exists_after_soft_delete() 
	{
		$user = $this->ci->user_model->find($this->user_id);
		$this->assert_is_type($user, 'Object');
	}
	
	//--------------------------------------------------------------------
	
	public function test_user_has_deleted_flag_after_soft_delete() 
	{
		$user = $this->ci->user_model->select('users.deleted')->find($this->user_id);
		$this->assert_equals($user->deleted, 1);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_returns_user_deleted_not_role_deleted() 
	{
		$user = $this->ci->user_model->find($this->user_id);
		$this->assert_equals($user->deleted, 1);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_does_not_return_deleted() 
	{
		$users = $this->ci->user_model->find_all();
		$found = false;
		
		foreach ($users as $user)
		{
			if ($user->deleted == 1)
			{
				$found = true;
			}
		}
		
		$this->assert_false($found);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_returns_deleted_when_asked() 
	{
		$users = $this->ci->user_model->find_all(true);
		$found = false;
//	echo '<pre>'; die(print_r($users));	
		foreach ($users as $user)
		{
			if ($user->deleted === 1)
			{
				$found = true;
			}
		}
		
		$this->assert_true($found);
	}

	//--------------------------------------------------------------------
	
	public function test_delete_with_hard_deletes() 
	{
		$this->ci->user_model->set_soft_deletes(false);
		$this->assert_true($this->ci->user_model->delete($this->user_id));
	}
	
	//--------------------------------------------------------------------
	
	public function test_user_gone_after_hard_delete() 
	{
		$user = $this->ci->user_model->find($this->user_id);
		$this->assert_false($user);
	}
	
	//--------------------------------------------------------------------
}