<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Install extends CI_Controller {

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
    }

    //--------------------------------------------------------------------

    public function index()
    {
        $this->lang->load('install');
        $this->load->library('installer_lib');

        if (!$this->installer_lib->is_installed())
        {
            //$this->do_install();

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
            //Template::set('content', $this->load->view('home/install_status', $data, true));
        }
        else
        {
            $this->load->library('users/auth');
            $this->load->library('settings/settings_lib');
        }

        Template::render();
    }

    //--------------------------------------------------------------------

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

        // Does the database table even exist?
        if ($this->installer_lib->db_settings_exist === FALSE)
        {
            show_error( lang('in_need_db_settings') );
        }

        // Run our migrations
        $this->load->library('migrations/migrations');

        if ($this->installer_lib->setup())
        {
            define('BF_DID_INSTALL', true);
        }

        Template::render();
    }

    //--------------------------------------------------------------------
}