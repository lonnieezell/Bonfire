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

class MY_Lang extends CI_Lang
{
	public function load($langfile, $lang = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '', $_module = '')
	{
		$success = TRUE;

		// Fallback to english (rather than the dumb keys) for missing translations
		// Bonfire used to do this in the translate editor,
		// but that has obvious maintenance problems.
		if ($lang != 'english')
		{
			$success &= parent::load($langfile, $lang, $return, $add_suffix, $alt_path);
		}

		$success &= parent::load($langfile, $lang, $return, $add_suffix, $alt_path);

		return $success;
	}
}