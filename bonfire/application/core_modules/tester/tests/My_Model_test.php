<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_Model_test extends Unit_tester {

	private $model;

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		if (!class_exists('MY_Model'))
		{
			require APPPATH .'core/MY_Model.php';
		}
		
		$this->model = new MY_Model();
	}
	
	//--------------------------------------------------------------------
	
	public function pre() 
	{
		// Create our database table and load in our SQL.
		$this->load_sql('test_generic_schema.sql');
	}
	
	//--------------------------------------------------------------------
	

	public function test_is_loaded() 
	{
		$this->assert_true(class_exists('MY_Model'));
	}
	
	//--------------------------------------------------------------------
	
	

}