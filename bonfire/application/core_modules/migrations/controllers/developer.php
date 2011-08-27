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

class Developer extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Developer.View');
		$this->auth->restrict('Bonfire.Database.Manage');
		
		$this->config->load('migrations');
		$this->load->library('Migrations');	
		$this->lang->load('migrations');
	}

	//--------------------------------------------------------------------
	
	public function index() 
	{
		if ($this->input->post('submit') == lang('mig_migrate_button'))
		{
			$core = $this->input->post('core_only') ? '' : 'app_';
			
			if ($version = $this->input->post('migration'))
			{
				redirect(SITE_AREA .'/developer/migrations/migrate_to/'. $version .'/'. $core);
			}
		}
		
		Assets::add_js('jquery-ui-1.8.8.min');
	
		Template::set('installed_version', $this->migrations->get_schema_version('app_'));
		Template::set('latest_version', $this->migrations->get_latest_version('app_'));
	
		Template::set('core_installed_version', $this->migrations->get_schema_version());
		Template::set('core_latest_version', $this->migrations->get_latest_version());
	
		Template::set('core_migrations', $this->migrations->get_available_versions());
		Template::set('app_migrations', $this->migrations->get_available_versions('app_'));
		
		Template::set('mod_migrations', $this->get_module_versions());
		
		Template::set('toolbar_title', 'Database Migrations');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function migrate_to($version=0, $type='') 
	{
		if (!is_numeric($version))
		{
			$version = $this->uri->segment(5);
		}
		
		$result = $this->migrations->version($version, $type);
		
		if ($result !== FALSE)
		{
			if ($result === 0)
			{
				Template::set_message('Successfully uninstalled module\'s migrations.', 'success');

				// Log the activity
				$this->activity_model->log_activity($this->auth->user_id(), 'Migrate Type: '. $type .' Uninstalled Version: ' . $version . ' from: ' . $this->input->ip_address(), 'migrations');

				redirect(SITE_AREA .'/developer/migrations');
			}
			else 
			{
				Template::set_message('Successfully migrated database to version '. $result, 'success');

				// Log the activity
				$this->activity_model->log_activity($this->auth->user_id(), 'Migrate Type: '. $type .' to Version: ' . $version . ' from: ' . $this->input->ip_address(), 'migrations');

				redirect(SITE_AREA .'/developer/migrations');
			}
		} else 
		{
			Template::set_message('There was an error migrating the database.', 'error');
		}

		Template::set_message('No version to migrate to.', 'error');
		redirect(SITE_AREA .'/developer/migrations');
	}
	
	//--------------------------------------------------------------------
	
	public function migrate_module() 
	{
		$module 	= $this->uri->segment(5);
		$file 		= $this->input->post('version');
		
		$version	= $file !== 'uninstall' ? (int)(substr($file, 0, 3)) : 0;
		
		$path = module_path($module, 'migrations');
		
		// Reset the migrations path for this run only.
		$this->migrations->set_path($path);

		// Do the migration
		$this->migrate_to($version, $module .'_');

		// Log the activity
		$this->activity_model->log_activity($this->auth->user_id(), 'Migrate module: ' . $module . ' Version: ' . $version . ' from: ' . $this->input->ip_address(), 'migrations');

	}
	
	//--------------------------------------------------------------------
	
	private function get_module_versions()
	{
		$mod_versions = array();
	
		$modules = module_files(null, 'migrations');
		
		if ($modules === false)
		{
			return false;
		}

		foreach ($modules as $module => $migrations)
		{
			$mod_versions[$module] = array(
				'installed_version'	=> $this->migrations->get_schema_version($module .'_'),
				'latest_version'	=> $this->migrations->get_latest_version($module .'_'),
				'migrations'		=> $migrations['migrations']
			);
		}
		
		return $mod_versions;
	}
	
	//--------------------------------------------------------------------
}

// End Migrations Developer Class
