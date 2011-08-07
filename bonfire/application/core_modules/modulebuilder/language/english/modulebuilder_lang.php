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

// INDEX page
$lang['mb_actions']				= 'Actions';
$lang['mb_create_button']		= 'Create Module';
$lang['mb_create_note']			= 'Use our wizbang module building wizard to create your next module. We do all the heavy lifting by generating all the controllers, models, views and language files you need.';
$lang['mb_delete']				= 'Delete';
$lang['mb_generic_description']	= 'Your Description here.';
$lang['mb_installed_head']		= 'Installed Application Modules';
$lang['mb_module']				= 'Module';
$lang['mb_modules']				= 'Modules';
$lang['mb_no_modules']			= 'No Modules Installed.';

$lang['mb_table_name']			= 'Name';
$lang['mb_table_version']		= 'Version';
$lang['mb_table_author']		= 'Author';
$lang['mb_table_description']	= 'Description';

// OUTPUT page
$lang['mb_out_success']	= 'The module creation was successful! Below you will find the list of Controller, Model, Language, Migration and View files that were created during this process. Model and SQL files will be included if you selected the "Generate Migration" option and a Javascript file if it was required during creation.';
$lang['mb_out_success_note']	= 'NOTE: Please add extra user input validation as you require.  This code is to be used as a starting point only.';

// FORM page
$lang['mb_form_note'] = '<p><b>Fill out the fields you would like in your module (an "id" field is created automatically).  If you want to create the SQL for a DB table check the "Generate Migration" box.</b></p>
	
	<p>This form will generate a full CodeIgniter module (model, controller and views) and, if you choose, database Migrations file.</p>
	
	<p>If DB field type is "enum" or "set", please enter the values using this format: \'a\',\'b\',\'c\'...
	<br />If you ever need to put a backslash ("\") or a single quote ("\'") amongst those values, precede it with a backslash (for example \'\\xyz\' or \'a\\\'b\').
	</p>';
	
$lang['mb_form_errors']			= 'Please correct the errors below.';
$lang['mb_form_mod_details']	= 'Module Details.';
$lang['mb_form_mod_name']		= 'Module Name';
$lang['mb_form_mod_name_ph']	= 'Forums, Blog, ToDo';
$lang['mb_form_mod_desc']		= 'Module Description';
$lang['mb_form_mod_desc_ph']	= 'A list of todo items';
$lang['mb_form_contexts']		= 'Contexts Required';
$lang['mb_form_public']			= 'Public';
$lang['mb_form_actions']		= 'Controller Actions';
$lang['mb_form_primarykey']		= 'Primary Key';
$lang['mb_form_delims']			= 'Form Input Delimiters';
$lang['mb_form_err_delims']		= 'Form Error Delimiters';
$lang['mb_form_text_ed']		= 'Textarea Editor';
$lang['mb_form_generate']		= 'Generate Migration';
$lang['mb_form_fieldnum']		= 'Number of fields';
$lang['mb_form_field_details']	= 'Field details';
$lang['mb_form_label']			= 'Label';
$lang['mb_form_label_ph']		= 'The name that will be used on webpages';
$lang['mb_form_fieldname']		= 'Name (no spaces)';
$lang['mb_form_fieldname_ph']	= 'The field name for the database. Lowercase is best.';
$lang['mb_form_type']			= 'Type';
$lang['mb_form_length']			= 'Length/Values';
$lang['mb_form_length_ph']		= '30, 255, 1000, etc...';
$lang['mb_form_dbtype']			= 'Database Type';
$lang['mb_form_rules']			= 'Validation Rules';
$lang['mb_form_required']		= 'required';
$lang['mb_form_trim']			= 'trim';
$lang['mb_form_xss']			= 'xss';
$lang['mb_form_valid_email']	= 'valid_email';
$lang['mb_form_is_numeric']		= 'is_numeric';