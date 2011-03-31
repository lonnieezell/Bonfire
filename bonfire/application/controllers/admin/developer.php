<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Developer.View');
		
		Template::set('toolbar_title', 'Developer Tools');
	}
	
	//--------------------------------------------------------------------	

	public function index() 
	{
		Template::set_view('admin/developer/index');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}