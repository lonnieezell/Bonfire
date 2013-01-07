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

// ------------------------------------------------------------------------

/**
 * Form Class
 *
 * @package    Bonfire
 * @subpackage Libraries
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/core/unit_test.html
 * @version    3.0
 *
 */
class Form
{

	/**
	 * Stores the CodeIgniter core object.
	 *
	 * @access private
	 * @static
	 *
	 * @var object
	 */
	private static $ci;

	/**
	 * Stores the template that inputs are wrapped in.
	 *
	 * @access private
	 * @static
	 *
	 * @var string
	 */
	private static $template = '<div class="clearfix">
	{label}
	<div class="input {error_class}">
		{input}
		<span class="inline-help">{help}</span>
		<span class="inline-help error">{error}</span>
	</div>
</div>';


	/**
	 * Stores the standard hTML5 inputs.
	 *
	 * @access private
	 * @static
	 *
	 * @var string
	 */
	private static $standard_inputs = array(
		'button', 'checkbox', 'color', 'date', 'datetime', 'datetime-local',
		'email', 'file', 'hidden', 'image', 'month', 'number', 'password',
		'radio', 'range', 'reset', 'search', 'submit', 'tel', 'text', 'time',
		'url', 'week'
	);


	/**
	 * Stores the custom inputs that we provide.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $custom_inputs = array(
		'state'		=> 'state_select',
		'country'	=> 'country_select'
	);

	//--------------------------------------------------------------------

	/**
	 * Constructor calls the init method
	 *
	 * @access public
	 * @uses   init()
	 *
	 * @return void
	 */
	public function __construct()
	{
		self::init();

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Retrieves the CodeIgniter core object
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function init()
	{
		self::$ci =& get_instance();

	}//end init()

	//--------------------------------------------------------------------

	/**
	 * Returns the HTML from the template based on the field passed in
	 *
	 * @access public
	 * @static
	 *
	 * @param string $name       Name of the field
	 * @param array  $properties Field settings
	 *
	 * @return string HTML for the required field
	 */
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

	}//end field()

	//--------------------------------------------------------------------

	/**
	 * Generates a <label> tag.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $value The displayed text of the label.
	 * @param string $for   The tag to be applied to the 'for' part of the tag.
	 *
	 * @return string HTML for the field label
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

	}//end label()

	//--------------------------------------------------------------------

	/**
	 * Generates a generic <input> tag.
	 *
	 * @access public
	 * @static
	 *
	 * @param array $options An array of options to be applied as attributes to the input. $options['type'] is required.
	 *
	 * @return string HTML for the input field
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

	}//end input()

	//--------------------------------------------------------------------

	/**
	 * Generates a <textarea> tag.
	 *
	 * @access public
	 * @static
	 *
	 * @param array $options An array of options to be applied as attributes.
	 *
	 * @return string HTML for the textarea field
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

	}//end textarea()

	//--------------------------------------------------------------------

	/**
	 * Address State field
	 *
	 * @access public
	 * @static
	 *
	 * @param array $options An array of options to be applied as attributes.
	 *
	 * @return string HTML for the State dropdown field
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

		/*
		 * @TODO Is this required?  Is this file even used anymore?
		 */
		print_r($options);

		return $input;
	}//end state()

	//--------------------------------------------------------------------

	/**
	 * Prepares the value for display in the form.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $value The value to prepare.
	 *
	 * @return string
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

	/**
	 * Takes an array of attributes and turns it into a string for an input.
	 *
	 * @access private
	 * @static
	 *
	 * @param array $attr Attributes for a field
	 *
	 * @return string
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

	}//end attr_to_string()

	//--------------------------------------------------------------------

}//end class