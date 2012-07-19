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

$lang['db_maintenance']			= 'Mantenimiento';
$lang['db_backups']				= 'Respaldo';

$lang['db_backup_warning']		= 'Nota: debido al limite de tiempo de ejecuci&oacute;n y de memoria disponible en PHP, respaldar bases de datos muy grandes podr&iacute;a no ser posible. Si su base de datos es muy grande, deber&aacute; hacer el respaldo directamente desde servidor SQL a trav&eacute;s de la l&iacute;nea de comandos, o solicitar al administrador del servidor que lo haga por ti si no tienes los permisos suficientes de acceso.';
$lang['db_filename']			= 'Nombre de archivo';

$lang['db_drop_question']		= 'Agregar el comando &lsquo;Drop Tables&rsquo; a la consulta SQL?';
$lang['db_drop_tables']			= 'Eliminar tablas';
$lang['db_compress_question']	= '&iquest;Tipo de compresi&oacute;n?';
$lang['db_compress_type']		= 'Tipo de compresi&oacute;n';
$lang['db_insert_question']		= 'Agregar &lsquo;Inserts&rsquo; para los datos de la consulta SQL?';
$lang['db_add_inserts']			= 'Agregar Inserts';

$lang['db_restore_note']		= 'La opci&oacute;n de restaurar solo esta disponible para archivos no comprimidos. La compresi&oacute;n Gzip y Zip es indicada para realizar espaldos para descargar y almacenar en su computador.';

$lang['db_apply']				= 'Aplicar';
$lang['db_gzip']				= 'gzip';
$lang['db_zip']					= 'zip';
$lang['db_backup']				= 'Respaldar';
$lang['db_tables']				= 'Tablas';
$lang['db_restore']				= 'Restaurar';
$lang['db_database']			= 'Base de datos';
$lang['db_drop']				= 'Eliminar';
$lang['db_repair']				= 'Reparar';
$lang['db_optimize']			= 'Optimizar';
$lang['db_migrations']			= 'Migraci&oacute;n';

$lang['db_delete_note']			= 'Eliminar los archivos de respaldo seleccionados: ';
$lang['db_no_backups']			= 'No se encontraron respaldos anteriores.';
$lang['db_backup_delete_confirm']	= '&iexcl;Realmente desea eliminar los siguientes archivos de respaldo?';
$lang['db_backup_delete_none']	= 'No se selecciono ning&uacute;n archivo de respaldo para su eliminaci&oacute;n';
$lang['db_drop_confirm']		= '&iquest;Realmente desea eliminar las siguientes tablas?';
$lang['db_drop_none']			= 'No ha seleccionado ninguna tabla para eliminar';
$lang['db_drop_attention']		= '<p>Eliminar tablas de la base de datos llevar&aacute; a la perdida de datos.</p><p><strong>Esto podr&iacute;a hacer que su aplicaci&oacute;n no funciona correctamente.</strong></p>';
$lang['db_repair_none']			= 'No ha seleccionado ninguna tabla para reparar';

$lang['db_table_name']			= 'Nombre de la tabla';
$lang['db_records']				= 'Registros';
$lang['db_data_size']			= 'Tama&ntilde;o de los datose';
$lang['db_index_size']			= 'Tama&ntilde;o del &iacute;ndice';
$lang['db_data_free']			= 'Datos libres';
$lang['db_engine']				= 'Motor';
$lang['db_no_tables']			= 'No se encontraron tablas en la base de datos actual.';

$lang['db_restore_results']		= 'Restaurar los resultados';
$lang['db_back_to_tools']		= 'Regresar a las herramientas de la base de datos';
$lang['db_restore_file']		= 'Restaurar la base de datos desde un archivo';
$lang['db_restore_attention']	= '<p>La restauraci&oacute;n de una base de datos desde un archivo de respaldo podr&iacute;a llevar a que una parte o la totalidad de su base de datos sea borrada antes de restaurar.</p><p><strong>Esto podr&iacute;a llevar a la perdida de datos</strong>.</p>';

$lang['db_database_settings']	= 'Configuraci&oacute;n de la base de datos';
$lang['db_server_type']			= 'Tipo de Servidor';
$lang['db_hostname']			= 'Nombre del servidor';
$lang['db_dbname']				= 'Nombre de la base de datos';
$lang['db_advanced_options']	= 'Opciones Avanzadas';
$lang['db_persistant_connect']	= 'Conexi&oacute;n persistente';
$lang['db_display_errors']		= 'Mostrar los errores de la base de datos';
$lang['db_enable_caching']		= 'Activar el almacenamiento en cache de las consultas';
$lang['db_cache_dir']			= 'Carpeta para elcache';
$lang['db_prefix']				= 'Prefijo';

$lang['db_servers']				= 'Servidores';
$lang['db_driver']				= 'Controladores';
$lang['db_persistant']			= 'Persistente';
$lang['db_debug_on']			= 'Modo depuraci&oacute;n activado';
$lang['db_strict_mode']			= 'Modo estricto';
$lang['db_running_on_1']		= 'Actualmente se esta ejecutando desde ';
$lang['db_running_on_2']		= 'servidor.';
$lang['db_serv_dev']			= 'Desarrollo';
$lang['db_serv_test']			= 'Pruebas';
$lang['db_serv_prod']			= 'Producci&oacute;n';

$lang['db_successful_save']		= 'Su configuraci&oacute;n ha sido guardada.';
$lang['db_erroneous_save']		= 'Hubo un error al guardar su configuraci&oacute;n.';
$lang['db_successful_save_act']	= 'La configuraci&oacute;n de la base de datos ha sido guardada';
$lang['db_erroneous_save_act']	= 'La configuraci&oacute;n de la base de datos no se guardo correctamente';

$lang['db_sql_query']			= 'Consulta SQL';
$lang['db_total_results']		= 'Resultados totales';
$lang['db_no_rows']				= 'No se encontraron datos en la tabla.';
$lang['db_browse']				= 'Explorar';
$lang['db_apply']               = 'Aplicar';
