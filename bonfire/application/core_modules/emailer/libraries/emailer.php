<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Class: Emailer
	
	The Emailer core module makes sending emails a breeze. It uses the 
	default CodeIgniter email library, but extends the functionality to 
	provide the ability to queue emails to be processed later by a CRON
	job, allowing you to limit the number of emails that are sent per/hour
	if you have a picky mail server or ISP.
	
	It also provides the ability to use HTML email templates, though only
	one template is supported at the moment. 
	
	Package: 
		Core Modules
*/
class Emailer {

	/*
		Var: $queue_emails
		
		Whether to send emails immediately or queue them by default.
		
		If true, will queue emails into the database to be sent later. 
		If false, will send the email immediately.
	*/ 
	public $queue_emails = false;
	
	/*
		Var: $errors
		
		An array of errors generated during the course of the script running.
	*/
	public $errors = array();
	
	/*
		Var: $debug
		
		A private variable for reporting extra information about the 
		running of the script and the sending of immediate emails.
		
		Access: 
			Private
	*/
	private $debug = false;
	
	/*
		Var: $ci
		
		A pointer to the CodeIgniter instance.
		
		Access:
			Private
	*/
	private $ci;

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		$this->ci =& get_instance();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: send()
	
		Handles sending the emails and routing to the appropriate methods
		for queueing or sending.
		
		Information about the email should be sent in the $data
		array. It looks like: 
		
		$data = array(
		    'to'			=> '',		// either string or array
		    'subject'		=> '',		// string
		    'message'		=> '',		// string
		    'alt_message'	=> ''		// optional (text alt to html email)
		);
		
		Parameters:
			$data			- An array of required information need to send the email.
			$queue_override	- If true, will queue the email, no matter what the default setting is.
			
		Return:
			true/false		Whether the operation was successful or not.
	*/
	public function send($data=array(), $queue_override=false) 
	{
		$this->ci->config->load('email');
	
		// Make sure we have the information we need. 
		$to = isset($data['to']) ? $data['to'] : false;
		$from = $this->ci->config->item('sender_email');
		$subject = isset($data['subject']) ? $data['subject'] : false;
		$message = isset($data['message']) ? $data['message'] : false;
		$alt_message = isset($data['alt_message']) ? $data['alt_message'] : false;
		
		// If we don't have everything, return false.
		if ($to == false || $subject == false || $message == false)
		{
			$this->errors[] = 'One or more required fields are missing.';
			return false;
		}
		
		// Should we put it in the queue?
		if ($queue_override == true || $this->queue_emails == true)
		{
			return $this->queue_email($to, $from, $subject, $message, $alt_message);
		}
		// Otherwise, we're sending it right now.
		else 
		{
			return $this->send_email($to, $from, $subject, $message, $alt_message);
		}
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: queue_email()
		
		Add the email to the database to be sent out during a cron job.
		
		Parameters:
			$to				- The email to send the message to
			$from			- The from email (Ignored in this method, but kept for consistency with the send_email method.
			$subject		- The subject line of the email
			$message		- The text to be inserted into the template for HTML emails.
			$alt_message	- An optional, text-only version of the message to be sent with HTML emails.
			
		Return:
			true/false		Whether it was successful or not.
			
		Access: 
			Private
	*/
	private function queue_email(&$to=null, &$from, &$subject=null, &$message=null, &$alt_message=false) 
	{
		$this->ci->db->set('to_email', $to);
		$this->ci->db->set('subject', $subject);
		$this->ci->db->set('message', $message);
		if ($alt_message)
		{
			$this->ci->db->set('alt_message', $alt_message);
		}
		
		return $this->ci->db->insert('email_queue');
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: send_email()
	
		Sends the email immediately.
		
		Parameters:
			$to				- The email to send the message to
			$from			- The from email.
			$subject		- The subject line of the email
			$message		- The text to be inserted into the template for HTML emails.
			$alt_message	- An optional, text-only version of the message to be sent with HTML emails.
			
		Return:
			true/false		Whether it was successful or not.
		
		Access: 
			Private
	*/
	private function send_email(&$to=null, &$from=null, &$subject=null, &$message=null, &$alt_message=false) 
	{
		$this->ci->load->library('email');
		
		$this->ci->email->to($to);
		$this->ci->email->from($from);
		$this->ci->email->subject($subject);
		$this->ci->email->message($message);
		if ($alt_message)
		{
			$this->ci->email->set_alt_message($alt_message);
		}
				
		$result = $this->ci->email->send();
		
		if ($this->debug)
		{
			echo $this->ci->email->print_debugger();
		}
		
		return $result;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: process_queue()
	
		Process the email queue in chunks. 
		
		Parameters:
			$limit	- An int specifying how many emails to process at once. 
					  Defaults to 33 which, if processed every 5 minutes, equals 400/hour
					  And should keep you safe with most ISP's. Always check your ISP's 
					  terms of service to verify, though.
					  
		Return: 
			true/false	Whether the method was successful or not.
	*/
	public function process_queue($limit=33) 
	{
		//$limit = 33; // 33 emails every 5 minutes = 400 emails/hour.
		$this->ci->load->library('email');
		
		$this->ci->config->load('email');
				
		// Grab records where success = 0
		$this->ci->db->limit($limit);
		$this->ci->db->where('success', 0);
		$query = $this->ci->db->get('email_queue');
		
		if ($query->num_rows() > 0)
		{
			$emails = $query->result();
		} else
		{
			return true;
		}
		
		foreach($emails as $email)
		{
			echo '.'; 
			
			$this->ci->email->clear();
			
			$this->ci->email->from($this->ci->config->item('sender_email'));
			$this->ci->email->to($email->to_email);

			$this->ci->email->subject($email->subject);
			$this->ci->email->message($email->message);
			
			if ($email->alt_message)
			{
				$this->ci->email->set_alt_message($email->alt_message);
			}
			
			if ($this->ci->email->send())
			{
				// Email was successfully sent
				$sql = "UPDATE email_queue
						SET success=1, attempts=attempts+1, last_attempt = NOW(), date_sent = NOW()
						WHERE id = " .$email->id;
				
				$this->ci->db->query($sql);
			} else 
			{
				// Error sending email
				$sql = "UPDATE email_queue
						SET attempts = attempts+1, last_attempt=NOW()
						WHERE id=". $email->id;
				$this->ci->db->query($sql);
			}
		}
		
		return true;
	}
	
	//--------------------------------------------------------------------
	

}