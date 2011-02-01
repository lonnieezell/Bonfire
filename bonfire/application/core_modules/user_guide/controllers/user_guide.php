<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_guide extends Base_Controller {
	
	public $current_page;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		Template::set_theme('user_guide');
	}
	
	//--------------------------------------------------------------------
	
	public function _remap() 
	{
		// For base user_guide
		$this->current_page = trim(str_replace('user_guide', '', $this->uri->uri_string()), '/ ');
		
		$this->index();
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		Template::set_view($this->current_page);
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	
	
}