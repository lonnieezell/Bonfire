<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Builder Language File (English)
 *
 * @package     Bonfire\Modules\Builder\Language\English
 * @author      Bonfire Dev Team
 * @copyright   Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license     http://opensource.org/licenses/MIT
 * @link        http://cibonfire.com/docs/builder
 */

// INDEX page
$lang['mb_delete_confirm']		= 'Really delete this module and all of its files?';
$lang['mb_create_button']		= 'Create Module';
$lang['mb_create_link']			= 'Create a new module';
$lang['mb_create_note']			= 'Use our wizbang module building wizard to create your next module. We do all the heavy lifting by generating all the controllers, models, views and language files you need.';
$lang['mb_not_writable_note']	= 'Error: The application/modules folder is not writable so modules cannot be written to the server. Please make the folder writable and refresh this page.';
$lang['mb_generic_description']	= 'Your Description here.';
$lang['mb_installed_head']		= 'Installed Application Modules';
$lang['mb_module']				= 'Module';
$lang['mb_no_modules']			= 'No Modules Installed.';
$lang['mb_toolbar_title_index'] = 'Manage Modules';

$lang['mb_table_name']			= 'Name';
$lang['mb_table_version']		= 'Version';
$lang['mb_table_author']		= 'Author';
$lang['mb_table_description']	= 'Description';

// OUTPUT page
$lang['mb_out_success']			= 'The module creation was successful! Below you will find the list of Controller, Model, Language, Migration and View files that were created during this process. Model and SQL files will be included if you selected the "Generate Migration" option and a Javascript file if it was required during creation.';
$lang['mb_out_success_note']	= 'NOTE: Please add extra user input validation as you require.  This code is to be used as a starting point only.';
$lang['mb_out_tables_success']	= 'The database tables were automatically installed for you. You can verify or uninstall, if you wish, from the %s section.';
$lang['mb_out_tables_error']	= 'The database tables were <strong>NOT</strong> automatically installed for you. You still need to go to the %s section and migrate your database table(s) before you can work with them.';
$lang['mb_out_acl'] 			= 'Access Control File';
$lang['mb_out_acl_path']        = 'migrations/001_Install_%s_permissions.php';
$lang['mb_out_config'] 			= 'Config file';
$lang['mb_out_config_path'] 	= 'config/config.php';
$lang['mb_out_controller']		= 'Controllers';
$lang['mb_out_controller_path']	= 'controllers/%s.php';
$lang['mb_out_model'] 			= 'Models';
$lang['mb_out_model_path']		= '%s_model.php';
$lang['mb_out_view']			= 'Views';
$lang['mb_out_view_path']		= 'views/%s.php';
$lang['mb_out_lang']			= 'Language File';
$lang['mb_out_lang_path']		= '%s_lang.php';
$lang['mb_out_migration']		= 'Migration File(s)';
$lang['mb_out_migration_path']	= 'migrations/002_Install_%s.php';
$lang['mb_new_module']			= 'New Module';
$lang['mb_exist_modules']		= 'Existing Modules';

// FORM page
$lang['mb_form_note']			= '<p><b>Fill out the fields you would like in your module (an "id" field is created automatically).  If you want to create the SQL for a DB table check the "Create Module Table" box.</b></p><p>This form will generate a full CodeIgniter module (model, controller and views) and, if you choose, database Migration file(s).</p>';
$lang['mb_table_note']			= '<p>Your table will be created with at least one field, the primary key field that will be used as a unique identifier and as an index. If you required additional fields, click the number you require to add them to this form.</p>';
$lang['mb_field_note']			= '<p><b>NOTE : FOR ALL FIELDS</b><br />If DB field type is "enum" or "set", please enter the values using this format: \'a\',\'b\',\'c\'...<br />If you ever need to put a backslash ("\\") or a single quote ("\'") amongst those values, precede it with a backslash (for example \'\\\\xyz\' or \'a\\\'b\').</p>';

