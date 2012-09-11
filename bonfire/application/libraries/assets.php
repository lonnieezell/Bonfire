<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2012, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Assets Class
 *
 * The Assets class works with the Template class to provide powerful theme/template functionality.
 *
 * @package    Bonfire
 * @subpackage Libraries
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/core/unit_test.html
 * @version    3.0
 *
 */
class Assets
{

	/**
	 * Whether or not debug messages should be displayed.
	 *
	 * @access private
	 *
	 * @var bool
	 */
	private static $debug = FALSE;

	/**
	 * Stores the CodeIgniter core object.
	 *
	 * @access protected
	 *
	 * @var object
	 */
	protected static $ci;

	/**
	 * The base folder (relative to the template.site_root config setting)
	 * that all of the assets are stored in.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private static $asset_base		= 'assets/';

	/**
	 * The names of the folders for the various assets.
	 * These are set in the assets config file, and
	 * default to 'js', 'css', and 'images'.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private static $asset_folders 	= array(
										'css'		=> 'css',
										'js'		=> 'js',
										'images'	=> 'images'
									);

	/**
	 * The name of the cache folders for the various generated assets.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private static $asset_cache_folder 	= 'cache';

	/**
	 * An array of inline scripts to be placed at the end of the page.
	 *
	 * @access protected
	 *
	 * @var array
	 */
	protected static $inline_scripts		= array();

	/**
	 * An array of external (linked) javascript files to be called at the end of the page.
	 *
	 * @access protected
	 *
	 * @var array
	 */
	protected static $external_scripts 	= array();

	/**
	 * An array of module js code used to combined into one js file
	 * to be called at the end of the page.
	 *
	 * @access protected
	 *
	 * @var array
	 */
	protected static $module_scripts 	= array();

	/**
	 * An array of css files to be placed at the beginning of the file.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private static $styles				= array();

	/**
	 * An array of module css files to be placed at the beginning of the file.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private static $module_styles		= array();


	/**
	 * Flag to define is global includes should be rendered
	 * on css() and js() output or supressed.
	 *
	 * @access private
	 *
	 * @var bool
	 */
	private static $globals          = TRUE;


	//--------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * This if here solely for CI loading to work. Just calls the init( ) method.
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
	 * Load the assets config file, and inserts the base
	 * css and js into our array for later use. This ensures
	 * that these files will be processed first, in the order
	 * the user is expecting, prior to and later-added files.
	 *
	 * @return void
	 */
	public static function init()
	{

		/*
			It is recommended to combine as many config files as sensible into
			a single file for performance reasons. To handle these situations,
			we should check to see if the config file is already loaded before
			loading it ourself.
		*/
		if (config_item('assets.base_folder') === FALSE)
		{
			self::$ci->config->load('application');
		}

		// Store our settings
		self::$asset_base    = self::$ci->config->item('assets.base_folder');
		self::$asset_folders = self::$ci->config->item('assets.asset_folders');

		log_message('debug', 'Assets library loaded.');

	}//end init()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !GLOBAL METHODS
	//--------------------------------------------------------------------

	/**
	 * Set the value of the static $globals flag that determines if
	 * global includes (like the default media type CSS and global.js files)
	 * are automatically included in css() and js() output.
	 *
	 * @access public
	 *
	 * @param bool $include TRUE to include (default) or FALSE to exclude
	 */
	public static function set_globals($include = TRUE)
	{
		self::$globals = $include;

	}//end set_globals()

	//--------------------------------------------------------------------
	// !STYLESHEET METHODS
	//--------------------------------------------------------------------

