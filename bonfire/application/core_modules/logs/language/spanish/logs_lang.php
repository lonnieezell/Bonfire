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

$lang['log_no_logs'] 			= 'No se encontraron registros.';
$lang['log_not_enabled']		= 'El sistema de registro no se encuentra activo.';
$lang['log_the_following']		= 'Registrar lo siguiente:';
$lang['log_what_0']				= '0 - Nada';
$lang['log_what_1']				= '1 - Mensajes de Error (incluidos los errores de PHP)';
$lang['log_what_2']				= '2 - Mensajes de Depuraci&oacute;n';
$lang['log_what_3']				= '3 - Mensajes de Informaci&oacute;n';
$lang['log_what_4']				= '4 - Todos los mensajes';
$lang['log_what_note']			= 'El valor de registro m&aacute;s alto incluye todos los mensajes desde el numero m&aacute;s bajo. Entonces, si se registra a partir de la opci&oacute;n 2 - Mensajes de Depuraci&oacute;n tambi&eacute;n se registrara la opci&oacute;n 1 - Mensjaes de Error.';

$lang['log_save_button']		= 'Guardar la configuraci&oacute;n del Registro';
$lang['log_delete_button']		= 'Eliminar los archivos de registro';
$lang['log_delete1_button']		= '&iexcl;Eliminar este archivo de registro?';
$lang['logs_delete_confirm']	= '&iexcl;Esta seguro de eliminar este registro?';

$lang['log_big_file_note']		= 'El sistema de registro puede r&aacute;pidamente crear archivos muy grandes, si su registro tiene demasiada informaci&oacute;n. Para sitios publicados, deber&iacute;a registrar unicamente errores.';
$lang['log_delete_note']		= 'La eliminaci&oacute;n de archivos de registro es permanente. No hay marcha atr&aacute;s, as&iacute; que asegurese de saber lo que hace.';
$lang['log_delete1_note']		= 'La eliminaci&oacute;n de los archivos es una acci&oacute;n permanente. No hay marcha atr&aacute;s, as&iacute; que por favor asegurece de entender que es lo que esta haciendo.';
$lang['log_delete_confirm'] 	= '&iquest;Esta seguro de eliminar el archivo de registro?';

$lang['log_not_found']			= 'O el archivo de registro no se puede localizar o esta vac&iacute;o.';
$lang['log_show_all_entries']	= 'Todas las entradas';
$lang['log_show_errors']		= 'S&oacute;lo Errores';

$lang['log_date']				= 'Fecha';
$lang['log_file']				= 'Nombre de archivo';
$lang['log_logs']				= 'Registros';
$lang['log_settings']			= 'Configuraci&oacute;n';

$lang['log_title']				= 'Sistema de Registro';
$lang['log_title_settings']		= 'Configuraci&oacute;n del sistema de registro';
$lang['log_deleted']			= 'Archivos de registro eliminados';
$lang['log_filter_label'] 		= 'Vistas';
$lang['log_intro']        		= 'Estos son los errores y registros de depuraci&oacute;n....';
