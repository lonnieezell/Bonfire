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
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		Template::set('modules', module_list(true));
		Template::set('module_files', module_files(null, 'views', true));
	
		Template::render('for_ui');
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
}