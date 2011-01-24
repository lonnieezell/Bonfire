<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Template Class
 *
 * The Template class makes the creation of consistently themed web pages across your
 * entire site simple and as automatic as possible.
 * 
 * @author Lonnie Ezell
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @package Ocular Layout Library
 * @version 3.0a
 */
 
/*
	CHANGES: 
		- Modified get() method to also return class methods, if they exist.
		- Added a set_view method since setting it as a public class member was 
			a) wrong and b) not working with the static class.
*/
class Template {

	private static $debug = false;

	/**
	 * Stores the name of the active theme (folder)
	 * with a trailing slash. 
	 * 
	 * (default value: '')
	 * 
	 * @var string
	 * @access protected
	 */
	protected static $active_theme = '';
	
	/**
	 * Stores the default theme from the config file
	 * for a slight performance increase.
	 *
	 * @var string
	 * @access protected
	 */
	 protected static $default_theme = '';

	/**
	 * The view to load. Normally not set unless
	 * you need to bypass the automagic.
	 * 
	 * @var mixed
	 * @access public
	 */
	protected static $current_view;
	
	/**
	 * The layout to render the views into.
	 * 
	 * @var mixed
	 * @access public
	 */
	public static $layout;
	
	/**
	 * parse_views
	 * 
	 * If true, CodeIgniter's Template Parser will be used to 
	 * parse the view. If false, the view is displayed with
	 * no parsing. Used by the yield() and block() 
	 * 
	 * @var mixed
	 * @access public
	 */
	public static $parse_views = false;
	
	/**
	 * The data to be passed into the views.
	 * The keys are the names of the variables
	 * and the values are the values.
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected static $data = array();
	
	/**
	 * An array of blocks. The key is the name
	 * to reference it by, and the value is the file.
	 * The class will loop through these, parse them,
	 * and push them into the layout.
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access protected
	 */
	protected static $blocks = array();
	
	/**
	 * Holds a simple array to store the status Message
	 * that gets displayed using the message() function.
	 *
	 * @var array
	 * @access protected
	 */
	protected static $message;

	/**
	 * An array of paths to look for themes.
	 *
	 * @var array
	 * @access protected
	 */
	protected static $theme_paths	= array();	
	
	/**
	 * The full server path to the site root.
	 */
	public static $site_path;
	
	/**
	 * Stores CI's default view path.
	 */
	protected static $orig_view_path;
	
	/**
	 * An instance of the CI super object.
	 * 
	 * @var mixed
	 * @access private
	 */
	private static $ci;

	//--------------------------------------------------------------------

	/**
	 * __construct function.
	 *
	 * This is purely here for CI's benefit. 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() 
	{	
		self::$ci =& get_instance();
		
		self::init();
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * init function
	 * 
	 * Grabs an instance of the CI superobject, loads the Ocular config
	 * file, and sets our default layout.
	 *
	 * @access 	public
	 * @return	void
	 */
	public static function init() 
	{
		// If the application config file hasn't been loaded, do it now
		if (!self::$ci->config->item('template.theme_paths'))
		{ 
			self::$ci->config->load('template');
		}
		
		// Store our settings
		self::$site_path 		= self::$ci->config->item('template.site_path');
		self::$theme_paths 		= self::$ci->config->item('template.theme_paths');
		self::$layout 			= self::$ci->config->item('template.default_layout');
		self::$default_theme 	= self::$ci->config->item('template.default_theme');
		self::$parse_views		= self::$ci->config->item('template.parse_views');
		
		// Store our orig view path, so we can reset it
		self::$orig_view_path = self::$ci->load->_ci_view_path;
		
		log_message('debug', 'Template library loaded');
	}
	
