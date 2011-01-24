<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Content extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		Template::set('toolbar_title', 'Content');
	}
	
	//--------------------------------------------------------------------	

	public function index() 
	{
		Template::set_view('admin/stats/index');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}