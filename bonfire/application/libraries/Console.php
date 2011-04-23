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
	Class: Console
	
	Provides several additional logging features designed to work 
	with the Forensics Profiler.
	
	Inspired by ParticleTree's PHPQuickProfiler. (http://particletree.com)
	
	Package: 
		Forensics
		
	Author: 
		Lonnie Ezell (http://lonnieezell.com)
		
	License:
		MIT 
*/
class Console {

	/*
		Var: $logs
		Contains all of the logs that are collected.
	*/
	private static $logs = array(
		'console'		=> array(),
		'log_count'		=> 0,
		'memory_count'	=> 0,
	);

	/*	
		Var: $ci
		An instance of the CI super object.
	 */
	private static $ci;

	//--------------------------------------------------------------------
	
	/*
		Method: __construct()
		
		This constructor is here purely for CI's benefit, as this is a
		static class.
		
		Return:
			void
	 */
	public function __construct() 
	{			
		self::init();
		
		log_message('debug', 'Forensics Console library loaded');
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: init()
		
		Grabs an instance of CI and gets things ready to run.
	*/
	public static function init() 
	{
		self::$ci =& get_instance();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: log()
		
		Logs a variable to the console. 
		
		Parameters:
			$data	- The variable to log.
	*/
	public static function log($data=null) 
	{
		if (empty($data)) 
		{ 
			return false; 
		}
		
		$log_item = array(
			'data' => $data,
			'type' => 'log'
		);
		
		self::add_to_console('log_count', $log_item);
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: log_memory()
		
		Logs the memory usage a single variable, or the entire script.
		
		Parameters:
			$object	- The object to store the memory usage of.
			$name	- The name to be displayed in the console.
	*/
	public static function log_memory($object=false, $name='PHP') 
	{
		$memory = memory_get_usage();
		
		if ($object) 
		{
			$memory = strlen(serialize($object));
		}
		
		$log_item = array(
			'data' => $memory,
			'type' => 'memory',
			'name' => $name,
			'data_type' => gettype($object)
		);

		self::add_to_console('memory_count', $log_item);
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: get_logs()
		
		Returns the logs array for use in external classes. (Namely the
		Forensics Profiler.
	*/
	public static function get_logs() 
	{
		return self::$logs;
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	private static function add_to_console($log=null, $item=null) 
	{
		if (empty($log) || empty($item)) 
		{ 
			return false;
		}
		
		self::$logs['console'][]	= $item;
		self::$logs[$log] 			+= 1;
	}
	
	//--------------------------------------------------------------------
	
}

// End Console class