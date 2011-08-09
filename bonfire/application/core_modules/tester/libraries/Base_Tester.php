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
	Class: Base_Tester
	
	The base test class that all other test classes extend from.
*/
class Base_Tester {

	/*
		Var: $ci
		An instance of the CodeIgniter object.
	*/
	protected $ci;
	
	/*
		Var: $asserts
		Stores the result from the current assertion test.
	*/
	protected $asserts = true;
	
	/*
		Var: $message
		Stores the message results from the assertion test.
	*/
	protected $message = '';
	
	/*
		Var: $messages
		Stores the collective messages from all of the tests.
	*/
	protected $messages	= array();
	
	/*
		Var: $module_path
		Stores the full server path to the module.
		(Automatically set by the test runner)
	*/
	protected $module_path;

	//--------------------------------------------------------------------

	/*
		Method: __construct()
		
		Sets up the CI instance.
	*/
	public function __construct() 
	{
		$this->ci =& get_instance();
	}
	
	//--------------------------------------------------------------------

	/*
		Method: get_test_methods()
		
		Returns an array of all tests in the class.
		Tests are methods that start with 'test_'.
	*/
	protected function get_test_methods() 
	{
		$test_methods = array();
		
		$methods = get_class_methods($this);
		
		foreach ($methods as $method)
		{
			if (substr(strtolower($method), 0, 5) == 'test_') 
			{
				$test_methods[] = $method;
			}
		}
		
		return $test_methods;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: run_all()
		
		Runs all test in the extended test class and displays the results.
	*/
	public function run_all() 
	{
		foreach ($this->get_test_methods() as $test)
		{
			$this->run($test);
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: run()
		
		Runs a single test in the extended test class.
		
		Parameters:
			$method	- The name of the test method to run. Must start with 'test_'.
	*/
	protected function run($method) 
	{
		// Reset the message from test
		$this->message = '';
		
		// Reset asserts
		$this->asserts = true;
		
		// Run prep method
		$this->pre();
		
		// Run the test case (result will be in $this->asserts)
		$this->$method();
		
		// Run the cleanup method
		$this->post();
		
		// Store our message for the view
		$this->messages[] = $this->message;
		
		$test_name = $this->format_test_name($method);
		
		// Start the Benchmark
		$this->ci->benchmark->mark($method .'_start');
		
		// Pass it to the CI Unit Tester
		$this->ci->unit->run($this->asserts, true, $test_name, $this->message);
		
		// End the Benchmark
		$this->ci->benchmark->mark($method .'_end');
	}
	
	//--------------------------------------------------------------------
	
	protected function format_test_name($method) 
	{
		// Remove the 'test_' from the method name
		$name = str_replace('test_', '', $method);
		
		// Clean it up
		$name = ucwords(str_replace('_', ' ', $name));
		
		return $name;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: pre()
		
		Ran automatically before each test is run. 
		This function is intended to be overwritten by extending classes
		and allows any necessary setup to be preformed.
	*/
	protected function pre() 
	{
		
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: post()
		
		Ran automatically after each test is run.
		This function is intended to be overwrittern by extending classes
		and allows any necessary cleanup to be performed.
	*/
	protected function post() 
	{
		
	}
	
	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !UTILITY METHODS
	//--------------------------------------------------------------------
	
	/*
		Method: set_module_path()
		
		Saves the full server path to the current module.
		
		Parameters:
			$path	- The path to store.
	*/
	public function set_module_path($path=null) 
	{
		if (!empty($path))
		{
			$this->module_path = $path;
		}
	}
	
	//--------------------------------------------------------------------
	
	
	/*
		Method: describe_value()
		
		Returns a string describing the object and it's type.
	*/
	protected function describe_value($value) 
	{
		$type = $this->get_type($value);
		
		switch ($type)
		{
			case 'Null':
				return 'NULL';
			case 'Boolean':
				return 'Boolean: '. ($value ? 'true' : 'false');
			case 'Array':
				return 'Array: '. count($value) .' items';
			case 'Object':
				return 'Object of: '. get_class($value);
			case 'String':
				return 'String: '. substr($value, 0, 200) .'...';
			default: 
				return "$type: $value";
		}
		
		return 'Unknown';
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !Type Methods
	//--------------------------------------------------------------------
	
	/*
		Method: is_type_match()
		
		Checks to see if two values are of the same type.
		
		Parameters:
			$base		- The value to compare.
			$compare	- The value to compare against.
			
		Returns:
			true/false
	*/
	public function is_type_match($base, $compare) 
	{
		return ($this->get_type($base) == $this->get_type($compare));
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: get_type()
		
		Returns a string representation of the value's type.
		
		Parameters:
			$value	- The value to get the type of.
	*/
	protected function get_type($value) 
	{
		if (!isset($value))
		{
			return 'NULL';
		}
		else if (is_bool($value))
		{
			return 'Boolean';
		}
		else if (is_string($value))
		{
			return 'String';
		}
		else if (is_integer($value))
		{
			return 'Integer';
		}
		else if (is_float($value))
		{
			return 'Float';
		}
		else if (is_array($value))
		{
			return 'Array';
		}
		else if (is_resource($value))
		{
			return 'Resource';
		}
		else if (is_object($value))
		{
			return 'Object';
		}
		
		return 'Unknown';
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !Difference Methods
	//--------------------------------------------------------------------
	
	/*
		Method: describe_difference()
		
		Creates a human readable description of the difference between
		two variables.
		
		Parameters:
			$base		- The object to compare.
			$compare	- The object to compare against.
			$identical	- Whether to perform type checking.
			
		Returns:
			A string describing the difference between $base and $compare.
	*/
	protected function describe_difference($base, $compare, $identical=false) 
	{
		if ($identical)
		{
			if (!$this->is_type_match($base, $compare))
			{
				return 'with type mismatch as ['. $this->describe_value($base) .'] does not match ['. $this->describe_value($compare) .']';
			}
		}
		
		$type = $this->get_type($base);
		
		if ($type == 'Unknown')
		{
			return 'with unknown type';
		}
		
		$method = 'describe_'. strtolower($type) .'_difference';
		return $this->$method($base, $compare, $identical);
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: describe_generic_difference()
		
		Creates a human readable description of the difference between
		two variables. This method is used as the basis for many of the 
		other difference describers.
		
		Parameters:
			$base		- The object to compare.
			$compare	- The object to compare against.
			
		Returns:
			A string describing the difference between $base and $compare.
	*/
	protected function describe_generic_difference($base, $compare) 
	{
		return 'as ['. $this->describe_value($base) .'] does not match ['. $this->describe_value($compare) .']';
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: describe_null_difference()
		
		Creates a human readable description of the difference between
		two variables.
		
		Parameters:
			$base		- The object to compare.
			$compare	- The object to compare against.
			$identical	- Whether to perform type checking.
			
		Returns:
			A string describing the difference between $base and $compare.
	*/
	protected function describe_null_difference($base, $compare, $identical) 
	{
		return $this->describe_generic_difference($base, $compare);
	}
	
	//--------------------------------------------------------------------
	
	protected function describe_boolean_difference($base, $compare, $identical) 
	{
		return $this->describe_generic_difference($base, $compare);
	}
	
	//--------------------------------------------------------------------
	
	protected function describe_string_difference($base, $compare, $identical) 
	{
		if (is_object($compare) || is_array($compare))
		{
			return $this->describe_generic_difference($base, $compare);
		}
		
		return 'because ['. $this->describe_value($base) .'] differs from ['. 
			$this->describe_value($compare) .'] by '. abs($base) - abs($compare);
	}
	
	//--------------------------------------------------------------------
	
	protected function describe_integer_difference($base, $compare, $identical) 
	{
		return $this->describe_string_difference($base, $compare, $identical);
	}
	
	//--------------------------------------------------------------------
	
	protected function describe_float_difference($base, $compare, $identical) 
	{
		return $this->describe_string_difference($base, $compare, $identical);
	}
	
	//--------------------------------------------------------------------
	
	protected function describe_array_difference($base, $compare, $identical) 
	{
		if (!is_array($compare))
		{
			return $this->describe_generic_difference($base, $compare);
		}
		
		if (!$this->is_matching_keys($base, $compare, $identical))
		{
			return 'as key list ['. 
				implode(", ", array_keys($base)) . "] does not match key list [" .
				implode(", ", array_keys($compare)) . "]";
		}
		
		foreach (array_keys($base) as $key) 
		{
			if ($identical && ($base[$key] === $compare[$key])) 
			{
				continue;
			}
			
			if (! $identical && ($base[$key] == $compare[$key])) 
			{
				continue;
			}
			
			return "with member [$key] " . $this->describe_difference($base[$key], $compare[$key], $identical);
		}
		return '';
	}
	
	//--------------------------------------------------------------------
	
	protected function is_matching_keys($base, $compare, $identical) 
	{
		$first_keys = array_keys($base);
		$second_keys = array_keys($compare);
	
		if ($identical) 
		{
			return ($first_keys === $second_keys);
		}
		
		sort($first_keys);
		sort($second_keys);
	
		return ($first_keys == $second_keys);
	}
	
	//--------------------------------------------------------------------
	
	protected function describe_resource_difference($base, $compare, $identical) 
	{
		return $this->describe_generic_difference($base, $compare);
	}
	
	//--------------------------------------------------------------------
	
	protected function describe_object_difference($base, $compare, $identical) 
	{
		if (!is_object($compare))
		{
			return $this->describe_generic_difference($base, $compare);
		}
		
		return $this->describe_array_difference((array)$base, (array)$compare, $identical);
	}
	
	//--------------------------------------------------------------------
	
}