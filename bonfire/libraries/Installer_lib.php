<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Installer library.
 *
 * @package Bonfire\Libraries\Installer_lib
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/installation
 */
class Installer_lib
{
    /** @var boolean Indicates whether the default database settings were found. */
    public $db_exists = null;

    /** @var boolean Indicates whether the database settings were found. */
    public $db_settings_exist = null;

    /** @var string The version of the currently running PHP parser or extension. */
    public $php_version;

    /** @var CI The CodeIgniter controller instance. */
    private $ci;

    /** @var mixed Check whether cURL is enabled in PHP. */
    private $curl_error = 0;

    /** @var mixed Whether we should check for updates. */
    private $curl_update = 0;

    /** @var string[] Supported database engines. */
    private $supported_dbs = array('mysql', 'mysqli', 'bfmysqli');

    /** @var string[] Folders the installer checks for write access. */
    private $writable_folders;

    /** @var string[] Files the installer checks for write access. */
    private $writable_files;

    /**
     * The version of the MySQL Client.
     * @var string
     * @deprecated since 0.7.2.
     */
    public $mysql_client_version;

    /**
     * The version of the MySQL Server.
     * @var string
     * @deprecated since 0.7.2.
     */
    public $mysql_server_version;

    //--------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @param array $config unused?
     *
     * @return void
     */
    public function __construct($config = array())
    {
        if (! empty($config['writable_folders'])) {
            $this->writable_folders = $config['writable_folders'];
        }
        if (! empty($config['writable_files'])) {
            $this->writable_files = $config['writable_files'];
        }

        $this->ci =& get_instance();
        $this->curl_update = $this->cURL_enabled();
        $this->php_version = phpversion();
    }

    /**
     * Check an array of files/folders to see if they are writable and return the
     * results in a format usable in the requirements check step of the installation.
     *
     * Note that this only returns the data in the format expected by the Install
     * controller if called via check_folders() and check_files(). Otherwise, the
     * files and folders are intermingled unless they are passed as input.
     *
     * @param  array $filesAndFolders An array of paths to files/folders to check.
     *
     * @return array An associative array with the path as key and boolean value
     * indicating whether the path is writable.
     */
    public function checkWritable(array $filesAndFolders = array())
    {
        if (empty($filesAndFolders)) {
            $filesAndFolders = array_merge($this->writable_files, $this->writable_folders);
        }

        $this->ci->load->helper('file');

        $data = array();
        foreach ($filesAndFolders as $fileOrFolder) {
            // If it starts with 'public/', then that represents the web root.
            // Otherwise, try to locate it from the main folder. This does not use
            // DIRECTORY_SEPARATOR because the string is supplied by $this->writable_folders
            // or $this->writable_files.
            if (strpos($fileOrFolder, 'public/') === 0) {
                $realpath = FCPATH . preg_replace('{^public/}', '', $fileOrFolder);
            } else {
                // Because this is APPPATH, use DIRECTORY_SEPARATOR instead of '/'.
                $realpath = str_replace('application' . DIRECTORY_SEPARATOR, '', APPPATH) . $fileOrFolder;
            }

            $data[$fileOrFolder] = is_really_writable($realpath);
        }

        return $data;
    }

    /**
     * Determine whether the installed version of PHP is above $version.
     *
     * @param string $version The version to compare to the installed version.
     *
     * @return boolean True if the installed version is at or above $version, else
     * false.
     */
    public function php_acceptable($version = null)
    {
        return version_compare($this->php_version, $version, '>=');
    }

    /**
     * Tests whether the specified database type can be found.
     *
     * @return boolean
     */
    public function db_available()
    {
        $driver = $this->ci->input->post('driver');

        switch ($driver) {
            case 'mysql':
                return function_exists('mysql_connect');
            case 'bfmysqli':
            case 'mysqli':
                return class_exists('Mysqli');
            case 'cubrid':
                return function_exists('cubrid_connect');
            case 'mongodb': // deprecated
                return class_exists('Mongo');
            case 'mongoclient': // I don't believe we have a driver for this, yet
                return class_exists('MongoClient');
            case 'mssql':
                return function_exists('mssql_connect');
            case 'oci8':
                return function_exists('oci_connect');
            case 'odbc':
                return function_exists('odbc_connect');
            case 'pdo':
                return class_exists('PDO');
            case 'postgre':
                return function_exists('pg_connect');
            case 'sqlite':
                return function_exists('sqlite_open');
            case 'sqlsrv':
                return function_exists('sqlsrv_connect');
            default:
                return false;
        }
    }

