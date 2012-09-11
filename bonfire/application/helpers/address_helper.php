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
 * Address Helpers
 *
 * Provides various helper functions when working with address in forms.
 *
 * @package    Bonfire
 * @subpackage Helpers
 * @category   Helpers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/address_helpers.html
 *
 */

if ( ! function_exists('state_select'))
{
	/**
	 * Creates a state/provience/county dropdown form input based on the entries in the address.states config.
	 * The word "state" is used but the structure can be used for Canadian proviences, Irish and UK counties and
	 * any other area based data.
	 *
	 * @param $selected_code string The value of the item that should be selected when the dropdown is drawn.
	 * @param $default_abbr string The value of the item that should be selected if no other matches are found.
	 * @param $country_code string The code of the country for which the states/priviences/counties are returned. Defaults to 'US'.
	 * @param $select_name string The name assigned to the select. Defaults to 'state_code'.
	 * @param $class string Optional value for class name.
	 *
	 * @return string The full html for the select input.
	 */
	function state_select($selected_code='', $default_abbr='', $country_code='US', $select_name='state_code', $class='')
	{
		// First, grab the states from the config
		$all_states = config_item('address.states');

		if (!is_array($all_states) OR empty($all_states[$country_code]))
		{
			$output = lang('us_no_states');
		}
		else
		{

			// Get the states for the selected country
			$states = $all_states[$country_code];
			$class  = ( !empty($class) && $class != '' ) ? ' class="' . $class . '" ' : '';

			$output  = '<select name="'. $select_name . '" id="' . $select_name . '" ' . $class . ' >' . PHP_EOL;
			$output .= '<option value="">&nbsp;</option>' . PHP_EOL;
			foreach ($states as $abbrev => $name)
			{
				$output .= "<option value='{$abbrev}'";
				$output .= ($abbrev == $selected_code) ? ' selected="selected"' : '';
				$output .= empty($selected_code) && ($default_abbr == $abbrev) ? ' selected="selected"' : '';
				$output .= ">{$name}</option>\n";
			}
			$output .= "</select>\n";
		}

		return $output;

	}//end state_select()
}

if ( ! function_exists('country_select'))
{
	/**
	 * Creates a country-based dropdown form input based on the entries in the address.countries config.
	 *
	 * @param $selected_iso string The value of the item that should be selected when the dropdown is drawn.
	 * @param $default_iso string The value of the item that should be selected if no other matches are found.
	 * @param $select_name string The name assigned to the select. Defaults to 'iso'.
	 * @param $class string Optional value for class name.
	 *
	 * @return string The full html for the select input.
	 */
	function country_select($selected_iso='', $default_iso='US', $select_name='iso', $class='' )
	{
		// First, grab the states from the config
		$countries = config_item('address.countries');

		if (!is_array($countries) OR empty($countries))
		{
			return;
		}

		$class  = ( !empty($class) && $class[1] != '' ) ? ' class="' . $class . '" ' : '';
		
		$output  = '<select name="'. $select_name .'" id="' . $select_name . '" ' . $class . ' >';
		$output .= '<option value="">&nbsp;</option>' . PHP_EOL;
		foreach ($countries as $country_iso => $country)
		{
			$output .= "<option value='{$country_iso}'";
			$output .= ($country_iso == $selected_iso) ? ' selected="selected"' : '';
			$output .= empty($selected_iso) && ($default_iso == $country_iso) ? ' selected="selected"' : '';
			$output .= ">{$country['printable']}</option>\n";
		}
		$output .= "</select>\n";

		return $output;

	}//end country_select()
}
