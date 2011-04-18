<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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
		
	Return:
		An array of settings, or false on failure (when $fail_gracefully = true).
 */
function read_config($file, $fail_gracefully=TRUE) 
{
	$file = ($file == '') ? 'config' : str_replace(EXT, '', $file);
	
	if ( ! file_exists(APPPATH.'config/'.$file.EXT))
	{
		if ($fail_gracefully === TRUE)
		{
			return FALSE;
		}
		show_error('The configuration file '.$file.EXT.' does not exist.');
	}
	
	include(APPPATH.'config/'.$file.EXT);

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
		
	Return: 
		true/false
 */
function write_config($file='', $settings=null) 
{
	if (empty($file) || !is_array($settings	))
	{
		return false;
	}
			
	// Load the file so we can loop through the lines
	if (is_file(APPPATH .'config/'. $file . EXT))
	{
		$contents = file_get_contents(APPPATH.'config/'.$file.EXT);
		$empty = false;
	} else 
	{
		$contents = '';
		$empty = true;
	}
	
	// Clean up post
	if (isset($settings['submit'])) unset($settings['submit']);
	
	foreach ($settings as $name => $val)
	{
		// Is the config setting in the file? 
		$start = strpos($contents, '$config[\''.$name.'\']');
		$end = strpos($contents, ';', $start);
		
		$search = substr($contents, $start, $end-$start+1);
		
		//var_dump($search); die();
		
		if (is_array($val))
		{
			$tval  = 'array(\'';
			$tval .= implode("','", $val);
			$tval .= '\')';
		
			$val = $tval;
			unset($tval);
		} else 
		if (is_numeric($val))
		{
			$val = $val;
		} else
		{
			$val ="'$val'";
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
	$source = APPPATH . 'config/'.$file.EXT;
	$dest = APPPATH . 'config/'.$file.EXT.'.bak';
	if ($empty === false) copy($source, $dest);
	
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

	$result = write_file(APPPATH.'config/'.$file.EXT, $contents);
	
	if ($result === FALSE)
	{
		return false;
	} else {
		return true;
	}
}

//---------------------------------------------------------------

/*
	Function: read_db_config()

	Retrieves the config/database.php file settings. Plays nice with CodeIgniter 2.0's
	multiple environment support.
	
	Parameters:
		$environment	- (Optional) The envinroment to get. If empty, will return all environments.
		
	Return:
		An array of database settings.
 */  
function read_db_config($environment=null) 
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
		include(APPPATH.'config/'.$file.EXT);
	
		if ( ! isset($db) OR ! is_array($db))
		{
			return FALSE;
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
		return false;
	}
	
	// Clean up post
	if (isset($_POST['submit'])) unset($_POST['submit']);
	
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
	
			// Backup the file for safety
			$source = APPPATH .'config/'. $env .'database'.EXT;
			$dest_folder = APPPATH . config_item('site.backup_folder') .'config/'. $env;
			$dest = $dest_folder .'database'.EXT.'.bak';
			
			// Make sure our directory exists
			if (!is_dir($dest_folder))
			{
				mkdir($dest_folder, 0755, true);
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
}

//---------------------------------------------------------------
	

// End Config helper