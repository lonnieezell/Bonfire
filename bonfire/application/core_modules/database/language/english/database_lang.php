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

$lang['db_database_maintenance']			= 'Database Maintenance';
$lang['db_database_backups']				= 'Database Backups';
$lang['db_drop_database_tables']			= 'Drop Database Tables';
$lang['db_backup_database_tables']				= 'Backup Database Tables';

$lang['db_backup_warning']		= 'Note: Due to the limited execution time and memory available to PHP, backing up very large databases may not be possible. If your database is very large you might need to backup directly from your SQL server via the command line, or have your server admin do it for you if you do not have root privileges.';
$lang['db_filename']			= 'File Name';

$lang['db_drop_question']		= 'Add &lsquo;Drop Tables&rsquo; command to SQL?';
$lang['db_drop_tables']			= 'Drop Tables';
$lang['db_compresssion_type']	= 'Compression type';
$lang['db_insert_question']		= 'Add &lsquo;Inserts&rsquo; for data to SQL?';
$lang['db_add_inserts']			= 'Add Inserts';
$lang['db_backup_options']	= 'Backup Options';

$lang['db_restore_note']		= 'The Restore option is only capable of reading un-compressed files. Gzip and Zip compression is good if you just want a backup to download and store on your computer.';

$lang['db_action_apply']				= 'Apply';
$lang['db_compresssion_none']				= 'None';
$lang['db_compresssion_gzip']				= 'gzip';
$lang['db_compresssion_zip']					= 'zip';
$lang['db_action_backup']				= 'Backup';
$lang['db_action_restore']= 'Restore';
$lang['db_action_drop']				= 'Drop';
$lang['db_action_repair']				= 'Repair';
$lang['db_action_optimize']			= 'Optimize';
$lang['db_action_delete_tables']			= 'Drop Table(s)';
$lang['db_action_browse']				= 'Browse';

$lang['db_backup_create_heading']	= 'Create New Backup';
$lang['db_database_restore_heading']	= 'Database Restore';
$lang['db_table_browse_heading']	= 'Browsing the table:';

$lang['db_no_backups']			= 'No previous backups were found.';
$lang['db_backup_delete_confirm']	= 'Really delete the following backup files?';
$lang['db_backup_delete_none']	= 'No backup files were selected for deletion';
$lang['db_drop_confirm']		= 'Really delete the following database tables?';
$lang['db_drop_none']			= 'No tables were selected to drop';
$lang['db_drop_attention']		= '<p>Deleting tables from the database will result in loss of data.</p><p><strong>This may make your application non-functional.</strong></p>';
$lang['db_repair_none']			= 'No tables were selected to repair.';
$lang['db_browse_none']			= 'No table name was provided.';

$lang['db_table_name']			= 'Table Name';
$lang['db_records']				= 'Records';
$lang['db_data_size']			= 'Data Size';
$lang['db_index_size']			= 'Index Size';
$lang['db_data_free']			= 'Data Free';
$lang['db_engine']				= 'Engine';
$lang['db_no_tables']			= 'No tables were found for the current database.';

$lang['db_successful_query']				= 'Successful Query:';
$lang['db_unsuccessful_query']				= 'Unsuccessful Query:';

$lang['db_restore_results']		= 'Restore Results';
$lang['db_back_to_tools']		= 'Back to Database Tools';
$lang['db_restore_file']		= 'Restore database from file %s?';
$lang['db_restore_attention']	= '<p>Restoring a database from a backup file will result in some or all of your database being erased before restoring.</p><p><strong>This may result in a loss of data</strong>.</p>';

$lang['db_sql_query']			= 'SQL Query:';
$lang['db_total_results']		= 'Total Results:';
$lang['db_no_rows']				= 'No data found for table.';

$lang['db_backup_file_delete_success']				= 'The backup file was deleted.';
$lang['db_backup_files_delete_success']				= '%s backup files were deleted.';
$lang['db_backup_file_save_success']				= 'Backup file successfully saved. It can be found at %s.';
$lang['db_backup_file_save_failure']				= 'There was a problem saving the backup file.';
$lang['db_backup_file_not_found']				= 'The file <em>%s</em> could not be found.';
$lang['db_backup_file_read_failure']				= 'Could not read the file <em>%s</em>.';
$lang['db_table_drop_success']				= 'The table was successfully dropped.';
$lang['db_tables_drop_success']				= '%s tables were successfully dropped.';
$lang['db_table_repair_success']				= '%s of %s table(s) successfully repaired.';
$lang['db_database_optimize_success']				= 'The database was successfully optimized.';
$lang['db_database_optimize_failure']				= 'Unable to optimize the database.';
$lang['db_database_update_success']				= 'Database updated to the latest version.';
$lang['db_database_update_failure']				= 'Unable to update database schema: ';

/* Sub nav */
$lang['db_s_maintenance']			= 'Maintenance';
$lang['db_s_backups']				= 'Backups';
$lang['db_s_migrations']				= 'Migrations';