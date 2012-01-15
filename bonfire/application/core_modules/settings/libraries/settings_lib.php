<?php  defined('BASEPATH') or exit('No direct script access allowed');
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

class Settings_lib
{
	protected $ci;

	/**
	 * Settings cache
	 *
	 * @var	array
	 */
	private static $cache = array();

	/**
	 * The Settings Construct
	 */
	public function __construct()
	{
		
		$this->ci =& get_instance();
		$this->ci->load->model('settings/settings_model');

		$this->find_all();
	}

	/**
	 * Getter
	 *
	 * Gets the setting value requested
	 *
	 * @param	string	$name
	 */
	public function __get($name)
	{
		return self::get($name);
	}

	/**
	 * Setter
	 *
	 * Sets the setting value requested
	 *
	 * @param	string	$name
	 * @param	string	$value
	 * @return	bool
	 */
	public function __set($name, $value)
	{
		return self::set($name, $value);
	}

	/**
	 * Gets a setting.
	 *
	 * @param	string	$name
	 * @return	bool
	 */
	public static function item($name)
	{
		$ci =& get_instance();

		if(isset(self::$cache[$name]))
		{
			return self::$cache[$name];
		}
		
		$setting = $ci->settings_model->find_by('name', $name);

		// Setting doesn't exist, maybe it's a config option
		$value = $setting ? $setting->value : config_item($name);

		// Store it for later
		self::$cache[$name] = $value;

		return $value;
	}

	/**
	 * Set
	 *
	 * Sets a config item
	 * 
	 * @param	string	$name
	 * @param	string	$value
	 * @return	bool
	 */
	public static function set($name, $value)
	{
		$setting = $this->ci->settings_model->update($name, array('value' => $value));

		self::$cache[$name] = $value;

		return TRUE;
	}


	/**
	 * All
	 *
	 * Gets all the settings
	 *
	 * @return	array
	 */
	public function find_all()
	{
		if(self::$cache)
		{
			return self::$cache;
		}

		$settings = $this->ci->settings_model->find_all();

		foreach($settings as $setting)
		{
			self::$cache[$setting->name] = $setting->value;
		}

		return self::$cache;
	}
}

/* End of file Settings.php */