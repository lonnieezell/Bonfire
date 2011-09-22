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
	
	/*
		Var: $config
		
		Holds the config settings from config/email.php
		
		Access:
			Private
	*/
	private $config	= array();

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		$this->ci =& get_instance();
		
		// CI is refusing to read prefs from the config for some reason,
		// So we'll do it manually...
		if (!function_exists('read_config'))
		{
			$this->ci->load->helper('config_file');
		}
		$this->config = read_config('email');
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
		// Make sure we have the information we need. 
		$to = isset($data['to']) ? $data['to'] : false;
		$from = $this->config['sender_email'];
		$subject = isset($data['subject']) ? $data['subject'] : false;
		$message = isset($data['message']) ? $data['message'] : false;
		$alt_message = isset($data['alt_message']) ? $data['alt_message'] : false;
	
		// If we don't have everything, return false.
		if ($to == false || $subject == false || $message == false)
		{
			$this->errors[] = lang('em_missing_data');
			return false;
		}
		
		// Wrap the $message in the email template.
		$templated  = $this->ci->load->view('emailer/email/_header', null, true);
		$templated .= $message;
		$templated .= $this->ci->load->view('emailer/email/_footer', null, true);
		
		// Should we put it in the queue?
		if ($queue_override == true || $this->queue_emails == true)
		{
			return $this->queue_email($to, $from, $subject, $templated, $alt_message);
		}
		// Otherwise, we're sending it right now.
		else 
		{
			return $this->send_email($to, $from, $subject, $templated, $alt_message);
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
		
		$result['success'] = $this->ci->db->insert('email_queue');
		
		if ($this->debug)
		{
			$result['debug'] = lang('em_no_debug');
		}
		
		return $result;
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
		$this->ci->email->initialize($this->config);
		
		$this->ci->email->to($to);
		$this->ci->email->from($from, $this->ci->config->item('site.title'));
		$this->ci->email->subject($subject);
		$this->ci->email->message($message);
		if ($alt_message)
		{
			$this->ci->email->set_alt_message($alt_message);
		}
				
		$result['success'] = $this->ci->email->send();
		
		if ($this->debug)
		{
			$result['debug'] = $this->ci->email->print_debugger();
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
		
		$this->ci->email->initialize($this->config);
	
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

			$this->ci->email->from($this->config['sender_email'], $this->ci->config->item('site.title'));
			$this->ci->email->to($email->to_email);

			$this->ci->email->subject($email->subject);
			$this->ci->email->message($email->message);
			
			if ($email->alt_message)
			{
				$this->ci->email->set_alt_message($email->alt_message);
			}
	
			$prefix = $this->ci->db->dbprefix;
			
			if ($this->ci->email->send() === TRUE)
			{ 
				// Email was successfully sent
				$sql = "UPDATE {$prefix}email_queue
						SET success=1, attempts=attempts+1, last_attempt = NOW(), date_sent = NOW()
						WHERE id = " .$email->id;
				
				$this->ci->db->query($sql);
			} else 
			{ 
				// Error sending email
				$sql = "UPDATE {$prefix}email_queue
						SET attempts = attempts+1, last_attempt=NOW()
						WHERE id=". $email->id;
				$this->ci->db->query($sql);
				
				if (class_exists('CI_Session'))
				{ 
					$result = $this->ci->email->print_debugger();
					$this->ci->session->set_userdata('email_debug', $result);
				}
			}
		}
		
		return true;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: debug()
		
		Tells the emailer lib to show or hide the debugger string.
	*/
	public function enable_debug($show_debug=false) 
	{
		$this->debug = $show_debug;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: queue_emails()
		
		Specifies whether to queue emails in the send() method. 
		
		Default: 
			false	- Do NOT queue emails. Instead, send them directly.
			
		Return:
			void
	*/	
	public function queue_emails($queue=false) 
	{
		if ($queue !== true && $queue !== false)
		{
			return;
		}
	
		$this->queue_emails = $queue;
	}
	
	//--------------------------------------------------------------------
	
}