<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function index() 
	{
		Template::set_view('admin/home/index');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}

// End Admin class