<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BFPATH .'libraries/Modules.php';
require_once BFPATH .'libraries/Route.php';

/**
 * Bonfire Modified
 *
 * This file was originally based off of the stock CodeIgniter Router class.
 * It was modified to include lightweight HMVC functionality/modules based
 * on segersjens/CodeIgniter-HMVC-Modules, as well as Laravel's router.
 *
 * https://github.com/segersjens/CodeIgniter-HMVC-Modules
 *
 * @package 	Bonfire
 * @author   	Bonfire Dev Team
 * @since  		Version 1.0
 */

//--------------------------------------------------------------------

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Router Class
 *
 * Parses URIs and determines routing
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		ExpressionEngine Dev Team
 * @category	Libraries
 * @link		http://codeigniter.com/user_guide/general/routing.html
 */
class CI_Router {

	/**
	 * Config class
	 *
	 * @var object
	 * @access public
	 */
	var $config;
	/**
	 * List of routes
	 *
	 * @var array
	 * @access public
	 */
	var $routes			= array();
	/**
	 * List of error routes
	 *
	 * @var array
	 * @access public
	 */
	var $error_routes	= array();
	/**
	 * Current class name
	 *
	 * @var string
	 * @access public
	 */
	var $class			= '';
	/**
	 * Current method name
	 *
	 * @var string
	 * @access public
	 */
	var $method			= 'index';
	/**
	 * Sub-directory that contains the requested controller class
	 *
	 * @var string
	 * @access public
	 */
	var $directory		= '';
	/**
	 * Default controller (and method if specific)
	 *
	 * @var string
	 * @access public
	 */
	var $default_controller;

	/**
	 * Current module name
	 *
	 * @var string
	 * @access  public
	 */
	var $module = '';

	/**
	 * The route names that have been matched
	 *
	 * @var array
	 */
	var $names = array();

	//--------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * Runs the route mapping function.
	 */
	function __construct()
	{
		$this->config =& load_class('Config', 'core');
		$this->uri =& load_class('URI', 'core');
		log_message('debug', "Router Class Initialized");

		/*
			HMVC modifications
		 */

		// Process 'modules_locations' from config
		$locations = $this->config->item('modules_locations');

		if (!$locations)
		{
			$locations = array(APPPATH .'modules/', BFPATH .'modules/');
		}
		else if (!is_array($locations))
		{
			$locations = array($locations);
		}

		// Make sure all paths are the same format.
		foreach ($locations as &$location)
		{
			$location = realpath($location);
			$location = str_replace('\\', '/', $location);
			$location = rtrim($location, '/'). '/';
		}

		$this->config->set_item('modules_locations', $locations);
	}

	// --------------------------------------------------------------------

	/**
	 * Set the route mapping
	 *
	 * This function determines what should be served based on the URI request,
	 * as well as any "routes" that have been set in the routing config file.
	 *
	 * @access	private
	 * @return	void
	 */
	function _set_routing()
	{
		// Are query strings enabled in the config file?  Normally CI doesn't utilize query strings
		// since URI segments are more search-engine friendly, but they can optionally be used.
		// If this feature is enabled, we will gather the directory/class/method a little differently
		$segments = array();
		if ($this->config->item('enable_query_strings') === TRUE AND isset($_GET[$this->config->item('controller_trigger')]))
		{
			if (isset($_GET[$this->config->item('directory_trigger')]))
			{
				$this->set_directory(trim($this->uri->_filter_uri($_GET[$this->config->item('directory_trigger')])));
				$segments[] = $this->fetch_directory();
			}

			if (isset($_GET[$this->config->item('controller_trigger')]))
			{
				$this->set_class(trim($this->uri->_filter_uri($_GET[$this->config->item('controller_trigger')])));
				$segments[] = $this->fetch_class();
			}

			if (isset($_GET[$this->config->item('function_trigger')]))
			{
				$this->set_method(trim($this->uri->_filter_uri($_GET[$this->config->item('function_trigger')])));
				$segments[] = $this->fetch_method();
			}
		}

		// Load the routes.php file.
		if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/routes.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
		}
		elseif (is_file(APPPATH.'config/routes.php'))
		{
			include(APPPATH.'config/routes.php');
		}

		$this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
		unset($route);

