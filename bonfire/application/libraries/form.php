<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Form {

	/*
		Var: $ci
		Stores the global CI object.
	*/
	private static $ci;
	
	/*
		Var: $template
		Stores the template that inputs are wrapped in.
	*/
	private static $template = '<div class="clearfix">
	{label}
	<div class="input {error_class}">
		{input}
		<span class="inline-help">{help}</span>
		<span class="inline-help error">{error}</span>
	</div>
</div>';

	/*
		Var: $standard_inputs
		Stores the standard hTML5 inputs.
	*/
	private static $standard_inputs = array(
		'button', 'checkbox', 'color', 'date', 'datetime', 'datetime-local',
		'email', 'file', 'hidden', 'image', 'month', 'number', 'password', 
		'radio', 'range', 'reset', 'search', 'submit', 'tel', 'text', 'time',
		'url', 'week'
	);
	
	/*
		Var: $custom_inputs
		Stores the custom inputs that we provide.
	*/
	private static $custom_inputs = array(
		'state'		=> 'state_select',
		'country'	=> 'country_select'
	);
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		self::init();
	}
	
	//--------------------------------------------------------------------
	
	public static function init()
	{
		self::$ci =& get_instance();
	}
	
	//--------------------------------------------------------------------
	
	public static function field($name, $properties=array())
	{	
		if (!isset($properties['name']))
		{
			$properties['name'] = $name;
		}

		$input	= '';
		$error_class = '';
		$error = '';
		$help = '';
		
		if (isset($properties['help']))
		{
			$help = $properties['help'];
			unset($properties['help']);
		}
		
		switch ($properties['type'])
		{
			case 'hidden':
				break;
			case 'radio':
			case 'checkbox':
				break;
			case 'select':
				break;
			case 'textarea':
				break;
			case 'state':
				$input = self::state($properties);
				break;
			default:
				$input = self::input($properties);
				break;
		}
		
		$return = str_replace('{label}', self::label($properties['label']), self::$template);
		$return = str_replace('{input}', $input, $return);
		$return = str_replace('{help}', $help, $return);
		$return = str_replace('{error_class}', $error_class, $return);
		$return = str_replace('{error}', $error, $return);
		
		return $return;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: label()
		
		Generates a <label> tag.
		
		Parameters:
			$value	- The displayed text of the label.
			$for	- the tag to be applied to the 'for' part of the tag.
	*/
	public static function label($value, $for = NULL)
	{
		if ($for === NULL)
		{
			return '<label>' . $value . '</label>';
		}
		else
		{
			return '<label for="' . $for . '">' . $value . '</label>';
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: input()
		
		Generates a generic <input> tag.
		
		Parameters:
			$options	- An array of options to be applied as attributes to the input.
						  $options['type'] is required.
	*/
	public static function input($options)
	{
		if (!isset($options['type']))
		{
			logit('You must specify a type for the input.');
		}
		else if (!in_array($options['type'], self::$standard_inputs))
		{
			logit(sprintf('"%s" is not a valid input type.', $options['type']));
		}
		
		$input = '<input '. self::attr_to_string($options) .' />';
		
		return $input;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: textarea()
		
		Generates a <textarea> tag.
		
		Parameters:
			$options	- an array of options to be applied as attributes.
	*/
	public static function textarea($options)
	{
		$value = '';
		if (isset($options['value']))
		{
			$value = $options['value'];
			unset($options['value']);
		}
		$input = "<textarea " . self::attr_to_string($options) . '>';
		$input .= self::prep_value($value);
		$input .= '</textarea>';

		return $input;
	}
	
	//--------------------------------------------------------------------
	
	/*
		
	*/
	public static function state($options)
	{	
		if (!function_exists('state_select'))
		{
			self::$ci->load->helper('address');
		}
		
		$selected	= isset($options['value']) ? $options['value'] : '';
		$default	= isset($options['default']) ? $options['default'] : '';
		$country	= 'US';
		$name		= isset($options['name']) ? $options['name'] : '';
		$class		= isset($options['class']) ? $options['class'] : '';
		
		$input = state_select($selected, $default, $country, $name, $class);
		
		print_r($options);
		
		return $input;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: prep_value()
		
		Prepares the value for display in the form.
		
		Parameters:
			$value	- The value to prepare.
			
		Returns: 
			The prepared value.
	*/
	public static function prep_value($value)
	{
		$value = htmlspecialchars($value);
		$value = str_replace(array("'", '"'), array("&#39;", "&quot;"), $value);

		return $value;
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	/*
		Method: attr_to_string()
		
		Takes an array of attributes and turns it into a string for an input.
	*/
	private static function attr_to_string($attr)
	{
		$attr_str = '';

		if ( ! is_array($attr))
		{
			$attr = (array) $attr;
		}

		foreach ($attr as $property => $value)
		{
			if ($property == 'label')
			{
				continue;
			}
			if ($property == 'value')
			{
				$value = self::prep_value($value);
			}
			$attr_str .= $property . '="' . $value . '" ';
		}

		// We strip off the last space for return
		return substr($attr_str, 0, -1);
	}
	
	//--------------------------------------------------------------------
	
}