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
	
	public function mysql_available() 
	{
		return function_exists('mysql_connect');
	}
	
	//--------------------------------------------------------------------
	
	public function mysql_acceptable($type='server') 
	{
		// Server version
		if ($type == 'server')
		{
			
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
		Method: startup_check()

		Verifies that the folders and files needed are writeable. Sets
		'startup_errors' as a string in the template if not.
	*/
	public function startup_check()
	{
		$errors = '';
		$folder_errors = '';
		$file_errors = '';

		// Check Folders
		foreach ($this->writeable_folders as $folder)
		{
			$full_folder = FCPATH . '..' . $folder;

			@chmod($full_folder, 0777);
			if (!is_dir($full_folder) || !is_writeable($full_folder))
			{
				$folder_errors .= "<li>$folder</li>";
			}
		}

		if (!empty($folder_errors))
		{
			$errors = '<p>'.lang('in_writeable_directories_message').':</p><ul>' . $folder_errors .'</ul>';
		}

		// Check files
		foreach ($this->writeable_files as $file)
		{
			@chmod(FCPATH . '..' . $file, 0666);
			if (!is_writeable(FCPATH . '..' . $file))
			{
				$file_errors .= "<li>$file</li>";
			}
		}

		if (!empty($file_errors))
		{
			$errors .= '<p>'.lang('in_writeable_files_message').':</p><ul>' . $file_errors .'</ul>';
		}

		// Make it available to the template lib if there are errors
		if (!empty($errors))
		{
			$this->vdata['startup_errors'] = $errors;
		}

		unset($errors, $folder_errors, $file_errors);
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
		$data = new stdClass();
		
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
			$data->$folder = is_really_writable($start . $folder);
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
		$data = new stdClass();
		
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
			$data->$file = is_really_writable($start . $file);
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