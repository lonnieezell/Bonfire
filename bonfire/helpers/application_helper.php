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
 * @license   http://opensource.org/licenses/MIT    MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Application helper functions
 *
 * Includes various helper functions from across the core modules to ease editing
 * and minimize physical files which need to be loaded.
 *
 * @package    Bonfire\Helpers\application_helper
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/developer
 */

if (! function_exists('array_implode')) {
	/**
     * Implode an array with the key and value pair given a glue, a separator
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
        if (! is_array($array)) {
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

if (! function_exists('dump')) {
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

            if (! empty($argument)
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

if (! function_exists('e')) {
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

if (! function_exists('gravatar_link')) {
	/**
	 * Create an image link based on Gravatar for the specified email address.
	 * It will default to the site's generic image if none is found for the user.
	 *
     * Note that if gravatar does not have an image that matches the criteria, it
     * will default to gravatar's 'identicon' return a link to an image under *your_theme/images/user.png*.
	 * Also, by explicity omitting email you're denying http-req to gravatar.com.
	 *
     * @param string  $email The email address to check for. If null, the gravatar
     * image defaults to 'identicon'.
     * @param integer $size  The width (and height) of the resulting image to grab.
	 * @param string $alt   Alt text to be put in the link tag.
	 * @param string $title The title text to be put in the link tag.
	 * @param string $class Any class(es) that should be assigned to the link tag.
	 * @param string $id    The id (if any) that should put in the link tag.
	 *
	 * @return string The resulting image tag.
	 */
	function gravatar_link($email = null, $size = 48, $alt = '', $title = '', $class = null, $id = null)
	{
        // Make sure $size is an integer.
        $size = empty($size) || is_object($size) || ! is_int($size) ? 48 : intval($size);

        // If email is empty, don't send an HTTP request to gravatar.com.
        if (empty($email)) {
            $avatarURL = Template::theme_url('images/user.png');
        } else {
            // While it would be more efficient to place the values for $defaultImage,
            // $rating, and $gravatarUrl (and $httpProtocol) directly into the call
            // to sprintf(), it would be more difficult to document and change the
            // values when necessary (as was the case when the format for the URL
            // changed in the past).
            //
            // Similarly, the calls to the strtolower() and rawurlencode() functions
            // to manipulate the $rating and $defaultImage values in the sprintf()
            // call could be avoided/removed by making sure the values were correct
            // beforehand, but the requirements would need to be documented for
            // each value anyway...

            // Set the default image.
            $defaultImage = 'identicon';

            // Set the minimum site rating to PG.
		$rating = 'PG';

            // Check whether HTTP or HTTPS Request should be used.
            $httpProtocol = is_https() ? 'https://secure.' : 'http://www.';

            // URL for Gravatar, with placeholders for sprintf().
            $gravatarUrl = "{$httpProtocol}gravatar.com/avatar/%s?s=%s&amp;r=%s&amp;d=%s";

			$avatarURL = sprintf(
                $gravatarUrl,
				md5(strtolower(trim($email))),
				$size,
				strtolower($rating),
				rawurlencode($defaultImage)
			);
		}

        // Escape all of the attributes, except the src, width, and height.
        // Use an empty alt attribute if $alt is empty.
        $alt = empty($alt) ? '' : html_escape($alt);

        // These are the most commonly-required attributes for an image tag.
        $imageAttributes = array(
            "src='{$avatarURL}'",
            "width='{$size}'",
            "height='{$size}'",
            "alt='{$alt}'",
        );

        if (! empty($id)) {
            $imageAttributes[] = "id='" . html_escape($id) . "'";
        }

        if (! empty($class)) {
            $imageAttributes[] = "class='" . html_escape($class) . "'";
        }

        if (! empty($title)) {
            $imageAttributes[] = "title='" . html_escape($title) . "'";
        }

        return "<img " . implode(' ', $imageAttributes) . " />";
	}
}

