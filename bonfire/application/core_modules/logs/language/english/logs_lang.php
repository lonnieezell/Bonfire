<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

$lang['log_big_file_note']		= 'Logging can rapidly create very large files, if you log too much information. For live sites, you should probably log only Errors.';
$lang['log_delete_note']		= '<h3>Delete all log files?</h3><p>Deleting log files is permanent. There is no going back, so please make sure.</p>';

$lang['log_not_found']			= 'Either the log file could not be located, or it was empty.';
$lang['log_show_all_entries']	= 'All entries';
$lang['log_show_errors']		= 'Errors only';