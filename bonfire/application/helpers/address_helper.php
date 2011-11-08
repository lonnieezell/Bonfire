<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

/*
	File: Address Helper
	
	Provides various helper functions when working with address in forms.
*/


/*
	Function: state_select()
	
	Creates a state/provience/county dropdown form input based on the entries in the address.states config.
	The word "state" is used but the structure can be used for Canadian proviences, Irish and UK counties and 
	any other area based data.
	
	Parameters:
		$selected_code	- The value of the item that should be selected when the dropdown is drawn.
		$default_abbr	- The value of the item that should be selected if no other matches are found.
		$country_code	- The code of the country for which the states/priviences/counties are returned. Defaults to 'US'.
		$select_name	- The name assigned to the select. Defaults to 'state_code'.
		$class        - Optional value for class name
				
	Return:
		A string with the full html for the select input. 
*/
if (!function_exists('state_select'))
{

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

			$output = '<select name="'. $select_name . '" id="' . $select_name . '" ' . $class . ' >';
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
	}
	
	//--------------------------------------------------------------------

}


/*
	Function: country_select()
	
	Creates a country-based dropdown form input based on the entries in the address.countries config.
	
	Parameters:
		$selected_iso	- The value of the item that should be selected when the dropdown is drawn.
		$default_iso	- The value of the item that should be selected if no other matches are found.
		$select_name	- The name assigned to the select. Defaults to 'iso'.
		$class        - Optional value for class name

	Returns:
		A string with the full html for the select input.
*/
if (!function_exists('country_select'))
{
	function country_select($selected_iso='', $default_iso='US', $select_name='iso', $class='' )
	{
		// First, grab the states from the config
		$countries = config_item('address.countries');
		
		if (!is_array($countries) OR empty($countries))
		{
			return;
		}
		
		$class  = ( !empty($class) && $class[1] != '' ) ? ' class="' . $class . '" ' : '';		
		
		$output = '<select name="'. $select_name .'" id="' . $select_name . '" ' . $class . ' >';

		foreach ($countries as $country_iso => $country)
		{
			$output .= "<option value='{$country_iso}'";
			$output .= ($country_iso == $selected_iso) ? ' selected="selected"' : '';
			$output .= empty($selected_iso) && ($default_iso == $country_iso) ? ' selected="selected"' : '';
			$output .= ">{$country['printable']}</option>\n";
		}
		$output .= "</select>\n";
		
		return $output;
	}
}