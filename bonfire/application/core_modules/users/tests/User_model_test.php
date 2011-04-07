<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model_test extends Unit_Tester {

	public function __construct() 
	{
		parent::__construct();
		
		$this->load->model('users/User_model', 'user_model', true);
	}
	
	//--------------------------------------------------------------------
	
	public function test_is_loaded() 
	{
		$this->assert_true(class_exists('User_model'));
	}
	
	//--------------------------------------------------------------------

	public function test_if_failing() 
	{
		$this->assert_true(false);
	}
	
	//--------------------------------------------------------------------
	
	
}