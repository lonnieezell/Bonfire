<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

/**
 * Assets Class
 *
 * The Assets class helps manage CSS, JavaScript, and Image assets.
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
	 * The base folder in which all of the assets are stored.
	 *
	 * Relative to the template.site_root config setting
	 *
	 * @access private
	 * @static
	 *
	 * @var string
	 */
	private static $asset_base = 'assets/';

	/**
	 * The name of the cache folder for the generated assets.
	 *
	 * Relative to $asset_base
	 *
	 * @access private
	 * @static
	 *
	 * @var string
	 */
	private static $asset_cache_folder = 'cache';

	/**
	 * The names of the folders for the various assets.
	 *
	 * Relative to $asset_base. These are set in the assets config file, and
	 * default to 'js', 'css', and 'images'.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $asset_folders = array(
		'css'		=> 'css',
		'js'		=> 'js',
		'images'	=> 'images',
	);

	/**
	 * Stores the CodeIgniter core object.
	 *
	 * @access protected
	 * @static
	 *
	 * @var object
	 */
	protected static $ci;

	/**
	 * Display debug messages
	 *
	 * @access private
	 * @static
	 *
	 * @var bool
	 */
	private static $debug = false;

	/**
	 * Holds the external (linked) scripts to be placed at the end of the page.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array
	 */
	protected static $external_scripts = array();

	/**
	 * Include global css/js files
	 *
	 * @access private
	 * @static
	 *
	 * @var bool
	 */
	private static $globals = true;

	/**
	 * Holds the inline scripts to be placed at the end of the page.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array
	 */
	protected static $inline_scripts = array();

	/**
	 * Holds the module scripts which will be combined into one js file to be
	 * placed at the end of the page.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array
	 */
	protected static $module_scripts = array();

	/**
	 * Holds the module css files to be placed at the beginning of the page.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $module_styles = array();

	/**
	 * Value which indicates a language is read from right to left.
	 *
	 * Compared to lang('bf_language_direction') to indicate direction and
	 * appended to the file name to check for existence of the file when
	 * lang('bf_language_direction') == $rtl_postfix
	 *
	 * @access private
	 * @static
	 *
	 * @var string
	 */
	private static $rtl_postfix = 'rtl';

	/**
	 * Holds the css files to be placed at the beginning of the page.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $styles = array();

	/**
	 * Constructor
	 *
	 * Support for loading in CI. Gets the CI instance and calls the init()
	 * method.
	 *
	 * @return void
	 */
	public function __construct()
	{
		self::$ci =& get_instance();
		self::init();
	}

	/**
     * Initialize the assets library
     *
	 * Loads the config file and inserts the base css and js into the arrays for
	 * later use. This ensures that the base files will be processed in the
	 * order the user expects.
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function init()
	{
		// It is recommended to combine as many config files as sensible into a
        // single file for performance reasons. If the config entry is already
        // available, don't load the file.
		if (config_item('assets.base_folder') === false) {
			self::$ci->config->load('application');
		}

		// Store the config settings
		self::$asset_base    = self::$ci->config->item('assets.base_folder');
		self::$asset_folders = self::$ci->config->item('assets.asset_folders');

		log_message('debug', 'Assets library loaded.');
	}

	//--------------------------------------------------------------------
	// !GLOBAL METHODS
	//--------------------------------------------------------------------

	/**
     * Set the library to include global CSS and JS files
     *
	 * If $include is set to true, global includes (like the default media type
	 * CSS and global.js files) are automatically included in css() and js()
	 * output.
	 *
	 * @access public
	 * @param bool $include true to include (default) or false to exclude
	 *
	 * @return void
	 */
	public static function set_globals($include=true)
	{
		self::$globals = (bool)$include;
	}

	//--------------------------------------------------------------------
	// !STYLESHEET METHODS
	//--------------------------------------------------------------------

	/**
     * Render links to stylesheets
     *
	 * Prepends the $asset_url.
	 *
	 * If a single filename is passed, a link is created for that file.
	 *
	 * If multiple files are passed, merges the list of styles with the list of
	 * styles previously added with add_css(), and outputs the links for all of
	 * the files.
	 *
	 * If no style is passed, defaults to the theme's style.css file.
	 *
	 * When passing a filename, the filepath should be relative to the site
	 * root (where index.php resides).
	 *
	 * @todo Determine whether a passed filename should be relative to the site
	 * root or the $asset_url
	 *
	 * @access public
	 * @static
	 *
	 * @param mixed  $style              The style(s) to have links rendered for.
	 * @param string $media              The media to assign to the style(s) being passed in.
	 * @param bool   $bypass_inheritance If TRUE, will skip the check for parent theme styles.
	 * @param bool	 $bypass_module      If TRUE, will not output the css file named after the controller or the module styles.
	 *
	 * @return string A string containing all necessary links.
	 */
	public static function css($style=null, $media='screen', $bypass_inheritance=false, $bypass_module=false)
	{
		$styles = array();
		$return = '';

		// Debugging issues with media being set to 1 on module_js
		if ($media == '1') {
			$media = 'screen';
		}

		// If no style(s) has been passed in, use all that have been added.
		if (empty($style) && self::$globals) {
			// Make sure to include a file based on media type.
			$styles[] = array(
				'file'	=> $media,
				'media'	=> $media,
			);

			$styles = array_merge(self::$styles, $styles);
		}
		// If an array has been passed, merge it with any added styles.
		elseif (is_array($style)) {
			$styles = array_merge($style, self::$styles);
		}
		// If a single style has been passed in, render it only.
		else {
			$styles[] = array(
				'file'	=> $style,
				'media'	=> $media,
			);
		}

		if ($bypass_module == false) {
			// Add a style named for the controller so it will be looked for.
			$styles[] = array(
				'file'	=> self::$ci->router->fetch_class(),
				'media' => $media,
			);

			$mod_styles	= self::find_files(self::$module_styles, 'css', $bypass_inheritance);
		}

		$styles = self::find_files($styles, 'css', $bypass_inheritance);

		$combine = self::$ci->config->item('assets.css_combine');
		if ( ! $combine) {
    		// Loop through the styles, spitting out links for each one.
			foreach ($styles as $s) {
				if (is_array($s)) {
					if (substr($s['file'], -4) != '.css') {
						$s['file'] .= '.css';
					}
				} else {
					if (substr($s, -4) != '.css') {
						$s .= '.css';
					}
				}

				$attr = array(
					'rel'	=> 'stylesheet',
					'type'	=> 'text/css',
					'href'	=> is_array($s) ? $s['file'] : $s,
					'media'	=> !empty($s['media']) ? $s['media'] : $media,
				);

				$return .= '<link' . self::attributes($attr) . " />\n";
			}
		} else {
            // Add the combined css
			$return .= self::combine_css($styles, $media);
		}

		if ($bypass_module == false) {
			// Make sure we include module styles
			$return .= self::combine_css($mod_styles, $media, 'module');
		}

		return $return;
	}//end css()

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
	public static function combine_css($files=array(), $media='screen', $type='')
	{
		// Are there any scripts to include?
		if (count($files) == 0) {
			return;
		}

		$output = '';
		$min	= '';

		// Add the theme name to the filename
		// to account for different frontend/backend themes.
		$theme = trim(Template::get('active_theme'), '/');
		$theme = empty($theme) ? trim(Template::get('default_theme'), '/') : $theme;
		$file_name = $theme . '_' . self::$ci->router->fetch_module() . '_' . self::$ci->router->fetch_class();

		if (self::$ci->config->item('assets.encrypt_name') == true) {
			$file_name = md5($file_name);
		}

		if ($type == 'module') {
			$file_name .= '_mod';
		} else {
			$file_name .= '_combined';
		}

		if (self::$ci->config->item('assets.css_minify') == true) {
			// Don't add .min to $file_name here, because $file_name is passed
            // to generate_file(). However, it needs to be added to the href
            // attribute below.
			$min = '.min';
		}

		// Debugging issues with media being set to 1 on module_js
		if ($media == '1') {
			$media = 'screen';
		}

		// Create our link attributes
		$attr = array(
			'rel'	=> 'stylesheet',
			'type'	=> 'text/css',
			'href'	=> base_url() . self::$asset_base . '/' . self::$asset_cache_folder . "/{$file_name}{$min}.css",
			'media'	=> $media,
		);

		if (self::generate_file($files, $file_name, 'css')) {
			$output .= '<link' . self::attributes($attr) . " />\n";
		}

		return $output;
	}//end combine_css()

	/**
	 * Add a file to the CSS queue
	 *
	 * @access public
	 * @static
	 *
	 * @param mixed  $style The style(s) to be added
	 * @param string $media The type of media the stylesheet styles.
	 * @param bool	 $prepend If true, the file(s) will be added to the beginning of the style array
	 *
	 * @return void
	 */
	public static function add_css($style=null, $media='screen', $prepend=false)
	{
		if (empty($style)) {
			return;
		}

		// Debugging issues with media being set to 1
		if ($media == '1') {
			$media = 'screen';
		}

		$style_array = array();

		// Add a string
		if (is_string($style)) {
            $style = array(
                'field' => $style,
                'media' => $media,
            );
		}

		// Add an array
		if (is_array($style) && count($style)) {
			foreach ($style as $s) {
				$style_array[] = array(
					'file'	=> $s,
					'media'	=> $media,
				);
			}
		}

		if ($prepend) {
			self::$styles = array_merge($style_array, self::$styles);
		} else {
			self::$styles = array_merge(self::$styles, $style_array);
		}
	}//end add_css()

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
		if (empty($file_path)) {
			return;
		}

		if ($media == '1') {
			$media = 'screen';
		}

		// Add a string
		if (is_string($file_path)) {
            $file_path = array(
				'module' => $module,
				'file'	=> $file_path,
				'media' => $media
			);
		}

        // Add an array
		if (is_array($file_path) && count($file_path)) {
			foreach ($file_path as $s) {
				self::$module_styles[] = array(
					'module' => $module,
					'file'	=> $s,
					'media'	=> $media
				);
			}
		}
	}//end add_module_css()

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
	public static function add_js($script=null, $type='external', $prepend=false)
	{
		if (empty($script)) {
			return;
		}

		$type .= '_scripts';
		$script_array = array();

		if (is_string($script)) {
            $script = array($script);
		}

        if (is_array($script) && count($script)) {
			foreach ($script as $s) {
                // Remove any obvious duplicates
				if ( ! in_array($s, self::$$type)) {
					$script_array[] = $s;
				}
			}
		}

		if ($prepend) {
			self::${$type} = array_merge($script_array, self::${$type});
		} else {
			self::${$type} = array_merge(self::${$type}, $script_array);
		}
	}//end add_js()

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
		if (empty($file)) {
			return;
		}

		// Add a string
		if (is_string($file)) {
            $file = array($file);
		}

        // Add an array
		if (is_array($file) && count($file)) {
			foreach ($file as $s) {
				self::$module_scripts[] = array(
					'module' => $module,
					'file'   => $s,
				);
			}
		}
	}//end add_module_js()

	/**
	 * Renders links to all javascript files including External, Module, and
	 * Inline
	 *
	 * If a single filename is passed, it will only create a single link for
	 * that file, otherwise, it will include any javascript files that have been
	 * added with add_js().
	 *
	 * When passing a filename, the filepath should be relative to the site root
	 * (where index.php resides).
	 *
	 * @access public
	 * @static
	 *
	 * @param mixed  $script The name of the script to link to (optional)
	 * @param string $type Whether the script should be linked to externally or
	 * rendered inline. Acceptable values: 'external' or 'inline'
	 *
	 * @return string Returns all Scripts located in External JS, Module JS, and
	 * Inline JS in that order.
	 */
	public static function js($script=null, $type='external')
	{
        if ( ! empty($script)) {
            if (is_string($script) && $type == 'external') {
                return self::external_js($script);
            }

            self::add_js($script, $type);
        }

		// Render the scripts/links
		$output  = self::external_js();
		$output .= self::module_js();
		$output .= self::inline_js();

		return $output;
	}//end js()

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
	 * @param bool	$bypass_globals	If TRUE, bypass global scripts for this call to external_js
	 *
	 * @return string
	 */
	public static function external_js($new_js=null, $list=false, $add_ext=true, $bypass_globals=false)
	{
		$return = '';
		$scripts = array();
        $renderSingleScript = false;

		if (empty($new_js)) {
            $scripts = self::$external_scripts;
		}
		// If scripts were passed, they override all other scripts.
        elseif (is_string($new_js)) {
            $scripts[] = $new_js;
            $renderSingleScript = true;
        } elseif (is_array($new_js)) {
            $scripts = $new_js;
        }

		// Make sure we check for a 'global.js' file.
		if ($bypass_globals == false && self::$globals) {
			$scripts[] = 'global';
		}

		// Add a style named for the controller so it will be looked for.
		$scripts[] = self::$ci->router->fetch_class();

		// Prep scripts array with only files that can actually be found.
		$scripts = self::find_files($scripts, 'js');

		// Either combine the files into one...
		if ( ! $renderSingleScript && ! $list
            && self::$ci->config->item('assets.js_combine')
		   ) {
			$attr = array('type'  => 'text/javascript');
            $attr['src'] = self::combine_js($scripts);

            $return .= '<script' . self::attributes($attr) . "></script>\n";
		}
		// Or generate individual links
		else {
			// Check for HTTPS or HTTP connection
			$http_protocol = ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';

			foreach ($scripts as $script) {
				if (true === $add_ext && substr($script, -3) != '.js') {
					$script .= '.js';
				}
				$attr = array('type' => 'text/javascript');

				// If $script has a full url built in, leave it alone
				if (strpos($script, $http_protocol . ':') !== false	// Absolute URL with current protocol, which should be more likely
					|| strpos($script, '//') === 0					// Protocol-relative URL
					|| strpos($script, 'https:') !== false			// We should assume $http_protocol is most likely 'http', so check 'https' next
					|| strpos($script, 'http:') !== false			// Finally, check 'http' in case $http_protocol is 'https'
				   ) {
					$attr['src'] = $script;
				}
				// Otherwise, build the full url
                elseif (strpos($script, base_url()) === 0) {
                    $attr['src'] = self::path(site_url(), $script);
                } else {
					$attr['src'] = self::path(site_url(), self::$asset_base, self::$asset_folders['js'], $script);
				}

				if ($list) {
					$return .= '"' . $attr['src'] . '", ';
				} else {
					$return .= '<script' . self::attributes($attr) . "></script>\n";
				}
			}
		}

		return trim($return, ', ');
	}//end external_js()

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
	public static function assets_url($type=null)
	{
		$url = '';

		// Add Assets base and resource type folders if needed
		if ($type !== null && array_key_exists($type, self::$asset_folders)) {
            $url = self::path(base_url(), self::$asset_base, self::$asset_folders[$type]) . '/';
		}
        // Add Assets Base Folder
        else {
            $url = self::path(base_url(), self::$asset_base) . '/';
        }

		// Cleanup, just to be safe
		$url = str_replace('//', '/', $url);
		$url = str_replace(':/', '://', $url);

		return $url;
	}//end assets_url()

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
	public static function module_js($list=false)
	{
        // Don't bother with count() if is_array() fails
		if ( ! (is_array(self::$module_scripts) && count(self::$module_scripts))) {
			return '';
		}

		// Prep the scripts array with only files that can actually be found.
		$scripts = self::find_files(self::$module_scripts, 'js');

		// Mod Scripts are always combined. This allows the working files to be
		// out of the web root, but still provides a link to the assets.
		$src = self::combine_js($scripts, 'module');

		$attr = array(
			'src'	=> $src . '?_dt=' . time(),
			'type'	=> 'text/javascript',
		);

		if ($list) {
			return '"' . $attr['src'] . '"';
		}

        return '<script' . self::attributes($attr) . "></script>\n";
	}//end module_js()

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
		if (empty(self::$inline_scripts)) {
			return;
		}

		// Create our shell opening
		$output = "<script type='text/javascript'>\n" .
			self::$ci->config->item('assets.js_opener') . "\n\n";

		// Loop through all available scripts inserting them inside the shell.
		foreach (self::$inline_scripts as $script) {
			$output .= $script . "\n";
		}

		// Close the shell.
		$output .= "\n" . self::$ci->config->item('assets.js_closer') .
            "\n</script>\n";

		return $output;
	}//end inline_js()

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
		if (is_array($files) && count($files) == 0) {
			return;
		}

		$output = '';
		$min	= '';
		$theme = Template::get('active_theme');
		$theme = empty($theme) ? Template::get('default_theme') : $theme;

		// Get the module name
		$module_name = self::$ci->router->fetch_module();

		// Get the uri segments
		$uri_segments = self::$ci->uri->segment_array();
        $className = self::$ci->router->fetch_class();

		// Get the context name from the uri segment
		$context_key = array_search($className, $uri_segments);
		if (false !== $context_key) {
			$module_key = $context_key + 1;
			if (isset($uri_segments[$module_key])) {
				$module_name = $uri_segments[$module_key];
			}
		}

		$file_name = trim($theme, '/') . "_{$module_name}_{$className}";

		if (self::$ci->config->item('assets.encrypt_name')) {
			$file_name = md5($file_name);
		}
		$file_name .= $type == 'module' ? '_mod' : '_combined';

		if (self::$ci->config->item('assets.js_minify') == true) {
			// .min must be added to the URL below, but since $file_name is
            // passed to generate_file(), .min can't be added to $file_name, yet
			$min = '.min';
		}

		// Create the shell opening
		if (self::generate_file($files, $file_name, 'js')) {
			$output .= self::path(base_url(), self::$asset_base, self::$asset_cache_folder, "{$file_name}{$min}.js");
		}

		return $output;
	}//end combine_js()

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
	 * @param bool	 $suppress_eol Optionally suppresses the newline after the img tag
	 *
	 * @return string A string containing the image tag.
	 */
	public static function image($image=null, $extra_attrs=array(), $suppress_eol=false)
	{
		if (empty($image)) {
			return '';
		}

		$attrs = array(
			'src'	=> $image,
			'alt'	=> isset($extra_attrs['alt']) ? $extra_attrs['alt'] : '',
		);
		unset($extra_attrs['alt']);

		$attrs = array_merge($attrs, $extra_attrs);
		$return = '<img' . self::attributes($attrs) . ' />';

		return ($suppress_eol ? $return : $return . PHP_EOL);
	}//end image()

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
        if ( ! function_exists('delete_files') || ! function_exists('write_file')) {
            self::$ci->load->helper('file');
        }

		$site_path = Template::get('site_path');
        $cache_path = self::path($site_path, self::$asset_base, self::$asset_cache_folder) . '/';

		delete_files($cache_path);

		// Write the index.html file back in
		$indexhtml_data = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
		write_file("{$cache_path}index.html", $indexhtml_data);
	}//end clear_cache()

    /**
     * Build a path from a variable number of arguments
     *
     * @return String    The path
     */
    protected static function path()
    {
        $params = func_get_args();
        $path = array();
        $sep = '/';

        foreach ($params as &$param) {
            $param = rtrim($param, $sep);
            if ( ! empty($param)) {
                $path[] = $param;
            }
        }

        return implode($sep, $path);
    }

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
		if (empty($attributes)) {
			return '';
		}

		$final = '';
		if (is_array($attributes)) {
			foreach ($attributes as $key => $value) {
				if ($value === null) {
					continue;
				}

				$final .= ' ' . $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '"';
			}
		}

		return $final;
	}//end attributes()

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
	 *
	 * @return bool True if file generated successfully, False if there were errors.
	 */
	private static function generate_file($files=array(), $file_name, $file_type='css')
	{
		if (count($files) == 0) {
			// While the file wasn't actually created, there were no errors
			return true;
		}

		$site_path = Template::get('site_path');

		// Where to save the combined file
        $cache_path = self::path($site_path, self::$asset_base, self::$asset_cache_folder) . '/';

		// Full file path - without the extension
		$file_path = $cache_path . $file_name;

        // Append .min if the file is to be minified
		if (self::$ci->config->item("assets.{$file_type}_minify")) {
			$file_path .= '.min';
		}

		$file_path .= ".{$file_type}"; // Append the file extension
		$modified_time	= 0;	// Holds the last modified date of all included files.
		$actual_file_time = 0;	// The modified time of the combined file.

		// If the combined file already exists, grab the last modified time.
		if (is_file($file_path)) {
			$actual_file_time = filemtime($file_path);
		}

		foreach ($files as $key => $file) {
			$app_file = '';

            if (is_array($file)) {
                $app_file = $file['server_path'];
            } else {
                $app_file = self::path($site_path, str_replace(base_url(), '', $file));
            }

			// Javascript
			if ($file_type == 'js') {
                // Using strripos and substr because rtrim was giving some odd
                // results (for instance, rtrim('tickets.js', '.js');
                // would return 'ticket')
                $pos = strripos($app_file, '.js');
                if ($pos !== false) {
                    $app_file = substr($app_file, 0, $pos);
                }

				$app_file .= '.js';
			}

			$files_array[$key] = $app_file;

			// Grab the modified time. If it is higher than the previous files'
            // modified times, keep it
			$modified_time = max(filemtime($app_file), $modified_time);
		}//end foreach

		if ($actual_file_time < $modified_time) {
    		$asset_output = '';
			// Write to the file
			foreach ($files_array as $key => $file) {
				$file_output = file_get_contents($file);
				if ( ! empty($file_output)) {
					$asset_output .= $file_output . PHP_EOL;
				}
			}

			switch ($file_type) {
				case 'js':
					if (config_item('assets.js_minify')) {
						$asset_output = JSMin::minify($asset_output);
					}
					break;

				case 'css':
					if (config_item('assets.css_minify')) {
						$asset_output = CSSMin::minify($asset_output);
					}
					break;

				default:
					throw new LoaderException("Unknown file type - {$file_type}.");
					break;
			}

            if ( ! function_exists('write_file')) {
                self::$ci->load->helper('file');
            }
			if ( ! is_dir($cache_path)) {
				@mkdir($cache_path);
			}

			if ( ! write_file($file_path, $asset_output)) {
				return false;
			}
		} elseif ($actual_file_time == 0) {
			return false;
		}

		return true;
	}//end generate_file()

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
	 * @param array  $files              An array of file names to search for.
	 * @param string $type               Either 'css' or 'js'.
	 * @param bool   $bypass_inheritance If TRUE, will skip the check for parent theme styles.
	 *
	 * @return array The complete list of files with url paths.
	 */
	private static function find_files($files=array(), $type='css', $bypass_inheritance=false)
	{
		// Grab the theme paths from the template library.
		$paths          = Template::get('theme_paths');
		$site_path      = Template::get('site_path');
		$active_theme   = Template::get('active_theme');
		$default_theme  = Template::get('default_theme');

		$new_files = array();
		$clean_type = $type;
		$type = ".{$type}";
		if (self::$debug) {
			echo "Active Theme = {$active_theme}<br/>
				Default Theme = {$default_theme}<br/>
				Site Path = {$site_path}<br/>
				File(s) to find: ";
			print_r($files);
		}

		// Check for HTTPS or HTTP connection
		$http_protocol = ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';

        // Is the language moving right to left?
		$rtl = self::$rtl_postfix;
		$rtl_set = lang('bf_language_direction') == $rtl;

		foreach ($files as &$file) {
			// If it's an array, $file is css and has both 'file' and 'media'
            // keys. Store them for later use.
			if (is_array($file)) {
				if ($type == '.css') {
					$media = $file['media'];
				}
				$module	= isset($file['module']) ? $file['module'] : '';
				$file = $file['file'];
			}

            $file = (string)$file;

			// Strip out the file type for consistency
            // Using strripos and substr because rtrim was giving some odd
            // results (for instance, rtrim('tickets.js', '.js');
            // would return 'ticket')
            $pos = strripos($file, $type);
            if ($pos !== false) {
                $file = substr($file, 0, $pos);
            }

			// If it contains an external URL, there's nothing more to do
			if (strpos($file, $http_protocol . ':') !== false	// Absolute URL with current protocol, which should be more likely
					|| strpos($file, '//') === 0				// Protocol-relative URL
					|| strpos($file, 'https:') !== false		// We should assume $http_protocol is most likely 'http', so check 'https' next
					|| strpos($file, 'http:') !== false			// Finally, check 'http' in case $http_protocol is 'https'
			   ) {
				$new_files[] = empty($media) ? $file : array('file' => $file, 'media' => $media);
				continue;
			}

			$found = false;

			// Non-module files
			if ( empty($module)) {
				$media = empty($media) ? '' : $media;
				// Check all possible theme paths
				foreach ($paths as $path) {
					if (self::$debug) {
						echo "[Assets] Looking in:
							<ul>
								<li>{$site_path}{$path}/{$default_theme}{$file}{$type}</li>
								<li>{$site_path}{$path}/{$default_theme}{$clean_type}/{$file}{$type}</li>";

						if ( ! empty($active_theme)) {
							echo "
								<li>{$site_path}{$path}/{$active_theme}{$file}{$type}</li>
								<li>{$site_path}{$path}/{$active_theme}{$clean_type}/{$file}{$type}</li>";
						}

						echo "
								<li>{$site_path}" . self::$asset_base . "/{$clean_type}/{$file}{$type}</li>
							</ul>" . PHP_EOL;
					}

                    // DEFAULT THEME
                    // First, check the default theme. Add found files to the
                    // array. Anything in the active theme will override it.
                    //
                    // If $default_theme and $active_theme are the same,
                    // checking $default_theme would just repeat the
                    // $active_theme section, resulting in duplicates
					if ( ! $bypass_inheritance && $default_theme !== $active_theme) {
						if (($file_array = self::get_file_array($site_path, "{$path}/{$default_theme}", $file, $type, $media))) {
							$new_files[] = $file_array;
							$found = true;
						}
                        // If it wasn't in the root, check the $type sub-folder
						elseif (($file_array = self::get_file_array($site_path, "{$path}/{$default_theme}{$clean_type}/", $file, $type, $media))) {
							$new_files[] = $file_array;
							$found = true;
						}
					}
                    // ACTIVE THEME
                    // By grabbing a copy from both $default_theme and
                    // $active_theme, simple CSS-only overrides for a theme are
                    // supported, completely changing appearance through a
                    // simple child CSS file
					if ( ! empty($active_theme)) { // separated this because the elseif below should not run if $active_theme is empty
						if (($file_array = self::get_file_array($site_path, "{$path}/{$active_theme}", $file, $type, $media))) {
							$new_files[] = $file_array;
							$found = true;
						}
                        // If it wasn't in the root, check the $type sub-folder
						elseif (($file_array = self::get_file_array($site_path, "{$path}/{$active_theme}{$clean_type}/", $file, $type, $media))) {
							$new_files[] = $file_array;
							$found = true;
						}
					}
                    // ASSET BASE
                    // If the file hasn't been found, yet, look in the
                    // 'assets.base_folder'
					if ( ! $found) {
						// Assets/type folder
						if (($file_array = self::get_file_array($site_path, self::$asset_base . "/{$clean_type}/", $file, $type, $media))) {
							$new_files[] = $file_array;
						}
                        // ASSETS ROOT
                        // One last check to see if it's under assets without
                        // the type sub-folder. Useful for keeping script
                        // collections together
						elseif (($file_array = self::get_file_array($site_path, self::$asset_base . '/', $file, $type, $media))) {
							$new_files[] = $file_array;
						}
					} // if (!$found)
				} // foreach ($paths as $path)
			}
            // Module Files
            else {
				$file_path_name = $file;

				// Try the /module/assets folder
                $assetsFolder = trim(self::$asset_base, '/');
				$path = Modules::file_path($module, $assetsFolder, $file_path_name . $type);

				// If $path is empty, try the /module/assets/type folder
				if (empty($path)) {
					$file_path_name = "{$clean_type}/{$file}";
					$path = Modules::file_path($module, $assetsFolder, $file_path_name . $type);
				}

				// If the language is right-to-left, add -rtl to the file name
				if ( ! empty($path) && $rtl_set) {
					$path_rtl = Modules::file_path($module, $assetsFolder, "{$file_path_name}-{$rtl}{$type}");
					if ( ! empty($path_rtl)) {
						$path = $path_rtl;
					}
				}

				if (self::$debug) {
					echo "[Assets] Looking for MODULE asset at: {$path}<br/>" . PHP_EOL;
				}

				// If the file was found, add it to the array for output
				if ( ! empty($path)) {
					$file = array(
						'file'			=> '',
						'server_path'	=> $path
					);
					if (isset($media)) {
						$file['media'] = $media;
					}

					$new_files[] = $file;
				}
            }
		} //end foreach

		return $new_files;
	}//end find_files()

	/**
	 * Get the file array for a given file and path
	 *
	 * @access private
	 * @static
	 *
	 * @param string $site_path The base server path
	 * @param string $path      The path to the file (appended to base_url() and $site_path)
	 * @param string $file      The name of the file, without the extension
	 * @param string $type      File extension, including '.'
	 * @param string $media     media type, e.g. 'screen' or 'print'
	 *
	 * @return mixed    false if the file wasn't found, the URL of the file if $media is empty,
	 * 					or an array containing the file (URL), media, and server_path
	 */
	private static function get_file_array($site_path='', $path='', $file='', $type='', $media='')
	{
		if (empty($file) || empty($type)) {
			return false;
		}

		$return = false;
		$rtl = self::$rtl_postfix;
		$file_name = $path . $file;

		if (is_file(self::path($site_path, $file_name . $type))) {
            $file_path = self::path(base_url(), $file_name . $type);
            $server_path = self::path($site_path, $file_name . $type);

			if (lang('bf_language_direction') == $rtl) {
				if (is_file(self::path($site_path, "{$file_name}-{$rtl}{$type}"))) {
					$file_path = self::path(base_url(), "{$file_name}-{$rtl}{$type}");
					$server_path = self::path($site_path, "{$file_name}-{$rtl}{$type}");
				}
			}
			$return = empty($media) ? $file_path : array('file' => $file_path, 'media' => $media, 'server_path' => $server_path);

			if (self::$debug) {
                echo "[Assets] Found file at: <strong>{$server_path}</strong><br/>";
            }
		}

		return $return;
	}
}//end Assets class

//--------------------------------------------------------------------
// Helpers: Assets Helpers
//
// The following helpers are related and dependent on the Assets class
//--------------------------------------------------------------------

/**
 * Returns full site url to assets javascript folder.
 *
 * @access public
 *
 * @return string Returns full site url to assets javascript folder.
 */
function js_path()
{
	return Assets::assets_url('js');
}

/**
 * Returns full site url to assets images folder.
 *
 * @access public
 *
 * @return string Returns full site url to assets images folder.
 */
function img_path()
{
	return Assets::assets_url('image');
}

/**
 * Returns full site url to assets css folder.
 *
 * @access public
 *
 * @return string Returns full site url to assets css folder.
 */
function css_path()
{
	return Assets::assets_url('css');
}

/**
 * Returns full site url to assets base folder.
 *
 * @access public
 *
 * @return string Returns full site url to assets base folder.
 */
function assets_path()
{
	return Assets::assets_url();
}

/* End of file assets.php */
/* Location: ./application/libraries/assets.php */