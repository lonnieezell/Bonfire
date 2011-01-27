<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Content extends Admin_Controller {

	public function index() 
	{
		Template::set('toolbar_title', 'Manage Pages');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function create() 
	{
		Template::set('toolbar_title', 'Create New Page');
		Template::set_view('content/page_form');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}