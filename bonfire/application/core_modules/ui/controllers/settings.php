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
		
		$this->auth->restrict('Bonfire.UI.Manage');
		$this->lang->load('ui');
		
		Template::set('toolbar_title', 'UI Settings');
		
		if (!class_exists('Activity_model'))
		{
			$this->load->model('activities/Activity_model', 'activity_model', true);
		}
		
		Assets::add_js($this->load->view('settings/js', null, true), 'inline');
	}
	
	//--------------------------------------------------------------------	

	public function index() 
	{
		if ($this->input->post('submit'))
		{
			if ($this->save_settings())
			{
				Template::set_message('Your settings were successfully saved.', 'success');
				redirect(uri_string());
			}
			else 
			{
				Template::set_message('There was an error saving your settings.', 'error');
			}
		}
		
		// Read our current settings from the application config
		Template::set('current', config_item('ui.current_shortcuts'));
		
		$settings = $this->settings_lib->item('ui.shortcut_keys');
		Template::set('settings', unserialize($settings));

		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	private function save_settings() 
	{
		$this->form_validation->set_rules('actions', lang('ui_actions'), 'required|is_arrsy');
		$this->form_validation->set_rules('shortcuts', lang('ui_shortcuts'), 'required|is_arrsy');
		
		if ($this->form_validation->run() === false)
		{
			return false;
		}
		
		$actions   = $this->input->post('actions');
		$shortcuts = $this->input->post('shortcuts');
		
		$setting_array = array();
		$data = array();
		if (count($actions) == count($shortcuts))
		{
			foreach ($actions as $key => $name)
			{
				if (!empty($shortcuts[$key]))
				{
					$setting_array[$name] = $shortcuts[$key];
				}
			}
		}

		//destroy the saved update message in case they changed update preferences.
		if ($this->cache->get('update_message'))
		{
			$this->cache->delete('update_message');
		}
		
		// save the settings to the DB
		$updated = $this->settings_lib->set('ui.shortcut_keys', serialize($setting_array));

		// Log the activity
		$this->activity_model->log_activity($this->auth->user_id(), lang('bf_act_settings_saved').': ' . $this->input->ip_address(), 'ui');

		return $updated;
	}
	
	//--------------------------------------------------------------------
	
	

}