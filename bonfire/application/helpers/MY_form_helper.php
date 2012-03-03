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
		$tooltip - A string for inline help or a tooltip icon

	Returns:
		A string with the formatted input element, label tag and wrapping divs.
*/
if (!function_exists('_form_common'))
{
	function _form_common($type='text', $data='', $value='', $label='', $extra='', $tooltip = '')
	{
		$defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

		// If name is empty at this point, try to grab it from the $data array
		if (empty($defaults['name']) && is_array($data) && isset($data['name']))
		{
			$defaults['name'] = $data['name'];
			unset($data['name']);
		}
		$output = _parse_form_attributes($data, $defaults);
		$output = <<<EOL

	<div class="control-group">
		<label class="control-label" for="{$defaults['name']}">{$label}</label>
		<div class="controls">
			 <input {$output} {$extra} />
			{$tooltip}
		</div>
	</div>

EOL;

		return $output;
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_input'))
{
	function form_input($data='', $value='', $label='', $extra='', $tooltip = '' )
	{
		return _form_common('text', $data, $value, $label, $extra);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_email'))
{
	function form_email($data='', $value='', $label='', $extra='', $tooltip = '' )
	{
		return _form_common('email', $data, $value, $label, $extra);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_password'))
{
	function form_password($data='', $value='', $label='', $extra='', $tooltip = '' )
	{
		return _form_common('password', $data, $value, $label, $extra);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_url'))
{
	function form_url($data='', $value='', $label='', $extra='', $tooltip = '' )
	{
		return _form_common('url', $data, $value, $label, $extra);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_telephone'))
{
		function form_telephone($data='', $value='', $label='', $extra='', $tooltip = '' )
		{
				return _form_common('tel', $data, $value, $label, $extra);
		}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_number'))
{
		function form_number($data='', $value='', $label='', $extra='', $tooltip = '' )
		{
				return _form_common('number', $data, $value, $label, $extra);
		}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_color'))
{
		function form_color($data='', $value='', $label='', $extra='', $tooltip = '' )
		{
				return _form_common('color', $data, $value, $label, $extra);
		}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_search'))
{
		function form_search($data='', $value='', $label='', $extra='', $tooltip = '' )
		{
				return _form_common('search', $data, $value, $label, $extra);
		}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_date'))
{
		function form_search($data='', $value='', $label='', $extra='', $tooltip = '' )
		{
				return _form_common('date', $data, $value, $label, $extra);
		}
}



