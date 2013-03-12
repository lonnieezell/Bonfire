<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Installer_lib {
	
	private $ci;

	public 	$php_version;
	public	$mysql_server_version;
	public	$mysql_client_version;

	/*
		Var: $curl_error
		Boolean check if cURL is enabled in PHP
	*/
	private	$curl_error = 0;

	/*
		Var: $curl_update
		Boolean that says whether we should check
		for updates.
	*/
	private	$curl_update = 1;

	/* Paths for the real Bonfire installation */
	public	$FCPATH;
	public	$APPPATH;
	public	$BFPATH;

	public	$reverse_writeable_folders = array();

	//--------------------------------------------------------------------
	
	public function __construct($config=array()) 
	{
		$this->ci =& get_instance();
		
		$this->curl_update = $this->cURL_enabled();
		
		$this->FCPATH = realpath(FCPATH . '..') . '/';
		$this->APPPATH = INSTALLPATH . 'application/';
		$this->BFPATH = INSTALLPATH . 'bonfire/';

		if (array_key_exists('reverse_writeable_folders', $config))
		{
			$this->reverse_writeable_folders = $config['reverse_writeable_folders'];
		}
	}
	
	//--------------------------------------------------------------------
	
	public function php_acceptable($version=null) 
	{
		$this->php_version = phpversion();
		
		return ( version_compare($this->php_version, $version, '>=') ) ? true : false;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	Tests whether the specified database type can even be found.
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function db_available() 
	{
		$driver = $this->ci->input->post('driver');
		
		switch ($driver)
		{
			case 'mysql':
				return function_exists('mysql_connect');
				break;
			case 'mysqli':
				return class_exists('Mysqli');
				break;
			default: 
				return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	Attempts to connect to the database given the existing $_POST vars.
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function test_db_connection() 
	{
		$driver	= $this->ci->input->post('driver');
		
		$hostname	= $this->ci->input->post('hostname');
		$username	= $this->ci->input->post('username');
		$password	= $this->ci->input->post('password');
		$port		= $this->ci->input->post('port');
		
		switch ($driver)
		{
			case 'mysql':
				return $this->db_available() && @mysql_connect("$hostname:$port", $username, $password);
				break;
			case 'mysqli':
				$mysqli = new mysqli($hostname, $username, $password, '', $port);

				if ($mysqli->connect_error)
				{
					return false;
				}
				else
				{
					return true;
				}
				break;
			default: 
				return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: is_installed()
		
		Performs some basic checks to see if maybe, just maybe, the 
		user has already installed the application and just hasn't 
		moved the install folder....
	*/
	public function is_installed() 
	{
		// First check - Does a 'install/installed.txt' file exist? If so, 
		// then we've likely already installed. 
		if (is_file(FCPATH . 'installed.txt'))
		{
			return true;
		}

		// Does the database config exist? 
		// If not, then we definitely haven't installed yet.
		if (!is_file($this->APPPATH . 'config/development/database.php'))
		{
			return false;
		}
		
		require($this->APPPATH . '/config/development/database.php');
		
		// If the $db['default'] doesn't exist then we can't
		// load our database.
		if (!isset($db) || !isset($db['default']))
		{
			return false;
		}
		
		$this->ci->load->database($db['default']);
		
		// Does the users table exist?
		if (!$this->ci->db->table_exists('users'))
		{
			return false;
		}
		
		// Make sure at least one row exists in the users table.
		$query = $this->ci->db->get('users');
		
		if ($query->num_rows() == 0)
		{
			return false;
		}
		
		return true;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: cURL_check()

		Verifies that cURL is enabled as a PHP extension. Sets
	   'curl_update' to 0 if not.
	*/
	public function cURL_enabled()
	{
       return (bool) function_exists('curl_init');
    }


	//--------------------------------------------------------------------
	
	/**
	 * Checks an array of folders to see if they are writeable and
	 * returns results usable in the requirements check step.
	 *
	 * @access	public
	 * @return	array
	 */
	public function check_folders($folders) 
	{
		$data = array();
		
		// Load the file helper
		$this->ci->load->helper('file');
	
		foreach ($folders as $folder)
		{
			// If it starts with 'public/', then that represents
			// the web root. Otherwise, we try to locate it
			// from the main folder.
			if (strpos($folder, 'public/') === 0)
			{
				$realpath = preg_replace('{^public/}', $this->FCPATH, $folder);
			}
			else
			{
				$realpath = INSTALLPATH . $folder;
			}
			
			$data[$folder] = is_really_writable($realpath);
		}
		
		return $data;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Checks an array of files to see if they are writeable and
	 * returns results usable in the requirements check step.
	 *
	 * @access	public
	 * @return	array
	 */
	public function check_files($files) 
	{
		$data = array();
		
		// Load the file helper
		$this->ci->load->helper('file');
	
		foreach ($files as $file)
		{
			// If it starts with 'public/', then that represents
			// the web root. Otherwise, we try to locate it
			// from the main folder.
			if (strpos($file, 'public/') === 0)
			{
				$realpath = preg_replace('{^public/}', $this->FCPATH, $file);
			}
			else
			{
				$realpath = INSTALLPATH . $file;
			}
			
			$data[$file] = is_really_writable($realpath);
		}
		
		return $data;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: rewrite_check()

		Verifies that mod_rewrite is enabled as a PHP extension.
	*/
	public function rewrite_check()
	{
        if (!function_exists('rewrite_check'))
        {
			ob_start();
			phpinfo(INFO_MODULES);
			$contents = ob_get_clean();
			return strpos($contents, 'mod_rewrite') !== false;
        }

    }
    
    //--------------------------------------------------------------------
    
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
			$this->ci->load->helper('security');
		}

		$salt = $this->generate_salt();
		$pass = do_hash($salt . $old);

		return array($pass, $salt);
	}

	//--------------------------------------------------------------------
	
	/**
	 * Performs the actuall installation of the database, creates our
	 * config files, and installs the user account.
	 *
	 * @access	public
	 * @return	string or boolean(TRUE)
	 */
	public function setup()
	{
		$environment = $this->ci->session->userdata('environment');

		/*
			1. Save our database configuration
		*/
		$hostname	= $this->ci->session->userdata('hostname');
		$db_user	= $this->ci->session->userdata('username');
		$db_pass	= $this->ci->session->userdata('password');
		$database	= $this->ci->session->userdata('database');
		$driver		= $this->ci->session->userdata('driver');
		$prefix		= $this->ci->session->userdata('db_prefix');
		$port		= $this->ci->session->userdata('port');
		
		$db_debug	= $environment == 'production' ? FALSE : TRUE;

		$this->ci->load->helper('config_file');
		
		$db_data = array(
			'hostname'	=> $hostname,
			'port'		=> $port,
			'username'	=> $db_user,
			'password'	=> $db_pass,
			'database'	=> $database,
			'dbdriver'	=> $driver,
			'dbprefix'	=> $prefix,
			'db_debug'	=> $db_debug
		);

		// Write main database config file.
		if (write_db_config( array('main' => $db_data), $this->APPPATH ) === false)
		{
			$str = lang('in_db_config_error');
			return str_replace('{file}', 'config/database.php', $str);
		}

		// Write environment database config file.
		if (copy($this->APPPATH . '/config/database.php', $this->APPPATH . "/config/" . $environment ."/database.php") === false)
		{
			$str = lang('in_db_config_error');
			return str_replace('{file}', "config/$environment/database.php", $str);
		}
		

		/*
			2. Install default info into our database.
			
			This happens by running the app, core and module-specific migrations.
		*/
		
		// use the entered Database settings to connect before calling the Migrations
		$this->ci->load->database($db_data);

		//
		// Now install the database tables.
		//
		$this->ci->load->library('Migrations', array('migrations_path' => $this->BFPATH .'migrations'));

		if (!$this->ci->migrations->install())
		{ 
			return $this->ci->migrations->error;
		}

		// get the list of custom modules in the main application
		$module_list = $this->get_module_versions();

		if (is_array($module_list) && count($module_list))
		{
			foreach($module_list as $module_name => $module_detail)
			{
				// install the migrations for the custom modules
				if (!$this->ci->migrations->install($module_name.'_'))
				{
					return $this->ci->migrations->error;
				}
			}
		}

		/*
			Save the information to the settings table
		*/

		$settings = array(
			'site.title'	=> $this->ci->session->userdata('site_title'),
			'site.system_email'	=> $this->ci->session->userdata('user_email'),
			'updates.do_check' => $this->curl_update,
			'updates.bleeding_edge' => $this->curl_update
		);

		foreach	($settings as $key => $value)
		{
			$setting_rec = array('name' => $key, 'module' => 'core', 'value' => $value);

			$this->ci->db->where('name', $key);
			if ($this->ci->db->update('settings', $setting_rec) == false)
			{
				return lang('in_db_settings_error');
			}
		}

		// update the emailer sender_email
		$setting_rec = array('name' => 'sender_email', 'module' => 'email', 'value' => $this->ci->session->userdata('user_email'));

		$this->ci->db->where('name', 'sender_email');
		if ($this->ci->db->update('settings', $setting_rec) == false)
		{
			return lang('in_db_settings_error');
		}

		//
		// Install the user in the users table so they can actually login.
		//
		$data = array(
			'role_id'	=> 1,
			'email'		=> $this->ci->session->userdata('user_email'),
			'username'	=> $this->ci->session->userdata('user_username'),
			'active'    => 1,
		);
		
		// As of 0.7, we've switched to using phpass for password encryption...
		require ($this->BFPATH .'modules/users/libraries/PasswordHash.php' );

		$iterations	= $this->ci->config->item('password_iterations');
		$hasher = new PasswordHash($iterations, false);
		
		$password = $hasher->HashPassword($this->ci->session->userdata('user_password'));

		$data['password_hash'] = $password;
		$data['password_iterations'] = $iterations;
		$data['created_on'] = date('Y-m-d H:i:s');
		$data['display_name'] = $data['username'];

		if ($this->ci->db->insert('users', $data) == false)
		{
			$this->errors = lang('in_db_account_error');
			return false;
		}

		// Create a unique encryption key
		$this->ci->load->helper('string');
		$key = random_string('unique', 40);

		$config_array = array('encryption_key' => $key);

		// check the mod_rewrite setting
		$config_array['index_page'] = $this->rewrite_check() ? '' : 'index.php';

		write_config('config', $config_array, '', $this->APPPATH);

		// Reverse Folders
		foreach ($this->reverse_writeable_folders as $folder)
		{
			@chmod(INSTALLPATH . $folder, 0775);
		}

		// We made it to the end, so we're good to go!
		return true;
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !Private Methods
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

	private function get_module_versions()
	{
		$mod_versions = array();

		$modules = module_files(null, 'migrations');

		if ($modules === false)
		{
			return false;
		}

		foreach ($modules as $module => $migrations)
		{
			$mod_versions[$module] = array(
				'installed_version'	=> $this->ci->migrations->get_schema_version($module .'_'),
				'latest_version'	=> $this->ci->migrations->get_latest_version($module .'_'),
				'migrations'		=> $migrations['migrations']
			);
		}

		return $mod_versions;
	}


	//--------------------------------------------------------------------
}