		// Set the default controller so we can display it in the event
		// the URI doesn't correlated to a valid controller.
		$this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);

		// Were there any query string segments?  If so, we'll validate them and bail out since we're done.
		if (count($segments) > 0)
		{
			return $this->_validate_request($segments);
		}

		// Fetch the complete URI string
		$this->uri->_fetch_uri_string();

		// Is there a URI string? If not, the default controller specified in the "routes" file will be shown.
		if ($this->uri->uri_string == '')
		{
			return $this->_set_default_controller();
		}

		// Do we need to remove the URL suffix?
		$this->uri->_remove_url_suffix();

		// Compile the segments into an array
		$this->uri->_explode_segments();

		// Parse any custom routing that may exist
		$this->_parse_routes();

		// Re-index the segment array so that it starts with 1 rather than 0
		$this->uri->_reindex_segments();
	}

	// --------------------------------------------------------------------

	/**
	 * Set the default controller
	 *
	 * @access	private
	 * @return	void
	 */
	function _set_default_controller()
	{
		if ($this->default_controller === FALSE)
		{
			show_error("Unable to determine what should be displayed. A default route has not been specified in the routing file.");
		}
		// Is the method being specified?
		if (strpos($this->default_controller, '/') !== FALSE)
		{
			$x = explode('/', $this->default_controller);

			$this->set_class($x[0]);
			$this->set_method($x[1]);
			$this->_set_request($x);
		}
		else
		{
			$this->set_class($this->default_controller);
			$this->set_method('index');
			$this->_set_request(array($this->default_controller, 'index'));
		}

		// re-index the routed segments array so it starts with 1 rather than 0
		$this->uri->_reindex_segments();

		log_message('debug', "No URI present. Default controller set.");
	}

	// --------------------------------------------------------------------

	/**
	 * Set the Route
	 *
	 * This function takes an array of URI segments as
	 * input, and sets the current class/method
	 *
	 * @access	private
	 * @param	array
	 * @param	bool
	 * @return	void
	 */
	function _set_request($segments = array())
	{
		$segments = $this->_validate_request($segments);

		if (count($segments) == 0)
		{
			return $this->_set_default_controller();
		}

		$this->set_class($segments[0]);

		if (isset($segments[1]))
		{
			// A standard method request
			$this->set_method($segments[1]);
		}
		else
		{
			// This lets the "routed" segment array identify that the default
			// index method is being used.
			$segments[1] = 'index';
		}

		// Update our "routed" segment array to contain the segments.
		// Note: If there is no custom routing, this array will be
		// identical to $this->uri->segments
		$this->uri->rsegments = $segments;
	}

	// --------------------------------------------------------------------

	/**
	 * Validates the supplied segments.  Attempts to determine the path to
	 * the controller.
	 *
	 * BONFIRE: This method was modified to reflect the HMVC changes.
	 *
	 * @access	private
	 * @param	array
	 * @return	array
	 */
	function _validate_request($segments)
	{
		if (count($segments) == 0)
		{
			return $segments;
		}

		// Locate the controller with modules support
		if ($located = $this->locate($segments))
		{
			return $located;
		}

		// Is there a 404 override?
		if (!empty($this->routes['404_override']))
		{
			$segments = explode('/', $this->routes['404_override']);
			if ($located = $this->locate($segments))
			{
				return $located;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse Routes
	 *
	 * This function matches any routes that may exist in
	 * the config/routes.php file against the URI to
	 * determine if the class/method need to be remapped.
	 *
	 * NOTE: The first segment must be the name of the
	 * module, otherwise it is impossible to detect
	 * the current module in this method.
	 *
	 * @access	private
	 * @return	void
	 */
	function _parse_routes()
	{
		// Apply the current module's routing config
		if ($module = $this->uri->segment(0))
		{
			foreach ($this->config->item('modules_locations') as $location)
			{
				if (is_file($file = $location . $module .'/config/routes.php'))
				{
					include ($file);

					$route = (!isset($route) or !is_array($route)) ? array() : $route;
					$this->routes = array_merge($this->routes, $route);
					unset($route);
				}
			}
		}

		// Get our names parsed out of the routes
		$this->_parse_names();

		// Turn the segment array into a URI string
		$uri = implode('/', $this->uri->segments);

		// Is there a literal match?  If so we're done
		if (isset($this->routes[$uri]))
		{
			return $this->_set_request(explode('/', $this->routes[$uri]));
		}

		// Loop through the route array looking for wild-cards
		foreach ($this->routes as $key => $val)
		{
			// Convert wild-cards to RegEx
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

			// Does the RegEx match?
			if (preg_match('#^'.$key.'$#', $uri))
			{
				// Do we have a back-reference?
				if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}

				return $this->_set_request(explode('/', $val));
			}
		}

		// If we got this far it means we didn't encounter a
		// matching route so we'll set the site default route
		$this->_set_request($this->uri->segments);
	}

	// --------------------------------------------------------------------

	/**
	 * Runs through the routes, and pulls out any named routes so that we
	 * have clean routes to use, and have the named pairs for later matching.
	 *
	 * @return [type] [description]
	 */
	public function _parse_names()
	{
		foreach ($this->routes as &$route)
		{
			// Any named routes will be separated by a double-colon.
			if (is_string($route) && strpos($route, '::') !== false)
			{
				list($name, $uri) = explode('::', $route);

				// Save it for later matching
				$this->names[$name] = $uri;

				// Clean up our route.
				$route = $uri;
			}
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a named route.
	 *
	 * @param  string $name The name of the route to return
	 * @return string       The route, if exists, or NULL.
	 */
	public function route_named($name)
	{
		if (array_key_exists($name, $this->names))
		{
			return $this->names[$name];
		}

		return null;
	}

	//--------------------------------------------------------------------


	/**
	 * The logic of locating a controller is grouped in this function.
	 *
	 * @param  array $segments
	 * @return array
	 */
	function locate($segments)
	{
		list($module, $directory, $controller) = array_pad($segments, 3, NULL);

		foreach ($this->config->item('modules_locations') as $location)
		{
			$relative = $location;

			// Make path relative to controllers directory
			$start = rtrim(realpath(FCPATH . APPPATH), '/');
			$parts = explode('/', str_replace('\\', '/', $start));

			// Iterate all parts and replace absolute part with relative part.
			for ($i = 1; $i <= count($parts); $i++)
			{
				$relative = str_replace(implode('/', $parts) .'/', str_repeat('../', $i), $relative, $count);
				array_pop($parts);

				// Stop iteration if found
				if ($count)
				{
					break;
				}
			}

			// Does a module exist? (modules/xyz/controllers)
			if (is_dir($source = $location . $module .'/controllers/')) {
				$this->module = $module;
				$this->directory = $relative . $module .'/controllers/';

				// Module root controller
				if ($directory && is_file($source . $directory .'.php'))
				{
					$this->class = $directory;
					return array_slice($segments, 1);
				}

				// Module sub-directory?
				if ($directory && is_dir($source . $directory .'/'))
				{
					$source = $source . $directory .'/';
					$this->directory .= $directory .'/';

					// Module sub-directory controller?
					if (is_file($source . $directory . '.php'))
					{
						return array_slice($segments, 1);
					}

					// Module sub-directory  default controller?
					if (is_file($source . $this->default_controller . '.php'))
					{
						$segments[1] = $this->default_controller;
						return array_slice($segments, 1);
					}

					// Module sub-directory sub-controller?
					if ($controller && is_file($source . $controller . '.php'))
					{
						return array_slice($segments, 2);
					}
				}

				// Module controller?
				if (is_file($source . $module . '.php'))
				{
					return $segments;
				}

				// Module default controller?
				if (is_file($source . $this->default_controller . '.php'))
				{
					$segments[0] = $this->default_controller;
					return $segments;
				}
			}
		}

		// Root folder controller?
		if (is_file(APPPATH . 'controllers/' . $module . '.php'))
		{
			return $segments;
		}

		// Sub-directory controller?
		if ($directory && is_file(APPPATH . 'controllers/' . $module . '/' . $directory . '.php'))
		{
			$this->directory = $module . '/';
			return array_slice($segments, 1);
		}

		// Bonfire Controller?
		if (is_file(BFPATH .'controllers/'. $module .'.php'))
		{
			return $segments;
		}

		// Default controller?
		if (is_file(APPPATH . 'controllers/' . $module . '/' . $this->default_controller . '.php'))
		{
			$segments[0] = $this->default_controller;
			return $segments;
		}

	}

	//--------------------------------------------------------------------

	/**
	 * Set the module name
	 *
	 * @param  string $module The name of the module
	 * @return  void
	 */
	function set_module($module) {
		$this->module = $module;
	}

	//--------------------------------------------------------------------

	/**
	 * Fetch the module name
	 *
	 * @return  void
	 */
	function fetch_module() {
		return $this->module;
	}

	//--------------------------------------------------------------------

	/**
	 * Set the class name
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	function set_class($class)
	{
		$this->class = str_replace(array('/', '.'), '', $class);
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch the current class
	 *
	 * @access	public
	 * @return	string
	 */
	function fetch_class()
	{
		return $this->class;
	}

	// --------------------------------------------------------------------

	/**
	 *  Set the method name
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	function set_method($method)
	{
		$this->method = $method;
	}

	// --------------------------------------------------------------------

	/**
	 *  Fetch the current method
	 *
	 * @access	public
	 * @return	string
	 */
	function fetch_method()
	{
		if ($this->method == $this->fetch_class())
		{
			return 'index';
		}

		return $this->method;
	}

	// --------------------------------------------------------------------

	/**
	 *  Set the directory name
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	function set_directory($dir)
	{
		$this->directory = str_replace(array('/', '.'), '', $dir).'/';
	}

	// --------------------------------------------------------------------

	/**
	 *  Fetch the sub-directory (if any) that contains the requested controller class
	 *
	 * @access	public
	 * @return	string
	 */
	function fetch_directory()
	{
		return $this->directory;
	}

	// --------------------------------------------------------------------

	/**
	 *  Set the controller overrides
	 *
	 * @access	public
	 * @param	array
	 * @return	null
	 */
	function _set_overrides($routing)
	{
		if ( ! is_array($routing))
		{
			return;
		}

		if (isset($routing['directory']))
		{
			$this->set_directory($routing['directory']);
		}

		if (isset($routing['controller']) AND $routing['controller'] != '')
		{
			$this->set_class($routing['controller']);
		}

		if (isset($routing['function']))
		{
			$routing['function'] = ($routing['function'] == '') ? 'index' : $routing['function'];
			$this->set_method($routing['function']);
		}
	}


}
// END CI_Router Class

//--------------------------------------------------------------------

/* End of file Router.php */
/* Location: ./system/core/Router.php */