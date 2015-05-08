<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT    The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * The controller which handles installation of Bonfire.
 *
 * @package Bonfire\Controllers\Install
 * @author  Bonfire Dev Team
 * @version 0.7.3
 * @link    http://cibonfire.com/docs/developer/installation
 */
class Install extends CI_Controller
{
    /** @var string The minimum PHP version required to use Bonfire. */
    protected $minVersionPhp = '5.3';

    /**
     * Initialize the installer.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Load the basics since Base_Controller is not used here.
        $this->lang->load('application');

        // Make sure the template library doesn't try to use sessions.
        $this->load->library('template');
        Template::setSessionUse(false);

        $this->load->library('assets');
        $this->load->library('events');

        $this->load->helper('application');

        // Disable hooks, since they may rely on an installed environment.
        get_instance()->hooks->enabled = false;

        // Load the Installer library.
        $this->lang->load('install');
        $this->load->library('installer_lib');
    }

    /**
     * Get some basic information about the environment before installation.
     *
     * @return void
     */
    public function index()
    {
        if ($this->installer_lib->is_installed()) {
            $this->load->library('users/auth');
            $this->load->library('settings/settings_lib');
        }

        $data = array();
        $data['curl_enabled']    = $this->installer_lib->cURL_enabled();
        $data['files']           = $this->installer_lib->check_files();
        $data['folders']         = $this->installer_lib->check_folders();
        $data['php_acceptable']  = $this->installer_lib->php_acceptable($this->minVersionPhp);
        $data['php_min_version'] = $this->minVersionPhp;
        $data['php_version']     = $this->installer_lib->php_version;

        Template::set($data);
        Template::render();
    }

    /**
     * Handle the basic installation of the migrations into the database, if available,
     * and display the current status.
     *
     * @return void
     */
    public function do_install()
    {
        // Make sure the application is not installed already, otherwise attackers
        // could take advantage and recreate the admin account.
        if ($this->installer_lib->is_installed()) {
            show_error('This application has already been installed. Cannot install again.');
        }

        // Does the database table even exist?
        if ($this->installer_lib->db_settings_exist === false) {
            show_error(lang('in_need_db_settings'));
        }

        // If setup fails, it will return an error message.
        if ($this->installer_lib->setup() === true) {
            define('BF_DID_INSTALL', true);
        }

        Template::render();
    }
}
