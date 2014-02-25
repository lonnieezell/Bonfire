<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Assets Class
 *
 * The Assets class helps manage CSS, JavaScript, and Image assets.
 *
 * @package    Bonfire\Libraries
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/bonfire/working_with_assets
 * @version    3.1
 *
 */
class Assets
{
	/**
	 * @var object Stores the CodeIgniter core object.
	 */
	protected static $ci;

    /**
     * @var Closure Stores the function used to minify CSS
     */
    protected static $cssMinify;

    /**
     * @var string[] The directories used by the library.
     *
     * 'base' is relative to public, all others are relative to 'base'
     *
     * Combines the previous $asset_base, $asset_cache_folder, and
     * $asset_folders into a single array.
     *
     * Note: trailing and preceding slashes are removed, so 'base' does not
     * include the trailing slash previously included in $asset_base
     */
    protected static $directories = array(
        'base'  => 'assets',
        'cache' => 'cache',
        'css'   => 'css',
        'image' => 'images',
        'js'    => 'js',
    );

    /**
     * @var array[] Holds scripts to place at the end of the page
     */
    protected static $scripts = array(
        'external'  => array(),
        'inline'    => array(),
        'module'    => array(),
    );

	/**
	 * @var string[] Holds the external scripts to place at the end of the page.
	 *
	 * @deprecated since 0.7.1, use $scripts instead
	 */
	protected static $external_scripts = array();

	/**
	 * @var string[] Holds the inline scripts to place at the end of the page.
	 *
	 * @deprecated since 0.7.1, use $scripts instead
	 */
	protected static $inline_scripts = array();

    /**
     * @var Closure Stores the function used to minify JS
     */
    protected static $jsMinify;

	/**
	 * @var string[] Holds the module scripts which will be combined into one js
	 * file to place at the end of the page.
	 *
	 * @deprecated since 0.7.1, use $scripts instead
	 */
	protected static $module_scripts = array();

	/**
	 * @var bool Display debug messages
	 */
	private static $debug = false;

	/**
	 * @var bool Include global css/js files
	 */
	private static $globals = true;

	/**
	 * @var string[] Holds the module css files to place at the beginning of the
	 * page.
	 */
	private static $module_styles = array();

	/**
	 * @var string Value which indicates a language is read from right to left.
	 *
	 * Compared to lang('bf_language_direction') to indicate direction and
	 * appended to the file name to check for existence of the file when
	 * lang('bf_language_direction') == $rtl_postfix
	 */
	private static $rtl_postfix = 'rtl';

