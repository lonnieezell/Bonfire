<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Content extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		Template::set('toolbar_title', 'Content');
		
		$this->auth->restrict('Site.Content.View');
	}
	
	//--------------------------------------------------------------------	

	public function index() 
	{	
		Template::set_view('admin/content/index');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}