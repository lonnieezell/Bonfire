<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['site.title'] = 'Bonfire';
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
