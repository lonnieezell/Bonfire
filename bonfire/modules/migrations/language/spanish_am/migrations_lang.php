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
 * Language file for migrations module (American Spanish)
 *
 * @package    Bonfire\Modules\Migrations\Language\Spanish_am
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/migrations
 */

$lang['migrations_intro']				= 'Migraciones te ayuda a mantener su base de datos actualizada y sincronizada entre los servidores de desarrollo y producción, proporcionando una manera simple de mantener un control de versiones de su base de datos.';
$lang['migrations_not_enabled']			= 'Migraciones no esta dispobible.';
$lang['migrations_installed_version']	= 'Versión instalada: <b>%d</b>';
$lang['migrations_latest_version']		= 'La última versión disponible: <b>%d</b>';
$lang['migrations_db_not_current']		= 'Su base de datos no esta actualizada.';
$lang['migrations_no_migrations']		= 'No hay mirgraciones disponibles.';

$lang['migrations_migrate_note']		= 'Aplicar las migraciones <b>CAMBIARÁ</b> la estructura de su base de datos, posiblemente terminando en desastre. Si usted no se siente seguro con la migración, verifiquela antes de continuar.';
$lang['migrations_migrate_to']			= 'Migrando la base de datos a la versión';
$lang['migrations_choose_migration']	= 'Migrando a la versión:';
$lang['migrations_migrate_button']		= 'Migrar base de datos';

$lang['migrations_app_migrations']		= 'Migración de Aplicaciones';
$lang['migrations_core_migrations']		= 'Migración de Bonfire Core';
$lang['migrations_mod_migrations']		= 'Migración de Módulos';