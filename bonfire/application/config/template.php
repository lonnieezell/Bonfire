<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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
