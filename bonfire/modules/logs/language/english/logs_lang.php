<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Logs module language file (English)
 *
 * Localization strings used by Bonfire's Logs module
 *
 * @package    Bonfire\Modules\Logs\Language\English
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/guides
 */

$lang['logs_no_logs'] 			= 'No logs found.';
$lang['logs_not_enabled']		= 'Logging is not currently enabled.';
$lang['logs_the_following']		= 'Log the following:';
$lang['logs_what_0']			= '0 - Nothing';
$lang['logs_what_1']			= '1 - Error Message (including PHP Errors)';
$lang['logs_what_2']			= '2 - Debug Messages';
$lang['logs_what_3']			= '3 - Information Messages';
$lang['logs_what_4']			= '4 - All Messages';
$lang['logs_what_note']			= 'The higher log values also include all messages from the lower numbers. So, logging "2 - Debug Messages" also logs "1 - Error Messages".';

$lang['logs_save_button']		= 'Save Log Settings';
$lang['logs_delete_button']		= 'Delete Log Files';
$lang['logs_delete1_button']	= 'Delete This Log File?';

$lang['logs_big_file_note']		= 'Logging can rapidly create very large files, if you log too much information. For live sites, you should probably log only Errors.';
$lang['logs_delete_note']		= 'Deleting log files is permanent. There is no going back, so please make sure.';
$lang['logs_delete1_note']		= 'Deleting log files is a permanent action. There is no going back, so please make sure you understand what you are doing.';
$lang['logs_delete_confirm']    = 'Are you sure you want to delete this log file?';

$lang['logs_not_found']			= 'Either the log file could not be located, or it was empty.';
$lang['logs_show_all_entries']	= 'All entries';
$lang['logs_show_errors']		= 'Errors only';

$lang['logs_date']				= 'Date';
$lang['logs_file']				= 'Filename';
$lang['logs_logs']				= 'Logs';
$lang['logs_settings']			= 'Settings';

$lang['logs_title']				= 'System Logs';
$lang['logs_title_settings']	= 'System Log Settings';
$lang['logs_deleted']			= '%d log files deleted';
$lang['logs_filter_label']      = 'View';

$lang['logs_delete_confirm']	        = 'Are you sure you want to delete these logs?';
$lang['logs_delete_all_confirm']	    = 'Are you sure you want to delete all log files?';

$lang['logs_act_deleted']               = 'Log file %s deleted from: %s';
$lang['logs_act_deleted_all']           = 'All log files deleted from: %s';
$lang['logs_act_settings_modified']     = 'Log settings modified from: %s';

$lang['logs_deleted_all_success']       = 'Successfully deleted all log files';
$lang['logs_settings_modified_success'] = 'Log settings successfully saved.';
$lang['logs_settings_modified_failure'] = 'Unable to save log settings. Check the write permissions on <strong>application/config/config.php</strong> and try again.';
$lang['logs_view_empty']                = 'No log file provided.';

$lang['logs_viewing']                   = 'Viewing:';