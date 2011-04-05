<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['site.title'] = 'Bonfire Testsite';
$config['site.system_email'] = 'lonnieje@gmail.com';
$config['site.status'] = 1;		// 0 = offline, 1 = online
$config['site.list_limit'] = 25;

//--------------------------------------------------------------------
// !For Update messages
//--------------------------------------------------------------------

$config['updates.do_check'] = 0;		// Whether to check for updates or not.
$config['updates.bleeding_edge'] = 1;	// Show update message when new commits exist? 
$config['updates.last_commit']	= '';	// Stores the last installed commit ref

//--------------------------------------------------------------------
// !BACKUPS
//--------------------------------------------------------------------
//
// A folder that all backup files will be written to. This should be
// relative APPPATH. This is primarily used by MY_Config when writing
// new files.
//
$config['site.backup_folder']	= 'archives/';

//--------------------------------------------------------------------
// !AUTHENTICATION
//--------------------------------------------------------------------

// Can new accounts be registered? 
$config['auth.allow_register'] = 1;

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
		'views'		=> 'Site.Content.View'
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

//--------------------------------------------------------------------
// !TEMPLATE
//--------------------------------------------------------------------

/*
|--------------------------------------------------------------------
| SITE PATH
|--------------------------------------------------------------------
| The path to the root folder that holds the application. This does
| not have to be the site root folder, or even the folder defined in
| FCPATH. 
|
*/
$config['template.site_path']	= FCPATH;

/*
|---------------------------------------------------------------------
| THEME PATHS
|---------------------------------------------------------------------
| An array of folders to look in for themes. There must be at least
| one folder path at all times, to serve as the fall-back for when
| a theme isn't found. Paths are relative to the FCPATH.
*/
$config['template.theme_paths'] = array('bonfire/themes');

/*
|--------------------------------------------------------------------
| DEFAULT LAYOUT
|--------------------------------------------------------------------
| This is the name of the default layout used if no others are
| specified.
|
| NOTE: do not include an ending ".php" extension.
|
*/
$config['template.default_layout'] = "index";

/*
|--------------------------------------------------------------------
| DEFAULT AJAX LAYOUT
|--------------------------------------------------------------------
| This is the name of the default layout used when the page is 
| displayed via an AJAX call.
|
| NOTE: do not include an ending ".php" extension.
|
*/
$config['template.ajax_layout'] = 'ajax';

/*
|--------------------------------------------------------------------
| USE THEMES?
|--------------------------------------------------------------------
| When set to TRUE, Ocular will check the user agent during the 
| render process, and check the UA against the template.themes (below),
| allowing you to create mobile versions of your site, and version
| targetted specifically at a single type of phone (ie, Blackberry or
| iPhone).
|
| Note, that, when rendering, if the file doesn't exist in the 
| targetted theme, Ocular then checks the default site for the same file.
|
*/
$config['template.use_mobile_themes'] = FALSE;


/*
|--------------------------------------------------------------------
| DEFAULT THEME
|--------------------------------------------------------------------
| This is the folder name that contains the default theme to use
| when 'template.use_mobile_themes' is set to TRUE.
|
*/
$config['template.default_theme'] = 'default/';

/*
|--------------------------------------------------------------------
| MESSAGE TEMPLATE
|--------------------------------------------------------------------
| This is the template that Ocular will use when displaying messages
| through the message() function. 
|
| To set the class for the type of message (error, success, etc),
| the {type} placeholder will be replaced. The message will replace 
| the {message} placeholder.
|
*/
$config['template.message_template'] =<<<EOD
	<div class="notification {type} fade-me">
		<div>{message}</div>
	</div>
EOD;

/*
|--------------------------------------------------------------------
| BREADCRUMB Separator
|--------------------------------------------------------------------
| The symbol displayed between elements of the breadcrumb.
|
*/
$config['template.breadcrumb_symbol']	= ' : ';


//--------------------------------------------------------------------
// !ASSETS
//--------------------------------------------------------------------
/*
	The base folder (relative to the template.site_root config setting)
	that all of the assets are stored in. This is used to generate both
	the url and the relative file path.
	
	This should NOT include the trailing slash.
*/
$config['assets.base_folder'] = 'assets';

/*
	The names of the folders for the various assets.
	These default to 'js', 'css', and 'images'. These folders
	are expected to be found directly under the 'assets.base_folder'. 
	
	While searching through themes, these names are also used to
	build alternate folders to look into, under the theme folders.
*/
$config['assets.asset_folders'] = array(
	'css'	=> 'css',
	'js'	=> 'js',
	'image'	=> 'images'
);

/*
	The 'assets.js_opener' and 'assets.js_closer' strings are used
	to wrap all of your inline scripts into. By default, it is
	setup to work with jQuery.
*/
$config['assets.js_opener'] = 'head.ready(function(){'. "\n";
$config['assets.js_closer'] = '});'. "\n";



//--------------------------------------------------------------------
// !PAGES
//--------------------------------------------------------------------

/*
	Specifies the name of the RTE to use for the pages editors, and 
	other editors throughout the system. Defaults to none.
	
	The choices are: 'html', 'markdown', 'textile', 'tinymce'
*/
$config['pages.rte'] = 'html';

/*
	These items set the default settings for new pages within the system.
*/
$config['pages.default_cacheable'] = 1;	// Are pages cacheable by default? 
$config['pages.default_searchable'] = 1;	// Are pages searchable by default?
$config['pages.default_rich_text'] = 1;	// Is the RTE enabled by default?
$config['pages.track_hits'] = 1;		// Should page hits be tracked in the system?
$config['pages.home_page_alias'] = 'page-12';	// The home page
