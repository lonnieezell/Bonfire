<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
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
 * Application helper functions
 *
 * Includes various helper functions from across the core modules to ease
 * editing and minimize physical files that need to be loaded.
 *
 * @package    Bonfire\Helpers\application_helper
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/developer
 */

if ( ! function_exists('array_implode')) {
	/**
	 * Implode an array with the key and value pair giving a glue, a separator
	 * between pairs, and the array to implode.
	 *
	 * Encode Query Strings
	 * @example $query = url_encode(array_implode('=', '&', $array));
	 *
	 * @param string $glue      The glue between key and value.
	 * @param string $separator Separator between pairs.
	 * @param array  $array     The array to implode.
	 *
	 * @return string A string with the combined elements.
	 */
	function array_implode($glue, $separator, $array)
	{
		if ( ! is_array($array)) {
			return $array;
		}

		$string = array();
		foreach ($array as $key => $val) {
			if (is_array($val)) {
				$val = implode(',', $val);
			}

			$string[] = "{$key}{$glue}{$val}";
		}

		return implode($separator, $string);
	}
}

if ( ! function_exists('dump')) {
	/**
	 * Output the given variables with formatting and location.
	 *
	 * Huge props out to Phil Sturgeon for this one
     * (http://philsturgeon.co.uk/blog/2010/09/power-dump-php-applications).
     *
	 * To use, pass in any number of variables as arguments.
	 *
	 * @return void
	 */
	function dump()
	{
		list($callee) = debug_backtrace();
		$arguments = func_get_args();
		$totalArguments = count($arguments);

		echo "<fieldset class='dump'>" . PHP_EOL .
			"<legend>{$callee['file']} @ line: {$callee['line']}</legend>" . PHP_EOL .
			'<pre>';

	    $i = 0;
	    foreach ($arguments as $argument) {
			echo '<br /><strong>Debug #' . (++$i) . " of {$totalArguments}</strong>: ";

			if ( ! empty($argument)
                && (is_array($argument) || is_object($argument))
            ) {
				print_r($argument);
			} else {
				var_dump($argument);
			}
		}

		echo '</pre>' . PHP_EOL .
			'</fieldset>' . PHP_EOL;
	}
}

if ( ! function_exists('e')) {
	/**
	 * A convenience function to ensure output is safe to display. Helps to
	 * defeat XSS attacks by running the text through htmlspecialchars().
	 *
	 * Should be used anywhere user-submitted text is displayed.
	 *
	 * @param String $str The text to process and output.
	 *
	 * @return void
	 */
	function e($str)
	{
		echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}
}

if ( ! function_exists('gravatar_link')) {
	/**
	 * Create an image link based on Gravatar for the specified email address.
	 * It will default to the site's generic image if none is found for the user.
	 *
	 * Note that if gravatar does not have an image that matches the criteria,
	 * it will return a link to an image under *your_theme/images/user.png*.
	 * Also, by explicity omitting email you're denying http-req to gravatar.com.
	 *
	 * @param string $email The email address to check for. If NULL, defaults to
	 * theme image.
	 * @param int    $size  The width (and height) of the resulting image to grab.
	 * @param string $alt   Alt text to be put in the link tag.
	 * @param string $title The title text to be put in the link tag.
	 * @param string $class Any class(es) that should be assigned to the link tag.
	 * @param string $id    The id (if any) that should put in the link tag.
	 *
	 * @return string The resulting image tag.
	 */
	function gravatar_link($email = null, $size = 48, $alt = '', $title = '', $class = null, $id = null)
	{
		// Set our default image based on required size.
		//$defaultImage = Template::theme_url('images/user.png');
		$defaultImage = 'identicon';

		// Set our minimum site rating to PG
		$rating = 'PG';

		// If email null, don't send gravatar.com HTTP request
		if ($email) {
			// Check whether HTTP or HTTPS Request should be used
			$httpProtocol = ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https://secure.' : 'http://www.';

			// URL for Gravatar
			$gravatarURL = "{$httpProtocol}gravatar.com/avatar/%s?s=%s&amp;r=%s&amp;d=%s";
			$avatarURL = sprintf(
				$gravatarURL,
				md5(strtolower(trim($email))),
				$size,
				strtolower($rating),
				rawurlencode($defaultImage)
			);
		} else {
			$avatarURL = $defaultImage ;
		}

		$alt = html_escape($alt);
		$title = html_escape($title);

		$id = $id !== null ? " id='{$id}' " : ' ';
		$class = $class !== null ? " class='{$class}'" : ' ';

		return "<img src='{$avatarURL}' width='{$size}' height='{$size}' alt='{$alt}' title='{$title}' {$class}{$id} />";
	}
}

