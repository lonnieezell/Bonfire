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

// ------------------------------------------------------------------------

/**
 * Bonfire Language Class
 *
 * This class replaces both CI_Lang and MX_Lang.
 *
 * It will fall back to english for un-translated lines.
 */
class MY_Lang extends MX_Lang {

	public function __construct()
	{
		log_message('debug', "Bonfire MY_Lang: Language Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Load a language file
	 *
	 * This version always loads english first (as a fallback).
	 * It will tolerate either file being missing (but not both).
	 *
	 * It doesn't implement any of the advanced options.
	 *
	 * @param	string	the name of the language file to be loaded
	 * @param	string	the language (english, etc.)
	 * @return	void
	 */
	public function load($langfile)
	{
		if (in_array($langfile . '_lang.php', $this->is_loaded, TRUE))
		{
			return;
		}

		$config =& get_config();
		$idiom = $config['language'];

		$loaded = $this->__load($langfile, 'english');

		if ($idiom != 'english')
		{
			$loaded_foreign = $this->__load($langfile, $idiom);
			if ($loaded_foreign)
			{
				$loaded = TRUE;
			}
			else
			{
				log_message('debug', "Unable to load the requested language file '$langfile' for current language '$idiom'.");
			}
		}

		if (!$loaded)
		{
			show_error("Unable to load the requested language file '$langfile' for current language AND for fallback to English.");
		}

		$this->is_loaded[] = $langfile.'_lang.php';
	}

	private function __load($langfile, $idiom)
	{
		$module = CI::$APP->router->fetch_module();
		list($path, $file) = Modules::find($langfile.'_lang', $module, 'language/'.$idiom.'/');
		if ($path)
		{
			// Module file
			$lang = Modules::load_file($file, $path, 'lang');
		}

		if (!isset($lang))
		{
			// Determine where the language file is and load it
			foreach (get_instance()->load->get_package_paths(TRUE) as $package_path)
			{
				$file = $package_path.'language/'.$idiom.'/'.$langfile.'_lang.php';

				if (file_exists($file))
				{
					include $file;
					if (!isset($lang))
					{
						log_message('error', "Language file contains no data? $file");
					}
					break;
				}
			}
		}

		if (!isset($lang))
		{
			return FALSE;
		}

		$this->language = array_merge($this->language, $lang);
		log_message('debug', 'Bonfire MY_Lang: Language file loaded: language/'.$idiom.'/'.$langfile.'_lang.php');
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a line of text from the language array
	 *
	 * @param	string	$line	The language line.  Not optional in this version (!)
	 * @return	string
	 */
	public function line($line)
	{
		if (! isset($this->language[$line]))
		{
			log_message('error', 'Could not find the language line "'.$line.'"');

			return 'FIXME ("'.$line.'")';
		}

		return $this->language[$line];
	}
}
// END Language Class

/* End of file MY_Lang.php */
