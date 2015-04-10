<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Permissions Language File
 *
 * @package Bonfire\Modules\Permissions\Language\English
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/bonfire/roles_and_permissions
 */

// Create messages/titles.
$lang['permissions_create_failure']    = 'There was a problem creating the Permission: ';
$lang['permissions_create_message']    = 'Create a new permission in the system which will allow you to assign access to it in the Roles.';
$lang['permissions_create_new']        = 'Create a new Permission';
$lang['permissions_create_new_button'] = 'Create New Permission';
$lang['permissions_create_success']    = 'Permissions successfully created';

// Delete messages/titles.
$lang['permissions_del_error']         = 'You have not selected any Permissions to delete.';
$lang['permissions_del_failure']       = 'Unable to delete Permissions: ';
$lang['permissions_delete_confirm']    = 'Are you sure you want to delete this Permission?';
$lang['permissions_delete_failure']    = 'We could not delete the Permission: ';
$lang['permissions_delete_record']     = 'Delete this Permission';
$lang['permissions_delete_success']    = 'The Permission was successfully deleted';
$lang['permissions_delete_warning']    = 'Deleting this permission will also remove the access to this permission from the roles.';
$lang['permissions_deleted']           = 'Permissions deleted';

// Edit messages/titles.
$lang['permissions_edit_failure']      = 'There was a problem saving the Permissions: ';
$lang['permissions_edit_heading']      = 'Edit Permission';
$lang['permissions_edit_success']      = 'Permissions successfully saved';
$lang['permissions_edit_text']         = 'Edit this to suit your needs';

// Field names/labels.
$lang['permissions_description']       = 'Description';
$lang['permissions_name']              = 'Name';
$lang['permissions_status']            = 'Status';

// Status names/labels.
$lang['permissions_active']            = 'active';
$lang['permissions_inactive']          = 'inactive';

// Misc. messages/labels.
$lang['permissions_details']           = 'Permission Details';
$lang['permissions_id']                = 'ID';
$lang['permissions_intro']             = 'Permissions provide fine-grained control over what each role is allowed to do.';
$lang['permissions_invalid_id']        = 'Invalid permissions ID';
$lang['permissions_manage']            = 'Manage Permissions';
$lang['permissions_matrix']            = 'Permissions Matrix';
$lang['permissions_no_records']        = "There aren't any Permissions in the system.";
$lang['permissions_permission']        = 'Permission';
$lang['permissions_save']              = 'Save Permission';
