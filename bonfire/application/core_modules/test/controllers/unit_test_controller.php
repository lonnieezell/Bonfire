<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Unit_test_controller extends CI_Controller {

	protected	$class		= '';
	protected	$method		= '';
	
	protected	$message	= '';
	protected	$asserts;
	

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->load->library('unit_test');
	}
	
	//--------------------------------------------------------------------
	
	/*
		The main entry point into the script, this method will run
		either a single test or all tests (if no parameters are passed.)
	*/
	public function run($test=null) 
	{
		// Run all tests
		if (empty($test))
		{
			foreach ($this->get_test_methods() as $method)
			{
				$this->run($method);
			}
		}
		
		// Run a single test
		else 
		{
			// Reset test message
			$this->message = '';
			
			// Reset the assert
			$this->asserts	 = true;
			
			// Run setup method
			$this->pre();
			
			// Run the test
			$this->$test();
			
			// Run tear down method
			$this->post();
	
			// Pass the test case to CodeIgniter
			$this->unit->run($this->asserts, TRUE, $desc);
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Returns an array of all methods in the test class that start
		with the 'test_'.
	*/
	public function get_test_methods() 
	{
		$methods = get_class_methods($this);
		
		$test_methods = array();
		foreach ($methods as $method) 
		{
			if (substr(strtolower($method), 0, 5) == 'test_') 
			{
				$test_methods[] = $method;
			}
		}
		return $test_methods;
	}
	
	//--------------------------------------------------------------------
	
	protected function pre() 
	{
		
	}
	
	//--------------------------------------------------------------------
	
	protected function post() 
	{
		
	}
	
	//--------------------------------------------------------------------
	

}

// End Unit_test_controller