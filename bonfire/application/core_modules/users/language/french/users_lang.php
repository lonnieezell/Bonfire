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

$lang['us_account_deleted']			= 'Unfortunately your account has been deleted. It has not yet been purged and <strong>may still</strong> be restored. Contact the administrator at %s.';

$lang['us_bad_email_pass']			= 'Incorrect email or password.';
$lang['us_must_login']				= 'You must be logged in to view that page.';
$lang['us_no_permission']			= 'You do not have permission to access that page.';
$lang['us_fields_required']         = '%s and Password fields must be filled out.';

$lang['us_access_logs']				= 'Access Logs';
$lang['us_logged_in_on']			= 'logged in on';
$lang['us_no_access_message']		= '<p>Congratulations!</p><p>All of your users have good memories!</p>';
$lang['us_log_create']				= 'created a new';
$lang['us_log_edit']				= 'modified user';
$lang['us_log_delete']				= 'deleted user';
$lang['us_log_logged']				= 'logged in from';
$lang['us_log_logged_out']			= 'logged out from';
$lang['us_log_reset']				= 'reset their password.';
$lang['us_log_register']			= 'registered a new account.';
$lang['us_log_edit_profile']		= 'updated their profile';


$lang['us_deleted_users']			= 'Deleted Users';
$lang['us_purge_del_accounts']		= 'Purge Deleted Accounts';
$lang['us_purge_del_note']			= '<p>Purging deleted accounts is a permanent action. There is no going back, so please make sure.</p>';
$lang['us_purge_del_confirm']		= 'Are you sure you want to completely remove the user account(s) - there is no going back?';
$lang['us_action_purged']			= 'Users purged.';
$lang['us_action_deleted']			= 'The User was successfully deleted.';
$lang['us_action_not_deleted']		= 'We could not delete the user: ';
$lang['us_delete_account']			= 'Delete Account';
$lang['us_delete_account_note']		= '<h3>Delete this Account</h3><p>Deleting this account will revoke all of their privileges on the site.</p>';
$lang['us_delete_account_confirm']	= 'Are you sure you want to delete the user account(s)?';

$lang['us_restore_account']			= 'Restore Account';
$lang['us_restore_account_note']	= '<h3>Restore this Account</h3><p>Un-delete this user\'s account.</p>';
$lang['us_restore_account_confirm']	= 'Restore this users account?';

$lang['us_role']					= 'Rôle';
$lang['us_role_lower']				= 'role';
$lang['us_no_users']				= 'No users found.';
$lang['us_create_user']				= 'Create New User';
$lang['us_create_user_note']		= '<h3>Create A New User</h3><p>Create new accounts for other users in your circle.</p>';
$lang['us_edit_user']				= 'Edit User';
$lang['us_restore_note']			= 'Restore the user and allow them access to the site again.';
$lang['us_unban_note']				= 'Un-Ban the user and all them access to the site.';
$lang['us_account_status']			= 'Account Status';

$lang['us_failed_login_attempts']	= 'Failed Login Attempts';
$lang['us_failed_logins_note']		= '<p>Congratulations!</p><p>All of your users have good memories!</p>';

$lang['us_banned_admin_note']		= 'This user has been banned from the site.';
$lang['us_banned_msg']				= 'This account does not have permission to enter the site.';

$lang['us_first_name']				= 'Nom de famille';
$lang['us_last_name']				= 'Prénom';
$lang['us_address']					= 'Addresse';
$lang['us_street_1']				= 'Rue 1';
$lang['us_street_2']				= 'Rue 2';
$lang['us_city']					= 'Ville';
$lang['us_state']					= 'Etat/région';
$lang['us_no_states']				= 'There are no states/provences/counties/regions for this country. Create them in the address config file';
$lang['us_country']					= 'Pays';
$lang['us_zipcode']					= 'Code postal';

$lang['us_user_management']			= 'Gestion des utlisateurs';
$lang['us_email_in_use']			= 'The %s address is already in use. Please choose another.';

$lang['us_edit_profile']			= '&Eacute;dition du profil';
$lang['us_edit_note']				= 'Enter your details below and click Save.';
$lang['us_create_account']			= 'Création d\'un compte utilisateur';

