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

class Subnav extends Base_Controller {

	private $actions = array();

	//--------------------------------------------------------------------

	public function index($type=null) 
	{	
		// Get a list of modules with a controller matching
		// $type ('content', 'appearance', 'settings', 'statistics', or 'developer')
		foreach (module_list() as $module)
		{
			if (module_controller_exists($type, $module))
			{
				$this->actions[] = $module;
			}
		}
		
		// Do we have any actions? 
		if (!count($this->actions))
		{
			return '<ul class="nav-sub clearfix"></ul>';
		}
		
		// Grab our module permissions so we know who can see what on the sidebar
		$permissions = config_item('module_permissions');
		
		// Build a ul to return
		$list = "<ul class='nav-sub clearfix'>\n";
		
		foreach ($this->actions as $module)
		{
			// Make sure the user has permission to view this page.
			if ((isset($permissions[$type][$module]) && has_permission($permissions[$type][$module])) || !array_key_exists($module, $permissions[$type]))
			{
				// Is this the current module? 
				if ($module == $this->uri->segment(3))
				{
					$class = 'class="current"';
				}
				else
				{
					$class = '';
				}
				
				// Grab our module config array, if any.
				$mod_config = module_config($module);
				
				$display_name = isset($mod_config['name']) ? $mod_config['name'] : $module;
				$title = isset($mod_config['description']) ? $mod_config['description'] : $module;
				
				// Build our list item.
				$list .= '<li><a href="'. site_url('admin/'. $type .'/'. $module) .'" '. $class;
				$list .= ' title="'. $title .'">'. ucwords(str_replace('_', '', $display_name)) ."</a>\n";
				
				// Drop-down menus?
				if (isset($mod_config['menus']) && isset($mod_config['menus'][$type]))
				{ 
					// Only works if it's a valid view...
					$view = $this->load->view($mod_config['menus'][$type], null, true);
					
					$list .= $view;
				}
				
				$list .= "</li>\n";
			}
		}
		
		$list .= "</ul>\n";
		
		return $list;
	}
	
	//--------------------------------------------------------------------
	

}

// End sidebar class