	/**
	 * Renders links to stylesheets, with the $asset_url prepended.
	 * If a single filename is passed, it will only create a single link
	 * for that file, otherwise, it will include any styles that have
	 * been added with add_css below. If no style is passed it will default
	 * to the theme's style.css file.
	 *
	 * When passing a filename, the filepath should be relative to the site
	 * root (where index.php resides).
	 *
	 * @access public
	 * @static
	 *
	 * @param mixed  $style              The style(s) to have links rendered for.
	 * @param string $media              The media to assign to the style(s) being passed in.
	 * @param bool   $bypass_inheritance If TRUE, will skip the check for parent theme styles.
	 *
	 * @return string A string containing all necessary links.
	 */
	public static function css($style=null, $media='screen', $bypass_inheritance=FALSE)
	{
		$styles = array();
		$return = '';

		//Debugging issues with media being set to 1 on module_js
		if ($media == '1')
		{
			$media = 'screen';
		}

		// If no style(s) has been passed in, use all that have been added.
		if (empty($style) && self::$globals)
		{
			// Make sure to include a file based on media type.
			$styles[] = array(
				'file'	=> $media,
				'media'	=> $media
			);

			$styles = array_merge(self::$styles, $styles);
		}
		// If an array has been passed, merge it with any added styles.
		else if (is_array($style))
		{
			$styles = array_merge($style, self::$styles);
		}
		// If a single style has been passed in, render it only.
		else
		{
			$styles[] = array(
				'file'	=> $style,
				'media'	=> $media
			);
		}

		// Add a style named for the controller so it will be looked for.
		$styles[] = array(
			'file'	=> self::$ci->router->class,
			'media' => $media
		);

		$styles     = self::find_files($styles, 'css', $bypass_inheritance);
		$mod_styles	= self::find_files(self::$module_styles, 'css', $bypass_inheritance);

		$combine = self::$ci->config->item('assets.css_combine');

		// Loop through the styles, spitting out links for each one.
		if (!$combine)
		{
			foreach ($styles as $s)
			{
				if (is_array($s))
				{
					if (substr($s['file'], -4) != '.css')
					{
						$s['file'] .= '.css';
					}
				}
				else
				{
					if (substr($s, -4) != '.css')
					{
						$s .= '.css';
					}
				}

				$attr = array(
					'rel'	=> 'stylesheet',
					'type'	=> 'text/css',
					'href'	=> is_array($s) ? $s['file'] : $s,
					'media'	=> !empty($s['media']) ? $s['media'] : $media
				);

				if (!$combine)
				{
					$return .= '<link'. self::attributes($attr) ." />\n";
				}
			}
		}

		// add the combined css
		else
		{
			$return .= self::combine_css($styles, $media);
		}

		// Make sure we include module styles
		$return .= self::combine_css($mod_styles, $media, 'module');

		return $return;

	}//end css()

	//--------------------------------------------------------------------

	/**
	 * Does the actual work of generating the combined css code.
	 *
	 * It is called by the css() method.
	 *
	 * @access public
	 * @static
	 *
	 * @param array  $files An array of file arrays
	 * @param string $media The media to assign to the style(s) being passed in.
	 * @param string $type  Either a string 'module' or empty - defines which scripts are being combined
	 *
	 * @return string
	 */
	public static function combine_css($files=array(), $media='screen', $type = '')
	{
		// Are there any scripts to include?
		if (count($files) == 0)
		{
			return;
		}

		$output = '';
		$min	= '';

		// Add the theme name to the filename
		// to account for different frontend/backend themes.
		$theme = trim(Template::get('active_theme'), '/');
		$theme = empty($theme) ? trim(Template::get('default_theme'), '/') : $theme;

		$file_name = $theme .'_'. self::$ci->router->fetch_module() .'_'. self::$ci->router->fetch_class();

		if (self::$ci->config->item('assets.encrypt_name') == TRUE)
		{
			$file_name = md5($file_name);
		}

		if ($type == 'module')
		{
			$file_name .= '_mod';
		}
		else
		{
			$file_name .= '_combined';
		}

		if (self::$ci->config->item('assets.css_minify') == TRUE)
		{
			$file_name .= '.min';
		}

		//Debugging issues with media being set to 1 on module_js
		if ($media == '1')
		{
			$media = 'screen';
		}

		// Create our link attributes
		$attr = array(
			'rel'	=> 'stylesheet',
			'type'	=> 'text/css',
			'href'	=> base_url() .self::$asset_base . '/' . self::$asset_cache_folder . '/' . $file_name.$min.".css",
			'media'	=> $media
		);

		if (self::generate_file($files, $file_name, 'css')) {
			$output .= '<link'. self::attributes($attr) ." />\n";
		}

		return $output;

	}//end combine_css()

	//--------------------------------------------------------------------


	/**
	 * Adds a file to be the CSS queue to be rendered out.
	 *
	 * @access public
	 * @static
	 *
	 * @param mixed  $style The style(s) to be added
	 * @param string $media The type of media the stylesheet styles.
	 *
	 * @return void
	 */
	public static function add_css($style=null, $media='screen')
	{
		if (empty($style)) return;

		//Debugging issues with media being set to 1 on module_js
		if ($media == '1')
		{
			$media = 'screen';
		}

		// Add a string
		if (is_string($style))
		{
			self::$styles[] = array(
				'file'	=> $style,
				'media'	=> $media
			);
		}
		// Add an array
		else if (is_array($style) && count($style))
		{
			foreach ($style as $s)
			{
				self::$styles[] = array(
					'file'	=> $s,
					'media'	=> $media
				);
			}
		}

	}//end add_css()

