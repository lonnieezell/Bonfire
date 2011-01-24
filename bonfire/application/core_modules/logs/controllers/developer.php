<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends Admin_Controller {
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		Template::set('toolbar_title', 'System Logs');
		
		// Logging enabled?
		Template::set('log_threshold', $this->config->item('log_threshold'));
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		$this->load->helper('file');
		
		// Log Files
		Template::set('log_files', get_filenames($this->config->item('log_path')));
	
		Template::render();
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
				redirect($this->uri->uri_string());
			} else
			{
				Template::set_message('Unable to save log settings. Check the write permissions on <b>appication/config.php</b> and try again.', 'error');
			}
		}
	
		Template::render();
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
		Template::set('log_content', file($this->config->item('log_path') . $file .EXT));
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}