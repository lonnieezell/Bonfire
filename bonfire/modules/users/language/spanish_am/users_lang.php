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

$lang['us_account_deleted']			= 'Desafortunadamente su cuenta ha sido eliminada. Si aún no ha sido purgada<strong>tal vez</strong> pueda ser restaurada. Contacte al administrador en %s.';

$lang['us_bad_email_pass']			= 'Correo electrónico o contraseña incorrectos.';
$lang['us_must_login']				= 'Debes iniciar sesión para ver esta página.';
$lang['us_no_permission']			= 'No tienes permiso de acceso a esta página.';
$lang['us_fields_required']         = '%s y el campo de contraseña debe ser llenado.';

$lang['us_access_logs']				= 'Registro de acceso';
$lang['us_logged_in_on']			= '<b>%s</b> inicio sesión en %s';
$lang['us_no_access_message']		= '<p>Felicitaciones!</p><p>Todos los usuarios tienen buena memoria!</p>';
$lang['us_log_create']				= 'crearon uno nuevo %s';
$lang['us_log_edit']				= 'usuario modificado';
$lang['us_log_delete']				= 'usuario eliminado';
$lang['us_log_logged']				= 'inicio de sesión desde';
$lang['us_log_logged_out']			= 'cierre de sesión desde';
$lang['us_log_reset']				= 'reiniciaron sus contraseñas.';
$lang['us_log_register']			= 'registraron una cuenta nueva.';
$lang['us_log_edit_profile']		= 'actualizaron su perfil';


$lang['us_purge_del_confirm']		= '¡Esta complentamente seguro de remover la cuenta de usuario(s) - no hay retorno?';
$lang['us_action_purged']			= 'Usuario purgados.';
$lang['us_action_deleted']			= 'El usuario fue eliminado.';
$lang['us_action_not_deleted']		= 'No podemos eliminar el usuario: ';
$lang['us_delete_account']			= 'Eliminar cuenta';
$lang['us_delete_account_note']		= '<h3>Eliminar esta cuenta</h3><p>Eliminar esta cuenta revocará todos los permisos en el sitio web.</p>';
$lang['us_delete_account_confirm']	= '¿Estas seguro de eliminar la cuenta de usuario(s)?';

$lang['us_restore_account']			= 'Restaurar la cuenta';
$lang['us_restore_account_note']	= '<h3>Restaurar esta cuenta</h3><p>restaurar la cuenta de este usuario.</p>';
$lang['us_restore_account_confirm']	= '¡Restaurar la cuenta de estos usuarios?';

$lang['us_role']					= 'Rol';
$lang['us_role_lower']				= 'rol';
$lang['us_no_users']				= 'No se encontraron usuarios.';
$lang['us_create_user']				= 'Crear un nuevo usuario';
$lang['us_create_user_note']		= '<h3>Crear un nuevo ususario</h3><p>Crear una nueva cuenta para otros usuarios en su círculo.</p>';
$lang['us_edit_user']				= 'Modificar el usuario';
$lang['us_restore_note']			= 'Restaurar el usuario y permitirle de nuevo el acceso al sitio web.';
$lang['us_unban_note']				= 'Desbloquear el usuario y permitirle el acceso al sitio web.';
$lang['us_account_status']			= 'Estado de la cuenta';

$lang['us_failed_login_attempts']	= 'Intentos de acceso fallidos';
$lang['us_failed_logins_note']		= '<p>Felicitaciones!</p><p>Todos los usuarios tienen buena memoria!</p>';

$lang['us_banned_admin_note']		= 'Este usuario ha sido bloqueado para acceder al sitio.';
$lang['us_banned_msg']				= 'Esta cuenta no tiene permisos para acceder al sitio.';

$lang['us_first_name']				= 'Nombre';
$lang['us_last_name']				= 'Apellido';
$lang['us_address']					= 'Dirección';
$lang['us_street_1']				= 'Calle 1';
$lang['us_street_2']				= 'Calle 2';
$lang['us_city']					= 'Ciudad';
$lang['us_state']					= 'Estado';
$lang['us_no_states']				= 'No hay estado/provincia/regiones para este país. Crealos en el archivo de confguración address';
$lang['us_country']					= 'País';
$lang['us_zipcode']					= 'Código Postal';

