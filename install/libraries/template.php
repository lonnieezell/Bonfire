<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

/*
	Class: Template
	
	The Template class makes the creation of consistently themed web pages across your
	entire site simple and as automatic as possible. 
	
	It supports parent/child themes, controller-named automatic overrides, and more.
	
	Version - 3.0
	Author	- Lonnie Ezell
*/
class Template {

	private static $debug = false;

	/*
		Var: $active_theme
		Stores the name of the active theme (folder)
		with a trailing slash. 
	 */
	protected static $active_theme = '';
	
	/*
		Var: $default_theme
		Stores the default theme from the config file
		for a slight performance increase.
	 */
	 protected static $default_theme = '';

	/*
		Var: $current_view
		The view to load. Normally not set unless
		you need to bypass the automagic.
	 */
	protected static $current_view;
	
	/*
		Var: $layout
		The layout to render the views into.
	 */
	public static $layout;
	
	/*
		Var: $parse_views
		If true, CodeIgniter's Template Parser will be used to 
		parse the view. If false, the view is displayed with
		no parsing. Used by the yield() and block() 
	 */
	public static $parse_views = false;
	
	/*
		Var: $data
		The data to be passed into the views.
		The keys are the names of the variables
		and the values are the values.
	 */
	protected static $data = array();
	
	/*
		Var: $blocks
		An array of blocks. The key is the name
		to reference it by, and the value is the file.
		The class will loop through these, parse them,
		and push them into the layout.
	 */
	public static $blocks = array();
	
	/*
		Var: $message
		Holds a simple array to store the status Message
		that gets displayed using the message() function.
	 */
	protected static $message;

	/*
		Var: $theme_paths
		An array of paths to look for themes.
	 */
	protected static $theme_paths	= array();	
	
	/*
		Var: $site_path
		The full server path to the site root.
	 */
	public static $site_path;
	
	/*
		Var: $orig_view_path
		Stores CI's default view path.
	 */
	protected static $orig_view_path;
	
	/*	
		Var: $ci
		An instance of the CI super object.
	 */
	private static $ci;

	//--------------------------------------------------------------------

