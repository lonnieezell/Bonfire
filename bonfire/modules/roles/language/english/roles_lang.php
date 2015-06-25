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
 * @filesource
 */

/**
 * Roles Language File
 *
 * @package Bonfire\Modules\Roles\Language\English
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/bonfire/roles_and_permissions
 */

$lang['role_intro']                 = 'Roles allow you to define the abilities that a user can have.';
$lang['role_manage']                = 'Manage User Roles';
$lang['role_no_roles']              = 'There are not any roles in the system.';
$lang['role_create_button']         = 'Create a new role.';
$lang['role_create_note']           = 'Every user needs a role. Make sure you have all that you need.';
$lang['role_account_type']          = 'Account Type';
$lang['role_description']           = 'Description';
$lang['role_details']               = 'Role Details';

$lang['role_name']                  = 'Role Name';
$lang['role_max_desc_length']       = 'Max. 255 characters.';
$lang['role_default_role']          = 'Default Role';
$lang['role_default_note']          = 'This role should be assigned to all new users.';
$lang['role_permissions']           = 'Permissions';
$lang['role_permissions_check_note']= 'Check all permissions that apply to this Role.';
$lang['role_save_role']             = 'Save Role';
$lang['role_delete_role']           = 'Delete this Role';
$lang['role_delete_confirm']        = 'Are you sure you want to delete this role?';
$lang['role_delete_note']           = 'Deleting this role will convert all users that are currently assigned it to the site\'s default role.';
$lang['role_can_delete_role']       = 'Removable';
$lang['role_can_delete_note']       = 'Can this role be deleted?';

$lang['role_roles']                 = 'Roles';
$lang['role_new_role']              = 'New Role';
$lang['role_new_permission_message']    = 'You will be able to edit permissions once the role has been created.';
$lang['role_not_used']              = 'Not used';

$lang['role_login_destination']     = 'Login Destination';
$lang['role_destination_note']      = 'The site URL to redirect to on successful login.';
$lang['role_default_context']       = 'Default Admin Context';
$lang['role_default_context_note']  = 'The admin context to load when no context is specified (I.E. http://yoursite.com/admin/)';

$lang['matrix_header']              = 'Permission Matrix';
$lang['matrix_permission']          = 'Permission';
$lang['matrix_role']                = 'Role';
$lang['matrix_note']                = 'Instant permission editing. Toggle a checkbox to add or remove that permission for that role.';
$lang['matrix_insert_success']      = 'Permission added for role.';
$lang['matrix_insert_fail']         = 'There was a problem adding the permission for the role: ';
$lang['matrix_delete_success']      = 'Permission removed from the role.';
$lang['matrix_delete_fail']         = 'There was a problem deleting the permission for the role: ';
$lang['matrix_auth_fail']           = 'Authentication: You do not have the ability to manage the access control for this role.';

$lang['role_create_success']        = 'The role was successfully created.';
$lang['role_create_error']          = 'There was a problem creating the role: ';
$lang['role_delete_success']        = 'The role was successfully deleted.';
$lang['role_delete_error']          = 'The role could not be deleted: ';
$lang['role_edit_success']          = 'The role was successfully saved.';
$lang['role_edit_error']            = 'There was a problem saving the role: ';
$lang['role_invalid_id']            = 'Invalid Role ID.';

$lang['form_validation_role_name']              = 'Role Name';
$lang['form_validation_role_login_destination'] = 'Login Destination';
$lang['form_validation_role_default_context']   = 'Default Admin Context';
$lang['form_validation_role_default_role']      = 'Default Role';
$lang['form_validation_role_can_delete_role']   = 'Removable';
