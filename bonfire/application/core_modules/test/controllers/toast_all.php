<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Toast_all
 * 
 * Runs all tests in the /app/controllers/test/ folder.
 * 
 * NOTE: This class *REQUIRES* CURL!
 *
 * @package			CodeIgniter
 * @subpackage	Controllers
 * @category		Unit Testing
 * @license			Creative Commons Attribution 3.0 (cc) 2009 Jens Roland
 * @author			Jens Roland (mail@jensroland.com)
 * 
 */


class Toast_all extends Controller
{
	// The folder INSIDE /controllers/ where the test classes are located
	// TODO: autoset
	var $test_dir = APPPATH .'/test/';

	// Files to skip (ie. non-test classes) inside the test dir
	var $skip = array(
		'Toast.php',
		'Toast_all.php'
	);

	// CURL multithreaded mode (only set to true if you are sure your tests
	// don't conflict when run in parallel)
	var $multithreaded = false;

	function Toast_all()
	{
		parent::Controller();
	}

	function index()
	{
		$output = '';
		
		// Fetch all test classes
		$test_files = $this->_get_test_files();

		// Build array of full test URLs
		$this->load->helper('url');
		$test_urls = array();
		foreach ($test_files as $file)
		{
			$test_urls[] = site_url($this->test_dir . $file . '/show_results');
		}

		// Load header
		$output .= $this->load->view('test/header', NULL, TRUE);

		// Aggregate test results
		if ($this->multithreaded)
		{
			$output .= $this->_curl_get_multi($test_urls);
		}
		else
		{
			$output .= $this->_curl_get($test_urls);
		}

		// Load footer
		$output .= $this->load->view('test/footer', NULL, TRUE);
		
		// Send to display
		echo $output;
	}

	/**
	 * Get a list of all the test files in the test dir
	 * 
	 * @return array of filenames (without '.php' extensions)
	 */
	function _get_test_files()
	{
		$files = array();

		$handle=opendir(APPPATH . '/controllers' . $this->test_dir);
		while (false!==($file = readdir($handle)))
		{
			// Skip hidden/system files and the files in the skip[] array
			if ( ! in_array($file, $this->skip) && ! (substr($file, 0, 1) == '.'))
			{
				// Remove the '.php' part of the file name
				$files[] = substr($file, 0, strlen($file) - 4);
			}
		}
		closedir($handle);
		return $files;
	}

	/**
	 * Fetch a number of URLs as a string
	 * 
	 * @return string containing the (concatenated) HTML documents
	 * @param array $urls array of fully qualified URLs
	 */
	function _curl_get($urls)
	{
		$html_str = '';
		foreach ($urls as $url)
		{
			$curl_handle=curl_init();
			curl_setopt($curl_handle, CURLOPT_URL, $url);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
			$html_str .= curl_exec($curl_handle);
			curl_close($curl_handle);
		}
		return $html_str;
	}

	/**
	 * Fetch a number of URLs as a string (multithreaded)
	 * 
	 * @return string containing the (concatenated) HTML documents
	 * @param array $urls array of fully qualified URLs
	 */
	function _curl_get_multi($urls)
	{
		$html_str = '';
		$url_count = count($urls);

		// Initialize CURL (multithreaded) for all the URLs
		$curl_arr = array();
		$master = curl_multi_init();

		for ($i = 0; $i < $url_count; $i++)
		{
			$url = $urls[$i];
			$curl_arr[$i] = curl_init($url);
			curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
			curl_multi_add_handle($master, $curl_arr[$i]);
		}

		// Run the tests in parallel
		do {
			curl_multi_exec($master, $running);
		} while($running > 0);

		// Aggregate the results
		for ($i = 0; $i < $url_count; $i++)
		{
			$html_str .= curl_multi_getcontent($curl_arr[$i]);
		}
		return $html_str;
	}


}

// End of file Toast_all.php */
// Location: ./system/application/controllers/test/Toast_all.php */ 
