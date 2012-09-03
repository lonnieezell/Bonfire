<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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

/**
 * Config File Helpers
 *
 * Functions to aid in reading and saving config items to and from
 * configuration files.
 *
 * The config files are expected to be found in the APPPATH .'/config' folder.
 * It does not currently work within modules.
 *
 * @package    Bonfire
 * @subpackage Helpers
 * @category   Helpers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/config_file_helpers.html
 *
 */

if ( ! function_exists('read_config'))
{
	/**
	 * Returns an array of configuration settings from a single
	 * config file.
	 *
	 * @param $file string The config file to read.
	 * @param $fail_gracefully boolean Whether to show errors or simply return FALSE.
	 * @param $module string Name of the module where the config file exists.
	 *
	 * @return array An array of settings, or FALSE on failure (when $fail_gracefully = TRUE).
	 */
	function read_config($file, $fail_gracefully = TRUE, $module = '') 
	{
		$file = ($file == '') ? 'config' : str_replace(EXT, '', $file);
		$file = 'config/'.$file;
	
		$file_details = Modules::find($file, $module, '');
		
		if (is_array($file_details) && !empty($file_details[0]))
		{
			$file = implode("", $file_details);
		}
		else
		{
			$file = APPPATH.$file;
		}
		
		if ( ! file_exists($file.EXT))
		{
			if ($fail_gracefully === TRUE)
			{
				return FALSE;
			}
			show_error('The configuration file '.$file.EXT.' does not exist.');
		}
		
		include($file.EXT);
		
		if ( ! isset($config) OR ! is_array($config))
		{
			if ($fail_gracefully === TRUE)
			{
				return FALSE;
			}
			show_error('Your '.$file.EXT.' file does not appear to contain a valid configuration array.');
		}
		
		return $config;
		
	}//end read_config()
}

if ( ! function_exists('write_config'))
{
	/**
	 * Saves the passed array settings into a single config file located
	 * in the /config directory.
	 *
	 * The $settings passed in should be an array of key/value pairs, where
	 * the key is the name of the config setting and the value is it's value.
	 *
	 * @param $file string The config file to write to.
	 * @param $settings array An array of key/value pairs to be written to the file.
	 * @param $module string Name of the module where the config file exists.
	 *
	 * @return boolean
	 */
	function write_config($file='', $settings=null, $module='')
	{
		if (empty($file) || !is_array($settings	))
		{
			return FALSE;
		}
				
		$config_file = 'config/'.$file;
	
		$file_details = Modules::find($config_file, $module, '');
		
		if (is_array($file_details) && !empty($file_details[0]))
		{
			$config_file = implode("", $file_details);
		}
		else
		{
			$config_file = APPPATH.$config_file;
		}
	
		// Load the file so we can loop through the lines
		if (is_file($config_file . EXT))
		{
			$contents = file_get_contents($config_file.EXT);
			$empty = FALSE;
		}
		else 
		{
			$contents = '';
			$empty = TRUE;
		}
		
		foreach ($settings as $name => $val)
		{
			// Is the config setting in the file? 
			$start = strpos($contents, '$config[\''.$name.'\']');
			$end = strpos($contents, ';', $start);
			
			$search = substr($contents, $start, $end-$start+1);
			
			//var_dump($search); die();
			
			if (is_array($val))
			{
				// get the array output
				$val = config_array_output($val);
			}
			elseif (is_numeric($val))
			{
				$val = $val;
			}
			else
			{
				$val ="\"$val\"";
			}
	
			if (!$empty)
			{
				$contents = str_replace($search, '$config[\''.$name.'\'] = '. $val .';', $contents);
			}
			else 
			{
				$contents .= '$config[\''.$name.'\'] = '. $val .";\n";
			}
		}
		
		// Backup the file for safety
		$source = $config_file.EXT;	
		$dest = $module == '' ? APPPATH . 'archives/config/'.$file.EXT.'.bak' : $config_file.EXT.'.bak';
	
		if ($empty === FALSE) copy($source, $dest);
		
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
	
		$result = write_file($config_file.EXT, $contents);
		
		if ($result === FALSE)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
		
	}//end write_config()
}

if ( ! function_exists('config_array_output'))
{
	/**
	 * Outputs the array string which is then used in the config file.
	 *
	 * @param $array array Array of values to store in the config.
	 * @param $num_tabs int (Optional) The number of tabs to use in front of the array elements - makes it all nice !
	 *
	 * @return string A string of text which will make up the array values in the config file.
	 */
	function config_array_output($array, $num_tabs=1)
	{
		if (!is_array($array))
		{
			return FALSE;
		}
		
		$tval  = 'array(';
	
		// allow for two-dimensional arrays
		$array_keys = array_keys($array);
		// check if they are basic numeric keys
		if (is_numeric($array_keys[0]) && $array_keys[0] == 0)
		{
			$tval .= "'".implode("','", $array)."'";
		}
		else
		{
			$tabs = "";
			for ($num=0;$num<$num_tabs;$num++)
			{
				$tabs .= "\t";
			}
			
			// non-numeric keys
			foreach ($array as $key => $value)
			{
				if(is_array($value))
				{
					$num_tabs++;
					$tval .= "\n".$tabs."'".$key."' => ". config_array_output($value, $num_tabs). ",";
				}
				else
				{
					$tval .= "\n".$tabs."'".$key."' => '". $value. "',";
				}
			}//end foreach
			
			$tval .= "\n".$tabs;
		}//end if
		
		$tval .= ')';
			
		return $tval;

	}//end config_array_output()
}