	//--------------------------------------------------------------------

	/**
	 * Adds a module css file to the CSS queue to be rendered out.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $module    Module name
	 * @param string $file_path Module path to the css file, leave blank for default location of modules/assets/css
	 * @param string $media     The type of media the stylesheet styles.
	 *
	 * @return void
	 */
	public static function add_module_css($module, $file_path=null, $media='screen')
	{
		if (empty($file_path)) return;

		if ($media == '1')
		{
			$media = 'screen';
		}

		// Add a string
		if (is_string($file_path))
		{
			self::$module_styles[] = array(
				'module' => $module,
				'file'	=> $file_path,
				'media' => $media
			);
		}
		// Add an array
		else if (is_array($file_path) && count($file_path))
		{
			foreach ($file_path as $s)
			{
				self::$module_styles[] = array(
					'module' => $module,
					'file'	=> $s,
					'media'	=> $media
				);
			}
		}

	}//end add_module_css()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !JAVASCRIPT METHODS
	//--------------------------------------------------------------------

	/**
	 * Adds scripts to the array to be served with the js() method, below.
	 *
	 * @access public
	 * @static
	 *
	 * @param mixed  $script  The script(s) to be added to the queue.
	 * @param string $type    Either 'external' or 'inline'
	 * @param bool   $prepend Add to the start of the array - default is No
	 *
	 * @return void
	 */
	public static function add_js($script=null, $type='external', $prepend=FALSE)
	{
		if (empty($script)) return;

		$type .= '_scripts';

		if (is_string($script))
		{
			if (!in_array($script, self::$$type))
			{
				if ($prepend)
				{
					array_unshift(self::${$type}, $script);
				}
				else
				{
					array_push(self::${$type}, $script);
				}
			}
		}
		else if (is_array($script))
		{
			$temp = array();

			// Remove any potential duplicates
			foreach ($script as $s)
			{
				if (!in_array($s, self::$$type))
				{
					$temp[] = $s;
				}
			}

			if ($prepend)
			{
				self::${$type} = array_merge($temp, self::${$type});
			}
			else
			{
				self::${$type} = array_merge(self::${$type}, $temp);
			}
		}

	}//end add_js()

	//--------------------------------------------------------------------

	/**
	 * Adds a module's javascript file to be rendered.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $module The name of the module
	 * @param string $file   The name of the file, relative to that module's assets folder.
	 *
	 * @return void
	 */
	public static function add_module_js($module='', $file='')
	{
		if (empty($file)) return;

		// Add a string
		if (is_string($file))
		{
			self::$module_scripts[] = array(
				'module' => $module,
				'file'	=> $file
			);
		}
		// Add an array
		else if (is_array($file) && count($file))
		{
			foreach ($file as $s)
			{
				self::$module_scripts[] = array(
					'module' => $module,
					'file'	=> $s,
				);
			}
		}

	}//end add_module_js()

	//--------------------------------------------------------------------

	/**
	 * Renders links to all javascript files including External, Module and Inline
	 * If a single filename is passed, it will only create a single link
	 * for that file, otherwise, it will include any javascript files that have
	 * been added with add_js below.
	 *
	 * When passing a filename, the filepath should be relative to the site
	 * root (where index.php resides).
	 *
	 * @access public
	 * @static
	 *
	 * @param mixed  $script The name of the script to link to (optional)
	 * @param string $type Whether the script should be linked to externally or rendered inline. Acceptable values: 'external' or 'inline'
	 *
	 * @return string Returns all Scripts located in External JS, Module JS and Inline JS in that order.
	 */
	public static function js($script=null, $type='external')
	{
		$type .= '_scripts';
		$output = '';

		// If a string is passed, it's a single script, so override
		// any that are already set
		if (!empty($script))
		{
			self::external_js((string)$script);
			return;
		}
		// If an array was passed, loop through them, adding each as we go.
		else if (is_array($script))
		{
			foreach ($script as $s)
			{
				self::${$type}[] = $s;
			}
		}

		// Render out the scripts/links
		$output  = self::external_js();
		$output .= self::module_js();
		$output .= self::inline_js();

		return $output;

	}//end js()

	//--------------------------------------------------------------------

