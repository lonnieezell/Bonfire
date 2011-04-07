<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH .'core_modules/tester/libraries/Base_Tester.php');

/*
	Class: Unit_Test
	
	An abstract base class that all unit tests should extend from.
	
	Inspired by Toast (http://jensroland.com/projects/toast/), 
	SimpleTest (http://simpletest.org), and 
	FuelCMS (http://getfuelcms.com)
*/
class Unit_Tester extends Base_Tester {

	
	//--------------------------------------------------------------------
	// !Assertions
	//--------------------------------------------------------------------
	
	/*
		Method: assert_true()
		
		Checks if the assertion evaluates to TRUE.
		
		Parameters:
			$assertion	- The value to test.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_true($assertion, $message=null) 
	{		
		$this->message = !empty($message) ? $message : 'Expected TRUE, got ['. $this->describe_value($assertion) .']';
	
		if ($assertion)
		{
			return true;
		}
		else
		{
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: assert_false()
		
		Checks if the assertion evaluates to FALSE.
		
		Parameters:
			$assertion	- The value to test.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_false($assertion, $message=null) 
	{
		$this->message = !empty($message) ? $message : 'Expected FALSE, got ['. $this->describe_value($assertion) .']';
	
		if (!$assertion)
		{
			return true;
		}
		else
		{
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: assert_equals()
		
		Checks if the values equal each other. Does not perform strict
		type checking.
		
		Parameters:
			$base		- The value to test.
			$check		- The value to test against.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_equals($base, $check, $message=null) 
	{
		if ($base == $check)
		{
			$this->message = !empty($message) ? $message : 'Equal expectation ['. $this->describe_value($compare) .']';
			return true;
		}
		else 
		{
			$this->message = !empty($message) ? $message : 'Equal expectation failed ['. $this->describe_difference($base, $compare) .']';
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: assert_not_equals()
		
		Checks if the values are not equal to each other. Does not perform
		strict type checking.
		
		Parameters:
			$base		- The value to test.
			$check		- The value to test against.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_not_equals($base, $check, $message=null) 
	{
		if (!empty($message))
		{
			$this->message = $message;
		}
	
		if ($base != $check)
		{
			return true;
		}
		else 
		{
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: assert_empty()
		
		Checks if the assertion is empty.
		
		Parameters:
			$assertion	- The value to test.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	public function assert_empty($assertion, $message=null) 
	{
		if (!empty($message))
		{
			$this->message = $message;
		}
	
		if (empty($assertion))
		{
			return true;
		}
		else 
		{
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: assert_not_empty()
		
		Checks if the assertion is not empty.
		
		Parameters:
			$assertion	- The value to test.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	public function assert_not_empty($assertion, $message=null) 
	{
		if (!empty($message))
		{
			$this->message = $message;
		}
	
		if (!empty($assertion))
		{
			return true;
		} 
		else 
		{
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: assert_null()
		
		Checks if the assertion is null.
		
		Parameters:
			$assertion	- The value to test.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_null($assertion, $message=null) 
	{
		if (!empty($message))
		{
			$this->message = $message;
		}
	
		if (is_null($assertion))
		{
			return true;
		}
		else
		{
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: assert_not_null()
		
		Checks if the assertion is not null.
		
		Parameters:
			$assertion	- The value to test.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_not_null($assertion, $message=null) 
	{
		if (!empty($message))
		{
			$this->message = $message;
		}
	
		if (!is_null($assertion))
		{
			return true;
		}
		else
		{
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: assert_identical()
		
		Checks if the values are equal AND of the same type.
		
		Parameters:
			$base		- The value to test.
			$check		- The value to test against.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_identical($base, $check, $message=null) 
	{
		if (!empty($message))
		{
			$this->message = $message;
		}
	
		if ($base === $check)
		{
			return true;
		}
		else
		{
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: assert_not_identical()
		
		Checks if the values are not equal, or not of the same type.
		
		Parameters:
			$base		- The value to test.
			$check		- The value to test against.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_not_identical($base, $check, $message=null) 
	{
		if (!empty($message))
		{
			$this->message = $message;
		}
	
		if ($base !== $check)
		{
			return true;
		}
		else 
		{
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: assert_is_a()
		
		Checks if the assertion is a type of class. Uses the PHP is_a() function.
		
		Parameters:
			$base		- The value to test.
			$type		- The class name to compare against.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_is_a($base, $type, $message=null) 
	{
		if (!empty($message))
		{
			$this->message = $message;
		}
	
		if (is_a($base, $type))
		{
			return true;
		}
		else
		{
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------

	/*
		Method: assert_not_is_a()
		
		Checks if the assertion is not a type of class. Uses the PHP is_a() function.
		
		Parameters:
			$base		- The value to test.
			$type		- The class name to compare against.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_not_is_a($base, $type, $message='') 
	{
		if (!empty($message))
		{
			$this->message = $message;
		}
	
		if (!is_a($base, $type))
		{
			return true;
		}
		else
		{
			$this->asserts = false;
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
}