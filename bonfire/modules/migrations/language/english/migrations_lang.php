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
 * @filesource
 */

/**
 * Language file for migrations module (English)
 *
 * @package    Bonfire\Modules\Migrations\Language\English
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/migrations
 */

$lang['migrations_intro']				= 'Migrations help you keep your database up to date and synced between development and production servers by providing a simple way to keep &lsquo;version control&rsquo; on your database.';
$lang['migrations_not_enabled']			= 'Migrations are not enabled.';
$lang['migrations_installed_version']	= 'Installed Version: <strong>%d</strong>';
$lang['migrations_latest_version']		= 'Latest Available Version: <strong>%d</strong>';
$lang['migrations_db_not_current']		= 'Your database is not up to date.';
$lang['migrations_no_migrations']		= 'There are no available migrations.';

$lang['migrations_migrate_note']		= 'Performing migrations <strong>WILL</strong> change your database structure, possibly ending in disaster. If you are not comfortable with your migrations, please verify them before continuing.';
$lang['migrations_migrate_to']			= 'Migrate database to version';
$lang['migrations_choose_migration']	= 'Migrate to version:';
$lang['migrations_migrate_button']		= 'Migrate Database';
$lang['migrations_migrate_module']		= 'Migrate Module';

$lang['migrations_app_migrations']		= "Application Migrations";
$lang['migrations_core_migrations']		= "Bonfire Core Migrations";
$lang['migrations_mod_migrations']		= "Module Migrations";

$lang['migrations_tbl_module']			= 'Module';
$lang['migrations_tbl_installed_ver']	= 'Installed Version';
$lang['migrations_tbl_latest_ver']	    = 'Latest Version';

$lang['migrations_uninstall']			= 'Uninstall';

$lang['migrations_tab_app']				= 'Application';
$lang['migrations_tab_mod']				= 'Modules';
$lang['migrations_tab_core']			= 'Bonfire';

$lang['migrations_title_index']         = 'Database Migrations';
$lang['migrations_uninstall_success']   = "Successfully uninstalled module's migrations.";
$lang['migrations_act_uninstall_success']   = 'Migrate Type: %s Uninstalled Version: %s from: %s';
$lang['migrations_migrate_success']     = 'Successfully migrated database to version %s';
$lang['migrations_act_migrate_success'] = 'Migrate Type: %s to Version: %s from: %s';
$lang['migrations_migrate_error']       = 'There was an error migrating the database.';
$lang['migrations_module_none']         = 'No version selected for migration.';
$lang['migrations_act_module']          = 'Migration module: %s Version: %s from: %s';