	/**
	 * Does the actual work of generating the links to the js files.
	 * It is called by the js() method, but can be used on it's own.
	 *
	 * If no script are passed into the first parameter, links are created for
	 * all scripts within the self::$external_scripts array. If one or
	 * more scripts are passed in the first parameter, only these script files
	 * will be used to create links with, and any stored in self::$external_scripts
	 * will be ignored.
	 *
	 * Note that links will not be rendered for files that cannot be found, though
	 * scripts will full urls are not checked, but are simply included.
	 *
	 * @access public
	 * @static
	 *
	 * @param mixed $new_js  Either a string or an array containing the names of files to link to.
	 * @param bool  $list    If TRUE, will echo out a list of scriptnames, enclosed in quotes and comma separated. Convenient for using with third-party js loaders.
	 * @param bool  $add_ext Automatically add the .js extension when adding files
	 *
	 * @return string
	 */
	public static function external_js($new_js=null, $list=FALSE, $add_ext=TRUE)
	{
		$return = '';
		$scripts = array();

		// If scripts were passed, they override all other scripts.
		if (!empty($new_js))
		{
			if (is_string($new_js))
			{
				$scripts[] = $new_js;
			}
			else if (is_array($new_js))
			{
				$scripts = $new_js;
			}
		}
		else
		{
			$scripts = self::$external_scripts;
		}

		// Make sure we check for a 'global.js' file.
		if (self::$globals)
		{
				$scripts[] = 'global';
		}

		// Add a style named for the controller so it will be looked for.
		$scripts[] = self::$ci->router->class;

		// Prep our scripts array with only files
		// that actually can be found.
		$scripts = self::find_files($scripts, 'js');

		// We either combine the files into one...
		if ((empty($new_js) || is_array($new_js)) && $list==FALSE && self::$ci->config->item('assets.js_combine'))
		{
			$return = self::combine_js($scripts);
		}
		// Or generate individual links
		else
		{
			//Check for HTTPS or HTTP connection

			if (isset($_SERVER['HTTPS']))
			{
				$http_protocol = "https";
			}
			else
			{
				$http_protocol = "http";
			}

			foreach ($scripts as $script)
			{
				if (TRUE === $add_ext && substr($script, -3) != '.js')
				{
					$script .= '.js';
				}

				$attr = array(
					'src'	=> (strpos($script, $http_protocol . ':') !== FALSE ||
										strpos($script, 'http:') !== FALSE ||
										strpos($script, 'https:') !== FALSE) ?

						// It has a full url built in, so leave it alone
						$script :

						// Otherwise, build the full url
						base_url() . self::$asset_base .'/'. self::$asset_folders['js'] .'/'. $script,
							'type'=>'text/javascript'
				);

				if ($list)
				{
					$return .= '"'. $attr['src'] .'", ';
				}
				else
				{
					$return .= '<script'. self::attributes($attr) ." ></script>\n";
				}
			}
		}

		return trim($return, ', ');

	}//end external_js()

	//--------------------------------------------------------------------

	/**
	 * Returns the full url to a folder in the assets directory.
	 *
	 * @access public
	 * @static
	 *
	 * @param mixed $type Optional a string with the assets folder to locate leave blank to return the assets base folder.
	 *
	 * @return string The full url (including http://) to the resource.
	 */
	public static function assets_url($type=NULL)
	{
		// Add Assets Base Folder
		$url = base_url() . self::$asset_base . '/';

		// Add resource type folder if needed.
		if ($type !== NULL && (array_key_exists($type, self::$asset_folders)))
		{
			$url .= self::$asset_folders[$type] . '/';
		}

		// Cleanup, just to be safe
		$url = str_replace('//', '/', $url);
		$url = str_replace(':/', '://', $url);

		return $url;
	}//end assets_url()

	//--------------------------------------------------------------------

	/**
	 * Renders out links for the module's external javascript files.
	 *
	 * @access public
	 * @static
	 *
	 * @param bool $list If TRUE, will echo out a list of scriptnames, enclosed in quotes and comma separated. Convenient for using with third-party js loaders.
	 *
	 * @return string A string with the link(s) to the script files.
	 */
	public static function module_js($list=FALSE)
	{
		if (!is_array(self::$module_scripts) || !count(self::$module_scripts))
		{
			return '';
		}

		$return = '';

		// Prep our scripts array with only files
		// that actually can be found.
		$scripts = self::find_files(self::$module_scripts, 'js');

		// Mod Scripts are always combined. This allows us to have the
		// working files out of the web root, but still provide a link
		// to the assets.
		$src = self::combine_js($scripts, 'module');

		$attr = array(
			'src'	=> $src,
			'type'	=> 'text/javascript'
		);

		if ($list)
		{
			$return .= '"'. $attr['src'] .'", ';
		}
		else
		{
			$return .= '<script'. self::attributes($attr) ." ></script>\n";
		}

		return trim($return, ', ');

	}//end module_js()

