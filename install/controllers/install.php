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
		'public/assets/cache',
		'public/install'
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

		// Module locations copied from application/config/config.php.
		// TODO check if '../..' needs changing in the values
		Install::$locations = array(
			realpath($this->installer_lib->APPPATH .'../bonfire/modules') .'/' => '../../bonfire/modules/',
			realpath($this->installer_lib->APPPATH) . '/modules/' => '../../application/modules/'
		);

		// Load form validation
		$this->load->library('form_validation');

		// Sets our language
		$this->set_language();
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
			$data->php_min_version	= '5.3';
			$data->php_acceptable	= $this->installer_lib->php_acceptable($data->php_min_version);
			$data->php_version		= $this->installer_lib->php_version;

			// Curl Enabled?
			$data->curl_enabled		= $this->installer_lib->cURL_enabled();

			// Files/Folders writeable?
			$data->folders			= $this->installer_lib->check_folders($this->writeable_folders);
			$data->files			= $this->installer_lib->check_files($this->writeable_files);

			// If everything is good... go ahead with install
			$data->step_passed = $data->php_acceptable == true && !in_array(false, $data->folders) && !in_array(false, $data->files);
			$this->session->set_userdata('step1_done', true);

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
			$this->form_validation->set_rules('driver', lang('in_db_driver'), 'required|trim');
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

				$this->session->set_userdata('step2_done', true);
				redirect('install/account');
			}
		}

		// Supported DB Drivers
		$data->drivers = $this->supported_dbs;

		$this->load->view('install/database', $data);
	}

	//--------------------------------------------------------------------

	/**
	 *	Test our database connection. This is a callback used with form_validation.
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function test_db_connection()
	{
		// Database drivers avialable?
		if (!$this->installer_lib->db_available())
		{
			$this->form_validation->set_message('test_db_connection', lang('in_db_not_available'));
			return false;
		}

		// Can we connect to it?
		if (!$this->installer_lib->test_db_connection())
		{
			$this->form_validation->set_message('test_db_connection', lang('in_db_no_connect'));
			return false;
		}

		return true;
	}

	//--------------------------------------------------------------------

	/**
	 *	Retrieves the user's account information and validates it.
	 *
	 * @access	public
	 * @return	void
	 */
	public function account()
	{
		if ($this->input->post('submit'))
		{
			$this->form_validation->set_rules('site_title', lang('in_site_title'), 'required|trim|min_length[1]');
			$this->form_validation->set_rules('username', lang('in_username'), 'required|trim');
			$this->form_validation->set_rules('password', lang('in_password'), 'required|min_length[8]');
			$this->form_validation->set_rules('pass_confirm', lang('in_password_again'), 'required|matches[password]');
			$this->form_validation->set_rules('email', lang('in_email'), 'required|trim|valid_email');

			if ($this->form_validation->run() !== false)
			{
				// Store the user information in the session so
				// we can access it during the next step.
				$this->session->set_userdata( array(
					'site_title'	=> $this->input->post('site_title'),
					'user_username'	=> $this->input->post('username'),
					'user_password'	=> $this->input->post('password'),
					'user_email'	=> $this->input->post('email')
				));

				$this->session->set_userdata('step3_done', true);

				// Redirect to the actual installer
				redirect('install/do_install');
			}
		}

		$this->load->view('install/account');
	}

	//--------------------------------------------------------------------

	/**
	 *	Do the actual installation...
	 */
	public function do_install()
	{
		$data = new stdClass();

		$ready = true;

		// database info in session?
		if (!$this->session->userdata('hostname'))
		{
			$ready = false;
			$data->reason = lang('in_db_no_session');
		}

		// account info in session?
		if (!$this->session->userdata('user_username'))
		{
			$ready = false;
			$data->reason = lang('in_user_no_session');
		}

		// Do the install!
		if ($ready)
		{
			$setup = $this->installer_lib->setup();
			if ($setup === true)
			{
				$this->session->set_userdata('step3_done', true);
				$this->session->set_userdata('installed', true);
				redirect('install/complete');
			}
			else
			{
				$data->reason = $setup;
			}
		}

		$this->load->view('install/failed', $data);
	}

	//--------------------------------------------------------------------

	/**
	 *	We're done!
	 */
	public function complete()
	{
		if (!$this->session->userdata('installed'))
		{
			redirect('/install');
		}

		$this->load->view('install/complete');
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

		$url = installed_url();
		redirect($url);
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
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