$lang['us_reset_password']			= 'Réinitialisation du mot de passe';
$lang['us_reset_note']				= 'Entrez votre adresse de courriel et nous vous ferons parvenir un mot de passe temporaire.';
$lang['us_confirm_password_send']		= 'Demander un nouveau mot de passe';

$lang['us_login_account']			= 'Connexion au compte utilisateur';
$lang['us_no_account']				= 'Besoin d\'un compte ? ';
$lang['us_sign_up']					= 'Inscrivez-vous maintenant !';
$lang['us_forgot_your_password']	= 'J\'ai oublié mon mot de passe';

$lang['us_password_mins']			= 'Minimum %s caractères.';
$lang['us_register']				= 'Register';
$lang['us_already_registered']		= 'Déjà inscrit ?';

$lang['us_action_save']				= 'Sauvegarder l\'utilisateur';
$lang['us_unauthorized']			= 'Unauthorized. Sorry you do not have the appropriate permission to manage the "%s" role.';
$lang['us_empty_id']				= 'No userid provided. You must provide a userid to perform this action.';
$lang['us_self_delete']				= 'Unauthorized. Sorry, you can not delete yourself.';

$lang['us_filter_first_letter']		= 'Le nom d\'utilisateur commence par : ';
$lang['us_account_details']			= 'Détails du compte';
$lang['us_last_login']				= 'Dernière connexion';



$lang['us_no_password']             = 'No Password present.';
$lang['us_no_email']                = 'No Email given.';
$lang['us_email_taken']             = 'Email already exists.';
$lang['us_invalid_user_id']         = 'Invalid User ID';

$lang['us_no_password']             = 'No Password present.';

$lang['us_no_email']                = 'No Email given.';

$lang['us_email_taken']             = 'Email already exists.';
$lang['us_invalid_user_id']         = 'Invalid User ID';



$lang['us_account_created_success'] = 'Your account has been created. Please log in.';

$lang['us_email_already_used']      = 'That email address is already in use.';
$lang['us_username_already_used']   = 'That username is already in use.';
$lang['us_invalid_user_id']         = 'Invalid user id.';
$lang['us_invalid_email']           = 'Cannot find that email in our records.';

$lang['us_reset_invalid_email']     = 'That did not appear to be a valid password reset request.';
$lang['us_reset_pass_subject']      = 'Your Temporary Password';
$lang['us_reset_pass_message']      = 'Please check your email for instructions to reset your password.';
$lang['us_reset_pass_error']        = 'Unable to send an email: ';
$lang['us_reset_password_success']  = 'Please login using your new password.';
$lang['us_reset_password_error']    = 'There was an error resetting your password: ';


$lang['us_profile_updated_success'] = 'Le profil a été mis à jour avec succès.';
$lang['us_profile_updated_error']   = 'There was a problem updating your profile ';

$lang['us_register_disabled']       = 'New account registrations are not allowed.';


$lang['us_user_created_success']    = 'User successfully created.';
$lang['us_user_update_success']     = 'L\'utilisateur a été mis à jour avec succès.';

$lang['us_user_restored_success']   = 'User successfully restored.';
$lang['us_user_restored_error']     = 'Unable to restore user: ';

$lang['us_users_list']					= 'Liste des utilisateurs';
$lang['us_no_user_found']				= 'Aucun utilisateur n\'a été trouvé correspondant à votre sélection.';
$lang['us_logged_in_date_format']				= 'j F Y à H:i';

/* Sub nav */
$lang['us_s_users']					= 'Utilisateurs';
$lang['us_s_new_user']					= 'Nouvel utilisateur';

/* Tabs */
$lang['us_t_all_users']				= 'Tous les utilisateurs';
$lang['us_t_inactive_users']	= 'Utilisateurs inactifs';
$lang['us_t_banned_users']		= 'Utilisateurs bannis';
$lang['us_t_deleted_users']		= 'Utilisateurs supprimés';
$lang['us_t_by_role_users']		= 'Utilisateurs par rôle';

