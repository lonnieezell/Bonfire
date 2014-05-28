<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Config file helper functions.
 *
 * Functions to aid in reading and saving config items to and from configuration
 * files.
 *
 * The config files are expected to be found in the APPPATH . '/config' folder.
 * It does not currently work within modules.
 *
 * @package    Bonfire\Helpers\config_file_helper
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/developer
 */

// Since this is listed as deprecated in /public/index.php, ensure it's defined
// before using it in read_config(), read_db_config(), and write_config().
defined('EXT') || define('EXT', '.php');

// Ensure this is defined before using it in write_db_config().
defined('DIR_READ_MODE') || define('DIR_READ_MODE', 0755);

if ( ! function_exists('config_array_output')) {
	/**
	 * Output the array string which is then used in the config file.
	 *
	 * @param array $array   Values to store in the config.
	 * @param int   $numTabs (Optional) The number of tabs to use in front of
	 * the array elements (for formatting/presentation).
	 *
	 * @return string/bool A string of text which will make up the array values
	 * in the config file, or false.
	 */
	function config_array_output($array, $numTabs = 1)
	{
		if ( ! is_array($array)) {
			return false;
		}

		$tval  = 'array(';

		// Allow for two-dimensional arrays
		$arrayKeys = array_keys($array);

		// Check whether they are basic numeric keys
		if (is_numeric($arrayKeys[0]) && $arrayKeys[0] == 0) {
			$tval .= "'" . implode("','", $array) . "'";
		} else {
			$tabs = "";
			for ($num = 0; $num < $numTabs; $num++) {
				$tabs .= "\t";
			}

			// Non-numeric keys
			foreach ($array as $key => $value) {
                $tval .= "\n{$tabs}'{$key}' => ";
				if (is_array($value)) {
					$numTabs++;
                    $tval .= config_array_output($value, $numTabs);
				} else {
					$tval .= "'{$value}'";
				}
                $tval .= ',';
			}
			$tval .= "\n{$tabs}";
		}

		$tval .= ')';

		return $tval;
	}
}

if ( ! function_exists('read_config')) {
	/**
	 * Return an array of configuration settings from a single config file.
	 *
	 * @param string $file           The config file to read.
	 * @param bool   $failGracefully Whether to show errors or simply return false.
	 * @param string $module         Name of the module where the config file exists.
	 * @param bool   $moduleOnly     Whether to fail if config does not exist in module directory.
	 *
	 * @return array An array of settings, or FALSE on failure (when
	 * $failGracefully == TRUE).
	 */
	function read_config($file, $failGracefully = true, $module = '', $moduleOnly = false)
	{
		$file = $file == '' ? 'config' : str_replace(EXT, '', $file);

		// Look in module first
		$found = false;
		if ($module) {
			$fileDetails = Modules::file_path($module, 'config', "{$file}.php");
			if ( ! empty($fileDetails)) {
				$file = $fileDetails;
				$found = true;
			}
		}

		// Fall back to application directory
		if ( ! $found && ! $moduleOnly) {
            $checkLocations = array();

            if (defined('ENVIRONMENT')) {
                $checkLocations[] = APPPATH . 'config/' . ENVIRONMENT . "/{$file}";
            }

            $checkLocations[] = APPPATH . "config/{$file}";

            foreach ($checkLocations as $location) {
                if (file_exists($location . EXT)) {
                    $file = $location;
                    $found = true;
                    break;
                }
            }
		}

		if ( ! $found) {
			if ($failGracefully === true) {
				return false;
			}

			show_error("The configuration file {$file}" . EXT . ' does not exist.');
		}

		include($file . EXT);

		if ( ! isset($config) || ! is_array($config)) {
			if ($failGracefully === true) {
				return false;
			}

			show_error("Your {$file}" . EXT . ' file does not appear to contain a valid configuration array.');
		}

		return $config;
	}
}