if (! function_exists('iif')) {
	/**
	* If then Else Statement wrapped in one function, If $expression = true then
	* $returntrue else $returnfalse.
	*
    * @param mixed   $expression  Expression to evaluate.
    * @param mixed   $returntrue  What to return if $expression is true.
    * @param mixed   $returnfalse What to return if $expression is false.
    * @param boolean $echo        If set to true, the result will echo instead of
    * returning. Defaults to false (return the result, will not echo).
	*
    * @return mixed If $echo is true, nothing is returned and the result will be
    * sent to echo. Otherwise, either $returntrue or $returnfalse will be returned.
	*/
	function iif($expression, $returntrue, $returnfalse = '', $echo = false)
	{
		$result = $expression ? $returntrue : $returnfalse;

        if ($echo === false) {
            return $result;
        }

        echo $result;
    }
}

if (! function_exists('is_https')) {
    /**
     * Is HTTPS?
     *
     * Determines if the application is accessed via an encrypted (HTTPS) connection.
     *
     * This function copied from CI v3 /core/Common.php
     * @copyright Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
     * @copyright Copyright (c) 2014, British Columbia Institute of Technology (http://bcit.ca/)
     * @license   http://opensource.org/licenses/MIT    MIT License
     * @link      https://github.com/bcit-ci/CodeIgniter/blob/develop/system/core/Common.php
     *
     * @return boolean True if the application is currently using HTTPS, else false.
     */
    function is_https()
    {
        if (! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
            && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
        ) {
            return true;
        } elseif (! empty($_SERVER['HTTP_FRONT_END_HTTPS'])
            && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off'
        ) {
            return true;
        }

        return false;
	}
}

if (! function_exists('js_escape')) {
	/**
	 * Like html_escape() for JavaScript string literals.
	 *
	 * Inside attributes like onclick, you need to use html_escape() *as well*.
     *
     * Inside script tags, html_escape() would do the wrong thing, and js_escape()
     * is enough on its own.
	 *
	 * Useful for confirm() or alert() - but of course not document.write() or
	 * similar, so take care.
	 *
     * @param string $str The string to process.
	 *
     * @return string The escaped string.
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

if (! function_exists('list_contexts')) {
    /**
     * Return a list of the contexts specified for the application.
     *
     * @param boolean $landingPageFilter If true, only returns contexts which have
     * a landing page (index.php) available.
     *
     * @return array The context values array.
     */
    function list_contexts($landingPageFilter = false)
    {
        // While limiting the number of files loaded is good, only the Contexts
        // library should be determining required and available contexts.
        if (! class_exists('Contexts', false)) {
        $ci = &get_instance();
            $ci->load->library('ui/contexts');
        }

        return Contexts::getContexts($landingPageFilter);
    }
}

if (! function_exists('log_activity')) {
	/**
	 * Log an activity if config item 'enable_activities' is true.
	 *
     * @param integer $userId   The id of the user that performed the activity.
	 * @param string $activity The activity details. Max length of 255 chars.
	 * @param string $module   The name of the module that set the activity.
	 *
     * @return integer/boolean The ID of the new object, or false on failure (or
     * if enable_activity_logging is not true).
	 */
	function log_activity($userId = null, $activity = '', $module = 'any')
	{
		$ci =& get_instance();
		if ($ci->config->item('enable_activity_logging') === true) {
			$ci->load->model('activities/activity_model');
            return $ci->activity_model->log_activity($userId, $activity, $module);
		}

        return false;
	}
}

if (! function_exists('logit')) {
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

if (! function_exists('obj_value')) {
	/**
	 *
	 * @param object $obj   Object
     * @param string  $key   Name of the object element.
     * @param string  $type  Input type.
     * @param integer $value Value to check the key against.
	 *
     * @return null|string If $obj->$key is set, returns the value, or a
     * checked/selected string if $type is 'checkbox', 'radio', or 'select'. Returns
     * null if $obj->$key is not set.
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

if (! function_exists('module_config')) {
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

if (! function_exists('module_controller_exists')) {
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

if (! function_exists('module_file_path')) {
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

if (! function_exists('module_files')) {
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

if (! function_exists('module_folders')) {
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

if (! function_exists('module_list')) {
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

if (! function_exists('module_path')) {
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
