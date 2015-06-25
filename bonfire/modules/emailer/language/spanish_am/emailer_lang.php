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
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Emailer language file (American Spanish)
 *
 * Localization strings used by Bonfire's Emailer module.
 *
 * @package Bonfire\Modules\Emailer\Language\Spanish_Am
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer
 */

$lang['emailer_template']                = 'Plantilla';
$lang['emailer_email_template']          = 'Plantilla de Correo';
$lang['emailer_emailer_queue']           = 'Cola de correo';

$lang['emailer_system_email']            = 'Correo electrónico para el sistema';
$lang['emailer_system_email_note']       = 'Los correo que todo el sistema genere serán enviados desde este correo.';
$lang['emailer_email_server']            = 'Servidor de Correo';
$lang['emailer_settings']                = 'Configuración de Correo';
$lang['emailer_settings_note']           = '<b>Correo</b> usa la función estándar de correo de PHP (Mail), no se requiere configuración.';
$lang['emailer_location']                = 'ubicación';
$lang['emailer_server_address']          = 'Dirección del servidor';
$lang['emailer_port']                    = 'Puerto';
$lang['emailer_timeout_secs']            = 'Tiempo de espera (segundos)';
$lang['emailer_email_type']              = 'Tipo de Correo';
$lang['emailer_test_settings']           = 'Probar la configuración de Correo';

$lang['emailer_template_note']           = 'Los correos son enviados en formato HTML. Los puede personalizar modificando el encabezado y el pie de página.';
$lang['emailer_header']                  = 'Encabezado';
$lang['emailer_footer']                  = 'Pie de página';

$lang['emailer_test_header']             = 'Probar su configuración';
$lang['emailer_test_intro']              = 'Ingrese una dirección de correo a continuación, para verificar que su configuración de correo esta funcionando.<br/>Por favor guarde la configuración actual antes de hacer la prueba.';
$lang['emailer_test_button']             = 'Enviar un correo de prueba';
$lang['emailer_test_result_header']      = 'Resultados de la prueba';
$lang['emailer_test_debug_header']       = 'Información de depuración';
$lang['emailer_test_success']            = 'El correo parece estar configurado correctamente. Si no ve el correo en su buzón, intente buscando en la bandeja de spam o detry looking in your Spam box or de correo basura.';
$lang['emailer_test_error']              = 'El correo parece no estar configurado correctamente.';

$lang['emailer_test_mail_subject']       = 'Felicitaciones! su sistema de envío de correo esta funcionando!';
$lang['emailer_test_mail_body']          = 'Si esta viendo este correo es porque parece que su sistema de envío de correo esta funcionando!';

$lang['emailer_stat_no_queue']           = 'Actualmente no tiene ningún correo en cola.';
$lang['emailer_total_in_queue']          = 'Total de correos en cola:';
$lang['emailer_total_sent']              = 'Total de correos enviados:';

$lang['emailer_sent']                    = 'Enviar';
$lang['emailer_attempts']                = 'Intentos';
$lang['emailer_id']                      = 'ID';
$lang['emailer_to']                      = 'para';
$lang['emailer_subject']                 = 'Asunto';

$lang['emailer_missing_data']            = 'Uno o más campos requeridos faltan.';
$lang['emailer_no_debug']                = 'El correo se puso en cola. No hay información de depuración disponible.';

$lang['emailer_delete_success']          = '%d registros eliminados.';
$lang['emailer_delete_failure']          = 'No se puede eliminar el registro: %s';
$lang['emailer_delete_error']            = 'Error eliminando el registro: %s';
$lang['emailer_delete_confirm']          = '¡Esta seguro de que quiere eliminar este correo? ';

// $lang['emailer_create_email']         = 'Send New Email';
// $lang['emailer_create_setting']       = 'Email Configure';
// $lang['emailer_create_email_error']   = 'Error in creating emails: %s';
// $lang['emailer_create_email_success'] = 'Email(s) are inserted into email queue.';
// $lang['emailer_create_email_failure'] = 'Fail in creating emails: %s';

$lang['form_validation_emailer_system_email']  = 'Correo electrónico para el sistema';
$lang['form_validation_emailer_email_server']  = 'Servidor de Correo';
// $lang['form_validation_emailer_sendmail_path'] = 'Sendmail Path';
// $lang['form_validation_emailer_smtp_address']  = 'SMTP Server Address';
// $lang['form_validation_emailer_smtp_username'] = 'SMTP Username';
// $lang['form_validation_emailer_smtp_password'] = 'SMTP Password';
// $lang['form_validation_emailer_smtp_port']     = 'SMTP Port';
// $lang['form_validation_emailer_smtp_timeout']  = 'SMTP timeout';
