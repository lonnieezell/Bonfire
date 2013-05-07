<?php

/**
 * Please note this file shouldn't be exposed on a live server,
 * there is no filtering of $_POST!!!!
 */

//--------------------------------------------------------------------
// User Configurable Options
//--------------------------------------------------------------------

// System Folder Paths
//
// Customize these paths if you are using a folder layout other than
// what is provided by default with Bonfire.
$folder = realpath(dirname(__FILE__)).'/';
define('MAIN_PATH', str_replace('tests/', '', $folder));
define('SIMPLETEST', MAIN_PATH .'tests/simpletest/');	// Directory of simpletest
define('ROOT', MAIN_PATH .'public/'); 					// Directory of codeigniter index.php
define('TESTS_DIR', MAIN_PATH . 'tests/'); 				// Directory of your tests.
define('APP_DIR', MAIN_PATH . 'application/'); 			// CodeIgniter Application directory
define('BF_DIR', MAIN_PATH .'bonfire/');				// Bonfire core directory

// Ignore Folders
//
// This array contains names of folders, relative to the
// base 'tests' folder that will be ignored when doing a scan
// the folders to locate tests.
//
//  This can be modified at runtime with to include/exclude app-specific
//  or Bonfire core folders with the --app_only or -bf_only options on the CLI
$ignore_folders = array(
	TESTS_DIR .'support',
	TESTS_DIR .'simpletest'
);

// Only Folder
//
// Will only have a value if the -d or --dir flags were used on the CLI
$only_folder = NULL;

// Only File
//
// Will only have a value if the -f or --file flags were used on the CLI
$only_file = isset($_POST['test']) && !empty($_POST['test']) ? $_POST['test'] : null;

//--------------------------------------------------------------------
// END User Configurable Values
//--------------------------------------------------------------------
// Do not edit below this line unless you know what you are doing.
//

//do not use autorun as it output ugly report upon no test run
require_once SIMPLETEST . 'unit_tester.php';
require_once SIMPLETEST . 'mock_objects.php';
require_once SIMPLETEST . 'collector.php';
require_once SIMPLETEST . 'web_tester.php';
require_once TESTS_DIR . '_support/my_reporter.php';

// Bypass any CSRF protection in order to avoid
// modifying simpletest
//
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_COOKIE['ci_csrf_token']))
{
	$_POST['ci_csrf_token'] = $_COOKIE['ci_csrf_token'];
}

//Capture CodeIgniter output, discard and load system into $ci variable
ob_start();
	include(ROOT . 'index.php');
	$ci =& get_instance();
ob_end_clean();

//--------------------------------------------------------------------
// TEST CLASSES
//--------------------------------------------------------------------
// These classes provide CodeIgniter & Bonfire-specific setup needed
// to use SimpleTest with the project.
//

/**
 * CI_UnitTestCase
 *
 * Provides an parent class our tests can run from. This class extends
 * from the default SimpleTest UnitTestCase file. It provides access
 * to the $this object in a CI-style manner, as well as additional
 * assertions:
 *
 * 		- assertEmpty()
 * 		- assertNotEmpty()
 */
class CI_UnitTestCase extends UnitTestCase {

	protected $ci;

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
		$this->ci =& get_instance();
	}

	//--------------------------------------------------------------------

	/**
	 * Allows us to transparently use CI-standard $this->load, etc.
	 * in our tests and still have them function correctly in the
	 * test files.
	 *
	 * @return  mixed
	 *
	 * @access  public
	 */
	public function __get($var)
	{
		return $this->ci->$var;
	}

	//--------------------------------------------------------------------

	/**
	 * Will be true if the value is empty.
	 *
	 * @param  mixed  $value   Supposedly empty value.
	 * @param  string $message Message to display.
	 *
	 * @return boolean True on pass.
	 *
	 * @access public
	 */
	public function assertEmpty($value, $message = '%s')
	{
		$dumper = new SimpleDumper();
		$message = sprintf($message, '[' . $dumper->describeValue($value) . '] should be empty');
		return $this->assertTrue(empty($value), $message);

	}

	//--------------------------------------------------------------------

	/**
	 * Will be true if the value is not empty.
	 *
	 * @param  mixed  $value   Supposedly not empty value.
	 * @param  string $message Message to display.
	 *
	 * @return boolean True on pass.
	 *
	 * @access public
	 */
	public function assertNotEmpty($value, $message = '%s')
	{
		$dumper = new SimpleDumper();
		$message = sprintf($message, '[' . $dumper->describeValue($value) . '] should not be empty');
		return $this->assertFalse(empty($value), $message);

	}

	//--------------------------------------------------------------------
}	// end CI_UnitTestCase

