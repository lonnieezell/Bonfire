<?php
/**
 * Please note this file shouldn't be exposed on a live server,
 * there is no filtering of $_POST!!!!
 */
error_reporting(0);
$cli_mode = setup_cli($argv); // Determines if running in cli mode

/**
 * Configure your paths here:
 */
define('MAIN_PATH', realpath(dirname(__FILE__)).'/');
define('SIMPLETEST', MAIN_PATH .'tests/simpletest/'); // Directory of simpletest
define('ROOT', MAIN_PATH); // Directory of codeigniter index.php
define('TESTS_DIR', MAIN_PATH . 'tests/'); // Directory of your tests.
define('APP_DIR', MAIN_PATH . 'bonfire/application/'); // CodeIgniter Application directory

//do not use autorun as it output ugly report upon no test run
require_once SIMPLETEST . 'unit_tester.php';
require_once SIMPLETEST . 'mock_objects.php';
require_once SIMPLETEST . 'collector.php';
require_once SIMPLETEST . 'web_tester.php';
require_once SIMPLETEST . 'extensions/my_reporter.php';

$test_suite = new TestSuite();
$test_suite->_label = 'Bonfire Test Suite';

class CodeIgniterUnitTestCase extends UnitTestCase {
	protected $ci;

	public function __construct()
	{
		parent::__construct();
		$this->ci =& get_instance();
	}

	public function __get($var)
	{
		return $this->ci->$var;
	}

	/**
	 * Will be true if the value is empty.
	 * @param  mixed  $value   Supposedly empty value.
	 * @param  string $message Message to display.
	 *
	 * @return boolean True on pass.
	 *
	 * @access public
	 */
	public function assertEmpty($value, $message = '%s')
	{
		$dumper = &new SimpleDumper();
		$message = sprintf($message, '[' . $dumper->describeValue($value) . '] should be empty');
		return $this->assertTrue(empty($value), $message);

	}

	/**
	 * Will be true if the value is not empty.
	 * @param  mixed  $value   Supposedly not empty value.
	 * @param  string $message Message to display.
	 *
	 * @return boolean True on pass.
	 *
	 * @access public
	 */
	public function assertNotEmpty($value, $message = '%s')
	{
		$dumper = &new SimpleDumper();
		$message = sprintf($message, '[' . $dumper->describeValue($value) . '] should not be empty');
		return $this->assertFalse(empty($value), $message);

	}

}

class CodeIgniterWebTestCase extends WebTestCase {
	protected $ci;

	public function __construct()
	{
		parent::WebTestCase();
		$this->ci =& get_instance();
	}

	public function __get($var)
	{
		return $this->ci->$var;
	}
}

// Because get is removed in ci we pull it out here.
$run_all = FALSE;
if (isset($_GET['all']) || isset($_POST['all']))
{
	$run_all = TRUE;
}



//Capture CodeIgniter output, discard and load system into $CI variable
ob_start();
	include(ROOT . 'index.php');
	$CI =& get_instance();
ob_end_clean();

$CI->load->library('session');
$CI->session->sess_destroy();

$CI->load->helper('directory');

$test_start = microtime();

// Get all main tests
if ($run_all OR ( ! empty($_POST) && ! isset($_POST['test'])))
{
	$test_objs = array('controllers','models','views','libraries','bugs','helpers');

	foreach ($test_objs as $obj)
	{
		if (isset($_POST[$obj]) OR $run_all)
		{
			$dir = TESTS_DIR . $obj;
			$dir_files = directory_map($dir);
			foreach ($dir_files as $file)
			{
				if ($file != 'index.html')
				{
					$test_suite->addTestFile($dir . '/' . $file);
				}
			}
		}
	}
}
elseif (isset($_POST['test'])) //single test
{
	$file = $_POST['test'];

	if (file_exists(TESTS_DIR . $file))
	{
		$test_suite->addTestFile(TESTS_DIR . $file);
	}
}

// ------------------------------------------------------------------------

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

/**
 * Function to map tests and strip .html files.
 *
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

function memory_usage()
{
	$size = memory_get_usage(true);

	$unit=array('B','KB','MB','GB','TB','PB');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

//variables for report
$controllers = map_tests(TESTS_DIR . 'controllers');
$models = map_tests(TESTS_DIR . 'models');
$views = map_tests(TESTS_DIR . 'views');
$libraries = map_tests(TESTS_DIR . 'libraries');
$bugs = map_tests(TESTS_DIR . 'bugs');
$helpers = map_tests(TESTS_DIR . 'helpers');
$form_url =  'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$test_end = microtime();

/* Benchmark */
list($sm, $ss) = explode(' ', $test_start);
list($em, $es) = explode(' ', $test_end);

$elapse_time =  number_format(($em + $es) - ($sm + $ss), 4);

//display the form
if ($cli_mode) {
	exit ($test_suite->run(new TextReporter()) ? 0 : 1);
}
else {
	include(TESTS_DIR . 'test_gui.php');
}
