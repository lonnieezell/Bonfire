<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright © 2011 Lonnie Ezell

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
	Class: Meta
	
	Provides a simple interface for working with custom fields (meta information)
	in your forms.
*/
class Meta {

	/*
		Var: $ci
		Stores the global CI instance.
	*/
	private static $ci;
	
	/*
		Var: $fields
		Stores the field info for the current module.
	*/
	private static $fields;
	
	/*
		Var: $field_list
		Stores a list of fields to be rendered.
	*/
	private static $field_list;
	
	/*
		Var: $error
		Stores the last error encountered.
	*/
	private static $error;
	
	/*
		Var: $module
		Stores the name of the module we're currently working with.
	*/
	private static $module;

	//--------------------------------------------------------------------

	/*
		Method::__construct()
		
		Simply calls the static init() method. Here to make loading the 
		class via CodeIgniter standard affair. Even if the class is used
		statically after that.
	*/
	public function __construct()
	{
		self::init();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: init()
		
		Handles any static initialization tasks.
	*/
	public static function init()
	{
		self::$ci =& get_instance();
		
		if (!class_exists('Meta_model'))
		{
			self::$ci->load->model('meta/meta_model');
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: render_fields()
		
		Renders out the custom fields into a HTML5 form. Does not create
		the form information, just the form inputs and wrappers needed for
		the module's custom fields.
		
		Parameters:
			$module		- The name of the module.
			$field_list	- An array of field id's or names of the fields to render.
						  If this is not provided, all existing fields for that
						  module will be displayed.
						  
		Returns: 
			A string with the HTML for the fields, or FALSE.
	*/
	public static function render_fields($module=null, $fkey_value=null, $field_list=null)
	{
		if (empty($module))
		{
			self::$error = 'No module name given.';
			return false;
		}
		
		if (!class_exists('Form'))
		{
			$this->load->library('form');
		}
		
		$out = '';
		
		self::$module = $module;
		
		self::get_fields();
		
		self::get_field_meta($fkey_value);
		
		foreach (self::$fields as $field_name => $field)
		{
			$options = array(
				'name'	=> $field->name,
				'type'	=> $field->type,
				'label'	=> $field->label,
				'help'	=> $field->desc,
				'default'	=> $field->default,
				'option'	=> $field->options,
				'placeholder'	=> $field->placeholder
			);
			if ($field->required)
			{
				$options['required'] = 'required';
			}
		
			$out .= Form::field($field_name, $options);
		}
		
		return $out;
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	/*
		Method: get_fields()
		
		Retrieves all the fields for this module in the database.
	*/
	private static function get_fields()
	{
		// Don't load them more than once for performance sake.
		if (is_array(self::$fields) && count(self::$fields))
		{
			return;
		}
		
		// Otherwise, we need to load them from the database.
		$fields = self::$ci->meta_model->find_all_fields(self::$module);
		
		foreach ($fields as $field)
		{
			self::$fields[$field->name] = $field;
		}
		unset($fields);
	}
	
	//--------------------------------------------------------------------
	
	/*
		Retrieves the meta information for the current module
	*/
	private static function get_field_meta($fkey_value=null)
	{ 
		if (!is_numeric($fkey_value))
		{
			self::$error = 'No foreign_key value provided.';
			return false;
		}
		
		$meta = self::$ci->meta_model->find_all_for($fkey_value, self::$module);
		
		dump($meta);
	}
	
	//--------------------------------------------------------------------
	
}