//--------------------------------------------------------------------


/**
 * CI_WebTestCase
 *
 * Inherits from the SimpleTest WebTestCase class to provide a simple
 * means of accessing the CI $this object when running tests against the
 * Web pages themselves.
 */
class CI_WebTestCase extends WebTestCase {

	protected $ci;

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::WebTestCase();
		$this->ci =& get_instance();
	}

	//--------------------------------------------------------------------

	public function __get($var)
	{
		return $this->ci->$var;
	}

	//--------------------------------------------------------------------
}	// end CI_WebTestCase

//--------------------------------------------------------------------
// END TEST CLASSES
//--------------------------------------------------------------------




//--------------------------------------------------------------------
// UTILITY FUNCTIONS
//--------------------------------------------------------------------

/**
 * Function to determine if in cli mode and if so set up variables to make it work
 *
 * @param Array of commandline args
 * @return Boolean true or false if commandline mode setup
 *
 */
function setup_cli($argv)
{
	if (php_sapi_name() == 'cli')
	{
		if(isset($argv[1]))
		{
			if(stripos($argv[1],'.php') !== false)
			{
				$_POST['test'] = $argv[1];
			}
			else
			{
				$_POST[$argv[1]] = $argv[1];
			}
		}
		else
		{
			$_POST['all'] = 'all';
		}
		$_SERVER['HTTP_HOST'] = '';
		$_SERVER['REQUEST_URI'] = '';
		return true;
	}
	return false;
}

//--------------------------------------------------------------------

/**
 * Function to map tests and strip .html files.
 *
 * @param	string
 * @return 	array
 */
function map_tests($location = '')
{
	if (empty($location))
	{
		return FALSE;
	}

	$files = directory_map($location);
	$return = array();

	foreach ($files as $file)
	{
		if ($file != 'index.html')
		{
			$return[] = $file;
		}
	}
	return $return;
}

//--------------------------------------------------------------------

/**
 * A simple copy of CI's memory_usage method that we use to
 * provide a quick overview of memory usage. This is mainly to ensure that
 * none of the scripts are getting out of hand.
 *
 * @return strign The amount of memory used
 */
