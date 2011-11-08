<?php

class test_activity_model extends CodeIgniterUnitTestCase {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->ci->load->model('activities/activity_model', 'activity_model', true);
		$this->ci->load->helper('application');
	}
	
	//--------------------------------------------------------------------
	
	public function __destruct() 
	{
		// Make sure we don't have logs lying around
		$this->db->where('module', 'unit tests');
		$this->db->delete('activities');
	}
	
	//--------------------------------------------------------------------
	
	public function test_is_loaded() 
	{
		$this->assertTrue(class_exists('Activity_model'));
	}
	
	//--------------------------------------------------------------------

	public function test_log_activity_fails_with_no_info() 
	{
		error_reporting(0);
		$result = $this->ci->activity_model->log_activity();
		$this->assertFalse($result);
		error_reporting(E_ALL);
	}
	
	//--------------------------------------------------------------------
	
	public function test_log_activity_fails_with_no_user_id() 
	{
		error_reporting(0);
		$result = $this->ci->activity_model->log_activity(null, 'Did Something', 'unit tests');
		$this->assertFalse($result);
		error_reporting(E_ALL);
	}
	
	//--------------------------------------------------------------------
	
	public function test_log_activity_fails_with_string_id() 
	{
		error_reporting(0);
		$result = $this->ci->activity_model->log_activity('one', 'Did Something', 'unit_tests');
		$this->assertFalse($result);
		error_reporting(E_ALL);
	}
	
	//--------------------------------------------------------------------
	
	public function test_log_activity_fails_with_float_id() 
	{
		error_reporting(0);
		$result = $this->ci->activity_model->log_activity(1.03, 'Did Something', 'unit tests');
		$this->assertFalse($result);
		error_reporting(E_ALL);
	}
	
	//--------------------------------------------------------------------
	
	public function test_log_activity_fails_with_no_activity() 
	{
		error_reporting(0);
		$result = $this->ci->activity_model->log_activity(1);
		$this->assertFalse($result);
		error_reporting(E_ALL);
	}
	
	//--------------------------------------------------------------------
	
	public function test_failed_log_creates_message() 
	{
		error_reporting(0);
		$msg = Template::message();
		$this->assertIsA($msg, 'string');
		error_reporting(E_ALL);
	}
	
	//--------------------------------------------------------------------
	
	public function test_log_creates_db_entry() 
	{
		$result = $this->ci->activity_model->log_activity(1, 'Ran a Test', 'unit tests');
		
		$prefix = $this->ci->db->dbprefix;
		$this->ci->db->where('module', 'unit tests');
		$query = $this->ci->db->get($prefix.'activities');
		$count = $query->num_rows();
		
		$this->assertNotEqual($count, 0);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_fails_with_no_params() 
	{
		$result = $this->ci->activity_model->find_by_module();
		$this->assertFalse($result);
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_works_with_string() 
	{
		$result = $this->ci->activity_model->find_by_module('unit tests');
		$this->assertIsA($result, 'Array');
	}
	
	//--------------------------------------------------------------------
	
	public function test_find_works_with_array() 
	{
		$result = $this->ci->activity_model->find_by_module(array('unit tests', 'users'));
		$this->assertIsA($result, 'Array');
	}
	
	//--------------------------------------------------------------------
}