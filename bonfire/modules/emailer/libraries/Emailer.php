<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2018, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License.
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Emailer Library
 *
 * The Emailer core module makes sending emails a breeze. It uses the default
 * CodeIgniter email library, but extends the functionality to provide the
 * ability to queue emails to be processed later by a CRON job, allowing you to
 * limit the number of emails that are sent per/hour if you have a picky mail
 * server or ISP.
 *
 * It also provides the ability to use HTML email templates, though only one
 * template is supported at the moment.
 *
 * @todo process_queue and send_email should use a common method to send each
 * message. While it's understandable that the model and library don't need to
 * be loaded by each iteration of the loop in process_queue (which would be
 * attempted if it called send_email to handle it), the code to send the email
 * shouldn't be completely separate for these two methods, either.
 *
 * @package Bonfire\Modules\Emailer\Libraries\Emailer
 * @author  Bonfire Dev Team
 * @link    https://github.com/ci-bonfire/Bonfire/blob/develop/bonfire/modules/emailer/docs/developer/index.md
 */
class Emailer
{
    /**
     * @var string Additional information about the running of the script and
     * the sending of an immediate email.
     */
    public $debug_message = '';

    /**
     * @var string An error generated during the course of the script running.
     */
    public $error = '';

    /**
     * @var bool Whether to send emails immediately or queue them by default.
     *
     * If true, will queue emails into the database to be sent later.
     * If false, will send the email immediately.
     */
    public $queue_emails = false;

    /**
     * @var string The name of the database table used to queue emails.
     */
    protected $tableName = 'email_queue';

    /**
     * @var object A pointer to the CodeIgniter instance.
     */
    private $ci;

    /**
     * @var bool Whether to set $debug_message.
     */
    private $debug = false;

    //--------------------------------------------------------------------------

    /**
     * Get the CodeIgniter instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ci =& get_instance();

        // Make sure the emailer_ lang entries are available.
        $this->ci->lang->load('emailer/emailer');
    }

    /**
     * Handle sending the emails and routing to the appropriate methods for queueing
     * or sending.
     *
     * Information about the email should be sent in the $data array. It looks like:
     *
     * $data = [
     *     'to'          => '', // either string or array
     *     'subject'     => '', // string
     *     'message'     => '', // string
     *     'alt_message' => '', // optional (text alt to html email)
     *     'attachments' => ['FILENAME_1', 'FILENAME_2'], // optional
     * ];
     *
     * @param array $data          An array of settings required to send the email.
     * @param bool  $queueOverride If true, forces the email to be queued. If false,
     * forces the email to be sent immediately. If omitted or set to any value other
     * than true/false, $this->queue_emails determines whether the email is queued.
     *
     * @return bool True if the operation was successful, else false.
     */
    public function send($data = [], $queueOverride = null)
    {
        // Ensure the required information is supplied.
        $from = empty($data['from']) ? (settings_item('sender_email') ?: settings_item('site.system_email'))
            : $data['from'];

        if (empty($data['to'])
            || ! isset($data['message'])
            || ! isset($data['subject'])
            || empty($from)
        ) {
            $this->error = lang('emailer_missing_data');
            return false;
        }

        // If $queueOverride is not a boolean value, use $this->queue_emails.
        if ($queueOverride !== true && $queueOverride !== false) {
            $queueOverride = (bool) $this->queue_emails;
        }

        $to      = $data['to'];
        $subject = $data['subject'];
        $message = $data['message'];

        $altMessage  = isset($data['alt_message']) ? $data['alt_message'] : false;
        $attachments = isset($data['attachments']) ? $data['attachments'] : false;

        // Wrap the $message in the email template, or strip HTML.
        $mailtype  = settings_item('mailtype');
        $templated = '';
        if ($mailtype != 'html') {
            $templated = html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8');
        } else {
            $templated  = $this->ci->load->view('emailer/email/_header', null, true);
            $templated .= $message;
            $templated .= $this->ci->load->view('emailer/email/_footer', null, true);
        }

        // Are emails queued?
        return $queueOverride ?
            $this->queueEmail($to, $from, $subject, $templated, $altMessage, $attachments)
            : $this->sendEmail($to, $from, $subject, $templated, $altMessage, $attachments);
    }

    /**
     * Add the email to the database to be sent out during a cron job.
     *
     * @todo Update this code to use the emailer_model
     *
     * @param string $to          The email to send the message to
     * @param string $from        The from email (Ignored in this method, but
     * kept for consistency with the send_email method).
     * @param string $subject     The subject line of the email
     * @param string $message     The message for the email, with the template
     * already applied if HTML emails are enabled.
     * @param string $altMessage An optional text-only version of the message
     * to be sent with HTML emails.
     * @param array  $attachments An optional array containing the location of
     * any files to be attached.
     *
     * @return bool true on success, else false
     */
    private function queueEmail($to, $from, $subject, $message, $altMessage = false, $attachments = false)
    {
        $data = [
            'to_email' => $to,
            'subject'  => $subject,
            'message'  => $message,
        ];

        if ($altMessage) {
            $data['alt_message'] = $altMessage;
        }

        if (! empty($attachments) && is_array($attachments)) {
            $data['csv_attachment'] = implode(',', $attachments);
        }

        if ($this->debug) {
            $this->debug_message = lang('emailer_no_debug');
        }

        return $this->ci->db->insert($this->tableName, $data);
    }

