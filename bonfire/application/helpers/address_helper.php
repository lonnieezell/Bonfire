<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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
