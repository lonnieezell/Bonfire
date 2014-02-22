<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
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
 * Emailer Library
 *
 * @todo process_queue and send_email should use a common method to send each
 * message. While it's understandable that the model and library don't need to
 * be loaded by each iteration of the loop in process_queue (which would be
 * attempted if it called send_email to handle it), the code to send the email
 * shouldn't be completely separate for these two methods, either.
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
 * @package    Bonfire\Modules\Emailer\Libraries\Emailer
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/guides
 */
class Emailer
{
	/**
	 * @var bool Whether to send emails immediately or queue them by default.
	 *
	 * If true, will queue emails into the database to be sent later.
	 * If false, will send the email immediately.
	 */
	public $queue_emails = false;

	/**
	 * @var string Additional information about the running of the script and
	 * the sending of an immediate email.
	 */
	public $debug_message = '';

	/**
	 * @var bool Whether to set $debug_message.
	 */
	private $debug = false;

	/**
	 * @var string An error generated during the course of the script running.
	 */
	public $error = '';

	/**
	 * @var object A pointer to the CodeIgniter instance.
	 */
	private $ci;

	//--------------------------------------------------------------------

	/**
	 * Sets up the CodeIgniter core object
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
	}

	/**
	 * Handles sending the emails and routing to the appropriate methods for
	 * queueing or sending.
	 *
	 * Information about the email should be sent in the $data array. It looks
	 * like:
	 *
	 * $data = array(
	 *     'to' => '',	// either string or array
	 *     'subject' => '',	// string
	 *     'message' => '',	// string
	 *     'alt_message' => ''	// optional (text alt to html email)
	 *     'attachments' => array('FILENAME_1','FILENAME_2' ) // optional
	 * );
	 *
	 * @param array $data   An array of information required to send the email.
	 * @param bool  $queue_override If true, forces the email to be queued. If
	 * false, forces the email to be sent immediately. If omitted or set to any
	 * value other than true/false, $this->queue_emails determines whether the
	 * email is queued.
	 *
	 * @return bool true if the operation was successful, else false
	 */
	public function send($data = array(), $queue_override = null)
	{
		// Ensure the required information is supplied
		$to      = isset($data['to']) ? $data['to'] : false;
		$from    = isset($data['from']) ? $data['from'] : settings_item('sender_email');
		$subject = isset($data['subject']) ? $data['subject'] : false;
		$message = isset($data['message']) ? $data['message'] : false;
		$alt_message = isset($data['alt_message']) ? $data['alt_message'] : false;
		$attachments = isset($data['attachments']) ? $data['attachments'] : false;

		// Return false if any required fields are missing
		if ($to == false || $subject == false || $message == false || $from == false) {
			$this->error = lang('em_missing_data');
			return false;
		}

		// Wrap the $message in the email template, or strip HTML
		$mailtype = settings_item('mailtype');
		$templated = $message;
        if ($mailtype == 'html') {
            $templated  = $this->ci->load->view('emailer/email/_header', null, true);
            $templated .= $message;
            $templated .= $this->ci->load->view('emailer/email/_footer', null, true);
        } else {
            $templated = html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8');
        }

		// Are emails queued?
		if ($queue_override === true
            || ($queue_override !== false && $this->queue_emails == true)
           ) {
			return $this->queue_email($to, $from, $subject, $templated, $alt_message, $attachments);
		}

		// Otherwise, send it right now
        return $this->send_email($to, $from, $subject, $templated, $alt_message, $attachments);
	}

	/**
	 * Add the email to the database to be sent out during a cron job.
	 *
	 * @todo Update this code to use the emailer_model
	 *
	 * @param string $to          The email to send the message to
	 * @param string $from        The from email (Ignored in this method, but kept for consistency with the send_email method.
	 * @param string $subject     The subject line of the email
	 * @param string $message     The message for the email, with the template already applied if HTML emails are enabled.
	 * @param string $alt_message An optional text-only version of the message to be sent with HTML emails.
     * @param array  $attachments An optional array containing the location of any files to be attached.
	 *
	 * @return bool true on success, else false
	 */
	private function queue_email($to, $from, $subject, $message, $alt_message = false, $attachments = false)
	{
        $data = array(
            'to_email'  => $to,
            'subject'   => $subject,
            'message'   => $message,
        );

		if ($alt_message) {
			$data['alt_message'] = $alt_message;
		}

        if (is_array($attachments)) {
            $csv_of_file_locations = '';
            foreach ($attachments as $attachment) {
                $csv_of_file_locations .= $attachment . ',';
            }
            $data['csv_attachment'] = $csv_of_file_locations;
        }

		if ($this->debug) {
			$this->debug_message = lang('em_no_debug');
		}

		return $this->ci->db->insert('email_queue', $data);
	}