/* Activations */
$lang['us_inactive_users']			= 'Inactive users';
$lang['us_activate']				= 'Activation de compte';
$lang['us_user_activate_note']		= 'Entrez votre code d\'activation pour confirmer votre adresse de courriel et activer votre compte.';
$lang['us_activate_note']			= 'Activate the user and allow them access to the site';
$lang['us_deactivate_note']			= 'Deactivate the user to prevent access to the site';
$lang['us_activate_enter']			= 'Please enter your activation code to continue.';
$lang['us_activate_code']			= 'Code d\'activation';
$lang['us_activate_request']		= 'Request a new one';
$lang['us_activate_resend']			= 'Renvoi du code d`activation';
$lang['us_activate_resend_note']	= 'Entrez votre adresse de couriiel et nous vous renverrons votre code d\'activation .';
$lang['us_confirm_activate_code']	= 'Confirmer le code d\'activation';
$lang['us_activate_code_send']		= 'Demander le code d\'activation';
$lang['bf_action_activate']			= 'Activer';
$lang['bf_action_deactivate']		= 'Déactiver';
$lang['us_purge_del_accounts']		= 'Purge Deleted Accounts';
$lang['us_no_inactive']				= 'There are not any users requiring activation in the database.';
$lang['us_activate_accounts']		= 'Activate All Accounts';
$lang['us_purge_act_note']			= '<h3>Activate All Accounts</h3><p>batch activate all users requiring activation.</p>';
$lang['us_account_activated']		= 'User account activation.';
$lang['us_account_deactivated']		= 'User account deactivation.';
$lang['us_account_activated_admin']	= 'Administrative account activation.';
$lang['us_account_deactivated_admin']	= 'Administrative account deactivation.';
$lang['us_active']					= 'Actif';
$lang['us_inactive']				= 'Inactif';
//email subjects
$lang['us_email_subj_activate']		= 'Activate Your membership';
$lang['us_email_subj_pending']		= 'Registration Complete. Activation Pending.';
$lang['us_email_thank_you']			= 'Thank you for registering! ';
// Activation Statuses
$lang['us_registration_fail'] 		= 'Registration did not complete successfully. ';
$lang['us_check_activate_email'] 	= 'Please check your email for instructions to activate your account.';
$lang['us_admin_approval_pending']  = 'Your account is pending admin approval. You will receive email notification if your account is activated.';
$lang['us_account_not_active'] 		= 'Your account is not yet active please activate your account by entering the code.';
$lang['us_account_active'] 			= 'Congratulations. Your account is now active!.';
$lang['us_account_active_login'] 	= 'Your account is active and you can now login.';
$lang['us_account_reg_complete'] 	= 'Registration to [SITE_TITLE] completed!';
$lang['us_active_status_changed'] 	= 'The user status was successfully changed.';
$lang['us_active_email_sent'] 		= 'Activation email was sent.';
// Activation Errors
$lang['us_err_no_id'] 				= 'No User ID was received.';
$lang['us_err_status_error'] 		= 'Le statut de l\'utilisateur n\'a pas été modifié. ';
$lang['us_err_no_email'] 			= 'Unable to send an email: ';
$lang['us_err_activate_fail'] 		= 'Your membership could not be activated at this time due to the following reason: ';
$lang['us_err_activate_code'] 		= 'Veuillez vérifier votre code et essayez à nouveau ou contactez l\'administrateur du site pour obtenir de l\'aide.';
$lang['us_err_no_activate_code'] 	= 'A required activation validation code was missing.';
$lang['us_err_no_matching_code'] 	= 'Aucun code d\'activation correspondant n\'a été trouvé dans le système.';
$lang['us_err_no_matching_id'] 		= 'No matching user id was found in the system.';
$lang['us_err_user_is_active'] 		= 'L\'utilisateur est déjà actif.';
$lang['us_err_user_is_inactive'] 	= 'L\'utilisateur est déjà inactif.';

/* Password strength/match */
$lang['us_pass_strength']			= 'Strength';
$lang['us_pass_match']				= 'Comparison';
$lang['us_passwords_no_match']		= 'No match!';
$lang['us_passwords_match']			= 'Match!';
$lang['us_pass_weak']				= 'Weak';
$lang['us_pass_good']				= 'Good';
$lang['us_pass_strong']				= 'Strong';
