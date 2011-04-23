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
	
	Creates a US-based state dropdown form input.
	
	Parameters:
		$selected_id	- The value of the item that should be selected when the dropdown is drawn.
		$default_abbr	- The value of the item that should be selected if no other matches are found.
		
	Return:
		A string withe the full html for the select input. 
*/
if (!function_exists('state_select'))
{

	function state_select($selected_id=0, $default_abbr='')
	{
		$ci =& get_instance();
	
		// First, grab the states
		$query = $ci->db->get('states');
		
		if (!$query || $query->num_rows() == 0)
		{
			return;
		}
		
		$states = $query->result();
		
		$output = '<select name="state_id">';
		foreach ($states as $state)
		{
			$output .= "<option value='{$state->id}'";
			$output .= ($state->id == $selected_id) ? ' selected="selected"' : '';
			$output .= ($state->id != $selected_id) && ($default_abbr == $state->abbrev) ? ' selected="selected"' : '';
			$output .= ">{$state->name}</option>\n";
		}
		$output .= "</select>\n";
		
		return $output;
	}
	
	//--------------------------------------------------------------------

}
