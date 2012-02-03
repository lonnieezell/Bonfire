<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
		
		Template::set('toolbar_title', lang('meta_manage'));
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}