if ( ! function_exists('read_db_config')) {
	/**
	 * Retrieve the config/database.php file settings. Plays nice with
	 * CodeIgniter 2.0's multiple environment support.
	 *
	 * @param string $environment    (Optional) The environment to get. If empty,
	 * will return all environments.
	 * @param string $newDb          (Optional) Returns a new db config array
	 * with parameter as $db active_group name.
	 * @param bool   $failGracefully Whether to halt on errors or simply return
	 * FALSE.
	 *
	 * @return array|FALSE An array of database settings or abrupt failure (when
	 * $failGracefully == FALSE).
	 */
	function read_db_config($environment = null, $newDb = null, $failGracefully = true)
	{
		$files = array();
		$settings = array();

		// Determine which environment to read.
		if (empty($environment)) {
			$files['main'] 		  = 'database';
			$files['development'] = 'development/database';
			$files['testing']	  = 'testing/database';
			$files['production']  = 'production/database';
		} else {
			$files[$environment]	= "$environment/database";
		}

		// Grab the required settings
		foreach ($files as $env => $file) {
			if (file_exists(APPPATH . "config/{$file}" . EXT)) {
				include(APPPATH . "config/{$file}" . EXT);
			} elseif ($failGracefully === false) {
				show_error("The configuration file {$file}" . EXT . ' does not exist.');
			}

			// Acts as a reset for given environment and active_group
			if (empty($db) && $newDb !== null) {
				// @todo Do I wanna make sure we won't overwrite existing $db?
				// If not, removing empty($db) will always return a new db
                // array for given ENV

				$db[$newDb] = array(
                    'hostname' => '',
                    'username' => '',
                    'password' => '',
                    'database' => '',
                    'dbdriver' => 'mysql',
                    'dbprefix' => '',
                    'pconnect' => true,
                    'db_debug' => true,
                    'cache_on' => false,
                    'cachedir' => '',
                    'char_set' => 'utf8',
                    'dbcollat' => 'utf8_general_ci',
                    'swap_pre' => '',
                    'autoinit' => true,
                    'stricton' => true,
                    'stricton' => true,
                );
			}

			// Found file but it is empty or clearly malformed
			if (empty($db) || ! is_array($db)) {
				// logit('[Config_File_Helper] Corrupt DB ENV file: '.$env,'debug');
				continue;
			}

			$settings[$env] = $db;
			unset($db);
		}

		unset($files);

		return $settings;
	}
}

if ( ! function_exists('write_config')) {
	/**
	 * Save the passed array settings into a single config file located in the
	 * /config directory.
	 *
	 * The $settings passed in should be an array of key/value pairs, where the
	 * key is the name of the config setting and the value is its value.
	 *
	 * @param string $file     The config file to write to.
	 * @param array  $settings An array of key/value pairs to be written to the file.
	 * @param string $module   Name of the module where the config file exists.
	 *
	 * @return boolean
	 */
	function write_config($file = '', $settings = null, $module = '', $apppath = APPPATH)
	{
		if (empty($file) || ! is_array($settings)) {
			return false;
		}

		$configFile = "config/{$file}";

		// Look in module first
		$found = false;
		if ($module) {
			$fileDetails = Modules::find($configFile, $module, '');
			if ( ! empty($fileDetails) && ! empty($fileDetails[0])) {
				$configFile = implode('', $fileDetails);
				$found = true;
			}
		}

		// Fall back to application directory
		if ( ! $found) {
			$configFile = "{$apppath}{$configFile}";
			$found = is_file($configFile . EXT);
		}

		// Load the file and loop through the lines
		if ($found) {
			$contents = file_get_contents($configFile . EXT);
			$empty = false;
		} else {
			$contents = '';
			$empty = true;
		}

		foreach ($settings as $name => $val) {
			// Is the config setting in the file?
			$start  = strpos($contents, '$config[\'' . $name . '\']');
			$end    = strpos($contents, ';', $start);
			$search = substr($contents, $start, $end - $start + 1);

			if (is_array($val)) {
				// Get the array output
				$val = config_array_output($val);
			} elseif ( ! is_numeric($val)) {
				$val = "\"$val\"";
			}

			if ($empty) {
                $contents .= '$config[\'' . $name . '\'] = ' . $val . ";\n";
            } else {
				$contents = str_replace(
                    $search,
                    '$config[\'' . $name . '\'] = ' . $val . ';',
                    $contents
                );
			}
		}

		// Backup the file for safety
		$source = $configFile . EXT;
		$dest = ($module == '' ? "{$apppath}archives/{$file}" : $configFile)
            . EXT . '.bak';

		if ($empty === false) {
            copy($source, $dest);
        }

		// Make sure the file still has the php opening header in it...
		if (strpos($contents, '<?php') === false) {
			$contents = "<?php defined('BASEPATH') || exit('No direct script access allowed');\n\n{$contents}";
		}

		// Write the changes out...
		if ( ! function_exists('write_file')) {
			$CI = get_instance();
			$CI->load->helper('file');
		}

		$result = write_file($configFile . EXT, $contents);
		if ($result === false) {
			return false;
		}

        return true;
	}
}