	/**
	 * Sends the email immediately.
	 *
	 * @param string $to          The email to send the message to
	 * @param string $from        The from email.
	 * @param string $subject     The subject line of the email
	 * @param string $message     The message for the email, with the template already applied if HTML emails are enabled.
	 * HTML emails.
	 * @param string $alt_message An optional, text-only version of the message
	 * to be sent with HTML emails.
	 * @param array  $attachments An optional array, the array contains the
	 * location of the files to be attached.
	 *
	 * @return bool true on success, else false
	 */
	private function send_email($to, $from, $subject, $message, $alt_message = false, $attachments = false)
	{
		$this->ci->load->library('email');
		$this->ci->load->model('settings/settings_model', 'settings_model');

		$this->ci->email->initialize(
            $this->ci->settings_model->select(array('name', 'value'))
                                     ->find_all_by('module', 'email')
        );
        $this->ci->email->clear(true);
		$this->ci->email->set_newline("\r\n");
		$this->ci->email->to($to);
		$this->ci->email->from($from, settings_item('site.title'));
		$this->ci->email->subject($subject);
		$this->ci->email->message($message);

		if ($alt_message) {
			$this->ci->email->set_alt_message($alt_message);
		}

        if (is_array($attachments)) {
            foreach ($attachments as $attachment) {
                $this->ci->email->attach($attachment);
            }
        }

		if (defined('ENVIRONMENT') && ENVIRONMENT == 'development'
            && $this->ci->config->item('emailer.write_to_file') === true
           ) {
			if ( ! function_exists('write_file')) {
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

		return $result;
	}

	/**
	 * Process the email queue in chunks.
	 *
	 * Defaults to 33 which, if processed every 5 minutes, equals 400/hour and
	 * should keep you safe with most ISPs. Always check your ISP's terms of
	 * service to verify, though.
	 *
	 * @param int $limit An int specifying how many emails to process at once.
	 *
	 * @return bool true on success, else false
	 */
	public function process_queue($limit = 33)
	{
		$config_settings = $this->ci->settings_model->select(array('name', 'value'))
                                                    ->find_all_by('module', 'email');

		// Grab records where success = 0
		$query = $this->ci->db->limit($limit)
                              ->where('success', 0)
                              ->get('email_queue');

		$success = true;

        // If the query returned no rows, the queue is empty, so it has been
        // processed successfully
        if ( ! $query->num_rows()) {
            return $success;
        }

        $emails = $query->result();
		$this->ci->load->library('email');

        // MySQL datetime format
        $dateTimeFormat = 'Y-m-d H:i:s';
        $now = new DateTime();

		foreach ($emails as $email) {
			//echo '.';
			$this->ci->email->initialize($config_settings);
			$this->ci->email->clear(true);
			$this->ci->email->set_newline("\r\n");
			$this->ci->email->to($email->to_email);
			$this->ci->email->from(settings_item('sender_email'), settings_item('site.title'));
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

            $data = array(
                'attempts' => $email->attempts + 1,
            );

            // Email was successfully sent
			if ($this->ci->email->send() === true) {
                $data['success'] = 1;
			}
            // Error sending email
			else {
                // While explicitly setting 'success' to 0 is not necessary, it
                // makes it easier to check whether 'date_sent' should be set
                // below
                $data['success'] = 0;

				if ($this->debug) {
					$this->debug_message = $this->ci->email->print_debugger();
				}

                // Note that $success is only set true before the loop, so,
                // while the loop continues attempting to send queued emails
                // after a failure, it will still indicate a failure if only
                // a single email fails
				$success = false;
			}

            // Update the timestamp with the current time, this is done after
            // calling email->send() because sending the email could take time
            $timeStamp = $now->setTimestamp(time())->format($dateTimeFormat);
            $data['last_attempt'] = $timeStamp;
            if ($data['success'] == 1) {
                $data['date_sent'] = $timeStamp;
            }

            // @todo modify this to use the emailer_model?
            $this->ci->db->where('id', $email->id)
                         ->update('email_queue', $data);
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
/* End of file emailer.php */