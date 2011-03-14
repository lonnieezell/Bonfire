<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	File: Application Helper

	Pulls together various helper functions from across the core modules
	to ease editing and minimize physical files that need loaded.
*/

//--------------------------------------------------------------------

//--------------------------------------------------------------------
// !USER HELPERS
//--------------------------------------------------------------------

/*
	Function: gravatar_link()

	Creates an image link based on Gravatar for the specified email address.
	It will default to the site's generic image if none is found for
	the user.
	
	Note that if gravatar does not have an image that matches the criteria,
	it will return a link to an image under *your_theme/images/user.png*.
	
	Parameters:
		$email	- The email address to check for.
		$size	- The width (and height) of the resulting image to grab.
		$alt	- Alt text to be put in the link tag.
		$title	- The title text to be put in the link tag.
		$class	- Any class(es) that should be assigned to the link tag.
		$id		- The id (if any) that shoudl put in the link tag.
		
	Return:
		The resulting image tag.
 */
function gravatar_link($email=null, $size=48, $alt='', $title='', $class='', $id='') 
{
	// Set our default image based on required size.
	$default_image = Template::theme_url('images/user.png');
	
	// Set our minimum site rating to PG
	$rating = 'PG';
	
	// Border color 
	$border = 'd6d6d6';
	
	// URL for Gravatar
	$gravatarURL = "http://www.gravatar.com/avatar.php?gravatar_id=%s&default=%s&size=%s&border=%s&rating=%s";
	
	$avatarURL = sprintf
	(
		$gravatarURL, 
		md5($email), 
		$default_image,
		$size,
		$border,
		$rating
	);
	
	return '<img src="'. $avatarURL .'" width="'.	$size .'" height="'. $size . '" alt="'. $alt .'" title="'. $title .'" class="'. $class .'" id="'. $id .'" />';
}

//---------------------------------------------------------------

//--------------------------------------------------------------------
// !MODULE HELPERS
//--------------------------------------------------------------------

/*
	Function: module_folders();

	Returns an array of the folders that modules are allowed to be stored in. 
	These are set in *bonfire/application/third_party/MX/Modules.php*.
*/
function module_folders()
{
	return array_keys(modules::$locations);
}

//--------------------------------------------------------------------

/*
	Function: module_list()
 
	Returns a list of all modules in the system.
*/
function module_list($exclude_core=false)
{
	if (!function_exists('directory_map'))
	{
		$ci =& get_instance();
		$ci->load->helper('directory');
	}

	$map = array();

	foreach (module_folders() as $folder)
	{
		// If we're excluding core modules and this module
		// is in the 'core_modules' folder... ignore it.
		if ($exclude_core && strpos($folder, 'core_modules') !== false)
		{
			continue;
		}
		
		$map = array_merge($map, directory_map($folder, 1));
	}
	
	return $map;
}

//--------------------------------------------------------------------

/*
	Function: module_controller_exists()

	Determines whether a controller exists for a module.
	
	Parameters:
	$controller	- The name of the controller to looke for (without the .php)
	$module		- The name of module to look in.
	
	Return: 
		true/false
 */
function module_controller_exists($controller=null, $module=null)
{
	if (empty($controller) || empty($module))
	{
		return false;
	}
	
	// Look in all module paths
	foreach (module_folders() as $folder)
	{
		if (is_file($folder . $module .'/controllers/'. $controller .'.php'))
		{
			return true;
		}
	}
	
	return false;
}

//--------------------------------------------------------------------

/*
	Function: module_files()
	
	Returns an associative array of files within one or more modules.
	
	Parameters:
		$module_name	- If not NULL, will return only files from that module.
		$module_folder	- if not NULL, will return only files within that folder of each module (ie 'views')
		$exclude_core	- Whether we should ignore all core modules.
		
	Return:
		An associative array, like: array('module_name' => array('folder' => array('file1', 'file2')))
*/
function module_files($module_name=null, $module_folder=null, $exclude_core=false) 
{
	if (!function_exists('directory_map'))
	{
		$ci =& get_instance();
		$ci->load->helper('directory');
	}

	$files = array();

	foreach (module_folders() as $path)
	{
		// If we're ignoring core modules and we find the core_module folder... skip it.
		if ($exclude_core === true && strpos($path, 'core_modules') !== false)
		{
			continue;
		}
		
		if (!empty($module_name) && is_dir($path . $module_name))
		{
			$path = $path . $module_name;
		}
		
		$modules = directory_map($path);
		
		// If the element is not an array, we know that it's a file, 
		// so we ignore it, otherwise it is assumbed to be a module.
		if (!is_array($modules) || !count($modules))
		{
			continue;
		}

		foreach ($modules as $mod_name => $values)
		{
			if (is_array($values))
			{
				// Add just the specified folder for this module
				if (!empty($module_folder) && isset($values[$module_folder]))
				{
					$files[$mod_name] = array(
						$module_folder	=> $values[$module_folder]
					);
				}
				// Add the entire module
				else
				{
					$files[$mod_name] = $values;
				}
			}
		}
	}
	
	return $files;
}

//--------------------------------------------------------------------

/*
	Returns the path to the module's icon.
*/
function module_icon($module=null)
{
	if (empty($module))
	{
		return '';
	}
	
	// Find our module location
	foreach (module_folders() as $folder)
	{
		if (is_file($folder . $module .'/icon.png'))
		{
			$icon = $folder . $module .'/icon.png';
			return base_url() . str_replace(FCPATH, '', $icon);
		}
	}
	
	return '';
}