	//--------------------------------------------------------------------

	/**
	 * Does the actual work of generating the inline js code. All code is
	 * wrapped by open and close tags specified in the config file, so that
	 * you can modify it to use your favorite js library.
	 *
	 * It is called by the js() method.
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function inline_js()
	{
		// Are there any scripts to include?
		if (count(self::$inline_scripts) == 0)
		{
			return;
		}

		$output = '';

		// Create our shell opening
		$output .= '<script type="text/javascript">' . "\n";
		$output .= self::$ci->config->item('assets.js_opener') ."\n\n";

		// Loop through all available scripts
		// inserting them inside the shell.
		foreach(self::$inline_scripts as $script)
		{
			$output .= $script . "\n";
		}

		// Close the shell.
		$output .= "\n" . self::$ci->config->item('assets.js_closer') . "\n";
		$output .= '</script>' . "\n";

		return $output;

	}//end inline_js()

	//--------------------------------------------------------------------

	/**
	 * Does the actual work of generating the combined js code.
	 *
	 * It is called by the external_js() and module_js() methods.
	 *
	 * @access public
	 * @static
	 *
	 * @param array $files An array of files to be combined.
	 * @param string $type Either a string 'module' or empty. Helps determine the file name.
	 *
	 * @return string
	 */
	public static function combine_js($files=array(), $type='')
	{
		// Are there any scripts to include?
		if (is_array($files) && count($files) == 0)
		{
			return;
		}

		$output = '';

		$theme = Template::get('active_theme');
		$theme = empty($theme) ? Template::get('default_theme') : $theme;

		// get the module name
		$module_name = self::$ci->router->fetch_module();

		// get the uri segments
		$uri_segments = self::$ci->uri->segment_array();

		// get the module name from the uri segment
		$context_key = array_search(self::$ci->router->fetch_class(), $uri_segments);
		if (FALSE !== $context_key) {
			$module_key = $context_key + 1;
			if (isset($uri_segments[$module_key])) {
				$module_name = $uri_segments[$module_key];
			}
		}

		$file_name = trim($theme,'/') .'_'. $module_name .'_'. self::$ci->router->fetch_class();

		if (self::$ci->config->item('assets.encrypt_name'))
		{
			$file_name = md5($file_name);
		}

		$file_name .= $type == 'module' ? '_mod' : '_combined';

		// Create our shell opening
		if (self::generate_file($files, $file_name, 'js')) {
			$output .= base_url() .self::$asset_base . '/' . self::$asset_cache_folder . '/' . $file_name.".js";
		}

		return $output;

	}//end combine_js()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !IMAGE METHODS
	//--------------------------------------------------------------------

	/**
	 * A simple helper to build image tags.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $image       The name of the image file
	 * @param array  $extra_attrs An of key/value pairs that are attributes that should be added to the tag, such as height, width, class, etc.
	 *
	 * @return string A string containing the image tag.
	 */
	public static function image($image=null, $extra_attrs=array())
	{
		if (empty($image)) return '';

		$attrs = array(
			'src'	=> $image,
			'alt'	=> isset($extra_attrs['alt']) ? $extra_attrs['alt'] : ''
		);

		unset($extra_attrs['alt']);

		$attrs = array_merge($attrs, $extra_attrs);

		return '<img'. self::attributes($attrs) ." />\n";

	}//end image()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !UTILITY METHODS
	//--------------------------------------------------------------------

	/**
	 * Deletes all asset cache files from the assets/cache folder.
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function clear_cache()
	{
		self::$ci->load->helper('file');

		$cache_path = FCPATH . '/' . self::$asset_base . '/' . self::$asset_cache_folder . '/';

		delete_files($cache_path);

		//Write the index.html file back in
		$indexhtml_data = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
		write_file($cache_path.'/index.html', $indexhtml_data);

	}//end clear_cache()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Converts an array of attribute into a string
	 *
	 * @access private
	 * @static
	 * @author Dan Horrigan (Stuff library)
	 *
	 * @param array $attributes An array of key/value pairs representing the attributes.
	 *
	 * @return string A string containing the rendered attributes.
	 */
	private static function attributes($attributes=null)
	{
		if (empty($attributes))
		{
			return '';
		}

		$final = '';
		if (is_array($attributes))
		{
			foreach ($attributes as $key => $value)
			{
				if ($value === NULL)
				{
					continue;
				}

				$final .= ' '.$key.'="'.htmlspecialchars($value, ENT_QUOTES).'"';
			}
		}

		return $final;

	}//end attributes()


