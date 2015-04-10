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
 * @license   http://opensource.org/licenses/MIT    The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Events Class
 *
 * Allows you to create hook points throughout the application that any other
 * module can tap into without hacking core code.
 *
 * @package Bonfire\Libraries\Events
 * @author     Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer
 */
class Events
{
    /**
     * @var object The CI instance, only retrieved if required in the init() method.
     */
    private static $ci;

	/**
     * @var array Holds the registered events.
	 */
	private static $events;

	/**
     * This if here solely for CI loading to work. Just calls the init() method.
	 *
	 * @return void
	 */
	public function __construct()
	{
		self::init();
    }

	/**
     * Loads the library's dependencies and configuration.
	 *
	 * @return void
	 */
	public static function init()
	{
        if (! function_exists('read_config')) {
            self::$ci =& get_instance();
            self::$ci->load->helper('config_file');
		}

        self::$events = read_config('events', true, null, false);

        // Merge events from indivdual modules.
        foreach (Modules::list_modules(true) as $module) {
            $module_events = read_config('events', true, $module, true);
            if (is_array($module_events)) {
                self::$events = array_merge_recursive(self::$events, $module_events);
            }
        }

        if (self::$events == false) {
			self::$events = array();
		}
    }

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
    public static function trigger($event_name = null, &$payload = null)
		{
        if (empty($event_name)
            || ! is_string($event_name)
            || ! array_key_exists($event_name, self::$events)
        ) {
			return;
		}

        foreach (self::$events[$event_name] as $subscriber) {
            if (strpos($subscriber['filename'], '.php') === false) {
				$subscriber['filename'] .= '.php';
			}

            $file_path = Modules::file_path(
                $subscriber['module'],
                $subscriber['filepath'],
                $subscriber['filename']
            );

            if (! file_exists($file_path)) {
				continue;
			}

			@include_once($file_path);

            if (! class_exists($subscriber['class'])) {
                // if class doesn't exist check that the function is callable
                // could be just a helper function
                if (is_callable($subscriber['method'])) {
                    call_user_func($subscriber['method'], $payload);
                }
				continue;
			}

			$class = new $subscriber['class'];

            if (! is_callable(array($class, $subscriber['method']))) {
				unset($class);
				continue;
			}

			$class->{$subscriber['method']}($payload);
			unset($class);
        }
    }
}
// end class /bonfire/libraries/events.php
