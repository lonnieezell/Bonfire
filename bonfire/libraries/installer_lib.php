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
	private	$curl_update = 0;

	/*
		Status flags to see how the install check
		went from outside controllers.
	 */
	public $db_settings_exist 	= NULL;
	public $db_exists 			= NULL;

	/**
	 * An array of folders the installer checks to make
	 * sure they can be written to.
	 *
	 * @access	private
	 * @var		array
	 */
	private $writeable_folders = array(
		'application/cache',
		'application/logs',
		'application/config',
		'application/archives',
		'application/db/backups',
		'public/assets/cache',
	);

	/**
	 * An array of files the installer checks to make
	 * sure they can be written to.
	 *
	 * @access	private
	 * @var 	array
	 */
	private $writeable_files = array(
		'application/config/application.php',
		'application/config/database.php',
	);

	/**
	 * Array of supported database engines.
	 *
	 * @access	private
	 * @var		array
	 */
	private $supported_dbs = array('mysql', 'mysqli');

	//--------------------------------------------------------------------

	public function __construct($config=array())
	{
		$this->ci =& get_instance();

		$this->curl_update = $this->cURL_enabled();
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

			case 'cubrid':
				return function_exists('cubrid_connect');
				break;
			case 'mongodb': // deprecated
				return class_exists('Mongo');
				break;
			case 'mongoclient': // I don't believe we have a driver for this, yet
				return class_exists('MongoClient');
				break;
			case 'mssql':
				return function_exists('mssql_connect');
				break;
			case 'oci8':
				return function_exists('oci_connect');
				break;
			case 'odbc':
				return function_exists('odbc_connect');
				break;
			case 'pdo':
				return class_exists('PDO');
				break;
			case 'postgre':
				return function_exists('pg_connect');
				break;
			case 'sqlite':
				return function_exists('sqlite_open');
				break;
			case 'sqlsrv':
				return function_exists('sqlsrv_connect');
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
		if ( ! $this->db_available())
		{
			return false;
		}

		$driver	= $this->ci->input->post('driver');

		$hostname	= $this->ci->input->post('hostname');
		$username	= $this->ci->input->post('username');
		$password	= $this->ci->input->post('password');
		$port		= $this->ci->input->post('port');
		$db_name	= $this->ci->input->post('database');

		switch ($driver)
		{
			case 'mysql':
				return @mysql_connect("$hostname:$port", $username, $password);
				break;
			case 'mysqli':
				$mysqli = new mysqli($hostname, $username, $password, '', $port);
				if ( ! $mysqli->connect_error)
				{
					return true;
				}
				return false;
				break;
			case 'cubrid':
				return @cubrid_connect($hostname, $port, $db_name, $username, $password);
				break;
			case 'mongodb': // deprecated
				$connect_string = $this->get_mongo_connection_string($hostname, $port, $username, $password, $db_name);
				try
				{
					$mongo = new Mongo($connect_string);
					return true;
				}
				catch (MongoConnectionException $e)
				{
					show_error('Unable to connect to MongoDB.', 500);
				}
				return false;
				break;
			case 'mongoclient': // no driver support at this time
				$connect_string = $this->get_mongo_connection_string($hostname, $port, $username, $password, $db_name);
				try
				{
					$mongo = new MongoClient($connect_string);
					return true;
				}
				catch (MongoConnectionException $e)
				{
					show_error('Unable to connect MongoClient.', 500);
				}
				return false;
				break;
			case 'mssql':
				return @mssql_connect("$hostname,$port", $username, $password);
				break;
			case 'oci8':
				$connect_string = $this->get_oracle_connection_string($hostname, $port);
				return @oci_connect($username, $password, $connect_string);
				break;
			case 'odbc':
				$connect_string = $this->get_odbc_connection_string($hostname);
				return @odbc_connect($connect_string, $username, $password);
				break;
			case 'pdo':
				$connect_string = $this->get_pdo_connection_string($hostname, $db_name);
				try
				{
					$pdo = new PDO($connect_string, $username, $password);
					return true;
				}
				catch (PDOException $e)
				{
					show_error('Unable to connect using PDO.', 500);
				}
				return false;
				break;
			case 'postgre':
				$connect_string = $this->get_postgre_connection_string($hostname, $port, $username, $password, $db_name);
				return @pg_connect($connect_string);
				break;
			case 'sqlite':
				if ( ! $sqlite = @sqlite_open($db_name, FILE_WRITE_MODE, $error))
				{
					show_error($error, 500);
					return false;
				}
				return $sqlite;
				break;
			case 'sqlsrv':
				$connection = $this->get_sqlsrv_connection($username, $password, $db_name);
				return sqlsrv_connect($hostname, $connection);
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
		if (is_file(APPPATH . 'config/installed.txt'))
		{
			return true;
		}

		// Does the database config exist?
		// If not, then we definitely haven't installed yet.
		if (!is_file(APPPATH . 'config/development/database.php'))
		{
			return false;
		}

		require(APPPATH . '/config/development/database.php');

		// If the $db['default'] doesn't exist then we can't
		// load our database.
		if (!isset($db) || !isset($db['default']))
		{
			$this->db_settings_exist = FALSE;
			return false;
		}

		$this->db_settings_exist = TRUE;

		// Just to be safe, also make sure that the database name
		// is actually specified.
		if (empty($db['default']['database']))
		{
			$this->db_exists = FALSE;
			return false;
		}

		$this->db_exists = TRUE;

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

		define('BF_INSTALLED', true);

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
	public function check_folders($folders=null)
	{
		if (is_null($folders))
		{
			$folders = $this->writeable_folders;
		}

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
				$realpath = FCPATH . preg_replace('{^public/}', '', $folder);
			}
			else
			{
				$realpath = str_replace('application/', '', APPPATH) . $folder;
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
	public function check_files($files=null)
	{
		if (is_null($files))
		{
			$files = $this->writeable_files;
		}

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
				$realpath = $this->FCPATH . preg_replace('{^public/}', '', $file);
			}
			else
			{
				$realpath = str_replace('application/', '', APPPATH) . $file;
			}

			$data[$file] = is_really_writable($realpath);
		}

		return $data;
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
		/*
			Install default info into our database.

			This happens by running the app, core and module-specific migrations.
		*/

		// use the entered Database settings to connect before calling the Migrations
		$this->ci->load->database();

		//
		// Now install the database tables.
		//
		$this->ci->load->library('Migrations', array('migrations_path' => BFPATH .'migrations'));

		// Core Migrations - this is all that is needed for Bonfire install.
		if (!$this->ci->migrations->install())
		{
			return $this->ci->migrations->error;
		}

		/*
			Save the information to the settings table
		*/

		$settings = array(
			'site.title'	=> 'My Bonfire',
			'site.system_email'	=> 'admin@mybonfire.com',
			'updates.do_check' => 0,
			'updates.bleeding_edge' => 0
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
		$setting_rec = array('name' => 'sender_email', 'module' => 'email', 'value' => '');

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
			'email'		=> 'admin@mybonfire.com',
			'username'	=> 'admin',
			'active'    => 1,
		);

		// As of 0.7, we've switched to using phpass for password encryption...
		require (BFPATH .'modules/users/libraries/PasswordHash.php' );

		$iterations	= $this->ci->config->item('password_iterations');
		$hasher = new PasswordHash($iterations, false);

		$password = $hasher->HashPassword('password');

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

		$this->ci->load->helper('config_file');
		write_config('config', $config_array, '', APPPATH);

		/*
			Run custom migrations last.  In particular this comes after
			the core migrations, and after we've populated the user table.
		 */

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

		// Write a file to /public/install/installed.txt as a simpler
		// check whether it's installed, so developing doesn't require
		// us to remove the install folder.
		$filename = APPPATH .'config/installed.txt';

		$msg = 'Installed On: '. date('r') ."\n";
		$this->ci->load->helper('file');
		write_file($filename, $msg);

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

	/**
	 * Get the connection string for MongoDB
	 *
	 * @param string $hostname The server hostname
	 * @param string $port     The server port on which MongoDB accepts connections
	 * @param string $username User name
	 * @param string $password Password
	 * @param string $db_name  The name of the database to connect to
	 *
	 * @return string    The connection string used to connect to the database
	 */
	private function get_mongo_connection_string($hostname, $port='', $username='', $password='', $db_name='')
	{
		$connect_string = 'mongodb://';

		if ( ! empty($username) && ! empty($password))
		{
			$connect_string .= $username . ':' . $password . '@';
		}
		$connect_string .=  $hostname;

		if ( ! empty($port))
		{
			$connect_string .= ':' . $port;
		}
		if ( ! empty($db_name))
		{
			$connect_string .= '/' . $db_name;
		}

		return trim($connect_string);
	}


	//--------------------------------------------------------------------

	/**
	 * Get the connection string for PostgreSQL
	 *
	 * @param string $hostname The server hostname
	 * @param string $port     The server port on which PostgreSQL accepts connections
	 * @param string $username User name
	 * @param string $password Password
	 * @param string $db_name  The name of the database to connect to
	 *
	 * @return string    The connection string used to connect to the database
	 */
	private function get_postgre_connection_string($hostname, $port='', $username='', $password='', $db_name='')
	{
		$connect_string = '';
		$components = array(
			'host'		=> $hostname,
			'port'		=> $port,
			'dbname'	=> $db_name,
			'user'		=> $username,
			'password'	=> $password,
		);

		foreach ($components as $key => $val)
		{
			if ( ! empty($val))
			{
				$connect_string .= ' ' . $key . '=' . $val;
			}
		}

		return trim($connect_string);
	}


	//--------------------------------------------------------------------

	/**
	 * Get the connection string for Oracle (10g+ EasyConnect string)
	 * Note: 10g can also accept a service name (//$hostname:$port/$service_name)
	 *		11g can also accept a server type and instance name (//$hostname:$port/$service_name:$server_type/$instance_name)
	 * We don't currently support these options, though
	 *
	 * @param string $hostname The server hostname
	 * @param string $port     The server port on which Oracle accepts connections
	 *
	 * @return string    The connection string used to connect to the database
	 */
	private function get_oracle_connection_string($hostname, $port='')
	{
		$connect_string = '//' . $hostname;
		if ( ! empty($port))
		{
			$connect_string .= ':' . $port;
		}

		return $connect_string;
	}


	//--------------------------------------------------------------------

	/**
	 * Stub method to handle ODBC Connection strings.
	 * Currently, the user will have to either setup a DSN connection and input the DSN name
	 * or input the DSN-less connection string into the hostname field
	 *
	 * @param string $hostname The DSN name or DSN-less connection string
	 *
	 * @return string    The connection string used to connect to the database
	 */
	private function get_odbc_connection_string($hostname)
	{
		return $hostname;
	}


	//--------------------------------------------------------------------

	/**
	 * Stub method to handle PDO Connection strings.
	 * Currently, the user will have to enter the PDO driver as part of the hostname,
	 * e.g. mysql:host
	 *
	 * @param string $hostname The driver and hostname (separated by a colon) or DSN
	 * @param string $db_name  The name of the database, if not specified in $hostname
	 *
	 * @return string    The connection string used to connect to the database
	 */
	private function get_pdo_connection_string($hostname, $db_name='')
	{
		$connect_string = $hostname;
		if ( ! empty($db_name))
		{
			$connect_string .= ';dbname=' . $db_name;
		}

		return $connect_string;
	}


	//--------------------------------------------------------------------

	/**
	 * Stub method to handle SQLSrv connection strings
	 *
	 * @param string $username User name
	 * @param string $password Password
	 * @param string $db_name  The name of the database
	 * @param string $char_set Character set
	 * @param bool $pooling  Connection pooling
	 *
	 * @return array    The connection settings array used to connect to the database
	 */
	private function get_sqlsrv_connection($username, $password, $db_name, $char_set = 'UTF-8', $pooling = false)
	{
		$character_set = (0 === strcasecmp('utf8', $char_set)) ? 'UTF-8' : $char_set;
		$connection = array(
			'UID'					=> empty($username) ? '' : $username,
			'PWD'					=> empty($password) ? '' : $password,
			'Database'				=> $db_name,
			'ConnectionPooling'		=> $pooling ? 1 : 0,
			'CharacterSet'			=> $character_set,
			'ReturnDatesAsStrings'	=> 1,
		);

		return $connection;
	}


	//--------------------------------------------------------------------

}