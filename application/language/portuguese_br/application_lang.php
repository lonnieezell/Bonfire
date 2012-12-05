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

$lang['bf_site_name'] = 'Título';
$lang['bf_site_email'] = 'Email';
$lang['bf_site_email_help'] = 'Email através do qual o sistema reporta';
$lang['bf_site_status'] = 'Status';
$lang['bf_online'] = 'Online';
$lang['bf_offline'] = 'Offline';
$lang['bf_top_number'] = 'Máx. de registos por página';
$lang['bf_top_number_help'] = 'Nos reportes, quantos items por página?';
// $lang['bf_home']				= 'Home';
// $lang['bf_site_information']	= 'Site Information';
// $lang['bf_timezone']			= 'Timezone';
// $lang['bf_language']			= 'Language';
// $lang['bf_language_help']		= 'Choose the languages available to the user.';

//--------------------------------------------------------------------
// ! AUTH SETTINGS
//--------------------------------------------------------------------

$lang['bf_security'] = 'Segurança';
$lang['bf_login_type'] = 'Tipo de Login';
$lang['bf_login_type_email'] = 'Apenas Email';
$lang['bf_login_type_username'] = 'Apenas Username';
$lang['bf_allow_register'] = 'Permite que os utilizadores se registem';
$lang['bf_login_type_both'] = 'Username ou Email';
$lang['bf_use_usernames'] = 'Usernames - Nome de Utilizador';
$lang['bf_use_own_name'] = 'Use Own Name';
$lang['bf_allow_remember'] = 'Permite o  \'Lembrar login\'?';
$lang['bf_remember_time'] = 'Lembrar Utilizadores de';
$lang['bf_week'] = 'Semana';
$lang['bf_weeks'] = 'Semanas';
$lang['bf_days'] = 'Dias';
$lang['bf_username'] = 'Utilizador';
$lang['bf_password'] = 'Password';
$lang['bf_password_confirm'] = 'Password (novamente)';
// $lang['bf_display_name']		= 'Display Name';

//--------------------------------------------------------------------
// ! CRUD SETTINGS
//--------------------------------------------------------------------

$lang['bf_home_page'] = 'Página Principal';
$lang['bf_pages'] = 'Páginas';
$lang['bf_enable_rte'] = 'Permitir RTE para páginas?';
$lang['bf_rte_type'] = 'RTE Type';
$lang['bf_searchable_default'] = 'Buscável por padrão?';
$lang['bf_cacheable_default'] = 'Cacheable por padrão?';
$lang['bf_track_hits'] = 'Gravar quantidade de entrada nas páginas?';

$lang['bf_action_save'] = 'Guardar';
$lang['bf_action_delete'] = 'Apagar';
$lang['bf_action_cancel'] = 'Cancelar';
$lang['bf_action_download'] = 'Download';
$lang['bf_action_preview'] = 'Previsualizar';
$lang['bf_action_search'] = 'Procurar';
$lang['bf_action_purge'] = 'Purgar';
$lang['bf_action_restore'] = 'Recuperar';
$lang['bf_action_show'] = 'Mostrar';
$lang['bf_action_login'] = 'Login';
// $lang['bf_action_logout']		= 'Sign Out';
$lang['bf_actions'] = 'Acções';
// $lang['bf_clear']				= 'Clear';
// $lang['bf_action_list']			= 'List';
// $lang['bf_action_create']		= 'Create';
// $lang['bf_action_ban']			= 'Ban';

//--------------------------------------------------------------------
// ! SETTINGS LIB
//--------------------------------------------------------------------

// $lang['bf_do_check']			= 'Check for updates?';
// $lang['bf_do_check_edge']		= 'Must be enabled to see bleeding edge updates as well.';

$lang['bf_update_show_edge'] = 'Ver bleeding edge updates?';
// $lang['bf_update_info_edge']	= 'Leave unchecked to only check for new tagged updates. Check to see any new commits to the official repository.';

// $lang['bf_ext_profile_show']	= 'Does User accounts have extended profile?';
// $lang['bf_ext_profile_info']	= 'Check "Extended Profiles" to have extra profile meta-data available option(wip), omiting some default bonfire fields (eg: address).';

$lang['bf_yes'] = 'Sim';
$lang['bf_no'] = 'Não';
$lang['bf_none'] = 'n/a';
// $lang['bf_id']					= 'ID';

$lang['bf_or'] = 'ou';
$lang['bf_size'] = 'Tamanho';
$lang['bf_files'] = 'Arquivos';
$lang['bf_file'] = 'Arquivo';

$lang['bf_with_selected'] = 'com os selecionados';

$lang['bf_env_dev'] = 'Desenvolvimento';
$lang['bf_env_test'] = 'Teste';
$lang['bf_env_prod'] = 'Produção';

$lang['bf_show_profiler'] = 'Mostrar Perfil?';
// $lang['bf_show_front_profiler']	= 'Show Front End Profiler?';

// $lang['bf_cache_not_writable']  = 'The application cache folder is not writable';

// $lang['bf_password_strength']			= 'Password Strength Settings';
// $lang['bf_password_length_help']		= 'Minimum password length e.g. 8';
// $lang['bf_password_force_numbers']		= 'Should password force numbers?';
// $lang['bf_password_force_symbols']		= 'Should password force symbols?';
// $lang['bf_password_force_mixed_case']	= 'Should password force mixed case?';
// $lang['bf_password_show_labels']	    = 'Display password validation labels';

