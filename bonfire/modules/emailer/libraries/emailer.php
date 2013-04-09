<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Emailer Library
 *
 * The Emailer core module makes sending emails a breeze. It uses the
 * default CodeIgniter email library, but extends the functionality to
 * provide the ability to queue emails to be processed later by a CRON
 * job, allowing you to limit the number of emails that are sent per/hour
 * if you have a picky mail server or ISP.
 *
 * It also provides the ability to use HTML email templates, though only
 * one template is supported at the moment.
 *
 * @package    Bonfire
 * @subpackage Modules_Emailer
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Emailer
{


	/**
	 * Whether to send emails immediately or queue them by default.
	 *
	 * If TRUE, will queue emails into the database to be sent later.
	 * If FALSE, will send the email immediately.
	 *
	 * @access public
	 *
	 * @var bool
	 */
	public $queue_emails = FALSE;


	/**
	 * Extra information about the running of the script and the sending of an immediate email.
	 *
	 * @access public
	 *
	 * @var string
	 */
	public $debug_message = '';

	/**
	 * Whether to set $debug_message.
	 *
	 * @access private
	 *
	 * @var bool
	 */
	private $debug = FALSE;

	/**
	 * An error generated during the course of the script running.
	 *
	 * @access public
	 *
	 * @var string
	 */
	public $error = '';


	/**
	 * A pointer to the CodeIgniter instance.
	 *
	 * @access private
	 *
	 * @var object
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

	//--------------------------------------------------------------------

	/**
	 * Handles sending the emails and routing to the appropriate methods
	 * for queueing or sending.
	 *
	 * Information about the email should be sent in the $data
	 * array. It looks like:
	 *
	 * $data = array(
	 *     'to' => '',	// either string or array
	 *     'subject' => '',	// string
	 *     'message' => '',	// string
	 *     'alt_message' => ''	// optional (text alt to html email)
	 * );
	 *
	 * @access public
	 *
	 * @param array $data           An array of required information need to send the email.
	 * @param bool  $queue_override (optional) Overrides the value of $queue_emails.
	 *
	 * @return bool TRUE/FALSE	Whether the operation was successful or not.
	 */
	public function send($data=array(), $queue_override=null)
	{
		// Make sure we have the information we need.
		$to = isset($data['to']) ? $data['to'] : FALSE;
		$from = settings_item('sender_email');
		$subject = isset($data['subject']) ? $data['subject'] : FALSE;
		$message = isset($data['message']) ? $data['message'] : FALSE;
		$alt_message = isset($data['alt_message']) ? $data['alt_message'] : FALSE;

		// If we don't have everything, return FALSE.
		if ($to == FALSE || $subject == FALSE || $message == FALSE)
		{
			$this->error = lang('em_missing_data');
			return FALSE;
		}

		// Wrap the $message in the email template.
		$mailtype = settings_item('mailtype');
		$templated = $message;
		if ($mailtype == 'html')
		{
			$templated  = $this->ci->load->view('emailer/email/_header', null, TRUE);
			$templated .= $message;
			$templated .= $this->ci->load->view('emailer/email/_footer', null, TRUE);
		}

		// Should we put it in the queue?
		if ($queue_override === TRUE || ($queue_override !== FALSE && $this->queue_emails == TRUE))
		{
			return $this->queue_email($to, $from, $subject, $templated, $alt_message);
		}
		// Otherwise, we're sending it right now.
		else
		{
			return $this->send_email($to, $from, $subject, $templated, $alt_message);
		}

	}//end send()

	//--------------------------------------------------------------------

	/**
	 * Add the email to the database to be sent out during a cron job.
	 *
	 * @access private
	 *
	 * @param string $to          The email to send the message to
	 * @param string $from        The from email (Ignored in this method, but kept for consistency with the send_email method.
	 * @param string $subject     The subject line of the email
	 * @param string $message     The text to be inserted into the template for HTML emails.
	 * @param string $alt_message An optional, text-only version of the message to be sent with HTML emails.
	 *
	 * @return bool TRUE/FALSE	Whether it was successful or not.
	 */
	private function queue_email($to=null, $from=null, $subject=null, $message=null, $alt_message=FALSE)
	{
		$this->ci->db->set('to_email', $to);
		$this->ci->db->set('subject', $subject);
		$this->ci->db->set('message', $message);

		if ($alt_message)
		{
			$this->ci->db->set('alt_message', $alt_message);
		}

		if ($this->debug)
		{
			$this->debug_message = lang('em_no_debug');
		}
		return $this->ci->db->insert('email_queue');

	}//end queue_email

	//--------------------------------------------------------------------

	/**
	 * Sends the email immediately.
	 *
	 * @access private
	 *
	 * @param string $to          The email to send the message to
	 * @param string $from        The from email.
	 * @param string $subject     The subject line of the email
	 * @param string $message     The text to be inserted into the template for HTML emails.
	 * @param string $alt_message An optional, text-only version of the message to be sent with HTML emails.
	 *
	 * @return bool TRUE/FALSE	Whether it was successful or not.
	 */
	private function send_email($to=null, $from=null, $subject=null, $message=null, $alt_message=FALSE)
	{
		$this->ci->load->library('email');
		$this->ci->load->model('settings/settings_model', 'settings_model');
		$this->ci->email->initialize($this->ci->settings_model->select('name,value')->find_all_by('module', 'email'));

		$this->ci->email->set_newline("\r\n");
		$this->ci->email->to($to);
		$this->ci->email->from($from, settings_item('site.title'));
		$this->ci->email->subject($subject);
		$this->ci->email->message($message);

		if ($alt_message)
		{
			$this->ci->email->set_alt_message($alt_message);
		}

		if ((defined('ENVIRONMENT') && ENVIRONMENT == 'development') && $this->ci->config->item('emailer.write_to_file') === TRUE) {
			if (!function_exists('write_file')) {
				$this->ci->load->helper('file');
			}
			write_file($this->ci->config->item('log_path').str_replace(" ","_",strtolower($subject)).substr(md5($to.time()),0,8).".html",$message);
			$result = TRUE;
		}
		else
		{
			$result = $this->ci->email->send();
		}

		if ($this->debug)
		{
			$this->debug_message = $this->ci->email->print_debugger();
		}

		return $result;

	}//end send_email()

	//--------------------------------------------------------------------

	/**
	 * Process the email queue in chunks.
	 *
	 * Defaults to 33 which, if processed every 5 minutes, equals 400/hour
	 * And should keep you safe with most ISP's. Always check your ISP's
	 * terms of service to verify, though.
	 *
	 * @access public
	 *
	 * @param int $limit An int specifying how many emails to process at once.
	 *
	 * @return bool TRUE/FALSE	Whether the method was successful or not.
	 */
	public function process_queue($limit=33)
	{
		$success = TRUE;

		//$limit = 33; // 33 emails every 5 minutes = 400 emails/hour.
		$this->ci->load->library('email');

		$config_settings = $this->ci->settings_model->select('name,value')->find_all_by('module', 'email');

		// Grab records where success = 0
		$this->ci->db->limit($limit);
		$this->ci->db->where('success', 0);
		$query = $this->ci->db->get('email_queue');

		if ($query->num_rows() > 0)
		{
			$emails = $query->result();
		}
		else
		{
			return TRUE;
		}

		foreach($emails as $email)
		{
			echo '.';

			$this->ci->email->clear();
			$this->ci->email->initialize($config_settings);

			$this->ci->email->from(settings_item('sender_email'), settings_item('site.title'));
			$this->ci->email->to($email->to_email);

			$this->ci->email->subject($email->subject);
			$this->ci->email->message($email->message);
			$this->ci->email->set_newline("\r\n");

			if ($email->alt_message)
			{
				$this->ci->email->set_alt_message($email->alt_message);
			}

			$prefix = $this->ci->db->dbprefix;

			if ($this->ci->email->send() === TRUE)
			{
				// Email was successfully sent
				$sql = "UPDATE {$prefix}email_queue SET success=1, attempts=attempts+1, last_attempt = NOW(), date_sent = NOW() WHERE id = " .$email->id;
				$this->ci->db->query($sql);
			}
			else
			{
				// Error sending email
				$sql = "UPDATE {$prefix}email_queue SET attempts = attempts+1, last_attempt=NOW() WHERE id=". $email->id;
				$this->ci->db->query($sql);

				if ($this->debug)
				{
					$this->debug_message = $this->ci->email->print_debugger();
				}

				$success = FALSE;
			}
		}//end foreach

		return $success;

	}//end process_queue()

	//--------------------------------------------------------------------

	/**
	 * Tells the emailer lib whether to generate debugging messages.
	 *
	 * @access public
	 *
	 * @param bool $enable_debug TRUE/FALSE - enable/disable debugging messages
	 */
	public function enable_debug($enable_debug)
	{
		$this->debug = $enable_debug;

	}//end enable_debug()

	//--------------------------------------------------------------------

	/**
	 * Specifies whether to queue emails in the send() method.
	 *
	 * @param bool $queue Queue emails instead of sending them directly.
	 *
	 * @return void
	 */
	public function queue_emails($queue)
	{
		if ($queue !== TRUE && $queue !== FALSE)
		{
			return;
		}

		$this->queue_emails = $queue;

	}//end queue_emails()

	//--------------------------------------------------------------------

}//end class

/* End of file emailer.php */
