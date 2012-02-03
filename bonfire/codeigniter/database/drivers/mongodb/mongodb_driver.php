<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Class: CI_DB_mongodb_driver
	
	Note: _DB is an extender class that the app controller
	creates dynamically based on whether the active record
	class is being used or not.
	
	Implements a CodeIgniter database driver for MongoDB.
	
	This class stands on the excellent work of others, including
	*	Alex Bilbie's MongoDB library (https://github.com/alexbilbie/codeigniter-mongodb-library)
	* 	Gabriel Garcia's MongoDB library (https://github.com/Garciat/codeigniter-mongodb)
	
	Author: Lonnie Ezell
*/
class CI_DB_mongodb_driver extends CI_DB {

	public $dbdriver = 'mongodb';
	
	// clause and character used for LIKE escape sequences - not used in MongoDB
	protected $_like_escape_str = '';
	protected $_like_escape_chr = '';
	
	/**
	 * The syntax to count rows is slightly different across different
	 * database engines, so this string appears in each driver and is
	 * used for the count_all() and count_all_results() functions.
	 */
	var $_count_string = 'SELECT COUNT(*) AS ';
	var $_random_keyword = ' RAND()'; // database specific random keyword
	
	// whether SET NAMES must be used to set the character set
	var $use_set_names;
	
	//--------------------------------------------------------------------
	
	public function __construct($params) 
	{
		parent::__construct($params);
		
		$this->connection_string();
	}
	
	//--------------------------------------------------------------------
	
	
	/**
	 * Non-persistent database connection
	 *
	 * @access	private called by the base class
	 * @return	resource
	 */
	public function db_connect() 
	{
		if (!class_exists('Mongo'))
		{
			show_error('The MongoDB PECL extension has not been installed or enabled', 500);
		}
		
		try{
			$this->connection = new Mongo($this->connection_string);
			return $this->connection;
		}
		catch (MongoConnectionException $e)
		{
			show_error('Unable to connect to MongoDB.', 500);
		}
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Persistent database connection
	 *
	 * @access	private called by the base class
	 * @return	resource
	 */
	public function db_pconnect() 
	{
		if (!class_exists('Mongo'))
		{
			show_error('The MongoDB PECL extension has not been installed or enabled', 500);
		}
		
		$persist = 'ci_mongo_persist';
		
		try{
			$this->connection = new Mongo($this->connection_string, array('persist' => $persist));
			return $this->connection;
		}
		catch (MongoConnectionException $e)
		{
			show_error('Unable to connect to MongoDB.', 500);
		}
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Select the database
	 *
	 * @access	private called by the base class
	 * @return	resource
	 */
	public function db_select() 
	{
		return $this->connection->selectDB($this->database);
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Set client character set
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	resource
	 */
	public function db_set_charset($charset, $collation) 
	{ 
		/*
			The MongoDB driver assumes that all data
			is in UTF-8, so just return TRUE here.
		*/
		
		return TRUE;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Execute the query
	 *
	 * @access	private called by the base class
	 * @param	string	an SQL query
	 * @return	resource
	 */
	protected function _execute($sql) 
	{
		echo $sql ."<br/>";
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Escape the SQL Identifiers
	 *
	 * This function escapes column and table names
	 *
	 * @access	private
	 * @param	string
	 * @return	string
	 */
	protected function _escape_identifiers($item) 
	{
		/*
			MongoDB doesn't require escaping identifiers.
		*/	
		return TRUE;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * From Tables
	 *
	 * This function implicitly groups FROM tables so there is no confusion
	 * about operator precedence in harmony with SQL standards
	 *
	 * @access	public
	 * @param	type
	 * @return	type
	 */
	protected function _from_tables($tables) 
	{
		if (!is_array($tables))
		{
			$tables = (array)$tables;
		}
		
		return implode(',', $tables);
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Insert statement
	 *
	 * Generates a platform-specific insert string from the supplied data
	 *
	 * @access	public
	 * @param	string	the table name
	 * @param	array	the insert keys
	 * @param	array	the insert values
	 * @return	string
	 */
	protected function _insert($table, $keys, $values)
	{
		//return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Replace statement
	 *
	 * Generates a platform-specific replace string from the supplied data
	 *
	 * @access	public
	 * @param	string	the table name
	 * @param	array	the insert keys
	 * @param	array	the insert values
	 * @return	string
	 */
	protected function _replace($table, $keys, $values)
	{
	//	return "REPLACE INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Insert_batch statement
	 *
	 * Generates a platform-specific insert string from the supplied data
	 *
	 * @access	public
	 * @param	string	the table name
	 * @param	array	the insert keys
	 * @param	array	the insert values
	 * @return	string
	 */
	protected function _insert_batch($table, $keys, $values)
	{
		//return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES ".implode(', ', $values);
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Update statement
	 *
	 * Generates a platform-specific update string from the supplied data
	 *
	 * @access	public
	 * @param	string	the table name
	 * @param	array	the update data
	 * @param	array	the where clause
	 * @param	array	the orderby clause
	 * @param	array	the limit clause
	 * @return	string
	 */
	protected function _update($table, $values, $where, $orderby = array(), $limit = FALSE)
	{
	
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Update_Batch statement
	 *
	 * Generates a platform-specific batch update string from the supplied data
	 *
	 * @access	public
	 * @param	string	the table name
	 * @param	array	the update data
	 * @param	array	the where clause
	 * @return	string
	 */
	protected function _update_batch($table, $values, $index, $where = NULL)
	{
	
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Truncate statement
	 *
	 * Generates a platform-specific truncate string from the supplied data
	 * If the database does not support the truncate() command
	 * This function maps to "DELETE FROM table"
	 *
	 * @access	public
	 * @param	string	the table name
	 * @return	string
	 */
	protected function _truncate($table)
	{
		//return "TRUNCATE ".$table;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Delete statement
	 *
	 * Generates a platform-specific delete string from the supplied data
	 *
	 * @access	public
	 * @param	string	the table name
	 * @param	array	the where clause
	 * @param	string	the limit clause
	 * @return	string
	 */
	protected function _delete($table, $where = array(), $like = array(), $limit = FALSE)
	{
	
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	/** 
	 * Generates the connection string for the database.
	 */
	private function connection_string() 
	{
		$connection_string = "mongodb://";
		
		if (empty($this->hostname))
		{
			show_error('The Host name must be set to connect to MongoDB.', 500);
		}
		
		if (empty($this->database))
		{
			show_error('The Database name must be set to connect to MongoDB.', 500);
		}
		
		if (!empty($this->username) && !empty($this->password))
		{
			$connection_string .= "{$this->username}:{$this->password}@";
		}
		
		if (isset($this->port) && !empty($this->port))
		{
			$connection_string .= "{$this->hostname}:{$this->port}";
		} 
		else
		{
			$connection_string .= "{$this->hostname}";
		}
		
		$this->connection_string = trim($connection_string);
	}
	
	//--------------------------------------------------------------------
	
}

//--------------------------------------------------------------------