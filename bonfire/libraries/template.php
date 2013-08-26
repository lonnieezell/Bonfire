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
 * Template
 *
 * The Template class makes the creation of consistently themed web pages across your
 * entire site simple and as automatic as possible.
 *
 * It supports parent/child themes, controller-named automatic overrides, and more.
 *
 * @package    Bonfire
 * @subpackage Libraries
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @version    3.0
 * @link       http://cibonfire.com/docs/guides/views.html
 *
 */
class Template
{

	/**
	 * Set the debug mode on the template to output messages
	 *
	 * @access public
	 * @static
	 *
	 * @var bool
	 */
	public static $debug = false;

    /**
     * @var string Prefix added to debug messages in the log
     */
    protected static $log_prefix = '[Template] ';

	/**
	 * Stores the name of the active theme (folder) with a trailing slash.
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $active_theme = '';


	/**
	 * Stores the default theme from the config file for a slight performance increase.
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $default_theme = '';


	/**
	 * The view to load. Normally not set unless you need to bypass the automagic.
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $current_view;


	/**
	 * The layout to render the views into.
	 *
	 * @access public
	 * @static
	 *
	 * @var string
	 */
	public static $layout;


	/**
	 * If TRUE, CodeIgniter's Template Parser will be used to
	 * parse the view. If FALSE, the view is displayed with
	 * no parsing. Used by the content() and block()
	 *
	 * @access public
	 * @static
	 *
	 * @var bool
	 */
	public static $parse_views = FALSE;


	/**
	 * The data to be passed into the views. The keys are the names of the variables
	 * and the values are the values.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array
	 */
	protected static $data = array();


	/**
	 * An array of blocks. The key is the name to reference it by, and the value is the file.
	 * The class will loop through these, parse them, and push them into the layout.
	 *
	 * @access public
	 * @static
	 *
	 * @var array
	 */
	public static $blocks = array();


	/**
	 * Holds a simple array to store the status Message
	 * that gets displayed using the message() function.
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $message;


	/**
	 * An array of paths to look for themes.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array
	 */
	protected static $theme_paths = array();


	/**
	 * The full server path to the site root.
	 *
	 * @access public
	 * @static
	 *
	 * @var string
	 */
	public static $site_path;


	/**
	 * Stores CI's default view path.
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $orig_view_path;

	/**
	 * If TRUE, we won't use Session-related functions.
	 * This is helpful during unit testing.
	 *
	 * @var boolean
	 */
	public static $ignore_session = FALSE;

	/**
	 * An instance of the CI super object.
	 *
	 * @access private
	 * @static
	 *
	 * @var object
	 */
	private static $ci;

	//--------------------------------------------------------------------

