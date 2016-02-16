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
}
