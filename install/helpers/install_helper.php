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
	File: Application Helper

	Pulls together various helper functions from across the core modules
	to ease editing and minimize physical files that need loaded.
*/

//--------------------------------------------------------------------


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
function message($message='', $type='information') 
{	
	$ci =& get_instance();
	
	// Does session data exist? 
	if (empty($message) && class_exists('CI_Session'))
	{
		$message = $ci->session->flashdata('message');

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
		return '';
	}

	// Grab out message template and replace the placeholders
	$template = str_replace('{type}', $type, $ci->config->item('template.message_template'));
	$template = str_replace('{message}', $message, $template);

	// Clear our session data so we don't get extra messages. 
	// (This was a very rare occurence, but clearing should resolve the problem.
	if (class_exists('CI_Session'))
	{
		$ci->session->set_flashdata('message', '');
	}

	return $template;
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
	return array_keys(Install::$locations);
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
	
	// Clean out any html or php files
	if ($count = count($map))
	{
		for ($i=0; $i < $count; $i++)
		{
			if (strpos($map[$i], '.html') !== false || strpos($map[$i], '.php') !== false)
			{
				unset($map[$i]);
			}
		}
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
	Function: module_file_path()
	
	Finds the path to a module's file.
	
	Parameters:
		$module	- The name of the module to find.
		$folder	- The folder within the module to search for the file (ie. controllers) 
		$file	- The name of the file to search for.
		
	Returns:
		A string with the full path to the file.
*/
function module_file_path($module=null, $folder=null, $file=null)
{
	if (empty($module) || empty($folder) || empty($file))
	{
		return false;
	}
	
	foreach (module_folders() as $module_folder)
	{
		$test_file = $module_folder . $module .'/'. $folder .'/'. $file;
	
		if (is_file($test_file))
		{
			return $test_file;
		}
	}
}

//--------------------------------------------------------------------

/*
	Function module_path()
	
	Returns the path to the module and it's specified folder.
	
	Parameters: 
		$module	- The name of the module (must match the folder name)
		$folder	- The folder name to search for. (Optional)
		
	Returns:
		A string with the path, relative to the front controller.
*/
function module_path($module=null, $folder=null)
{
	foreach (module_folders() as $module_folder)
	{
		if (is_dir($module_folder . $module))
		{
			if (!empty($folder) && is_dir($module_folder . $module .'/'. $folder))
			{
				return $module_folder . $module .'/'. $folder;
			}
			else
			{
				return $module_folder . $module .'/';
			}
		}
	}
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
			$modules[$module_name] = directory_map($path);
		}
		else 
		{		
			$modules = directory_map($path);
		}
		
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
				if (!empty($module_folder) && isset($values[$module_folder]) && count($values[$module_folder]))
				{
					$files[$mod_name] = array(
						$module_folder	=> $values[$module_folder]
					);
				}
				// Add the entire module
				else if (empty($module_folder))
				{
					$files[$mod_name] = $values;
				}
			}
		}
	}
	
	return count($files) ? $files : false;
}

//--------------------------------------------------------------------

/*
	Function: module_config()
	
	Returns the 'module_config' array from a modules config/config.php file.
	
	Parameters:
		$module_name	- The name of the module.
		$return_full	- If true, will return the entire config array.
						  If false, will return only the 'module_config' portion.
						  
	The 'module_config' contains more information about a module, and even provide 
	enhanced features within the UI. All fields are optional.
	
	$config['module_config'] = array(
		'name'			=> 'Blog', 			// The name that is displayed in the UI
		'description'	=> 'Simple Blog',	// May appear at various places within the UI
		'author'		=> 'Your Name',		// The name of the module's author
		'homepage'		=> 'http://...',	// The module's home on the web
		'version'		=> '1.0.1',			// Currently installed version
		'menu'			=> array(			// A view file containing an <ul> that will be the sub-menu in the main nav.
			'context'	=> 'path/to/view'
		)
	);
	
	Author: 
		Liam Rutherford (http://www.liamr.com)
						  
	Returns:
		An array of config settings, or an empty array if empty/not found.
*/
function module_config($module_name=null, $return_full=false)
{
	$config_param = array();

	$config_file = module_file_path($module_name, 'config', 'config.php');
	
	if (file_exists($config_file)) 
	{ 
		include($config_file);
	
		/* Check for the optional module_config and serialize if exists*/
		if (isset($config['module_config'])) 
		{	
			$config_param =$config['module_config'];
		}
		else if ($return_full === true && isset($config) && is_array($config))
		{
			$config_param = $config;
		}
	}
	
	return $config_param;
}

//--------------------------------------------------------------------


//--------------------------------------------------------------------