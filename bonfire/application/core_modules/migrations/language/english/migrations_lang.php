<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$lang['mig_database_migrations']			= 'Database Migrations';
$lang['mig_intro']					= 'Migrations help you keep your database up to date and synced between development and production servers by providing a simple way to keep &lsquo;version control&rsquo; on your database.';
$lang['mig_not_enabled']			= 'Migrations are not enabled.';
$lang['mig_installed_version']		= 'Installed Version:';
$lang['mig_latest_version']			= 'Latest Available Version:';
$lang['mig_db_not_current']			= 'Your database is not up to date.';
$lang['mig_no_migration']			= 'There are no available migrations.';
$lang['mig_mod_module']		= 'Module';
$lang['mig_mod_installed_version']		= 'Installed Version';
$lang['mig_mod_latest_version']			= 'Latest Version';
$lang['mig_mod_no_migration']			= 'No modules have any migrations available.';
$lang['mig_mod_uninstall']			= 'Uninstall';

$lang['mig_class_doesnt_exist']     = 'The %s migration class does not exist';

$lang['mig_migrate_note']			= 'Performing migrations <b>WILL</b> change your database structure, possibly ending in disaster. If you are not comfortable with your migrations, please verify them before continuing.';
$lang['mig_migrate_to']				= 'Migrate database to version';
$lang['mig_choose_migration']		= 'Migrate to version:';
$lang['mig_action_migrate_database']			= 'Migrate Database';
$lang['mig_action_migrate_module']			= 'Migrate Module';
$lang['mig_migrate_database_success']			= 'Successfully migrated database to version %s.';
$lang['mig_migrate_database_failure']			= 'There was an error migrating the database.';
$lang['mig_migrate_module_success']			= 'Successfully uninstalled module\'s migrations.';

$lang['mig_app_migrations']			= 'Application Migrations';
$lang['mig_mod_migrations']			= 'Module Migrations';
$lang['mig_core_migrations']		= 'Bonfire Core Migrations';

$lang['mig_t_application']			= 'Application Migrations';
$lang['mig_t_module']			= 'Module Migrations';
$lang['mig_t_bonfire']		= 'Bonfire Core Migrations';

/* Sub nav */
$lang['db_s_maintenance']			= 'Maintenance';
$lang['db_s_backups']				= 'Backups';
$lang['db_s_migrations']				= 'Migrations';