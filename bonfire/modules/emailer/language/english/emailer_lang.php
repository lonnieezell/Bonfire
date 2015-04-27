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
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Emailer language file (English)
 *
 * Localization strings used by Bonfire's Emailer module.
 *
 * @package Bonfire\Modules\Emailer\Language\English
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/emailer
 */

$lang['emailer_template']       = 'Template';
$lang['emailer_email_template'] = 'Email Template';
$lang['emailer_emailer_queue']  = 'Email Queue';
$lang['emailer_email_test']     = 'Test Email';

$lang['emailer_system_email']      = 'System Email';
$lang['emailer_system_email_note'] = 'The email that all system-generated emails are sent from.';
$lang['emailer_email_server']      = 'Email Server';
$lang['emailer_settings']          = 'Email Settings';
$lang['emailer_settings_note']     = '<b>Mail</b> uses the standard PHP mail function, so no settings are necessary.';
$lang['emailer_location']          = 'location';
$lang['emailer_server_address']    = 'Server Address';
$lang['emailer_port']              = 'Port';
$lang['emailer_timeout_secs']      = 'Timeout (seconds)';
$lang['emailer_email_type']        = 'Email Type';
$lang['emailer_save_settings']     = 'Save Settings';
$lang['emailer_test_settings']     = 'Test Email Settings';
$lang['emailer_sendmail_path']     = 'Sendmail Path';
$lang['emailer_smtp_address']      = 'SMTP Server Address';
$lang['emailer_smtp_username']     = 'SMTP Username';
$lang['emailer_smtp_password']     = 'SMTP Password';
$lang['emailer_smtp_port']         = 'SMTP Port';
$lang['emailer_smtp_timeout']      = 'SMTP timeout';
$lang['emailer_smtp_timeout_secs'] = 'SMTP timeout (seconds)';

$lang['emailer_template_note'] = 'Emails are sent in HTML format. They can be customized by editing the header and footer, below.';
$lang['emailer_header']        = 'Header';
$lang['emailer_footer']        = 'Footer';
$lang['emailer_save_template'] = 'Save Template';

$lang['emailer_test_header']        = 'Test Your Settings';
$lang['emailer_test_intro']         = 'Enter an email address below to verify that your email settings are working.<br/>Please save the current settings before testing.';
$lang['emailer_test_button']        = 'Send Test Email';
$lang['emailer_test_result_header'] = 'Test Results';
$lang['emailer_test_debug_header']  = 'Debug Information';
$lang['emailer_test_success']       = 'The email appears to be set correctly. If you do not see the email in your inbox, try looking in your Spam box or Junk mail.';
$lang['emailer_test_error']         = 'The email looks like it is not set correctly.';

$lang['emailer_test_mail_subject'] = 'Congratulations! Your Bonfire Emailer is working!';
$lang['emailer_test_mail_body']    = 'If you are seeing this email, then it appears your Bonfire Emailer is working!';

$lang['emailer_stat_no_queue']  = 'You do not currently have any emails in the queue.';
$lang['emailer_total_in_queue'] = 'Total Emails in Queue:';
$lang['emailer_total_sent']     = 'Total Emails Sent:';
$lang['emailer_force_process']  = 'Process Now';
$lang['emailer_insert_test']    = 'Insert Test Email';

$lang['emailer_sent']          = 'Sent?';
$lang['emailer_attempts']      = 'Attempts';
$lang['emailer_id']            = 'ID';
$lang['emailer_to']            = 'To';
$lang['emailer_subject']       = 'Subject';
$lang['emailer_email_subject'] = 'Email Subject';
$lang['emailer_email_content'] = 'Email Content';

$lang['emailer_missing_data'] = 'One or more required fields are missing.';
$lang['emailer_no_debug']     = 'Email was queued. No debug data is available.';

$lang['emailer_delete_success'] = '%d records deleted.';
$lang['emailer_delete_failure'] = 'Could not delete records: %s';
$lang['emailer_delete_error']   = 'Error deleting records: %s';
$lang['emailer_delete_confirm'] = 'Are you sure you want to delete these emails?';
$lang['emailer_delete_none']    = 'No messages selected to delete.';

$lang['emailer_create_email']          = 'Send New Email';
$lang['emailer_create_setting']        = 'Email Configure';
$lang['emailer_create_email_error']    = 'Error creating emails: %s';
$lang['emailer_create_email_success']  = 'Email(s) are inserted into email queue.';
$lang['emailer_create_email_queued']   = '%s email(s) have been inserted into the email queue.';
$lang['emailer_create_email_failure']  = 'Failure creating emails: %s';
$lang['emailer_create_email_no_users'] = 'No users selected as recipients for the email(s)';

$lang['emailer_validation_errors_heading'] = 'Please fix the following errors:';
$lang['emailer_no_users_found']            = 'No users found that match your selection.';
$lang['emailer_queue_debug_heading']       = 'Email Debugger';
$lang['emailer_queue_debug_error']         = 'There was an error sending emails from the queue. The results appear below.';

$lang['emailer_general_settings']  = 'General Settings';
$lang['emailer_mailtype_text']     = 'Text';
$lang['emailer_mailtype_html']     = 'HTML';
$lang['emailer_protocol_mail']     = 'mail';
$lang['emailer_protocol_sendmail'] = 'sendmail';
$lang['emailer_protocol_smtp']     = 'SMTP';

$lang['emailer_settings_save_error'] = 'There was an error saving your settings.';
$lang['emailer_settings_save_success'] = 'Email settings successfully saved.';

$lang['form_validation_emailer_system_email']  = 'System Email';
$lang['form_validation_emailer_email_server']  = 'Email Server';
$lang['form_validation_emailer_sendmail_path'] = 'Sendmail Path';
$lang['form_validation_emailer_smtp_address']  = 'SMTP Server Address';
$lang['form_validation_emailer_smtp_username'] = 'SMTP Username';
$lang['form_validation_emailer_smtp_password'] = 'SMTP Password';
$lang['form_validation_emailer_smtp_port']     = 'SMTP Port';
$lang['form_validation_emailer_smtp_timeout']  = 'SMTP timeout';