if ( ! function_exists('write_db_config')) {
	/**
	 * Save the settings to the config/database.php file.
	 *
	 * @param array $settings The array of database settings. Should be in the
	 * format:
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
	function write_db_config($settings = null, $apppath = APPPATH)
	{
		if ( ! is_array($settings)) {
			logit('[Config_File_Helper] Invalid write_db_config PARAMETER!');
			return false;
		}

		foreach ($settings as $env => $values) {
			if (strpos($env, '/') === false) {
				$env .= '/';
			}

			// Is it the main file?
			if ($env == 'main/') {
				$env = '';
			}

			// Load the file and loop through the lines
			$contents = file_get_contents("{$apppath}config/{$env}database.php");
			if (empty($contents)) {
				return false;
			}

			if ($env != 'submit') {
				foreach ($values as $name => $value) {
					// Convert on/off to TRUE/FALSE values
					// $value = strtolower($value);
					if (strtolower($value) == 'on'
                        || strtolower($value) == 'yes'
                        || strtolower($value) == 'true'
                        || $value === true
                    ) {
                        $value = 'TRUE';
                    }

                    // @todo should the first value in this list be 'off'?
					if (strtolower($value) == 'on'
                        || strtolower($value) == 'no'
                        || strtolower($value) == 'false'
                        || $value === false
                    ) {
                        $value = 'FALSE';
                    }

					if ($value != 'TRUE' && $value != 'FALSE') {
						$value = "'{$value}'";
					}

					// Is the config setting in the file?
					$start  = strpos($contents, '$db[\'default\'][\'' . $name . '\']');
					$end    = strpos($contents, ';', $start);
					$search = substr($contents, $start, $end - $start + 1);

					$contents = str_replace(
                        $search,
                        '$db[\'default\'][\'' . $name . '\'] = ' . $value . ';',
                        $contents
                    );
				}

				// Backup the file for safety
				$source = "{$apppath}config/{$env}database.php";
				$destFolder = $apppath . config_item('site.backup_folder') . "config/{$env}";
				$dest = "{$destFolder}database.php.bak";

				// Make sure the directory exists
				if ( ! is_dir($destFolder)) {
					mkdir($destFolder, DIR_READ_MODE, true);
				}

				copy($source, $dest);

				// Make sure the file still has the php opening header in it...
				if ( ! strpos($contents, '<?php') === false) {
					$contents = "<?php\n{$contents}";
				}

				$CI = get_instance();
				$CI->load->helper('file');;

				// Write the changes out...
				$result = write_file("{$apppath}config/{$env}database.php", $contents);
			}
		}

		return $result;
	}
}
/* End /helpers/config_file_helper.php */