	//--------------------------------------------------------------------

	/**
	 * Locates file by looping through the active and default themes, and
	 * then the assets folder (as specified in the config file).
	 *
	 * Files are searched for in this order...
	 *     1 - active_theme/
	 *     2 - active_theme/type/
	 *     3 - default_theme/
	 *     4 - default_theme/type/
	 *     5 - asset_base/type
	 *
	 * Where 'type' is either 'css' or 'js'.
	 *
	 * If the file is not found, it is removed from the array. If the file
	 * is found, a full url is created, using base_path(), unless the path
	 * already includes 'http' at the beginning of the filename, in which case
	 * it is simply included in the return files.
	 *
	 * For CSS files, if a script of the same name is found in both the
	 * default_theme and the active_theme folders (or their type sub-folder),
	 * they are both returned, with the default_theme linked to first, so that
	 * active_theme styles can override those in the default_theme without
	 * having to recreate the entire stylesheet.
	 *
	 * @access private
	 * @static
	 *
	 * @param array  $files     Array of files
	 * @param string $file_name Name of the file to generate
	 * @param string $file_type Either 'css' or 'js'.
	 * @param string $type
	 *
	 * @return type True if file generated successfully, False if there were errors.
	 */
	private function generate_file($files=array(), $file_name, $file_type='css', $type='')
	{
		if (count($files) == 0)
		{
			// While the file wasn't actually created,
			// there weren't any errors, either.
			return TRUE;
		}

		// Where to save the combined file to.
		$cache_path = FCPATH . '/' . self::$asset_base . '/' . self::$asset_cache_folder . '/';

		// full file path - without the extension
		$file_path = $cache_path.$file_name;

		if (self::$ci->config->item("assets.{$type}_minify"))
		{
			$file_path .= '.min';
		}

		$file_path .= '.'.$file_type;

		$modified_time	= 0;			// Holds the last modified date of all included files.
		$actual_file_time = 0;		// The modified time of the combined file.

		// If the combined file already exists,
		// we need to grab the last modified time.
		if (is_file($file_path))
		{
			$actual_file_time = filemtime($file_path);
		}

		foreach ($files as $key => $file)
		{

			// Javascript
			if ($file_type == 'js')
			{
				if (is_array($file))
				{
					$app_file = $file['server_path'];
				}
				else
				{
					$app_file = FCPATH . '/'.str_replace(base_url(), '', $file);
				}
				$app_file = strpos($app_file, '.js') ? $app_file : $app_file .'.js';
				$files_array[$key] = $app_file;
			}
			// CSS
			else
			{
				$app_file = $file['server_path'];
				$files_array[$key] = $app_file;
			}

			if ($file == 'global')
			{
				$files_array[$key] = $app_file;
			}

			// By this point, we already know that the files exist,
			// so just grab the modified time.
			$modified_time = max(filemtime($app_file), $modified_time);
		}//end foreach

		$asset_output = '';

		if ($actual_file_time < $modified_time)
		{
			// write to the file
			foreach ($files_array as $key => $file)
			{
				$file_output = file_get_contents($file);

				if (!empty($file_output))
				{
					$asset_output .= $file_output. PHP_EOL;
				}
			}

			switch ($file_type)
			{
				case 'js':
					if (config_item('assets.js_minify'))
					{
						$asset_output = JSMin::minify($asset_output);
					}
					break;
				case 'css':
					if (config_item('assets.css_minify'))
					{
						$asset_output = CSSMin::minify($asset_output);
					}
					break;
				default:
					throw new LoaderException("Unknown file type - {$file_type}.");
					break;
			}

			self::$ci->load->helper('file');

			if (!is_dir($cache_path))
			{
				@mkdir($cache_path);
			}

			if (! write_file($file_path, $asset_output))
			{
				return FALSE;
			}
		}
		elseif ($actual_file_time == 0)
		{
			return FALSE;
		}

		return TRUE;

	}//end generate_file()


	//--------------------------------------------------------------------

