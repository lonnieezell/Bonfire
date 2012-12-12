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

$lang['log_no_logs'] 			= 'No logs found.';
$lang['log_not_enabled']		= 'Logging is not currently enabled.';
$lang['log_the_following']		= 'Log the following:';
$lang['log_what_0']				= '0 - Nothing';
$lang['log_what_1']				= '1 - Error Message (including PHP Errors)';
$lang['log_what_2']				= '2 - Debug Messages';
$lang['log_what_3']				= '3 - Information Messages';
$lang['log_what_4']				= '4 - All Messages';
$lang['log_what_note']			= 'The higher log values also include all messages from the lower numbers. So, logging 2 - Debug Messages also logs 1 - Error Messages.';

$lang['log_save_button']		= 'Save Log Settings';
$lang['log_delete_button']		= 'Delete Log Files';
$lang['log_delete1_button']		= 'Delete This Log File?';
$lang['logs_delete_confirm']	= 'Are you sure you want to delete these logs?';
$lang['logs_delete_all_confirm']	= 'Are you sure you want to delete all log files?';

$lang['log_big_file_note']		= 'Logging can rapidly create very large files, if you log too much information. For live sites, you should probably log only Errors.';
$lang['log_delete_note']		= 'Deleting log files is permanent. There is no going back, so please make sure.';
$lang['log_delete1_note']		= 'Deleting log files is a permanent action. There is no going back, so please make sure you understand what you are doing.';
$lang['log_delete_confirm'] = 'Are you sure you want to delete this log file?';

$lang['log_not_found']			= 'Either the log file could not be located, or it was empty.';
$lang['log_show_all_entries']	= 'All entries';
$lang['log_show_errors']		= 'Errors only';

$lang['log_date']				= 'Date';
$lang['log_file']				= 'Filename';
$lang['log_logs']				= 'Logs';
$lang['log_settings']			= 'Settings';

$lang['log_title']				= 'System Logs';
$lang['log_title_settings']		= 'System Log Settings';
$lang['log_deleted']			= '%d log files deleted';
$lang['log_filter_label'] = 'View';
