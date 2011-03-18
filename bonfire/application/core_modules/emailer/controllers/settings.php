<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Emailer Settings Class

class Settings extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Settings.View');
		$this->auth->restrict('Bonfire.Emailer.Manage');
		
		Template::set_block('sub_nav', 'settings/_sub_nav');
		
		$this->load->helper('config_file');
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		$this->load->library('form_validation');
	
		if ($this->input->post('submit'))
		{
			$this->form_validation->set_rules('sender_email', 'System Email', 'required|trim|valid_email|max_length[120]|xss_clean');
			$this->form_validation->set_rules('mailpath', 'Sendmail Path', 'trim|xss_clean');
			$this->form_validation->set_rules('smtp_host', 'SMTP Server Address', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_user', 'SMTP Username', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_pass', 'SMTP Password', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_port', 'SMTP Port', 'trim|strip_tags|numeric|xss_clean');
			$this->form_validation->set_rules('smtp_timeout', 'SMTP timeout', 'trim|strip_tags|numeric|xss_clean');
			
			if ($this->form_validation->run() !== FALSE)
			{
				$data = array(
					'sender_email'	=> $this->input->post('sender_email'),
					'protocol'		=> strtolower($_POST['protocol']),
					'mailpath'		=> $_POST['mailpath'],
					'smtp_host'		=> isset($_POST['smtp_host']) ? $_POST['smtp_host'] : '',
					'smtp_user'		=> isset($_POST['smtp_user']) ? $_POST['smtp_user'] : '',
					'smtp_pass'		=> isset($_POST['smtp_pass']) ? $_POST['smtp_pass'] : '',
					'smtp_port'		=> isset($_POST['smtp_port']) ? $_POST['smtp_port'] : '',
					'smtp_timeout'	=> isset($_POST['smtp_timeout']) ? $_POST['smtp_timeout'] : '5'
				);	
				
				if (write_config('email', $data))
				{
					// Success, so reload the page, so they can see their settings
					Template::set_message('Email settings successfully saved.', 'success');
					redirect('/admin/settings/emailer');
				}
				else 
				{
					Template::set_message('There was an error saving the file: config/email.', 'error');
				}
			}
		}
		
		// Load our current settings
		Template::set(read_config('email'));
		
		Template::set('toolbar_title', 'Email Settings');
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function template() 
	{
		if ($this->input->post('submit'))
		{
			$header = $_POST['header'];
			$footer = $_POST['footer'];
			
			$this->load->helper('file');
			
			write_file(APPPATH .'modules/emailer/views/email/_header.php', $header, 'w+');
			write_file(APPPATH .'modules/emailer/views/email/_footer.php', $footer, 'w+');
			
			Template::set_message('Template successfully saved.', 'success');
			
			redirect('admin/settings/emailer/template');
		}
	
		Template::set('toolbar_title', 'Email Template');
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function emails() 
	{
		Template::set('toolbar_title', 'Email Contents');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}

// End Admin class