if ( ! function_exists('iif')) {
	/**
	* If then Else Statement wrapped in one function, If $expression = true then
	* $returntrue else $returnfalse.
	*
	* @param mixed $expression    IF Statement to be checked
	* @param mixed $returntrue    What to Return on True
	* @param mixed $returnfalse   What to Return on False
	* @param bool  $echo          Defaults to false, if set to true will echo
	* instead of return.
	*
	* @return mixed    If echo is set to true will echo the value of the
	* expression, defaults to returning the value.
	*/
	function iif($expression, $returntrue, $returnfalse = '', $echo = false)
	{
		$result = $expression ? $returntrue : $returnfalse;

        if ( $echo === false ) {
            return $result;
        }

        echo $result;
	}
}

if ( ! function_exists('js_escape')) {
	/**
	 * Like html_escape() for JavaScript string literals.
	 *
	 * Inside attributes like onclick, you need to use html_escape() *as well*.
	 * Inside script tags, html_escape() would do the wrong thing, and
	 * js_escape() is enough on its own.
	 *
	 * Useful for confirm() or alert() - but of course not document.write() or
	 * similar, so take care.
	 *
	 * @param String $str The string to process.
	 *
	 * @return String    The escaped string.
	 */
	function js_escape($str)
	{
		/*
		$escape =
			// Obvious string literal escapes:
			'\'' . "\"" . "\\" .

			// Newlines could also break the literal;
			// escape all the C0 controls including \r\n
			"\0..\037" .

			// Escape </script> - n.b. '<' alone wouldn't work.
			// This works for HTML - XHTML would need much more here.
			"\/";
		*/

		return addcslashes($str, "\"'\\\0..\037\/");
	}
}

if ( ! function_exists('list_contexts')) {
    /**
     * Return a list of the contexts specified for the application.
     *
     * The optional $landingPageFilter can be applied to force return of
     * contexts that have a landing page (index.php) available.
     *
     * @param	$landingPageFilter	Boolean	TRUE to filter FALSE for all.
     *
     * @return	Array	The context values array.
     */
    function list_contexts($landingPageFilter = false)
    {
        $ci = &get_instance();

        $contexts = $ci->config->item('contexts');
        if (empty($contexts) || ! is_array($contexts)) {
            return false;
        }

        // Ensure settings context exists
        if ( ! in_array('settings', $contexts)) {
            array_push($contexts, 'settings');
        }

        // Ensure developer context exists
        if ( ! in_array('developer', $contexts)) {
            array_push($contexts, 'developer');
        }

        // Optional removal of contexts without landing pages
        if ($landingPageFilter === true) {
            $returnContexts = array();
            foreach ($contexts as $context) {
                if (file_exists(realpath(VIEWPATH) . DIRECTORY_SEPARATOR . SITE_AREA . DIRECTORY_SEPARATOR . $context . DIRECTORY_SEPARATOR . 'index.php')) {
                    array_push($returnContexts, $context);
                }
            }
            $contexts = $returnContexts;
        }

        return $contexts;
    }
}

if ( ! function_exists('log_activity')) {
	/**
	 * Log an activity if config item 'enable_activities' is true.
	 *
	 * @param int    $userId   The id of the user that performed the activity.
	 * @param string $activity The activity details. Max length of 255 chars.
	 * @param string $module   The name of the module that set the activity.
	 *
	 * @return int/bool An int with the ID of the new object, or FALSE on failure.
	 */
	function log_activity($userId = null, $activity = '', $module = 'any')
	{
		$ci =& get_instance();

		if ($ci->config->item('enable_activity_logging') === true) {
			$ci->load->model('activities/activity_model');
			$ci->activity_model->log_activity($userId, $activity, $module);
		}
	}
}

if ( ! function_exists('logit')) {
	/**
	 * Log an error to the Console (if loaded) and to the log files.
	 *
	 * @param $message string The string to write to the logs.
	 * @param $level string The log level, as per CI log_message method.
	 *
	 * @return void
	 */
	function logit($message = '', $level = 'debug')
	{
		if (empty($message)) {
			return;
		}

		if (class_exists('Console')) {
			Console::log($message);
		}

		log_message($level, $message);
	}
}

if ( ! function_exists('obj_value')) {
	/**
	 *
	 * @param object $obj   Object
	 * @param string $key   Name of the object element
	 * @param string $type  Input type
	 * @param int    $value Value to check the key against
	 *
	 * @return null|string
	 */
	function obj_value($obj, $key, $type = 'text', $value = 0)
	{
		if (isset($obj->$key)) {
			switch ($type) {
				case 'checkbox':
                    // no break;
				case 'radio':
					if ($obj->$key == $value) {
						return 'checked="checked"';
					}
					break;

				case 'select':
					if ($obj->$key == $value) {
						return 'selected="selected"';
					}
					break;

				case 'text':
                    // no break;
				default:
					return $obj->$key;
			}
		}

		return null;
	}
}