	/*
		Method: __construct()
		
		This constructor is here purely for CI's benefit, as this is a
		static class.
		
		Return:
			void
	 */
	public function __construct() 
	{	
		self::$ci =& get_instance();
		
		self::init();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: init()
		
		Grabs an instance of the CI superobject, loads the Ocular config
		file, and sets our default layout.
	
		Return:	
			void
	 */
	public static function init() 
	{
		// If the application config file hasn't been loaded, do it now
		if (!self::$ci->config->item('template.theme_paths'))
		{ 
			self::$ci->config->load('application');
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
	
	
	/*
		Method: render()
		
		Renders out the specified layout, which starts the process
		of rendering the page content. Also determines the correct
		view to use based on the current controller/method.
		
		Parameters:
		 	$layout	- The name of the a layout to use. This overrides any current or default layouts set.
		 	
		Return:
			void
	 */
	public static function render($layout=null) 
	{
		$output = '';
		$controller = self::$ci->router->class;
	
		// We need to know which layout to render
		$layout = empty($layout) ? self::$layout : $layout;		

		// Is it in an AJAX call? If so, override the layout
		if (self::$ci->input->is_ajax_request())
		{ 
			$layout = self::$ci->config->item('template.ajax_layout');
			self::$ci->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
			self::$ci->output->set_header("Cache-Control: post-check=0, pre-check=0");
			self::$ci->output->set_header("Pragma: no-cache");
			self::$ci->output->set_header('Content-Type: text/html');
			
			$controller = null;
		}
		
		// Grab our current view name, based on controller/method
		// which routes to views/controller/method.
		if (empty(self::$current_view))
		{		
			self::$current_view =  self::$ci->router->class . '/' . self::$ci->router->method;
		}

		//
		// Time to render the layout
		//
		self::load_view($layout, self::$data, $controller, true, $output);
		
		if (empty($output)) { show_error('Unable to find theme layout: '. $layout); }
		
		Events::trigger('after_layout_render', $output);
		
		global $OUT;
		$OUT->set_output($output); 
		
		// Reset the original view path
		self::$ci->load->_ci_view_path = self::$orig_view_path;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: yield()
		Renders the current page into the layout. 
		
		Uses a view based on the controller/function being run. (See __constructor).
		
		Return:
			a string containing the output of the render process.
	 */
	public static function yield() 
	{ 	
		$output = '';
		
		if (self::$debug) { echo 'Current View = '. self::$current_view; }

		self::load_view(self::$current_view, null, self::$ci->router->class .'/'. self::$ci->router->method, false, $output);
		
		Events::trigger('after_page_render', $output);
		
		return $output;
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !BLOCKS
	//--------------------------------------------------------------------
	
	/*
		Method: set_block()
	
		Stores the block named $name in the blocks array for later rendering.
		The $current_view variable is the name of an existing view. If it is empty,
		your script should still function as normal.
		
		Parameters:
			$block_name	- the name of the block. Must match the name in the block() method.
			$view_name	- the name of the view file to render.
		
		Return:
			void
	 */
	public static function set_block($block_name='', $view_name='') 
	{		
		if (!empty($block_name))
		{
			self::$blocks[$block_name] = $view_name;
		} 
		
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: block()
	
		Renders a "block" to the view.
		
		A block is a partial view contained in a view file in the 
		application/views folder. It can be used for sidebars,
		headers, footers, or any other recurring element within
		a site. It is recommended to set a default when calling
		this function within a layout. The default will be rendered
		if no methods override the view (using the set_block() method).
		
		Parameters:
			$block_name		- The name of the block to render.
			$default_view	- The view to render if no other view has been set with the set_block() method.
			$data			- An array of data to pass to the view.
			$themed			- Whether we should look in the themes or standard view locations.

		Return:
			void
	 */
	public static function block($block_name='', $default_view='', $data=array(), $themed=false)
	{		
		if (empty($block_name)) 
		{
			logit('[Template] No block name provided.');
			return;
		}
		
		if (empty($block_name) && empty($default_view)) 
		{ 
			logit('[Template] No default block provided for `' . $block_name . '`');
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

		self::load_view($block_name, $data, false, $themed, $output);
		
		$block_data = array('block'=>$block_name, 'output'=>$output);
		Events::trigger('after_block_render', $block_data );
		
		echo $output;
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !THEME PATHS
	//--------------------------------------------------------------------
	
	/*
		Method: add_theme_path()
		
		Theme paths allow you to have multiple locations for themes to be
		stored. This might be used for separating themes for different sub-
		applications, or a core theme and user-submitted themes.
		
		Parameters:
			$path	- A new path where themes can be found.
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
			logit("[Template] Cannot add theme folder: $path does not exist");
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: remove_theme_path()
		
		Parameters:
			$path	- The path to remove from the theme paths.

		Return:
			void
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
	
	/*
		Method: set_theme()
		
		Stores the name of the active theme to use. This theme should be
		relative to one of the 'template.theme_paths' folders.
		
		Parameters:
			$theme	- The name of the active theme.
			$default_theme	- (Optional) The name of the desired default theme.

		Return: 
			void
	 */
	public static function set_theme($theme=null, $default_theme=null) 
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
		
		// Default theme? 
		if (!empty($default_theme) && is_string($default_theme))
		{
			self::set_default_theme($default_theme);
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: set_default_theme()
		
		Stores the name of the default theme to use. This theme should be
		relative to one of the template.theme_paths folders.
		
		Parameters:
			$theme	- The name of the desired default theme to use.
			
		Returns:
			void
	*/
	public static function set_default_theme($theme=null) 
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

		self::$default_theme = $theme;
	}
	
	//--------------------------------------------------------------------
	
	
	/*
		Method: theme()
	
		Returns the active theme.
		
		Return:
			The name of the active theme.
	 */
	public static function theme() 
	{
		return self::$active_theme;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: theme_url()
	
		Returns the full url to a file in the currently active theme.
		
		Return:
			The full url (including http://) to the resource.
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
	
	
	/*
		Method: set_view()
		
		Set the current view to render.
		
		Parameter:
			$view	- The name of the view file to render as content.
			
		Return:
			void
	 */
	public static function set_view($view=null) 
	{
		if (empty($view) || !is_string($view))
		{
			return;
		}
		
		self::$current_view = $view;
	}
	
	//--------------------------------------------------------------------
	
	
	/*
		Method: set()
		
		Makes it easy to save information to be rendered within the views. 
		As of 3.0, can also set any of the class properties.
		
		Parameters:
			$var_name	- The name of the variable to set
			$value		- The value to set it to.
		@return void
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
	
	/*
		Method: get()
		
		Returns a variable that has been previously set, or false if not exists.
		As of 3.0, will also return class properties.
		
		Parameter:
			$var_name	- The name of the data item to return.
			
		Return: 
			The value of the class property or view data.
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
	
	/*
		Method: parse_views()
		
		Set whether or not the views will be passed through CI's parser.
		
		Parameter:
			$parse	- boolean value. Should we parse views?
			
		Return:
			void
	 */
	public function parse_views($parse) 
	{
		self::parse_views($parse);
	}
	
	//--------------------------------------------------------------------
	
	
	/*
		Method: set_message()
	
		Sets a status message (for displaying small success/error messages).
		This function is used in place of the session->flashdata function,
		because you don't always want to have to refresh the page to get the
		message to show up. 
		
		Parameters:
			$message	- A string with the message to save.
			$type		- A string to be included as the CSS class of the containing div.
			
		Return:
			void
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
	
	/*
		Method: message()
	
		Displays a status message (small success/error messages).
		If data exists in 'message' session flashdata, that will 
		override any other messages. The renders the message based
		on the template provided in the config file ('OCU_message_template').
	
		Parameters:
			$message	- a string to be the message. (Optional) If included, will override
							any other messages in the system.
			$type		- the class to attached to the div. (i.e. 'information', 'attention', 'error', 'success')
		
		Return:
			A string with the results of inserting the message into the message template.
	 */
	public static function message($message='', $type='information') 
	{	
		// Does session data exist? 
		if (empty($message) && class_exists('CI_Session'))
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
			self::$ci->session->set_flashdata('message', '');
		}
		
		return $template;
	}
	
	//---------------------------------------------------------------
	
	/*
		Method: redirect()
		
		Returns a javascript solution for page redirection. This is especially
		handy when you want to redirect out of an ajax request to a standard
		http request.
		
		Parameter:
			$url	- The url to redirect to. If not a full url, will wrap it
						in site_url().
	*/
	public function redirect($url=null) 
	{
		$url = strpos($url, 'http') === false ? site_url($url) : $url;
		
		echo "<script>window.location='$url'</script>";
		exit();
	}
	
	//--------------------------------------------------------------------
	
	
	/*
		Method: load_view()
	
		Loads a view based on the current themes.
		
		Parameters:
			$view		- The view to load.
			$data		- An array of data elements to be made available to the views
			$override	- The name of a view to check for first (used for controller-based layouts)
			$is_themed	- Whether it should check in the theme folder first.
			&$output	- A pointer to the variable to store the output of the loaded view into.
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
	
	/*
		Method: find_file
		
		Searches through the the active theme and the default theme to try to find
		a view file. If found, it returns the rendered view.
		
		Parameters:
			$view	- The name of the view to find.
			$data   -  An array of key/value pairs to pass to the views.
		
		Return:
			The content of the file, if found, else empty.
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

/*
	Function: theme_view()
	
	A shorthand method that allows views (from the current/default themes)
	to be included in any other view.
	
	Parameters:
		$view	- the name of the view to render.
		$data	- an array of data to pass to the view.
*/
function theme_view($view=null, $data=null)
{
	if (empty($view)) return '';
	
	$ci =& get_instance();
	
	$output ='';
	Template::load_view($view, $data, null, true, $output);
	return $output;
}

//--------------------------------------------------------------------

/*
	Function: check_class()
	
	A simple helper method for checking menu items against the current
	class that is running.
	
	Parameter:
		$item	- The name of the class to check against.
		
	Return:
		Either <b>class="current"</b> or an empty string.
*/
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

/*
	Function: check_method()
	
	A simple helper method for checking menu items against the current
	class' method that is being executed (as far as the Router knows.)
	
	Parameter:
		$item	- The name of the method to check against. Can be an array of names.
		
	Return:
		Either <b>class="current"</b> or an empty string.
*/
function check_method($item)
{
	$ci =& get_instance();

	$items = array();

	if (!is_array($item))
	{
		$items[] = $item;
	} else
	{
		$items = $item;
	}

	if (in_array($ci->router->fetch_method(), $items))
	{
		return 'class="current"';
	}

	return '';
}

//--------------------------------------------------------------------

/*
	Function: breadcrumb()

	Will create a breadcrumb from either the uri->segments or
	from a key/value paired array passed into it. 	
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
	if (empty($my_segments) || !is_array($my_segments))
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