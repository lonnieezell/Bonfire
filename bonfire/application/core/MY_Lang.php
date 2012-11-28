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

class MY_Lang extends MX_Lang
{
	public function load($langfile, $lang = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '', $_module = '')
	{
		if (is_array($langfile))
		{
			foreach ($langfile as $file)
			{
				$this->load($file);
			}
			return $this->language;
		}

		if ($lang == '')
		{
			$lang = CI::$APP->config->item('language');
		}

		if (in_array($langfile, $this->is_loaded, TRUE))
		{
			return $this->language;
		}

		// Fallback to english (rather than the dumb keys) for missing translations
		// Bonfire used to do this in the translate editor,
		// but that has obvious maintenance problems.
		$result = array();

		if ($lang != 'english')
		{
			// We need to use $return = TRUE so we can load the same $langfile twice,
			// but with different languages
			$result = parent::load($langfile, 'english', TRUE, $add_suffix, $alt_path);
		}
		$result = array_merge($result, parent::load($langfile, $lang, TRUE, $add_suffix, $alt_path));

		if (!$return)
		{
			$this->language = array_merge($this->language, $result);
			$this->is_loaded[] = $langfile;
		}
		return $result;
	}
}
