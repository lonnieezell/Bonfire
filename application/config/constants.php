<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


define('BONFIRE_VERSION', 'v0.7.1-dev');

/*
	The 'App Area' allows you to specify the base folder used for all of
	the contexts in the app. By default, this is set to '/admin', but this
	does not make sense for all applications.
*/
define('SITE_AREA', 'admin');

/*
	The 'LOGIN_URL' and 'REGISTER_URL' constant allows changing of the url where the login page is accessible
	(asides from the controller-based 'users/login'). This may be helpful for reducing brute force
	login attacks, as the login URL can be changed to something obscure, and the controller-based
	'users/login' can be redirected to 403/4 in routes.php.
*/
define('LOGIN_URL', 'login');
define('REGISTER_URL', 'register');


/*
	The 'IS_AJAX' constant allows for a quick simple test as to whether the
	current request was made with XHR.
*/

$ajax_request = ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? TRUE : FALSE;
define('IS_AJAX' , $ajax_request );
unset ( $ajax_request );

/* End of file constants.php */
/* Location: ./application/config/constants.php */
