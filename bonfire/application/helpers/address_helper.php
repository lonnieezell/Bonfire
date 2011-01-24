<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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