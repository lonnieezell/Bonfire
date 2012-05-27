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
 * Language Code Helpers
 *
 * Provides various helper functions for working with language codes
 *
 * @package		Bonfire
 * @subpackage	Helpers
 * @category	Helpers
 * @author		San Diego State University
 */

if ( ! function_exists('find_language_code'))
{
	function find_language_code($language_name='english', $country=FALSE) {
		$language_array = config_item('language_codes');

		if ($country != FALSE) {
			$country_array = config_item('address.countries');
		}
	}
}
