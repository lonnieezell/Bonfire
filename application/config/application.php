<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT    The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Used to set the default timezone for the user registration page
 */
$config['site.default_user_timezone'] = 'UM8';

//------------------------------------------------------------------------------
// Module Locations
//------------------------------------------------------------------------------
// These paths are checked in the order listed when attempting to locate a module,
// whether loading a library, helper, or routes file.
//
$config['modules_locations'] = array(
    realpath(APPPATH) . '/modules/' => '../../application/modules/',
    realpath(BFPATH) . '/modules/' => '../../bonfire/modules/',
);

//------------------------------------------------------------------------------
// !BACKUPS
//------------------------------------------------------------------------------

// A folder to which all backup files will be written. This should be relative
// to APPPATH. This is primarily used by MY_Config when writing new files.
$config['site.backup_folder']	= 'archives/';

//------------------------------------------------------------------------------
// !CONTEXTS
//------------------------------------------------------------------------------

// Contexts provide the main sections of the admin area.
// Only two are required: 'settings' and 'developer'.
// The name of the context displayed in the UI is determined by language strings
// as defined in application_lang.php. The string must follow the format:
//      context_content_name
// The icon displayed is chosen automatically from the file:
//      theme/images/context_context_name.png
$config['contexts'] = array('content','reports','settings','developer');

//------------------------------------------------------------------------------
// ACTIVITIES
//------------------------------------------------------------------------------

// If true, will log activities to the database using the activity_model's
// log_activity method. If this is false, you can remove the Activity module
// without repurcussions.
$config['enable_activity_logging'] = true;

//------------------------------------------------------------------------------
// SPARKS
//------------------------------------------------------------------------------
$config['sparks_path'] = '../sparks/';

//------------------------------------------------------------------------------
// !TEMPLATE
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Paths
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// SITE PATH
//------------------------------------------------------------------------------
// The path to the root folder that holds the application. This does not have to
// be the site root folder, or even the folder defined in FCPATH.
$config['template.site_path']	= FCPATH;

//------------------------------------------------------------------------------
// THEME PATHS
//------------------------------------------------------------------------------
// An array of folders to look in for themes. There must be at least one folder
// path at all times, to serve as the fall-back for when a theme isn't found.
// Paths are relative to the FCPATH.
$config['template.theme_paths'] = array('themes');

//------------------------------------------------------------------------------
// Layouts
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// DEFAULT LAYOUT
//------------------------------------------------------------------------------
// This is the name of the default layout used if no others are specified.
// NOTE: do not include an ending ".php" extension.
$config['template.default_layout'] = "index";

//------------------------------------------------------------------------------
// DEFAULT AJAX LAYOUT
//------------------------------------------------------------------------------
// This is the name of the default layout used when the page is displayed via an
// AJAX call.
// NOTE: do not include an ending ".php" extension.
$config['template.ajax_layout'] = 'ajax';

//------------------------------------------------------------------------------
// Themes
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// USE MOBILE THEMES?
//------------------------------------------------------------------------------
// When set to true, the Template library will check the user agent during the
// rendering process, and check the UA against the template.themes (below),
// allowing you to create mobile versions of your site, and versions targetted
// specifically at a single type of phone (ie, Blackberry or iPhone).
// NOTE: when rendering, if the file doesn't exist in the targetted theme, the
// Template library then checks the default site for the same file.
$config['template.use_mobile_themes'] = false;

//------------------------------------------------------------------------------
// DEFAULT THEME
//------------------------------------------------------------------------------
// This is the folder name that contains the default theme to use when
// 'template.use_mobile_themes' is set to true.
$config['template.default_theme']	= 'default/';

//------------------------------------------------------------------------------
// ADMIN THEME
//------------------------------------------------------------------------------
// This is the folder name that contains the default admin theme to use
$config['template.admin_theme'] = 'admin';

//------------------------------------------------------------------------------
// Templates
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// MESSAGE TEMPLATE
//------------------------------------------------------------------------------
// This is the template that the Template library will use when displaying
// messages through the message() function.
// To set the class for the type of message (error, success, etc), the {type}
// placeholder will be replaced. The message will replace the {message}
// placeholder.
$config['template.message_template'] =<<<EOD
 <div class="alert alert-{type} alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<div>{message}</div>
	</div>
EOD;

//------------------------------------------------------------------------------
// BREADCRUMB Separator - The symbol displayed between the breadcrumb elements.
//------------------------------------------------------------------------------
$config['template.breadcrumb_symbol']	= ' : ';

//------------------------------------------------------------------------------
// Settings
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// PARSE VIEWS
//------------------------------------------------------------------------------
// If set to true, views will be parsed via CodeIgniter's parser.
// If false, views will be considered PHP views only.
$config['template.parse_views'] = false;

//------------------------------------------------------------------------------
// !ASSETS
//------------------------------------------------------------------------------

