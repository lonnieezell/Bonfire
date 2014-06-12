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

        // Make sure we're not installed already,
        // otherwise attackers could take advantage
        // and recreate the admin account.
        if ($this->installer_lib->is_installed())
        {
            show_error('This application has already been installed. Cannot install again.');
        }

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

            // Log anonymous statistics
            $this->statistics();
        }

        Template::render();
    }

    //--------------------------------------------------------------------

    /**
     * Sends anonymous stastics back to cibonfire.com. These are only used
     * for seeing environments we should be targeting for development.
     *
     * @return [type] [description]
     */
    public function statistics()
    {
        $this->load->library('installer_lib');
        $db = $this->load->database('default', true);

        $data = array(
            'bonfire_version'   => BONFIRE_VERSION,
            'php_version'       => phpversion(),
            'server'            => $this->input->server('SERVER_SOFTWARE'),
            'dbdriver'          => $db->dbdriver,
            'dbserver'          => @mysql_get_server_info($db->conn_id),
            'dbclient'          => preg_replace('/[^0-9\.]/','', mysql_get_client_info()),
            'curl'              => $this->installer_lib->cURL_enabled(),
            'server_hash'       => md5($this->input->server('SERVER_NAME').$this->input->server('SERVER_ADDR').$this->input->server('SERVER_SIGNATURE'))
        );

        $data_string = '';

        foreach($data as $key=>$value)
        {
            $data_string .= $key.'='.$value.'&';
        }
        rtrim($data_string, '&');

        $url = 'http://cibonfire.com/stats/collect';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $result = curl_exec($ch);

        curl_close($ch);

        //die(var_dump($result));
    }

    //--------------------------------------------------------------------

}