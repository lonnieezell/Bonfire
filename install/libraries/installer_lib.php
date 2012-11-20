<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Installer_lib {
	
	private $ci;
	public 	$php_version;
	public	$mysql_server_version;
	public	$mysql_client_version;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		$this->ci =& get_instance();
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
		// Does the database config exist? 
		// If not, then we definitely haven't installed yet.
		if (!file_exists('../bonfire/application/config/development/database.php'))
		{
			return false;
		}
		
		require('../bonfire/application/config/development/database.php');
		
		// If the $db['default'] doesn't exist then we can't
		// load our database.
		if (!isset($db) || !isset($db['default']))
		{
			return false;
		}

		$this->load->database($db['default']);
		
		// Does the users table exist?
		if (!$this->db->table_exists('users'))
		{
			return false;
		}
		
		// Make sure at least one row exists in the users table.
		$query = $this->db->get('users');
		
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
			// If it starts with a '/', then we assume it's
			// in the web root. Otherwise, we try to locate
			// it from the main folder.
			$start = strpos($folder, '/') === 0 ? FCPATH : str_replace('application/', '', BFPATH);
			
			// Try to set it to writeable if possible
			@chmod($start . $folder, 0777);
			$data[$folder] = is_really_writable($start . $folder);
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
			// If it starts with a '/', then we assume it's
			// in the web root. Otherwise, we try to locate
			// it from the main folder.
			$start = strpos($file, '/') === 0 ? FCPATH : str_replace('application/', '', BFPATH);
			
			// Try to set it to writeable if possible
			@chmod($start . $file, 0666);
			$data[$file] = is_really_writable($start . $file);
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
			$this->load->helper('security');
		}

		$salt = $this->generate_salt();
		$pass = do_hash($salt . $old);

		return array($pass, $salt);
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
				'installed_version'	=> $this->migrations->get_schema_version($module .'_'),
				'latest_version'	=> $this->migrations->get_latest_version($module .'_'),
				'migrations'		=> $migrations['migrations']
			);
		}

		return $mod_versions;
	}


	//--------------------------------------------------------------------
}