$lang['us_user_management']			= 'Gestión de usuarios';
$lang['us_email_in_use']			= 'El correo electrónico %s ya se encuentra en uso. Por favor elige otro.';

$lang['us_edit_profile']			= 'Modificar el perfil';
$lang['us_edit_note']				= 'Ingrese sus detalles acontinuación y luego haga clic en guardar.';

$lang['us_reset_password']			= 'Restablecer su contraseña';
$lang['us_reset_note']				= 'Ingrese su correo electrónico y le enviaremos una contraseña temporal.';

$lang['us_login']					= 'Mi nombre es...';
$lang['us_remember_note']			= 'Recordarme por 2 semanas';
$lang['us_no_account']				= '¿No tienes una cuenta?';
$lang['us_sign_up']					= 'Registrate hoy';
$lang['us_forgot_your_password']	= '¿Olvidaste tu contraseña?';
$lang['us_button_enter']			= 'Ingresar';

$lang['us_password_mins']			= 'Mínimo 8 caracteres.';
$lang['us_register']				= 'Registrar';
$lang['us_already_registered']		= '¿Ya estás registrado?';
$lang['us_action_save']				= 'Guardar Usuario';
$lang['us_unauthorized']			= 'Sin autorización. Lo sentimos pero no tienes los permisos apropiados para gestionar el rol "%s".';
$lang['us_empty_id']				= 'No proporciono el ID del usuario. Debes proporcionarlo para realizar esta acción.';
$lang['us_self_delete']				= 'Sin autorización. Lo sentimos, no se puede eliminar a usted mismo.';
$lang['us_filter_first_letter']		= 'El nombre de usuario inicia con: ';
$lang['us_account_details']			= 'Detalles de la cuenta';
$lang['us_last_login']				= 'ültimo inicio de sesión';
$lang['us_no_password']             = 'No se indico contraseña.';
$lang['us_no_email']                = 'No se indico el correo electrónico.';
$lang['us_email_taken']             = 'El correo electrónico ya existe.';
$lang['us_invalid_user_id']         = 'ID de usuario incorrecto';
$lang['us_account_created_success'] = 'Su cuenta ha sido creada. Por favor inicie sesión.';
$lang['us_email_already_used']      = 'Ese correo electrónico ya se encuentra en uso.';
$lang['us_username_already_used']   = 'Ese nombre de usuario ya se encuentra en uso.';

$lang['us_invalid_email']           = 'No se pudo encontrar el correo electrónico en nuestros registros.';

$lang['us_reset_invalid_email']     = 'No parece ser una solicitud de restablecimiento de contraseña válido.';
$lang['us_reset_pass_subject']      = 'Su contraseña temporal';
$lang['us_reset_pass_message']      = 'Por favor revise su correo electrónico para las instrucciones para restablecer su contraseña.';
$lang['us_reset_pass_error']        = 'No se puede enviar el correo electrónico: ';
$lang['us_reset_password_success']  = 'Por favor inicie sesión usando su nueva contraseña.';
$lang['us_reset_password_error']    = 'Hubo un error al restablecer su contraseña: ';


$lang['us_profile_updated_success'] = 'El perfil se ha actualizado';
$lang['us_profile_updated_error']   = 'Hubo problema al actualizar su perfil ';

$lang['us_register_disabled']       = 'No esta permitido registrar nuevas cuentas de usuario.';


$lang['us_user_created_success']    = 'El usuario ha sido creado.';
$lang['us_user_update_success']     = 'El usuario ha sido actualizado.';

$lang['us_user_restored_success']   = 'El usuario ha sido restaurado.';
$lang['us_user_restored_error']     = 'No se puede restaurar el usuario: ';


