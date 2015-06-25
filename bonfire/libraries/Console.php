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
 * Console
 *
 * Provides several additional logging features designed to work
 * with the Forensics Profiler.
 *
 * Inspired by ParticleTree's PHPQuickProfiler. (http://particletree.com)
 *
 * @package    Bonfire
 * @subpackage Libraries
 * @category   Forensics
 * @author     Lonnie Ezell (http://lonnieezell.com)
 * @link       http://guides.cibonfire.com/core/console.html
 *
 */
class Console {

	/**
	 * Contains all of the logs that are collected.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $logs = array(
		'console'		=> array(),
		'log_count'		=> 0,
		'memory_count'	=> 0,
	);

	/**
	 * Stores the CodeIgniter core object.
	 *
	 * @access private
	 * @static
	 *
	 * @var object
	 */
	private static $ci;

	//--------------------------------------------------------------------

	/**
	 * This constructor is here purely for CI's benefit, as this is a
	 * static class.
	 *
	 * @return void
	 */
	public function __construct()
	{
		self::init();

		log_message('debug', 'Forensics Console library loaded');

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Grabs an instance of CI and gets things ready to run.
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function init()
	{
		self::$ci =& get_instance();

	}//end init()

	//--------------------------------------------------------------------

	/**
	 * Logs a variable to the console.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $data The variable to log.
	 *
	 * @return void
	 */
	public static function log($data=null)
	{
		if ($data !== 0 && empty($data))
		{
			// Anyone know why we were allowing
			// loggin of empty data? I can't think of
			// a good reason for it.
			//$data = 'empty';
			return false;
		}

		$log_item = array(
			'data' => $data,
			'type' => 'log'
		);

		self::add_to_console('log_count', $log_item);

	}//end log()

	//--------------------------------------------------------------------

	/**
	 * Logs the memory usage a single variable, or the entire script.
	 *
	 * @access public
	 * @static
	 *
	 * @param object $object The object to store the memory usage of.
	 * @param string $name   The name to be displayed in the console.
	 *
	 * @return void
	 */
	public static function log_memory($object=false, $name='PHP')
	{
		$memory = memory_get_usage();

		if ($object)
		{
			$memory = strlen(serialize($object));
		}

		$log_item = array(
			'data' => $memory,
			'type' => 'memory',
			'name' => $name,
			'data_type' => gettype($object)
		);

		self::add_to_console('memory_count', $log_item);

	}//end log_memory()

	//--------------------------------------------------------------------

	/**
	 * Returns the logs array for use in external classes. Namely the Forensics Profiler.
	 *
	 * @access public
	 * @static
	 *
	 * @return array
	 */
	public static function get_logs()
	{
		return self::$logs;

	}//end get_logs()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 *
	 * @access public
	 * @static
	 *
	 * @param string $log  Name of the log entry
	 * @param mixed  $item Item to add
	 *
	 * @return void
	 */
	public static function add_to_console($log=null, $item=null)
	{
		if (empty($log) || empty($item))
		{
			return;
		}

		self::$logs['console'][]	= $item;
		self::$logs[$log] 			+= 1;
	}//end add_to_console()

	//--------------------------------------------------------------------

}//end class
