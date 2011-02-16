<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = ENVIRONMENT;
$active_record = TRUE;

$db['dev']['hostname'] = 'localhost';
$db['dev']['username'] = 'bonfire_dever';
$db['dev']['password'] = '';
$db['dev']['database'] = 'bonfire2_dev';
$db['dev']['dbdriver'] = 'mysql';
$db['dev']['dbprefix'] = 'bf_';
$db['dev']['pconnect'] = TRUE;
$db['dev']['db_debug'] = TRUE;
$db['dev']['cache_on'] = FALSE;
$db['dev']['cachedir'] = '';
$db['dev']['char_set'] = 'utf8';
$db['dev']['dbcollat'] = 'utf8_general_ci';
$db['dev']['swap_pre'] = '';
$db['dev']['autoinit'] = TRUE;
$db['dev']['stricton'] = TRUE;

$db['test']['hostname'] = 'localhost';
$db['test']['username'] = '';
$db['test']['password'] = '';
$db['test']['database'] = '';
$db['test']['dbdriver'] = 'mysql';
$db['test']['dbprefix'] = '';
$db['test']['pconnect'] = TRUE;
$db['test']['db_debug'] = TRUE;
$db['test']['cache_on'] = FALSE;
$db['test']['cachedir'] = '';
$db['test']['char_set'] = 'utf8';
$db['test']['dbcollat'] = 'utf8_general_ci';
$db['test']['swap_pre'] = '';
$db['test']['autoinit'] = TRUE;
$db['test']['stricton'] = TRUE;

$db['prod']['hostname'] = 'localhost';
$db['prod']['username'] = '';
$db['prod']['password'] = '';
$db['prod']['database'] = '';
$db['prod']['dbdriver'] = 'mysql';
$db['prod']['dbprefix'] = '';
$db['prod']['pconnect'] = TRUE;
$db['prod']['db_debug'] = TRUE;
$db['prod']['cache_on'] = FALSE;
$db['prod']['cachedir'] = '';
$db['prod']['char_set'] = 'utf8';
$db['prod']['dbcollat'] = 'utf8_general_ci';
$db['prod']['swap_pre'] = '';
$db['prod']['autoinit'] = TRUE;
$db['prod']['stricton'] = TRUE;


/* End of file database.php */
/* Location: ./application/config/database.php */