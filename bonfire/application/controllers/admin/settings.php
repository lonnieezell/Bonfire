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
		
		Template::set('toolbar_title', 'Site Settings');
		
		if (!class_exists('Activity_model'))
		{
			$this->load->model('activities/Activity_model', 'activity_model', true);
		}
		
		$this->load->helper('config_file');
	}
	
	//--------------------------------------------------------------------	

	public function index() 
	{
		if ($this->input->post('submit'))
		{
			if ($this->save_settings())
			{
				Template::set_message('Your settings were successfully saved.', 'success');
				redirect(SITE_AREA .'/settings');
			} else 
			{
				Template::set_message('There was an error saving your settings.', 'error');
			}
		}
		
		// Read our current settings
		Template::set('settings', read_config('application'));

		Template::set_view('admin/settings/index');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	private function save_settings() 
	{
		$this->form_validation->set_rules('title', lang('bf_site_name'), 'required|trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('system_email', lang('bf_site_email'), 'required|trim|strip_tags|valid_email|xss_clean');
		$this->form_validation->set_rules('list_limit','Items <em>p.p.</em>', 'required|trim|strip_tags|numeric|xss_clean');
		
		if ($this->form_validation->run() === false)
		{
			return false;
		}
		
		$data = array(
			'site.title' 		=> $this->input->post('title'),
			'site.system_email'	=> $this->input->post('system_email'),
			'site.status'		=> $this->input->post('status'),
			'site.list_limit'	=> $this->input->post('list_limit'),
			
			'auth.allow_register'	=> isset($_POST['allow_register']) ? 1 : 0,
			'auth.login_type'		=> $this->input->post('login_type'),
			'auth.use_usernames'	=> isset($_POST['use_usernames']) ? $this->input->post('use_usernames') : 0,
			'auth.use_own_names'	=> isset($_POST['use_own_names']) ? $this->input->post('use_own_names') : 0,
			'auth.allow_remember'	=> isset($_POST['allow_remember']) ? 1 : 0,
			'auth.remember_length'	=> (int)$this->input->post('remember_length'),
			'auth.use_extended_profile' => isset($_POST['use_ext_profile']) ? 1 : 0,
			
			'updates.do_check'		=> isset($_POST['do_check']) ? 1 : 0,
			'updates.bleeding_edge'	=> isset($_POST['bleeding_edge']) ? 1 : 0,
			'site.show_profiler'	=> isset($_POST['show_profiler']) ? 1 : 0,
			'site.show_front_profiler'	=> isset($_POST['show_front_profiler']) ? 1 : 0,
		);
		
		//destroy the saved update message in case they changed update preferences.
		if ($this->cache->get('update_message'))
		{
			$this->cache->delete('update_message');
		}
		
		// Log the activity
		$this->activity_model->log_activity($this->auth->user_id(), lang('bf_act_settings_saved').': ' . $this->input->ip_address(), 'core');
		
		return write_config('application', $data);
	}
	
	//--------------------------------------------------------------------
	
	

}