<?php

class test_console_lib extends CodeIgniterUnitTestCase {
	
	private $first_log_count;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		if (!class_exists('Console'))
		{
			$this->ci->load->library('Console');
		}
		
		$r = Console::get_logs();
		$this->first_log_count = $r['log_count'];
		unset($r);
	}
	
	//--------------------------------------------------------------------
	
	public function test_class_loaded() 
	{
		$this->assertTrue(class_exists('Console'));
	}
	
	//--------------------------------------------------------------------
	
	public function test_log_returns_false_on_empty_data() 
	{
		$r = Console::log();
		$this->assertFalse($r);
	}
	
	//--------------------------------------------------------------------
	
	public function test_log_stores_message() 
	{
		Console::log('abcdef');
		$r = Console::get_logs();
		$this->assertEqual($r['log_count'], $this->first_log_count +1);
	}
	
	//--------------------------------------------------------------------
	
	public function test_log_memory_stores_value() 
	{
		Console::log_memory($this->ci);
		$r = Console::get_logs();
		$this->assertEqual($r['memory_count'], 1);
	}
	
	//--------------------------------------------------------------------
}