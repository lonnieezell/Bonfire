<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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

/*
	Class: Events Class
	
	Allows you to create hook points throughout the application that any
	other module can tap into without hacking core code.
*/
class Events {

	/*
		Var: $events
		Holds the registered events.
		
		Access:
			Private
	*/
	private static $events;

	//--------------------------------------------------------------------
	
	/*
		Method: __construct()
		
		This if here solely for CI loading to work. Just calls the init( ) method.
		
		Return: 
			void
	*/
	public function __construct()
	{	
		self::init();
	}

	//--------------------------------------------------------------------
	
	/*
		Method: init()
		
		Loads the config/events.php file into memory so we can access it 
		later without the disk load. 
	*/
	public static function init() 
	{
		if (!function_exists('read_config'))
		{
			$ci =& get_instance();
			$ci->load->helper('config_file');
		}
	
		self::$events = read_config('events');
		
		if (self::$events == false)
		{
			self::$events = array();
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: trigger()
		
		Triggers an individual event.
		
		NOTE: The payload sent to the event method is a pointer to the actual data.
		This means that any operations on the data will affect the original data. 
		Use with care.
		
		Parameters:
			$event_name	= A string with the name of the event to trigger. Case sensitive.
			$payload	= (optional) A variable pointer to send to the event method.
			
		Returns:
			void
	*/
	public static function trigger($event_name=null, &$payload=null) 
	{
		if (empty($event_name) || !is_string($event_name))
		{
			return;
		}
		
		if (!array_key_exists($event_name, self::$events))
		{
			return;
		}

		if (!function_exists('module_file_path'))
		{
			$ci =& get_instance();
			$ci->load->helper('application');
		}		
		
		$subscribers = self::$events[$event_name];
		
		foreach ($subscribers as $subscriber)
		{
			$file_path = module_file_path($subscriber['module'], $subscriber['filepath'], $subscriber['filename']);
			
			if (!file_exists($file_path))
			{
				continue;
			}
			
			@include($file_path);
			
			if (!class_exists($subscriber['class']))
			{
				continue;
			}
			
			$class = new $subscriber['class'];
			
			if (!is_callable( array($class, $subscriber['method']) ))
			{
				unset($class);
				continue;
			}
			
			$class->{$subscriber['method']}($payload);
			unset($class);
		}
	}
	
	//--------------------------------------------------------------------
	
}