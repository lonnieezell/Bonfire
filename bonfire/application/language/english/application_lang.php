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
// General Actions
//--------------------------------------------------------------------
$lang['bf_action_save']			= 'Save';
$lang['bf_action_save_changes']			= 'Save Changes';
$lang['bf_action_save_settings']			= 'Save Settings';
$lang['bf_action_delete']		= 'Delete';
$lang['bf_action_cancel']		= 'Cancel';
$lang['bf_action_download']		= 'Download';
$lang['bf_action_preview']		= 'Preview';
$lang['bf_action_search']		= 'Search';
$lang['bf_action_purge']		= 'Purge';
$lang['bf_action_restore']		= 'Restore';
$lang['bf_action_show']			= 'Show';
$lang['bf_actions']				= 'Actions';
$lang['bf_clear']				= 'Clear';
$lang['bf_action_list']			= 'List';
$lang['bf_action_create']		= 'Create';
$lang['bf_action_ban']			= 'Ban';

//--------------------------------------------------------------------
// Environments
//--------------------------------------------------------------------
$lang['bf_env_dev']				= 'Development';
$lang['bf_env_test']			= 'Testing';
$lang['bf_env_prod']			= 'Production';

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
$lang['bf_context_content']		= 'Content';
$lang['bf_context_reports']		= 'Reports';
$lang['bf_context_settings']	= 'Settings';
$lang['bf_context_developer']	= 'Developer';

//--------------------------------------------------------------------
// Pagination
//--------------------------------------------------------------------
$lang['bf_pager_first_link']		= '&lsaquo; First';
$lang['bf_pager_next_link']			= '&gt;';
$lang['bf_pager_prev_link']			= '&lt;';
$lang['bf_pager_last_link']			= 'Last &rsaquo;';
	
//--------------------------------------------------------------------
// Form validation
//--------------------------------------------------------------------

// [MIN] will be change by minimum lenght
$lang['bf_form_validation_lenght'] 		= 'The %s field must be at least [MIN] characters long.';
$lang['bf_form_validation_unique'] 		= 'The value in &quot;%s&quot; is already being used.';
$lang['bf_form_validation_alpha_extra'] 		= 'The %s field may only contain alpha-numeric characters, spaces, periods, underscores, and dashes.';
$lang['bf_form_validation_matches_pattern'] 		= 'The %s field does not match the required pattern.';
$lang['bf_form_validation_allowed_types'] 		= '%s must contain one of the allowed selections.';
$lang['bf_form_validation_one_of'] 		= '%s must contain one of the available selections.';
$lang['bf_form_validation_use_nums'] 		= '%s must contain at least 1 number.';
$lang['bf_form_validation_use_syms'] 		= '%s must contain at least 1 punctuation mark.';
$lang['bf_form_validation_use_mixed_upper'] 		= '%s must contain at least 1 uppercase characters.';
$lang['bf_form_validation_use_mixed_lower'] 		= '%s must contain at least 1 lowercase characters.';

//--------------------------------------------------------------------
// Admin Keyboard Shortcuts
//--------------------------------------------------------------------
$lang['bf_keyboard_shortcuts']		= 'Keyboard Shortcuts';
$lang['bf_available_keyboard_shortcuts']		= 'Available keyboard shortcuts:';
$lang['bf_keyboard_shortcuts_empty']	= 'There are no keyboard shortcuts assigned.';
$lang['bf_keyboard_shortcuts_edit']	= 'Update the keyboard shortcuts';

//--------------------------------------------------------------------
// Common
//--------------------------------------------------------------------
$lang['bf_language_direction']	= 'ltr';
$lang['bf_yes']					= 'Yes';
$lang['bf_no']					= 'No';
$lang['bf_none']				= 'None';
$lang['bf_id']					= 'ID';
$lang['bf_or']					= 'or';
$lang['bf_size']				= 'Size';
$lang['bf_files']				= 'Files';
$lang['bf_file']				= 'File';
$lang['bf_with_selected']		= 'With selected';
$lang['bf_description']			= 'Description';
$lang['bf_week']				= 'Week';
$lang['bf_weeks']				= 'Weeks';
$lang['bf_days']				= 'Days';
$lang['bf_required_note']		= 'Required fields are in <b>bold</b>.';
$lang['bf_form_label_required'] = '<span class="required">*</span>';

//--------------------------------------------------------------------
// User Meta examples
//--------------------------------------------------------------------
$lang['user_meta_street_name']	= 'Street Name';
$lang['user_meta_type']			= 'Type';
$lang['user_meta_country']		= 'Country';
$lang['user_meta_state']		= 'State';