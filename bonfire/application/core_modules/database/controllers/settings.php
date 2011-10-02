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

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Settings.View');
		
		$this->load->helper('config_file');
		$this->lang->load('database');
		
		Template::set('toolbar_title', 'Database Settings');
		
	}
	
	//--------------------------------------------------------------------
	

	public function index() 
	{		
		Assets::add_js($this->load->view('settings/database_js', null, true), 'inline');

		Template::set('settings', read_db_config());
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function edit() 
	{
		$this->load->library('form_validation');
	
		$server_type = $this->uri->segment(5);
		
		if ($this->input->post('submit'))
		{
			$this->form_validation->set_rules('server_type', lang('db_server_type'), 'required|trim|max_length[20]|xss_clean');
			$this->form_validation->set_rules('hostname', lang('db_hostname'), 'required|trim|max_length[120]|xss_clean');
			$this->form_validation->set_rules('database', lang('db_dbname'), 'required|trim|max_length[120]|xss_clean');
			$this->form_validation->set_rules('username', lang('bf_username'), 'trim|xss_clean');
			$this->form_validation->set_rules('password', lang('bf_password'), 'trim|xss_clean');
		
			if ($this->form_validation->run() !== FALSE)
			{
				unset($_POST['server_type'], $_POST['submit']);

				if (write_db_config(array($server_type => $_POST)) == TRUE)
				{
					Template::set_message(lang('db_successful_save'), 'success');
					$this->activity_model->log_activity($this->auth->user_id(), $server_type . ' : ' . lang('db_successful_save_act'), 'database');
				}
				else 
				{
					Template::set_message(lang('db_erroneous_save'), 'error');
					$this->activity_model->log_activity($this->auth->user_id(), $server_type . ' : ' . lang('db_erroneous_save_act'), 'database');
				}
			}
		}
		
		$settings = read_db_config($server_type);
		
		if (! empty ($settings))
		{
			Template::set('db_settings', $settings[$server_type]);
		}
	
		Template::set('server_type', $server_type);
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}

// End Database Settings class