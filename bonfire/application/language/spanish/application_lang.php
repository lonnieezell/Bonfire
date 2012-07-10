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

//--------------------------------------------------------------------
// ! GENERAL SETTINGS
//--------------------------------------------------------------------

$lang['bf_site_name']			= 'Nombre del sitio';
$lang['bf_site_email']			= 'Correo electrónico del sitio';
$lang['bf_site_email_help']		= 'The default email that system-generated emails are sent from.';
$lang['bf_site_status']			= 'Estado del sitio';
$lang['bf_online']				= 'En línea';
$lang['bf_offline']				= 'Fuera de línea';
$lang['bf_top_number']			= 'Item <em>por</em> página:';
$lang['bf_top_number_help']		= 'Cuando se muestran los reportes, &iquest;cu&aacute;ntos &iacute;tems se listar&aacute;n a la vez?';
$lang['bf_home']				= 'Inicio';
$lang['bf_site_information']	= 'Información del sitio';
$lang['bf_timezone']			= 'Zona Horaria';
$lang['bf_language']			= 'Idioma';
$lang['bf_language_help']		= 'Elige los idiomas disponibles para el usuario.';

//--------------------------------------------------------------------
// ! AUTH SETTINGS
//--------------------------------------------------------------------

$lang['bf_security']			= 'Seguridad';
$lang['bf_login_type']			= 'Tipo de acceso';
$lang['bf_login_type_email']	= 'Solo correo electrónico';
$lang['bf_login_type_username']	= 'Solo nombre de usuario';
$lang['bf_allow_register']		= '&iquest;Permitir a los usuarios registrarse?';
$lang['bf_login_type_both']		= 'Email o Usuario';
$lang['bf_use_usernames']		= 'Usuario a mostrar a trav&eacute;s de bonfire:';
$lang['bf_use_own_name']		= 'Use Own Name';
$lang['bf_allow_remember']		= '&iquest;Permitir \'Recordarme\'?';
$lang['bf_remember_time']		= 'Recordar usuario por';
$lang['bf_week']				= 'Semana';
$lang['bf_weeks']				= 'Semanas';
$lang['bf_days']				= 'Días';
$lang['bf_username']			= 'Nombre de usuario';
$lang['bf_password']			= 'Contraseña';
$lang['bf_password_confirm']	= 'Contraseña (Repetir)';
$lang['bf_display_name']		= 'Nombre';

//--------------------------------------------------------------------
// ! CRUD SETTINGS
//--------------------------------------------------------------------

$lang['bf_home_page']			= 'Inicio';
$lang['bf_pages']				= 'P&aacute;ginas';
$lang['bf_enable_rte']			= 'Enable RTE for pages?';
$lang['bf_rte_type']			= 'RTE Type';
$lang['bf_searchable_default']	= 'Searchable by default?';
$lang['bf_cacheable_default']	= 'Cacheable by default?';
$lang['bf_track_hits']			= 'Track Page Hits?';

$lang['bf_action_save']			= 'Guardar';
$lang['bf_action_delete']		= 'Eliminar';
$lang['bf_action_cancel']		= 'Cancelar';
$lang['bf_action_download']		= 'Descargar';
$lang['bf_action_preview']		= 'Vista previa';
$lang['bf_action_search']		= 'Buscar';
$lang['bf_action_purge']		= 'Purgar';
$lang['bf_action_restore']		= 'Restaurar';
$lang['bf_action_show']			= 'Mostrar';
$lang['bf_action_login']		= 'Iniciar Sesi&oacute;n';
$lang['bf_action_logout']		= 'Salir';
$lang['bf_actions']				= 'Acciones';
$lang['bf_clear']				= 'Limpiar';
$lang['bf_action_list']			= 'Listar';
$lang['bf_action_create']		= 'Crear';
$lang['bf_action_ban']			= 'Bloquear';

//--------------------------------------------------------------------
// ! SETTINGS LIB
//--------------------------------------------------------------------

$lang['bf_do_check']			= 'Check for updates?';
$lang['bf_do_check_edge']		= 'Must be enabled to see bleeding edge updates as well.';

$lang['bf_update_show_edge']	= 'View bleeding edge updates?';
$lang['bf_update_info_edge']	= 'Leave unchecked to only check for new tagged updates. Check to see any new commits to the official repository.';

$lang['bf_ext_profile_show']	= 'Does User accounts have extended profile?';
$lang['bf_ext_profile_info']	= 'Check "Extended Profiles" to have extra profile meta-data available option(wip), omiting some default bonfire fields (eg: address).';

$lang['bf_yes']					= 'Yes';
$lang['bf_no']					= 'No';
$lang['bf_none']				= 'None';
$lang['bf_id']					= 'ID';

$lang['bf_or']					= 'or';
$lang['bf_size']				= 'Size';
$lang['bf_files']				= 'Files';
$lang['bf_file']				= 'File';

$lang['bf_with_selected']		= 'With selected';

$lang['bf_env_dev']				= 'Development';
$lang['bf_env_test']			= 'Testing';
$lang['bf_env_prod']			= 'Production';

$lang['bf_show_profiler']		= 'Show Admin Profiler?';
$lang['bf_show_front_profiler']	= 'Show Front End Profiler?';

$lang['bf_cache_not_writable']  = 'The application cache folder is not writable';

$lang['bf_password_strength']			= 'Password Strength Settings';
$lang['bf_password_length_help']		= 'Minimum password length e.g. 8';
$lang['bf_password_force_numbers']		= 'Should password force numbers?';
$lang['bf_password_force_symbols']		= 'Should password force symbols?';
$lang['bf_password_force_mixed_case']	= 'Should password force mixed case?';
$lang['bf_password_show_labels']	    = 'Display password validation labels';

