<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Install extends Base_Controller {

	protected $errors = '';

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		Template::set_theme('installer');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		if ($this->input->post('submit'))
		{
			$this->form_validation->set_rules('site_title', 'Site Title', 'required|trim|strip_tags|min_length[1]|xss_clean');
			$this->form_validation->set_rules('username', 'Username', 'required|trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|trim|strip_tags|alpha_dash|min_length[8]|xss_clean');
			$this->form_validation->set_rules('pass_confirm', 'Password (again)', 'required|trim|matches[password]');
			$this->form_validation->set_rules('email', 'Email', 'required|trim|strip_tags|valid_email|xss_clean');
			
			if ($this->form_validation->run() !== false)
			{
				if ($this->setup())
				{
					Template::set_message('You are good to go! Happy coding!', 'success');
					redirect('/login');
				}
				else 
				{
					Template::set('There was an error setting up your database: '. $this->errors, 'error');
				}
			}
		}
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	public function setup() 
	{
		//
		// First, save the information to the config/application.php file.
		//
		$this->load->helper('config_file');
		
		$config = array(
			'site.title'	=> $this->input->post('site_title'),
			'site.system_email'	=> $this->input->post('email')
		);
		
		if (write_config('application', $config) === false)
		{
			$this->errors = 'Unable to write to config/application.php. Make sure that it is writable and try again.';
			return false;
		}
		
		//
		// Now install the database tables.
		//
		$this->load->library('migrations/Migrations');
	
		if ($this->migration->version($this->config->item('migrations_version')) != 1)
		{
			$this->errors = 'There was an error setting up the database. Please check your settings and try again.';
		}
		
		//
		// Install the user in the users table so they can actually login.
		//
		$data = array(
			'role_id'	=> 1,
			'email'		=> $this->input->post('email'),
			'username'	=> $this->input->post('username'),
			'password'	=> $this->input->post('password')
		);
		
		if ($this->user_model->insert($data) == false)
		{
			$this->errors = 'There was an error creating your account in the database: '. $this->user_model->error;
			return false;
		}
		
		// We made it to the end, so we're good to go!
		return true;
	}
	
	//--------------------------------------------------------------------
	
}