<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

//--------------------------------------------------------------------

/**
 * Form Helpers
 *
 * Creates HTML5 extensions for the standard CodeIgniter form helper.
 *
 * These functions also wrap the form elements as necessary to create
 * the styling that the Bootstrap-inspired admin theme requires to
 * make it as simple as possible for a developer to maintain styling
 * with the core. Also makes changing the core a snap.
 *
 * All methods (including overridden versions of the originals) now
 * support passing a final 'label' attribute that will create the
 * label along with the field.
 *
 * @package    Bonfire
 * @subpackage Helpers
 * @category   Helpers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/array_helpers.html
 *
 */

if ( ! function_exists('_form_common'))
{
	/**
	 * Used by many of the new functions to wrap the input in the correct
	 * tags so that the styling is automatic.
	 *
	 * @access private
	 *
	 * @param string $type    A string with the name of the element type.
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function _form_common($type='text', $data='', $value='', $label='', $extra='', $tooltip = '')
	{
		$defaults = array('type' => $type, 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

		// If name is empty at this point, try to grab it from the $data array
		if (empty($defaults['name']) && is_array($data) && isset($data['name']))
		{
			$defaults['name'] = $data['name'];
			unset($data['name']);
		}

		// If label is empty at this point, try to grab it from the $data array
		if (empty($label) && is_array($data) && isset($data['label']))
		{
			$label = $data['label'];
			unset($data['label']);
		}

		// If tooltip is empty at this point, try to grab it from the $data array
		if (empty($tooltip) && is_array($data) && isset($data['tooltip']))
		{
			$tooltip = $data['tooltip'];
			unset($data['tooltip']);
		}

		$error = '';

		if (function_exists('form_error'))
		{
			if (form_error($defaults['name']))
			{
				$error   = ' error';
				$tooltip = '<span class="help-inline">' . form_error($defaults['name']) . '</span>' . PHP_EOL;
			}
		}

		$output = _parse_form_attributes($data, $defaults);

		$output = <<<EOL

<div class="control-group {$error}">
	<label class="control-label" for="{$defaults['name']}">{$label}</label>
	<div class="controls">
		 <input {$output} {$extra} />
		{$tooltip}
	</div>
</div>

EOL;

		return $output;

	}//end _form_common()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_input'))
{
	/**
	 * Returns a properly templated text input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_input($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('text', $data, $value, $label, $extra, $tooltip);

	}//end form_input()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_email'))
{
	/**
	 * Returns a properly templated email input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_email($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('email', $data, $value, $label, $extra, $tooltip);

	}//end form_email()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_password'))
{
	/**
	 * Returns a properly templated password input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_password($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('password', $data, $value, $label, $extra, $tooltip);

	}//end form_password()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_url'))
{
	/**
	 * Returns a properly templated URL input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_url($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('url', $data, $value, $label, $extra, $tooltip);

	}//end form_url()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_telephone'))
{
	/**
	 * Returns a properly templated Telephone input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_telephone($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('tel', $data, $value, $label, $extra, $tooltip);

	}//end form_telephone()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_number'))
{
	/**
	 * Returns a properly templated number input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_number($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('number', $data, $value, $label, $extra, $tooltip);

	}//end form_number()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_range'))
{
	/**
	 * Returns a properly templated range input field.
	 *
	 * NOTE: The $data value should be an array and should contain both
	 * 'min' and 'max' values. If they are not present, then they will
	 * default to 1 and 10, respectively.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_range($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		if (is_string($data))
		{
			$data = (array)$data;
		}

		$data['min'] = isset($opts['min']) ? $opts['min'] : 1;
		$data['max'] = isset($opts['max']) ? $opts['max'] : 10;

		return _form_common('range', $data, $value, $label, $extra, $tooltip);

	}//end form_color()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_color'))
{
	/**
	 * Returns a properly templated color input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_color($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('color', $data, $value, $label, $extra, $tooltip);

	}//end form_color()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_search'))
{
	/**
	 * Returns a properly templated search input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_search($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('search', $data, $value, $label, $extra, $tooltip);

	}//end form_search()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_date'))
{
	/**
	 * Returns a properly templated date input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_date($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('date', $data, $value, $label, $extra, $tooltip);

	}//end form_date()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_datetime'))
{
	/**
	 * Returns a properly templated date input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_datetime($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('datetime', $data, $value, $label, $extra, $tooltip);

	}//end form_date()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_month'))
{
	/**
	 * Returns a properly templated month input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_month($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('month', $data, $value, $label, $extra, $tooltip);

	}//end form_date()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_dropdown'))
{
	/**
	 * Returns a properly templated dropdown field.
	 *
	 * @param string $data     Either a string with the element name, or an array of key/value pairs of all attributes, which must include a name or id.
	 * @param array  $options  Array of options for the drop down list
	 * @param string $selected Either a string of the selected item or an array of selected items
	 * @param string $label    A string with the label of the element.
	 * @param string $extra    A string with any additional items to include, like Javascript.
	 * @param string $tooltip  A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_dropdown($data, $options=array(), $selected=array(), $label='', $extra='', $tooltip = '')
	{
		if (! is_array($data))
		{
			$data = array('name' => $data);
		}

		if (! isset($data['id']))
		{
			$data['id'] = $data['name'];
		}

		$output = _parse_form_attributes($data, array());

		if ( ! is_array($selected))
		{
			$selected = array($selected);
		}

		// If no selected state was submitted we will attempt to set it automatically
		if (count($selected) === 0)
		{
			// If the form name appears in the $_POST array we have a winner!
			if (isset($_POST[$data['name']]))
			{
				$selected = array($_POST[$data['name']]);
			}
		}

		$options_vals = '';
		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val) && ! empty($val))
			{
				$options_vals .= '<optgroup label="'.$key.'">'.PHP_EOL;

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';

					$options_vals .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
				}

				$options_vals .= '</optgroup>'.PHP_EOL;
			}
			else
			{
				$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';

				$options_vals .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
			}
		}

		$error = '';

		if (function_exists('form_error'))
		{
			if (form_error($data['name']))
			{
				$error   = ' error';
				$tooltip = '<span class="help-inline">' . form_error($data['name']) . '</span>' . PHP_EOL;
			}
		}

		$output = <<<EOL

<div class="control-group {$error}">
	<label class="control-label" for="{$data['id']}">{$label}</label>
	<div class="controls">
		 <select {$output} {$extra}>
			{$options_vals}
		</select>
		{$tooltip}
	</div>
</div>

EOL;

		return $output;

	}//end form_dropdown()
}

if ( ! function_exists('form_multiselect'))
{
	/**
	 * Multi-select menu
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	mixed
	 * @param	string
	 * @return	type
	 */
	function form_multiselect($name = '', $options = array(), $selected = array(), $label = '', $extra = '')
	{
		if ( ! strpos($extra, 'multiple'))
		{
			$extra .= ' multiple="multiple"';
		}

		return form_dropdown($name, $options, $selected, $label, $extra);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_has_error'))
{
	/**
	 * Check if the form has an error
	 *
	 * DEPRECATED as of 0.7.1 since form_error() in the main
	 * form library does the same thing. Not sure how this got it, actually...
	 *
	 * @access public
	 *
	 * @param string $field Name of the field
	 *
	 * @return bool
	 */
	function form_has_error($field=null)
	{

		if (FALSE === ($OBJ =& _get_validation_object()))
		{
			return FALSE;
		}

		$return = $OBJ->has_error($field);

		return $return;
	}//end form_has_error()

}
//--------------------------------------------------------------------
