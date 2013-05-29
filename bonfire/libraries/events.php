<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Events Class
 *
 * Allows you to create hook points throughout the application that any
 * other module can tap into without hacking core code.
 *
 * @package    Bonfire
 * @subpackage Libraries
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/core/unit_test.html
 * @version    3.0
 *
 */
class Events
{

	/**
	 * Holds the registered events.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private static $events;

	//--------------------------------------------------------------------

	/**
	 * This if here solely for CI loading to work. Just calls the init( ) method.
	 *
	 * @return void
	 */
	public function __construct()
	{
		self::init();
	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Loads the config/events.php file into memory so we can access it
	 * later without the disk load.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public static function init()
	{
		if (!function_exists('read_config'))
		{
			$ci =& get_instance();
			$ci->load->helper('config_file');
			$ci->load->helper('application');
		}


		self::$events = read_config('events', TRUE, NULL, FALSE);

        // merge other modules events
        foreach(module_list(TRUE) as $module)
        {
        	$module_events = read_config('events', TRUE, $module, TRUE);

            if(is_array($module_events))
            {
                self::$events = array_merge_recursive(self::$events, $module_events);
            }
        }


		if (self::$events == false)
		{
			self::$events = array();
		}

	}//end init()

	//--------------------------------------------------------------------

	/**
	 * Triggers an individual event.
	 *
	 * NOTE: The payload sent to the event method is a pointer to the actual data.
	 * This means that any operations on the data will affect the original data.
	 * Use with care.
	 *
	 * @param string $event_name A string with the name of the event to trigger. Case sensitive.
	 * @param mixed  $payload    (optional) A variable pointer to send to the event method.
	 *
	 * @return void
	 */
	public static function trigger($event_name=null, &$payload=null)
	{
		if (empty($event_name) || !is_string($event_name))
		{
			return;
		}

		if (!array_key_exists($event_name, self::$events))
		{
			return;
		}

		if (!function_exists('module_file_path'))
		{
			$ci =& get_instance();
			$ci->load->helper('application');
		}

		$subscribers = self::$events[$event_name];

		foreach ($subscribers as $subscriber)
		{
			if (strpos($subscriber['filename'], '.php') == false)
			{
				$subscriber['filename'] .= '.php';
			}

			$file_path = module_file_path($subscriber['module'], $subscriber['filepath'], $subscriber['filename']);

			if (!file_exists($file_path))
			{
				continue;
			}

			@include_once($file_path);

			if (!class_exists($subscriber['class']))
			{
                // if class doesn't exist check that the function is callable
                // could be just a helper function
                if(is_callable($subscriber['method']))
                {
                    call_user_func($subscriber['method'], $payload);
                }
				continue;
			}

			$class = new $subscriber['class'];

			if (!is_callable( array($class, $subscriber['method']) ))
			{
				unset($class);
				continue;
			}

			$class->{$subscriber['method']}($payload);
			unset($class);
		}//end foreach

	}//end trigger()

	//--------------------------------------------------------------------

}//end class