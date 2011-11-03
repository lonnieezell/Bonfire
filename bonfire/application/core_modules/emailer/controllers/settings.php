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
	Class: Emailer Settings Context
	
	Allows the management of the Emailer. Assists in setting up the proper email
	settings, as well as editing the template and viewing emails that are in the queue.
*/
class Settings extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Settings.View');
		$this->auth->restrict('Bonfire.Emailer.Manage');
		
		Template::set_block('sub_nav', 'settings/_sub_nav');
		
		$this->load->helper('config_file');
		
		$this->lang->load('emailer');
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		$this->load->library('form_validation');
	
		if ($this->input->post('submit'))
		{
			$this->form_validation->set_rules('sender_email', 'System Email', 'required|trim|valid_email|max_length[120]|xss_clean');
			$this->form_validation->set_rules('mailpath', 'Sendmail Path', 'trim|xss_clean');
			$this->form_validation->set_rules('smtp_host', 'SMTP Server Address', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_user', 'SMTP Username', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_pass', 'SMTP Password', 'trim|strip_tags|matches_pattern[[A-Za-z!@#\%$^&+=]{2,20}]');
			$this->form_validation->set_rules('smtp_port', 'SMTP Port', 'trim|strip_tags|numeric|xss_clean');
			$this->form_validation->set_rules('smtp_timeout', 'SMTP timeout', 'trim|strip_tags|numeric|xss_clean');
			
			if ($this->form_validation->run() !== FALSE)
			{
				$data = array(
					'sender_email'	=> $this->input->post('sender_email'),
					'mailtype'		=> $this->input->post('mailtype'),
					'protocol'		=> strtolower($_POST['protocol']),
					'mailpath'		=> $_POST['mailpath'],
					'smtp_host'		=> isset($_POST['smtp_host']) ? $_POST['smtp_host'] : '',
					'smtp_user'		=> isset($_POST['smtp_user']) ? $_POST['smtp_user'] : '',
					'smtp_pass'		=> isset($_POST['smtp_pass']) ? $_POST['smtp_pass'] : '',
					'smtp_port'		=> isset($_POST['smtp_port']) ? $_POST['smtp_port'] : '',
					'smtp_timeout'	=> isset($_POST['smtp_timeout']) ? $_POST['smtp_timeout'] : '5'
				);	
				
				if (write_config('email', $data))
				{
					// Success, so reload the page, so they can see their settings
					Template::set_message('Email settings successfully saved.', 'success');
					redirect(SITE_AREA .'/settings/emailer');
				}
				else 
				{
					Template::set_message('There was an error saving the file: config/email.', 'error');
				}
			}
		}
		
		// Load our current settings
		Template::set(read_config('email'));
		
		Template::set('toolbar_title', 'Email Settings');
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function template() 
	{
		if ($this->input->post('submit'))
		{
			$header = $_POST['header'];
			$footer = $_POST['footer'];
			
			$this->load->helper('file');
			
			write_file(APPPATH .'core_modules/emailer/views/email/_header.php', $header, 'w+');
			write_file(APPPATH .'core_modules/emailer/views/email/_footer.php', $footer, 'w+');
			
			Template::set_message('Template successfully saved.', 'success');
			
			redirect(SITE_AREA .'/settings/emailer/template');
		}
	
		Template::set('toolbar_title', 'Email Template');
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function emails() 
	{
		Template::set('toolbar_title', 'Email Contents');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function test() 
	{
		$this->output->enable_profiler(false);

		$this->load->library('emailer');
		$this->emailer->enable_debug(true);
		
		$data = array(
			'to'		=> $this->input->post('email'),
			'subject'	=> lang('em_test_mail_subject'),
			'message'	=> lang('em_test_mail_body')
		);
		
		$results = $this->emailer->send($data, false);
		
		Template::set('results', $results);
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: queue()
		
		Displays all of the emails currently in the queue to be sent.
	*/
	public function queue() 
	{
		$offset = $this->uri->segment(5);

		$this->load->model('Emailer_model', 'emailer_model', true);

		Template::set('emails', $this->emailer_model->limit($this->limit, $offset)->find_all());
		Template::set('total_in_queue', $this->emailer_model->count_by('date_sent IS NULL'));
		Template::set('total_sent', $this->emailer_model->count_by('date_sent IS NOT NULL'));
	
		$total_emails = $this->emailer_model->count_all();
	
		$this->pager['base_url'] = site_url(SITE_AREA .'/settings/emailer/queue');
		$this->pager['total_rows'] = $total_emails;
		$this->pager['per_page'] = $this->limit;
		$this->pager['uri_segment']	= 5;
		
		$this->pagination->initialize($this->pager);
		
		if ($debug_msg = $this->session->userdata('email_debug'))
		{	
			Template::set('email_debug', $debug_msg);
			$this->session->unset_userdata('email_debug');
			unset($debug_msg);
		}
	
		Template::set('toolbar_title', 'Emailer Queue');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function insert_test() 
	{
		$this->output->enable_profiler(false);

		$this->load->library('emailer');
		
		$data = array(
			'to'		=> config_item('site.system_email'),
			'subject'	=> lang('em_test_mail_subject'),
			'message'	=> lang('em_test_mail_body')
		);
		
		$this->emailer->send($data, true);
		
		redirect(SITE_AREA .'/settings/emailer/queue');
	}
	
	//--------------------------------------------------------------------
	
	public function force_process() 
	{
		$this->load->library('emailer');
		
		ob_start();
		$this->emailer->process_queue();
		ob_end_clean();
		
		redirect(SITE_AREA .'/settings/emailer/queue');
	}
	
	//--------------------------------------------------------------------
	
	
	/*
		Method: preview()
	
		Displays a preview of the email as stored in the database.
		
		Parameters:
			$id		- an INT with the ID of the email to preview from the queue.
	*/
	public function preview($id=0) 
	{
		$this->output->enable_profiler(false);
		
		$this->load->model('emailer/emailer_model');
		
		if (!empty($id) && is_numeric($id))
		{
			$email = $this->emailer_model->find($id);
			
			if ($email)
			{
				Template::set('email', $email);
			
				Template::render('blank');
			}
		}
	}
	
	//--------------------------------------------------------------------
}

// End Admin class