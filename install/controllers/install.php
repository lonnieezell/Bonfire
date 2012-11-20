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

/**
 * Helps the developer with the initial install of the application for developement
 * purposes by...
 *
 * 1. Creating necessary config files so they won't be overwritten during upgrades.
 * 2. Sets up the database.
 * 3. Creates the initial database schema.
 * 4. Creates the initial admin user.
 *
 * @author Lonnie Ezell
 * @author Bonfire Dev Team
 * @package Bonfire\Installer\Controllers
 */
class Install extends CI_Controller {

	public static $locations;

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
		'application/config/development',
		'application/config/testing',
		'application/config/production',
		'application/archives',
		'application/archives/config',
		'application/db/backups',
		'application/db/migrations',
		'public/assets/cache'
	);

	/**
	 * An array of folders the installer can make unwriteable after
	 * installation.
	 *
	 * @access	private
	 * @var		array
	 */
	private $reverse_writeable_folders = array(
		'application/config',
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
	private $supported_dbs = array('mysqli', 'mysql');

	//--------------------------------------------------------------------

	/**
	 * Constructor method.
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();

		// Contains most of our installer utility functions
		$this->load->library('installer_lib');
		$this->load->helper('install');

		// Load form validation
		$this->load->library('form_validation');

		// Sets our language
		$this->set_language();

		// check if the app is installed
		//$this->load->config('application');
	}

	//--------------------------------------------------------------------

	/** 
	 *	Index Method
	 *
	 * @access	public
	 * @return	void
	 */
	 
	public function index()
	{
		if ($this->installer_lib->is_installed())
		{
			$this->load->view('install/installed');
		}
		else
		{
			$data = new stdClass();
			
			// PHP Version Check
			$data->php_min_version	= '5.2';
			$data->php_acceptable	= $this->installer_lib->php_acceptable($data->php_min_version);
			$data->php_version		= $this->installer_lib->php_version;
			
			// Curl Enabled? 
			$data->curl_enabled		= $this->installer_lib->cURL_enabled();
			
			// Files/Folders writeable?
			$data->folders			= $this->installer_lib->check_folders($this->writeable_folders);
			$data->files			= $this->installer_lib->check_files($this->writeable_files);
			
			// If everything is good... go ahead with install
			$data->step_passed = $data->php_acceptable == true && !in_array(false, $data->folders) && !in_array(false, $data->files);
		
			$this->load->view('install/req_check', $data);
		}
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Grabs the database setup information from the user and 
	 * attempts to install the database schema and migrations.
	 *
	 * @access	public
	 * @return	void
	 */
	public function database()
	{
		$data = new stdClass();
		
		if ($this->input->post('install_db'))
		{
			$this->form_validation->set_rules('environment', lang('in_environment'), 'required|trim');
			$this->form_validation->set_rules('driver', lang('in_driver'), 'required|trim');	
			$this->form_validation->set_rules('port', lang('in_port'), 'required|trim|numeric');	
			$this->form_validation->set_rules('hostname', lang('in_host'), 'required|trim');
			$this->form_validation->set_rules('username', lang('bf_username'), 'required|trim');
			$this->form_validation->set_rules('database', lang('in_database'), 'required|trim');
			$this->form_validation->set_rules('db_prefix', lang('in_prefix'), 'trim|callback_test_db_connection');
			
			if ($this->form_validation->run() !== false)
			{
				// Save the values to the session for later.
				$this->session->set_userdata( array(
					'environment'	=> $this->input->post('environment'),
					'driver'	=> $this->input->post('driver'),
					'hostname'	=> $this->input->post('hostname'),
					'port'		=> $this->input->post('port'),
					'username'	=> $this->input->post('username'),
					'password'	=> $this->input->post('password'),
					'database'	=> $this->input->post('database'),
					'db_prefix'	=> $this->input->post('db_prefix')
				));
				
				redirect('install/account');
			}
		}
		
		// Supported DB Drivers
		$data->drivers = $this->supported_dbs;

		$this->load->view('install/database', $data);
	}

	//--------------------------------------------------------------------

	public function test_db_connection() 
	{
		if (!$this->installer_lib->db_available())
		{
			$this->form_validation->set_message('test_db_connection', lang('in_db_not_available'));
			return false;
		}
		
		if (!$this->installer_lib->test_db_connection())
		{
			$this->form_validation->set_message('test_db_connection', lang('in_db_no_connect'));
			return false;
		}
		
		return true;
	}
	
	//--------------------------------------------------------------------

	public function account()
	{
		$view = 'install/account';

		if ($this->input->post('submit'))
		{
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', '');
			//$this->form_validation->CI =& $this;

			$this->form_validation->set_rules('site_title', lang('in_site_title'), 'required|trim|min_length[1]');
			$this->form_validation->set_rules('username', lang('in_username'), 'required|trim');
			$this->form_validation->set_rules('password', lang('in_password'), 'required|min_length[8]');
			$this->form_validation->set_rules('pass_confirm', lang('in_password_again'), 'required|matches[password]');
			$this->form_validation->set_rules('email', lang('in_email'), 'required|trim|valid_email');

			if ($this->form_validation->run() !== false)
			{
				if ($this->setup())
				{
					$this->vdata['message'] = message(lang('in_success_notification'), 'success');

					$success_data = array();
					// check if we are running in a sub directory
					$url_path = parse_url(base_url(), PHP_URL_PATH);
					$base_path = preg_replace('#/install/#', '', $url_path);
					if (!empty($base_path))
					{
						$this->vdata['rebase'] = $base_path.'/';
					}

					$view = 'install/success';
				}
				else
				{
					$this->vdata['message']= message(lang('in_db_setup_error').': '. $this->errors, 'error');
				}
			}
		}

        // if $this->curl_error = 1, show warning on "account" page of setup
        $this->vdata['curl_error'] = $this->curl_error;

		$this->load->view($view, $this->vdata);
	}

	//--------------------------------------------------------------------

	public function rename_folder() 
	{
		if ($_SERVER['REQUEST_METHOD'] != 'POST')
		{
			$this->security->csrf_show_error();
		}

		$folder = FCPATH;
	
		// This should always have the /install in it, but
		// better safe than sorry.
		if (strpos($folder, 'install') === false)
		{
			$folder .= '/install/';
		}
		
		$new_folder = preg_replace('{install/$}', 'install_bak', $folder);
	
		rename($folder, $new_folder);
		
		$url = preg_replace('{install/$}', '', base_url());
		$url = str_replace('http://', '', $url);
		$url = str_replace('//', '/', $url);
		$url = 'http://'. $url;
		
		redirect($url);
	}
	
	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------


	/*
		Copies generic file versions to their appropriate spots.
		This provides a safe way to perform upgrades, as well
		as simplifying what will need to be modified when some
		sweeping changes are made.
	*/
	private function setup()
	{

		// Save the DB details
		$data = $this->session->userdata("db_data");
		$environment = $data['environment'];
		unset($data['environment']);

		$this->load->helper('config_file');

		write_db_config($data);

		if (!file_exists(FCPATH . $this->app_path . 'config/development/database.php') && is_writeable(FCPATH . $this->app_path . 'config/'))
		{
			// Database
			copy(FCPATH . $this->app_path . 'config/database.php', FCPATH . $this->app_path . 'config/'.$environment.'/database.php');
		}

		$server   = $data['main']['hostname'];
		$username = $data['main']['username'];
		$password = $data['main']['password'];
		$database = $data['main']['database'];
		$dbprefix = $data['main']['dbprefix'];

		if( !$this->db = mysql_connect($server, $username, $password) )
		{
			return array('status' => FALSE, 'message' => lang('in_db_no_connect'));
		}

		// use the entered Database settings to connect before calling the Migrations
		$dsn = 'mysql://'.$username.':'.$password.'@'.$server.'/'.$database.'?dbprefix='.$dbprefix.'&db_debug=TRUE';
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

		// get the list of custom modules in the main application
		$module_list = $this->get_module_versions();

		if (is_array($module_list) && count($module_list))
		{
			foreach($module_list as $module_name => $module_detail)
			{
				// install the migrations for the custom modules
				if (!$this->migrations->install($module_name.'_'))
				{
					$this->errors = $this->migrations->error;
					return false;
				}
			}
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
				$this->errors = lang('in_db_settings_error');
				return false;
			}
		}

		// update the emailer serder_email
		$setting_rec = array('name' => 'sender_email', 'module' => 'email', 'value' => $this->input->post('email'));

		$this->db->where('name', 'sender_email');
		if ($this->db->update('settings', $setting_rec) == false)
		{
			$this->errors = lang('in_db_settings_error');
			return false;
		}

		//
		// Install the user in the users table so they can actually login.
		//
		$data = array(
			'role_id'	=> 1,
			'email'		=> $this->input->post('email'),
			'username'	=> $this->input->post('username'),
			'active'    => 1,
		);
		list($password, $salt) = $this->hash_password($this->input->post('password'));

		$data['password_hash'] = $password;
		$data['salt'] = $salt;

		if ($this->db->insert('users', $data) == false)
		{
			$this->errors = lang('in_db_account_error');
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
			@chmod(FCPATH . '../' . $folder, 0775);
		}

		// We made it to the end, so we're good to go!
		return true;
	}

	//--------------------------------------------------------------------
	
	/**
	 * Sets the language and loads the corresponding language files. 
	 * For now, this method is very simple, and needs to be expanded
	 * so that we can support multiple languages in the installation.
	 * 
	 * @access	private
	 * @author	lonnieezell
	 * @since	0.7-dev
	 * @return	void
	 */
	private function set_language() 
	{
		// Load our install language strings
		$this->lang->load('install');
	
		// Load some application-wide, generic, language labels.
		$this->lang->load('application');
	}
	
	//--------------------------------------------------------------------
}

/* get module locations from config settings or use the default module location and offset */
Install::$locations = array(
	APPPATH.'../bonfire/modules/' => '../modules/',
);