	/**
	 * Locates file by looping through the active and default themes, and
	 * then the assets folder (as specified in the config file).
	 *
	 * Files are searched for in this order...
	 *     1 - active_theme/
	 *     2 - active_theme/type/
	 *     3 - default_theme/
	 *     4 - default_theme/type/
	 *     5 - asset_base/type
	 *
	 * Where 'type' is either 'css' or 'js'.
	 *
	 * If the file is not found, it is removed from the array. If the file
	 * is found, a full url is created, using base_path(), unless the path
	 * already includes 'http' at the beginning of the filename, in which case
	 * it is simply included in the return files.
	 *
	 * For CSS files, if a script of the same name is found in both the
	 * default_theme and the active_theme folders (or their type sub-folder),
	 * they are both returned, with the default_theme linked to first, so that
	 * active_theme styles can override those in the default_theme without
	 * having to recreate the entire stylesheet.
	 *
	 * @access private
	 *
	 * @param array  $files              An array of file names to search for.
	 * @param string $type               Either 'css' or 'js'.
	 * @param bool   $bypass_inheritance
	 *
	 * @return array The complete list of files with url paths.
	 */
	private function find_files($files=array(), $type='css', $bypass_inheritance=FALSE)
	{
		// Grab the theme paths from the template library.
		$paths = Template::get('theme_paths');
		$site_path = Template::get('site_path');
		$active_theme = Template::get('active_theme');
		$default_theme = Template::get('default_theme');

		$new_files = array();

		$clean_type = $type;
		$type = '.'. $type;

		if (self::$debug)
		{
			echo "Active Theme = {$active_theme}<br/>";
			echo "Default Theme = {$default_theme}<br/>";
			echo "Site Path = {$site_path}<br/>";
			echo 'File(s) to find: '; print_r($files);
		}

		foreach ($files as $file)
		{
			// If it's an array, we're dealing with css and it has both
			// a file and media keys. Store them for later use.
			if (is_array($file))
			{
				if ($type == '.css')
				{
					$media = $file['media'];
				}
				$module	= isset($file['module']) ? $file['module'] : '';
				$file = $file['file'];
			}

			// Strip out the file type for consistency
			$file = str_replace($type, '', $file);
			$rtl = 'rtl';

			//Check for HTTPS or HTTP connection
			if (isset($_SERVER['HTTPS']))
			{
				$http_protocol = "https";
			}
			else
			{
				$http_protocol = "http";
			}

			// If it contains an external URL, we're all done here.
			if (strpos((string)$file, $http_protocol, 0) !== FALSE)
			{
				$new_files[] = !empty($media) ? array('file'=>$file, 'media'=>$media) : $file;
				continue;
			}

			$found = FALSE;

			// Is it a module file?
			if (!empty($module))
			{
				$file_path_name = $file;
				$path = module_file_path($module, 'assets', $file_path_name . $type);
				if (empty($path))
				{
					$file_path_name = $clean_type . '/' . $file;
					// Try assets/type folder
					$path = module_file_path($module, 'assets', $file_path_name . $type);
				}

				if (!empty($path) && lang('bf_language_direction') == $rtl)
				{
					$path_rtl = module_file_path($module, 'assets', $file_path_name . '-' . $rtl . $type);
					if (!empty($path_rtl))
						$path = $path_rtl;
				}

				if (self::$debug)
				{
					echo "[Assets] Lookin for MODULE asset at: {$path}<br/>" . PHP_EOL;
				}

				if (!empty($path))
				{
					$file_path = '';

					$file = array(
						'file'			=> $file_path,
						'server_path'	=> $path
					);
					if (isset($media))
					{
						$file['media'] = $media;
					}

					$new_files[] = $file;
				}

				continue;
			}
			// Non-module files
			else
			{
				$media = empty($media) ? '' : $media;
				// We need to check all of the possible theme_paths
				foreach ($paths as $path)
				{
					if (self::$debug) {
						echo "[Assets] Looking in: <ul><li>{$site_path}{$path}/{$default_theme}{$file}{$type}</li>" . PHP_EOL;
						echo "<li>{$site_path}{$path}/{$default_theme}{$type}/{$file}{$type}</li>" . PHP_EOL;

						if (!empty($active_theme))
						{
							echo "<li>{$site_path}{$path}/{$active_theme}{$file}{$type}</li>" . PHP_EOL;
							echo "<li>{$site_path}{$path}/{$active_theme}{$type}/{$file}{$type}</li>" . PHP_EOL;
						}

						echo '<li>'. $site_path . self::$asset_base ."/{$type}/{$file}{$type}</li>" . PHP_EOL;

						echo '</ul>' . PHP_EOL;
					}

					/*
					 * If default_theme and active_theme are the same,
					 * checking the default_theme would just repeat the
					 * active_theme section below, resulting in duplicates
					 */
					if (!$bypass_inheritance && $default_theme !== $active_theme)
					{
						/*
							DEFAULT THEME

							First, check the default theme. Add it to the array. We check here first so that it
							will get overwritten by anything in the active theme.
						*/
						if (($file_array = self::get_file_array($site_path, $path . '/' . $default_theme, $file, $type, $media)))
						{
							$new_files[] = $file_array;
							$found = TRUE;
						}
						/*
							If it wasn't found in the default theme root folder, look in default_theme/$type/
						*/
						else if (($file_array = self::get_file_array($site_path, $path . '/' . $default_theme . $clean_type . '/', $file, $type, $media)))
						{
							$new_files[] = $file_array;
							$found = TRUE;
						}
					}

					/*
						ACTIVE THEME

						By grabbing a copy from both the default theme and the active theme, we can
						handle simple CSS-only overrides for a theme, completely changing it's appearance
						through a simple child css file.
					*/
					if (!empty($active_theme)) // separated this because the else if below should not run if $active_theme is empty
					{
						if (($file_array = self::get_file_array($site_path, $path . '/' . $active_theme, $file, $type, $media)))
						{
							$new_files[] = $file_array;
							$found = TRUE;
						}
						/*
							If it wasn't found in the active theme root folder, look in active_theme/$type/
						*/
						else if (($file_array = self::get_file_array($site_path, $path . '/' . $active_theme . $clean_type . '/', $file, $type, $media)))
						{
							$new_files[] = $file_array;
							$found = TRUE;
						}
					}

					/*
						ASSET BASE

						If the file hasn't been found, yet, we have one more place to look for it:
						in the folder specified by 'assets.base_folder', and under the $type sub-folder.
					*/
					if (!$found)
					{
						// Assets/type folder
						if (($file_array = self::get_file_array($site_path, self::$asset_base . '/' . $clean_type . '/', $file, $type, $media)))
						{
							$new_files[] = $file_array;
						}

						/*
							ASSETS ROOT

							Finally, one last check to see if it is simply under assets/. This is useful for
							keeping collections of scripts (say, TinyMCE or MarkItUp together for easy upgrade.
						*/
						else if (($file_array = self::get_file_array($site_path, self::$asset_base . '/', $file, $type, $media)))
						{
							$new_files[] = $file_array;
						}
					}	// if (!$found)
				}	// foreach ($paths as $path)
			}	// else
		}//end foreach

		return $new_files;

	}//end find_files()

