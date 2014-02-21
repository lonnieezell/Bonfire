<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire Modified
 *
 * This file was originally based off of the stock CodeIgniter Router class.
 * It was modified to include lightweight HMVC functionality/modules based
 * on segersjens/CodeIgniter-HMVC-Modules.
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
 * Loader Class
 *
 * Loads views and files
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		ExpressionEngine Dev Team
 * @category	Loader
 * @link		http://codeigniter.com/user_guide/libraries/loader.html
 */
class CI_Loader
{
	// All these are set automatically. Don't mess with them.
	/**
	 * Nesting level of the output buffering mechanism
	 *
	 * @var int
	 * @access protected
	 */
	protected $_ci_ob_level;
	/**
	 * List of paths to load views from
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_view_paths		= array();
	/**
	 * List of paths to load libraries from
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_library_paths	= array();
	/**
	 * List of paths to load models from
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_model_paths		= array();
	/**
	 * List of paths to load helpers from
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_helper_paths		= array();
	/**
	 * List of loaded base classes
	 * Set by the controller class
	 *
	 * @var array
	 * @access protected
	 */
	protected $_base_classes		= array(); // Set by the controller class
	/**
	 * List of cached variables
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_cached_vars		= array();
	/**
	 * List of loaded classes
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_classes			= array();
	/**
	 * List of loaded files
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_loaded_files		= array();
	/**
	 * List of loaded models
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_models			= array();
	/**
	 * List of loaded helpers
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_helpers			= array();
	/**
	 * List of class name mappings
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_varmap			= array('unit_test' => 'unit',
											'user_agent' => 'agent');

	/**
	 * List of loaded modules
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_modules = array();

	/**
	 * List of loaded controllers
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_controllers = array();

	//--------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * Sets the path to the view files and gets the initial output buffering level
	 */
	public function __construct()
	{
		$this->_ci_ob_level  = ob_get_level();
		$this->_ci_library_paths = array(APPPATH, BFPATH, BASEPATH);
		$this->_ci_helper_paths = array(APPPATH, BFPATH, BASEPATH);
		$this->_ci_model_paths = array(APPPATH, BFPATH);
		$this->_ci_view_paths = array(APPPATH.'views/'	=> TRUE);

		// Get current module from the router
		$router =& $this->_ci_get_component('router');
		if ($router->module)
		{
			$this->add_module($router->module);
		}

		log_message('debug', "Loader Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Loader
	 *
	 * This method is called once in CI_Controller.
	 *
	 * @param 	array
	 * @return 	object
	 */
	public function initialize()
	{
		$this->_ci_classes = array();
		$this->_ci_loaded_files = array();
		$this->_ci_models = array();
		$this->_base_classes =& is_loaded();

		$this->_ci_autoloader();

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Is Loaded
	 *
	 * A utility function to test if a class is in the self::$_ci_classes array.
	 * This function returns the object name if the class tested for is loaded,
	 * and returns FALSE if it isn't.
	 *
	 * It is mainly used in the form_helper -> _get_validation_object()
	 *
	 * @param 	string	class being checked for
	 * @return 	mixed	class object name on the CI SuperObject or FALSE
	 */
	public function is_loaded($class)
	{
		if (isset($this->_ci_classes[$class]))
		{
			return $this->_ci_classes[$class];
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Sparks Integration
    //--------------------------------------------------------------------

    /**
     * Provides a convenience method that lets us use the provided
     * official Sparks loader in a nice convenient way.
     *
     * @param       $spark
     * @param array $autoload
     */
    public function spark ($spark, $autoload=array())
    {
        require_once (APPPATH .'third_party/Sparks_Loader.php');

        $loader = new Sparks_Loader();
        $loader->spark($spark, $autoload);
    }
    
    //--------------------------------------------------------------------
    
    
	//--------------------------------------------------------------------
	// HMVC Methods
	//--------------------------------------------------------------------

	 /**
     * Controller Loader
     *
     * This function lets users load and hierarchical controllers to enable HMVC support
     *
     * @param	string	the uri to the controller
     * @param	array	parameters for the requested method
     * @param	boolean return the result instead of showing it
     * @return	void
     */
    public function controller($uri, $params = array(), $return = FALSE)
    {
		// No valid module detected, add current module to uri
		list($module) = $this->detect_module($uri);
		if ( ! isset($module)) {
			$router = & $this->_ci_get_component('router');
			if ($router->fetch_module()) {
				$module = $router->fetch_module();
				$uri = "{$module}/{$uri}";
			}
		}

		return $this->_load_controller($uri, $params, $return);
	}

	//--------------------------------------------------------------------

	/**
     * HMVC Class Loader
     *
     * This function lets users load and instantiate classes.
     * It is designed to be called from a user's app controllers.
     *
     * @param	string	the name of the class
     * @param	mixed	the optional parameters
     * @param	string	an optional object name
     * @return	void
     */
	public function library($library = '', $params = NULL, $object_name = NULL)
	{
		if (is_array($library))
		{
			foreach ($library as $class)
			{
				$this->library($class, $params);
			}
			return;
		}

        global $RTR;

		// Detect module
        list($module, $class) = Modules::find($library, $RTR->fetch_module(), 'libraries');

		if ( $module)
		{
			$this->add_package_path( str_replace('libraries/', '', $module) );

			// Let original do the heavy work
			$void = $this->_library($class, $params, $object_name);

			$this->remove_package_path( str_replace('libaries', '', $module) );

			return $void;
		}
		else
		{
			return $this->_library($library, $params, $object_name);
		}
	}

    //--------------------------------------------------------------------

	/**
	* Model Loader
	*
	* This function lets users load and instantiate models.
	*
	* @param	string	the name of the class
	* @param	string	name for the model
	* @param	bool	database connection
	* @return	void
	*/
	public function model($model, $name = '', $db_conn = FALSE)
	{
		if (is_array($model))
		{
			foreach ($model as $babe)
			{
				$this->model($babe);
			}
			return;
		}

        global $RTR;

		// Detect module
		if (list($module, $class) = Modules::find(strtolower($model), $RTR->fetch_module(), 'models'))
		{
            $this->add_package_path( str_replace('models/', '', $module) );

			// Let parent do the heavy work
			$void = $this->_model($class, $name, $db_conn);

			$this->remove_package_path( str_replace('models/', '', $module) );

			return $void;
		}
		else
		{
			return $this->_model($model, $name, $db_conn);
		}
	}

	//--------------------------------------------------------------------

	/**
     * Load View
     *
     * This function is used to load a "view" file.  It has three parameters:
     *
     * 1. The name of the "view" file to be included.
     * 2. An associative array of data to be extracted for use in the view.
     * 3. TRUE/FALSE - whether to return the data or load it.  In
     * some cases it's advantageous to be able to return data so that
     * a developer can process it in some way.
     *
     * @param	string
     * @param	array
     * @param	bool
     * @return	void
     */
    public function view($view, $vars = array(), $return = FALSE)
    {
        list($path, $_view) = Modules::find($view, get_instance()->router->fetch_module(), 'views');

        if ($path != FALSE) {
            $this->_ci_view_paths = array($path => TRUE) + $this->_ci_view_paths;
            $view = $_view;
        }

        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    //--------------------------------------------------------------------

    /**
     * Loads a config file
     *
     * @param	string
     * @param	bool
     * @param 	bool
     * @return	void
     */
    public function config($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        global $RTR;

		// Detect module
		if (list($module, $class) = Modules::find($file, $RTR->fetch_module(), 'config'))
		{
			$this->add_package_path( str_replace('config/', '', $module) );

			// Let parent do the heavy work
			$void = $this->_config($class, $use_sections, $fail_gracefully);

			// Remove module
			$this->remove_package_path(str_replace('config/', '', $module));

			return $void;
		}
		else
		{
			$this->_config($file, $use_sections, $fail_gracefully);
		}
    }

    //--------------------------------------------------------------------

    /**
     * Load Helper
     *
     * This function loads the specified helper file.
     *
     * @param	mixed
     * @return	void
     */
    public function helper($helper = array())
    {
		if (is_array($helper))
		{
			foreach ($helper as $help)
			{
				$this->helper($help);
			}
			return;
		}

        global $RTR;

		// Detect module
		if (list($module, $class) = Modules::find($helper .'_helper', $RTR->fetch_module(), 'helpers'))
		{
            $this->add_package_path( str_replace('helpers/', '', $module) );

			// Let parent do the heavy work
			$void = $this->_helper($class);

			$this->remove_package_path( str_replace('helpers/', '', $module) );

			return $void;
		}
		else
		{
			return $this->_helper($helper);
		}
    }

    //--------------------------------------------------------------------

    /**
     * Loads a language file
     *
     * @param	array
     * @param	string
     * @return	void
     */
    public function language($file = array(), $lang = '')
    {
		if (is_array($file)) {
			foreach ($file as $langfile) {
				$this->language($langfile, $lang);
			}
			return;
		}

		// Detect module
		if (list($module, $class) = $this->detect_module($file)) {
			// Module already loaded
			if (in_array($module, $this->_ci_modules)) {
				return $this->_language($class, $lang);
			}

			// Add module
			$this->add_module($module);
			$void = $this->_language($class, $lang);

			// Remove module
			$this->remove_module($module);

			return $void;
		} else {
			return $this->_language($file, $lang);
		}
    }

    //--------------------------------------------------------------------

    /**
     * Add Module
     *
     * Allow resources to be loaded from this module path
     *
     * @param	string
     * @param 	boolean
     */
    public function add_module($module, $view_cascade = TRUE)
    {
		if ($path = $this->find_module($module))
		{
			// Mark module as loaded
			array_unshift($this->_ci_modules, $module);

			// Add package path
			$this->add_package_path($path, $view_cascade);
		}
    }

    //--------------------------------------------------------------------

    /**
     * Remove Module
     *
     * Remove a module from the allowed module paths
     *
     * @param	type
     * @param 	bool
     */
    public function remove_module($module = '', $remove_config = TRUE)
    {
		if ($module == '')
		{
			// Mark module as not loaded
			array_shift($this->_ci_modules);

			// Remove package path
			$this->remove_package_path('', $remove_config);
		}
		else if (($key = array_search($module, $this->_ci_modules)) !== FALSE)
		{
			if ($path = $this->find_module($module))
			{
				// Mark module as not loaded
				unset($this->_ci_modules[$key]);

				// Remove package path
				$this->remove_package_path($path, $remove_config);
			}
		}
    }

    //--------------------------------------------------------------------

    /**
     * Controller loader
     *
     * This function is used to load and instantiate controllers
     *
     * @param	string
     * @param	array
     * @param	boolean
     * @return	object
     */
    private function _load_controller($uri = '', $params = array(), $return = FALSE)
    {
		$router = & $this->_ci_get_component('router');

		// Back up current router values (before loading new controller)
		$backup = array();
		foreach (array('directory', 'class', 'method', 'module') as $prop)
		{
			$backup[$prop] = $router->{$prop};
		}

		// Locate the controller
		$segments = $router->locate(explode('/', $uri));
		$class = isset($segments[0]) ? $segments[0] : FALSE;
		$method = isset($segments[1]) ? $segments[1] : "index";

		// Controller not found
		if (!$class)
		{
			return;
		}

		if (!array_key_exists(strtolower($class), $this->_ci_controllers))
		{
            $routerDir = $router->fetch_directory();
            $filepath = "{$routerDir}{$class}.php";

            if (file_exists($filepath))
            {
                include_once($filepath);
            }

			// Determine filepath
			$filepath = APPPATH . 'controllers/' . $router->fetch_directory() . $class . '.php';

			// Load the controller file
			if (file_exists($filepath))
			{
				include_once ($filepath);
			}

            // Check for a Bonfire controller
            $filepath = BFPATH . 'controllers/' . $router->fetch_directory() . $class . '.php';

            if (file_exists($filepath))
            {
                include_once ($filepath);
            }

			// Controller class not found, show 404
			if (!class_exists($class))
			{
				show_404("{$class}/{$method}");
			}

			// Create a controller object
			$this->_ci_controllers[strtolower($class)] = new $class();
		}

		$controller = $this->_ci_controllers[strtolower($class)];

		// Method does not exists
		if (!method_exists($controller, $method))
		{
			show_404("{$class}/{$method}");
		}

		// Restore router state
		foreach ($backup as $prop => $value)
		{
			$router->{$prop} = $value;
		}

		// Capture output and return
		ob_start();
		$result = call_user_func_array(array($controller, $method), $params);

		// Return the buffered output
		if ($return === TRUE)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		// Close buffer and flush output to screen
		ob_end_flush();

		// Return controller return value
		return $result;
    }

    //--------------------------------------------------------------------

    /**
     * Detects the module from a string. Returns the module name and class if found.
     *
     * @param	string
     * @return	array|boolean
     */
    private function detect_module($class, $folder='')
    {
		$class = str_replace('.php', '', trim($class, '/'));

		if (($first_slash = strpos($class, '/')) === false) {
            $module = $class;
        } else {
			$module = substr($class, 0, $first_slash);
			$class = substr($class, $first_slash + 1);
        }

        // Check whether module exists by itself.
        if ($this->find_module($module)) {
            return array($module, $class);
        }

        $router_module = get_instance()->router->fetch_module();

        // Try the $router_module
        if ($this->find_module($router_module)) {
            return array($router_module, $module);
        }

		return false;
    }

    //--------------------------------------------------------------------

	/**
	* Searches a given module name. Returns the path if found, FALSE otherwise
    *
    * If a sub_path is provided, will look in the module for that specific location.
    * If the location exists it returns TRUE, otherwise false. Good for verifying
    * that a folder or file exists at that location.
    *
	* @param string $module    The name of the module to search for.
    * @param strign $sub_path  The name of the folder/file combo to search for.
	* @return string|boolean
	*/
	public function find_module($module, $sub_path='')
	{
/*
		$config = & $this->_ci_get_component('config');

		// Check all locations for this module
		foreach ($config->item('modules_locations') as $location)
		{
			$path = $location . rtrim($module, '/') . '/';

			if (file_exists($path . $sub_path))
			{
				return $path;
			}
		}

		return FALSE;
 */
        return Modules::path($module, $sub_path);
	}

    //--------------------------------------------------------------------

	/**
	 * Adds a path to the list of views that are searched for. Makes it simpler
	 * for the rendering engine.
	 *
	 * Unlike 'package paths', this does not require the view path to actually
	 * end in 'views/'.
	 *
	 * @param string  $path         The full server path to look into.
	 * @param boolean $view_cascade [description]
	 */
	public function add_view_path($path, $view_cascade=TRUE)
	{
		$path = rtrim($path, '/').'/';

		if ( ! isset($this->_ci_view_paths[$path]) )
		{
			$this->_ci_view_paths = array($path => $view_cascade) + $this->_ci_view_paths;
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Removes an arbitrary view path from the view arrays.
	 *
	 * @param  string $path The path to remove
	 * @return [type]       [description]
	 */
	public function remove_view_path($path)
	{
		$path = rtrim($path, '/').'/';

		if (isset($this->_ci_view_paths[$path]))
		{
			unset($this->_ci_view_paths[$path]);
		}
	}

	//--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // ORIGINAL CI METHODS
    //--------------------------------------------------------------------

	/**
	 * CodeIgniter's Original Class Loader
	 *
	 * This function lets users load and instantiate classes.
	 * It is designed to be called from a user's app controllers.
	 *
	 * @param	string	the name of the class
	 * @param	mixed	the optional parameters
	 * @param	string	an optional object name
	 * @return	void
	 */
	public function _library($library = '', $params = NULL, $object_name = NULL)
	{
		if ($library == '' OR isset($this->_base_classes[$library]))
		{
			return FALSE;
		}

		if ( ! is_null($params) && ! is_array($params))
		{
			$params = NULL;
		}

		$this->_ci_load_class($library, $params, $object_name);
	}

	// --------------------------------------------------------------------

	/**
	 * Model Loader
	 *
	 * This function lets users load and instantiate models.
	 *
	 * @param	string	the name of the class
	 * @param	string	name for the model
	 * @param	bool	database connection
	 * @return	void
	 */
	public function _model($model, $name = '', $db_conn = FALSE)
	{
		if (is_array($model))
		{
			foreach ($model as $babe)
			{
				$this->model($babe);
			}
			return;
		}

		if ($model == '')
		{
			return;
		}

		$path = '';

		// Is the model in a sub-folder? If so, parse out the filename and path.
		if (($last_slash = strrpos($model, '/')) !== FALSE)
		{
			// The path is in front of the last slash
			$path = substr($model, 0, $last_slash + 1);

			// And the model name behind it
			$model = substr($model, $last_slash + 1);
		}

		if ($name == '')
		{
			$name = $model;
		}

		if (in_array($name, $this->_ci_models, TRUE))
		{
			return;
		}

		$CI =& get_instance();
		if (isset($CI->$name))
		{
			show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
		}

		$model = strtolower($model);

		foreach ($this->_ci_model_paths as $mod_path)
		{
			if ( ! file_exists($mod_path.'models/'.$path.$model.'.php'))
			{
				continue;
			}

			if ($db_conn !== FALSE AND ! class_exists('CI_DB'))
			{
				if ($db_conn === TRUE)
				{
					$db_conn = '';
				}

				$CI->load->database($db_conn, FALSE, TRUE);
			}

			if ( ! class_exists('CI_Model'))
			{
				load_class('Model', 'core');
			}

			require_once($mod_path.'models/'.$path.$model.'.php');

			$model = ucfirst($model);

			$CI->$name = new $model();

			$this->_ci_models[] = $name;
			return;
		}

		// couldn't find the model
		show_error('Unable to locate the model you have specified: '.$model);
	}

	// --------------------------------------------------------------------

	/**
	 * Database Loader
	 *
	 * @param	string	the DB credentials
	 * @param	bool	whether to return the DB object
	 * @param	bool	whether to enable active record (this allows us to override the config setting)
	 * @return	object
	 */
	public function database($params = '', $return = FALSE, $active_record = NULL)
	{
		// Grab the super object
		$CI =& get_instance();

		// Do we even need to load the database class?
		if (class_exists('CI_DB') AND $return == FALSE AND $active_record == NULL AND isset($CI->db) AND is_object($CI->db))
		{
			return FALSE;
		}

		require_once(BASEPATH.'database/DB.php');

		if ($return === TRUE)
		{
			return DB($params, $active_record);
		}

		// Initialize the db variable.  Needed to prevent
		// reference errors with some configurations
		$CI->db = '';

		// Load the DB class
		$CI->db =& DB($params, $active_record);
	}

	// --------------------------------------------------------------------

	/**
	 * Load the Utilities Class
	 *
	 * @return	string
	 */
	public function dbutil()
	{
		if ( ! class_exists('CI_DB'))
		{
			$this->database();
		}

		$CI =& get_instance();

		// for backwards compatibility, load dbforge so we can extend dbutils off it
		// this use is deprecated and strongly discouraged
		$CI->load->dbforge();

		require_once(BASEPATH.'database/DB_utility.php');
		require_once(BASEPATH.'database/drivers/'.$CI->db->dbdriver.'/'.$CI->db->dbdriver.'_utility.php');
		$class = 'CI_DB_'.$CI->db->dbdriver.'_utility';

		$CI->dbutil = new $class();
	}

	// --------------------------------------------------------------------

	/**
	 * Load the Database Forge Class
	 *
	 * @return	string
	 */
	public function dbforge()
	{
		if ( ! class_exists('CI_DB'))
		{
			$this->database();
		}

		$CI =& get_instance();

		require_once(BASEPATH.'database/DB_forge.php');
		require_once(BASEPATH.'database/drivers/'.$CI->db->dbdriver.'/'.$CI->db->dbdriver.'_forge.php');
		$class = 'CI_DB_'.$CI->db->dbdriver.'_forge';

		$CI->dbforge = new $class();
	}

	// --------------------------------------------------------------------

	/**
	 * Load File
	 *
	 * This is a generic file loader
	 *
	 * @param	string
	 * @param	bool
	 * @return	string
	 */
	public function file($path, $return = FALSE)
	{
		return $this->_ci_load(array('_ci_path' => $path, '_ci_return' => $return));
	}

	// --------------------------------------------------------------------

	/**
	 * Set Variables
	 *
	 * Once variables are set they become available within
	 * the controller class and its "view" files.
	 *
	 * @param	array
	 * @param 	string
	 * @return	void
	 */
	public function vars($vars = array(), $val = '')
	{
		if ($val != '' AND is_string($vars))
		{
			$vars = array($vars => $val);
		}

		$vars = $this->_ci_object_to_array($vars);

		if (is_array($vars) AND count($vars) > 0)
		{
			foreach ($vars as $key => $val)
			{
				$this->_ci_cached_vars[$key] = $val;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Get Variable
	 *
	 * Check if a variable is set and retrieve it.
	 *
	 * @param	array
	 * @return	void
	 */
	public function get_var($key)
	{
		return isset($this->_ci_cached_vars[$key]) ? $this->_ci_cached_vars[$key] : NULL;
	}

	// --------------------------------------------------------------------

    /**
     * Returns all of the currently cached vars since _ci_cached_vars is
     * now a protected var.
     *
     * @return array
     */
    public function get_vars ()
    {
        return $this->_ci_cached_vars;
    }

    //--------------------------------------------------------------------


	/**
	 * Load Helper
	 *
	 * This function loads the specified helper file.
	 *
	 * @param	mixed
	 * @return	void
	 */
	public function _helper($helpers = array())
	{
		foreach ($this->_ci_prep_filename($helpers, '_helper') as $helper)
		{
			if (isset($this->_ci_helpers[$helper]))
			{
				continue;
			}

			$bf_helper = BFPATH.'helpers/BF_'.$helper.'.php';

			$ext_helper = APPPATH.'helpers/'.config_item('subclass_prefix').$helper.'.php';

			// Is this a helper extension request?
			if (file_exists($ext_helper))
			{
				$base_helper = BASEPATH.'helpers/'.$helper.'.php';

				if ( ! file_exists($base_helper))
				{
					show_error('Unable to load the requested file: helpers/'.$helper.'.php');
				}

                // Load the MY_* version of the helper.
				include_once($ext_helper);

                // Load the BF_version of the helper after the MY_* version
                // so that any MY_ functions overload the BF_* versions.
				if (file_exists($bf_helper))
				{
					include_once($bf_helper);
				}
				include_once($base_helper);

				$this->_ci_helpers[$helper] = TRUE;
				log_message('debug', 'Helper loaded: '.$helper);
				continue;
			}

            // If we're here then there wasn't a MY_* version, so
            // try to load the BF_* version again.
            if (file_exists($bf_helper))
            {
                include_once($bf_helper);
            }

			// Try to load the helper
			foreach ($this->_ci_helper_paths as $path)
			{
				if (file_exists($path.'helpers/'.$helper.'.php'))
				{
					include_once($path.'helpers/'.$helper.'.php');

					// Load the BF_helper version if it exists
					if (file_exists($bf_helper))
					{
						include_once($bf_helper);
					}

					$this->_ci_helpers[$helper] = TRUE;
					log_message('debug', 'Helper loaded: '.$helper);
					break;
				}
			}

			// Still here? Try to load the BF_helper
			if (file_exists($bf_helper))
			{
				include_once($bf_helper);
				return;
			}

			// unable to load the helper
			if ( ! isset($this->_ci_helpers[$helper]))
			{
				show_error('Unable to load the requested file: helpers/'.$helper.'.php');
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Load Helpers
	 *
	 * This is simply an alias to the above function in case the
	 * user has written the plural form of this function.
	 *
	 * @param	array
	 * @return	void
	 */
	public function helpers($helpers = array())
	{
		$this->helper($helpers);
	}

	// --------------------------------------------------------------------

	/**
	 * Loads a language file
	 *
	 * @param	array
	 * @param	string
	 * @return	void
	 */
	public function _language($file = array(), $lang = '')
	{
		$CI =& get_instance();

		if ( ! is_array($file))
		{
			$file = array($file);
		}

		foreach ($file as $langfile)
		{
			$CI->lang->load($langfile, $lang);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Loads a config file
	 *
	 * @param	string
	 * @param	bool
	 * @param 	bool
	 * @return	void
	 */
	public function _config($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		$CI =& get_instance();
		$CI->config->load($file, $use_sections, $fail_gracefully);
	}

	// --------------------------------------------------------------------

	/**
	 * Driver
	 *
	 * Loads a driver library
	 *
	 * @param	string	the name of the class
	 * @param	mixed	the optional parameters
	 * @param	string	an optional object name
	 * @return	void
	 */
	public function driver($library = '', $params = NULL, $object_name = NULL)
	{
		if ( ! class_exists('CI_Driver_Library'))
		{
			// we aren't instantiating an object here, that'll be done by the Library itself
			require BASEPATH.'libraries/Driver.php';
		}

		if ($library == '')
		{
			return FALSE;
		}

		// We can save the loader some time since Drivers will *always* be in a subfolder,
		// and typically identically named to the library
		if ( ! strpos($library, '/'))
		{
			$library = ucfirst($library).'/'. ucfirst($library);
		}

		return $this->library($library, $params, $object_name);
	}

	// --------------------------------------------------------------------

	/**
	 * Add Package Path
	 *
	 * Prepends a parent path to the library, model, helper, and config path arrays
	 *
	 * @param	string
	 * @param 	boolean
	 * @return	void
	 */
	public function add_package_path($path, $view_cascade=TRUE)
	{
		$path = rtrim($path, '/').'/';

		array_unshift($this->_ci_library_paths, $path);
		array_unshift($this->_ci_model_paths, $path);
		array_unshift($this->_ci_helper_paths, $path);

		$this->_ci_view_paths = $this->_ci_view_paths + array($path.'views/' => $view_cascade);

		// Add config file path
		$config =& $this->_ci_get_component('config');
		array_unshift($config->_config_paths, $path);
	}

	// --------------------------------------------------------------------

	/**
	 * Get Package Paths
	 *
	 * Return a list of all package paths, by default it will ignore BASEPATH.
	 *
	 * @param	string
	 * @return	void
	 */
	public function get_package_paths($include_base = FALSE)
	{
		return $include_base === TRUE ? $this->_ci_library_paths : $this->_ci_model_paths;
	}

	// --------------------------------------------------------------------

	/**
	 * Remove Package Path
	 *
	 * Remove a path from the library, model, and helper path arrays if it exists
	 * If no path is provided, the most recently added path is removed.
	 *
	 * @param	type
	 * @param 	bool
	 * @return	type
	 */
	public function remove_package_path($path = '', $remove_config_path = TRUE)
	{
		$config =& $this->_ci_get_component('config');

		if ($path == '')
		{
			$void = array_shift($this->_ci_library_paths);
			$void = array_shift($this->_ci_model_paths);
			$void = array_shift($this->_ci_helper_paths);
			$void = array_shift($this->_ci_view_paths);
			$void = array_shift($config->_config_paths);
		}
		else
		{
			$path = rtrim($path, '/').'/';
			foreach (array('_ci_library_paths', '_ci_model_paths', '_ci_helper_paths') as $var)
			{
				if (($key = array_search($path, $this->{$var})) !== FALSE)
				{
					unset($this->{$var}[$key]);
				}
			}

			if (isset($this->_ci_view_paths[$path.'views/']))
			{
				unset($this->_ci_view_paths[$path.'views/']);
			}

			if (($key = array_search($path, $config->_config_paths)) !== FALSE)
			{
				unset($config->_config_paths[$key]);
			}
		}

		// make sure the application default paths are still in the array
		$this->_ci_library_paths = array_unique(array_merge($this->_ci_library_paths, array(APPPATH, BASEPATH)));
		$this->_ci_helper_paths = array_unique(array_merge($this->_ci_helper_paths, array(APPPATH, BASEPATH)));
		$this->_ci_model_paths = array_unique(array_merge($this->_ci_model_paths, array(APPPATH)));
		$this->_ci_view_paths = array_merge($this->_ci_view_paths, array(APPPATH.'views/' => TRUE));
		$config->_config_paths = array_unique(array_merge($config->_config_paths, array(APPPATH)));
	}

	// --------------------------------------------------------------------

	/**
	 * Loader
	 *
	 * This function is used to load views and files.
	 * Variables are prefixed with _ci_ to avoid symbol collision with
	 * variables made available to view files
	 *
	 * @param	array
	 * @return	void
	 */
	protected function _ci_load($_ci_data)
	{
		// Set the default data variables
		foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val)
		{
			$$_ci_val = ( ! isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
		}

		$file_exists = FALSE;

		// Set the path to the requested file
		if ($_ci_path != '')
		{
			$_ci_x = explode('/', $_ci_path);
			$_ci_file = end($_ci_x);
		}
		else
		{
			$_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
			$_ci_file = ($_ci_ext == '') ? $_ci_view.'.php' : $_ci_view;

			foreach ($this->_ci_view_paths as $view_file => $cascade)
			{

                // Check with the path added in.
				if (file_exists($view_file.$_ci_file))
				{
					$_ci_path = $view_file.$_ci_file;
					$file_exists = TRUE;
					break;
				}

                // Check without the path in case a full
                // server path is passed in.
                if (file_exists($_ci_file))
                {
                    $_ci_path = $_ci_file;
                    $file_exists = TRUE;
                    break;
                }

                // check without the first segment

				if ( ! $cascade)
				{
					break;
				}
			}
		}

		if ( ! $file_exists && ! file_exists($_ci_path))
		{
			show_error('Unable to load the requested file: '.$_ci_file);
		}

		// This allows anything loaded using $this->load (views, files, etc.)
		// to become accessible from within the Controller and Model functions.

		$_ci_CI =& get_instance();
		foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
		{
			if ( ! isset($this->$_ci_key))
			{
				$this->$_ci_key =& $_ci_CI->$_ci_key;
			}
		}

		/*
		 * Extract and cache variables
		 *
		 * You can either set variables using the dedicated $this->load_vars()
		 * function or via the second parameter of this function. We'll merge
		 * the two types and cache them so that views that are embedded within
		 * other views can have access to these variables.
		 */
		if (is_array($_ci_vars))
		{
			$this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
		}
		extract($this->_ci_cached_vars);

		/*
		 * Buffer the output
		 *
		 * We buffer the output for two reasons:
		 * 1. Speed. You get a significant speed boost.
		 * 2. So that the final rendered template can be
		 * post-processed by the output class.  Why do we
		 * need post processing?  For one thing, in order to
		 * show the elapsed page load time.  Unless we
		 * can intercept the content right before it's sent to
		 * the browser and then stop the timer it won't be accurate.
		 */
		ob_start();

		// If the PHP installation does not support short tags we'll
		// do a little string replacement, changing the short tags
		// to standard PHP echo statements.

		if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE)
		{
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
		}
		else
		{
			include($_ci_path); // include() vs include_once() allows for multiple views with the same name
		}

		log_message('debug', 'File loaded: '.$_ci_path);

		// Return the file data if requested
		if ($_ci_return === TRUE)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		/*
		 * Flush the buffer... or buff the flusher?
		 *
		 * In order to permit views to be nested within
		 * other views, we need to flush the content back out whenever
		 * we are beyond the first level of output buffering so that
		 * it can be seen and included properly by the first included
		 * template and any subsequent ones. Oy!
		 *
		 */
		if (ob_get_level() > $this->_ci_ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
			$_ci_CI->output->append_output(ob_get_contents());
			@ob_end_clean();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Load class
	 *
	 * This function loads the requested class.
	 *
	 * @param	string	the item that is being loaded
	 * @param	mixed	any additional parameters
	 * @param	string	an optional object name
	 * @return	void
	 */
	protected function _ci_load_class($class, $params = NULL, $object_name = NULL)
	{
		// Get the class name, and while we're at it trim any slashes.
		// The directory path can be included as part of the class name,
		// but we don't want a leading slash
		$class = str_replace('.php', '', trim($class, '/'));

		// Was the path included with the class name?
		// We look for a slash to determine this
		$subdir = '';
		if (($last_slash = strrpos($class, '/')) !== FALSE)
		{
			// Extract the path
			$subdir = substr($class, 0, $last_slash + 1);

			// Get the filename from the path
			$class = substr($class, $last_slash + 1);
		}

		// We'll test for both lowercase and capitalized versions of the file name
		foreach (array(ucfirst($class), strtolower($class)) as $class)
		{
			$subclass = APPPATH.'libraries/'.$subdir.config_item('subclass_prefix').$class.'.php';

			// Is this a class extension request?
			if (file_exists($subclass))
			{
				$baseclass = BASEPATH.'libraries/'.ucfirst($class).'.php';

				if ( ! file_exists($baseclass))
				{
					log_message('error', "Unable to load the requested class: ".$class);
					show_error("Unable to load the requested class: ".$class);
				}

				// Safety:  Was the class already loaded by a previous call?
				if (in_array($subclass, $this->_ci_loaded_files))
				{
					// Before we deem this to be a duplicate request, let's see
					// if a custom object name is being supplied.  If so, we'll
					// return a new instance of the object
					if ( ! is_null($object_name))
					{
						$CI =& get_instance();
						if ( ! isset($CI->$object_name))
						{
							return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
						}
					}

					$is_duplicate = TRUE;
					log_message('debug', $class." class already loaded. Second attempt ignored.");
					return;
				}

				include_once($baseclass);

				include_once($subclass);
				$this->_ci_loaded_files[] = $subclass;

				return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
			}

			// Lets search for the requested library file and load it.
			$is_duplicate = FALSE;
			foreach ($this->_ci_library_paths as $path)
			{
				$filepath = $path.'libraries/'.$subdir.$class.'.php';

				// Does the file exist?  No?  Bummer...
				if ( ! file_exists($filepath))
				{
					continue;
				}

				// Safety:  Was the class already loaded by a previous call?
				if (in_array($filepath, $this->_ci_loaded_files))
				{
					// Before we deem this to be a duplicate request, let's see
					// if a custom object name is being supplied.  If so, we'll
					// return a new instance of the object
					if ( ! is_null($object_name))
					{
						$CI =& get_instance();
						if ( ! isset($CI->$object_name))
						{
							return $this->_ci_init_class($class, '', $params, $object_name);
						}
					}

					$is_duplicate = TRUE;
					log_message('debug', $class." class already loaded. Second attempt ignored.");
					return;
				}

                $prefix = '';

                // Load the codeigniter version of the file.
				include_once($filepath);
                $this->_ci_loaded_files[] = $filepath;

                // Try to load a Bonfire version of the file too (BF_*)
                $bfclass = BFPATH.'libraries/BF_'.$class.'.php';
                if (file_exists($bfclass))
                {
                    include_once($bfclass);
                    $this->_ci_loaded_files[] = $bfclass;
                    $prefix = 'BF_';
                }


				return $this->_ci_init_class($class, $prefix, $params, $object_name);
			}

		} // END FOREACH

		// One last attempt.  Maybe the library is in a subdirectory, but it wasn't specified?
		if ($subdir == '')
		{
			$path = strtolower($class).'/'.$class;
			return $this->_ci_load_class($path, $params);
		}

		// If we got this far we were unable to find the requested class.
		// We do not issue errors if the load call failed due to a duplicate request
		if ($is_duplicate == FALSE)
		{
			log_message('error', "Unable to load the requested class: ".$class);
			show_error("Unable to load the requested class: ".$class);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Instantiates a class
	 *
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @param	string	an optional object name
	 * @return	null
	 */
	protected function _ci_init_class($class, $prefix = '', $config = FALSE, $object_name = NULL)
	{
		// Is there an associated config file for this class?  Note: these should always be lowercase
		if ($config === NULL)
		{
			// Fetch the config paths containing any package paths
			$config_component = $this->_ci_get_component('config');

			if (is_array($config_component->_config_paths))
			{
				// Break on the first found file, thus package files
				// are not overridden by default paths
				foreach ($config_component->_config_paths as $path)
				{
					// We test for both uppercase and lowercase, for servers that
					// are case-sensitive with regard to file names. Check for environment
					// first, global next
					if (defined('ENVIRONMENT') AND file_exists($path .'config/'.ENVIRONMENT.'/'.strtolower($class).'.php'))
					{
						include($path .'config/'.ENVIRONMENT.'/'.strtolower($class).'.php');
						break;
					}
					elseif (defined('ENVIRONMENT') AND file_exists($path .'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).'.php'))
					{
						include($path .'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).'.php');
						break;
					}
					elseif (file_exists($path .'config/'.strtolower($class).'.php'))
					{
						include($path .'config/'.strtolower($class).'.php');
						break;
					}
					elseif (file_exists($path .'config/'.ucfirst(strtolower($class)).'.php'))
					{
						include($path .'config/'.ucfirst(strtolower($class)).'.php');
						break;
					}
				}
			}
		}

		if ($prefix == '')
		{
			if (class_exists('CI_'.$class))
			{
				$name = 'CI_'.$class;
			}
			elseif (class_exists(config_item('subclass_prefix').$class))
			{
				$name = config_item('subclass_prefix').$class;
			}
            elseif (class_exists('BF_'. $class))
            {
                $name = 'BF_'. $class;
            }
			else
			{
				$name = $class;
			}
		}
		else
		{
			$name = $prefix.$class;
		}

		// Is the class name valid?
		if ( ! class_exists($name))
		{
			log_message('error', "Non-existent class: ".$name);
			show_error("Non-existent class: ".$class);
		}

		// Set the variable name we will assign the class to
		// Was a custom class name supplied?  If so we'll use it
		$class = strtolower($class);

		if (is_null($object_name))
		{
			$classvar = ( ! isset($this->_ci_varmap[$class])) ? $class : $this->_ci_varmap[$class];
		}
		else
		{
			$classvar = $object_name;
		}

		// Save the class name and object name
		$this->_ci_classes[$class] = $classvar;

		// Instantiate the class
		$CI =& get_instance();
		if ($config !== NULL)
		{
			$CI->$classvar = new $name($config);
		}
		else
		{
			$CI->$classvar = new $name;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Autoloader
	 *
	 * The config/autoload.php file contains an array that permits sub-systems,
	 * libraries, and helpers to be loaded automatically.
	 *
	 * @param	array
	 * @return	void
	 */
	private function _ci_autoloader()
	{
		if (defined('ENVIRONMENT') AND file_exists(APPPATH.'config/'.ENVIRONMENT.'/autoload.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/autoload.php');
		}
		else
		{
			include(APPPATH.'config/autoload.php');
		}

		if ( ! isset($autoload))
		{
			return FALSE;
		}

		// Autoload packages
		if (isset($autoload['packages']))
		{
			foreach ($autoload['packages'] as $package_path)
			{
				$this->add_package_path($package_path);
			}
		}

		// Load any custom config file
		if (count($autoload['config']) > 0)
		{
			$CI =& get_instance();
			foreach ($autoload['config'] as $key => $val)
			{
				$CI->config->load($val);
			}
		}

		// Autoload helpers and languages
		foreach (array('helper', 'language') as $type)
		{
			if (isset($autoload[$type]) AND count($autoload[$type]) > 0)
			{
				$this->$type($autoload[$type]);
			}
		}

		// A little tweak to remain backward compatible
		// The $autoload['core'] item was deprecated
		if ( ! isset($autoload['libraries']) AND isset($autoload['core']))
		{
			$autoload['libraries'] = $autoload['core'];
		}

		// Load libraries
		if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0)
		{
			// Load the database driver.
			if (in_array('database', $autoload['libraries']))
			{
				$this->database();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
			}

			// Load all other libraries
			foreach ($autoload['libraries'] as $item)
			{
				$this->library($item);
			}
		}

		// Autoload models
		if (isset($autoload['model']))
		{
			$this->model($autoload['model']);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Object to Array
	 *
	 * Takes an object as input and converts the class variables to array key/vals
	 *
	 * @param	object
	 * @return	array
	 */
	protected function _ci_object_to_array($object)
	{
		return (is_object($object)) ? get_object_vars($object) : $object;
	}

	// --------------------------------------------------------------------

	/**
	 * Get a reference to a specific library or model
	 *
	 * @param 	string
	 * @return	bool
	 */
	protected function &_ci_get_component($component)
	{
		$CI =& get_instance();
		return $CI->$component;
	}

	// --------------------------------------------------------------------

	/**
	 * Prep filename
	 *
	 * This function preps the name of various items to make loading them more reliable.
	 *
	 * @param	mixed
	 * @param 	string
	 * @return	array
	 */
	protected function _ci_prep_filename($filename, $extension)
	{
		if ( ! is_array($filename))
		{
			return array(strtolower(str_replace('.php', '', str_replace($extension, '', $filename)).$extension));
		}
		else
		{
			foreach ($filename as $key => $val)
			{
				$filename[$key] = strtolower(str_replace('.php', '', str_replace($extension, '', $val)).$extension);
			}

			return $filename;
		}
	}
}

/* End of file Loader.php */
/* Location: ./system/core/Loader.php */