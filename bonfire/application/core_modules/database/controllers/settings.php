<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Settings.View');
		
		$this->load->helper('config_file');
		
		Template::set('toolbar_title', 'Database Settings');
	}
	
	//--------------------------------------------------------------------
	

	public function index() 
	{
		if (isset($_POST['submit']))
		{
			if (write_db_config($_POST) === TRUE)
			{
				Template::set_message('Your settings were successfully saved.', 'success');
				redirect($this->uri->uri_string());
			} else 
			{
				Template::set_message('There was an error saving the settings.', 'error');
			}
		}
		
		Template::set('settings', read_db_config());
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}

// End Database Settings class