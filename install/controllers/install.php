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
class Install extends CI_Controller {

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
		Var: $bonfire_app_path
		Boolean that says whether we should check
		for updates.
	*/
	private $bonfire_app_path = '../bonfire/application/';

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
			);
			
			$this->session->set_userdata('db_data', $data);
			if ($this->session->userdata('db_data'))
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

					redirect('account');
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
//					redirect('/login');
					Template::set_view('install/success');
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
			@chmod(FCPATH . $this->bonfire_app_path . $folder, 0777);
			if (!is_writeable(FCPATH . $this->bonfire_app_path . $folder))
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
			@chmod(FCPATH . $this->bonfire_app_path .$file, 0666);
			if (!is_writeable(FCPATH . $this->bonfire_app_path . $file))
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
	}
	
	//--------------------------------------------------------------------
	
	
	private function setup() 
	{
		
		// Save the DB details
		$data = $this->session->userdata("db_data");

		write_db_config($data);
	
		if (is_writeable(FCPATH . $this->bonfire_app_path . 'config/'))
		{
			// Database
			copy(FCPATH . $this->bonfire_app_path . 'config/database.php', FCPATH . $this->bonfire_app_path . 'config/development/database.php');
			copy(FCPATH . $this->bonfire_app_path . 'config/database.php', FCPATH . $this->bonfire_app_path . 'config/production/database.php');
			copy(FCPATH . $this->bonfire_app_path . 'config/database.php', FCPATH . $this->bonfire_app_path . 'config/testing/database.php');
		}

		$server   = $data['main']['hostname'];
		$username = $data['main']['username'];
		$password = $data['main']['password'];
		$database = $data['main']['database'];
		$dbprefix = $data['main']['dbprefix'];
		
		if( !$this->db = mysql_connect($server, $username, $password) )
		{
			return array('status' => FALSE,'message' => 'The installer could not connect to the MySQL server or the database, be sure to enter the correct information.');
		}
		
		// use the entered Database settings to connect before calling the Migrations
		$dsn = 'mysql://'.$username.':'.$password.'@'.$server.'/'.$database.'?dbprefix='.$dbprefix;
		$this->load->database($dsn);
		
		//
		// Now install the database tables.
		//
		$this->load->library('Migrations');
	
		if (!$this->migrations->install())
		{
			$this->errors = $this->migrations->error;
			return false;
		}

		
		//
		// Save the information to the settings table
		//
		
		$settings = array(
			'site.title'	=> $this->input->post('site_title'),
			'site.system_email'	=> $this->input->post('email'),
			'updates.do_check' => $this->curl_update,
			'updates.bleeding_edge' => $this->curl_update
		);
		
		foreach	($settings as $key => $value)
		{
			$setting_rec = array('name' => $key, 'module' => 'core', 'value' => $value);
			
			$this->db->where('name', $key);
			if ($this->db->update('settings', $setting_rec) == false)
			{
				$this->errors = 'There was an error inserting settings into the database';
				return false;
			}
		}

		//
		// Install the user in the users table so they can actually login.
		//
		$data = array(
			'role_id'	=> 1,
			'email'		=> $this->input->post('email'),
			'username'	=> $this->input->post('username'),
		);
		list($password, $salt) = $this->hash_password($this->input->post('password'));
		
		$data['password_hash'] = $password;
		$data['salt'] = $salt;
		
		if ($this->db->insert('users', $data) == false)
		{
			$this->errors = 'There was an error creating your account in the database';
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
			@chmod(FCPATH . $this->bonfire_app_path . $folder, 0774);
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
	
	/*
		Method: hash_password()
		
		Generates a new salt and password hash for the given password.
		
		Parameters:
			$old	- The password to hash.
			
		Returns:
			An array with the hashed password and new salt.
	*/
	public function hash_password($old='') 
	{
		if (!function_exists('do_hash'))
		{
			$this->load->helper('security');
		}
	
		$salt = $this->generate_salt();
		$pass = do_hash($salt . $old);
		
		return array($pass, $salt);
	}
	
	//--------------------------------------------------------------------
	
	private function generate_salt() 
	{
		if (!function_exists('random_string'))
		{
			$this->load->helper('string');
		}
		
		return random_string('alnum', 7);
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
}