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
 * Address helper functions.
 *
 * Provides various helper functions when working with address(es) in forms.
 *
 * @package    Bonfire\Helpers\address_helper
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/developer
 */

if ( ! function_exists('country_select')) {
	/**
	 * Create a country-based form dropdown based on the entries in the
	 * address.countries config.
	 *
	 * @param string $selectedIso The value of the item that should be selected
	 * when the dropdown is rendered.
	 * @param string $defaultIso  The value of the item that should be selected
	 * if no other matches are found.
	 * @param string $selectName  The name assigned to the select element.
	 * Defaults to 'iso'.
	 * @param string $classValue       Optional value for class name.
	 *
	 * @return string The full html for the select input.
	 */
	function country_select($selectedIso = '', $defaultIso = 'US', $selectName = 'iso', $classValue = '')
	{
		// Grab the countries from the config
		$countries = config_item('address.countries');
		if ( ! is_array($countries) || empty($countries)) {
			return lang('us_no_countries');
		}

        // If $selectedIso is empty, set the selection to $defaultIso
        if (empty($selectedIso)) {
            $selectedIso = $defaultIso;
        }

        // Setup the opening select tag
        $output = "<select name='{$selectName}' id='{$selectName}'";
        if ( ! empty($classValue) && is_string($classValue)) {
            $output .= " class='{$classValue}'";
        }
		$output .= ">\n";

        // Add the option elements
        $output .= "<option value=''>&nbsp;</option>\n";
		foreach ($countries as $countryIso => $country) {
			$output .= "<option value='{$countryIso}'";
			if ($countryIso == $selectedIso) {
                $output .= " selected='selected'";
            }
			$output .= ">{$country['printable']}</option>\n";
		}

        // Close the select element and return.
		$output .= "</select>\n";
		return $output;
	}
}

if ( ! function_exists('state_select')) {
	/**
	 * Creates a state/provience/county form dropdown based on the entries in
	 * the address.states config. The word "state" is used but the structure can
	 * be used for Canadian provinces, Irish and UK counties, and any other area
	 * based data.
	 *
	 * @param string $selectedCode The value of the item that should be selected when the dropdown is drawn.
	 * @param string $defaultCode The value of the item that should be selected if no other matches are found.
	 * @param string $countryCode The code of the country for which the states/priviences/counties are returned. Defaults to 'US'.
	 * @param string $selectName The name assigned to the select. Defaults to 'state_code'.
	 * @param string $classValue Optional value for class name.
	 *
	 * @return string The full html for the select input.
	 */
	function state_select($selectedCode = '', $defaultCode = '', $countryCode = 'US', $selectName = 'state_code', $classValue = '')
	{
		// Grab the states from the config
		$allStates = config_item('address.states');
		if ( ! is_array($allStates) || empty($allStates[$countryCode])) {
			return lang('us_no_states');
		}

        // Get the states for the selected country
        $states = $allStates[$countryCode];

        // If $selectedCode is empty, set it to $defaultCode
        if (empty($selectedCode)) {
            $selectedCode = $defaultCode;
        }

        // Setup the opening select tag
        $output = "<select name='{$selectName}' id='{$selectName}'";
        if (is_string($classValue) && ! empty($classValue)) {
            $output .= " class='{$classValue}'";
        }
        $output .= ">\n";

        // Add the option elements
        $output .= "<option value=''>&nbsp;</option>\n";
        foreach ($states as $abbrev => $name) {
            $output .= "<option value='{$abbrev}'";
            if ($abbrev == $selectedCode) {
                $output .= " selected='selected'";
            }
            $output .= ">{$name}</option>\n";
        }

        // Close the select element and return.
        $output .= "</select>\n";
		return $output;
	}
}
/* End /helpers/address_helper.php */