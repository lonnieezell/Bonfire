<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Developer.View');
		$this->auth->restrict('Bonfire.Database.Manage');
		
		$this->config->load('migrations');
		$this->load->library('Migrations');	
	}

	//--------------------------------------------------------------------
	
	public function index() 
	{
		Template::set('installed_version', $this->migrations->get_schema_version());
		Template::set('latest_version', $this->migrations->get_latest_version());
	
		Template::set('toolbar_title', 'Database Migrations');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}

// End Migrations Developer Class