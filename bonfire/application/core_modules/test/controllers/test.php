<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require dirname(__FILE__) .'/unit_test_controller.php';

class Test extends Base_Controller {

	private	$base_tests_folder	= 'tests/';

	public function __construct() 
	{
		parent::__construct();
		
		Template::set_theme('test_suite');
		
		$this->base_tests_folder = FCPATH .'bonfire/'. $this->base_tests_folder;
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		// Run tests, if they're selected.
		if ($this->input->post('submit'))
		{
			foreach ($this->input->post('tests') as $test)
			{
				if ($test)
				{
					// Load the class
					require_once($this->base_tests_folder .'/'. $test .'.php');
					$test = $test;
					
					$class = new $test();
					
					// Run the tests...
					$class->run();
				}
			}
		}
	
		// Get our tests for the menu.
		$this->find_tests();
	
		$this->template->render();
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	private function find_tests() 
	{
		if (!function_exists('directory_map'))
		{
			$this->load->helper('directory');
		}
		
		$map = directory_map($this->base_tests_folder);
		
		Template::set('core_tests', $map);
	}
	
	//--------------------------------------------------------------------
	
}

/* End of file Test.php */
/* Location: ./application/controllers/Test.php */