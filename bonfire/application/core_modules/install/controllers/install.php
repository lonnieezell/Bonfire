<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

/*
	Class: Install
	
	Helps the developer with the initial install of the application for developement
	purposes by...
	
	1. Creating necessary config files so they won't be overwritten during upgrades.
	2. Sets up the database.
	3. Creates the initial database schema.
	4. Creates the initial admin user.
	
	Module:	Installer
*/
class Install extends MX_Controller {

	protected $errors = '';
    
	/*
		Var: $curl_error
		Boolean check if cURL is enabled in PHP
	*/
	private $curl_error = 0;
	
	/*
		Var: $curl_update
		Boolean that says whether we should check
		for updates.
	*/
	private $curl_update = 1;
	
	/*
		Var: $writable_folders
		An array of folders the installer checks to make 
		sure they can be written to.
	*/
	private $writeable_folders = array(
		'cache',
		'logs',
		'config',
        'archives',
		'db/backups',
		'db/migrations'
	);
	
	/*
		Var: $reverse_writable_folders
		An array of folders the installer can make unwriteable after 
		installation.
	*/
	private $reverse_writeable_folders = array(
		'config',
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
		
		$this->lang->load('application');
		$this->lang->load('install');
		
		// check if the app is installed
		$this->load->config('application');
		$site_title = config_item('site.title');
		if (!empty($site_title))
		{
			redirect('/');
		}

        
		$this->cURL_check();
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{ 
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
		$this->form_validation->set_rules('environment', lang('in_environment'), 'required|trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('hostname', lang('in_host'), 'required|trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('username', lang('bf_username'), 'required|trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('database', lang('in_database'), 'required|trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('db_prefix', lang('in_prefix'), 'trim|strip_tags|xss_clean');

		$this->startup_check();
		
		if ($this->form_validation->run() !== false)
		{ 
			// Write the database config files
			$this->load->helper('config_file');
			
			$dbname = strip_tags($this->input->post('database'));
			
			// get the chosen environment
			$environment = strip_tags($this->input->post('environment'));
			
			$data = array(
				'main'	=> array(
					'hostname'	=> strip_tags($this->input->post('hostname')),
					'username'	=> strip_tags($this->input->post('username')),
					'password'	=> strip_tags($this->input->post('password')),
					'database'	=> $dbname,
					'dbprefix'	=> strip_tags($this->input->post('db_prefix'))
				),
				$environment => array(
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
				$db = @mysql_connect(strip_tags($this->input->post('hostname')), strip_tags($this->input->post('username')), strip_tags($this->input->post('password')));
				
				if (!$db)
				{
					Template::set_message('Unable to connect to database: '. mysql_error(), 'error');	
				}
				else
				{
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
				}
			}
			else
			{
				Template::set_message('There was an error saving the settings. Please verify that your database and '.$environment.'/database config files are writeable.', 'attention');	
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
					Template::set_message('There was an error setting up your database: '. $this->errors, 'error');
				}
			}
		}
        
        // if $this->curl_error = 1, show warning on "account" page of setup
        Template::set('curl_error', $this->curl_error);
        
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
			@chmod(APPPATH .$folder, 0777);
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
			@chmod(APPPATH .$file, 0666);
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
		
		/*
			Copies generic file versions to their appropriate spots. 
			This provides a safe way to perform upgrades, as well
			as simplifying what will need to be modified when some
			sweeping changes are made. 
		*/
		if (!file_exists(APPPATH .'config/development/database.php') && is_writeable(APPPATH .'config/'))
		{
			// Database
			copy(APPPATH .'config/database.php', APPPATH .'config/development/database.php');
			copy(APPPATH .'config/database.php', APPPATH .'config/production/database.php');
			copy(APPPATH .'config/database.php', APPPATH .'config/testing/database.php');
		}
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
			'site.system_email'	=> $this->input->post('email'),
			'updates.do_check' => $this->curl_update,
			'updates.bleeding_edge' => $this->curl_update
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
			return false;
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
		
		// Create a unique encryption key
		$this->load->helper('string');
		$key = random_string('unique', 40);
		
		$config_array = array('encryption_key' => $key);
		
		// check the mod_rewrite setting
		$config_array['index_page'] = $this->rewrite_check() ? '' : 'index.php';
		
		write_config('config', $config_array);

		// Reverse Folders
		foreach ($this->reverse_writeable_folders as $folder)
		{
			@chmod(APPPATH .$folder, 0774);
		}

		// We made it to the end, so we're good to go!
		return true;
	}
	
	//--------------------------------------------------------------------
    
    /*
		Method: cURL_check()
		
		Verifies that cURL is enabled as a PHP extension. Sets 
	   'curl_update' to 0 if not.
	*/
	private function cURL_check() 
	{
        if (!function_exists('curl_version'))
        {
          $this->curl_error = 1;
          $this->curl_update = 0;
        }   
    }
	
	
	//--------------------------------------------------------------------
    
    /*
		Method: rewrite_check()
		
		Verifies that mod_rewrite is enabled as a PHP extension.
	*/
	private function rewrite_check()
	{
        if (!function_exists('rewrite_check'))
        {
			ob_start();
			phpinfo(INFO_MODULES);
			$contents = ob_get_clean();
			return strpos($contents, 'mod_rewrite') !== false;
        }
		
    }//end rewrite_check()
	
	
	//--------------------------------------------------------------------
}