$lang['mb_form_errors']			= 'Please correct the errors below.';
$lang['mb_form_mod_details']	= 'Module Details';
$lang['mb_form_mod_name']		= 'Module Name';
$lang['mb_form_mod_name_ph']	= 'Forums, Blog, ToDo';
$lang['mb_form_mod_desc']		= 'Module Description';
$lang['mb_form_mod_desc_ph']	= 'Your module description';
$lang['mb_form_contexts']		= 'Contexts Required';
$lang['mb_form_public']			= 'Public';
$lang['mb_form_table_details']	= 'Table Details';
$lang['mb_form_actions']		= 'Controller Actions';
$lang['mb_form_actions_index']  = 'List';
$lang['mb_form_actions_create'] = 'Create';
$lang['mb_form_actions_edit']   = 'Edit';
$lang['mb_form_actions_delete'] = 'Delete';
$lang['mb_form_primarykey']		= 'Primary Key';
$lang['mb_form_delims']			= 'Form Input Delimiters';
$lang['mb_form_err_delims']		= 'Form Error Delimiters';
$lang['mb_form_text_ed']		= 'Textarea Editor';
$lang['mb_form_soft_deletes']	= 'Use "Soft" Deletes?';
$lang['mb_form_use_created']	= 'Use "Created" field?';
$lang['mb_form_use_modified']	= 'Use "Modified" field?';
$lang['mb_form_created_field']	= '"Created" field name';
$lang['mb_form_created_field_ph']	= 'created_on';
$lang['mb_form_modified_field']	= '"Modified" field name';
$lang['mb_form_modified_field_ph']	= 'modified_on';
$lang['mb_form_generate']		= 'Create Module Table';
$lang['mb_form_role_id']		= 'Give Role Full Access';
$lang['mb_form_fieldnum']		= 'Additional table fields';
$lang['mb_form_field_details']	= 'Field details';
$lang['mb_form_table_name']		= 'Table Name';
$lang['mb_form_table_name_ph']	= 'Lowercase, no spaces';
$lang['mb_form_table_as_field_prefix']	= 'Use table name as field prefix';
$lang['mb_form_label']			= 'Label';
$lang['mb_form_label_ph']		= 'The name that will be used on webpages';
$lang['mb_form_fieldname']		= 'Name';
$lang['mb_form_fieldname_ph']	= 'The field name for the database.';
$lang['mb_form_fieldname_help'] = 'Lowercase is best. No spaces';
$lang['mb_form_type']			= 'Webpage Input Type';
$lang['mb_form_length']			= 'Maximum Length <b>-or-</b> Values';
$lang['mb_form_length_ph']		= '30, 255, 1000, etc...';
$lang['mb_form_dbtype']			= 'Database Type';
$lang['mb_form_rules']			= 'Validation Rules';
$lang['mb_form_rules_limits']	= 'Input Limitations';
$lang['mb_form_required']		= 'Required';
$lang['mb_form_unique']			= 'Unique';
$lang['mb_form_trim']			= 'Trim';
$lang['mb_form_valid_email']	= 'Valid Email';
$lang['mb_form_is_numeric']		= '0-9';
$lang['mb_form_alpha']			= 'a-Z';
$lang['mb_form_alpha_dash']		= 'a-Z, 0-9, and _-';
$lang['mb_form_alpha_numeric']	= 'a-Z and 0-9';
$lang['mb_form_add_fld_button'] = 'Add another field';
$lang['mb_form_show_advanced']	= 'Toggle Advanced Options';
$lang['mb_form_show_more']		= '...toggle more rules...';
$lang['mb_form_integer']		= 'Integers';
$lang['mb_form_is_decimal']		= 'Decimal Numbers';
$lang['mb_form_is_natural']		= 'Natural Numbers';
$lang['mb_form_is_natural_no_zero'] = 'Natural, no zeroes';
$lang['mb_form_valid_ip']		= 'Valid IP';
$lang['mb_form_valid_base64']	= 'Valid Base64';
$lang['mb_form_alpha_extra']	= 'AlphaNumerics, underscore, dash, periods and spaces.';
$lang['mb_form_match_existing']	= 'Ensure this matches the existing fieldname!';
$lang['mb_form_module_db_no']	= 'None';
$lang['mb_form_module_db_create']	= 'Create New Table';
$lang['mb_form_module_db_exists']	= 'Build from Existing Table';
$lang['mb_form_build']			= 'Build the Module';
$lang['mb_form_none_of_the_above']  = 'None of these';

