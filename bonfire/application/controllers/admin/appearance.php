<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Appearance extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		Template::set('toolbar_title', 'Appearance');
		
		$this->auth->restrict('Site.Appearance.View');
	}
	
	//--------------------------------------------------------------------	

	public function index() 
	{
		Template::set_view('admin/appearance/index');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}