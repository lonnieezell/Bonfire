<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Logs Developer file
*/
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
	
	/*
		Method: index()
		
		Lists all log files and allows you to change the log_threshold.
	*/
	public function index() 
	{
		$this->load->helper('file');
		
		// Log Files
		Template::set('logs', get_filenames($this->config->item('log_path')));
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: enable()
		
		Saves the logging threshold value.
	*/
	public function enable() 
	{
		$this->auth->restrict('Bonfire.Logs.Manage');
	
		if ($this->input->post('submit'))
		{
			$this->load->helper('config_file');
			
			if (write_config('config', array('log_threshold' => $_POST['log_threshold'])))
			{
				Template::set_message('Log settings successfully saved.', 'success');
			} else
			{
				Template::set_message('Unable to save log settings. Check the write permissions on <b>application/config.php</b> and try again.', 'error');
			}
		}
	
		redirect('admin/developer/logs');
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: view()
		
		Shows the contents of a single log file.
		
		Parameter: 
			$file	- the full name of the file to view (including extension).
	*/
	public function view($file='') 
	{
		if (empty($file))
		{
			Template::set_message('No log file provided.', 'error');
			redirect('admin/settings/developer/logs');
		}
				
		Template::set('log_file', $file);
		Template::set('log_content', file($this->config->item('log_path') . $file));
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: purge()
		
		Deletes all existing log files.
	*/
	public function purge() 
	{
		$this->auth->restrict('Bonfire.Logs.Manage');
	
		$this->load->helper('file');
		
		delete_files($this->config->item('log_path'));
	
		redirect('admin/developer/logs');
	}
	
	//--------------------------------------------------------------------
	
}