// Activities
$lang['mb_act_create']			= 'Created Module';
$lang['mb_act_delete']			= 'Deleted Module';

// Create Context
$lang['mb_create_a_context']	= 'Create A Context';
$lang['mb_tools']				= 'Tools';
$lang['mb_mod_builder']			= 'Module Builder';
$lang['mb_new_context']			= 'New Context';
$lang['mb_no_context_name']		= 'Invalid Context name.';
$lang['mb_cant_write_config']	= 'Unable to write to config file.';
$lang['mb_context_exists']		= 'Context already exists in application config file.';
$lang['mb_context_name']        = 'Context Name';
$lang['mb_context_name_help']   = 'Cannot contain spaces.';
$lang['mb_context_create_success']  = 'Context successfully created.';
$lang['mb_context_create_error']    = 'Error creating Context: ';
$lang['mb_context_create_intro']    = 'Creates and sets up a new Context.';
$lang['mb_roles_label']         = 'Allow for Roles:';
$lang['mb_context_migrate']     = 'Create an Application Migration?';
$lang['mb_context_submit']      = 'Create It';
$lang['mb_context_create_intro_note'] = 'Note: The builder will <strong>not</strong> setup routing for the new context. You will usually need to add a route for the new context in /application/config/routes.php.';

// Create Module
$lang['mb_module_table_not_exist']  = 'The specified table name does not exist';
$lang['mb_toolbar_title_create'] = 'Module Builder';

// Delete Module
$lang['mb_delete_trans_false']  = 'We could not delete this module.';
$lang['mb_delete_success']      = 'The module and associated database entries were successfully deleted.';
$lang['mb_delete_success_db_only']  = ' HOWEVER, the module folder and files were not removed. They must be removed manually.';

// Validate Form
$lang['mb_contexts_content']    = 'Contexts :: Content';
$lang['mb_contexts_developer']  = 'Contexts :: Developer';
$lang['mb_contexts_public']     = 'Contexts :: Public';
$lang['mb_contexts_reports']    = 'Contexts :: Reports';
$lang['mb_contexts_settings']   = 'Contexts :: Settings';
$lang['mb_module_db']           = 'Module Table';
$lang['mb_form_action_create']  = 'Form Actions :: Create';
$lang['mb_form_action_delete']  = 'Form Actions :: Delete';
$lang['mb_form_action_edit']    = 'Form Actions :: Edit';
$lang['mb_form_action_view']    = 'Form Actions :: List';
$lang['mb_soft_delete_field']   = '"Soft" Delete field name';
$lang['mb_soft_delete_field_ph']    = 'deleted';
$lang['mb_validation_no_match'] = '%s %ss (%s & %s) must be unique!';
$lang['mb_modulename_check']    = 'The %s field is not valid';
$lang['mb_modulename_check_class_exists']   = 'The %s field is not valid: the module name matches the name of an existing class.';

$lang['mb_form_log_user']       = 'Log User';
$lang['mb_deleted_by_field']    = '"Deleted By" field name';
$lang['mb_deleted_by_field_ph'] = 'deleted_by';
$lang['mb_form_created_by_field']       = '"Created By" field name';
$lang['mb_form_created_by_field_ph']    = 'created_by';
$lang['mb_form_modified_by_field']      = '"Modified By" field name';
$lang['mb_form_modified_by_field_ph']   = 'modified_by';
$lang['mb_form_use_pagination'] = 'Use pagination library to create pager';