    /**
     *  Attempts to connect to the database given the existing $_POST vars.
     *
     * @return boolean
     */
    public function test_db_connection()
    {
        if (! $this->db_available()) {
            return false;
        }

        $db_name  = $this->ci->input->post('database');
        $driver   = $this->ci->input->post('driver');
        $hostname = $this->ci->input->post('hostname');
        $password = $this->ci->input->post('password');
        $port     = $this->ci->input->post('port');
        $username = $this->ci->input->post('username');

        switch ($driver) {
            case 'mysql':
                return @mysql_connect("$hostname:$port", $username, $password);
            case 'bfmysqli':
            case 'mysqli':
                $mysqli = new mysqli($hostname, $username, $password, '', $port);
                if (! $mysqli->connect_error) {
                    return true;
                }
                return false;
            case 'cubrid':
                return @cubrid_connect($hostname, $port, $db_name, $username, $password);
            case 'mongodb': // deprecated
                $connect_string = $this->get_mongo_connection_string($hostname, $port, $username, $password, $db_name);
                try {
                    $mongo = new Mongo($connect_string);
                    return true;
                } catch (MongoConnectionException $e) {
                    show_error('Unable to connect to MongoDB.', 500);
                }
                return false;
                break;
            case 'mongoclient': // no driver support at this time
                $connect_string = $this->get_mongo_connection_string($hostname, $port, $username, $password, $db_name);
                try {
                    $mongo = new MongoClient($connect_string);
                    return true;
                } catch (MongoConnectionException $e) {
                    show_error('Unable to connect MongoClient.', 500);
                }
                return false;
                break;
            case 'mssql':
                return @mssql_connect("$hostname,$port", $username, $password);
            case 'oci8':
                $connect_string = $this->get_oracle_connection_string($hostname, $port);
                return @oci_connect($username, $password, $connect_string);
            case 'odbc':
                $connect_string = $this->get_odbc_connection_string($hostname);
                return @odbc_connect($connect_string, $username, $password);
            case 'pdo':
                $connect_string = $this->get_pdo_connection_string($hostname, $db_name);
                try {
                    $pdo = new PDO($connect_string, $username, $password);
                    return true;
                } catch (PDOException $e) {
                    show_error('Unable to connect using PDO.', 500);
                }
                return false;
                break;
            case 'postgre':
                $connect_string = $this->get_postgre_connection_string($hostname, $port, $username, $password, $db_name);
                return @pg_connect($connect_string);
            case 'sqlite':
                if (! $sqlite = @sqlite_open($db_name, FILE_WRITE_MODE, $error)) {
                    show_error($error, 500);
                    return false;
                }
                return $sqlite;
            case 'sqlsrv':
                $connection = $this->get_sqlsrv_connection($username, $password, $db_name);
                return sqlsrv_connect($hostname, $connection);
            default:
                return false;
        }
    }

    /**
     * Perform some basic checks to see if the user has already installed the application
     * and just hasn't moved the install folder...
     *
     * @return boolean True if the application is installed, else false.
     */
    public function is_installed()
    {
        // If 'config/installed.txt' exists, the app is installed
        if (is_file(APPPATH . 'config/installed.txt')) {
            return true;
        }

        // If the database config doesn't exist, the app is not installed
        if (defined('ENVIRONMENT') && is_file(APPPATH . 'config/' . ENVIRONMENT . '/database.php')) {
            require(APPPATH . 'config/' . ENVIRONMENT . '/database.php');
        } elseif (is_file(APPPATH . 'config/development/database.php')) {
            require(APPPATH . 'config/development/database.php');
        } elseif (is_file(APPPATH . 'config/database.php')) {
            require(APPPATH . 'config/database.php');
        } else {
            $this->db_settings_exist = false;
            return false;
        }

        // If $db['default'] doesn't exist, the app can't load the database
        if (! isset($db) || ! isset($db['default'])) {
            $this->db_settings_exist = false;
            return false;
        }
        $this->db_settings_exist = true;

        // Make sure the database name is specified
        if (empty($db['default']['database'])) {
            $this->db_exists = false;
            return false;
        }
        $this->db_exists = true;

        $this->ci->load->database($db['default']);

        // Does the users table exist?
        if (! $this->ci->db->table_exists('users')) {
            return false;
        }

        // Make sure at least one row exists in the users table.
        $query = $this->ci->db->get('users');
        if ($query->num_rows() == 0) {
            return false;
        }

        defined('BF_INSTALLED') || define('BF_INSTALLED', true);

        return true;
    }

