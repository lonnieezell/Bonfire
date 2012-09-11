<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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

$lang['in_intro']					= '<h2>Welcome</h2><p>Welcome to the Bonfire installation process! Just fill in the fields below, and before you know it you will be creating CodeIgniter 2.1 based web apps faster than ever.</p>';
$lang['in_not_writeable_heading']	= 'Files/Folders Not Writeable';

$lang['in_writeable_directories_message'] = 'Please ensure that the following directories are writeable, and try again';
$lang['in_writeable_files_message']       = 'Please ensure that the following files are writeable, and try again';

$lang['in_db_settings']				= 'Database Settings';
$lang['in_db_settings_note']		= '<p>Please fill out the database information below.</p><p class="small">These settings will be saved to both the main <b>config/database.php</b> file and to the development environment (found at <b>config/development/database.php)</b>. </p>';
$lang['in_db_no_connect']           = 'The installer could not connect to the MySQL server or the database, be sure to enter the correct information.';
$lang['in_db_setup_error']          = 'There was an error setting up your database';
$lang['in_db_settings_error']       = 'There was an error inserting settings into the database';
$lang['in_db_account_error']        = 'There was an error creating your account in the database';
$lang['in_settings_save_error']     = 'There was an error saving the settings. Please verify that your database and %s/database config files are writeable.';

$lang['in_environment']				= 'Environment';
$lang['in_host']					= 'Host';
$lang['in_database']				= 'Database';
$lang['in_prefix']					= 'Prefix';
$lang['in_test_db']					= 'Test Database';

$lang['in_account_heading']			= '<h2>Information Needed</h2><p>Please provide the following information.</p>';
$lang['in_site_title']				= 'Site Title';
$lang['in_username']			    = 'Username';
$lang['in_password']			    = 'Password';
$lang['in_password_note']			= 'Minimum length: 8 characters.';
$lang['in_password_again']			= 'Password (again)';
$lang['in_email']					= 'Your Email';
$lang['in_email_note']				= 'Please double-check your email before continuing.';
$lang['in_install_button']			= 'Install Bonfire';

$lang['in_curl_disabled']			= '<p class="error">cURL <strong>is not</strong> presently enabled as a PHP extension. Bonfire will not be able to check for updates until it is enabled.</p>';

$lang['in_success_notification']    = 'You are good to go! Happy coding!';
$lang['in_success_rebase_msg']		= 'Please set the .htaccess RewriteBase setting to: RewriteBase ';
$lang['in_success_msg']				= 'Please remove the install folder and return to ';

$lang['no_migrations_found']			= 'No migration files were found';
$lang['multiple_migrations_version']	= 'Multiple migrations version: %d';
$lang['multiple_migrations_name']		= 'Multiple migrations name: %s';
$lang['migration_class_doesnt_exist']	= 'Migration class does not exist: %s';
$lang['wrong_migration_interface']		= 'Wrong migration interface: %s';
$lang['invalid_migration_filename']		= 'Wrong migration filename: %s - %s';

$lang['in_installed']					= 'Bonfire is already installed. Please delete or rename the install folder to';
$lang['in_rename_msg']					= 'If you would like, we can simply rename it for you.';
$lang['continue']						= 'continue';
$lang['click']							= 'Click here';