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
	
	public function migrate_to($version=0) 
	{
		if ($this->input->post('submit') == 'Migrate Database')
		{
			$result = $this->migrations->version($version);
			
			if ($result)
			{
				Template::set_message('Successfully migrated database to version '. $result, 'success');
			} else 
			{
				Template::set_message('There was an error migrating the database.', 'error');
			}
			redirect('admin/developer/migrations');
		}
	
		Template::set('installed_version', $this->migrations->get_schema_version());
		Template::set('latest_version', $this->migrations->get_latest_version());
		Template::set('migrations', $this->migrations->get_available_versions());
	
		Template::set('toolbar_title', 'Database Migrations');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}

// End Migrations Developer Class