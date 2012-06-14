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

$lang['bf_site_name']			= 'Nom du site';
$lang['bf_site_email']			= 'Adresse de courriel';
$lang['bf_site_email_help']		= 'The default email that system-generated emails are sent from.';
$lang['bf_site_status']			= 'Etat du site';
$lang['bf_online']				= 'En ligne';
$lang['bf_offline']				= 'Hors-ligne';
$lang['bf_top_number']			= 'Eléments <em>par</em> page';
$lang['bf_top_number_help']		= 'Lors de la consultation de rapports, combien d\'éléments doivent être affichés à la fois ?';
$lang['bf_home']				= 'Accueil';
$lang['bf_timezone']			= 'Fuseaau horaire';
$lang['bf_language']			= 'Langue';
$lang['bf_languages']			= 'Langues';
$lang['bf_language_help']		= 'Choisissez les langues disponibles pour les utilisateurs.';

//--------------------------------------------------------------------
// ! AUTH SETTINGS
//--------------------------------------------------------------------

$lang['bf_security']			= 'Sécurité';
$lang['bf_login_type']			= 'Type de connexion';
$lang['bf_login_type_email']	= 'Uniquement avec l\'adresse de courriel';
$lang['bf_login_type_username']	= 'Uniquement avec le nom d\'utilisateur';
$lang['bf_allow_register']		= 'Autoriser l\'inscription des utilisateurs ?';
$lang['bf_login_type_both']		= 'Avec l\'adresse de courriel ou le nom d\'utilisateur';
$lang['bf_use_usernames']		= 'Affichage de l\'utilisateur<br />à travers le site';
$lang['bf_use_own_name']		= 'Use Own Name';
$lang['bf_allow_remember']		= 'Allow \'Remember Me\'?';
$lang['bf_remember_time']		= 'Se souvenir des utilisateurs';
$lang['bf_week']				= 'semaine';
$lang['bf_weeks']				= 'semaines';
$lang['bf_days']				= 'jours';
$lang['bf_username']			= 'Nom d\'utilisateur';
$lang['bf_password']			= 'Mot de passe';
$lang['bf_password_confirm']	= 'Mot de passe <em>(pour confirmation)</em>';
$lang['bf_display_name']		= 'Pseudonyme';

//--------------------------------------------------------------------
// ! CRUD SETTINGS
//--------------------------------------------------------------------

$lang['bf_home_page']			= 'Page d\'accueil';
$lang['bf_pages']				= 'Pages';
$lang['bf_enable_rte']			= 'Enable RTE for pages?';
$lang['bf_rte_type']			= 'RTE Type';
$lang['bf_searchable_default']	= 'Searchable by default?';
$lang['bf_cacheable_default']	= 'Cacheable by default?';
$lang['bf_track_hits']			= 'Track Page Hits?';

$lang['bf_action_save']			= 'Enregistrer';
$lang['bf_action_save_changes']			= 'Enregistrer les modifications';
$lang['bf_action_delete']		= 'Effacer';
$lang['bf_action_cancel']		= 'Annuler';
$lang['bf_action_download']		= 'Télécharger';
$lang['bf_action_preview']		= 'Preview';
$lang['bf_action_search']		= 'Rechercher';
$lang['bf_action_purge']		= 'Purger';
$lang['bf_action_restore']		= 'Restaurer';
$lang['bf_action_show']			= 'Afficher';
$lang['bf_action_login']		= 'Connexion';
$lang['bf_action_logout']		= 'Déconnexion';
$lang['bf_actions']				= 'Actions';
$lang['bf_clear']				= 'Clear';
$lang['bf_action_list']			= 'Lister';
$lang['bf_action_create']		= 'Créer';
$lang['bf_action_ban']			= 'Bannir';

$lang['bf_action_offline'] = 'Hors-ligne';
$lang['bf_action_manage']	= 'Gérer';
$lang['bf_action_allow']= 'Autoriser';
$lang['bf_action_edit'] = 'Editer';
$lang['bf_action_add'] = 'Ajouter';
$lang['bf_action_view'] = 'Voir';
$lang['bf_action_create'] = 'Créer';


$lang['bf_download_file']		= 'Télécharger ce fichier';
$lang['bf_restore_file']		= 'Restaurer ce fichier';

//--------------------------------------------------------------------
// ! SETTINGS LIB
//--------------------------------------------------------------------

$lang['bf_do_check']			= 'Check for updates?';
$lang['bf_do_check_edge']		= 'Must be enabled to see bleeding edge updates as well.';

$lang['bf_update_show_edge']	= 'View bleeding edge updates?';
$lang['bf_update_info_edge']	= 'Leave unchecked to only check for new tagged updates. Check to see any new commits to the official repository.';