	/**
	 * @var string[] Holds the css files to place at the beginning of the page.
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
	 * @return void
	 */
	public static function init()
	{
		// It is recommended to combine as many config files as sensible into a
        // single file for performance reasons. If the config entry is already
        // available, don't load the file.
        // @todo Update this to check for 'assets.directories' once it can be
        // safely assumed that the item should be present
		if (self::$ci->config->item('assets.base_folder') === false) {
			self::$ci->config->load('application');
		}

		// Retrieve the config settings
        if (self::$ci->config->item('assets.directories')) {
            self::$directories = self::$ci->config->item('assets.directories');
            foreach (self::$directories as $key => &$value) {
                $value = trim($value, '/');
            }
        }
        // If 'assets.directories' is not set, check the previous locations
        else {
            self::$directories = array();
            self::$directories['base'] = trim(self::$ci->config->item('assets.base_folder') ?: 'assets', '/');
            self::$directories['cache'] = trim(self::$ci->config->item('assets.cache_folder') ?: 'cache', '/');
            $assetFolders = self::$ci->config->item('assets.asset_folders') ?: array('js' => 'js', 'css' => 'css', 'image' => 'images');
            foreach ($assetFolders as $key => $value) {
                self::$directories[$key] = trim($value, '/');
            }
            unset($assetFolders);
        }

        // Set the closures to minify CSS/JS
        self::$cssMinify = function($css) {
            return CSSMin::minify($css);
        };

        self::$jsMinify = function($js) {
            return JSMin::minify($js);
        };

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
	 * @deprecated since 0.7.1 use setGlobals() instead
	 * @param bool $include true to include (default) or false to exclude
	 *
	 * @return void
	 */
	public static function set_globals($include = true)
	{
		self::$globals = (bool)$include;
	}

    /**
     * Configure the library to output debug messages to the page
     *
     * @param bool $debug true to output debug messages (default) or false to
     * disable debug messages
     *
     * @return void
     */
    public static function setDebug($debug = true)
    {
        self::$debug = (bool)$debug;
    }

	/**
     * Configure the library to include global CSS and JS files.
     *
	 * If $include is true, global includes (like the default media CSS and
	 * global.js files) are automatically included in css() and js() output.
	 *
	 * @param bool $include true to include (default) or false to exclude
	 *
	 * @return void
	 */
    public static function setGlobals($include = true)
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
	 * @param mixed  $style The style(s) for which links will be rendered.
	 * @param string $media The media to assign to the style(s).
	 * @param bool   $bypassInheritance If true, skip check for parent theme
	 * styles
	 * @param bool	 $bypassModule      If true, do not output the css file
	 * named after the controller, or the module styles.
	 *
	 * @return string A string containing all requested links.
	 */
	public static function css($style = null, $media = 'screen', $bypassInheritance = false, $bypassModule = false)
	{
		// Debugging issues with media being set to 1
		if ($media == '1') {
			$media = 'screen';
		}

		$styles = array();

		// If no style(s) has been passed in, use all that have been added.
		if (empty($style)) {
            $styles = self::$styles;

            // Make sure to include a file based on media type if $globals
            if (self::$globals) {
                $styles[] = array(
                    'file'	=> $media,
                    'media'	=> $media,
                );
            }
		}
		// If an array has been passed, merge it with any added styles.
		elseif (is_array($style)) {
			$styles = array_merge($style, self::$styles);
		}
		// If a single style has been passed in, render it only.
		else {
			$styles = array(
				'file'	=> $style,
				'media'	=> $media,
			);
		}

		if ($bypassModule == false) {
			// Add a style named for the controller so it will be looked for.
			$styles[] = array(
				'file'	=> self::$ci->router->fetch_class(),
				'media' => $media,
			);

			$moduleStyles = self::find_files(self::$module_styles, 'css', $bypassInheritance);
		}

		$styles = self::find_files($styles, 'css', $bypassInheritance);

        $return = '';
		if (self::$ci->config->item('assets.css_combine')) {
            // Add the combined css
			$return = self::combine_css($styles, $media);
        } else {
    		// Loop through the styles, spitting out links for each one.
			foreach ($styles as $styleToAdd) {
				$attr = array(
					'rel'	=> 'stylesheet',
					'type'	=> 'text/css',
					'href'	=> '',
					'media'	=> $media,
				);

                if (is_array($styleToAdd)) {
                    $attr['href'] = $styleToAdd['file'];
                    if ( ! empty($styleToAdd['media'])) {
                        $attr['media'] = $styleToAdd['media'];
                    }
                } elseif (is_string($styleToAdd)) {
                    $attr['href'] = $styleToAdd;
                } else {
                    continue;
                }

                if (substr($attr['href'], -4) != '.css') {
                    $attr['href'] .= '.css';
                }


				$return .= '<link' . self::attributes($attr) . " />\n";
			}
		}

		if ($bypassModule == false) {
			// Make sure we include module styles
			$return .= self::combine_css($moduleStyles, $media, 'module');
		}

		return $return;
	}

	/**
	 * Does the actual work of generating the combined css code.
	 *
	 * Called by the css() method.
	 *
	 * @param array  $files An array of file arrays
	 * @param string $media The media to assign to the style(s) being passed in.
	 * @param string $type  Either a string ('module') or empty - defines which
	 * scripts are being combined
	 *
	 * @return string
	 */
	public static function combine_css($files = array(), $media = 'screen', $type = '')
	{
		// Are there any styles to include?
		if (count($files) == 0) {
			return;
		}

		// Debugging issues with media being set to 1
		if ($media == '1') {
			$media = 'screen';
		}

		// Add the theme name to the filename to account for different frontend/
        // backend themes.
		$theme = trim(Template::get('active_theme'), '/');
        if (empty($theme)) {
            $theme = trim(Template::get('default_theme'), '/');
        }

		$fileName = "{$theme}_" . self::$ci->router->fetch_module() . '_' . self::$ci->router->fetch_class();
		if (self::$ci->config->item('assets.encrypt_name') == true) {
			$fileName = md5($fileName);
		}
        $fileName .= $type == 'module' ? '_mod' : '_combined';

        // Don't add .min to $file_name, because generate_file() will add .min
        // itself. However, it needs to be added to the href attribute below.
		$min = self::$ci->config->item('assets.css_minify') ? '.min' : '';

		// Create our link attributes
		$attr = array(
			'rel'	=> 'stylesheet',
			'type'	=> 'text/css',
			'href'	=> self::path(base_url(), self::$directories['base'], self::$directories['cache'], "{$fileName}{$min}.css"),
			'media'	=> $media,
		);

		$output = '';
		if (self::generate_file($files, $fileName, 'css')) {
			$output = '<link' . self::attributes($attr) . " />\n";
		}

		return $output;
	}

	/**
	 * Add a file to the CSS queue
	 *
	 * @param mixed  $style   The style(s) to be added
	 * @param string $media   The type of media the stylesheet styles.
	 * @param bool	 $prepend If true, the file(s) will be added to the
	 * beginning of the style array
	 *
	 * @return void
	 */
	public static function add_css($style = null, $media = 'screen', $prepend = false)
	{
		if (empty($style)) {
			return;
		}

		// Debugging issues with media being set to 1
		if ($media == '1') {
			$media = 'screen';
		}

		// Add a string
		if (is_string($style)) {
            $style = array(
                'field' => $style,
                'media' => $media,
            );
		}

		// Add an array
		$stylesToAdd = array();
		if (is_array($style) && count($style)) {
			foreach ($style as $file) {
				$stylesToAdd[] = array(
					'file'	=> $file,
					'media'	=> $media,
				);
			}
		}

		if ($prepend) {
			self::$styles = array_merge($stylesToAdd, self::$styles);
		} else {
			self::$styles = array_merge(self::$styles, $stylesToAdd);
		}
	}

	/**
	 * Adds a module css file to the CSS queue to be rendered out.
	 *
	 * @param string $module Module name
	 * @param string $path   Path to the css file, leave blank for default
	 * location of {$module}/assets/css
	 * @param string $media  The type of media to which the stylesheet applies.
	 *
	 * @return void
	 */
	public static function add_module_css($module, $path = null, $media = 'screen')
	{
		if (empty($path)) {
			return;
		}

		if ($media == '1') {
			$media = 'screen';
		}

		// Add a string
		if (is_string($path)) {
            $path = array(
				'module' => $module,
				'file'	 => $path,
				'media'  => $media
			);
		}

        // Add an array
		if (is_array($path) && count($path)) {
			foreach ($path as $file) {
				self::$module_styles[] = array(
					'module' => $module,
					'file'	 => $file,
					'media'	 => $media
				);
			}
		}
	}

	//--------------------------------------------------------------------
	// !JAVASCRIPT METHODS
	//--------------------------------------------------------------------

	/**
	 * Adds scripts to the array to be served with the js() method, below.
	 *
	 * @param mixed  $script  The script(s) to be added to the queue.
	 * @param string $type    Either 'external' or 'inline'
	 * @param bool   $prepend Add to the start of the array - default is No
	 *
	 * @return void
	 */
	public static function add_js($script = null, $type = 'external', $prepend = false)
	{
		if (empty($script)) {
			return;
		}

		if (is_string($script)) {
            $script = array($script);
		}

		$scriptsToAdd = array();
        if (is_array($script) && count($script)) {
			foreach ($script as $s) {
                // Remove any obvious duplicates
                if ( ! in_array($s, self::$scripts[$type])) {
					$scriptsToAdd[] = $s;
				}
			}
		}

		if ($prepend) {
            self::$scripts[$type] = array_merge($scriptsToAdd, self::$scripts[$type]);
		} else {
            self::$scripts[$type] = array_merge(self::$scripts[$type], $scriptsToAdd);
		}
	}

	/**
	 * Adds a module's javascript file to be rendered.
	 *
	 * @param string $module The name of the module
	 * @param string $file   The name of the file, relative to the module's
	 * assets folder.
	 *
	 * @return void
	 */
	public static function add_module_js($module = '', $file = '')
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
                self::$scripts['module'][] = array(
					'module' => $module,
					'file'   => $s,
				);
			}
		}
	}

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
	 * @param mixed  $script The name of the script to link to (optional)
	 * @param string $type Whether the script should be linked to externally or
	 * rendered inline. Acceptable values: 'external' or 'inline'
	 *
	 * @return string Returns all Scripts located in External JS, Module JS, and
	 * Inline JS in that order.
	 */
	public static function js($script = null, $type = 'external')
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
	}

	/**
	 * Generates the links to the external js files.
	 *
	 * Called by the js() method, but can be used on its own.
	 *
	 * If no scripts are passed into the first parameter, links are created for
	 * all scripts within the self::$scripts['external'] array.
	 *
	 * If one or more scripts are passed in the first parameter, only the passed
	 * script files will be used to create links, and any stored in
	 * self::$scripts['external'] will be ignored.
	 *
	 * Note that links will not be rendered for files that cannot be found,
	 * though scripts with full URLs are not checked (they are simply included).
	 *
	 * @param mixed $extJs          Either a string or an array containing the
	 * name(s) of file(s) to link.
	 * @param bool  $list           If true, will echo out a list of scripts,
	 * enclosed in quotes and comma separated. Convenient for use with third-
	 * party js loaders.
	 * @param bool  $addExtension   If true (default), add the .js extension
	 * when adding files. Set to false to prevent the addition of the extension
	 * @param bool	$bypassGlobals	If true, do not include global scripts
	 * (global.js) for this call
	 *
	 * @return string
	 */
	public static function external_js($extJs = null, $list = false, $addExtension = true, $bypassGlobals = false)
	{
		$return = '';
		$scripts = array();
        $renderSingleScript = false;

		if (empty($extJs)) {
            $scripts = self::$scripts['external'];
		}
		// If scripts were passed, they override all other scripts.
        elseif (is_string($extJs)) {
            $scripts[] = $extJs;
            $renderSingleScript = true;
        } elseif (is_array($extJs)) {
            $scripts = $extJs;
        }

		// Make sure we check for a 'global.js' file.
		if ($bypassGlobals == false && self::$globals) {
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
				if ($addExtension && substr($script, -3) != '.js') {
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
                    $attr['src'] = self::path(site_url(), self::$directories['base'], self::$directories['js'], $script);
				}

				if ($list) {
					$return .= '"' . $attr['src'] . '", ';
				} else {
					$return .= '<script' . self::attributes($attr) . "></script>\n";
				}
			}
		}

		return trim($return, ', ');
	}

	/**
	 * Returns a link for js files contained in modules' assets folders.
	 *
	 * Note: all module js files are currently combined and output as a single
	 * script, since the module directories are not normally in a location
	 * accessible to the browser.
	 *
	 * @param bool $list If true, will echo out the script name enclosed in
	 * quotes. Convenient for using with third-party js loaders.
	 *
	 * @return string A string with the link(s) to the script files.
	 */
	public static function module_js($list = false)
	{
        // Don't bother with count() if is_array() fails
		if ( ! (is_array(self::$scripts['module']) && count(self::$scripts['module']))) {
			return '';
		}

		// Prep the scripts array with only files that can actually be found.
		$scripts = self::find_files(self::$scripts['module'], 'js');

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
	}

	/**
	 * Generates the container and outputs inline js code.
	 *
	 * All inline js code is wrapped by open and close tags specified in the
	 * config file, so the wrapper can be modified to use any js library.
	 *
	 * Called by the js() method.
	 *
	 * @return string
	 */
	public static function inline_js()
	{
		// Are there any scripts to include?
		if (empty(self::$scripts['inline'])) {
			return;
		}

		// Create the shell opening
		$output = "<script type='text/javascript'>\n" .
			self::$ci->config->item('assets.js_opener') . "\n";

		// Loop through all available scripts, inserting them inside the shell.
		foreach (self::$scripts['inline'] as $script) {
			$output .= $script . "\n";
		}

		// Close the shell.
		$output .= "\n" . self::$ci->config->item('assets.js_closer') .
            "\n</script>\n";

		return $output;
	}

	/**
	 * Generates the combined js code.
	 *
	 * Called by the external_js() and module_js() methods.
	 *
	 * @param array $files       An array of files to be combined.
	 * @param string $scriptType Either a string ('module') or empty. Used in
	 * generating the file name.
	 *
	 * @return string
	 */
	public static function combine_js($files = array(), $scriptType = '')
	{
		// Are there any scripts to include?
		if (is_array($files) && count($files) == 0) {
			return;
		}

		$theme = Template::get('active_theme');
        if (empty($theme)) {
            $theme = Template::get('default_theme');
        }

		// Get the class name, module name, and uri segments
        $className   = self::$ci->router->fetch_class();
		$moduleName  = self::$ci->router->fetch_module();
		$uriSegments = self::$ci->uri->segment_array();

		// Get the context name from the uri segments
		$classKey = array_search($className, $uriSegments);
		if ($classKey !== false) {
			$classKey = $classKey + 1;
			if (isset($uriSegments[$classKey])) {
				$moduleName = $uriSegments[$classKey];
			}
		}

		$fileName = trim($theme, '/') . "_{$moduleName}_{$className}";
		if (self::$ci->config->item('assets.encrypt_name')) {
			$fileName = md5($fileName);
		}
		$fileName .= $scriptType == 'module' ? '_mod' : '_combined';

        // If the file is to be minified, .min must be added to the URL below,
        // but since generate_file() adds .min on its own, it can't be added to
        // $fileName, yet.
		$min = self::$ci->config->item('assets.js_minify') ? '.min' : '';

		// If the file is generated successfully, output the path to the file
		$output = '';
		if (self::generate_file($files, $fileName, 'js')) {
            $output = self::path(base_url(), self::$directories['base'], self::$directories['cache'], "{$fileName}{$min}.js");
		}

		return $output;
	}

	//--------------------------------------------------------------------
	// !IMAGE METHODS
	//--------------------------------------------------------------------

	/**
	 * A helper method to build image tags.
	 *
	 * @param string $image       The name of the image file
	 * @param array  $extraAttrs  An of key/value pairs which are attributes to
	 * be added to the tag, such as height, width, class, etc.
	 * @param bool	 $suppressEol If false (default) a newline character is
	 * added after the img tag. If true, the newline character is not added.
	 *
	 * @return string A string containing the image tag.
	 */
	public static function image($image = null, $extraAttrs = array(), $suppressEol = false)
	{
		if (empty($image)) {
			return '';
		}

		$attrs = array('src' => $image);

        if (isset($extraAttrs['alt'])) {
            $attrs['alt'] = $extraAttrs['alt'];
    		unset($extraAttrs['alt']);
        } else {
            $attrs['alt'] = '';
        }

		$attrs = array_merge($attrs, $extraAttrs);
		$return = '<img' . self::attributes($attrs) . ' />';

		return ($suppressEol ? $return : $return . PHP_EOL);
	}

	//--------------------------------------------------------------------
	// !UTILITY METHODS
	//--------------------------------------------------------------------

	/**
	 * Returns the full url to a folder in the assets directory.
	 *
	 * @param mixed $type Optional a string with the assets folder to locate.
	 * Leave blank to return the assets base folder.
	 *
	 * @return string The full url (including http://) to the resource.
	 */
	public static function assets_url($type = null)
	{
		$url = '';

		// Add Assets base and resource type folders if needed
        if ($type !== null && $type !== 'base'
            && array_key_exists($type, self::$directories)
           ) {
            $url = self::path(base_url(), self::$directories['base'], self::$directories[$type]) . '/';
        }
        // Add Assets Base Folder
        else {
            $url = self::path(base_url(), self::$directories['base']) . '/';
        }

		// Cleanup, just to be safe
		$url = str_replace('//', '/', $url);
		$url = str_replace(':/', '://', $url);

		return $url;
	}

	/**
	 * Deletes all asset cache files from the assets/cache folder.
	 *
	 * @return void
	 */
	public static function clear_cache()
	{
        if ( ! function_exists('delete_files') || ! function_exists('write_file')) {
            self::$ci->load->helper('file');
        }

		$sitePath  = Template::get('site_path');
        $cachePath = self::path($sitePath, self::$directories['base'], self::$directories['cache']) . '/';

		delete_files($cachePath);

		// Write the index.html file back in
		$indexhtml_data = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
		write_file("{$cachePath}index.html", $indexhtml_data);
	}

    /**
     * Build a path from a variable number of arguments
     *
     * @return string    The path
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
	 * @author Dan Horrigan (Stuff library)
	 *
	 * @param array $attributes An array of key/value pairs representing the attributes.
	 *
	 * @return string A string containing the rendered attributes.
	 */
	private static function attributes($attributes = null)
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
	}

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
	 * @param array  $files     Array of files
	 * @param string $fileName  Name of the file to generate
	 * @param string $fileType  Either 'css' or 'js'.
	 *
	 * @return bool True if file generated successfully, False if there were errors.
	 */
	private static function generate_file($files = array(), $fileName, $fileType = 'css')
	{
		if (count($files) == 0) {
			// While the file wasn't actually created, there were no errors
			return true;
		}

		$site_path = Template::get('site_path');

		// Where to save the combined file
        $cache_path = self::path($site_path, self::$directories['base'], self::$directories['cache']) . '/';

		// Full file path - without the extension
		$file_path = $cache_path . $fileName;

        // Append .min if the file is to be minified
		if (self::$ci->config->item("assets.{$fileType}_minify")) {
			$file_path .= '.min';
		}

		$file_path .= ".{$fileType}"; // Append the file extension
		$modified_time	= 0;	// Holds the last modified date of all included files.
		$actual_file_time = 0;	// The modified time of the combined file.

		// If the combined file already exists, grab the last modified time.
		if (is_file($file_path)) {
			$actual_file_time = filemtime($file_path);
		}

		foreach ($files as $key => $file) {
			$app_file = is_array($file) ? $file['server_path'] : self::path($site_path, str_replace(base_url(), '', $file));

			// Javascript
			if ($fileType == 'js') {
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
		}

        // If any of the files were modified after the cached file was created
		if ($actual_file_time < $modified_time) {
			// Grab the contents of the files
    		$asset_output = '';
			foreach ($files_array as $key => $file) {
				$file_output = file_get_contents($file);
				if ( ! empty($file_output)) {
					$asset_output .= $file_output . PHP_EOL;
				}
			}

            // If the assets are configured to be minified, minify them
            if (config_item("assets.{$fileType}_minify")) {
                $minifyFunc = "{$fileType}Minify";
                $minify = self::${$minifyFunc};
                $asset_output = $minify($asset_output);
                unset($minifyFunc, $minify);
            }

            // Write the contents out to asset cache (replaces existing file)
			if ( ! is_dir($cache_path)) {
				@mkdir($cache_path);
			}
            if ( ! function_exists('write_file')) {
                self::$ci->load->helper('file');
            }
			if ( ! write_file($file_path, $asset_output)) {
				return false;
			}
		} elseif ($actual_file_time == 0) {
			return false;
		}

		return true;
	}

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
	 * @param array  $files              An array of file names to search for.
	 * @param string $type               Either 'css' or 'js'.
	 * @param bool   $bypass_inheritance If TRUE, will skip the check for parent theme styles.
	 *
	 * @return array The complete list of files with url paths.
	 */
	private static function find_files($files = array(), $type = 'css', $bypass_inheritance = false)
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
                                <li>{$site_path}" . self::$directories['base'] . "/{$clean_type}/{$file}{$type}</li>
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
                        if (($file_array = self::get_file_array($site_path, self::$directories['base'] . "/{$clean_type}/", $file, $type, $media))) {
							$new_files[] = $file_array;
						}
                        // ASSETS ROOT
                        // One last check to see if it's under assets without
                        // the type sub-folder. Useful for keeping script
                        // collections together
						elseif (($file_array = self::get_file_array($site_path, self::$directories['base'] . '/', $file, $type, $media))) {
							$new_files[] = $file_array;
						}
					} // if (!$found)
				} // foreach ($paths as $path)
			}
            // Module Files
            else {
				$file_path_name = $file;

				// Try the /module/assets folder
				$path = Modules::file_path($module, self::$directories['base'], $file_path_name . $type);

				// If $path is empty, try the /module/assets/type folder
				if (empty($path)) {
					$file_path_name = "{$clean_type}/{$file}";
					$path = Modules::file_path($module, self::$directories['base'], $file_path_name . $type);
				}

				// If the language is right-to-left, add -rtl to the file name
				if ( ! empty($path) && $rtl_set) {
					$path_rtl = Modules::file_path($module, self::$directories['base'], "{$file_path_name}-{$rtl}{$type}");
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
	}

	/**
	 * Get the file array for a given file and path
	 *
	 * @param string $sitePath  The base server path
	 * @param string $path      The path to the file (appended to base_url() and $site_path)
	 * @param string $file      The name of the file, without the extension
	 * @param string $type      File extension, including '.'
	 * @param string $media     media type, e.g. 'screen' or 'print'
	 *
	 * @return mixed    false if the file wasn't found, the URL of the file if
	 * $media is empty, or an array containing the file (URL), media, and server
	 * path
	 */
	private static function get_file_array($sitePath = '', $path = '', $file = '', $type = '', $media = '')
	{
		if (empty($file) || empty($type)) {
			return false;
		}

		$fileName   = $path . $file;
        $serverPath = self::path($sitePath, $fileName . $type);
		if ( ! is_file($serverPath)) {
            return false;
        }

        $filePath = self::path(base_url(), $fileName . $type);

        if (lang('bf_language_direction') == self::$rtl_postfix) {
            if (is_file(self::path($sitePath, "{$fileName}-" . self::$rtl_postfix . $type))) {
                $filePath = self::path(base_url(), "{$fileName}-" . self::$rtl_postfix . $type);
                $serverPath = self::path($sitePath, "{$fileName}-" . self::$rtl_postfix . $type);
            }
        }

        if (self::$debug) {
            echo "[Assets] Found file at: <strong>{$serverPath}</strong><br/>";
        }

        return (empty($media) ? $filePath : array('file' => $filePath, 'media' => $media, 'server_path' => $serverPath));
	}
}

//--------------------------------------------------------------------
// Helpers: Assets Helpers
//
// The following helpers are related and dependent on the Assets class
//--------------------------------------------------------------------

/**
 * Returns full site url to assets javascript folder.
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
 * @return string Returns full site url to assets images folder.
 */
function img_path()
{
	return Assets::assets_url('image');
}

/**
 * Returns full site url to assets css folder.
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
 * @return string Returns full site url to assets base folder.
 */
function assets_path()
{
	return Assets::assets_url();
}
/* End of file assets.php */
/* Location: ./application/libraries/assets.php */