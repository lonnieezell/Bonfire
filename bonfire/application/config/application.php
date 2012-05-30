<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['auth.use_extended_profile'] = 0;
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

$config['site.title'] = "";
$config['site.system_email'] = "";
$config['site.status'] = 1;		// 0 = offline, 1 = online
$config['site.list_limit'] = 25;

//--------------------------------------------------------------------
// !For Update messages
//--------------------------------------------------------------------

$config['updates.do_check'] = 1;		// Whether to check for updates or not.
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
// !CONTEXTS
//--------------------------------------------------------------------

/*
	Contexts provide the main sections of the admin area. Only two are
	required: 'settings' and 'developer'.

	The name of the context displayed in the UI is determined by
	language strings as defined in application_lang.php. The string
	must follow the format: context_content_name.

	The icon displayed is chosen automatically from the
	theme/images/context_context_name.png files.
*/
$config['contexts'] = array( 'content', 'reports', 'settings', 'developer' );


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
$config['template.default_theme']	= 'default/';

/*
|--------------------------------------------------------------------
| ADMIN THEME
|--------------------------------------------------------------------
| This is the folder name that contains the default admin theme to use
|
*/
$config['template.admin_theme'] = 'admin';

/*
|--------------------------------------------------------------------
| PARSE VIEWS
|--------------------------------------------------------------------
| If set to TRUE, views will be parsed via CodeIgniter's parser.
| If FALSE, views will be considered PHP views only.
|
*/
$config['template.parse_views']		= FALSE;

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
 <div class="alert alert-block alert-{type} fade in notification">
		<a data-dismiss="alert" class="close" href="#">&times;</a>
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
$config['assets.js_opener'] = '$(document).ready(function(){'. "\n";
$config['assets.js_closer'] = '});'. "\n";

/*
	The 'assets.combine' setting tells the Asset library whether
	files should be combined or not.
*/
$config['assets.combine'] = FALSE;

/*
	The 'assets.encrypt' setting will mask the app structure
	by encrypting the filename of the combined files.

	If false the filename would be in the format...
		theme_module_controller_method
	If true, it would be an md5 hash of the above filename.
*/
$config['assets.encrypt_name'] = FALSE;

/*
	The 'assets.js_minify' and 'assets.css_minify' settings are used to
	tell the ui loader to minify the combined assets or not
*/
$config['assets.js_minify'] = FALSE;
$config['assets.css_minify'] = FALSE;

/*
	The 'assets.encode' setting is used to specify whether the assets should
	be encoded based on the HTTP_ACCEPT_ENCODING value.
*/
$config['assets.encode'] = FALSE;

//--------------------------------------------------------------------
// !Shortcut Keys
//--------------------------------------------------------------------

/*
	Array containing the currently available shortcuts - these are output in the /ui/views/shortcut_keys file
*/
$config['ui.current_shortcuts'] = array(
	'form_save'      => array('description' => 'Save any form in the admin area.', 'action' => '$("input[name=submit]").click();return false;'),
	'create_new'     => array('description' => 'Create a new record in the module.', 'action' => 'document.location=$("a#create_new").attr("href");'),
	'select_all'     => array('description' => 'Select all records in an index page.', 'action' => '$("table input[type=checkbox]").click();return false;'),
	'delete'         => array('description' => 'Delete the record(s).', 'action' => '$("#delete-me.btn-danger").click();'),
	'goto_content'   => array('description' => 'Jump to the Content context.', 'action' => "document.location='/" . SITE_AREA . "/content';"),
	'goto_reports'   => array('description' => 'Jump to the Reports context.', 'action' => "document.location='/" . SITE_AREA . "/reports';"),
	'goto_settings'  => array('description' => 'Jump to the Settings context.', 'action' => "document.location='/" . SITE_AREA . "/settings';"),
);

//--------------------------------------------------------------------
// !Emailer
//--------------------------------------------------------------------
/*
	Setting this option to true writes email content to a local file in the log path for debugging
	using 'development' environments without sendmail such as Windows Desktop servers like WAMP.
*/
$config['emailer.write_to_file'] = false;

//--------------------------------------------------------------------
// !Migrations
//--------------------------------------------------------------------
$config['migrate.auto_core']	= TRUE;
$config['migrate.auto_app']		= FALSE;