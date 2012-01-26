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

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Developer.View');
		
		Template::set('toolbar_title', 'Developer Tools');
	}
	
	//--------------------------------------------------------------------	

	public function index() 
	{
		$modules = module_list();
		$configs = array();
	
		foreach ($modules as $module)
		{
			$configs[$module] = module_config($module);
			if (!isset($configs[$module]['name']))
			{
				$configs[$module]['name'] = ucwords($module);
			}
			else
			{
				if(is_array($configs[$module]['name']))
				{
					if(isset ($configs[$module]['name'][$this->config->item('language')]))
						$configs[$module]['name'] = $configs[$module]['name'][$this->config->item('language')];
					else if(isset ($configs[$module]['name'][$this->config->item('english')]))
						$configs[$module]['name'] = $configs[$module]['name'][$this->config->item('english')];
				}
			}
			if (!isset($configs[$module]['description']))
			{
				$configs[$module]['description'] = '---';
			}
			else
			{
				if(is_array($configs[$module]['description']))
				{
					if(isset ($configs[$module]['description'][$this->config->item('language')]))
						$configs[$module]['description'] = $configs[$module]['description'][$this->config->item('language')];
					else if(isset ($configs[$module]['description'][$this->config->item('english')]))
						$configs[$module]['description'] = $configs[$module]['description'][$this->config->item('english')];
				}
			}                        
		}
		
		ksort($configs);
		Template::set('modules', $configs);
	
		Template::set_view('admin/developer/index');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}