	/**
	 * This constructor is here purely for CI's benefit, as this is a static class.
	 *
	 * @return void
	 */
	public function __construct()
	{
		self::$ci =& get_instance();

		self::init();

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Grabs an instance of the CI superobject, loads the Ocular config
	 * file, and sets our default layout.
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function init()
	{
		// If the application config file hasn't been loaded, do it now
		if ( ! self::$ci->config->item('template.theme_paths'))
		{
			self::$ci->config->load('application');
		}

		// Store our settings
		self::$site_path 		= self::$ci->config->item('template.site_path');
		self::$theme_paths 		= self::$ci->config->item('template.theme_paths');
		self::$layout 			= self::$ci->config->item('template.default_layout');
		self::$default_theme 	= self::$ci->config->item('template.default_theme');
		self::$parse_views		= self::$ci->config->item('template.parse_views');

        log_message('debug', 'Template library loaded');

	}//end init()

	//--------------------------------------------------------------------


	/**
	 * Renders out the specified layout, which starts the process
	 * of rendering the page content. Also determines the correct
	 * view to use based on the current controller/method.
	 *
	 * @access public
	 * @static
	 *
	 * @param  string $layout The name of the a layout to use. This overrides any current or default layouts set.
	 *
	 * @return void
	 */
	public static function render($layout=NULL)
	{
		$output = '';
		$controller = self::$ci->router->class;

		// We need to know which layout to render
		$layout = empty($layout) ? self::$layout : $layout;

		// Is it in an AJAX call? If so, override the layout
		if (self::$ci->input->is_ajax_request())
		{
			$layout = self::$ci->config->item('template.ajax_layout');

			$controller = NULL;
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
		self::load_view($layout, self::$data, $controller, TRUE, $output);

		if (empty($output)) { show_error('Unable to find theme layout: '. $layout); }

		Events::trigger('after_layout_render', $output);

		self::$ci->output->set_output($output);

		// Reset the original view path
		//self::$ci->load->_ci_view_path = self::$orig_view_path;

	}//end render()

	//--------------------------------------------------------------------

	/**
	 * Renders the current page into the layout.
	 *
	 * Uses a view based on the controller/function being run. (See __constructor).
	 *
	 * @access public
	 * @static
	 *
	 * @return string A string containing the output of the render process.
	 */
	public static function content()
	{
		$output = '';

        self::debug_message('Current View = '. self::$current_view);

		self::load_view(self::$current_view, NULL, self::$ci->router->class . '/' . self::$ci->router->method, FALSE, $output);

		Events::trigger('after_page_render', $output);

		return $output;

	}//end content()

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
	 * @static
	 *
	 * @param string $block_name The name of the block. Must match the name in the block() method.
	 * @param string $view_name  The name of the view file to render.
	 *
	 * @return void
	 */
	public static function set_block($block_name='', $view_name='')
	{
		if ( ! empty($block_name))
		{
			self::$blocks[$block_name] = $view_name;
		}

	}//end set_block()

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
	 * @static
	 *
	 * @param string $block_name   The name of the block to render.
	 * @param string $default_view The view to render if no other view has been set with the set_block() method.
	 * @param array  $data         An array of data to pass to the view.
	 * @param bool   $themed       Whether we should look in the themes or standard view locations.
	 *
	 * @return void
	 */
	public static function block($block_name='', $default_view='', $data=array(), $themed=FALSE)
	{
		if (empty($block_name))
		{
			self::debug_message('No block name provided.');
			return;
		}

		// If a block has been set previously use it; otherwise, use the default view.
		$block_view_name = isset(self::$blocks[$block_name]) ? self::$blocks[$block_name] : $default_view;

		if (empty($block_view_name) && empty($default_view))
		{
			self::debug_message('No default block provided for `' . $block_name . '`');
			return;
		}

        self::debug_message("Looking for block: <b>{$block_view_name}</b>.");

		self::load_view($block_view_name, $data, FALSE, $themed, $output);

		$block_data = array('block' => $block_view_name, 'output' => $output);
		Events::trigger('after_block_render', $block_data);

		echo $output;

	}//end block()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !THEME PATHS
	//--------------------------------------------------------------------

	/**
	 * Theme paths allow you to have multiple locations for themes to be
	 * stored. This might be used for separating themes for different sub-
	 * applications, or a core theme and user-submitted themes.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $path A new path where themes can be found.
	 *
	 * @return bool
	 */
	public static function add_theme_path($path=NULL)
	{
		if (empty($path) || ! is_string($path))
		{
			return FALSE;
		}

		// Make sure the path has a '/' at the end.
		if (substr($path, -1) != '/')
		{
			$path .= '/';
		}

		// If the path already exists, we're done here.
		if (isset(self::$theme_paths[$path]))
		{
			return TRUE;
		}

		// Make sure the folder actually exists
		if (is_dir(FCPATH . $path))
		{
			array_push(self::$theme_paths, $path);
			return FALSE;
		}
		else
		{
			self::debug_message("Cannot add theme folder: $path does not exist");
			return FALSE;
		}

	}//end add_theme_path()

	//--------------------------------------------------------------------

	/**
	 * Remove the theme path
	 *
	 * @access public
	 * @static
	 *
	 * @param string $path The path to remove from the theme paths.
	 *
	 * @return void
	 */
	public static function remove_theme_path($path=NULL)
	{
		if (empty($path) || ! is_string($path))
		{
			return;
		}

		if (isset(self::$theme_paths[$path]))
		{
			unset(self::$theme_paths[$path]);
		}

	}//end remove_theme_path()

	//--------------------------------------------------------------------

	/**
	 * Stores the name of the active theme to use. This theme should be
	 * relative to one of the 'template.theme_paths' folders.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $theme         The name of the active theme.
	 * @param string $default_theme (Optional) The name of the desired default theme.
	 *
	 * @return void
	 */
	public static function set_theme($theme=NULL, $default_theme=NULL)
	{
		if (empty($theme) || ! is_string($theme))
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
		if ( ! empty($default_theme) && is_string($default_theme))
        {
			self::set_default_theme($default_theme);
		}
	}//end set_theme()

	//--------------------------------------------------------------------

	/**
	 * Stores the name of the default theme to use. This theme should be
	 * relative to one of the template.theme_paths folders.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $theme The name of the desired default theme to use.
	 *
	 * @return void
	 */
	public static function set_default_theme($theme=NULL)
	{
		if (empty($theme) || ! is_string($theme))
		{
			return;
		}

		// Make sure a trailing slash is there
		if (substr($theme, -1) !== '/')
		{
			$theme .= '/';
		}

		self::$default_theme = $theme;

	}//end set_default_theme()

	//--------------------------------------------------------------------


	/**
	 * Returns the active theme.
	 *
	 * @access public
	 * @static
	 *
	 * @return string The name of the active theme.
	 */
	public static function theme()
	{
		return ( ! empty(self::$active_theme)) ? self::$active_theme : self::$default_theme;
	}//end theme()

	//--------------------------------------------------------------------

	/**
	 * Returns the full url to a file in the currently active theme.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $resource Path to a resource in the theme
	 *
	 * @return string The full url (including http://) to the resource.
	 */
	public static function theme_url($resource='')
	{
		$url = base_url();

		// Add theme path
		$url .= self::$theme_paths[0] . '/';

		// Add theme
		$url .= empty(self::$active_theme) ? self::$default_theme : self::$active_theme;

		// Cleanup, just to be safe
		$url = str_replace('//', '/', $url);
		$url = str_replace(':/', '://', $url);

		return $url . $resource;

	}//end theme_url()

	//--------------------------------------------------------------------


	/**
	 * Set the current view to render.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $view The name of the view file to render as content.
	 *
	 * @return void
	 */
	public static function set_view($view=NULL)
	{
		if (empty($view) || ! is_string($view))
		{
			return;
		}

		self::$current_view = $view;
	}//end set_view()

	//--------------------------------------------------------------------


	/**
	 * Makes it easy to save information to be rendered within the views.
	 *
	 * This should probably be updated to clarify the intended functionality
	 * when an array is passed into $var_name and $value is '' (and maybe
	 * change the $value=='' to empty($value)?).
	 *
	 * @param string $var_name The name of the variable to set
	 * @param mixed  $value    The value to set it to.
	 *
	 * @return void
	 */
	public static function set($var_name='', $value='')
	{
		// Added by dkenzik
		// 20101001
		// Easier migration when $data is scattered all over your project
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
			self::$data[$var_name] = $value;
		}//end if

	}//end set()

	//--------------------------------------------------------------------

	/**
	 * Returns a variable that has been previously set, or FALSE if not exists.
	 * As of 3.0, will also return class properties.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $var_name The name of the data item to return.
	 *
	 * @return mixed The value of the class property or view data.
	 */
	public static function get($var_name=NULL)
	{
		if (empty($var_name))
		{
			return FALSE;
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

		return FALSE;
	}//end get()

	//--------------------------------------------------------------------

	/**
	 * Set whether or not the views will be passed through CI's parser.
	 *
	 * @access public
	 *
	 * @param bool $parse Boolean value. Should we parse views?
	 */
	public function parse_views($parse = FALSE)
	{
		self::$parse_views = (bool) $parse;

	}//end parse_views()

	//--------------------------------------------------------------------


	/**
	 * Sets a status message (for displaying small success/error messages).
	 * This function is used in place of the session->flashdata function,
	 * because you don't always want to have to refresh the page to get the
	 * message to show up.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $message A string with the message to save.
	 * @param string $type    A string to be included as the CSS class of the containing div.
	 *
	 * @return void
	 */
	public static function set_message($message='', $type='info')
	{
		if ( ! empty($message))
		{
			if (isset(self::$ci->session) && ! self::$ignore_session)
			{
				self::$ci->session->set_flashdata('message', $type . '::' . $message);
			}

			self::$message = array('type'=>$type, 'message'=>$message);
		}

	}//end set_message()

	//---------------------------------------------------------------

	/**
	 * Displays a status message (small success/error messages).
	 * If data exists in 'message' session flashdata, that will
	 * override any other messages. The renders the message based
	 * on the template provided in the config file ('OCU_message_template').
	 *
	 * @access public
	 * @static
	 *
	 * @param string $message A string to be the message. (Optional) If included, will override any other messages in the system.
	 * @param string $type    The class to attached to the div. (i.e. 'information', 'attention', 'error', 'success')
	 *
	 * @return string A string with the results of inserting the message into the message template.
	 */
	public static function message($message='', $type='information')
	{
		// Does session data exist?
		if (empty($message) && class_exists('CI_Session'))
		{
			$message = self::$ci->session->flashdata('message');

			if ( ! empty($message))
			{
				// Split out our message parts
				$temp_message = explode('::', $message);
				$type = $temp_message[0];
				$message = $temp_message[1];

				unset($temp_message);
			}
		}//end if

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
		if (class_exists('CI_Session') && ! self::$ignore_session)
		{
			self::$ci->session->set_flashdata('message', '');
		}

		return $template;

	}//end message()

	//---------------------------------------------------------------

	/**
	 * Like CodeIgniter redirect(), but uses javascript if needed
	 * to redirect out of an ajax request.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $url The url to redirect to. If not a full url, will wrap it in site_url().
	 *
	 * @return void
	 */
	public static function redirect($url=NULL)
	{
		if ( ! preg_match('#^https?://#i', $url))
		{
			$url = site_url($url);
		}

		if ( ! self::$ci->input->is_ajax_request())
		{
			header("Location: ".$url);

			// A full HTML document requires certain elements to
			// be considered valid.  We don't return any content,
			// so override the default header which specifies HTML.
			header("Content-Type: text/plain");
		}
		else
		{
			// Output URL somewhere where we know how to escape it safely
			echo '<div id="url" data-url="';
			e($url);
			echo '"></div>';

			// then JS can grab it
			echo <<<EOF
<script>
window.location = document.getElementById('url').getAttribute('data-url');
</script>
EOF;
		}

		exit();

	}//end redirect()

	//--------------------------------------------------------------------

	/**
	 * Loads a view based on the current themes.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $view      The view to load.
	 * @param array  $data      An array of data elements to be made available to the views
	 * @param string $override  The name of a view to check for first (used for controller-based layouts)
	 * @param bool   $is_themed Whether it should check in the theme folder first.
	 * @param object $output    A pointer to the variable to store the output of the loaded view into.
	 *
	 * @return void
	 */
	public static function load_view($view=NULL, $data=NULL, $override='', $is_themed=TRUE, &$output)
	{
		if (empty($view))	return '';

		if (empty($data))
        {
            $data = self::$data;
        }

		if ($is_themed)
		{
			$output = '';

			// First check for the overridden file...
			if ( ! empty($override))
			{
				$output = self::find_file($override, $data);
			}

			// If we didn't find it, try the standard view
			if (empty($output))
			{
				$output = self::find_file($view, $data);
			}

			// Should it be parsed?
			if (self::$parse_views === TRUE)
            {
                if (!class_exists('CI_Parser'))
                {
                    self::$ci->load->library('parser');
                }

                $output = self::$ci->parser->parse($output, $data, TRUE, FALSE);
            }
		}

		// Just a normal view (possibly from a module, though.)
		else
		{
			// First check within our themes...
			$output = self::find_file($view, $data);

			// if $output is empty, no view was overriden, so go for the default
			if (empty($output))
			{
				self::$ci->load->_ci_view_path = self::$orig_view_path;

				if (self::$parse_views === TRUE)
				{

					if ( ! class_exists('CI_Parser'))
					{
						self::$ci->load->library('parser');
					}

					$output = self::$ci->parser->parse($view, $data, TRUE);
				}
				else
				{
					$output = self::$ci->load->view($view, $data, TRUE);
				}
			}
			self::$ci->load->_ci_view_path = self::$orig_view_path;
		}//end if
	}//end load_view()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Searches through the the active theme and the default theme to try to find
	 * a view file. If found, it returns the rendered view.
	 *
	 * @access private
	 *
	 * @param string $view The name of the view to find.
	 * @param array  $data An array of key/value pairs to pass to the views.
	 *
	 * @return string The content of the file, if found, else empty.
	 */
	private static function find_file($view=NULL, $data=NULL)
	{
		if (empty($view))
		{
			return FALSE;
		}

		if ( ! empty($data))
		{
			$data = (array)$data;
		}

		$output = '';		// Stores the final output
		$view_path = '';	// Used to store the location of the file.
		$active_theme_set = ! empty(self::$active_theme);	// Is the active theme set?
		$view_file = $view . '.php';	// filename for the view

		/*
		 * In most cases, self::$theme_paths will only include one location, but when it
		 * does not, the last will take precedence for the search. Previously the loop
		 * just replaced the $view_path with any files found in later paths. Instead,
		 * we will just reverse the $theme_paths array and break the loop when we find
		 * the file.
		 */
		$theme_locations = array_reverse(self::$theme_paths);

		// If there are multiple theme locations, we need to search through all of them.
		foreach ($theme_locations as $path)
		{
			$site_theme_path = self::$site_path . $path . '/';

			/*
				First, check the active theme
			*/
			$active_theme_path = $site_theme_path . self::$active_theme;
            self::debug_message('[Find File] Looking for view in active theme: <b>' . $active_theme_path . $view_file . '</b><br/>');

			if ($active_theme_set && is_file($active_theme_path . $view_file))
			{
				$view_path = $active_theme_path;
                self::debug_message('Found <b>' . $view . '</b> in Active Theme.<br/>');

				// If we found the view, we should exit the loop
				break;
			}

			/*
				If not in the active theme, try the default theme.
				As long as we break the loop whenever the $view_path is set,
				we should not need to check empty($view_path) here
			*/
			$default_theme_path = $site_theme_path . self::$default_theme;
            self::debug_message('[Find File] Looking for view in default theme: <b>' . $default_theme_path . $view_file . '</b><br/>');

			if (is_file($default_theme_path . $view_file))
			{
				$view_path = $default_theme_path;
				self::debug_message('Found <b>' . $view . '</b> in Default Theme.<br/>');

				// If we found the view, we should exit the loop
				break;
			}
		}

		// If the view was found, its path is stored in the $view_path var. So parse or render it
		// based on user settings.
		if ( ! empty($view_path))
		{
			$view_path = str_replace('//', '/', $view_path);
			self::debug_message('[Find File] Rendering file at: '. $view_path . $view_file .'<br/><br/>');

			// Grab the output of the view.
			if (self::$parse_views === TRUE)
			{
				$data = array_merge((array)$data, self::$ci->load->_ci_cached_vars);
				$output = self::$ci->load->_ci_load(array(
					'_ci_path' => $view_path . $view_file,
					'_ci_vars' => $data,
					'_ci_return' => TRUE,
				));
			}
			else
			{
				$output = self::$ci->load->_ci_load(array(
					'_ci_path' => $view_path . $view_file,
					'_ci_vars' => $data,
					'_ci_return' => TRUE,
				));
			}
		}//end if

		return $output;

	}//end find_file()

    //---------------------------------------------------------------

	/**
	 * Usefull debugging script to echo out message to the Console
	 * (if loaded) and to the log files.
	 *
	 * By default it will only log the messages if self::$debug == TRUE,
	 * but this behaviour can be modified by passing $force as TRUE.
	 *
	 * @param  string  $message The message to log
	 * @param  boolean $force   If FALSE, will respect self::$debug setting.
	 *                          If TRUE, will force the message to be logged.
	 */
    protected static function debug_message($message, $force=false)
    {
    	// Only log the message if we're in debug mode, so we don't
    	// clutter up people's applications.
        if (self::$debug)
        {
            echo $message;
            logit(self::$log_prefix . $message);
            return;
        }

        // If $debug is off but we want to force the message
        // then we'll deal with it here.
        if ($force)
        {
        	logit(self::$log_prefix . $message);
        }
    }

	//--------------------------------------------------------------------

}//end class


//--------------------------------------------------------------------

/**
 * A shorthand method that allows views (from the current/default themes)
 * to be included in any other view.
 *
 * This function also allows for a very simple form of mobile templates. If being
 * viewed from a mobile site, it will attempt to load a file whose name is prefixed
 * with 'mobile_'. If that file is not found it will load the regular view.
 *
 * @access  public
 * @example Rendering a view named 'index', the mobile version would be 'mobile_index'.
 *
 * @param string $view          The name of the view to render.
 * @param array  $data          An array of data to pass to the view.
 * @param bool   $ignore_mobile If TRUE, will not change the view name based on mobile viewing. If FALSE, will attempt to load a file prefixed with 'mobile_'
 *
 * @return string
 */
function theme_view($view=NULL, $data=NULL, $ignore_mobile=FALSE)
{
	if (empty($view)) return '';

	$ci =& get_instance();

	$output ='';

	// If we're allowed, try to load the mobile version of the file.
	if ( ! $ignore_mobile)
	{
		$ci->load->library('user_agent');

		if ($ci->agent->is_mobile())
		{
			Template::load_view('mobile_' . $view, $data, NULL, TRUE, $output);
		}
	}

	// If output is empty, then either no mobile file was found
	// or we weren't looking for one to begin with.
	if (empty($output))
	{
		Template::load_view($view, $data, NULL, TRUE, $output);
	}

	return $output;

}//end theme_view()

//--------------------------------------------------------------------

/**
 * A simple helper method for checking menu items against the current
 * class that is running.
 *
 * <code>
 *   <a href="<?php echo site_url(SITE_AREA . '/content'); ?>" <?php echo check_class(SITE_AREA . '/content'); ?> >
 *    Admin Home
 *  </a>
 *
 * </code>
 * @access public
 *
 * @param string $item       The name of the class to check against.
 * @param bool   $class_only If TRUE, will only return 'active'. If FALSE, will return 'class="active"'.
 *
 * @return string Either <b>class="active"</b> or an empty string.
 */
function check_class($item='', $class_only=FALSE)
{
	$ci =& get_instance();

	if (strtolower($ci->router->fetch_class()) == strtolower($item))
	{
		return $class_only ? 'active' : 'class="active"';
	}

	return '';

}//end check_class()

//--------------------------------------------------------------------

/**
 * A simple helper method for checking menu items against the current
 * class' method that is being executed (as far as the Router knows.)
 *
 * @access public
 *
 * @param string	$item		The name of the method to check against. Can be an array of names.
 * @param bool		$class_only	If TRUE, will only return 'active'. If FALSE, will return 'class="active"'.
 *
 * @return string Either <b>class="active"</b> or an empty string.
 */
function check_method($item, $class_only=FALSE)
{
	$ci =& get_instance();

	$items = array();

	if ( ! is_array($item))
	{
		$items[] = $item;
	}
	else
	{
		$items = $item;
	}

	if (in_array($ci->router->fetch_method(), $items))
	{
		return $class_only ? 'active' : 'class="active"';
	}

	return '';

}//end check_method()

//--------------------------------------------------------------------

/**
 * Will create a breadcrumb from either the uri->segments or
 * from a key/value paired array passed into it.
 *
 * @access public
 *
 * @param array $my_segments (optional) Array of Key/Value to make Breadcrumbs from
 * @param bool  $wrap        (boolean)  Set to TRUE to wrap in un-ordered list
 * @param bool  $echo        (boolean)  Set to TRUE to echo the output, set to FALSE to return it.
 *
 * @return string A Breadcrumb of your page structure.
 */
function breadcrumb($my_segments=NULL, $wrap=FALSE, $echo=TRUE)
{
	$ci =& get_instance();

	$output = '';

	if ( ! class_exists('CI_URI'))
	{
		$ci->load->library('uri');
	}


	if ( $ci->config->item('template.breadcrumb_symbol') == '' )
	{
		$separator = '/';
	}
	else
	{
		$separator = $ci->config->item('template.breadcrumb_symbol');
	}

	if ($wrap === TRUE)
	{
		$separator = '<span class="divider">' . $separator . '</span>' . PHP_EOL;
	}


	if (empty($my_segments) || ! is_array($my_segments))
	{
		$segments = $ci->uri->segment_array();
		$total    = $ci->uri->total_segments();
	}
	else
	{
		$segments = $my_segments;
		$total    = count($my_segments);
	}

	// Are we in the admin section of the site?
	if (is_array($segments) && in_array(SITE_AREA, $segments))
	{
		$home_link = site_url(SITE_AREA);
	}
	else
	{
		$home_link = site_url();
	}

	if ($wrap === TRUE)
	{
		$output  = '<ul class="breadcrumb">' . PHP_EOL;
		$output .= '<li><a href="'.$home_link.'"><i class="icon-home">&nbsp;</i></a> '.$separator.'</li>' . PHP_EOL;
	}
	else
	{
		$output  = '<a href="'.$home_link.'">home</a> '.$separator;
	}

	$url = '';
	$count = 0;

	// URI BASED BREADCRUMB
	if (empty($my_segments) || ! is_array($my_segments))
	{
		foreach ($segments as $segment)
		{
			$url .= '/' . $segment;
			$count += 1;

			if ($count == $total)
			{
				if ($wrap === TRUE)
				{
					$output .= '<li class="active">' . ucfirst(str_replace('_', ' ', $segment)) . '</li>' . PHP_EOL;
				}
				else
				{
					$output .= ucfirst(str_replace('_', ' ', $segment)) . PHP_EOL;
				}
			}
			else
			{
				if ($wrap === TRUE)
				{
					$output .= '<li><a href="'. $url .'">'. str_replace('_', ' ', ucfirst(mb_strtolower($segment))) .'</a>' . $separator . '</li>' . PHP_EOL;
				}
				else
				{
					$output .= '<a href="'. $url .'">'. str_replace('_', ' ', ucfirst(mb_strtolower($segment))) .'</a>' . $separator . PHP_EOL;
				}
			}
		}
	}
	else
	{
		// USER-SUPPLIED BREADCRUMB
		foreach ($my_segments as $title => $uri)
		{
			$url .= '/'. $uri;
			$count += 1;

			if ($count == $total)
			{
				if ($wrap === TRUE)
				{
					$output .= '<li class="active">' . str_replace('_', ' ', $title) . '</li>' . PHP_EOL;
				}
				else
				{
					$output .= str_replace('_', ' ', $title);
				}

			}
			else
			{

				if ($wrap === TRUE)
				{
					$output .= '<li><a href="'. $url .'">'. str_replace('_', ' ', ucfirst(mb_strtolower($title))) .'</a>' . $separator . '</li>' . PHP_EOL;
				}
				else
				{
					$output .= '<a href="'. $url .'">'. str_replace('_', ' ', ucfirst(mb_strtolower($title))) .'</a>' . $separator . PHP_EOL;
				}

			}
		}
	}

	if ($wrap === TRUE)
	{
		$output .= PHP_EOL . '</ul>' . PHP_EOL;
	}

	unset($separator, $url, $wrap);

	if ($echo === TRUE)
	{
		echo $output;
		unset ($output);
	}
	else
	{
		return $output;
	}

}//end breadcrumb()

//---------------------------------------------------------------

/* End of file template.php */
/* Location: ./application/libraries/template.php */