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
 * @filesource
 */

/**
 * Sysinfo Module
 *
 * Displays various system information to the user.
 *
 * @package Bonfire\Modules\Sysinfo\Controllers\Developer
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs
 */
class Developer extends Admin_Controller
{
    /**
     * Load required classes.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->auth->restrict('Bonfire.Sysinfo.View');

        $this->lang->load('sysinfo');

        Template::set('toolbar_title', lang('sysinfo_system_info'));
        Template::set_block('sub_nav', 'developer/_sub_nav');
    }

    /**
     * Display the system information, including Bonfire and PHP versions.
     *
     * @return void
     */
    public function index()
    {
        // Date helper is used for user_time().
        $this->load->helper('date');
        $dateFormat = 'g:i a';
        $data = array(
            'sysinfo_version_bf'   => BONFIRE_VERSION,
            'sysinfo_version_ci'   => CI_VERSION,
            'sysinfo_version_php'  => phpversion(),
            'sysinfo_time_server'  => date($dateFormat),
            'sysinfo_time_local'   => user_time(time(), false, $dateFormat),
            'sysinfo_db_name'      => $this->db->database,
            'sysinfo_db_server'    => $this->db->platform(),
            'sysinfo_db_version'   => $this->db->version(),
            'sysinfo_db_charset'   => $this->db->char_set,
            'sysinfo_db_collation' => $this->db->dbcollat,
            'sysinfo_basepath'     => BASEPATH,
            'sysinfo_apppath'      => APPPATH,
            'sysinfo_site_url'     => site_url(),
            'sysinfo_environment'  => ENVIRONMENT,
        );

        $this->config->load('installer_lib', true, true);
        $installerConfig = $this->config->item('installer_lib');
        if (is_array($installerConfig)) {
            foreach ($installerConfig as $key => $value) {
                if (is_array($value)) {
                    $data["sysinfo_{$key}"] = '';
                    $writable = $this->checkWritable($value);
                    foreach ($value as $path) {
                        $data["sysinfo_{$key}_" . str_replace(array('/', '\\', '.'), '_', $path)] = lang(
                            $writable[$path] ? 'sysinfo_writable' : 'sysinfo_not_writable'
                        );
                    }
                } else {
                    $data["sysinfo_{$key}"] = $value;
                }
            }
        }

        Template::set('info', $data);
        Template::render();
    }

    /**
     * Display the list of modules in the Bonfire installation.
     *
     * @return void
     */
    public function modules()
    {
        $modules = Modules::list_modules();
        $configs = array();
        $unsetReplacement = '---';

        foreach ($modules as $module) {
            $configs[$module] = Modules::config($module);

            if (! isset($configs[$module]['name'])) {
                $configs[$module]['name'] = ucwords($module);
            } elseif (strpos($configs[$module]['name'], 'lang:') === 0) {
                $configs[$module]['name'] = lang(str_replace('lang:', '', $configs[$module]['name']));
            }
            $configs[$module]['name'] = ucwords(str_replace('_', '', $configs[$module]['name']));

            $configs[$module]['version'] = isset($configs[$module]['version']) ? $configs[$module]['version'] : $unsetReplacement;
            $configs[$module]['description'] = isset($configs[$module]['description']) ? $configs[$module]['description'] : $unsetReplacement;
            $configs[$module]['author'] = isset($configs[$module]['author']) ? $configs[$module]['author'] : $unsetReplacement;
        }

        // Sort the list of modules by directory name.
        ksort($configs);

        Template::set('modules', $configs);
        Template::render();
    }

