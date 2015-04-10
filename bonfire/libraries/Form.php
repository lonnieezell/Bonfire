<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
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
 * Form Library
 *
 * @package    Bonfire\Libraries\Form
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs
 */
class Form
{
	/**
     * @var string The template which wraps form inputs
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
     * @var string[] The standard HTML5 inputs.
	 */
	private static $standard_inputs = array(
		'button', 'checkbox', 'color', 'date', 'datetime', 'datetime-local',
		'email', 'file', 'hidden', 'image', 'month', 'number', 'password',
		'radio', 'range', 'reset', 'search', 'submit', 'tel', 'text', 'time',
		'url', 'week'
	);

	/**
     * @var string[] The custom inputs provided by this library
	 */
	private static $custom_inputs = array(
		'state'		=> 'state_select',
		'country'	=> 'country_select'
	);

	//--------------------------------------------------------------------

	/**
     * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
    }

	/**
     * Return the HTML from the template based on the field passed in
	 *
	 * @param string $name       Name of the field
	 * @param array  $properties Field settings
	 *
	 * @return string HTML for the required field
	 */
    public static function field($name, $properties = array())
		{
        if (! isset($properties['name'])) {
			$properties['name'] = $name;
		}

		$error_class = '';
		$error = '';
		$help = '';
        $input = '';

        if (isset($properties['help'])) {
			$help = $properties['help'];
			unset($properties['help']);
		}

        switch ($properties['type']) {
			case 'hidden':
				break;

			case 'radio':
                // no break;
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
        $label = self::label($properties['label']);

        return str_replace(
            array('{label}', '{input}', '{help}', '{error_class}', '{error}'),
            array($label, $input, $help, $error_class, $error),
            self::$template
        );
    }

	/**
     * Generate a <label> tag.
	 *
	 * @param string $value The displayed text of the label.
	 * @param string $for   The tag to be applied to the 'for' part of the tag.
	 *
	 * @return string HTML for the field label
	 */
    public static function label($value, $for = null)
		{
        if ($for === null) {
            return "<label>{$value}</label>";
		}

        return "<label for='{$for}'>{$value}</label>";
		}

	/**
     * Generate a generic <input> tag.
	 *
     * @param array $options An array of options to be applied as attributes to
     * the input. $options['type'] is required.
	 *
	 * @return string HTML for the input field
	 */
	public static function input($options)
	{
        if (! isset($options['type'])) {
			logit('You must specify a type for the input.');
        } elseif (! in_array($options['type'], self::$standard_inputs)) {
			logit(sprintf('"%s" is not a valid input type.', $options['type']));
		}

        return '<input ' . self::attributesToString($options) . ' />';
    }

	/**
     * Generate a <textarea> tag.
	 *
	 * @param array $options An array of options to be applied as attributes.
	 *
	 * @return string HTML for the textarea field
	 */
	public static function textarea($options)
	{
		$value = '';
        if (isset($options['value'])) {
			$value = $options['value'];
			unset($options['value']);
		}

        return '<textarea ' . self::attributesToString($options) . '>' .
            self::prepValue($value) . '</textarea>';
    }

	/**
	 * Address State field
	 *
	 * @param array $options An array of options to be applied as attributes.
	 *
	 * @return string HTML for the State dropdown field
	 */
	public static function state($options)
	{
        return self::stateSelect(
            isset($options['value']) ? $options['value'] : '',
            isset($options['default']) ? $options['default'] : '',
            'US',
            isset($options['name']) ? $options['name'] : '',
            isset($options['class']) ? $options['class'] : ''
        );
		}

	/**
     * Prepare the value for display in the form.
	 *
	 * @param string $value The value to prepare.
	 *
	 * @return string
	 */
    public static function prepValue($value)
	{
		$value = htmlspecialchars($value);

        return str_replace(array("'", '"'), array("&#39;", "&quot;"), $value);
    }

    //--------------------------------------------------------------------------
    // Protected Methods
    //--------------------------------------------------------------------------

    protected static function stateSelect($selected, $default, $country, $name, $class)
    {
        if (! function_exists('state_select')) {
            get_instance()->load->helper('address');
	}

        return state_select($selected, $default, $country, $name, $class);
    }

    //--------------------------------------------------------------------------
	// !PRIVATE METHODS
    //--------------------------------------------------------------------------

	/**
     * Turn an array of attributes into a string for an input.
	 *
	 * @param array $attr Attributes for a field
	 *
	 * @return string
	 */
    private static function attributesToString($attr)
		{
        if (! is_array($attr)) {
			$attr = (array) $attr;
		}

        $attributes = array();
        foreach ($attr as $property => $value) {
            if ($property == 'label') {
				continue;
			}

            if ($property == 'value') {
                $value = self::prepValue($value);
			}

            $attributes[] = "{$property}='{$value}'";
		}

        return implode(' ', $attributes);
    }

    //--------------------------------------------------------------------------
    // Deprecated Methods (do not use)
    //--------------------------------------------------------------------------

    /**
     * Prepare the value for display in the form.
     *
     * @deprecated since 7.1. Use prepValue()
     *
     * @param string $value The value to prepare.
     *
     * @return string
     */
    public static function prep_value($value)
    {
        return self::prepValue($value);
    }
}
/* end /libraries/form.php */
