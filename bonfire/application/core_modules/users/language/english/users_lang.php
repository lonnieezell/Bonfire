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

$lang['us_access_logs']				= 'Access Logs';
$lang['us_logged_in_on']			= 'logged in on';
$lang['us_no_access_message']		= '<p>Congratulations!</p><p>All of your users have good memories!</p>';
$lang['us_log_create']				= 'created a new';
$lang['us_log_edit']				= 'modified user';
$lang['us_log_delete']				= 'deleted user';
$lang['us_log_logged']				= 'logged in from';
$lang['us_log_reset']				= 'reset their password.';
$lang['us_log_register']			= 'registered a new account.';
$lang['us_log_edit_profile']		= 'updated their profile';


$lang['us_deleted_users']			= 'Deleted Users';
$lang['us_purge_del_accounts']		= 'Purge Deleted Accounts';
$lang['us_purge_del_note']			= '<p>Purging deleted accounts is a permanent action. There is no going back, so please make sure.</p>';
$lang['us_action_deleted']			= 'The User was successfully deleted.';
$lang['us_action_not_deleted']		= 'We could not delete the user: ';
$lang['us_delete_account']			= 'Delete Account';
$lang['us_delete_account_note']		= '<h3>Delete this Account</h3><p>Deleting this account will revoke all of their privileges on the site.</p>';
$lang['us_delete_account_confirm']	= 'Are you sure you want to delete the user account(s)?';

$lang['us_restore_account']			= 'Restore Account';
$lang['us_restore_account_note']	= '<h3>Restore this Account</h3><p>Un-delete this user\'s account.</p>';
$lang['us_restore_account_confirm']	= 'Restore this users account?';

$lang['us_role']					= 'Role';
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

$lang['us_first_name']				= 'First Name';
$lang['us_last_name']				= 'Last Name';
$lang['us_address']					= 'Address';
$lang['us_street_1']				= 'Street 1';
$lang['us_street_2']				= 'Street 2';
$lang['us_city']					= 'City';
$lang['us_state']					= 'State';
$lang['us_no_states']				= 'There are no states/provences/counties/regions for this country. Create them in the address config file';
$lang['us_country']					= 'Country';
$lang['us_zipcode']					= 'Zipcode';

$lang['us_user_management']			= 'User Management';
$lang['us_email_in_use']			= 'The %s address is already in use. Please choose another.';

$lang['us_edit_profile']			= 'Edit Profile';
$lang['us_edit_note']				= 'Enter your details below and click Save.';

$lang['us_reset_password']			= 'Reset Password';
$lang['us_reset_note']				= 'Enter your email and we will send a temporary password to you.';

$lang['us_login']					= 'My Name Is...';
$lang['us_remember_note']			= 'Remember me for two weeks';
$lang['us_no_account']				= 'Don&rsquo;t have an account?';
$lang['us_sign_up']					= 'Sign up today';
$lang['us_forgot_your_password']	= 'Forgot Your Password?';

$lang['us_password_mins']			= 'Minimum 8 characters.';
$lang['us_register']				= 'Register';
$lang['us_already_registered']		= 'Already registered?';

$lang['us_action_save']				= 'Save User';
$lang['us_unauthorized']			= 'Unauthorized. Sorry you do not have the appropriate permission to manage the "%s" role.';
$lang['us_empty_id']				= 'No userid provided. You must provide a userid to perform this action.';
$lang['us_self_delete']				= 'Unauthorized. Sorry, you can not delete yourself.';

$lang['us_filter_first_letter']		= 'Username starts with: ';
$lang['us_account_details']			= 'Account Details';
$lang['us_last_login']				= 'Last Login';

/* Password strength/match */
$lang['us_pass_strength']			= 'Strength';
$lang['us_pass_match']				= 'Comparison';
$lang['us_passwords_no_match']		= 'No match!';
$lang['us_passwords_match']			= 'Match!';
$lang['us_pass_weak']				= 'Weak';
$lang['us_pass_good']				= 'Good';
$lang['us_pass_strong']				= 'Strong';