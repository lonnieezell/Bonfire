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
		if ( ! is_array($language_array))
		{
			$output = lang('language_codes_config_error');
		}
		elseif (empty($language_name))
		{
			$output = lang('language_codes_name_error');
		}
		else
		{
			$language_name = strtolower($language_name);
			foreach ($language_array as $code => $language_info)
			{
				if ($language_info['name'] == $language_name)
				{
					$output = $code;
					break;
				}
			}
			if ($country != FALSE) {
				$country_array = config_item('address.countries');
				// instead of returning an error if there's a problem retrieving the countries, just return the existing output
				if (is_array($country_array) && ! empty($country))
				{
					$country = strtoupper($country);
					foreach($country_array as $abbrev => $country_info)
					{
						if ($country_info['name'] == $country)
						{
							$output .= "-" . $abbrev;
							break;
						}
					}
				}
			}
		}
		return $output;
	}
}

if ( ! function_exists('language_select'))
{
	function language_code_select($selected_code='', $default_code='en', $select_name='language_code', $class='', $id='')
	{
		$language_array = config_item('language_codes');

		if ( ! is_array($language_array))
		{
			$output = lang('language_codes_config_error');
		}
		else
		{
			$class = ( ! empty($class) && $class != '') ? ' class="' . $class . '" ' : '';
			$id = ( ! empty($id) && $id != '') ? $id : $select_name;

			$output  = '<select name="' . $select_name . '" id="' . $id . '" ' . $class . ' >' . PHP_EOL;
			$output .= '<option value="">&nbsp;</option>' . PHP_EOL;
			foreach ($language_array as $abbrev => $language_info)
			{
				$output .= "<option value='{$abbrev}'";
				$output .= empty($selected_code) && ($default_code == $abbrev) ? ' selected="selected"' : '';
				$output .= ">{$language_info['printable']}</option>" . PHP_EOL;
			}
			$output .= "</select>" . PHP_EOL;
		}
		return $output;
	}
}
