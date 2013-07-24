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

$lang['in_need_db_settings']		= 'Unable to locate proper database settings. Please verify settings and reload the page.';
$lang['in_need_database']			= 'The Database does not seem to exist. Please create the database and reload the page.';

$lang['in_intro']					= '<h2>Welcome to Bonfire</h2><p>Please verify the system requirements below, then click "Next" to get started.</p>';
$lang['in_not_writeable_heading']	= 'Files/Folders Not Writeable';

$lang['in_php_version']				= 'PHP Version';
$lang['in_curl_enabled']			= 'cURL Enabled?';
$lang['in_enabled']					= 'Enabled';
$lang['in_disabled']				= 'Disabled';
$lang['in_folders']					= 'Writeable Folders';
$lang['in_files']					= 'Writeable Files';
$lang['in_writeable']				= 'Writeable';
$lang['in_not_writeable']			= 'Not Writeable';
$lang['in_bad_permissions']			= 'Please correct the issues above and refresh this page to continue.';

$lang['in_writeable_directories_message'] = 'Please ensure that the following directories are writeable, and try again';
$lang['in_writeable_files_message']       = 'Please ensure that the following files are writeable, and try again';

$lang['in_db_settings']				= 'Database Settings';
$lang['in_db_settings_note']		= '<p>Please fill out the database information below.</p>';
$lang['in_environment_note']		= '<p class="small">These settings will be saved to both the main <b>config/database.php</b> file and to the appropriate environment (i.e. <b>config/development/database.php)</b>. </p>';
$lang['in_db_not_available']		= 'Unable to find database.';
$lang['in_db_connect']				= 'Database settings OK';
$lang['in_db_no_connect']           = 'Invalid database settings.';
$lang['in_db_setup_error']          = 'There was an error setting up your database';
$lang['in_db_settings_error']       = 'There was an error inserting settings into the database';
$lang['in_db_account_error']        = 'There was an error creating your account in the database';
$lang['in_settings_save_error']     = 'There was an error saving the settings. Please verify that your database and %s/database config files are writeable.';
$lang['in_db_no_session']			= 'Unable to retrieve your database information from the session.';
$lang['in_user_no_session']			= 'Unable to retrieve your account information from the session.';
$lang['in_db_config_error']			= 'We encountered an error trying to write to database config settings to {file}.';

$lang['in_environment']				= 'Environment';
$lang['in_environment_dev']				= 'Development';
$lang['in_environment_test']			= 'Testing';
$lang['in_environment_prod']			= 'Production';
$lang['in_host']					= 'Host';
$lang['in_database']				= 'Database';
$lang['in_prefix']					= 'Prefix';
$lang['in_db_driver']				= 'Driver';
$lang['in_port']					= 'Port';

$lang['in_account_heading']			= '<h2>Administrator Account</h2><p>Please provide the following information.</p>';
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

$lang['in_installed']					= 'Bonfire is already installed. Please delete or rename the install folder to';
$lang['in_rename_msg']					= 'If you would like, we can simply rename it for you.';
$lang['in_continue']						= 'Continue';
$lang['in_click']							= 'Click here';

$lang['in_requirements']			= 'Requirements';
$lang['in_account']					= 'Account';
$lang['in_complete']				= 'Install Complete';
$lang['in_complete_heading']		= 'Time to unleash your Ninja coding skills.';
$lang['in_complete_intro']			= 'Bonfire has been installed, and your user account is setup for you. <br/><br/>A file called <b>installed.txt</b> has been created in the config folder. Leave it there and you will not be asked to install again.';
$lang['in_complete_next']			= 'What\'s next?';
$lang['in_complete_visit']			= 'View your';
$lang['in_admin_area']				= 'Admin area';
$lang['in_site_front']				= 'Frontpage';
$lang['in_read']					= 'Read the';
$lang['in_bf_docs']					= 'Bonfire documentation';
$lang['in_ci_docs']					= 'CodeIgniter documentation';
$lang['in_happy_coding']			= 'Happy coding!';

