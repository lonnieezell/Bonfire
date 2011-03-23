<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Settings.View');
		
		$this->load->helper('config_file');
		
		Template::set('toolbar_title', 'Database Settings');
		
		Assets::add_js($this->load->view('settings/database_js', null, true), 'inline');
	}
	
	//--------------------------------------------------------------------
	

	public function index() 
	{		
		Template::set('settings', read_db_config());
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function edit() 
	{
		$server_type = $this->uri->segment(5);
	
		if ($this->input->post('submit'))
		{
			//echo '<pre>'; print_r($_POST); die();
		
			unset($_POST['server_type'], $_POST['submit']);
		
			if (write_db_config(array($server_type => $_POST)) == TRUE)
			{
				Template::set_message('Your settings were successfully saved.', 'success');
			} else 
			{
				Template::set_message('There was an error saving the settings.', 'error');
			}
		}
		
		$settings = read_db_config($server_type);
		
		Template::set('db_settings', $settings[$server_type]);
	
		Template::set('server_type', $server_type);
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}

// End Database Settings class