$lang['bf_ext_profile_show']	= 'Does User accounts have extended profile?';
$lang['bf_ext_profile_info']	= 'Check "Extended Profiles" to have extra profile meta-data available option(wip), omiting some default bonfire fields (eg: address).';

$lang['bf_yes']					= 'Oui';
$lang['bf_no']					= 'Non';
$lang['bf_none']				= 'None';
$lang['bf_id']					= 'ID';

$lang['bf_or']					= 'ou';
$lang['bf_size']				= 'Taille';
$lang['bf_files']				= 'Fichiers';
$lang['bf_file']				= 'Fichier';

$lang['bf_with_selected']		= 'Avec la sélection ';

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

$lang['bf_user']				= 'User';
$lang['bf_users']				= 'Utilisateurs';
$lang['bf_email']				= 'Adresse de courriel';
$lang['bf_user_profile']		= 'Mon profil';
$lang['bf_control_pannel']		= 'Panneau de contrôle';

//--------------------------------------------------------------------
// !
//--------------------------------------------------------------------

$lang['bf_both']				= 'both';
$lang['bf_go_back']				= 'Go Back';
$lang['bf_new']					= 'New';
$lang['bf_required_note']		= 'Required fields are in <b>bold</b>.';
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
$lang['bf_context_content']		= 'Contenu';
$lang['bf_context_reports']		= 'Rapports';
$lang['bf_context_settings']	= 'Paramètres';
$lang['bf_context_developer']	= 'Développeur';

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
$lang['bf_form_label_end']		= ' :';
$lang['bf_for']	= 'pour';
$lang['bf_before']	= 'avant';
$lang['bf_description']			= 'Description';
$lang['bf_name']					= 'Nom';
$lang['bf_status']				= '&Eacute;tat';
$lang['bf_version']			= 'Version';
$lang['bf_author']			= 'Auteur';

//--------------------------------------------------------------------
// Login
//--------------------------------------------------------------------
$lang['bf_action_register']		= 'Créer un compte';
$lang['bf_forgot_password']		= 'Forgot your password?';
$lang['bf_remember_me']			= 'Se souvenir de moi';

//--------------------------------------------------------------------
// Password Help Fields to be used as a warning on register
//--------------------------------------------------------------------
$lang['bf_password_number_required_help']  = 'Password must contain at least 1 punctuation mark.';
$lang['bf_password_caps_required_help']    = 'Password must contain at least 1 capital letter.';
$lang['bf_password_symbols_required_help'] = 'Password must contain at least 1 symbol.';

$lang['bf_password_min_length_help']       = 'Le mot de passe doit comporter au moins %s caractères de long.';

//--------------------------------------------------------------------
// User Meta examples
//--------------------------------------------------------------------

$lang['user_meta_street_name']	= 'Street Name';
$lang['user_meta_type']			= 'Type';
$lang['user_meta_country']		= 'Pays';
$lang['user_meta_state']		= 'Etat/région';

//--------------------------------------------------------------------
// Pagination
//--------------------------------------------------------------------
$lang['pager_first_link']			= '&lsaquo; Première';
$lang['pager_next_link']			= 'Suivante &gt;';
$lang['pager_prev_link']			= '&lt; Précédente';
$lang['pager_last_link']			= 'Dernière &rsaquo;';

//--------------------------------------------------------------------
// Activation
//--------------------------------------------------------------------
$lang['bf_activate_method']			= 'Activation Method';
$lang['bf_activate_none']			= 'None';
$lang['bf_activate_email']			= 'Email';
$lang['bf_activate_admin']			= 'Admin';
$lang['bf_activate']				= 'activation de compte';
$lang['bf_activate_resend']			= 'renvoi du code d\'activation';

$lang['bf_reg_complete_error']		= 'An error occurred completing your registration. Please try again or contact the site administrator for help.';
$lang['bf_reg_activate_email'] 		= 'An email containing your activation code has been sent to [EMAIL].';
$lang['bf_reg_activate_admin'] 		= 'You will be notified when the site administrator has approved your membership.';
$lang['bf_reg_activate_none'] 		= 'Please login to begin using the site.';
$lang['bf_user_not_active'] 		= 'User account is not active.';
$lang['bf_login_activate_title']	= 'Besoin d\'activer votre compte ?';
$lang['bf_login_activate_email'] 	= '<b>Vous avez un code d\'activation à entrer pour activer votre inscription ?</b> Entrez-le sur la page [ACCOUNT_ACTIVATE_URL].<br /><br />    <b>Besoin de votre code à nouveau ?</b> Demandez-le à nouveau sur la page [ACTIVATE_RESEND_URL].';
