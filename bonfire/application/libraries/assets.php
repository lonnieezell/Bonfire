<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class: Assets Class
	
	The Assets class works with the Template class to provide powerful theme/
	template functionality.
	
	Version: 3.0a
 */
class Assets {

	/*
		Var: $debug
		
		Whether or not debug messages should be displayed.
	*/
	private static $debug = false;

	/*
		Var: $ci
		
		An instance of the CI app
	*/
	protected static $ci;
	
	/*
		Var: $asset_base
		
		The base folder (relative to the template.site_root config setting)
		that all of the assets are stored in.
	*/
	private static $asset_base		= 'assets/';
	
	/*
		Var: $asset_folders
		
		The names of the folders for the various assets.
		These are set in the assets config file, and 
		default to 'js', 'css', and 'images'.
	*/
	private static $asset_folders 	= array(
										'css'		=> 'css',
										'js'		=> 'js',
										'images'	=> 'images'
									);

	/*
		Var: $inline_scripts
		
		An array of inline scripts to be placed at the 
		end of the page.
	*/
	protected static $inline_scripts		= array();
	
	/*
		Var: $external_scripts
		
		An array of external (linked) javascript files
		to be called at the end of the page.
	*/
	protected static $external_scripts 	= array();
	
	/*
		Var: $styles
		
		An array of css files to be placed at the
		beginning of the file.
	*/
	private static $styles				= array();	

	//--------------------------------------------------------------------

