<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	File: MY_form_helper
	
	Creates HTML5 extensions for the standard CodeIgniter form helper.
	
	These functions also wrap the form elements as necessary to create
	the styling that the Bootstrap-inspired admin theme requires to
	make it as simple as possible for a developer to maintain styling
	with the core. Also makes changing the core a snap.
	
	All methods (including overriden versions of the originals) now
	support passing a final 'label' attribute that will create the
	label along with the field.
*/

/*
	Function: _form_common()
	
	Used by many of the new functions to wrap the input in the correct
	tags so that the styling is automatic.
	
	Parameters:
		$type	- A string with the name of the element type.
		$data	- Either a string with the element name, or an array of 
				  key/value pairs of all attributes.
		$value	- Either a string with the value, or blank if an array is
				  is passed to the $data param.
		$label	- A string with the label of the element.
		$extra	- A string with any additional items to include, like Javascript.
		
	Returns:
		A string with the formatted input element, label tag and wrapping divs.
*/
if (!function_exists('_form_common'))
{
	function _form_common($type='text', $data='', $value='', $label='', $extra='')
	{
		$defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
		
		// If name is empty at this point, try to grab it from the $data array
		if (empty($defaults['name']) && is_array($data) && isset($data['name']))
		{
			$defaults['name'] = $data['name'];
			unset($data['name']);
		}
		
		$output  = "<div class='clearfix'>\n";
		$output .= "<label for='{$defaults['name']}'>$label</label>\n";
		$output .= "<div class='input'>\n";
		$output .= '<input '. _parse_form_attributes($data, $defaults) . $extra ." />\n";
		$output .= "</div>\n</div>";
		
		return $output;
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_input'))
{
	function form_input($data='', $value='', $label='', $extra='')
	{
		return _form_common('text', $data, $value, $label, $extra);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_email'))
{
	function form_email($data='', $value='', $label='', $extra='')
	{
		return _form_common('email', $data, $value, $label, $extra);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_password'))
{
	function form_password($data='', $value='', $label='', $extra='')
	{
		return _form_common('password', $data, $value, $label, $extra);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_url'))
{
	function form_url($data='', $value='', $label='', $extra='')
	{
		return _form_common('url', $data, $value, $label, $extra);
	}
}

//--------------------------------------------------------------------