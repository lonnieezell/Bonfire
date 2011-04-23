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

require_once(APPPATH .'core_modules/tester/libraries/Base_Tester.php');

/*
	Class: Unit_Test
	
	An abstract base class that all unit tests should extend from.
	Provides unit-testing specific asserts, and database connection
	and management methods.
	
	Inspired by Toast (http://jensroland.com/projects/toast/), 
	SimpleTest (http://simpletest.org), and 
	FuelCMS (http://getfuelcms.com)
*/
class Unit_Tester extends Base_Tester {

	/*
		Var: $db_created
		If true, we know we need to tear down the database
		during the post() method.
	*/
	private $db_created	= false;
	
	/*
		Var: $my_db
		Stores the database connection used for this test only.
	*/
	protected $my_db;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->ci->config->load('tester');
	}
	
	//--------------------------------------------------------------------
	
	protected function post() 
	{
		if ($this->db_created === true)
		{
			$this->db_remove();
			// Close our database
			$this->my_db->close();
		}
	}
	
	//--------------------------------------------------------------------
	

	//--------------------------------------------------------------------
	// !Database Methods
	//--------------------------------------------------------------------
	
	/*
		Method: load_sql()
		
		Populates a database test results. Loads and/or creates the
		database, if necessary.
		
		Parameters:
			$file	- The name of the sql file (including extension);
	*/
	protected function load_sql($file=null) 
	{
		if (!$this->db_created)
		{
			$this->db_create();
		}
		
		// Connect to the database
		$sql = 'USE '. $this->ci->config->item('tester.database');
		$this->my_db->query($sql);
		
		// We need to know the SQL to run.
		$sql_path = $this->module_path .'/'. $file;
		
		if (file_exists($sql_path))
		{
			$sql = file_get_contents($sql_path);
			$sql = str_replace('`', '', $sql);
			$sql = preg_replace('#^/\*(.+)\*/$#U', '', $sql);
			$sql = preg_replace('/^#(.+)$/U', '', $sql);
		}
		
		$sql_statements = explode(";\n", $sql);

		// Now actually run the sql!
		foreach ($sql_statements as $statement)
		{
			$statement = trim($statement);
			
			if (!empty($statement))
			{
				$this->my_db->query($statement);
			}
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: db_create()
		
		Creates the database, if it doesn't exist.
		
		Parameters:
			$connect	- Will connect to the databse if true.
	*/
	protected function db_create($connect=true) 
	{
		if ($connect)
		{
			$this->db_connect();
		}
		
		if (!$this->db_exists())
		{
			$this->ci->load->dbforge();
			
			$this->ci->dbforge->create_database($this->ci->config->item('tester.database'));
		}
		
		$this->db_created = true;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: db_connect()
		
		Connects to the testing database. 
		
		Connection info is used from the tester config file, if present.
		If that's not there, we will look in the current environment's
		database config file for a group called 'test'.
	*/
	protected function db_connect() 
	{
		$dsn = $this->ci->config->item('tester.dsn');
		
		// No DSN provided? Create it from the test group 
		// in the current environment.
		if (empty($dsn))
		{
			$this->ci->load->helper('config_file');
			
			$settings = read_db_config(ENVIRONMENT);
			
			// If we don't have any db info, we can't continue....
			if (!isset($settings[ENVIRONMENT]['test']))
			{
				show_error('Unable to find Test database configuration.');			
			}
			
			$dsn = 'test';
		}
		
		$this->my_db = $this->ci->load->database($dsn, true);
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: db_exists()
		
		Checks to see if the testing database already exists.
		
		Returns:
			true/false
	*/
	protected function db_exists() 
	{
		$db_name = $this->ci->config->item('tester.database');
		
		$this->ci->load->dbutil();
		
		return $this->ci->dbutil->database_exists($db_name);
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: db_remove()
		
		Drops the database, if it exists.
	*/
	public function db_remove($connect = true) 
	{
		if ($connect)
		{
			$this->db_connect();
		}
		
		if ($this->db_exists())
		{
			$this->ci->load->dbforge();
			
			$db_name = $this->ci->config->item('tester.database');
			
			$this->ci->dbforge->drop_database($db_name);
		}
		
		$this->db_created = false;
	}
	
	//--------------------------------------------------------------------
	
	
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
		$this->message = 'Expected TRUE, got ['. $this->describe_value($assertion) .'] '. $message;
	
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
		$this->message = 'Expected FALSE, got ['. $this->describe_value($assertion) .'] '. $message;
	
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
			$this->message = 'Equal expectation ['. $this->describe_value($check) .'] '. $message;
			return true;
		}
		else 
		{
			$this->message = 'Equal expectation failed ['. $this->describe_difference($base, $check) .'] '. $message;
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
		if ($base != $check)
		{
			$this->message = 'Not Equal expectation passes ['. $this->describe_difference($base, $check) .'] '. $message;
			return true;
		}
		else 
		{
			$this->message = 'Not Equal expectation failed ['. $this->describe_difference($base, $check) .'] '. $message;
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
		$this->message = 'Expected empty, got ['. $this->describe_value($assertion) .'] '. $message;
	
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
		$this->message = 'Expected not empty, got ['. $this->describe_value($assertion) .'] '. $message;
	
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
		$this->message = 'Expected NULL, got ['. $this->describe_value($assertion) .'] '. $message;
	
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
		$this->message = 'Expected not NULL, got ['. $this->describe_value($assertion) .'] '. $message;
	
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
		$this->message = 'Expected identical values: ['. $this->describe_difference($base, $check, true) .'] '. $message;
	
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
		$this->message = 'Expected identical values: ['. $this->describe_difference($base, $check, true) .'] '. $message;
	
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
		Method: assert_is_type()
		
		Checks if the assertion is a type of class.
		
		Parameters:
			$base		- The value to test.
			$type		- The type name to compare against.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_is_type($base, $type, $message=null) 
	{ 
		$this->message = 'Expected type '. ucfirst($type) .', was ['. $this->get_type($base) .'] '. $message;
	
		if (strtolower($this->get_type($base)) == strtolower($type))
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
		Method: assert_not_is_type()
		
		Checks if the assertion is not a type of class.
		
		Parameters:
			$base		- The value to test.
			$type		- The class name to compare against.
			$message	- An optional message to be used.
			
		Returns: 
			true/false
	*/
	protected function assert_not_is_type($base, $type, $message='') 
	{
		$this->message = 'Expected not type '. ucfirst($type) .', was ['. $this->get_type($base) .'], '. $message;
	
		if (strtolower($this->get_type($base)) != strtolower($type))
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