if ( ! function_exists('read_db_config'))
{
	/**
	 * Retrieves the config/database.php file settings. Plays nice with CodeIgniter 2.0's
	 * multiple environment support.
	 *
	 * @param $environment string (Optional) The environment to get. If empty, will return all environments.
	 * @param $new_db string (Optional) Returns an new db config array with parameter as $db active_group name.
	 * @param $fail_gracefully boolean Whether to halt on errors or simply return FALSE.
	 *
	 * @return array|FALSE An array of database settings or abrupt failure (when $fail_gracefully == FALSE)
	 */
	function read_db_config($environment=null, $new_db = NULL, $fail_gracefully = TRUE)
	{
		$files = array();
	
		$settings = array();
	
		// Determine what environment to read.
		if (empty($environment))
		{
			$files['main'] 			= 'database';
			$files['development']	= 'development/database';
			$files['testing']		= 'testing/database';
			$files['production']	= 'production/database';
		}
		else
		{
			$files[$environment]	= "$environment/database";
		}
		
		// Grab our required settings
		foreach ($files as $env => $file)
		{	
			if ( file_exists(APPPATH.'config/'.$file.EXT))
			{
				include(APPPATH.'config/'.$file.EXT);
			}
			elseif ($fail_gracefully === FALSE)
			{
				show_error('The configuration file '.$file.EXT.' does not exist.');
			}
		
			//Acts as a reseter for given environment and active_group
			if (empty($db) && ($new_db !== NULL)) 
				//Do I wanna make sure we won't overwrite existing $db?
				//If not, removing empty($db) will always return a new db array for given ENV
			{ 
				$db[$new_db]['hostname'] = '';
				$db[$new_db]['username'] = '';
				$db[$new_db]['password'] = '';
				$db[$new_db]['database'] = '';
				$db[$new_db]['dbdriver'] = 'mysql';
				$db[$new_db]['dbprefix'] = '';
				$db[$new_db]['pconnect'] = TRUE;
				$db[$new_db]['db_debug'] = TRUE;
				$db[$new_db]['cache_on'] = FALSE;
				$db[$new_db]['cachedir'] = '';
				$db[$new_db]['char_set'] = 'utf8';
				$db[$new_db]['dbcollat'] = 'utf8_general_ci';
				$db[$new_db]['swap_pre'] = '';
				$db[$new_db]['autoinit'] = TRUE;
				$db[$new_db]['stricton'] = TRUE;
				$db[$new_db]['stricton'] = TRUE;
			}
		
			
			 //found file but is empty or clearly malformed
			if (empty($db) OR ! is_array($db))
			{
				//logit('[Config_File_Helper] Corrupt DB ENV file: '.$env,'debug');
				continue;
			}
			
			$settings[$env] = $db;
			unset($db);
		}
		
		unset($files);
		
		return $settings;

	}//end read_db_config()
}

if ( ! function_exists('write_db_config'))
{
	/**
	 * Saves the settings to the config/database.php file.
	 *
	 * @param $settings array The array of database settings. Should be in the format:
	 *
	 * <code>
	 * $settings = array(
	 *	'main' => array(
	 *		'setting1' => 'value',
	 *		...
	 *	),
	 *	'development' => array(
	 *		...
	 *	),
	 * );
	 * </code>
	 *
	 * @return boolean
	 */
	function write_db_config($settings=null) 
	{	
		if (!is_array($settings	))
		{
			logit('[Config_File_Helper] Invalid write_db_config PARAMETER!');
			return FALSE;
		}
		
		foreach ($settings as $env => $values)
		{
			if (strpos($env, '/') === FALSE)
			{
				$env .= '/';
			}
			
			// Is it the main file? 
			if ($env == 'main' || $env == 'main/')
			{
				$env = '';
			}
	
			// Load the file so we can loop through the lines
			$contents = file_get_contents(APPPATH.'config/'. $env .'database'.EXT);
	
			if (empty($contents) OR ! is_array($contents))
			{
				//logit('[Config_File_Helper] Error getting db file contents. Loading default database_format.php');
				$contents = file_get_contents(APPPATH.'config/database'.EXT);
			}
	
			if ($env != 'submit')
			{
				foreach ($values as $name => $value)
				{
					// Convert on/off to TRUE/FALSE values
					//$value = strtolower($value);
					if (strtolower($value) == 'on' || strtolower($value) == 'yes' || strtolower($value) == 'TRUE') $value = 'TRUE';
					if (strtolower($value) == 'on' || strtolower($value) == 'no' || strtolower($value) == 'FALSE') $value = 'FALSE';
				
					if ($value != 'TRUE' && $value != 'FALSE')
					{
						$value = "'$value'";
					}
				
					// Is the config setting in the file? 
					$start = strpos($contents, '$db[\'default\'][\''. $name .'\']');
					$end = strpos($contents, ';', $start);
					
					$search = substr($contents, $start, $end-$start+1);
					
					$contents = str_replace($search, '$db[\'default\'][\''. $name .'\'] = '. $value .';', $contents);
				}
	
				// Backup the file for safety
				$source = APPPATH .'config/'. $env .'database'.EXT;
				$dest_folder = APPPATH . config_item('site.backup_folder') .'config/'. $env;
				$dest = $dest_folder .'database'.EXT.'.bak';
				
				// Make sure our directory exists
				if (!is_dir($dest_folder))
				{
					mkdir($dest_folder, 0755, TRUE);
				}
				
				copy($source, $dest);
				
				// Make sure the file still has the php opening header in it...
				if (!strpos($contents, '<?php') === FALSE)
				{
					$contents = '<?php' . "\n" . $contents;
				}
				
				$CI = get_instance();
				$CI->load->helper('file');;
				
				// Write the changes out...
				$result = write_file(APPPATH.'config/'.$env .'database'.EXT, $contents);
			}
		}
		
		return $result;

	}//end write_db_config()
}