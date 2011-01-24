<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Config Helper
 *
 * Functions to aid in reading and saving config items to and from
 * their existing files.
 */

	/**
	 *	GET_CONFIG()
	 *
	 * Returns an array of configuration settings from a single 
	 * config file. 
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
	
	/**
	 *	SAVE()
	 *
	 *	Saves the passed array settings into a single config file located
	 * 	in the /config directory. 
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
		$result = file_put_contents(APPPATH.'config/'.$file.EXT, $contents, LOCK_EX);
		
		if ($result === FALSE)
		{
			return false;
		} else {
			return true;
		}
	}
	
	//---------------------------------------------------------------
	
	/**
	 * Retrieves the config/database.php file settings.
	 */  
	function read_db_config($group='') 
	{
		$file = 'database';
		
		if ( ! file_exists(APPPATH.'config/'.$file.EXT))
		{
			if ($fail_gracefully === TRUE)
			{
				return FALSE;
			}
			show_error('The configuration file '.$file.EXT.' does not exist.');
		}
		
		include(APPPATH.'config/'.$file.EXT);

		if ( ! isset($db) OR ! is_array($db))
		{
			if ($fail_gracefully === TRUE)
			{
				return FALSE;
			}
			show_error('Your '.$file.EXT.' file does not appear to contain a valid configuration array.');
		}
		
		if (!empty($group))
		{
			return $db[$group];
		} 
		else 
		{
			return $db;
		}
	}
	
	//---------------------------------------------------------------
	
	/**
	 *	Saves the settings to the config/database.php file.
	 */
	function write_db_config($settings=null) 
	{	
		if (!is_array($settings	))
		{
			return false;
		}
		
		// Clean up post
		if (isset($_POST['submit'])) unset($_POST['submit']);
		
		// Load the file so we can loop through the lines
		$contents = file_get_contents(APPPATH.'config/'.'database'.EXT);
		
		foreach ($settings as $group => $values)
		{
			if ($group != 'submit')
			{
				foreach ($values as $name => $value)
				{
					// Convert on/off to TRUE/FALSE values
					$value = strtolower($value);
					if ($value == 'on' || $value == 'yes' || $value == 'true') $value = 'TRUE';
					if ($value == 'on' || $value == 'no' || $value == 'false') $value = 'FALSE';
				
					if ($value != 'TRUE' && $value != 'FALSE')
					{
						$value = "'$value'";
					}
				
					// Is the config setting in the file? 
					$start = strpos($contents, '$db[\''.$group.'\'][\''. $name .'\']');
					$end = strpos($contents, ';', $start);
					
					$search = substr($contents, $start, $end-$start+1);
					
					$contents = str_replace($search, '$db[\''.$group.'\'][\''. $name .'\'] = '. $value .';', $contents);
				}
			}
		}
		
		// Backup the file for safety
		$source = APPPATH . 'config/database'.EXT;
		$dest = APPPATH . 'config/database'.EXT.'.bak';
		copy($source, $dest);
		
		// Make sure the file still has the php opening header in it...
		if (!strpos($contents, '<?php') === FALSE)
		{
			$contents = '<?php' . "\n" . $contents;
		}
		
		// Write the changes out...
		$result = file_put_contents(APPPATH.'config/'.'database'.EXT, $contents, LOCK_EX);
		//$result = false;
		
		if ($result === FALSE)
		{
			return false;
		} else {
			return true;
		}
	}
	
	//---------------------------------------------------------------
	

// End Config helper