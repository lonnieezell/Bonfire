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

$lang['em_template']			= 'Plantilla';
$lang['em_email_template']		= 'Plantilla de Correo';
$lang['em_emailer_queue']		= 'Cola de correo';

$lang['em_system_email']		= 'Correo electr&oacute;nico para el sistema';
$lang['em_system_email_note']	= 'Los correo que todo el sistema genere ser&aacute;n enviados desde este correo.';
$lang['em_email_server']		= 'Servidor de Correo';
$lang['em_settings']			= 'Configuraci&oacute;n de Correo';
$lang['em_settings_note']		= '<b>Correo</b> usa la funci&oacute;n est&aacute;ndar de correo de PHP (Mail), no se requiere configuraci&oacute;n.';
$lang['em_location']			= 'ubicaci&oacute;n';
$lang['em_server_address']		= 'Direcci&oacute;n del servidor';
$lang['em_port']				= 'Puerto';
$lang['em_timeout_secs']		= 'Tiempo de espera (segundos)';
$lang['em_email_type']			= 'Tipo de Correo';
$lang['em_test_settings']		= 'Probar la configuraci&oacute;n de Correo';

$lang['em_template_note']		= 'Los correos son enviados en formato HTML. Los puede personalizar modificando el encabezado y el pie de p&aacute;gina.';
$lang['em_header']				= 'Encabezado';
$lang['em_footer']				= 'Pie de p&aacute;gina';

$lang['em_test_header']			= 'Probar su configuraci&oacute;n';
$lang['em_test_intro']			= 'Ingrese una direcci&oacute;n de correo a continuaci&oacute;n, para verificar que su configuraci&oacute;n de correo esta funcionando.<br/>Por favor guarde la configuraci&oacute;n actual antes de hacer la prueba.';
$lang['em_test_button']			= 'Enviar un correo de prueba';
$lang['em_test_result_header']	= 'Resultados de la prueba';
$lang['em_test_no_results']		= 'O la prueba no se ha ejecutado o no devuelve ning&uacute;n resultado.';
$lang['em_test_debug_header']	= 'Informaci&oacute;n de depuraci&oacute;n';
$lang['em_test_success']		= 'El correo parece estar configurado correctamente. Si no ve el correo en su buz&oacute;n, intente buscando en la bandeja de spam o detry looking in your Spam box or de correo basura.';
$lang['em_test_error']			= 'El correo parece no estar configurado correctamente.';

$lang['em_test_mail_subject']	= 'Felicitaciones! su sistema de env&iacute;o de correo esta funcionando!';
$lang['em_test_mail_body']		= 'Si esta viendo este correo es porque parece que su sistema de env&iacute;o de correo esta funcionando!';

$lang['em_stat_no_queue']		= 'Actualmente no tiene ning&uacute;n correo en cola.';
$lang['em_total_in_queue']		= 'Total de correos en cola:';
$lang['em_total_sent']			= 'Total de correos enviados:';

$lang['em_sent']				= 'Enviar';
$lang['em_attempts']			= 'Intentos';
$lang['em_id']					= 'ID';
$lang['em_to']					= 'para';
$lang['em_subject']				= 'Asunto';

$lang['em_missing_data']		= 'Uno o m&aacute;s campos requeridos faltan.';
$lang['em_no_debug']			= 'El correo se puso en cola. No hay informaci&oacute;n de depuraci&oacute;n disponible.';

$lang['em_delete_success']      = '(x) registros eliminados.';
$lang['em_delete_failure']		= 'No se puede eliminar el registro : ';
$lang['em_delete_error']		= 'Error eliminando el registro : ';
$lang['em_delete_confirm']		= '&iexcl;Esta seguro de que quiere eliminar este correo? ';
