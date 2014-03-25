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

$lang['logs_no_logs'] 			= 'No se encontraron registros.';
$lang['logs_not_enabled']		= 'El sistema de registro no se encuentra activo.';
$lang['logs_the_following']		= 'Registrar lo siguiente:';
$lang['logs_what_0']			= '0 - Nada';
$lang['logs_what_1']			= '1 - Mensajes de Error (incluidos los errores de PHP)';
$lang['logs_what_2']			= '2 - Mensajes de Depuración';
$lang['logs_what_3']			= '3 - Mensajes de Información';
$lang['logs_what_4']			= '4 - Todos los mensajes';
$lang['logs_what_note']			= 'El valor de registro más alto incluye todos los mensajes desde el numero más bajo. Entonces, si se registra a partir de la opción 2 - Mensajes de Depuración también se registrara la opción 1 - Mensjaes de Error.';

$lang['logs_save_button']		= 'Guardar la configuración del Registro';
$lang['logs_delete_button']		= 'Eliminar los archivos de registro';
$lang['logs_delete1_button']	= '¡Eliminar este archivo de registro?';
$lang['logs_delete_confirm']	= '¡Esta seguro de eliminar este registro?';

$lang['logs_big_file_note']		= 'El sistema de registro puede rápidamente crear archivos muy grandes, si su registro tiene demasiada información. Para sitios publicados, debería registrar unicamente errores.';
$lang['logs_delete_note']		= 'La eliminación de archivos de registro es permanente. No hay marcha atrás, así que asegurese de saber lo que hace.';
$lang['logs_delete1_note']		= 'La eliminación de los archivos es una acción permanente. No hay marcha atrás, así que por favor asegurece de entender que es lo que esta haciendo.';
$lang['logs_delete_confirm'] 	= '¿Esta seguro de eliminar el archivo de registro?';

$lang['logs_not_found']			= 'O el archivo de registro no se puede localizar o esta vacío.';
$lang['logs_show_all_entries']	= 'Todas las entradas';
$lang['logs_show_errors']		= 'Sólo Errores';

$lang['logs_date']				= 'Fecha';
$lang['logs_file']				= 'Nombre de archivo';
$lang['logs_logs']				= 'Registros';
$lang['logs_settings']			= 'Configuración';

$lang['logs_title']				= 'Sistema de Registro';
$lang['logs_title_settings']	= 'Configuración del sistema de registro';
$lang['logs_deleted']			= '%d archivos de registro eliminados';
$lang['logs_filter_label'] 		= 'Vistas';
$lang['logs_intro']        		= 'Estos son los errores y mensajes de depuración...';