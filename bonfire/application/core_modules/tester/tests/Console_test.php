<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Console_test extends Unit_Tester {

	public function __construct() 
	{
		parent::__construct();
	
		if (!class_exists('Console'))
		{
			$this->load->libraries('Console');
		}
	}
	
	//--------------------------------------------------------------------
	
	public function test_class_loaded() 
	{
		$this->assert_true(class_exists('Console'));
	}
	
	//--------------------------------------------------------------------
	
	public function test_log_returns_false_on_empty_data() 
	{
		$r = Console::log();
		$this->assert_false($r);
	}
	
	//--------------------------------------------------------------------
	
	public function test_log_stores_message() 
	{
		Console::log('abcdef');
		$r = Console::get_logs();
		$this->assert_equals($r['log_count'], 1);
	}
	
	//--------------------------------------------------------------------
	
	public function test_log_memory_stores_value() 
	{
		Console::log_memory($this->ci);
		$r = Console::get_logs();
		$this->assert_equals($r['memory_count'], 1);
	}
	
	//--------------------------------------------------------------------
	
}