<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Pulls together various helper functions from across the core modules
	to ease editing and minimize physical files that need loaded.
*/

//--------------------------------------------------------------------

//--------------------------------------------------------------------
// !AUTH HELPERS
//--------------------------------------------------------------------

/** 
 * A convenient shorthand for checking user permissions.
 * 
 * @param	string	$permission	- The permission to check for, ie 'Site.Signin.Allow'
 * @return	true/false
 */ 
function has_permission($permission=null)
{
	$ci =& get_instance();
	
	if (class_exists('Auth'))
	{
		return $ci->auth->has_permission($permission); 
	}
	
	return false;
}

//--------------------------------------------------------------------

//--------------------------------------------------------------------
// !MODULE HELPERS
//--------------------------------------------------------------------

/**
 * This helper provides tools for working with modules, using
 * the Modular Separation HMVC modules library.
 *
 */

function module_folders()
{
	return array_keys(modules::$locations);
}

//--------------------------------------------------------------------

/** 
 * Returns a list of all modules in the system.
 */
function module_list()
{
	if (!function_exists('directory_map'))
	{
		$ci =& get_instance();
		$ci->load->helper('directory');
	}

	$map = array();

	foreach (module_folders() as $folder)
	{
		$map = array_merge($map, directory_map($folder, 1));
	}
	
	return $map;
}

//--------------------------------------------------------------------

/**
 * Determines whether a controller exists for a module.
 *
 * @param	string	$controller	The name of the controller to looke for (without the .php)
 * @param	string	$module		The name of module to look in.
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

/**
 * Returns the path to the module's icon.
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