/* Activations */
$lang['us_status']					= 'Estado';
$lang['us_inactive_users']			= 'Usuarios inactivos';
$lang['us_activate']				= 'Activación';
$lang['us_user_activate_note']		= 'Ingrese su código de activación para confirmar su correo electrónico y activar su cuenta.';
$lang['us_activate_note']			= 'Activar el usuario y permitirle el acceso al sitio';
$lang['us_deactivate_note']			= 'Inactivar el usuario y bloquear su acceso al sitio';
$lang['us_activate_enter']			= 'Por favor ingrese su código de activación para continuar.';
$lang['us_activate_code']			= 'Código de activación';
$lang['us_activate_request']		= 'Solicitar uno nuevo';
$lang['us_activate_resend']			= 'Reenviando código de activación';
$lang['us_activate_resend_note']	= 'Ingrese su correo electrónico y reenviaremos su código de activación.';
$lang['us_confirm_activate_code']	= 'Condirmar el código de activación';
$lang['us_activate_code_send']		= 'Enviar código de activación';
$lang['bf_action_activate']			= 'Activar';
$lang['bf_action_deactivate']		= 'Inactivar';
$lang['us_account_activated']		= 'Activación de cuentas de usuario.';
$lang['us_account_deactivated']		= 'Inactivación de cuentas de usuario.';
$lang['us_account_activated_admin']	= 'Activación de la cuenta administrativa.';
$lang['us_account_deactivated_admin']	= 'Inactivación de la cuenta administrativa.';
$lang['us_active']					= 'Activo.';
$lang['us_inactive']				= 'Inactivo.';
//email subjects
$lang['us_email_subj_activate']		= 'Activa tu cuenta';
$lang['us_email_subj_pending']		= 'Registro completado. Pendiente por activación.';
$lang['us_email_thank_you']			= 'Gracias por registrarse! ';
// Activation Statuses
$lang['us_registration_fail'] 		= 'El registro no se completo correctamente. ';
$lang['us_check_activate_email'] 	= 'Por favor revisa tu correo electrónivo para las instruccionesde activación de su cuenta.';
$lang['us_admin_approval_pending']  = 'Su cuenta esta pendiente de aprobación por el administrador. Recibirás un correo electrónico de notificación si su cuenta esta activada.';
$lang['us_account_not_active'] 		= 'Su cuenta aún no ha sido activada, por favor active su cuenta ingresando el código.';
$lang['us_account_active'] 			= 'Felicitaciones. Su cuenta esta activa!.';
$lang['us_account_active_login'] 	= 'Tu cuenta está activa y ahora puedes iniciar sesión.';
$lang['us_account_reg_complete'] 	= 'El registro para [SITE_TITLE] ha sido completado!';
$lang['us_active_status_changed'] 	= 'El estado del usuario se cambio correctamente.';
$lang['us_active_email_sent'] 		= 'El correo elecrónico de activación fue enviado.';
// Activation Errors
$lang['us_err_no_id'] 				= 'El ID de usuario no se ha recibido.';
$lang['us_err_status_error'] 		= 'El estado de los usuario no se cambió: ';
$lang['us_err_no_email'] 			= 'No se puede enviar un correo a: ';
$lang['us_err_activate_fail'] 		= 'Su cuenta no pudos ser activada en este momento por una de las siguientes razones: ';
$lang['us_err_activate_code'] 		= 'Por favor verifique su código e intente nuevamente o contacte al administrador del sitio solicitando su ayuda.';
$lang['us_err_no_matching_code'] 	= 'No se encuentran coincidencias del código de activación en el sistema.';
$lang['us_err_no_matching_id'] 		= 'No se encuentran coincidencias del ID de usuario en el sistema.';
$lang['us_err_user_is_active'] 		= 'El usuario ya se encuentra activo.';
$lang['us_err_user_is_inactive'] 	= 'El usuario ya se encuentra inactivo.';

/* Password strength/match */
$lang['us_pass_strength']			= 'Fuerza';
$lang['us_pass_match']				= 'Comparación';
$lang['us_passwords_no_match']		= '¡No coincide!';
$lang['us_passwords_match']			= '¡Coincide!';
$lang['us_pass_weak']				= 'Débil';
$lang['us_pass_good']				= 'Bueno';
$lang['us_pass_strong']				= 'Fuerte';

// $lang['us_tab_all']					= 'All Users';
// $lang['us_tab_inactive']			= 'Inactive';
// $lang['us_tab_banned']				= 'Banned';
// $lang['us_tab_deleted']				= 'Deleted';
// $lang['us_tab_roles']				= 'By Role';
