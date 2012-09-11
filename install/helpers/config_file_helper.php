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
	File: Config File Helper

	Functions to aid in reading and saving config items to and from
	configuration files.

	The config files are expected to be found in the APPPATH .'/config' folder.
	It does not currently work within modules.

	Author:
		Lonnie Ezell
 */

 //--------------------------------------------------------------------

/*
	Function: read_config()

	Returns an array of configuration settings from a single
	config file.

	Parameters:
		$file				- The config file to read.
		$fail_gracefully	- true/false. Whether to show errors or simply return false.
		$module				- Name of the module where the config file exists

	Return:
		An array of settings, or false on failure (when $fail_gracefully = true).
 */
function read_config($file, $fail_gracefully = TRUE, $module = '')
{
	$file = ($file == '') ? 'config' : str_replace(EXT, '', $file);
	$file = 'config/'.$file;

	$file_details = '';
	if (!empty($module))
	{
		$file_details = Modules::find($file, $module, '');
	}

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
}

//---------------------------------------------------------------

/*
	Function: write_config()

	Saves the passed array settings into a single config file located
	in the /config directory.

	Parameters:
		$file		- The config file to write to.
		$settigns	- An array of key/value pairs to be written to the file.
		$module				- Name of the module where the config file exists

	Return:
		true/false
 */
function write_config($file='', $settings=null, $module='')
{
	if (empty($file) || !is_array($settings	))
	{
		return false;
	}

	$config_file = APPPATH.'../bonfire/application/config/'.$file;

	// Load the file so we can loop through the lines
	if (is_file($config_file . EXT))
	{
		$contents = file_get_contents($config_file.EXT);
		$empty = false;
	}
	else
	{
		$contents = '';
		$empty = true;
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
		return false;
	} else {
		return true;
	}
}


//---------------------------------------------------------------

/*
	Function: config_array_output()

	Outputs the array string which is then used in the config file.

	Parameters:
		$array			- Array of values to store in the config
		$num_tabs		- (Optional) The number of tabs to use in front of the array elements - makes it all nice !

	Return:
		A string of text which will make up the array values in the config file
 */
function config_array_output($array, $num_tabs=1)
{
	if (!is_array($array))
	{
		return false;
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


//---------------------------------------------------------------

/*
	Function: read_db_config()

	Retrieves the config/database.php file settings. Plays nice with CodeIgniter 2.0's
	multiple environment support.

	Parameters:
		$environment	- (Optional) The envinroment to get. If empty, will return all environments.
		$new_db	(str)		- (Optional) Returns an new db config array with parameter as $db active_group name
		$fail_gracefully	- true/false. Whether to halt on errors or simply return false.

	Return:
		An array of database settings or abrupt failure (when $fail_gracefully == FALSE)
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
	} else
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
		else if ($fail_gracefully === FALSE)
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
}

//---------------------------------------------------------------

/*
	Function: write_db_config()

	Saves the settings to the config/database.php file.

	Parameters:
		$settings	- The array of database settings. Should be in the format:
					array(
						'main' => array(
							'setting1' => value,
							...
						),
						'development' => array(
							...
						)
					);

	Return:
		true/false
 */
function write_db_config($settings=null)
{
	if (!is_array($settings	))
	{
		logit('[Config_File_Helper] Invalid write_db_config PARAMETER!');
		return false;
	}

	foreach ($settings as $env => $values)
	{
		if (strpos($env, '/') === false)
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
				if (strtolower($value) == 'on' || strtolower($value) == 'yes' || strtolower($value) == 'true') $value = 'TRUE';
				if (strtolower($value) == 'on' || strtolower($value) == 'no' || strtolower($value) == 'false') $value = 'FALSE';

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

			// Make sure the file still has the php opening header in it...
			if (!strpos($contents, '<?php') === FALSE)
			{
				$contents = '<?php' . "\n" . $contents;
			}

			$CI = get_instance();
			$CI->load->helper('file');;

			// Write the changes out...
			$result = write_file(APPPATH.'../bonfire/application/config/'.$env .'database'.EXT, $contents);
		}
	}

	return $result;
}
//---------------------------------------------------------------
// End Config helper