    /**
     * Verify that cURL is enabled as a PHP extension.
     *
     * @return boolean True if cURL is enabled, else false.
     */
    public function cURL_enabled()
    {
        return (bool) function_exists('curl_init');
    }

    /**
     * Check an array of folders to see if they are writable and return results
     * in a format usable in the requirements check step.
     *
     * @param string[] $folders the folders to check.
     *
     * @return array
     */
    public function check_folders($folders = null)
    {
        if (is_null($folders)) {
            $folders = $this->writable_folders;
        }

        return $this->checkWritable($folders);
    }

    /**
     * Check an array of files to see if they are writable and return results
     * usable in the requirements check step.
     *
     * @param string[] $files The files to check.
     *
     * @return array
     */
    public function check_files($files = null)
    {
        if (is_null($files)) {
            $files = $this->writable_files;
        }

        return $this->checkWritable($files);
    }

    /**
     * Perform the actual installation of the database, create the config files,
     * and install the user account.
     *
     * @return string|boolean True on successful installation, else an error message.
     */
    public function setup()
    {
        // Install default info into the database.
        // This is done by running the app, core, and module-specific migrations

        // Load the Database before calling the Migrations
        $this->ci->load->database();

        // Install the database tables.
        $this->ci->load->library(
            'migrations/migrations',
            array('migrations_path' => BFPATH . 'migrations')
        );

        // Core Migrations - this is all that is needed for Bonfire install.
        if (! $this->ci->migrations->install()) {
            return $this->ci->migrations->getErrorMessage();
        }

        // Save the information to the settings table
        $settings = array(
            'site.title'        => 'My Bonfire',
            'site.system_email' => 'admin@mybonfire.com',
        );

        foreach ($settings as $key => $value) {
            $setting_rec = array(
                'name'   => $key,
                'module' => 'core',
                'value'  => $value,
            );

            $this->ci->db->where('name', $key);
            if ($this->ci->db->update('settings', $setting_rec) == false) {
                return lang('in_db_settings_error');
            }
        }

        // Update the emailer sender_email
        $setting_rec = array(
            'name'   => 'sender_email',
            'module' => 'email',
            'value'  => '',
        );

        $this->ci->db->where('name', 'sender_email');
        if ($this->ci->db->update('settings', $setting_rec) == false) {
            return lang('in_db_settings_error');
        }

        // Install the admin user in the users table so they can login.
        $data = array(
            'role_id'  => 1,
            'email'    => 'admin@mybonfire.com',
            'username' => 'admin',
            'active'   => 1,
        );

        // As of 0.7, using phpass for password encryption...
        require(BFPATH . 'modules/users/libraries/PasswordHash.php');

        $iterations = $this->ci->config->item('password_iterations');
        $hasher     = new PasswordHash($iterations, false);
        $password   = $hasher->HashPassword('password');

        $data['password_hash'] = $password;
        $data['created_on']    = date('Y-m-d H:i:s');
        $data['display_name']  = $data['username'];

        if ($this->ci->db->insert('users', $data) == false) {
            $this->errors = lang('in_db_account_error');
            return false;
        }

        // Create a unique encryption key
        $this->ci->load->helper('string');
        $key = random_string('md5', 40);

        $this->ci->load->helper('config_file');

        $config_array = array('encryption_key' => $key);
        write_config('config', $config_array, '', APPPATH);

        // Run custom migrations last. In particular this comes after the core
        // migrations, and after populating the user table.

        // Get the list of custom modules in the main application
        $module_list = $this->get_module_versions();
        if (is_array($module_list) && count($module_list)) {
            foreach ($module_list as $module_name => $module_detail) {
                // Install the migrations for the custom modules
                if (! $this->ci->migrations->install("{$module_name}_")) {
                    return $this->ci->migrations->getErrorMessage();
                }
            }
        }

        // Write a file to /public/install/installed.txt as a simple check
        // whether it's installed, so development doesn't require removing the
        // install folder.
        $this->ci->load->helper('file');

        $filename = APPPATH . 'config/installed.txt';
        $msg = 'Installed On: ' . date('r') . "\n";
        write_file($filename, $msg);

        $config_array = array(
            'bonfire.installed' => true,
        );
        write_config('application', $config_array, '', APPPATH);

        return true;
    }

    //--------------------------------------------------------------------------
    // !Private Methods
    //--------------------------------------------------------------------------

