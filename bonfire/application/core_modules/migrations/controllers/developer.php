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
		Template::set('installed_version', $this->migrations->get_schema_version());
		Template::set('latest_version', $this->migrations->get_latest_version());
	
		Template::set('toolbar_title', 'Database Migrations');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function migrate_to($version=0) 
	{
		if ($this->input->post('submit') == 'Migrate Database')
		{
			$result = $this->migrations->version($version);
			
			if ($result)
			{
				Template::set_message('Successfully migrated database to version '. $result, 'success');
			} else 
			{
				Template::set_message('There was an error migrating the database.', 'error');
			}
			redirect('admin/developer/migrations');
		}
	
		Template::set('installed_version', $this->migrations->get_schema_version());
		Template::set('latest_version', $this->migrations->get_latest_version());
		Template::set('migrations', $this->migrations->get_available_versions());
	
		Template::set('toolbar_title', 'Database Migrations');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}

// End Migrations Developer Class