<?php defined('BASEPATH') || exit('No direct script access allowed');

class Install extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load basics since we are not relying on
        // Base_Controller here...
        $this->load->library('template');
        $this->load->library('assets');
        $this->load->library('events');
        $this->load->helper('application');
        $this->lang->load('application');

        get_instance()->hooks->enabled = false;
    }

    public function index()
    {
        $this->lang->load('install');
        $this->load->library('installer_lib');

            $data = array();

            // PHP Version Check
            $data['php_min_version']    = '5.3';
            $data['php_acceptable']     = $this->installer_lib->php_acceptable($data['php_min_version']);
            $data['php_version']        = $this->installer_lib->php_version;

            // Curl Enabled?
            $data['curl_enabled']       = $this->installer_lib->cURL_enabled();

            // Files/Folders writeable?
            $data['folders']            = $this->installer_lib->check_folders();
            $data['files']              = $this->installer_lib->check_files();

            Template::set($data);

        if ($this->installer_lib->is_installed()) {
            $this->load->library('users/auth');
            $this->load->library('settings/settings_lib');
        }

        Template::render();
    }

    /**
     * Handles the basic installation of the migrations into the database
     * if available, and displays the current status.
     *
     * @return void
     */
    public function do_install()
    {
        $this->load->library('installer_lib');
        $this->lang->load('install');

        // Make sure we're not installed already,
        // otherwise attackers could take advantage
        // and recreate the admin account.
        if ($this->installer_lib->is_installed()) {
            show_error('This application has already been installed. Cannot install again.');
        }

        // Does the database table even exist?
        if ($this->installer_lib->db_settings_exist === false) {
            show_error(lang('in_need_db_settings'));
        }

        // Run our migrations
        $this->load->library('migrations/migrations');

        if ($this->installer_lib->setup()) {
            define('BF_DID_INSTALL', true);
        }

        Template::render();
    }
}
