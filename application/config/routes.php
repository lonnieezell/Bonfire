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

// Authentication
Route::any(LOGIN_URL, 'users/login', array('as' => 'login'));
Route::any(REGISTER_URL, 'users/register', array('as' => 'register'));
Route::block('users/login');
Route::block('users/register');

Route::any('logout', 'users/logout');
Route::any('forgot_password', 'users/forgot_password');
Route::any('reset_password/(:any)/(:any)', 'users/reset_password/$1/$2');

// Activation
Route::any('activate', 'users/activate');
Route::any('activate/(:any)', 'users/activate/$1');
Route::any('resend_activation', 'users/resend_activation');

// Contexts
Route::prefix(SITE_AREA, function(){
    Route::context('content', array('home' => SITE_AREA .'/content/index'));
    Route::context('reports', array('home' => SITE_AREA .'/reports/index'));
    Route::context('connector', array('home' => SITE_AREA .'/connector/index'));
    Route::context('developer');
    Route::context('settings');
});


$route[SITE_AREA]	= 'admin/home';


$route = Route::map($route);

/* End of file routes.php */
/* Location: ./application/config/routes.php */