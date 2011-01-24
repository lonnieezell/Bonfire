<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Emailer {

	/** 
	 *	Whether to queue emails or send immediately.
	 */ 
	public $queue_emails = false;
	
	public $errors = array();
	
	private $debug = false;
	
	private $ci;

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		$this->ci =& get_instance();
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	Handles sending the emails.
	 *
	 * Information about the email should be sent in the $data
	 * array. It looks like: 
	 * 
	 * $data = array(
	 *		'to'			=> '',		// either string or array
	 *		'from'			=> '',		// if string, must be email, otherwise array('email', 'Name')
	 *		'subject'		=> '',		// string
	 *		'message'		=> '',		// string
	 * 		'alt_message'	=> ''		// optional (text alt to html email)
	 * );
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
		if ($to == false || $from == false || $subject == false || $message == false)
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
	
	/**
	 * Add the email to the database to be sent out during a cron job.
	 */
	private function queue_email(&$to=null, &$from=null, &$subject=null, &$message=null, &$alt_message=false) 
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
	
	/**
	 * Sends the email immediately.
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
	
	/**
	 * Process the email queue in chunks.
	 */
	public function process_queue($limit=0) 
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