    /**
     * Display the PHP info settings to the user
     *
     * @return void
     */
    public function php_info()
    {
        ob_start();
        phpinfo();
        $buffer = ob_get_contents();
        ob_end_clean();

        $tableClass = 'table table-striped table-condensed';

        // Note: this matches the opening div, but leaves the closing tag intact.
        // Later, an opening div is inserted into the content which will need the
        // closing tag.
        $output = (preg_match("/<body><div.*?".">(.*)<\/body>/is", $buffer, $match)) ? $match['1'] : $buffer;
        $output = preg_replace("/<a href=\"http:\/\/www.php.net\/\">.*?<\/a>/", "", $output);
        $output = preg_replace("/<a href=\"http:\/\/www.zend.com\/\">.*?<\/a>/", "", $output);
        $output = preg_replace("/<h2 align=\"center\">PHP License<\/h2>.*?<\/table>/si", "", $output);
        $output = preg_replace("/<h2>PHP License.*?<\/table>/is", "", $output);
        $output = preg_replace("/<table(.*?)bgcolor=\".*?\">/", "<table>", $output);
        $output = preg_replace("/<table(.*?)>/", "<table class=\"{$tableClass}\">", $output);
        $output = preg_replace("/<a.*?<\/a>/", "", $output);
        $output = preg_replace("/<th(.*?)>/", "<th\\1>", $output);
        $output = preg_replace("/<hr.*?>/", "<br />", $output);
        $output = preg_replace("/<tr(.*?).*?".">/", "<tr\\1>", $output);
        $output = preg_replace('/<h(1|2)\s*(class="p")?/i', "\n<h\\1", $output);

        $output = preg_replace(
            "/<table class=\"{$tableClass}\">(\s+)<tr><td>(\s+)<h1>PHP Version(.*?)<\/h1>(\s+)<\/td><\/tr>(\s+)<\/table><br \/>/is",
            "<div class='tab-pane active' id='sysinfoVersion'><h3>PHP Version:\\3</h3>",
            $output
        );

        $output = preg_replace(
            "/<table class=\"{$tableClass}\">(\s+)<tr><td>(\s+)This program makes use of the Zend Scripting Language Engine:<br \/>(.*?)<br \/><\/td><\/tr>(\s+)<\/table><br \/>/is",
            "<p>This program makes use of the Zend Scripting Language Engine:<br />\\3</p>",
            $output
        );

        $output = preg_replace(
            "/<h1>Configuration<\/h1>/",
            "</div><div class='tab-pane' id='sysinfoConfig'><h3>Configuration</h3>",
            $output
        );
        $output = preg_replace(
            "/<h1>PHP Credits<\/h1>/",
            "</div><div class='tab-pane' id='sysinfoCredits'><h3>PHP Credits</h3>",
            $output
        );

        $output = preg_replace(
            "/<table class=\"{$tableClass}\">(\s+)<tr><th>mbstring extension makes use of(.*?)<\/th><\/tr>(\s+)<\/table><br \/>/is",
            "<p><strong>mbstring extension makes use of\\2</strong></p>",
            $output
        );

        $output = preg_replace(
            "/<table class=\"{$tableClass}\">(\s+)<tr><td>(\s+)Phar based on pear\/PHP_Archive(.*?)<\/td><\/tr>(\s+)<\/table><br \/>/is",
            "<p>Phar based on pear/PHP_Archive\\3</p>",
            $output
        );

        $output = preg_replace("/<h1>(.*?)<\/h1>/is", "<h3>\\1</h3>", $output);
        $output = preg_replace("/<h2>(.*?)<\/h2>/is", "<h4>\\1</h4>", $output);

        $output = preg_replace("/<td class=\"e\">(.*?)<\/td>/is", "<th>\\1</th>", $output);
        $output = preg_replace("/<td class=\"v\">(.*?)<\/td>/is", "<td>\\1</td>", $output);

        Template::set('phpinfo', $output);
        Template::render();
    }


    /**
     * Check an array of files/folders to see if they are writable and return the
     * results in a format usable in the requirements check step of the installation.
     *
     * Note that this only returns the data in the format expected by the Install
     * controller if called via check_folders() and check_files(). Otherwise, the
     * files and folders are intermingled unless they are passed as input.
     *
     * Yes, this is almost a direct copy of the method with the same name in Installer_lib.
     * Since the Installer_lib is supposed to be fairly independent from the rest
     * of Bonfire, we can't expect to be able to load it and use the method from
     * there.
     *
     * @param  array $filesAndFolders An array of paths to files/folders to check.
     *
     * @return array An associative array with the path as key and boolean value
     * indicating whether the path is writable.
     */
    protected function checkWritable(array $filesAndFolders = array())
    {
        if (empty($filesAndFolders)) {
            return array();
        }

        if (! function_exists('is_really_writable')) {
            $this->load->helper('file');
        }

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
}
