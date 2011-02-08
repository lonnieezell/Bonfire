<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class: Assets Class
	
	The Assets class works with the Template class to provide powerful theme/
	template functionality.
	
	Version: 3.0a
 */
class Assets {

	/**
	 * Whether or not debug messages should be displayed.
	 *
	 * @var		bool
	 * @access	private
	 */
	private static $debug = true;

	/**
	 *	An instance of the CI app
	 *
	 * @var 	object
	 * @access	protected
	 */
	protected static $ci;
	
	/**
	 * The base folder (relative to the template.site_root config setting)
	 * that all of the assets are stored in.
	 *
	 * @var		string
	 * @access	private
	 */
	private static $asset_base		= 'assets/';
	
	/**
	 * The names of the folders for the various assets.
	 * These are set in the assets config file, and 
	 * default to 'js', 'css', and 'images'.
	 *
	 * @var 	array
	 * @access	private
	 */
	private static $asset_folders 	= array(
										'css'		=> 'css',
										'js'		=> 'js',
										'images'	=> 'images'
									);

	/**
	 * An array of inline scripts to be placed at the 
	 * end of the page.
	 *
	 * @var		array
	 * @access	private
	 */
	private static $inline_scripts		= array();
	
	/**
	 * An array of external (linked) javascript files
	 * to be called at the end of the page.
	 *
	 * @var		array
	 * @access	private
	 */
	private static $external_scripts 	= array();
	
	/**
	 * An array of css files to be placed at the
	 * beginning of the file.
	 * 
	 * @var		array
	 * @access	private
	 */
	private static $styles				= array();	

	//--------------------------------------------------------------------

