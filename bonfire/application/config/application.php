<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['site.title'] = 'Crust.Me';
$config['site.system_email'] = 'lonnieje@gmail.com';
$config['site.status'] = 1;
$config['site.list_limit'] = 25;

//--------------------------------------------------------------------
// !AUTHENTICATION
//--------------------------------------------------------------------

// Type of login allowed ('email', 'username', 'both')
$config['auth.login_type'] = 'email';

// Whether usernames are used in the system
$config['auth.use_usernames'] = 1;

// Should users be able to use a 'remember me' functionality?
$config['auth.allow_remember'] = 1;

// How long should a user be remembered in the system? In seconds.
$config['auth.remember_length'] = 1209600;	

//--------------------------------------------------------------------
// !MODULES
//--------------------------------------------------------------------

/*
	These module settings determine what permissions are needed to view
	modules in the left-hand sidebar.
*/
$config['module_permissions'] = array(
	'content'		=> array(
	),
	'statistics'	=> array(
	
	),
	'appearance'	=> array(
	
	),
	'settings'		=> array(
		'database'	=> 'Bonfire.Database.Manage',
		'emailer'	=> 'Bonfire.Emailer.Manage',
		'roles'		=> 'Bonfire.Roles.Manage',
		'users'		=> 'Bonfire.Users.View'
	),
	'developer'		=> array(
		'database'	=> 'Bonfire.Database.Manage',		
	)
);