	/*
		Method: __construct()
		
		This if here solely for CI loading to work. Just calls the init( ) method.
		
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
		
		Load the assets config file, and inserts the base
		css and js into our array for later use. This ensures
		that these files will be processed first, in the order
		the user is expecting, prior to and later-added files.
		
		Return: 
			void
	 */
	public static function init() 
	{		
		
		/*
			It is recommended to combine as many config files as sensible into
			a single file for performance reasons. To handle these situations,
			we should check to see if the config file is already loaded before 
			loading it ourself.
		*/
		if (config_item('assets.base_folder') === false)
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
	
	/*
		Method: css()

		Renders links to stylesheets, with the $asset_url prepended. 
		If a single filename is passed, it will only create a single link
		for that file, otherwise, it will include any styles that have
		been added with add_css below. If no style is passed it will default
		to the theme's style.css file.
		
		When passing a filename, the filepath should be relative to the site
		root (where index.php resides).
		
		Parameters:		
			$style	- The style(s) to have links rendered for.
			$media	- The media to assign to the style(s) being passed in.
			$bypass_inheritance	- If true, will skip the check for parent theme styles.

		Return: 
		   A string containing all necessary links.
	*/
	public static function css($style=null, $media='screen', $bypass_inheritance=false) 
	{
		$styles = array();
		$return = '';
	
		// If no style(s) has been passed in, use all that have been added.
		if (empty($style))
		{
			// Make sure to include a file based on media type.
			$styles[] = array(
				'file'	=> $media,
				'media'	=> $media
			);
						
			$styles = array_merge($styles, self::$styles);
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

		$styles = self::find_files($styles, 'css', $bypass_inheritance);

		// Loop through the styles, spitting out links for each one.
		foreach ($styles as $s)
		{
			if (is_array($s))
			{
				if (substr($s['file'], -4) != '.css') 
				{ 
					$s['file'] .= '.css'; 
				}
			} else
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
			
			$return .= '<link'. self::attributes($attr) ." />\n";
		}
		
		echo $return;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: add_css()
	
		Adds a file to be the CSS queue to be rendered out.
		
		Parameters:
			$style	- The style(s) to be added
			$media	- The type of media the stylesheet styles.
		
		Return:	
			void
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
	
	/*
		Method: add_js()
		
		Adds scripts to the array to be served with the js() method, below.
		
		Parameters:
			$script		- The script(s) to be added to the queue.
			$type		- Either 'external' or 'inline'
		
		Return:	
			void
	*/
	public static function add_js($script=null, $type='external', $prepend=false) 
	{
		if (empty($script)) return;

		$type .= '_scripts';
		
		if (is_string($script))
		{
			if (!isset(self::$$type[$script]))
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
				if (!isset(self::$$type[$s]))
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
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: js()
		
		Renders links to stylesheets, with the $asset_url prepended. 
		If a single filename is passed, it will only create a single link
		for that file, otherwise, it will include any styles that have
		been added with add_css below. If no style is passed it will default
		to the theme's style.css file.
		
		When passing a filename, the filepath should be relative to the site
		root (where index.php resides).
		
		Paremeters:
			$script	- The name of the script to link to (optional)
			$type	- Whether the script should be linked to externally or rendered inline.
					  Acceptable values: 'external' or 'inline'
					
		Return: 
			void
	*/
	public static function js($script=null, $type='external') 
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
	
	/*
		Method: external_js()
		
		Does the actual work of generating the links to the js files. 
		It is called by the js() method, but can be used on it's own.
		
		If no script are passed into the first parameter, links are created for
		all scripts within the self::$external_scripts array. If one or 
		more scripts are passed in the first parameter, only these script files
		will be used to create links with, and any stored in self::$external_scripts
		will be ignored.
		
		Note that links will not be rendered for files that cannot be found, though
		scripts will full urls are not checked, but are simply included.
		
		Parameters:
			$new_js		- either a string or an array containing the names of files to link to.
			$list		- if true, will echo out a list of scriptnames, enclosed in quotes and 
							comma separated. Convenient for using with third-party js loaders.

		Return: 
			void
	*/
	public static function external_js($new_js=null, $list=false) 
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
				'src'	=> strpos($script, 'http:') !== false ?
					
					// It has a full url built in, so leave it alone
					$script :
					
					// Otherwise, build the full url
					self::$asset_url . self::$asset_base . self::$asset_folders['js'] .'/'. $script
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
		
		echo trim($return, ', ');
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: inline_js()
		
		Does the actual work of generating the inline js code. All code is 
		wrapped by open and close tags specified in the config file, so that 
		you can modify it to use your favorite js library.
		
		It is called by the js() method.
		
		Return: 
			void
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
	
	/*
		Method: image()
		
		A simple helper to build image tags.
		
		Parameters:
			$image			- The name of the image file
			$extra_attrs	- An of key/value pairs that are attributes that should be added to the tag, such as height, width, class, etc.
			
		Return: 
			A string containing the image tag.
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
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	/*
		Method: attributes()
		
		Converts an array of attribute into a string
		
		Author:
			Dan Horrigan (Stuff library)
		
		Parameters:
			$attributes	- An array of key/value pairs representing the attributes.
			
		Return: 
			A string containing the rendered attributes.
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
	
	/*
		Method: find_files()
		
		Locates file by looping through the active and default themes, and
		then the assets folder (as specified in the config file). 
		
		Files are searched for in this order...
			1 - active_theme/
			2 - active_theme/type/
			3 - default_theme/
			4 - default_theme/type/
			5 - asset_base/type
			
		Where 'type' is either 'css' or 'js'.
		
		If the file is not found, it is removed from the array. If the file
		is found, a full url is created, using base_path(), unless the path
		already includes 'http' at the beginning of the filename, in which case
		it is simply included in the return files.
		
		For CSS files, if a script of the same name is found in both the 
		default_theme and the active_theme folders (or their type sub-folder),
		they are both returned, with the default_theme linked to first, so that
		active_theme styles can override those in the default_theme without
		having to recreate the entire stylesheet.
		
		Access: 
			private
			
		Parameters:
			$files	- An array of file names to search for.
			$type	- Either 'css' or 'js'.
		
		Return:
			array			The complete list of files with url paths.
	 */
	private function find_files($files=array(), $type='css', $bypass_inheritance=false) 
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
			// If it's an array, we're dealing with css and it has both 
			// a file and media keys. Store them for later use.
			if (is_array($file))
			{	
				$media = $file['media'];
				$file = $file['file'];
			}
			
			// Strip out the file type for consistency
			$file = str_replace('.'. $type, '', $file);
		
			// If it contains an external URL, we're all done here.
			if (strpos((string)$file, 'http', 0) !== false)
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
					
					if (!empty($active_theme)) 
					{
						echo '<li>'. $site_path . $path .'/'. $active_theme . $file .".{$type}</li>";
						echo '<li>'. $site_path . $path .'/'. $active_theme . $type .'/'. $file .".{$type}</li>";
					}
					
					echo '<li>'. $site_path . self::$asset_base .'/'. $type .'/'. $file .".{$type}</li>";
					
					echo '</ul>';
				}
				
				if (!$bypass_inheritance)
				{
					/*
						DEFAULT THEME
					
						First, check the default theme. Add it to the array. We check here first so that it
						will get overwritten by anything in the active theme.
					*/
					if (is_file($site_path . $path .'/'. $default_theme . $file .".{$type}"))
					{
						$file_path = base_url() . $path .'/'. $default_theme . $file .".{$type}";
						$new_files[] = isset($media) ? array('file'=>$file_path, 'media'=>$media) : $file_path;
						$found = true;
						
						if (self::$debug) echo '[Assets] Found file at: <b>'. $site_path . $path .'/'. $default_theme . $file .".{$type}" ."</b><br/>"; 
					}
					/*
						If it wasn't found in the default theme root folder, look in default_theme/$type/
					*/
					else if (is_file($site_path . $path .'/'. $default_theme . $type .'/'. $file .".{$type}"))
					{
						$file_path = base_url() . $path .'/'. $default_theme . $type .'/'. $file .".$type";
						$new_files[] = isset($media) ? array('file'=>$file_path, 'media'=>$media) : $file_path;
						$found = true;
						
						if (self::$debug) echo '[Assets] Found file at: <b>'. $site_path . $path .'/'. $default_theme . $type .'/'. $file .".{$type}" ."</b><br/>";
					}
				}
				
				/*
					ACTIVE THEME
					
					By grabbing a copy from both the default theme and the active theme, we can
					handle simple CSS-only overrides for a theme, completely changing it's appearance
					through a simple child css file.
				*/ 
				if (!empty($active_theme) && is_file($site_path . $path .'/'. $active_theme . $file .".{$type}"))
				{
					$file_path = base_url() . $path .'/'. $active_theme . $file .".{$type}";
					$new_files[] = isset($media) ? array('file'=>$file_path, 'media'=>$media) : $file_path;
					$found = true;
					
					if (self::$debug) echo '[Assets] Found file at: <b>'. $site_path . $path .'/'. $active_theme . $file .".{$type}" ."</b><br/>";
				} 
				/*
					If it wasn't found in the active theme root folder, look in active_theme/$type/
				*/
				else if (is_file($site_path . $path .'/'. $active_theme . $type .'/'. $file .".{$type}"))
				{
					$file_path = base_url() . $path .'/'. $active_theme . $type .'/'. $file .".$type";
					$new_files[] = isset($media) ? array('file'=>$file_path, 'media'=>$media) : $file_path;
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
					// Assets/type folder
					if (is_file($site_path . self::$asset_base .'/'. $type .'/'. $file .".{$type}"))
					{
						$file_path = base_url() . self::$asset_base .'/'. $type .'/'. $file .".{$type}";
						$new_files[] = isset($media) ? array('file'=>$file_path, 'media'=>$media) : $file_path;

						if (self::$debug) echo '[Assets] Found file at: <b>'. $site_path . $path .'/'. $default_theme . $type .'/'. $file .".{$type}" ."</b><br/>";
					} 
					
					/*
						ASSETS ROOT
						
						Finally, one last check to see if it is simply under assets/. This is useful for
						keeping collections of scripts (say, TinyMCE or MarkItUp together for easy upgrade.
					*/
					else if (is_file($site_path . self::$asset_base .'/'. $file .".{$type}"))
					{
						$file_path = base_url() . self::$asset_base .'/'. $file .".{$type}";
						$new_files[] = isset($media) ? array('file'=>$file_path, 'media'=>$media) : $file_path;

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