// The names of the directories for the various assets.
//  'base' is relative to public, all others are relative to 'base', except
//  'module', which defines a directory name within both 'css' and 'js'.
// Trailing and preceding slashes are removed
$config['assets.directories'] = array(
    'base'   => 'assets',
    'cache'  => 'cache',
    'css'    => 'css',
    'image'  => 'images',
    'js'     => 'js',
    'module' => 'module',
);

// The 'assets.js_opener' and 'assets.js_closer' strings are used to wrap all
// inline scripts. By default, these are setup to work with jQuery.
$config['assets.js_opener'] = "$(document).ready(function() {";
$config['assets.js_closer'] = "});";

// The 'assets.css_combine' and 'assets.js_combine' settings tell the Assets
// library whether css and js files, respectively, should be combined.
$config['assets.css_combine'] = false;
$config['assets.js_combine']  = false;

// The 'assets.css_minify' and 'assets.js_minify' settings are used to tell the
// Assets library to minify the combined css and js files, respectively
$config['assets.css_minify'] = true;
$config['assets.js_minify']  = true;

// The 'assets.encrypt' setting will mask the app structure by encrypting the
// filename of the combined files.
// If false the filename would be in the format...
//      theme_module_controller_method
// If true, it would be an md5 hash of the above filename.
$config['assets.encrypt_name'] = false;

// The 'assets.encode' setting is used to specify whether the assets should be
// encoded based on the HTTP_ACCEPT_ENCODING value.
$config['assets.encode'] = false;

//------------------------------------------------------------------------------
// Deprecated assets config items
// (Use 'assets.directories' instead)
//------------------------------------------------------------------------------

// The base folder (relative to the template.site_root config setting) that all
// of the assets are stored in. This is used to generate both the url and the
// relative file path.
// This should NOT include the trailing slash.
$config['assets.base_folder'] = 'assets';

// The names of the folders for the various assets.
// These default to 'js', 'css', and 'images'.
// These folders are expected to be found directly under the
// 'assets.base_folder'.
// While searching through themes, these names are also used to build alternate
// folders to look into, under the theme folders.
$config['assets.asset_folders'] = array(
	'css'	=> 'css',
	'js'	=> 'js',
	'image'	=> 'images'
);

//------------------------------------------------------------------------------
// End deprecated assets config items
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// !Shortcut Keys
//------------------------------------------------------------------------------

// Array containing the currently available shortcuts
// - these are output in the /ui/views/shortcut_keys file
$config['ui.current_shortcuts'] = array(
	'form_save'      => array('description' => 'Save any form in the admin area.', 'action' => '$("input[name=save]").click();return false;'),
	'create_new'     => array('description' => 'Create a new record in the module.', 'action' => 'window.location.href=$("a#create_new").attr("href");'),
	'select_all'     => array('description' => 'Select all records in an index page.', 'action' => '$("table input[type=checkbox]").click();return false;'),
	'delete'         => array('description' => 'Delete the record(s).', 'action' => '$("#delete-me.btn-danger").click();'),
	'module_index'   => array('description' => 'Return to the index of the current module.', 'action' => 'window.location.href=$("a#list").attr("href");'),
	'goto_content'   => array('description' => 'Jump to the Content context.', 'action' => 'window.location.href=$("#tb_content").attr("href")'),
	'goto_reports'   => array('description' => 'Jump to the Reports context.', 'action' => 'window.location.href=$("#tb_reports").attr("href")'),
	'goto_settings'  => array('description' => 'Jump to the Settings context.', 'action' => 'window.location.href=$("#tb_settings").attr("href")'),
	'goto_developer' => array('description' => 'Jump to the Developer context.', 'action' => 'window.location.href=$("#tb_developer").attr("href")'),
);

//------------------------------------------------------------------------------
// !Emailer
//------------------------------------------------------------------------------

// Setting this option to true writes email content to a local file in the log
// path for debugging. This is helpful when using 'development' environments
// without sendmail such as Windows Desktop servers like WAMP.
$config['emailer.write_to_file'] = false;

//------------------------------------------------------------------------------
// !Migrations
//------------------------------------------------------------------------------
$config['migrate.auto_core'] = false;
$config['migrate.auto_app']  = false;

//------------------------------------------------------------------------------
// CommonMark library used by Docs module
//------------------------------------------------------------------------------
// The valid_drivers array will be merged with the valid_drivers array defined in
// the CommonMark library, so the MarkdownExtended driver will remain useable even
// if it is not included in the configured list.
$config['commonmark.valid_drivers'] = array(
    'Parsedown',
    'Markdown',
    'MarkdownExtra',
    'LeagueCommonMark',
);
// This is the driver configured to be used by the CommonMark library. The driver
// will be loaded from either /bonfire/libraries/CommonMark/drivers/ or
// /application/libraries/CommonMark/drivers/ (capitalization is important on most
// servers). The file name for the driver must be CommonMark_{drivername}.php
$config['commonmark.driver'] = 'MarkdownExtended';