function memory_usage()
{
	$size = memory_get_usage(true);

	$unit=array('B','KB','MB','GB','TB','PB');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

//--------------------------------------------------------------------

/**
 * discover_tests
 *
 * Given a base folder, it will recursively scan a folder to
 * determine which files are valid test files. Valid test files
 * must have either 'Test' or '_test' in their name.
 *
 * @param  string $start_folder The folder to start scanning. If empty, it will
 *                              	be the base of the tests folder.
 * @param boolean $ignore_onlys If TRUE, will ignore checks for $only_file and $only_folder
 *                              Primarily used for recursive scans.
 * @return array               The list of test files.
 */
function discover_tests($start_folder=null, $ignore_onlys=false)
{
	global $ignore_folders, $only_file, $only_folder;
	$files = array();

	// If no start folder exists, we'll
	// set it manually to our tests root
	if (is_null($start_folder))
	{
		$start_folder = TESTS_DIR;
	}

	// If this is one of the ignore folders
	// simply return an empty array.
	if (in_array($start_folder, $ignore_folders))
	{
		return array();
	}

	$start_folder = rtrim($start_folder, '/');

	// Are we restricting to a single file?
	if ($only_file && !$ignore_onlys)
	{
		if (is_file($start_folder .'/'. $only_file))
		{
			$files[] = $start_folder .'/'. $only_file;
			return $files;
		}
	}

	if (!function_exists('directory_map'))
	{
		global $ci;
		$ci->load->helper('directory');
	}

	$folders = directory_map($start_folder, 1);

	// Look through all of our folders & files for
	// valid test files.
	if (is_array($folders))
	{
		foreach ($folders as $folder)
		{
			// Folders get ran back through this function
			// and will return an array of valid files.
			// Merge this array with ours and call it good.
			if (is_dir($start_folder .'/'. $folder) && !in_array($start_folder .'/'. $folder, $ignore_folders))
			{
				$f = discover_tests($start_folder .'/'. $folder, true);
				$files = array_merge($files, $f);
			}
			else if (is_file($start_folder .'/'. $folder))
			{
				// Does it appear to be a valid filename?
				if (strpos($folder, 'Test')  OR strpos($folder, '_test'))
				{
					$files[] = $start_folder .'/'. $folder;
				}
			}
		}
	}

	return $files;
}

//--------------------------------------------------------------------
// END UTILITY FUNCTIONS
//--------------------------------------------------------------------



// Make sure we have plenty of time to run the tests.
error_reporting(E_ALL ^ E_NOTICE);

// Are we running in cli mode?
$is_cli = setup_cli($argv);


if ($is_cli)
{
	// Setup our allowed short/long options
	$short_opts  = '';
	$short_opts .= 'a';			// Include the applications folder tests only. Not Bonfire core.
	$short_opts .= 'b';			// Include Bonfire's core tests only, not the app-specific ones.
	$short_opts .= 'f:';		// Restrict to a single file
	$short_opts .= 'd:';		// Restrict to a single folder

	$long_opts  = array(
		'app_only',				// Include the applications folder tests only. Not Bonfire core.
		'bf_only',				// Include Bonfire's core tests only, not the app-specific ones.
		'file:',				// Restrict to a single file
		'dir:',					// Restrict to a single folder
	);

	$cli_opts = getopt($short_opts, $long_opts);

	// If we are on an app_only or bf_only run,
	// then add the folders to the ignored folders
	if (array_key_exists('a', $cli_opts) || array_key_exists('app_only', $cli_opts))
	{
		$ignore_folders[] = str_replace('src', 'tests', BF_DIR);
	}
	else if (array_key_exists('b', $cli_opts) || array_key_exists('bf_only', $cli_opts))
	{
		$ignore_folders[] = str_replace('src', 'tests', APP_DIR);
	}
	else if (array_key_exists('f', $cli_opts) || array_key_exists('file', $cli_opts) )
	{
		$only_file = array_key_exists('file', $cli_opts) ? $cli_opts['file'] : $cli_opts['f'];
	}
	else if (array_key_exists('d', $cli_opts) || array_key_exists('dir', $cli_opts) )
	{
		$only_folder = array_key_exists('dir', $cli_opts) ? $cli_opts['dir'] : $cli_opts['d'];
	}
}

// Setup similar options via $_GET vars for the GUI version
else
{
	if (isset($_GET['a']) || isset($_GET['app_only']) || isset($_POST['app_only']))
	{
		$ignore_folders[] = rtrim( str_replace('src', 'tests', BF_DIR), '/');
	}
	else if (isset($_GET['b']) || isset($_GET['bf_only']) || isset($_POST['bf_only']))
	{
		$ignore_folders[] = rtrim(str_replace('src', 'tests', APP_DIR), '/');
	}
	else if (isset($_GET['f']) || isset($_GET['file']))
	{
		$only_file = isset($_GET['f']) ? $_GET['f'] : $_GET['file'];
	}
	else if (isset($_GET['d']) || isset($_GET['dir']))
	{
		$only_folder = isset($_GET['d']) ? $_GET['d'] : $_GET['dir'];
	}
}

$test_suite = new TestSuite();
$test_suite->_label = 'Bonfire Test Suite';

// We destroy the session here so that tests can create
// their own sessions. This also allows us to not contaminate test
// results with our own session information.
$ci->load->library('session');
$ci->session->sess_destroy();

$ci->load->helper('directory');

// Start your engines!
$test_start = microtime();

$test_files = null;

// TODO Revise to allow args from the CLI as folder/file names
$only_folder = isset($only_folder) && !empty($only_folder) ? TESTS_DIR . $only_folder : null;

$all_tests = discover_tests(TESTS_DIR, true);

$test_files = discover_tests($only_folder);

// Add the found test files to the suite to be tested.
if (is_array($test_files))
{
	foreach ($test_files as $file)
	{
		$test_suite->addFile($file);
	}
}

// ------------------------------------------------------------------------


//variables for report
/*
$controllers = map_tests(TESTS_DIR . 'controllers');
$models = map_tests(TESTS_DIR . 'models');
$views = map_tests(TESTS_DIR . 'views');
$libraries = map_tests(TESTS_DIR . 'libraries');
$bugs = map_tests(TESTS_DIR . 'bugs');
$helpers = map_tests(TESTS_DIR . 'helpers');
*/
$form_url =  'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$test_end = microtime();

/* Benchmark */
list($sm, $ss) = explode(' ', $test_start);
list($em, $es) = explode(' ', $test_end);

$elapse_time =  number_format(($em + $es) - ($sm + $ss), 4);

//display the form
if ($is_cli) {
	exit ($test_suite->run(new TextReporter()) ? 0 : 1);
}
else {
	include(TESTS_DIR . 'test_gui.php');
}