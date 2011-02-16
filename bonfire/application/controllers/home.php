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
		Template::render();
	}
	
	//--------------------------------------------------------------------
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */