<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	A DSN string with connection details to the testing database.
	
	The string should look like:
	
	'dbdriver://username:password@hostname/';
	
	NOTE: do not include the database name at the end of the DSN.
	The 'tester.database' setting, below, will be automatically attached.
*/
$config['tester.dsn']		= '';

/*
	The name of the test database to create.
	This name will be added to the DSN info,
	above, if provided.
*/
$config['tester.database']	= 'bonfire_tester';
