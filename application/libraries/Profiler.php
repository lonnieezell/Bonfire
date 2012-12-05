<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright © 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Profiler Class
 *
 * This class enables you to display benchmark, query, and other data
 * in order to help with debugging and optimization.
 *
 * Note: At some point it would be good to move all the HTML in this class
 * into a set of template files in order to allow customization.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/profiling.html
 */
class CI_Profiler {

	var $CI;

	protected $_available_sections = array(
										'benchmarks',
										'get',
										'memory_usage',
										'post',
										'uri_string',
										'controller_info',
										'queries',
										'http_headers',
										'config',
										'files',
										'console',
										'userdata'
										);
	protected $_sections = array();		// Stores _compile_x() results

	protected $_time_format = 'ms';		// Benchmark time format for display - either 'sec' or 'ms'.

	// --------------------------------------------------------------------

	public function __construct($config = array())
	{
		$this->CI =& get_instance();
		$this->CI->load->language('profiler');

		// default all sections to display
		foreach ($this->_available_sections as $section)
		{
			if ( ! isset($config[$section]))
			{
				$this->_compile_{$section} = TRUE;
			}
		}

		// Make sure the Console is loaded.
		if (!class_exists('Console'))
		{
			$this->load->library('Console');
		}

		$this->set_sections($config);
	}

	// --------------------------------------------------------------------