    /**
     * Sends the email immediately.
     *
     * @param string $to          The email to send the message to.
     * @param string $from        The from email.
     * @param string $subject     The subject line of the email
     * @param string $message     The message for the email, with the template
     * already applied if HTML emails are enabled.
     * @param string $altMessage An optional, text-only version of the message
     * to be sent with HTML emails.
     * @param array  $attachments An optional array, the array contains the
     * location of the files to be attached.
     *
     * @return bool true on success, else false.
     */
    private function sendEmail($to, $from, $subject, $message, $altMessage = false, $attachments = false)
    {
        $this->ci->load->library('email');
        $this->ci->load->model('settings/settings_model', 'settings_model');

        $this->ci->email->initialize(
            $this->ci->settings_model->select(['name', 'value'])->find_all_by('module', 'email')
        );
        $this->ci->email->clear(true);
        $this->ci->email->set_newline("\r\n");
        $this->ci->email->to($to);
        $this->ci->email->from($from, settings_item('site.title'));
        $this->ci->email->subject($subject);
        $this->ci->email->message($message);

        if ($altMessage) {
            $this->ci->email->set_alt_message($altMessage);
        }

        if (! empty($attachments) && is_array($attachments)) {
            foreach ($attachments as $attachment) {
                $this->ci->email->attach($attachment);
            }
        }

        if (defined('ENVIRONMENT')
            && ENVIRONMENT == 'development'
            && $this->ci->config->item('emailer.write_to_file') === true
        ) {
            if (! function_exists('write_file')) {
                $this->ci->load->helper('file');
            }
            $result = write_file(
                $this->ci->config->item('log_path') . str_replace(' ', '_', strtolower($subject)) . substr(md5($to . time()), 0, 8) . '.html',
                $message
            );
        } else {
            $result = $this->ci->email->send();
        }

        if ($this->debug) {
            $this->debug_message = $this->ci->email->print_debugger();
        }

        if (! $result) {
            log_message('error', sprintf(lang('emailer_send_error'), $this->ci->email->print_debugger()));
        }

        return $result;
    }

    /**
     * Process the email queue in chunks.
     *
     * Defaults to 33 which, if processed every 5 minutes, equals 400/hour and
     * should keep you safe with most ISPs. Always check your ISP's terms of
     * service to verify, though.
     *
     * @todo Modify the database update at the end of the method to use a batch
     * update outside the loop. Additionally, it should probably be modified to
     * use the emailer_model...
     *
     * @param int $limit An int specifying how many emails to process at once.
     *
     * @return bool true on success, else false
     */
    public function process_queue($limit = 33)
    {
        $config_settings = $this->ci->settings_model->select(['name', 'value'])
            ->find_all_by('module', 'email');

        // Grab records where success = 0
        $query = $this->ci->db->limit($limit)
            ->where('success', 0)
            ->get($this->tableName);

        // If the query returned no rows, the queue is empty, so it has been
        // processed successfully.
        if (! $query->num_rows()) {
            return true;
        }

        $emails = $query->result();
        $this->ci->load->library('email');

        // MySQL datetime format
        $dateTimeFormat = 'Y-m-d H:i:s';
        $now = new DateTime();

        $senderEmail = settings_item('sender_email');
        $siteTitle = settings_item('site.title');

        $success = true;
        foreach ($emails as $email) {
            $this->ci->email->initialize($config_settings);
            $this->ci->email->clear(true);
            $this->ci->email->set_newline("\r\n");
            $this->ci->email->to($email->to_email);
            $this->ci->email->from($senderEmail, $siteTitle);
            $this->ci->email->subject($email->subject);
            $this->ci->email->message($email->message);

            if ($email->alt_message) {
                $this->ci->email->set_alt_message($email->alt_message);
            }

            if ($email->csv_attachment) {
                $attachments = str_getcsv($email->csv_attachment);
                foreach ($attachments as $attachment) {
                    $this->ci->email->attach($attachment);
                }
            }

            $data = ['attempts' => $email->attempts + 1];

            if ($this->ci->email->send() === true) {
                // Email was successfully sent
                $data['success'] = 1;
            } else {
                // Error sending email

                // While explicitly setting 'success' to 0 is not necessary, it
                // makes it easier to check whether 'date_sent' should be set below.
                $data['success'] = 0;
                if ($this->debug) {
                    $this->debug_message = $this->ci->email->print_debugger();
                }

                // Note that $success is only set true before the loop, so, while
                // the loop continues attempting to send queued emails after a
                // failure, it still indicates a failure when a single email fails.
                $success = false;
            }

            // Update the timestamp with the current time, this is done after
            // calling email->send() because sending the email could take time.
            $timeStamp = $now->setTimestamp(time())->format($dateTimeFormat);
            $data['last_attempt'] = $timeStamp;
            if ($data['success'] == 1) {
                $data['date_sent'] = $timeStamp;
            }

            $this->ci->db->where('id', $email->id)
                         ->update($this->tableName, $data);
        }

        return $success;
    }

    /**
     * Tells the emailer lib whether to generate debugging messages.
     *
     * @param bool $enable_debug true to enable debugging messages, false to
     * disable
     */
    public function enable_debug($enable_debug)
    {
        $this->debug = (bool) $enable_debug;
    }

    /**
     * Specifies whether to queue emails in the send() method.
     *
     * @todo is there a requirement to return without setting $queue_emails, or
     * should the input just be cast to bool and set the property?
     *
     * @param bool $queue true to queue emails instead of sending them directly.
     *
     * @return void
     */
    public function queue_emails($queue)
    {
        if ($queue !== true && $queue !== false) {
            return;
        }

        $this->queue_emails = $queue;
    }
}