	/**
	 * Constructor.
	 * 
	 * This if here solely for CI loading to work. Just calls the init( ) method.
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
	 * Constructor.
	 * 
	 * Load the assets config file, and inserts the base
	 * css and js into our array for later use. This ensures
	 * that these files will be processed first, in the order
	 * the user is expecting, prior to and later-added files.
	 *
	 * @access public
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
		if (config_item('assets.base_url') === false)
		{
			self::$ci->config->load('assets');
		}
	
		// Store our settings
		self::$asset_base		= self::$ci->config->item('assets.base_folder');
		self::$asset_folders	= self::$ci->config->item('assets.asset_folders');

		log_message('debug', 'Assets library loaded.');
	}
	
	//--------------------------------------------------------------------
	
	
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
	 * @param	string/array	The style(s) to have links rendered for.
	 * @param	string			The media to assign to the style(s) being passed in.
	 * @return	string			A string containing all necessary links.
	 */
	public static function css($style=null, $media='screen') 
	{
		$styles = array();
		$return = '';
	
		// If no style(s) has been passed in, use all that have been added.
		if (empty($style))
		{
			// If not styles are in the system, base it on the media type.
			if (!count(self::$styles))
			{
				$styles[] = $media;
			} else
			{
				$styles = self::$styles;
			}
		} 
		// If an array has been passed, merge it with any added styles.
		else if (is_array($style))
		{	
			$styles = array_merge($styles, self::$styles);
		}
		// If a single style has been passed in, render it only.
		else 
		{
			$styles = array($style);
		}
		
		// Add a style named for the controller so it will be looked for.
		$styles[] = self::$ci->router->class;
		
		$styles = self::find_files($styles);
		
		// Loop through the styles, spitting out links for each one.
		foreach ($styles as $s)
		{
			if (substr($s, -4) != '.css') 
			{ 
				$s .= '.css'; 
			}
		
			$attr = array(
				'rel'	=> 'stylesheet',
				'type'	=> 'text/css',
				'href'	=> $s,
				'media'	=> $media
			);
			
			$return .= '<link'. self::attributes($attr) ." />\n";
		}
		
		return $return;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Adds a file to be the CSS queue to be rendered out.
	 *
	 * @param	string/array	The style(s) to be added
	 * @param	string			The type of media the stylesheet styles.
	 * @return	void
	 */
	public static function add_css($style=null, $media='screen') 
	{
		if (empty($style)) return;
		
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
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !JAVASCRIPT METHODS
	//--------------------------------------------------------------------
	
	/**
	 * Adds scripts to the array to be served with the js() method, below.
	 *
	 * @param	script/array	$script		The script(s) to be added to the queue.
	 * @param	script			$type		Either 'external' or 'inline'
	 * @return	void
	 */
	public static function add_js($script=null, $type='external') 
	{
		if (empty($script)) return;

		$type .= '_scripts';
		
		if (is_string($script))
		{
			if (!isset(self::$$type[$script]))
			{
				array_unshift(self::${$type}, $script);
			}
		}
		else if (is_array($script))
		{
			foreach ($script as $s)
			{
				if (!isset(self::$$type[$s]))
				{
					self::${$type}[] = $s;
				}
			}
		}
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *
	 */
	public function js($script=null, $type='external') 
	{
		$type .= '_scripts';
		$return = '';
		
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
		self::external_js();
		self::inline_js();
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * _external_js function.
	 *
	 * This private method does the actual work of generating the
	 * links to the js files. It is called by the js() method.
	 * 
	 * @access private
	 * @return void
	 */
	public function external_js($new_js=null, $is_themed=false) 
	{
		$return = '';
		$scripts = array();
		
		// If scripts were passed, they override all other scripts.
		if (!empty($new_js))
		{
			if (is_string($new_js))
			{
				$scripts[] = $new_js;
			} else if (is_array($new_js))
			{
				$scripts = $new_js;
			}
		} else 
		{
			$scripts = self::$external_scripts;
			
			// Make sure we check for a 'global.js' file.
			$scripts[] = 'global';
			
			// Add a style named for the controller so it will be looked for.
			$scripts[] = self::$ci->router->class;
		}
		
		// Try to find them
		$scripts = self::find_files($scripts, 'js');
	
		foreach ($scripts as $script)
		{
			if (substr($script, -3) != '.js') 
			{ 
				$script .= '.js'; 
			}
		
			$attr = array(
				'type'	=> 'text/javascript',
				'src'	=> strpos($script, 'http:') !== false ?
					
					// It has a full url built in, so leave it alone
					$script :
					
					// Otherwise, build the full url
					self::$asset_url . self::$asset_base . self::$asset_folders['js'] .'/'. $script
			);
			
			$return .= '<script'. self::attributes($attr) ." ></script>\n";
		}
		
		echo $return;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * _inline_js function.
	 *
	 * This private method does the actual work of generating the
	 * inline js code. All code is wrapped by open and close tags
	 * specified in the config file, so that you can modify it to
	 * use your favorite js library.
	 * 
	 * It is called by the js() method.
	 * 
	 * @access private
	 * @return void
	 */
	public static function inline_js() 
	{
		// Are there any scripts to include? 
		if (count(self::$inline_scripts) == 0)
		{
			return;
		}
		
		// Create our shell opening
		echo '<script type="text/javascript">' . "\n";
		echo self::$ci->config->item('assets.js_opener') ."\n\n";
		
		// Loop through all available scripts
		// inserting them inside the shell.
		foreach(self::$inline_scripts as $script)
		{
			echo $script . "\n";
		}
		
		// Close the shell.
		echo "\n" . self::$ci->config->item('assets.js_closer') . "\n";
		echo '</script>' . "\n";
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !IMAGE METHODS
	//--------------------------------------------------------------------
	
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
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	/**
	 * Attr
	 *
	 * Converts an array of attribute into a string
	 *
	 * @author	Dan Horrigan (Stuff library)
	 *
	 * @access	public
	 * @param	array	The attribute array
	 * @return	string	The attribute string
	 * @return	string
	 */
	private static function attributes($attributes=null) 
	{
		if (empty($attributes))
		{
			return '';
		}

		$final = '';
		foreach ($attributes as $key => $value)
		{
			if ($value === NULL)
			{
				continue;
			}

			$final .= ' '.$key.'="'.htmlspecialchars($value, ENT_QUOTES).'"';
		}

		return $final;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Locates file by looping through the active and default themes. 
	 *
	 * Since CSS and JS should be allowed to override each other, we include
	 * both the files of this name from the active theme and the default theme.
	 * The file from the default theme should be resolved first so the active
	 * theme can override it.
	 *
	 * @access	private
	 * @param	array	$files	an array of file names to search for.
	 * @param	string	$type	either 'css' or 'js'.
	 * @return	array			The complete list of files with url paths.
	 */
	private function find_files($files=array(), $type='css') 
	{
		// Grab the theme paths from the template library.
		$paths = Template::get('theme_paths');
		$site_path = Template::get('site_path');
		$active_theme = Template::get('active_theme');
		$default_theme = Template::get('default_theme');
		
		$new_files = array();
		
		if (self::$debug)
		{
			echo "Active Theme = $active_theme<br/>";
			echo "Default Theme = $default_theme<br/>";
			echo 'Site Path = '. $site_path .'<br/>';
			echo 'File(s) to find: '; print_r($files);
		}
		
		foreach ($files as $file)
		{
			// If it contains an external URL, we're all done here.
			if (strpos($file, 'http', 0) !== false)
			{
				$new_files[] = $file;
				continue;
			}
			
			$found = false;
		
			// We need to check all of the possible theme_paths
			foreach ($paths as $path)
			{				
				if (self::$debug) { 
					echo '[Assets] Looking in: <ul><li>'. $site_path . $path .'/'. $default_theme . $file .".{$type}</li>"; 
					echo '<li>'. $site_path . $path .'/'. $default_theme . $type .'/'. $file .".{$type}</li>";
					
					if (!empty(self::$active_theme)) 
					{
						echo '<li>'. $site_path . $path .'/'. $active_theme . $file .".{$type}</li>";
						echo '<li>'. $site_path . $path .'/'. $active_theme . $type .'/'. $file .".{$type}</li>";
					}
					
					echo '<li>'. $site_path . self::$asset_base .'/'. $type .'/'. $file .".{$type}</li>";
					
					echo '</ul>';
				}
				
				/*
					DEFAULT THEME
				
					First, check the default theme. Add it to the array. We check here first so that it
					will get overwritten by anything in the active theme.
				*/
				if (is_file($site_path . $path .'/'. $default_theme . $file .".{$type}"))
				{
					$new_files[] = base_url() . $path .'/'. $default_theme . $file .".{$type}";
					$found = true;
					
					if (self::$debug) echo '[Assets] Found file at: <b>'. $site_path . $path .'/'. $default_theme . $file .".{$type}" ."</b><br/>"; 
				}
				/*
					If it wasn't found in the default theme root folder, look in default_theme/$type/
				*/
				else if (is_file($site_path . $path .'/'. $default_theme . $type .'/'. $file .".{$type}"))
				{
					$new_files[] = base_url() . $path .'/'. $default_theme . $type .'/'. $file .".$type";
					$found = true;
					
					if (self::$debug) echo '[Assets] Found file at: <b>'. $site_path . $path .'/'. $default_theme . $type .'/'. $file .".{$type}" ."</b><br/>";
				}
				
				/*
					ACTIVE THEME
					
					By grabbing a copy from both the default theme and the active theme, we can
					handle simple CSS-only overrides for a theme, completely changing it's appearance
					through a simple child css file.
				*/ 
				if (!empty($active_theme) && is_file($site_path . $path .'/'. $active_theme . $file .".{$type}"))
				{
					$new_files[] = base_url() . $path .'/'. $active_theme . $file .".{$type}";
					$found = true;
					
					if (self::$debug) echo '[Assets] Found file at: <b>'. $site_path . $path .'/'. $active_theme . $file .".{$type}" ."</b><br/>";
				} 
				/*
					If it wasn't found in the active theme root folder, look in active_theme/$type/
				*/
				else if (is_file($site_path . $path .'/'. $active_theme . $type .'/'. $file .".{$type}"))
				{
					$new_files[] = base_url() . $path .'/'. $active_theme . $type .'/'. $file .".$type";
					$found = true;
					
					if (self::$debug) echo '[Assets] Found file at: <b>'. $site_path . $path .'/'. $active_theme . $type .'/'. $file .".{$type}" ."</b><br/>";
				}
				
				/*
					ASSET BASE
					
					If the file hasn't been found, yet, we have one more place to look for it: 
					in the folder specified by 'assets.base_folder', and under the $type sub-folder.
				*/
				if (!$found)
				{
					if (is_file($site_path . self::$asset_base .'/'. $type .'/'. $file .".{$type}"))
					{
						$new_files[] = base_url() . self::$asset_base .'/'. $type .'/'. $file .".{$type}";

						if (self::$debug) echo '[Assets] Found file at: <b>'. $site_path . $path .'/'. $default_theme . $type .'/'. $file .".{$type}" ."</b><br/>";
					}
				}
			}			
		}
		
		return $new_files;
	}
	
	//--------------------------------------------------------------------
			
}


// END Assets class

/* End of file Assets.php */
/* Location: ./application/libraries/Assets.php */