//--------------------------------------------------------------------
// ! USER/PROFILE
//--------------------------------------------------------------------

$lang['bf_user']				= 'Usuario';
$lang['bf_users']				= 'Usuarios';
$lang['bf_username']			= 'Nombre de usuario';
$lang['bf_description']			= 'Descripción';
$lang['bf_email']				= 'Correo electrónico';
$lang['bf_user_settings']		= 'Mi Perfil';

//--------------------------------------------------------------------
// !
//--------------------------------------------------------------------

$lang['bf_both']				= 'ambos';
$lang['bf_go_back']				= 'Regresar';
$lang['bf_new']					= 'Nuevo';
$lang['bf_required_note']		= 'Los campos requeridos están en <b>negrita</b>.';
$lang['bf_form_label_required'] = '<span class="required">*</span>';

//--------------------------------------------------------------------
// MY_Model
//--------------------------------------------------------------------
$lang['bf_model_db_error']		= 'DB Error: ';
$lang['bf_model_no_data']		= 'No data available.';
$lang['bf_model_invalid_id']	= 'Invalid ID passed to model.';
$lang['bf_model_no_table']		= 'Model has unspecified database table.';
$lang['bf_model_fetch_error']	= 'Not enough information to fetch field.';
$lang['bf_model_count_error']	= 'Not enough information to count results.';
$lang['bf_model_unique_error']	= 'Not enough information to check uniqueness.';
$lang['bf_model_find_error']	= 'Not enough information to find by.';
$lang['bf_model_bad_select']	= 'Invalid selection.';

//--------------------------------------------------------------------
// Contexts
//--------------------------------------------------------------------
$lang['bf_no_contexts']			= 'The contexts array is not properly setup. Check your application config file.';
$lang['bf_context_content']		= 'Convocatorias';
$lang['bf_context_reports']		= 'Reportes';
$lang['bf_context_settings']	= 'Configuraciones';
$lang['bf_context_developer']	= 'Desarrollo';

//--------------------------------------------------------------------
// Activities
//--------------------------------------------------------------------
$lang['bf_act_settings_saved']	= 'App settings saved from';
$lang['bf_unauthorized_attempt']= 'unsuccessfully attempted to access a page which required the following permission "%s" from ';

$lang['bf_keyboard_shortcuts']		= 'Available keyboard shortcuts:';
$lang['bf_keyboard_shortcuts_none']	= 'There are no keyboard shortcuts assigned.';
$lang['bf_keyboard_shortcuts_edit']	= 'Update the keyboard shortcuts';

//--------------------------------------------------------------------
// Common
//--------------------------------------------------------------------
$lang['bf_question_mark']	      = '?';
$lang['bf_language_direction']	= 'ltr';
$lang['log_intro']              = 'These are your log messages';

//--------------------------------------------------------------------
// Login
//--------------------------------------------------------------------
$lang['bf_action_register']		= 'Registrarse';
$lang['bf_forgot_password']		= 'Olvido su contraseña?';
$lang['bf_remember_me']			= 'Recordarme';

//--------------------------------------------------------------------
// Password Help Fields to be used as a warning on register
//--------------------------------------------------------------------
$lang['bf_password_number_required_help']  = 'Password must contain at least 1 punctuation mark.';
$lang['bf_password_caps_required_help']    = 'La contrase&ntilde;a debe contener al menos una letra en may&uacute;scula.';
$lang['bf_password_symbols_required_help'] = 'La contrase&ntilde;a debe contener al menos un s&iacute;mbolo.';

$lang['bf_password_min_length_help']       = 'La contraseña debe tener mínimo %s caracteres.';
$lang['bf_password_length']                = 'Fuerza de la contrase&ntilde;a';

//--------------------------------------------------------------------
// User Meta examples
//--------------------------------------------------------------------

$lang['user_meta_telefono']		= 'Tel&eacute;fono';
$lang['user_meta_celular']		= 'Celular';
$lang['user_meta_street_name']	= 'Dirección';
$lang['user_meta_ciudad']		= 'Ciudad';
$lang['user_meta_country']		= 'País';
$lang['user_meta_state']		= 'Departamento';

// Activation
//--------------------------------------------------------------------
$lang['bf_activate_method']			= 'M&eacute;todo de Activaci&oacute;n';
$lang['bf_activate_none']			= 'None';
$lang['bf_activate_email']			= 'Email';
$lang['bf_activate_admin']			= 'Admin';
$lang['bf_activate']				= 'Activado';
$lang['bf_activate_resend']			= 'Reenviar activaci&oacute;n';

$lang['bf_reg_complete_error']		= 'An error occurred completing your registration. Please try again or contact the site administrator for help.';
$lang['bf_reg_activate_email'] 		= 'An email containing your activation code has been sent to [EMAIL].';
$lang['bf_reg_activate_admin'] 		= 'You will be notified when the site administrator has approved your membership.';
$lang['bf_reg_activate_none'] 		= 'Please login to begin using the site.';
$lang['bf_user_not_active'] 		= 'User account is not active.';
$lang['bf_login_activate_title']	= 'Need to activate your account?';
$lang['bf_login_activate_email'] 	= '<b>Have an activation code to enter to activate your membership?</b> Enter it on the [ACCOUNT_ACTIVATE_URL] page.<br /><br />    <b>Need your code again?</b> Request it again on the [ACTIVATE_RESEND_URL] page.';
