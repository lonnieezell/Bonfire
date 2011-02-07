<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends Admin_Controller {
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Developer.View');
		$this->auth->restrict('Bonfire.Logs.View');
		
		Template::set('toolbar_title', 'System Logs');
		
		// Logging enabled?
		Template::set('log_threshold', $this->config->item('log_threshold'));
		
		Assets::add_js($this->load->view('developer/logs_js', null, true), 'inline');
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		$this->load->helper('file');
		
		// Log Files
		Template::set('logs', get_filenames($this->config->item('log_path')));
	
		Template::render('for_ui');
	}
	
	//--------------------------------------------------------------------
	
	public function enable() 
	{
		if ($this->input->post('submit'))
		{
			$this->load->helper('config_file');
			
			if (write_config('config', array('log_threshold' => $_POST['log_threshold'])))
			{
				Template::set_message('Log settings successfully saved.', 'success');
			} else
			{
				Template::set_message('Unable to save log settings. Check the write permissions on <b>appication/config.php</b> and try again.', 'error');
			}
		}
	
		redirect('admin/developer/logs');
	}
	
	//--------------------------------------------------------------------
	
	public function view($file='') 
	{
		if (empty($file))
		{
			Template::set_message('No log file provided.', 'error');
			redirect('admin/settings/developer/logs');
		}
				
		Template::set('log_file', $file .EXT);
		Template::set('log_content', file($this->config->item('log_path') . $file));
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}