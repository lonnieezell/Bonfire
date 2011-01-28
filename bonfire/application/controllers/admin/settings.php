<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		Template::set('toolbar_title', 'Site Settings');
		
		$this->load->helper('config_file');
	}
	
	//--------------------------------------------------------------------	

	public function index() 
	{
		if ($this->input->post('submit'))
		{
			if ($this->save_settings())
			{
				Template::set_message('Your settings were successfully saved.', 'success');
				redirect('admin/settings');
			} else 
			{
				Template::set_message('There was an error saving your settings.', 'error');
			}
		}
		
		// Read our current settings
		Template::set('settings', read_config('application'));
	
		Template::set_view('admin/settings/index');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	private function save_settings() 
	{
		$this->form_validation->set_rules('title', 'Site Name', 'required|trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('system_email', 'Site Email', 'required|trim|strip_tags|valid_email|xss_clean');
		$this->form_validation->set_rules('list_limit', 'List Limit', 'required|trim|strip_tags|numeric|xss_clean');
		
		if ($this->form_validation->run() === false)
		{
			return false;
		}
		
		$data = array(
			'site.title' 		=> $_POST['title'],
			'site.system_email'	=> $_POST['system_email'],
			'site.status'		=> $_POST['status'],
			'site.list_limit'	=> $_POST['list_limit'],
			
			'auth.allow_remember'	=> isset($_POST['allow_remember']) ? 1 : 0,
			'auth.remember_length'	=> (int)$_POST['remember_length']
		);
		
		return write_config('application', $data);
	}
	
	//--------------------------------------------------------------------
	
	

}