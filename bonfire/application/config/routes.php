<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = '';

// Authorization
$route['login']				= 'users/login';
$route['register']			= 'users/register';
$route['logout']			= 'users/logout';
$route['forgot_password']	= 'users/forgot_password';

// Admin:content
$route['admin/content/(:any)/(:any)/(:any)/(:any)']		= "$1/content/$2/$3/$4";
$route['admin/content/(:any)/(:any)/(:any)']		= "$1/content/$2/$3";
$route['admin/content/(:any)/(:any)'] 		= "$1/content/$2";
$route['admin/content/(:any)']				= "$1/content/index";

// Admin:appearance
$route['admin/appearance/(:any)/(:any)/(:any)']		= "$1/appearance/$2/$3";
$route['admin/appearance/(:any)/(:any)'] 	= "$1/appearance/$2";
$route['admin/appearance/(:any)']			= "$1/dappearance/index";

// Admin:stats
$route['admin/stats/(:any)/(:any)/(:any)']		= "$1/stats/$2/$3";
$route['admin/stats/(:any)/(:any)'] 		= "$1/stats/$2";
$route['admin/stats/(:any)']				= "$1/stats/index";

// Admin:settings
$route['admin/settings/(:any)/(:any)/(:any)']		= "$1/settings/$2/$3";
$route['admin/settings/(:any)/(:any)']		= "$1/settings/$2";
$route['admin/settings/(:any)']				= "$1/settings/index";

// Admin:developer
$route['admin/developer/php_info']				= "admin/developer/php_info"; 
$route['admin/developer/(:any)/(:any)/(:any)']		= "$1/developer/$2/$3";
$route['admin/developer/(:any)/(:any)'] = "$1/developer/$2";
$route['admin/developer/(:any)']		= "$1/developer/index";

$route['admin']	= 'admin/home';

/* End of file routes.php */
/* Location: ./application/config/routes.php */