//------------------------------------------------------------------------------
// Module Functions (deprecated - use the Modules library)
//------------------------------------------------------------------------------

if ( ! function_exists('module_config')) {
	/**
	 * Returns the 'module_config' array from a modules config/config.php
	 * file. The 'module_config' contains more information about a module,
	 * and even provide enhanced features within the UI. All fields are optional
	 *
	 * @deprecated since 0.7.1 Use Modules::config() instead.
	 *
	 * @author Liam Rutherford (http://www.liamr.com)
	 *
	 * <code>
	 * $config['module_config'] = array(
	 * 	'name'			=> 'Blog', 			// The name that is displayed in the UI
	 *	'description'	=> 'Simple Blog',	// May appear at various places within the UI
	 *	'author'		=> 'Your Name',		// The name of the module's author
	 *	'homepage'		=> 'http://...',	// The module's home on the web
	 *	'version'		=> '1.0.1',			// Currently installed version
	 *	'menu'			=> array(			// A view file containing an <ul> that will be the sub-menu in the main nav.
	 *		'context'	=> 'path/to/view'
	 *	)
	 * );
	 * </code>
	 *
	 * @param $module_name string The name of the module.
	 * @param $return_full boolean If true, will return the entire config array. If false, will return only the 'module_config' portion.
	 *
	 * @return array An array of config settings, or an empty array if empty/not found.
	 */
	function module_config($module_name = null, $return_full = false)
	{
        return Modules::config($module_name, $return_full);
	}
}

if ( ! function_exists('module_controller_exists')) {
	/**
	 * Determines whether a controller exists for a module.
	 *
	 * @deprecated since 0.7.1 Use Modules::controller_exists() instead.
	 *
	 * @param $controller string The name of the controller to look for (without the .php)
	 * @param $module string The name of module to look in.
	 *
	 * @return boolean
	 */
	function module_controller_exists($controller = null, $module = null)
	{
        return Modules::controller_exists($controller, $module);
	}
}

if ( ! function_exists('module_file_path')) {
	/**
	 * Finds the path to a module's file.
	 *
	 * @deprecated since 0.7.1 Use Modules::file_path() instead.
	 *
	 * @param $module string The name of the module to find.
	 * @param $folder string The folder within the module to search for the file (ie. controllers).
	 * @param $file string The name of the file to search for.
	 *
	 * @return string The full path to the file.
	 */
	function module_file_path($module = null, $folder = null, $file = null)
	{
        return Modules::file_path($module, $folder, $file);
	}
}

if ( ! function_exists('module_files')) {
	/**
	 * Returns an associative array of files within one or more modules.
	 *
	 * @deprecated since 0.7.1 Use Modules::files() instead.
	 *
	 * @param $module_name string If not NULL, will return only files from that module.
	 * @param $module_folder string If not NULL, will return only files within that folder of each module (ie 'views')
	 * @param $exclude_core boolean Whether we should ignore all core modules.
	 *
	 * @return array An associative array, like: array('module_name' => array('folder' => array('file1', 'file2')))
	 */
	function module_files($module_name = null, $module_folder = null, $exclude_core = false)
	{
        return Modules::files($module_name, $module_folder, $exclude_core);
	}
}

if ( ! function_exists('module_folders')) {
	/**
	 * Returns an array of the folders that modules are allowed to be stored in.
	 * These are set in *bonfire/application/third_party/MX/Modules.php*.
	 *
	 * @deprecated since 0.7.1 Use Modules::folders() instead.
	 *
	 * @return array The folders that modules are allowed to be stored in.
	 */
	function module_folders()
	{
		return Modules::folders();
	}
}

if ( ! function_exists('module_list')) {
	/**
	 * Returns a list of all modules in the system.
	 *
	 * @deprecated since 0.7.1 Use Modules::list_modules() instead
	 *
	 * @param bool $exclude_core Whether to exclude the Bonfire core modules or not
	 *
	 * @return array A list of all modules in the system.
	 */
	function module_list($exclude_core = false)
	{
        return Modules::list_modules($exclude_core);
    }
}

if( ! function_exists('module_path')) {
	/**
	 * Returns the path to the module and it's specified folder.
	 *
	 * @deprecated since 0.7.1 Use Modules::path() instead.
	 *
	 * @param $module string The name of the module (must match the folder name)
	 * @param $folder string The folder name to search for. (Optional)
	 *
	 * @return string The path, relative to the front controller.
	 */
	function module_path($module = null, $folder = null)
	{
        return Modules::path($module, $folder);
	}
}
/* End /helpers/application_helper.php */