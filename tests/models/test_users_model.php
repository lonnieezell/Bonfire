<?php
class test_users_model extends CodeIgniterUnitTestCase
{
	protected $rand = '';

	public function __construct()
	{
		parent::__construct();

		$this->UnitTestCase('Users Model');

		$this->load->model('users/users_model');

		$this->rand = rand(500,15000);
	}

	public function setUp()
	{
		$this->db->truncate('users');

		$insert_data = array(
			    'user_email' => 'demo'.$this->rand.'@demo.com',
			    'user_username' => 'test_'.$this->rand,
			    'user_password' => 'demo_'.$this->rand,
			    'user_join_date' => time(),
				'user_group'	=> 1
			);
		$user_id = $this->users_model->add_user($insert_data);
		$this->user = $this->users_model->get_user($user_id);
    }

    public function tearDown()
	{

    }

	public function test_included()
	{
		$this->assertTrue(class_exists('users_model'));
	}

	public function test_add_user()
	{
		$insert_data = array(
			    'user_email' => 'demo'.$this->rand.'@demo.com',
			    'user_username' => 'test_'.$this->rand,
			    'user_password' => 'demo_'.$this->rand,
			    'user_join_date' => time(),
				'user_group'	=> 1
			);
		$user_id = $this->users_model->add_user($insert_data);

		//$this->dump($user_id);
		$this->assertEqual($user_id, 1, 'user id = 1');
	}

	public function test_get_user_by_id()
	{
		$user = $this->users_model->get_user(1);
		$this->assertEqual($user['user_id'], 1);
	}

	public function test_get_user_by_username()
	{
		$user = $this->users_model->get_user('test_'.$this->rand);
		$this->assertEqual($user['user_id'], 1);
	}

	public function test_edit_user()
	{
		$insert_data = array(
			    'user_email' => 'edit_demo'.$this->rand.'@demo.com',
			);
		$user = $this->users_model->edit_user(1, $insert_data);
		$this->assertTrue($user);
	}

	public function test_delete_user()
	{
		$user = $this->users_model->delete_user(1);
		$this->assertTrue($user);
	}

	public function test_username_exists()
	{
		$user = $this->users_model->username_check('test_'.$this->rand);
		$this->assertFalse($user);
	}

	public function test_username_does_not_exists()
	{
		$user = $this->users_model->username_check('my_super_test_'.$this->rand);
		$this->assertTrue($user);
	}

	public function test_email_exists()
	{
		$user = $this->users_model->email_check('demo'.$this->rand.'@demo.com');
		$this->assertFalse($user);
	}

	public function test_email_does_not_exists()
	{
		$user = $this->users_model->email_check('my_super_test_'.$this->rand.'@demo.com');
		$this->assertTrue($user);
	}
}

/* End of file test_users_model.php */
/* Location: ./tests/models/test_users_model.php */