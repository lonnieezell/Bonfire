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
 * Language file for the Database Module (American Spanish)
 *
 * @package    Bonfire\Modules\Database\Language\Spanish_am
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs
 */

$lang['database_maintenance']           = 'Mantenimiento';
$lang['database_backups']               = 'Respaldo';

$lang['database_backup_warning']        = 'Nota: debido al limite de tiempo de ejecución y de memoria disponible en PHP, respaldar bases de datos muy grandes podría no ser posible. Si su base de datos es muy grande, deberá hacer el respaldo directamente desde servidor SQL a través de la línea de comandos, o solicitar al administrador del servidor que lo haga por ti si no tienes los permisos suficientes de acceso.';
$lang['database_filename']              = 'Nombre de archivo';

$lang['database_drop_question']         = 'Agregar el comando &lsquo;Drop Tables&rsquo; a la consulta SQL?';
$lang['database_drop_tables']           = 'Eliminar tablas';
$lang['database_compress_question']     = '¿Tipo de compresión?';
$lang['database_compress_type']         = 'Tipo de compresión';
$lang['database_insert_question']       = 'Agregar &lsquo;Inserts&rsquo; para los datos de la consulta SQL?';
$lang['database_add_inserts']           = 'Agregar Inserts';

$lang['database_restore_note']          = 'La opción de restaurar solo esta disponible para archivos no comprimidos. La compresión Gzip y Zip es indicada para realizar espaldos para descargar y almacenar en su computador.';

$lang['database_apply']                 = 'Aplicar';
$lang['database_gzip']                  = 'gzip';
$lang['database_zip']                   = 'zip';
$lang['database_backup']                = 'Respaldar';
$lang['database_tables']                = 'Tablas';
$lang['database_restore']               = 'Restaurar';
$lang['database_database']              = 'Base de datos';
$lang['database_drop']                  = 'Eliminar';
$lang['database_repair']                = 'Reparar';
$lang['database_optimize']              = 'Optimizar';
$lang['database_migrations']            = 'Migración';

$lang['database_delete_note']           = 'Eliminar los archivos de respaldo seleccionados: ';
$lang['database_no_backups']            = 'No se encontraron respaldos anteriores.';
$lang['database_backup_delete_confirm'] = '¡Realmente desea eliminar los siguientes archivos de respaldo?';
$lang['database_backup_delete_none']    = 'No se selecciono ningún archivo de respaldo para su eliminación';
$lang['database_drop_confirm']          = '¿Realmente desea eliminar las siguientes tablas?';
$lang['database_drop_none']             = 'No ha seleccionado ninguna tabla para eliminar';
$lang['database_drop_attention']        = '<p>Eliminar tablas de la base de datos llevará a la perdida de datos.</p><p><strong>Esto podría hacer que su aplicación no funciona correctamente.</strong></p>';
$lang['database_repair_none']           = 'No ha seleccionado ninguna tabla para reparar';

$lang['database_table_name']        = 'Nombre de la tabla';
$lang['database_records']           = 'Registros';
$lang['database_data_size']         = 'Tamaño de los datose';
$lang['database_index_size']        = 'Tamaño del índice';
$lang['database_data_free']         = 'Datos libres';
$lang['database_engine']            = 'Motor';
$lang['database_no_tables']         = 'No se encontraron tablas en la base de datos actual.';

$lang['database_restore_results']   = 'Restaurar los resultados';
$lang['database_back_to_tools']     = 'Regresar a las herramientas de la base de datos';
$lang['database_restore_file']      = 'Restaurar la base de datos desde un archivo';
$lang['database_restore_attention'] = '<p>La restauración de una base de datos desde un archivo de respaldo podría llevar a que una parte o la totalidad de su base de datos sea borrada antes de restaurar.</p><p><strong>Esto podría llevar a la perdida de datos</strong>.</p>';

$lang['database_database_settings']     = 'Configuración de la base de datos';
$lang['database_server_type']           = 'Tipo de Servidor';
$lang['database_hostname']              = 'Nombre del servidor';
$lang['database_dbname']                = 'Nombre de la base de datos';
$lang['database_advanced_options']      = 'Opciones Avanzadas';
$lang['database_persistent_connect']    = 'Conexión persistente';
$lang['database_display_errors']        = 'Mostrar los errores de la base de datos';
$lang['database_enable_caching']        = 'Activar el almacenamiento en cache de las consultas';
$lang['database_cache_dir']             = 'Carpeta para elcache';
$lang['database_prefix']                = 'Prefijo';

$lang['database_servers']           = 'Servidores';
$lang['database_driver']            = 'Controladores';
$lang['database_persistent']        = 'Persistente';
$lang['database_debug_on']          = 'Modo depuración activado';
$lang['database_strict_mode']       = 'Modo estricto';
$lang['database_running_on_1']      = 'Actualmente se esta ejecutando desde ';
$lang['database_running_on_2']      = 'servidor.';
$lang['database_serv_dev']          = 'Desarrollo';
$lang['database_serv_test']         = 'Pruebas';
$lang['database_serv_prod']         = 'Producción';

$lang['database_successful_save']       = 'Su configuración ha sido guardada.';
$lang['database_erroneous_save']        = 'Hubo un error al guardar su configuración.';
$lang['database_successful_save_act']   = 'La configuración de la base de datos ha sido guardada';
$lang['database_erroneous_save_act']    = 'La configuración de la base de datos no se guardo correctamente';

$lang['database_sql_query']         = 'Consulta SQL';
$lang['database_total_results']     = 'Resultados totales';
$lang['database_no_rows']           = 'No se encontraron datos en la tabla.';
$lang['database_browse']            = 'Explorar';