	/**
	 * Set Sections
	 *
	 * Sets the private _compile_* properties to enable/disable Profiler sections
	 *
	 * @param	mixed
	 * @return	void
	 */
	public function set_sections($config)
	{
		foreach ($config as $method => $enable)
		{
			if (in_array($method, $this->_available_sections))
			{
				$this->_compile_{$method} = ($enable !== FALSE) ? TRUE : FALSE;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Auto Profiler
	 *
	 * This function cycles through the entire array of mark points and
	 * matches any two points that are named identically (ending in "_start"
	 * and "_end" respectively).  It then compiles the execution times for
	 * all points and returns it as an array
	 *
	 * @return	array
	 */
	protected function _compile_benchmarks()
	{
		$profile = array();
		$output = array();

		foreach ($this->CI->benchmark->marker as $key => $val)
		{
			// We match the "end" marker so that the list ends
			// up in the order that it was defined
			if (preg_match("/(.+?)_end/i", $key, $match))
			{
				if (isset($this->CI->benchmark->marker[$match[1].'_end']) AND isset($this->CI->benchmark->marker[$match[1].'_start']))
				{
					$time = $this->CI->benchmark->elapsed_time($match[1].'_start', $key);

					if ($this->_time_format == 'ms')
					{
						$time = round($time * 1000) .' ms';
					}

					$profile[$match[1]] = $time;
				}
			}
		}

		// Build a table containing the profile data.
		// Note: At some point we might want to make this data available to be logged.

		foreach ($profile as $key => $val)
		{
			$key = ucwords(str_replace(array('_', '-'), ' ', $key));
			$output[$key] = $val;
		}

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile Queries
	 *
	 * @return	string
	 */
	protected function _compile_queries()
	{
		$dbs = array();
		$output = array();

		// Let's determine which databases are currently connected to
		foreach (get_object_vars($this->CI) as $CI_object)
		{
			if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') )
			{
				$dbs[] = $CI_object;
			}
		}

		if (count($dbs) == 0)
		{
			return $this->CI->lang->line('profiler_no_db');
		}

				$highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR&nbsp;', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', ' IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', ' ON', 'AS', 'AVG', 'SUM', '(', ')');

		foreach ($dbs as $db)
		{
			if (count($db->queries) == 0)
			{
				$output = $this->CI->lang->line('profiler_no_queries');
			}
			else
			{
				$total = 0; // total query time
				$counts = array_count_values($db->queries);

				foreach ($db->queries as $key => $val)
				{
					$duplicate = false;

					$time = number_format($db->query_times[$key], 4);

					$query = $duplicate ? '<span class="ci-profiler-duplicate">'. $val .'</span>' : $val;

					$explain = strpos($val, 'SELECT') !== false ? $this->CI->db->query('EXPLAIN '. $val) : NULL;
					if (!is_null($explain))
					{
						$query .= $this->build_sql_explain($explain->row(), $time);
					}

					$total += $db->query_times[$key];

					foreach ($highlight as $bold)
					{
						$query = str_replace($bold, '<b>'. $bold .'</b>', $query);
					}

					$output[] = array(
						'query' => $query,
						'time'	=> $time
					);
				}

				$total = number_format($total, 4);
				$output[][$total] = 'Total Query Execution Time';
			}

		}
//die('<pre>' .print_r($output, true));
		return $output;
	}


	// --------------------------------------------------------------------

	public function build_sql_explain($data, $time)
	{
		$output = '<span class="ci-profiler-db-explain">';

		$output .= 'Speed: <em>'. $time .'</em>';
		$output .= ' - Possible keys: <em>'. htmlentities($data->possible_keys,ENT_QUOTES, 'UTF-8') .'</em>';
		$output .= ' - Key Used: <em>'. htmlentities($data->key,ENT_QUOTES, 'UTF-8') .'</em>';
		$output .= ' - Type: <em>'. htmlentities($data->type,ENT_QUOTES, 'UTF-8') .'</em>';
		$output .= ' - Rows: <em>'. htmlentities($data->rows,ENT_QUOTES, 'UTF-8') .'</em>';
		$output .= ' - Extra: <em>'. htmlentities($data->Extra,ENT_QUOTES, 'UTF-8') .'</em>';

		$output .= '</span>';

		return $output;
	}

	//--------------------------------------------------------------------


	/**
	 * Compile $_GET Data
	 *
	 * @return	string
	 */
	protected function _compile_get()
	{
		$output = array();

		if (count($_GET) == 0)
		{
			$output = $this->CI->lang->line('profiler_no_get');
		}
		else
		{
			foreach ($_GET as $key => $val)
			{
				if ( ! is_numeric($key))
				{
					$key = "'".$key."'";
				}

				//$output .= "<tr><td style='width:50%;color:#000;background-color:#ddd;padding:5px'>&#36;_GET[".$key."]&nbsp;&nbsp; </td><td style='width:50%;padding:5px;color:#cd6e00;font-weight:normal;background-color:#ddd;'>";
				if (is_array($val))
				{
					$output['&#36;_GET['. $key .']'] = "<pre>" . htmlspecialchars(stripslashes(print_r($val, true))) . "</pre>";
				}
				else
				{
					$output['&#36;_GET['. $key .']'] = htmlspecialchars(stripslashes($val));
				}
			}
		}

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile $_POST Data
	 *
	 * @return	string
	 */
	protected function _compile_post()
	{
		$output = array();

		if (count($_POST) == 0)
		{
			$output = $this->CI->lang->line('profiler_no_post');
		}
		else
		{
			foreach ($_POST as $key => $val)
			{
				if ( ! is_numeric($key))
				{
					$key = "'".$key."'";
				}

				if (is_array($val))
				{
					$output['&#36;_POST['. $key .']'] = '<pre>'. htmlspecialchars(stripslashes(print_r($val, TRUE))) . '</pre>';
				}
				else
				{
					$output['&#36;_POST['. $key .']'] = htmlspecialchars(stripslashes($val));
				}
			}
		}

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Show query string
	 *
	 * @return	string
	 */
	protected function _compile_uri_string()
	{
		if ($this->CI->uri->uri_string == '')
		{
			$output = $this->CI->lang->line('profiler_no_uri');
		}
		else
		{
			$output = $this->CI->uri->uri_string;
		}

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Show the controller and function that were called
	 *
	 * @return	string
	 */
	protected function _compile_controller_info()
	{
		$output = $this->CI->router->fetch_class()."/".$this->CI->router->fetch_method();

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile memory usage
	 *
	 * Display total used memory
	 *
	 * @return	string
	 */
	protected function _compile_memory_usage()
	{
		if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
		{
			$output = number_format($usage) .' bytes';
		}
		else
		{
			$output = $this->CI->lang->line('profiler_no_memory_usage');
		}

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile header information
	 *
	 * Lists HTTP headers
	 *
	 * @return	string
	 */
	protected function _compile_http_headers()
	{
		$output = array();

		foreach (array('HTTP_ACCEPT', 'HTTP_USER_AGENT', 'HTTP_CONNECTION', 'SERVER_PORT', 'SERVER_NAME', 'REMOTE_ADDR', 'SERVER_SOFTWARE', 'HTTP_ACCEPT_LANGUAGE', 'SCRIPT_NAME', 'REQUEST_METHOD',' HTTP_HOST', 'REMOTE_HOST', 'CONTENT_TYPE', 'SERVER_PROTOCOL', 'QUERY_STRING', 'HTTP_ACCEPT_ENCODING', 'HTTP_X_FORWARDED_FOR') as $header)
		{
			$val = (isset($_SERVER[$header])) ? $_SERVER[$header] : '';
			$output[$header] =  $val;
		}

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile config information
	 *
	 * Lists developer config variables
	 *
	 * @return	string
	 */
	protected function _compile_config()
	{
		$output = array();

		foreach ($this->CI->config->config as $config=>$val)
		{
			if (is_array($val))
			{
				$val = print_r($val, TRUE);
			}

			$output[$config] = htmlspecialchars($val);
		}

		return $output;
	}

	// --------------------------------------------------------------------

	public function _compile_files()
	{
		$files = get_included_files();

		sort($files);

		return $files;
	}

	//--------------------------------------------------------------------

	public function _compile_console()
	{
		$logs = Console::get_logs();

		if ($logs['console'])
		{
			foreach ($logs['console'] as $key => $log)
			{
				if ($log['type'] == 'log')
				{
					$logs['console'][$key]['data'] = print_r($log['data'], true);
				}
				elseif ($log['type'] == 'memory')
				{
					$logs['console'][$key]['data'] = $this->get_file_size($log['data']);
				}
			}
		}

		return $logs;
	}

	//--------------------------------------------------------------------

	function _compile_userdata()
	{
		$output = array();

		if (FALSE !== $this->CI->load->is_loaded('session'))
		{

			$compiled_userdata = $this->CI->session->all_userdata();

			if (count($compiled_userdata))
			{
				foreach ($compiled_userdata as $key => $val)
				{
					if (is_numeric($key))
					{
						$output[$key] = "'$val'";
					}

					if (is_array($val))
					{
						$output[$key] = htmlspecialchars(stripslashes(print_r($val, true)));
					}
					else
					{
						$output[$key] = htmlspecialchars(stripslashes($val));
					}
				}
			}
		}

		return $output;
	}

	//--------------------------------------------------------------------

	public static function get_file_size($size, $retstring = null) {
        // adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
	    $sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

	    if ($retstring === null) { $retstring = '%01.2f %s'; }

		$lastsizestring = end($sizes);

		foreach ($sizes as $sizestring) {
	       	if ($size < 1024) { break; }
	           if ($sizestring != $lastsizestring) { $size /= 1024; }
		}

		if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
		return sprintf($retstring, $size, $sizestring);
	}

	//--------------------------------------------------------------------

	/**
	 * Run the Profiler
	 *
	 * @return	string
	 */
	public function run()
	{
		$this->CI->load->helper('language');

		$fields_displayed = 0;

		foreach ($this->_available_sections as $section)
		{
			if ($this->_compile_{$section} !== FALSE)
			{
				$func = "_compile_{$section}";
				if ($section == 'http_headers') $section = 'headers';
				$this->_sections[$section] = $this->{$func}();
				$fields_displayed++;
			}
		}

		// Has the user created an override in application/views?
		if (is_file(APPPATH .'views/profiler_template'.EXT))
		{
			$output = $this->CI->load->view('profiler_template', array('sections' => $this->_sections, 'cip_time_format' => $this->_time_format), true);
		}
		else
		{
			// Load the view from system/views
			$orig_view_path = $this->CI->load->_ci_view_path;
			$this->CI->load->_ci_view_path = BASEPATH .'views/';

			$output = $this->CI->load->_ci_load(array(
					'_ci_view' 		=> 'profiler_template',
					'_ci_vars' 		=> array('sections' => $this->_sections, 'cip_time_format' => $this->_time_format),
					'_ci_return'	=> true,
			));

			$this->CI->load->_ci_view_path = $orig_view_path;
		}

		return $output;
	}

}

// END CI_Profiler class

//--------------------------------------------------------------------

/* End of file Profiler.php */
/* Location: ./system/libraries/Profiler.php */