//--------------------------------------------------------------------
// ! USER/PROFILE
//--------------------------------------------------------------------

$lang['bf_user'] = 'Utilizador';
$lang['bf_users'] = 'Utilizadores';
$lang['bf_description'] = 'Descrição';
$lang['bf_email'] = 'Email';
$lang['bf_user_settings'] = 'My Profile';

//--------------------------------------------------------------------
// !
//--------------------------------------------------------------------

$lang['bf_both'] = 'ambos';
$lang['bf_go_back'] = 'Anterior';
$lang['bf_new'] = 'Novo';
$lang['bf_required_note'] = 'Campos obrigatórios a <strong>negrito</strong>.';
// $lang['bf_form_label_required'] = '<span class="required">*</span>';

//--------------------------------------------------------------------
// MY_Model
//--------------------------------------------------------------------
// $lang['bf_model_db_error']		= 'DB Error: %s';
$lang['bf_model_no_data'] = 'Sem dados para mostrar.';
$lang['bf_model_invalid_id'] = 'IID inválido passado ao modelo.';
$lang['bf_model_no_table'] = 'Modelo sem tabela da BD especificada.';
$lang['bf_model_fetch_error'] = 'Informação insuficiente para fetch.';
$lang['bf_model_count_error'] = 'Informação insuficiente para count results.';
$lang['bf_model_unique_error'] = 'Informação insuficiente para check uniqueness.';
$lang['bf_model_find_error'] = 'Informação insuficiente para find by.';
$lang['bf_model_bad_select'] = 'Seleção Inválida.';

//--------------------------------------------------------------------
// Contexts
//--------------------------------------------------------------------
// $lang['bf_no_contexts']			= 'The contexts array is not properly setup. Check your application config file.';
$lang['bf_context_content'] = 'Conteúdo';
// $lang['bf_context_reports']		= 'Reports';
$lang['bf_context_settings'] = 'Definições';
$lang['bf_context_developer'] = 'Developer';

//--------------------------------------------------------------------
// Activities
//--------------------------------------------------------------------
$lang['bf_act_settings_saved'] = 'Configurações de App salvas de ';
// $lang['bf_unauthorized_attempt']= 'unsuccessfully attempted to access a page which required the following permission "%s" from ';

// $lang['bf_keyboard_shortcuts']		= 'Available keyboard shortcuts:';
// $lang['bf_keyboard_shortcuts_none']	= 'There are no keyboard shortcuts assigned.';
// $lang['bf_keyboard_shortcuts_edit']	= 'Update the keyboard shortcuts';

//--------------------------------------------------------------------
// Common
//--------------------------------------------------------------------
$lang['bf_question_mark'] = '?';
$lang['bf_language_direction'] = 'ltr';
// $lang['log_intro']              = 'These are your log messages';

//--------------------------------------------------------------------
// Login
//--------------------------------------------------------------------
// $lang['bf_action_register']		= 'Sign Up';
// $lang['bf_forgot_password']		= 'Forgot your password?';
// $lang['bf_remember_me']			= 'Remember me';

//--------------------------------------------------------------------
// Password Help Fields to be used as a warning on register
//--------------------------------------------------------------------
// $lang['bf_password_number_required_help']  = 'Password must contain at least 1 number.';
// $lang['bf_password_caps_required_help']    = 'Password must contain at least 1 capital letter.';
// $lang['bf_password_symbols_required_help'] = 'Password must contain at least 1 symbol.';

// $lang['bf_password_min_length_help']       = 'Password must be at least %s characters long.';
// $lang['bf_password_length']                = 'Password Length';

//--------------------------------------------------------------------
// User Meta examples
//--------------------------------------------------------------------

// $lang['user_meta_street_name']	= 'Street Name';
// $lang['user_meta_type']			= 'Type';
// $lang['user_meta_country']		= 'Country';
// $lang['user_meta_state']		= 'State';

// Activation
//--------------------------------------------------------------------
// $lang['bf_activate_method']			= 'Activation Method';
// $lang['bf_activate_none']			= 'None';
// $lang['bf_activate_email']			= 'Email';
// $lang['bf_activate_admin']			= 'Admin';
// $lang['bf_activate']				= 'Activate';
// $lang['bf_activate_resend']			= 'Resend Activation';

// $lang['bf_reg_complete_error']		= 'An error occurred completing your registration. Please try again or contact the site administrator for help.';
// $lang['bf_reg_activate_email'] 		= 'An email containing your activation code has been sent to [EMAIL].';
// $lang['bf_reg_activate_admin'] 		= 'You will be notified when the site administrator has approved your membership.';
// $lang['bf_reg_activate_none'] 		= 'Please login to begin using the site.';
// $lang['bf_user_not_active'] 		= 'User account is not active.';
// $lang['bf_login_activate_title']	= 'Need to activate your account?';
// $lang['bf_login_activate_email'] 	= '<b>Have an activation code to enter to activate your membership?</b> Enter it on the [ACCOUNT_ACTIVATE_URL] page.<br /><br />    <b>Need your code again?</b> Request it again on the [ACTIVATE_RESEND_URL] page.';
