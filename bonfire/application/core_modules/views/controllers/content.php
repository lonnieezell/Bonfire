<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	The Views content controller.
	
	Views controller allows you to actually edit the CodeIgniter views for your 
	site within the admin pages.
*/
class Content extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		Template::set('toolbar_title', 'Manage Module Views');
		
		Assets::add_js($this->load->view('content/view_js', null, true), 'inline');
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		Template::set('modules', module_list(true));
		Template::set('module_files', module_files(null, 'views', true));
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function edit($module='', $view="") 
	{
		echo "Module = $module, view = $view";
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
}