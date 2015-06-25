<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Language file for the Database Module (English)
 *
 * @package    Bonfire\Modules\Database\Language\English
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs
 */

// Sub_nav titles
$lang['database_backups']                   = 'Backups';
$lang['database_maintenance']               = 'Maintenance';
$lang['database_migrations']                = 'Migrations';

$lang['database_backup']                    = 'Backup';
$lang['database_backup_delete_confirm']     = 'Really delete the following backup files?';
$lang['database_backup_delete_none']        = 'No backup files were selected for deletion';
$lang['database_backup_deleted_count']      = '%s backup files were deleted.';
$lang['database_backup_deleted_error']      = 'One or more files could not be deleted.';
$lang['database_backup_failure_validation'] = 'There was a problem saving the backup file. Validation failed.';
$lang['database_backup_failure_write']      = 'There was a problem saving the backup file. Either the file could not be written, or the directory was not found.';
$lang['database_backup_no_tables']          = 'No tables were selected for backup';
$lang['database_backup_success']            = 'Backup file successfully saved. It can be found at <a href="%s">%s</a>.';
$lang['database_backup_warning']            = 'Note: Due to the limited execution time and memory available to PHP, backing up very large databases may not be possible. If your database is very large you might need to backup directly from your SQL server via the command line, or have your server admin do it for you if you do not have root privileges.';
$lang['database_add_inserts']               = 'Add Inserts';

$lang['database_compress_question']         = 'Compression type?';
$lang['database_compress_type']             = 'Compression type';
$lang['database_gzip']                      = 'gzip';
$lang['database_zip']                       = 'zip';

$lang['database_data_free']                 = 'Data Free';
$lang['database_data_free_unsupported']     = 'N/A';
$lang['database_data_size']                 = 'Data Size';
$lang['database_data_size_unsupported']     = 'N/A';
$lang['database_engine']                    = 'Engine';
$lang['database_index_size']                = 'Index Size';
$lang['database_index_field_unsupported']   = 'N/A';
$lang['database_num_records']               = '# Records';

$lang['database_drop']                      = 'Drop';
$lang['database_drop_attention']            = '<p>Deleting tables from the database will result in loss of data.</p><p><strong>This may make your application non-functional.</strong></p>';
$lang['database_drop_button']               = 'Drop Table(s)';
$lang['database_drop_confirm']              = 'Really delete the following database tables?';
$lang['database_drop_none']                 = 'No tables were selected to drop';
$lang['database_drop_question']             = 'Add &lsquo;Drop Tables&rsquo; command to SQL?';
$lang['database_drop_success_plural']       = '%s tables successfully dropped.';
$lang['database_drop_success_singular']     = '%s table successfully dropped.';
$lang['database_drop_tables']               = 'Drop Tables';
$lang['database_drop_title']                = 'Drop Database Tables';

$lang['database_optimize']                  = 'Optimize';
$lang['database_optimize_failure']          = 'Unable to optimize the database.';
$lang['database_optimize_success']          = 'The database was successfully optimized.';

$lang['database_repair']                    = 'Repair';
$lang['database_repair_none']               = 'No tables were selected to repair';
$lang['database_repair_success']            = '%s of %s tables were successfully repaired.';

$lang['database_restore']                   = 'Restore';
$lang['database_restore_attention']         = '<p>Restoring a database from a backup file will result in some or all of your database being erased before restoring.</p><p><strong>This may result in a loss of data</strong>.</p>';
$lang['database_restore_file']              = "Restore database from file: <span class='filename'>%s</span>?";
$lang['database_restore_note']              = 'The Restore option is only capable of reading un-compressed files. Gzip and Zip compression is good if you just want a backup to download and store on your computer.';
$lang['database_restore_out_successful']    = '<strong class="text-success">Successful Query</strong>: <span class="small">%s</span>';
$lang['database_restore_out_unsuccessful']  = '<strong class="text-error">Unsuccessful Query</strong>: <span class="small">%s</span>';
$lang['database_restore_read_error']        = 'Could not read the file: %s.';
$lang['database_restore_results']           = 'Restore Results';

$lang['database_title_backup_create']       = 'Create New Backup';
$lang['database_title_backups']             = 'Database Backups';
$lang['database_title_maintenance']         = 'Database Maintenance';
$lang['database_title_restore']             = 'Database Restore';

$lang['database_apply']                     = 'Apply';
$lang['database_back_to_tools']             = 'Back to Database Tools';
$lang['database_browse']                    = 'Browse: %s';
$lang['database_filename']                  = 'File Name';
$lang['database_get_backup_error']          = '%s could not be found.';
$lang['database_insert_question']           = 'Add &lsquo;Inserts&rsquo; for data to SQL?';
$lang['database_link_title_download']       = 'Download %s';
$lang['database_link_title_restore']        = 'Restore %s';
$lang['database_no_backups']                = 'No previous backups were found.';
$lang['database_no_rows']                   = 'No data found for table.';
$lang['database_no_table_name']             = 'No table name was provided.';
$lang['database_no_tables']                 = 'No tables were found for the current database.';
$lang['database_sql_query']                 = 'SQL Query';
$lang['database_table_name']                = 'Table Name';
$lang['database_tables']                    = 'Tables';
$lang['database_total_results']             = 'Total Results: %s';

$lang['database_backup_tables'] = 'Backup Tables';

$lang['database_validation_errors_heading'] = 'Please fix the following errors:';
$lang['database_action_unknown']            = 'An unsupported action was selected.';

$lang['form_validation_database_filename'] = 'File Name';
$lang['form_validation_database_tables']   = 'Tables';

// -----------------------------------------------------------------------------
// The remaining items appear to no longer be in use...
// -----------------------------------------------------------------------------
$lang['database_advanced_options']    = 'Advanced Options';
$lang['database_cache_dir']           = 'Cache Directory';
$lang['database_database']            = 'Database';
$lang['database_database_settings']   = 'Database Settings';
$lang['database_dbname']              = 'Database Name';
$lang['database_debug_on']            = 'Debug On';
$lang['database_delete_note']         = 'Delete selected backup files: ';
$lang['database_display_errors']      = 'Display Database Errors';
$lang['database_driver']              = 'Driver';
$lang['database_enable_caching']      = 'Enable Query Caching';
$lang['database_erroneous_save']      = 'There was an error saving the settings.';
$lang['database_erroneous_save_act']  = 'Database settings did not save correctly';
$lang['database_hostname']            = 'Hostname';
$lang['database_persistent']          = 'Persistent';
$lang['database_persistent_connect']  = 'Persistent Connection';
$lang['database_prefix']              = 'Prefix';
$lang['database_records']             = 'Records';
$lang['database_running_on_1']        = 'You are currently running on the';
$lang['database_running_on_2']        = 'server.';
$lang['database_serv_dev']            = 'Development';
$lang['database_serv_prod']           = 'Production';
$lang['database_serv_test']           = 'Testing';
$lang['database_server_type']         = 'Server Type';
$lang['database_servers']             = 'Servers';
$lang['database_strict_mode']         = 'Strict Mode';
$lang['database_successful_save']     = 'Your settings were successfully saved.';
$lang['database_successful_save_act'] = 'Database settings were successfully saved';
