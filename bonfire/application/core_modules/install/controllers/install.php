<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Install extends MX_Controller {

	protected $errors = '';
	
	/*
		Var: $writable_folders
		An array of folders the installer checks to make 
		sure they can be written to.
	*/
	private $writeable_folders = array(
		'cache',
		'logs',
		'config',
		'db/backups'
	);
	
	/*
		Var: $writeable_files
		An array of files the installer checks to make
		sure they can be written to.
	*/
	private $writeable_files = array(
		'config/application.php'
	);

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		$this->load->helper('form');
		
		$this->output->enable_profiler(false);
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{ 
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
	
		$this->startup_check();
		
		if (isset($_POST['hostname']) && isset($_POST['username']) && isset($_POST['database']) )
		{ 
			// Write the database config files
			$this->load->helper('config_file');
			
			$dbname = strip_tags($this->input->post('database'));
			
			$data = array(
				'main'	=> array(
					'hostname'	=> strip_tags($this->input->post('hostname')),
					'username'	=> strip_tags($this->input->post('username')),
					'password'	=> strip_tags($this->input->post('password')),
					'database'	=> $dbname,
					'dbprefix'	=> strip_tags($this->input->post('db_prefix'))
				),
				'development' => array(
					'hostname'	=> strip_tags($this->input->post('hostname')),
					'username'	=> strip_tags($this->input->post('username')),
					'password'	=> strip_tags($this->input->post('password')),
					'database'	=> $dbname,
					'dbprefix'	=> strip_tags($this->input->post('db_prefix'))
				)
			);
			
			if (write_db_config($data))
			{
				//
				// Make sure the database exists, otherwise create it.
				// CRAP! dbutil and database_forge require a running database driver,
				// which seems to require a valid database, which we don't have. To get 
				// past this, we'll deal only with MySQL for now and create things
				// the old fashioned way. Eventually, we'll make this more generic.
				//
				$db = mysql_connect(strip_tags($this->input->post('hostname')), strip_tags($this->input->post('username')), strip_tags($this->input->post('password')));
				
				if (!$db)
				{
					die('Unable to connect to database: '. mysql_error());
				}
				
				$db_selected = mysql_select_db($dbname, $db);
				if (!$db_selected)
				{
					// Table doesn't exist, so create it.
					if (!mysql_query("CREATE DATABASE $dbname", $db))
					{
						die('Unable to create database: '. mysql_error());
					}
					mysql_close($db);
				}
				 
				redirect('install/account');
			} else
			{
				Template::set_message('There was an error saving the settings. Please verify that your database and development/database config files are writeable.', 'attention');	
			}
		}
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function account() 
	{
		if ($this->input->post('submit'))
		{
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
		
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
	
	/*
		Method: startup_check()
		
		Verifies that the folders and files needed are writeable. Sets 
		'startup_errors' as a string in the template if not.
	*/
	private function startup_check() 
	{
		$errors = '';
		$folder_errors = '';
		$file_errors = '';
		
		// Check Folders
		foreach ($this->writeable_folders as $folder)
		{
			if (!is_writeable(APPPATH .$folder))
			{
				$folder_errors .= "<li>$folder</li>";
			}
		}
		
		if (!empty($folder_errors))
		{
			$errors = '<p>Please ensure that the following directories are writeable, and try again:</p><ul>' . $folder_errors .'</ul>';
		}
		
		// Check files
		foreach ($this->writeable_files as $file)
		{
			if (!is_writeable(APPPATH .$file))
			{
				$file_errors .= "<li>$file</li>";
			}
		}
		
		if (!empty($file_errors))
		{
			$errors .= '<p>Please ensure that the following files are writeable, and try again:</p><ul>' . $file_errors .'</ul>';
		}
		
		// Make it available to the template lib if there are errors
		if (!empty($errors))
		{
			Template::set('startup_errors', $errors);
		}
		
		unset($errors, $folder_errors, $file_errors);
	}
	
	//--------------------------------------------------------------------
	
	
	private function setup() 
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
	
		if (!$this->migrations->install())
		{
			$this->errors = 'There was an error setting up the database. Please check your settings and try again.';
		}
		
		//
		// Install the user in the users table so they can actually login.
		//
		$this->load->model('users/User_model', 'user_model', true);
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