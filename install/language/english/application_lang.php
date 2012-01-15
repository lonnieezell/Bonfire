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
// !SETTINGS
//--------------------------------------------------------------------

$lang['bf_site_name']			= 'Site Name';
$lang['bf_site_email']			= 'Site Email';
$lang['bf_site_email_help']		= 'The default email that system-generated emails are sent from.';
$lang['bf_site_status']			= 'Site Status';
$lang['bf_online']				= 'Online';
$lang['bf_offline']				= 'Offline';
$lang['bf_top_number']			= 'Items <em>per</em> page:';
$lang['bf_top_number_help']		= 'When viewing reports, how many items should be listed at a time?';

$lang['bf_security']			= 'Security';
$lang['bf_login_type']			= 'Login Type';
$lang['bf_login_type_email']	= 'Email Only';
$lang['bf_login_type_username']	= 'Username Only';
$lang['bf_allow_register']		= 'Allow User Registrations?';
$lang['bf_login_type_both']		= 'Email or Username';
$lang['bf_use_usernames']		= 'User display across bonfire:';
$lang['bf_use_own_name']		= 'Use Own Name';
$lang['bf_allow_remember']		= 'Allow \'Remember Me\'?';
$lang['bf_remember_time']		= 'Remember Users For';
$lang['bf_week']				= 'Week';
$lang['bf_weeks']				= 'Weeks';
$lang['bf_days']				= 'Days';
$lang['bf_username']			= 'Username';
$lang['bf_password']			= 'Password';
$lang['bf_password_confirm']	= 'Password (again)';

$lang['bf_home_page']			= 'Home Page';
$lang['bf_pages']				= 'Pages';
$lang['bf_enable_rte']			= 'Enable RTE for pages?';
$lang['bf_rte_type']			= 'RTE Type';
$lang['bf_searchable_default']	= 'Searchable by default?';
$lang['bf_cacheable_default']	= 'Cacheable by default?';
$lang['bf_track_hits']			= 'Track Page Hits?';

$lang['bf_action_save']			= 'Save';
$lang['bf_action_delete']		= 'Delete';
$lang['bf_action_cancel']		= 'Cancel';
$lang['bf_action_download']		= 'Download';
$lang['bf_action_preview']		= 'Preview';
$lang['bf_action_search']		= 'Search';
$lang['bf_action_purge']		= 'Purge';
$lang['bf_action_restore']		= 'Restore';
$lang['bf_action_show']			= 'Show';
$lang['bf_action_login']		= 'Login';
$lang['bf_actions']				= 'Actions';

$lang['bf_do_check']			= 'Check for updates?';
$lang['bf_do_check_edge']		= 'Must be enabled to see bleeding edge updates as well.';

$lang['bf_update_show_edge']	= 'View bleeding edge updates?';
$lang['bf_update_info_edge']	= 'Leave unchecked to only check for new tagged updates. Check to see any new commits to the official repository.';

$lang['bf_ext_profile_show']	= 'Does User accounts have extended profile?';
$lang['bf_ext_profile_info']	= 'Check "Extended Profiles" to have extra profile meta-data available option(wip), omiting some default bonfire fields (eg: address).';

$lang['bf_yes']					= 'Yes';
$lang['bf_no']					= 'No';
$lang['bf_none']				= 'None';

$lang['bf_or']					= 'or';
$lang['bf_size']				= 'Size';
$lang['bf_files']				= 'Files';
$lang['bf_file']				= 'File';

$lang['bf_with_selected']		= 'With selected';

$lang['bf_env_dev']				= 'Development';
$lang['bf_env_test']			= 'Testing';
$lang['bf_env_prod']			= 'Production';

$lang['bf_user']				= 'User';
$lang['bf_users']				= 'Users';
$lang['bf_username']			= 'Username';
$lang['bf_description']			= 'Description';
$lang['bf_email']				= 'Email';
$lang['bf_user_settings']		= 'My Profile';

$lang['bf_both']				= 'both';
$lang['bf_go_back']				= 'Go Back';
$lang['bf_new']					= 'New';
$lang['bf_required_note']		= 'Required fields are in <b>bold</b>.';

$lang['bf_show_profiler']		= 'Show Admin Profiler?';
$lang['bf_show_front_profiler']	= 'Show Front End Profiler?';

//--------------------------------------------------------------------
// MY_Model
//--------------------------------------------------------------------
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
// Activities
//--------------------------------------------------------------------
$lang['bf_act_settings_saved']	= 'App settings saved from';