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
 * Language Helpers
 *
 * Includes fucntions to help with the management of the language files.
 *
 * @package    Bonfire
 * @subpackage Modules_Translate
 * @category   Helpers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */


if (!function_exists('list_languages'))
{

	/**
	 * Lists the existing languages in the system by examining
	 * the core language folders in bonfire/application/language.
	 *
	 * @return array Array of the language directories
	 */
	function list_languages()
	{
		$folder = APPPATH .'language/';

		$ci =& get_instance();
		$ci->load->helper('directory');

		$folders = directory_map($folder, 1);

		return $folders;

	}//end list_languages()
}


	//--------------------------------------------------------------------

if (!function_exists('list_lang_files'))
{
	/**
	 * Finds a list of all language files for a specific language by
	 * searching the application/languages folder as well as all core module
	 * folders for folders matching the language name.
	 *
	 * @param string $language The language
	 *
	 * @return array An array of files.
	 */
	function list_lang_files($language='english')
	{
		$ci =& get_instance();
		$ci->load->helper('file');

		$lang_files = array();

		// Base language files.
		$lang_files['core'] = find_lang_files(APPPATH .'language/'. $language .'/');

		// Module lang files
		$modules = module_list();
		$custom_modules = module_list(TRUE);

		foreach ($modules as $module)
		{
			$module_langs = module_files($module, 'language');
			$type = 'core';

			if (isset($module_langs[$module]['language'][$language]))
			{
				$path = implode('/', array($module, 'language', $language));

				if (in_array($module, $custom_modules))
				{
					$files = find_lang_files(realpath(APPPATH .'/modules/'. $path) .'/');
					$type = 'custom';

				}
				else
				{
					$files = find_lang_files(BFPATH .'modules/'. $path .'/');
				}

				if (is_array($files))
				{
					foreach ($files as $file)
					{
						$lang_files[$type][] = $file;
					}
				}
			}//end if
		}//end foreach

		return $lang_files;

	}//end list_lang_files()
}


	//--------------------------------------------------------------------

if (!function_exists('find_lang_files'))
{
	/**
	 * Searches an individual folder for any language files,
	 * and returns an array appropriate for adding to the $lang_files
	 * array in get_lang_files() function.
	 *
	 * @return array An array of files
	 */
	function find_lang_files($path=NULL)
	{

		if (!is_dir($path))
		{
			return NULL;
		}

		$files = array();

		foreach (glob("{$path}*_lang.php") as $filename)
		{
			$files[] = basename($filename);
		}

		return $files;

	}//end find_lang_files()
}
	//--------------------------------------------------------------------

if (!function_exists('load_lang_file'))
{
	/**
	 * Loads a single language file into an array.
	 *
	 * @param string $filename The name of the file to locate. The file will be found by looking in all modules.
	 * @param string $language The language to retrieve.
	 *
	 * @return mixed An array on loading the language file, FALSE on error
	 */
	function load_lang_file($filename=NULL, $language='english')
	{
		if (empty($filename))
		{
			return NULL;
		}

		$array = FALSE;

		// Is it the application_lang file?
		if ($filename == 'application_lang.php' || $filename == 'datatable_lang.php')
		{
			$path = APPPATH .'language/'. $language .'/'. $filename;
		}
		// Look in modules
		else
		{
			$module = str_replace('_lang.php', '', $filename);

			$path = module_file_path($module, 'language', $language .'/'. $filename);
		}

		// Load the actual array
		if (is_file($path))
		{
			include($path);
		}

		if (isset($lang) && is_array($lang))
		{
			$array = $lang;
			unset($lang);
		}

		return $array;

	}//end load_lang_file()
}
	//--------------------------------------------------------------------

if (!function_exists('save_lang_file'))
{
	/**
	 * Save a language file
	 *
	 * @param string $filename The name of the file to locate. The file will be found by looking in all modules.
	 * @param string $language The language to retrieve.
	 * @param array  $settings An array of the language settings
	 * @param bool   $return   TRUE to return the contents or FALSE to write to file
	 *
	 * @return mixed A string when the $return setting is TRUE
	 */
	function save_lang_file($filename=NULL, $language='english', $settings=NULL, $return=FALSE)
	{
		if (empty($filename) || !is_array($settings))
		{
			return FALSE;
		}

		// Is it the application_lang file?
		if ($filename == 'application_lang.php' || $filename == 'datatable_lang.php')
		{
			$orig_path = APPPATH .'language/english/'. $filename;
			$path = APPPATH .'language/'. $language .'/'. $filename;
		}
		// Look in core modules
		else
		{
			$module = str_replace('_lang.php', '', $filename);

			$orig_path = module_file_path($module, 'language', 'english/'. $filename);
			$path = module_file_path($module, 'language', $language .'/'. $filename);

			// If it's empty still, just grab the module path
			if (empty($path))
			{
				$path = module_path($module, 'language');
			}
		}

		// Load the file so we can loop through the lines
		if (!is_file($orig_path))
		{
			return FALSE;
		}
		$contents = file_get_contents($orig_path);
		$contents = trim($contents) . "\n";

		if (!is_file($path))
		{
			// Create the folder...
			$folder = basename($path) == 'language' ? $path .'/'. $language : dirname($path);

			if (!is_dir($folder))
			{
				mkdir($folder);
				$path = basename($path) == 'language' ? $folder .'/'. $module .'_lang.php' : $path;
			}
		}

		// Save the file.
		foreach ($settings as $name => $val)
		{
			// Use strrpos() instead of strpos() so we don't lose data
			// when people have put duplicate keys in the english files
			$start = strrpos($contents, '$lang[\''.$name.'\']');
			if ($start === FALSE)
			{
				// tried to add non-existent value?
				return FALSE;
			}
			$end = strpos($contents, "\n", $start) + strlen("\n");

			if ($val !== '')
			{
				$val = '\'' . addcslashes($val, '\'\\') .'\'';
				$replace = '$lang[\''.$name.'\'] = ' . $val . ";\n";
			}
			else
			{
				$replace = '// ' . substr($contents, $start, $end-$start);
			}

			$contents = substr($contents, 0, $start) . $replace . substr($contents, $end);

		}//end foreach

		// is the code we are producing OK?
		if (!is_null(eval(str_replace('<?php', '', $contents))))
		{
			return FALSE;
		}

		// Make sure the file still has the php opening header in it...
		if (strpos($contents, '<?php') === FALSE)
		{
			$contents = '<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');' . "\n\n" . $contents;
		}

		// Write the changes out...
		if (!function_exists('write_file'))
		{
			$CI = get_instance();
			$CI->load->helper('file');
		}

        if ($return == FALSE)
        {
		    $result = write_file($path, $contents);
        }
        else
        {
            return $contents;
        }

		if ($result === FALSE)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}

	}//end save_lang_file()
}
	//--------------------------------------------------------------------
