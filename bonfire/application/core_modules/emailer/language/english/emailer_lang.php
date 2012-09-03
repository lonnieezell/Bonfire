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

$lang['em_template']			= 'Template';
$lang['em_email_template']		= 'Email Template';
$lang['em_emailer_queue']		= 'Email Queue';

$lang['em_system_email']		= 'System Email';
$lang['em_system_email_note']	= 'The email that all system-generated emails are sent from.';
$lang['em_email_server']		= 'Email Server';
$lang['em_settings']			= 'Email Settings';
$lang['em_settings_note']		= '<b>Mail</b> uses the standard PHP mail function, so no settings are necessary.';
$lang['em_location']			= 'location';
$lang['em_server_address']		= 'Server Address';
$lang['em_port']				= 'Port';
$lang['em_timeout_secs']		= 'Timeout (seconds)';
$lang['em_email_type']			= 'Email Type';
$lang['em_test_settings']		= 'Test Email Settings';

$lang['em_template_note']		= 'Emails are sent in HTML format. They can be customized by editing the header and footer, below.';
$lang['em_header']				= 'Header';
$lang['em_footer']				= 'Footer';

$lang['em_test_header']			= 'Test Your Settings';
$lang['em_test_intro']			= 'Enter an email address below to verify that your email settings are working.<br/>Please save the current settings before testing.';
$lang['em_test_button']			= 'Send Test Email';
$lang['em_test_result_header']	= 'Test Results';
$lang['em_test_no_results']		= 'Either the test did not run, or did not return any results.';
$lang['em_test_debug_header']	= 'Debug Information';
$lang['em_test_success']		= 'The email appears to be set correctly. If you do not see the email in your inbox, try looking in your Spam box or Junk mail.';
$lang['em_test_error']			= 'The email looks like it is not set correctly.';

$lang['em_test_mail_subject']	= 'Congratulations! Your Bonfire Emailer is working!';
$lang['em_test_mail_body']		= 'If you are seeing this email, then it appears your Bonfire Emailer is working!';

$lang['em_stat_no_queue']		= 'You do not currently have any emails in the queue.';
$lang['em_total_in_queue']		= 'Total Emails in Queue:';
$lang['em_total_sent']			= 'Total Emails Sent:';

$lang['em_sent']				= 'Sent';
$lang['em_attempts']			= 'Attempts';
$lang['em_id']					= 'ID';
$lang['em_to']					= 'To';
$lang['em_subject']				= 'Subject';

$lang['em_missing_data']		= 'One or more required fields are missing.';
$lang['em_no_debug']			= 'Email was queued. No debug data is available.';

$lang['em_delete_success']      = '%d records deleted.';
$lang['em_delete_failure']		= 'Could not delete records : ';
$lang['em_delete_error']		= 'Error deleting records : ';
$lang['em_delete_confirm']		= 'Are you sure you want to delete these emails? ';

$lang['em_create_email']		= 'Send New Email';
$lang['em_create_setting']		= 'Email Configure';
$lang['em_create_email_error']	= 'Error in creating emails : ';
$lang['em_create_email_success']= 'Email(s) are inserted into email queue.';
$lang['em_create_email_failure']= 'Fail in creating emails : ';
$lang['em_create_email_cancel']	= 'Cancel';
