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

	/*
		Var: $actions
		Stores the available menu actions.
	*/
	private $actions = array();
	
	/*
		Var: $menu
		Stores the organized menu actions.
	*/
	private $menu	= array();

	//--------------------------------------------------------------------

	/*
		Method: index()
		
		Builds the main navigation menu for each context.
		
		Parameters:
			$context	- The context to build the nav for.
			
		Returns:
			The HTML necessary to display the menu.
	*/
	public function index($context=null) 
	{	
		// Get a list of modules with a controller matching
		// $context ('content', 'appearance', 'settings', 'statistics', or 'developer')
		foreach (module_list() as $module)
		{
			if (module_controller_exists($context, $module))
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
		
		// Build up our menu array
		foreach ($this->actions as $module)
		{
			// Make sure the user has permission to view this page.
			if ((isset($permissions[$context][$module]) && has_permission($permissions[$context][$module])) || !array_key_exists($module, $permissions[$context]))
			{
				// Grab our module config array, if any.
				$mod_config = module_config($module);
				
				$display_name = isset($mod_config['name']) ? $mod_config['name'] : $module;
				$title = isset($mod_config['description']) ? $mod_config['description'] : $module;
				
				$menu_topic = isset($mod_config['menu_topic'][$context]) ? $mod_config['menu_topic'][$context] : $display_name;
				
				// Drop-down menus?
				if (isset($mod_config['menus']) && isset($mod_config['menus'][$context]))
				{ 
					$menu_view = $mod_config['menus'][$context];
				} else
				{
					$menu_view = '';
				}
				
				$this->menu[$menu_topic][$module] = array(
						'title'			=> $title,
						'display_name'	=> $display_name,
						'menu_view'		=> $menu_view,
						'menu_topic'	=> $menu_topic
				);
			}
		}

		return $this->build_menu($context);
	}
	
	//--------------------------------------------------------------------

	/*
		Method: build_menu()
		
		Handles building out the HTML for the menu.
		
		Parameters:
			$context	- The context to build the menu for.	
	*/
	private function build_menu($context) 
	{
		// Build a ul to return
		$list = "<ul class='nav-sub clearfix'>\n";
		
		//echo '<pre>'; die(print_r($this->menu));
		
		foreach ($this->menu as $topic_name => $topic)
		{		
			// If the topic has other items, we're not closed.
			$closed = true;
			
			// If there is more than one item in the topic, we need to build
			// out a menu based on the multiple items.
			if (count($topic) > 1)
			{
				$class = '';
			
				$list .= '<li><span{class}>'. ucwords($topic_name) .'</span>';
				$list .= '<ul>';
				
				foreach ($topic as $module => $vals)
				{ 	
					$class = $module == $this->uri->segment(3) ? ' class="current"' : '';
				
					// If it has a sub-menu, echo out that menu only...
					if (isset($vals['menu_view']) && !empty($vals['menu_view']))
					{ 
						$view = $this->load->view($vals['menu_view'], null, true);
						
						// To maintain backwards compatility, strip out and <ul> tags
						$view = str_ireplace('<ul>', '', $view);
						$view = str_ireplace('</ul>', '', $view);
						
						$list .= $view;
						
						$list = str_replace('{class}', $class, $list);
					}
					// Otherwise, it's a single item, so add it like normal
					else
					{
						$list .= $this->build_item($module, $vals['title'], $vals['display_name'], $context, $vals['menu_view']);
					}
				}
					
				$list .= '</ul></li>';
			}
			else
			{
				foreach ($topic as $module => $vals)
				{ 
					$list .= $this->build_item($module, $vals['title'], $vals['display_name'], $context, $vals['menu_view']);
				}
			}
			
		}
		
		$list .= "</ul>\n";
		
		return $list;
	}
	
	//--------------------------------------------------------------------
	
	
	/*
		Method: build_item()
		
		Handles building an individual list item (with sub-menus) for the menu.
		
		Parameters:
			$module			- The name of the module this link belongs to
			$title			- The title used on the link
			$display_name	- The name to display in the menu
			$context		- The name of the context
			$menu_view		- The name of the view file that contains the sub-menu
			
		Returns:
			The HTML necessary for a single item and it's sub-menus.
	*/
	public function build_item($module, $title, $display_name, $context, $menu_view='') 
	{
		// Is this the current module? 	
		$class = $module == $this->uri->segment(3) ? 'class="current"' : '';
		
		$item  = '<li><a href="'. site_url(SITE_AREA .'/'. $context .'/'. $module) .'" '. $class;
		$item .= ' title="'. $title .'">'. ucwords(str_replace('_', '', $display_name)) ."</a>\n";
		
		// Sub Menus?
		if (!empty($menu_view))
		{
			// Only works if it's a valid view...
			$view = $this->load->view($menu_view, null, true);
			
			$item .= $view;
		}
		
		$item .= "</li>\n";
				
		return $item;
	}
	
	//--------------------------------------------------------------------
			
}

// End sidebar class