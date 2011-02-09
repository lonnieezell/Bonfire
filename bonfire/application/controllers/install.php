<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Install extends Base_Controller {

	public function __construct() 
	{
		parent::__construct();
		
		Template::set_theme('installer');
	}
	
	//--------------------------------------------------------------------
	

	public function index() 
	{
		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}