<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Sysinfo Module
 *
 * Displays various system information to the user
 *
 * @package    Bonfire\Modules\Sysinfo\Controllers\Developer
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs
 */
class Developer extends Admin_Controller
{
	/**
	 * Load required classes
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// Restrict access - View
		$this->auth->restrict('Bonfire.Sysinfo.View');

		$this->lang->load('sysinfo');

		Template::set('toolbar_title', lang('sysinfo_system_info'));

		Template::set_block('sub_nav', 'developer/_sub_nav');
	}

	/**
	 * Display the system information, including Bonfire and PHP versions,
	 * to the user
	 *
	 * @return void
	 */
	public function index()
	{
        // Date helper is used for user_time() function in the view
        $this->load->helper('date');
		Template::render();
	}

	/**
	 * Display the list of modules in the Bonfire installation
	 *
	 * @return void
	 */
	public function modules()
	{
		$modules = Modules::list_modules();
		$configs = array();

		foreach ($modules as $module) {
			$configs[$module] = Modules::config($module);

			if ( ! isset($configs[$module]['name'])) {
				$configs[$module]['name'] = ucwords($module);
			}
		}

        // Sort the list of modules by directory name
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

		$output = (preg_match("/<body.*?".">(.*)<\/body>/is", $buffer, $match)) ? $match['1'] : $buffer;
		$output = preg_replace("/<a href=\"http:\/\/www.php.net\/\">.*?<\/a>/", "", $output);
		$output = preg_replace("/<a href=\"http:\/\/www.zend.com\/\">.*?<\/a>/", "", $output);
		$output = preg_replace("/<h2 align=\"center\">PHP License<\/h2>.*?<\/table>/si", "", $output);
		$output = preg_replace("/<h2>PHP License.*?<\/table>/is", "", $output);
		$output = preg_replace("/<table(.*?)bgcolor=\".*?\">/", "<table>", $output);
		$output = preg_replace("/<table(.*?)>/", "<table class=\"table table-striped table-condensed\">", $output);
		$output = preg_replace("/<a.*?<\/a>/", "", $output);
		$output = preg_replace("/<th(.*?)>/", "<th\\1>", $output);
		$output = preg_replace("/<hr.*?>/", "<br />", $output);
		$output = preg_replace("/<tr(.*?).*?".">/", "<tr\\1>", $output);
		$output = preg_replace('/<h(1|2)\s*(class="p")?/i', "\n<h\\1", $output);

		Template::set('phpinfo', $output);
		Template::render();
	}
}
/* end /sysinfo/controllers/developer.php */