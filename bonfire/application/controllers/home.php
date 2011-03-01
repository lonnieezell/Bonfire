<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Base_Controller {

	function __construct()
	{
		parent::__construct();
		$this->output->enable_profiler(true);
	}
	
	//--------------------------------------------------------------------

	function index()
	{
		
		Console::log('Testing the base log feature.');
		Console::log(array('test1', 'test2'));
		Console::log_memory();
		Console::log_memory(array('test'), 'Test Array');
		
		Template::render();
	}
	
	//--------------------------------------------------------------------
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */