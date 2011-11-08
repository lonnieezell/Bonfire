<?php
class test_user_model extends CodeIgniterUnitTestCase
{

	private $user_id = 0;
	private $user_rec;

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->ci->load->model('users/User_model', 'user_model', true);
		$this->ci->load->model('permissions/Permission_model', 'permission_model', true);
		
		// Make sure Darth Vader doesn't exist. :)
		$this->ci->db->where('email', 'darth@starwars.com');
		$this->ci->db->delete('users');
	}
	
	//--------------------------------------------------------------------
	
	public function test_is_loaded() 
	{
		$this->assertTrue(class_exists('User_model'));
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// Inserts
	//--------------------------------------------------------------------
	
	public function test_insert_fails_with_no_data() 
	{
		$user_id = $this->ci->user_model->insert();
		$this->assertFalse($user_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_fails_with_no_password() 
	{
		$user = array(
			'email'	=> 'darth@starwars.com'
		);
		$user_id = $this->ci->user_model->insert($user);
		$this->assertFalse($user_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_fails_with_no_email() 
	{
		$user = array(
			'password'	=> 'dierebelscum'
		);
		$user_id = $this->ci->user_model->insert($user);
		$this->assertFalse($user_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_user() 
	{
		$user = array(
			'email'	=> 'darth@starwars.com',
			'password'	=> 'dierebelscum',
			'username'	=> 'Darth Vader'
		);
		$this->user_id = $this->ci->user_model->insert($user);
		$this->assertIsA($this->user_id, 'Integer');
	}
	
	//--------------------------------------------------------------------

	public function test_insert_returns_int() 
	{
		$this->assertIsA($this->user_id, 'Integer');
	}
	
	//--------------------------------------------------------------------

	public function test_insert_fails_with_duplicate_email() 
	{
		$user = array(
			'email'	=> 'darth@starwars.com',
			'password'	=> 'dierebelscum'
		);
		$user_id = $this->ci->user_model->insert($user);
		$this->assertFalse($user_id);
	}
	
	//--------------------------------------------------------------------
	
	public function test_user_has_role() 
	{
		$this->user_rec = $this->ci->user_model->find($this->user_id);
		$this->assertTrue(!empty($this->user_rec->role_id));
	}
	
	//--------------------------------------------------------------------
	
	public function test_user_assigned_default_role() 
	{
		$this->assertEqual($this->user_rec->role_id, $this->ci->role_model->default_role_id());
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_stores_salt() 
	{
		$this->assertTrue(!empty($this->user_rec->salt));
	}
	
	//--------------------------------------------------------------------
	
	public function test_insert_password_does_not_match_original() 
	{
		$this->assertNotEqual($this->user_rec->password_hash, 'dierebelscum');
	}
	
	//--------------------------------------------------------------------
	
	public function test_password_hash_is_correct() 
	{
		if (!function_exists('do_hash'))
		{
			$this->ci->load->helper('security');
		}
	
		$hash = do_hash($this->user_rec->salt . 'dierebelscum');
		$this->assertEqual($hash, $this->user_rec->password_hash);
	}
	
	//--------------------------------------------------------------------

	public function test_insert_creates_default_country() 
	{
		$this->assertEqual($this->user_rec->country_iso, 'US');
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// Updates
	//--------------------------------------------------------------------
	
	public function test_update_allows_empty_usernames() 
	{
		$this->assertTrue($this->ci->user_model->update($this->user_id, array('username'=>'') ));
	}
	
	//--------------------------------------------------------------------
	
	public function test_update_empty_username_sticks() 
	{
		$user = $this->ci->user_model->find($this->user_id);
		$this->assertEqual($user->username, '');
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// Finds
	//--------------------------------------------------------------------

	public function test_find_returns_object() 
	{
		$this->assertIsA($this->user_rec, 'stdClass');
	}
	
	//--------------------------------------------------------------------

	public function test_find_returns_role_name() 
	{
		$this->assertNotNull($this->user_rec->role_name);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_returns_array() 
	{
		$users = $this->ci->user_model->find_all();
		$this->assertIsA($users, 'Array');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_returns_role() 
	{
		$users = $this->ci->user_model->find_all();
		$this->assertTrue(!empty($users[0]->role_id));
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// Deletes
	//--------------------------------------------------------------------
	
	public function test_delete_user_with_soft_delete() 
	{
		//$this->ci->user_model->set_soft_deletes(true);
		$this->assertTrue($this->ci->user_model->delete($this->user_id));
	}
	
	//--------------------------------------------------------------------
	
	public function test_user_exists_after_soft_delete() 
	{
		$user = $this->ci->user_model->find($this->user_id);
		$this->assertIsA($user, 'stdClass');
	}
	
	//--------------------------------------------------------------------
	
	public function test_user_has_deleted_flag_after_soft_delete() 
	{
		$user = $this->ci->user_model->select('users.deleted')->find($this->user_id);
		$this->assertEqual($user->deleted, 1);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_returns_user_deleted_not_role_deleted() 
	{
		$user = $this->ci->user_model->find($this->user_id);
		$this->assertEqual($user->deleted, 1);
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
		
		$this->assertFalse($found);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_all_returns_deleted_when_asked() 
	{
		$users = $this->ci->user_model->find_all(true);
		$found = false;

		foreach ($users as $user)
		{
			if ($user->deleted == 1)
			{
				$found = true;
			}
		}
		
		$this->assertTrue($found);
	}

	//--------------------------------------------------------------------
	
	public function test_delete_with_hard_deletes() 
	{
		$this->ci->user_model->set_soft_deletes(false);
		$this->assertTrue($this->ci->user_model->delete($this->user_id));
	}
	
	//--------------------------------------------------------------------
	
	public function test_user_gone_after_hard_delete() 
	{
		$user = $this->ci->user_model->find($this->user_id);
		$this->assertFalse($user);
	}
	
	//--------------------------------------------------------------------
}

/* End of file test_users_model.php */
/* Location: ./tests/models/test_users_model.php */