	//--------------------------------------------------------------------

	private function get_file_array($site_path='', $path='', $file='', $type='', $media='')
	{
		if (empty($file) || empty($type))
		{
			return false;
		}
		$return = false;
		$rtl = 'rtl';
		$file_name = $path.$file;
		if (is_file($site_path.$file_name.$type))
		{
			$file_path = base_url().$file_name.$type;
			$server_path=$site_path.$file_name.$type;
			if (lang('bf_language_direction') == $rtl)
			{
				if (is_file($site_path.$file_name.'-'.$rtl.$type))
				{
					$file_path = base_url().$file_name.'-'.$rtl.$type;
					$server_path=$site_path.$file_name.'-'.$rtl.$type;
				}
			}
			$return = empty($media) ? $file_path : array('file' => $file_path, 'media' => $media, 'server_path' => $server_path);

			if (self::$debug) echo "[Assets] Found file at: <b>{$site_path}{$file_name}{$type}</b><br/>";
		}
		return $return;
	}
}//end class


/**
 * Helpers: Assets Helpers
 *
 * The following helpers are related and dependent on the Assets class.
 *
 */



/**
 * Returns full site url to assets javascript folder.
 *
 * @access public
 *
 * @return string Returns full site url to assets javascript folder.
 */
function js_path()
{
	return Assets::assets_url ('js');

}//end js_path()


/**
 * Returns full site url to assets images folder.
 *
 * @access public
 *
 * @return string Returns full site url to assets images folder.
 */
function img_path()
{
	return Assets::assets_url ('image');

}//end img_path()


/**
 * Returns full site url to assets css folder.
 *
 * @access public
 *
 * @return string Returns full site url to assets css folder.
 */
function css_path()
{
	return Assets::assets_url ('css');

}//end css_path()


/**
 * Returns full site url to assets base folder.
 *
 * @access public
 *
 * @return string Returns full site url to assets base folder.
 */
function assets_path()
{
	return Assets::assets_url ();

}//end assets_path()


/* End of file assets.php */
/* Location: ./application/libraries/assets.php */