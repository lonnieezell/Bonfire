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

// Emailer Report Controller

class Stats extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Bonfire.Emailer.View');
		
		Template::set('toolbar_title', 'View Emailer Queue');
		$this->lang->load('emailer');
		
		$this->load->model('Emailer_model', 'emailer_model', true);
	}
	
	//--------------------------------------------------------------------
	
	/*
		Lists the emails in the Queue.
	*/
	public function index() 
	{
		$offset = $this->uri->segment(5);

		Template::set('emails', $this->emailer_model->limit($this->limit, $offset)->find_all());
		Template::set('total_in_queue', $this->emailer_model->count_by('date_sent IS NULL'));
		Template::set('total_sent', $this->emailer_model->count_by('date_sent IS NOT NULL'));
	
		$total_emails = $this->emailer_model->count_all();
	
		$this->pager['base_url'] = site_url('admin/stats/emailer/index');
		$this->pager['total_rows'] = $total_emails;
		$this->pager['per_page'] = $this->limit;
		$this->pager['uri_segment']	= 5;
		
		$this->pagination->initialize($this->pager);
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Displays a preview of the email as stored in the database.
	*/
	public function preview($id=0) 
	{
		$this->output->enable_profiler(false);
		
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

/* End of file Stats.php */
/* Location: ./application/controllers/Stats.php */