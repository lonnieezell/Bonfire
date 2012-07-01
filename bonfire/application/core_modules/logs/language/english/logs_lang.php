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

$lang['logs_no_logs'] 			= 'No logs found.';
$lang['logs_not_enabled']		= 'Logging is not currently enabled.';
$lang['logs_the_following']		= 'Log the following:';
$lang['logs_what_0']				= '0 - Nothing';
$lang['logs_what_1']				= '1 - Error Message (including PHP Errors)';
$lang['logs_what_2']				= '2 - Debug Messages';
$lang['logs_what_3']				= '3 - Information Messages';
$lang['logs_what_4']				= '4 - All Messages';
$lang['logs_what_note']			= 'The higher log values also include all messages from the lower numbers. So, logging 2 - Debug Messages also logs 1 - Error Messages.';

$lang['logs_action_save_settings']		= 'Save Log Settings';
$lang['logs_action_delete_files']		= 'Delete Log Files';
$lang['logs_action_delete_this_file']		= 'Delete This Log File?';
$lang['logs_delete_files']		= 'Delete Log Files';
$lang['logs_delete_file']		= 'Delete This Log File';
$lang['logs_delete_confirm']	= 'Are you sure you want to delete this or these log(s)?';
$lang['logs_delete_all_confirm']	= 'Are you sure you want to delete all log files?';

$lang['logs_big_file_note']		= 'Logging can rapidly create very large files, if you log too much information. For live sites, you should probably log only Errors.';
$lang['logs_delete_note']		= 'Deleting log files is a permanent action. There is no going back, so please make sure you understand what you are doing.';

$lang['logs_not_found']			= 'Either the log file could not be located, or it was empty.';
$lang['logs_viewing']	= 'Viewing:';
$lang['logs_view']	= 'View:';
$lang['logs_show_all_entries']	= 'All entries';
$lang['logs_show_errors']		= 'Errors only';

$lang['logs_date']				= 'Date';
$lang['logs_file']				= 'Filename';
$lang['logs_settings']			= 'Settings';

$lang['logs_title']				= 'System Logs';
$lang['logs_settings_heading']		= 'System Log Settings';
$lang['logs_logs_delete_success']			= 'log file(s) successfull deleted.';
$lang['logs_settings_save_success']			= 'Log settings successfully saved.';
$lang['logs_settings_save_failure']			= 'Unable to save log settings. Check the write permissions on <b>application/config.php</b> and try again.';
$lang['logs_no_log_provided'] 			= 'No log file provided.';

$lang['logs_log_file'] 						= 'log file(s)';
$lang['logs_all_log_files'] 			= 'all log files';
$lang['logs_logs_purge_success']			= 'Successfully purged %s.';

$lang['logs_intro']        = 'These are your error and debug logs....';

//--------------------------------------------------------------------
// Sub nav
//--------------------------------------------------------------------
$lang['logs_s_logs']				= 'Logs';
$lang['logs_s_settings']			= 'Settings';