	//--------------------------------------------------------------------
	
	
	/**
	 * render function.
	 *
	 * Renders out the specified layout, which starts the process
	 * of rendering the page content. Also determines the correct
	 * view to use based on the current controller/method.
	 * 
	 * @access public
	 * @param 	string 	$layout. (default: '')
	 * @return void
	 */
	public static function render($layout=null) 
	{
		$output = '';
	
		// We need to know which layout to render
		$layout = empty($layout) ? self::$layout : $layout;

		// Is it in an AJAX call? If so, override the layout
		/*if ($this->is_ajax())
		{
			$layout = self::$ci->config->item('template.ajax_layout');
			self::$ci->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
			self::$ci->output->set_header("Cache-Control: post-check=0, pre-check=0");
			self::$ci->output->set_header("Pragma: no-cache"); 
		}*/
		
		// Grab our current view name, based on controller/method
		// which routes to views/controller/method.
		if (empty(self::$current_view))
		{		
			self::$current_view =  self::$ci->router->class . '/' . self::$ci->router->method;
		}
		
		//
		// Time to render the layout
		//
		self::load_view($layout, self::$data, self::$ci->router->class, true, $output);
		
		if (empty($output)) { show_error('Unable to find theme layout: '. $layout); }
		
		global $OUT;
		$OUT->set_output($output); 
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Renders the current page. 
	 *
	 * Uses a view based on the controller/function being run. (See __constructor).
	 * 
	 * @access public
	 * @return void
	 */
	public static function yield() 
	{ 	
		$output = '';
		
		if (self::$debug) { echo 'Current View = '. self::$current_view; }

		self::load_view(self::$current_view, null, self::$ci->router->class .'/'. self::$ci->router->method, false, $output);
		
		return $output;
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !BLOCKS
	//--------------------------------------------------------------------
	
	/**
	 * Stores the block named $name in the blocks array for later rendering.
	 * The $current_view variable is the name of an existing view. If it is empty,
	 * your script should still function as normal.
	 * 
	 * @access public
	 * @param string $name. (default: '')
	 * @param string $view. (default: '')
	 * @return void
	 */
	public static function set_block($block_name='', $view_name='') 
	{		
		if (!empty($block_name))
		{
			self::$blocks[$block_name] = $view_name;
		} 
		
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Renders a "block" to the view.
	 *
	 * A block is a partial view contained in a view file in the 
	 * application/views folder. It can be used for sidebars,
	 * headers, footers, or any other recurring element within
	 * a site. It is recommended to set a default when calling
	 * this function within a layout. The default will be rendered
	 * if no methods override the view (using the set_block() method).
	 * 
	 * @access public
	 * @param string $name. (default: '')
	 * @param string $default_view. (default: '')
	 * @return void
	 */
	public static function block($block_name='', $default_view='', $data=array(), $themed=false)
	{		
		if (empty($block_name)) 
		{
			log_message('debug', '[Template] No block name provided.');
			return;
		}

		// If a block has been set previously use that
		if (isset(self::$blocks[$block_name]))
		{
			$block_name = self::$blocks[$block_name];
		} 
		// Otherwise, use the default view.
		else 
		{
			$block_name = $default_view;
		}

		if (self::$debug) { echo "Looking for block: <b>{$block_name}</b>."; }

		if (empty($block_name)) 
		{ 
			log_message('debug', 'Ocular was unable to find the default block: ' . $default_view);
			return;
		}
		
		self::load_view($block_name, $data, false, $themed, $output);
		
		return $output;
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !THEME PATHS
	//--------------------------------------------------------------------
	
	/**
	 * add_theme_path method
	 * 
	 * Theme paths allow you to have multiple locations for themes to be
	 * stored. This might be used for separating themes for different sub-
	 * applications, or a core theme and user-submitted themes.
	 *
	 * @param	string	$path	A new path where themes can be found.
	 */
	public static function add_theme_path($path=null) 
	{
		if (empty($path) || !is_string($path))
		{
			return false;
		}
		
		// Make sure the path has a '/' at the end.
		if (substr($path, -1) != '/')
		{
			$path .= '/';
		}
		
		// If the path already exists, we're done here.
		if (isset(self::$theme_paths[$path]))
		{
			return true;
		}
		
		// Make sure the folder actually exists
		if (is_dir(FCPATH . $path))
		{
			array_push(self::$theme_paths, $path);
			return false;
		} else 
		{
			log_message('debug', "[Template] Cannot add theme folder: $path does not exist");
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * remove_theme_path method
	 *
	 * @param	string	$path	The path to remove from the theme paths.
	 * @return	void
	 */
	public static function remove_theme_path($path=null) 
	{
		if (empty($path) || !is_string($path))
		{
			return;
		}
		
		if (isset(self::$theme_paths[$path]))
		{
			unset(self::$theme_paths[$path]);
		}
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * set_theme method
	 *
	 * Stores the name of the active theme to use. This theme should be
	 * relative to one of the 'template.theme_paths' folders.
	 *
	 * @access	public
	 * @param	string	$theme	The name of the active theme
	 * @return	void
	 */
	public static function set_theme($theme=null) 
	{
		if (empty($theme) || !is_string($theme))
		{
			return;
		}

		// Make sure a trailing slash is there
		if (substr($theme, -1) !== '/')
		{
			$theme .= '/';
		}

		self::$active_theme = $theme;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Returns the active theme.
	 * 
	 * @return	string	The name of the active theme.
	 */
	public static function theme() 
	{
		return self::$active_theme;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	Returns the full url to a file in the currently active theme.
	 *
	 * @return	string	The full url (including http://) to the resource.
	 */
	public static function theme_url($resource='') 
	{
		$url = base_url();
		
		// Add theme path
		$url .= self::$theme_paths[0] .'/';
		
		// Add theme
		$url .= empty(self::$active_theme) ? self::$default_theme : self::$active_theme;
		
		// Cleanup, just to be safe
		$url = str_replace('//', '/', $url);
		$url = str_replace(':/', '://', $url);
		
		return $url . $resource;
	}
	
	//--------------------------------------------------------------------
	
	
	/**
	 * Set the current view to render.
	 * 
	 * @param	string	$view	The name of the view file to render as content.
	 * @return	void
	 */
	public function set_view($view=null) 
	{
		if (empty($view) || !is_string($view))
		{
			return;
		}
		
		self::$current_view = $view;
	}
	
	//--------------------------------------------------------------------
	
	
	/**
	 * Makes it easy to save information to be rendered within the views.
	 * 
	 * @access public
	 * @param string $name. (default: '')
	 * @param string $value. (default: '')
	 * @return void
	 */
	public static function set($var_name='', $value='') 
	{		
		// Added by dkenzik
	    // 20101001
	    // Easier migration when $data is scaterred all over your project
	    //
	    if(is_array($var_name) && $value=='')
	    {
	        foreach($var_name as $key => $value)
	        {
	        	self::$data[$key] = $value;
	        }           
	    }
	    else
	    {
	    	// Is it a class property? 
	    	if (isset(self::$$var_name))
	    	{
	    		self::$$var_name = $value;
	    	}
	    	else 
	    	{
	        	self::$data[$var_name] = $value;
	        }
	    }
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	Returns a variable that has been previously set, or false if not exists.
	 *
	 * @param	string	$var_name	The name of the data item to return.
	 * @return	string/bool
	 */
	public static function get($var_name=null) 
	{
		if (empty($var_name))
		{
			return false;
		}
		
		// First, is it a class property? 
		if (isset(self::$$var_name))
		{
			return self::$$var_name;
		}
		else if (isset(self::$data[$var_name]))
		{
			return self::$data[$var_name];
		}
		
		return false;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * parse_views method
	 *
	 * Set whether or not the views will be passed through CI's parser.
	 *
	 * @param	bool	$parse	Should we parse views?
	 * @return	void
	 */
	public function parse_views($parse) 
	{
		self::parse_views($parse);
	}
	
	//--------------------------------------------------------------------
	
	
	/**
	 * Sets a status message (for displaying small success/error messages).
	 * This function is used in place of the session->flashdata function,
	 * because you don't always want to have to refresh the page to get the
	 * message to show up. 
	 * 
	 * @access public
	 * @param string $message. (default: '')
	 * @param string $type. (default: 'info')
	 * @return void
	 */
	public static function set_message($message='', $type='info') 
	{
		if (!empty($message))
		{
			if (class_exists('CI_Session'))
			{
				self::$ci->session->set_flashdata('message', $type.'::'.$message);
			}
			
			self::$message = array('type'=>$type, 'message'=>$message);
		}
	}
	
	//---------------------------------------------------------------
	
	/**
	 * Displays a status message (small success/error messages).
	 * If data exists in 'message' session flashdata, that will 
	 * override any other messages. The renders the message based
	 * on the template provided in the config file ('OCU_message_template').
	 * 
	 * @access public
	 * @return void
	 */
	public static function message() 
	{
		$message = '';		// The message body.
		$type	 = '';		// The message type (used for class)
	
		// Does session data exist? 
		if (class_exists('CI_Session'))
		{
			$message = self::$ci->session->flashdata('message');
			
			if (!empty($message))
			{
				// Split out our message parts
				$temp_message = explode('::', $message);
				$type = $temp_message[0];
				$message = $temp_message[1];
				
				unset($temp_message);
			} 
		}
		
		// If message is empty, we need to check our own storage.
		if (empty($message))
		{
			if (empty(self::$message['message']))
			{
				return '';
			}
			
			$message = self::$message['message'];
			$type = self::$message['type'];
		}
		
		// Grab out message template and replace the placeholders
		$template = str_replace('{type}', $type, self::$ci->config->item('template.message_template'));
		$template = str_replace('{message}', $message, $template);
		
		// Clear our session data so we don't get extra messages. 
		// (This was a very rare occurence, but clearing should resolve the problem.
		if (class_exists('CI_Session'))
		{
			self::$ci->session->flashdata('message', '');
		}
		
		return $template;
	}
	
	//---------------------------------------------------------------
	
	/**
	 *	Loads a view based on the current themes.
	 *
	 * @param	string	$view		The view to load.
	 * @param	array	$data		An array of data elements to be made available to the views
	 * @param	string	$override	The name of a view to check for first (used for controller-based layouts)
	 * @param	bool	$is_themed	Whether it should check in the theme folder first.
	 * @return	string	$output		The results of loading the view
	 */
	public static function load_view($view=null, $data=null, $override='', $is_themed=true, &$output) 
	{ 
		if (empty($view))	return '';
		
		// If no active theme is present, use the default theme.
		$theme = empty(self::$active_theme) ? self::$default_theme : self::$active_theme;
	
		if ($is_themed)
		{	
			// First check for the overriden file...
			$output = self::find_file($override, $data, $theme);
			
			// If we didn't find it, try the standard view
			if (empty($output))
			{
				$output = self::find_file($view, $data, $theme);
			}
		} 
		
		// Just a normal view (possibly from a module, though.)
		else 
		{
			// First check within our themes...
			$output = self::find_file($view, $data, $theme);
			
			// if $output is empty, no view was overriden, so go for the default
			if (empty($output))
			{	
				self::$ci->load->_ci_view_path = self::$orig_view_path;
		
				if (self::$parse_views === true)
				{
					$output = self::$ci->parser->parse($view, $data, true);
				}
				else 
				{
					$output = self::$ci->load->view($view, $data, true);
				}
			}
		}
		
		// Put our ci view path back to normal
		self::$ci->load->_ci_view_path = self::$orig_view_path;
		unset($theme, $orig_view_path);
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	/** 
	 * find_file method
	 *
	 * Searches through the the active theme and the default theme to try to find
	 * a view file. If found, it returns the rendered view.
	 *
	 * @param	string	$view		The name of the view to find.
	 * @param	array	$data		An array of key/value pairs to pass to the views.
	 * @return	string				The content of the file, if found, else empty.
	 */
	private function find_file($view=null, $data=null) 
	{
		if (empty($view))
		{
			return false;
		}
		
		$output = '';		// Stores the final output
		$view_path = '';	// Used to store the location of the file.
		
		// If there are multiple theme locations, we need to search through all of them.
		foreach (self::$theme_paths as $path)
		{
			/*
				First, check the active theme
			*/
			if (self::$debug) { echo "[Find File] Looking for view in active theme: <b>". self::$site_path . $path .'/'. self::$active_theme . $view .'.php</b><br/>'; }
			
			if (!empty(self::$active_theme) && is_file(self::$site_path . $path .'/'. self::$active_theme . $view .'.php'))
			{
				if (self::$debug) { echo 'Found <b>'. $view .'</b> in Active Theme.<br/>'; }
				$view_path = self::$site_path . $path .'/'. self::$active_theme .'/';
			}
			
			/*
				If not in the active theme, then try the default theme
			*/
			if (self::$debug) { echo "[Find File] Looking for view in default theme: <b>". self::$site_path . $path .'/'. self::$default_theme . $view .'.php</b><br/>'; }
			if (empty($view_path) && is_file(self::$site_path . $path .'/'. self::$default_theme . $view .'.php'))
			{
				if (self::$debug) { echo 'Found <b>'. $view .'</b> in Default Theme.<br/>'; }
				
				$view_path = self::$site_path . $path .'/'. self::$default_theme;
			}
		}
		
		// If the view was found, it's path is stored in the $view_path var. So parse or render it
		// based on user settings.
		if (!empty($view_path))
		{
			// Set CI's view path to point to the right location.
			self::$ci->load->_ci_view_path = $view_path;
			
			if (self::$debug) { echo '[Find File] Rendering file at: '. $view_path . $view .'.php<br/><br/>'; }
			
			// Grab the output of the view.
			if (self::$parse_views === true)
			{
				$output = self::$ci->parser->parse($view, $data, true);
			} else 
			{
				$output = self::$ci->load->_ci_load(array('_ci_view' => $view, '_ci_vars' => self::$ci->load->_ci_object_to_array($data), '_ci_return' => true));
			}
			
			// Put CI's view path back to the original
			self::$ci->load->_ci_view_path = self::$orig_view_path;
		}
		
		return $output;
	}
	
	//--------------------------------------------------------------------
	
}

// End of Template Class

//--------------------------------------------------------------------

function theme_view($view=null, $data=null)
{
	if (empty($view)) return '';
	
	$ci =& get_instance();
	
	$output ='';
	Template::load_view($view, $data, null, true, $output);
	return $output;
}

//--------------------------------------------------------------------

function check_class($item='')
{
	$ci =& get_instance();

	if (strtolower($ci->router->fetch_class()) == strtolower($item))
	{
		return 'class="current"';
	}
	
	return '';
}

//--------------------------------------------------------------------

function check_method($item='')
{
	$ci =& get_instance();

	if (strtolower($ci->router->fetch_method()) == strtolower($item))
	{
		return 'class="current"';
	}
	
	return '';
}

//--------------------------------------------------------------------

/**
 * Will create a breadcrumb from either the uri->segments or
 * from a key/value paired array passed into it. 
 *
 * @since 2.12
 */
function breadcrumb($my_segments=null) 
{
	$ci =& get_instance();
	
	if (!class_exists('CI_URI'))
	{
		$ci->load->library('uri');
	}
	
	if (empty($my_segments) || !is_array($my_segments))
	{
		$segments = $ci->uri->segment_array();
		$total = $ci->uri->total_segments();
	} else 
	{
		$total = count($my_segments);
	}
	
	echo '<a href="/">home</a> ' . $ci->config->item('template.breadcrumb_symbol');
	
	$url = '';
	$count = 0;
	
	// URI BASED BREADCRUMB
	if (is_null($my_segments))
	{
		foreach ($segments as $segment)
		{
			$url .= '/'. $segment;
			$count += 1;
		
			if ($count == $total)
			{
				echo str_replace('_', ' ', $segment);
			} else 
			{
				echo '<a href="'. $url .'">'. str_replace('_', ' ', strtolower($segment)) .'</a>'. $ci->config->item('template.breadcrumb_symbol');
			}
		}
	} else
	{
		// USER-SUPPLIED BREADCRUMB
		foreach ($my_segments as $title => $uri)
		{
			$url .= '/'. $uri;
			$count += 1;
		
			if ($count == $total)
			{
				echo str_replace('_', ' ', $title);
			} else 
			{
				echo '<a href="'. $url .'">'. str_replace('_', ' ', strtolower($title)) .'</a>'. $ci->config->item('template.breadcrumb_symbol');
			}
		}
	}
}

//---------------------------------------------------------------

/* End of file template.php */
/* Location: ./application/libraries/template.php */