    /**
     * Get the versions of the modules.
     *
     * @return array The installed/latest versions of each module.
     */
    private function get_module_versions()
    {
        $modules = Modules::files(null, 'migrations');
        if ($modules === false) {
            return false;
        }

        // Sort modules by key (module directory name)
        ksort($modules);

        // Get the installed version of all of the modules (modules which have
        // not been installed will not be included)
        $installedVersions = $this->ci->migrations->getModuleVersions();
        $modVersions = array();

        // Add the migration data for each module
        foreach ($modules as $module => &$mod) {
            if (! array_key_exists('migrations', $mod)) {
                continue;
            }

            // Sort module migrations in reverse order
            arsort($mod['migrations']);

            // Add the installed version, latest version, and list of migrations
            $modVersions[$module] = array(
                'installed_version' => isset($installedVersions["{$module}_"]) ? $installedVersions["{$module}_"] : 0,
                'latest_version'    => intval(substr(current($mod['migrations']), 0, 3), 10),
                'migrations'        => $mod['migrations'],
            );
        }

        return $modVersions;
    }

    /**
     * Get the connection string for MongoDB
     *
     * @param string $hostname The server hostname
     * @param string $port     The server port on which MongoDB accepts connections
     * @param string $username User name
     * @param string $password Password
     * @param string $db_name  The name of the database to connect to
     *
     * @return string The connection string used to connect to the database
     */
    private function get_mongo_connection_string($hostname, $port = '', $username = '', $password = '', $db_name = '')
    {
        $connect_string = 'mongodb://';

        if (! empty($username) && ! empty($password)) {
            $connect_string .= "{$username}:{$password}@";
        }

        $connect_string .= $hostname;

        if (! empty($port)) {
            $connect_string .= ":{$port}";
        }

        if (! empty($db_name)) {
            $connect_string .= "/{$db_name}";
        }

        return trim($connect_string);
    }

    /**
     * Get the connection string for PostgreSQL
     *
     * @param string $hostname The server hostname
     * @param string $port     The server port on which PostgreSQL accepts connections
     * @param string $username User name
     * @param string $password Password
     * @param string $db_name  The name of the database to connect to
     *
     * @return string The connection string used to connect to the database
     */
    private function get_postgre_connection_string($hostname, $port = '', $username = '', $password = '', $db_name = '')
    {
        $connect_string = '';
        $components = array(
            'host'      => $hostname,
            'port'      => $port,
            'dbname'    => $db_name,
            'user'      => $username,
            'password'  => $password,
        );

        foreach ($components as $key => $val) {
            if (! empty($val)) {
                $connect_string .= " {$key}={$val}";
            }
        }

        return trim($connect_string);
    }

    /**
     * Get the connection string for Oracle (10g+ EasyConnect string)
     *
     * Note: 10g can also accept a service name (//$hostname:$port/$service_name)
     *      11g can also accept a server type and instance name (//$hostname:$port/$service_name:$server_type/$instance_name)
     * We don't currently support these options, though
     *
     * @param string $hostname The server hostname
     * @param string $port     The server port on which Oracle accepts connections
     *
     * @return string    The connection string used to connect to the database
     */
    private function get_oracle_connection_string($hostname, $port = '')
    {
        $connect_string = '//' . $hostname;
        if (! empty($port)) {
            $connect_string .= ":{$port}";
        }

        return $connect_string;
    }

    /**
     * Stub method to handle ODBC Connection strings.
     *
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

    /**
     * Stub method to handle PDO Connection strings.
     *
     * Currently, the user will have to enter the PDO driver as part of the hostname,
     * e.g. mysql:host
     *
     * @param string $hostname The driver and hostname (separated by a colon) or DSN
     * @param string $db_name  The name of the database, if not specified in $hostname
     *
     * @return string    The connection string used to connect to the database
     */
    private function get_pdo_connection_string($hostname, $db_name = '')
    {
        $connect_string = $hostname;
        if (! empty($db_name)) {
            $connect_string .= ";dbname={$db_name}";
        }

        return $connect_string;
    }

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
        $character_set = 0 === strcasecmp('utf8', $char_set) ? 'UTF-8' : $char_set;
        $connection = array(
            'UID'                   => empty($username) ? '' : $username,
            'PWD'                   => empty($password) ? '' : $password,
            'Database'              => $db_name,
            'ConnectionPooling'     => $pooling ? 1 : 0,
            'CharacterSet'          => $character_set,
            'ReturnDatesAsStrings'  => 1,
        );

        return $connection;
    }
}
