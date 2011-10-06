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
		$this->auth->restrict('Bonfire.Roles.Manage');
		
		$this->load->model('role_model');
		
		$this->lang->load('roles');
		
		Assets::add_module_css('roles', 'css/settings.css');
		
		// for the render_search_box()
		$this->load->helper('ui/ui');
		
		Assets::add_js('js/jquery.tablehover.pack.js');
	}
		
	//--------------------------------------------------------------------
		
	public function index() 
	{
		// Get User Counts
		Assets::add_js($this->load->view('settings/js', null, true), 'inline');
		
		Template::set('role_counts', $this->user_model->count_by_roles());
		Template::set('total_users', $this->user_model->count_all());
		
		Template::set('deleted_users', $this->user_model->count_all(true));
	
		Template::set('roles', $this->role_model->find_all());
	
		Template::set('toolbar_title', lang("role_manage"));
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function create() 
	{
		if ($this->input->post('submit'))
		{
			if ($this->save_role())
			{
				Template::set_message('Role successfully created.', 'success');
				Template::redirect(SITE_AREA .'/settings/roles');
			}
			else 
			{
				Template::set_message('There was a problem creating the role: '. $this->role_model->error, 'error');
			}
		}
	
		Template::set('toolbar_title', 'Create New Role');
		Template::set_view('settings/role_form');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function edit() 
	{
		$id = (int)$this->uri->segment(5);
		
		if (empty($id))
		{
			Template::set_message('Invalid Role ID.', 'error');
			redirect(SITE_AREA .'/settings/roles');
		}
	
		$this->auth->restrict('Bonfire.Roles.Manage');
	
		if ($this->input->post('submit'))
		{
			if ($this->save_role('update', $id))
			{
				Template::set_message('Role successfully saved.', 'success');
				// redirect to update the sidebar which will show old name otherwise.
				Template::redirect(SITE_AREA .'/settings/roles');
			}
			else 
			{
				Template::set_message('There was a problem saving the role: '. $this->role_model->error);
			}
		}
		
		Template::set('role', $this->role_model->find($id));
	
		Template::set('toolbar_title', 'Edit Role');
		Template::set_view('settings/role_form');
		Template::render();		
	}
	
	//--------------------------------------------------------------------
	
	public function delete() 
	{	
		$id = $this->uri->segment(5);
	
		if (!empty($id))
		{	
			$this->auth->restrict('Bonfire.Roles.Manage');

			if ($this->role_model->delete($id))
			{
				Template::set_message('The Role was successfully deleted.', 'success');
			} else
			{
				Template::set_message('We could not delete the role: '. $this->role_model->error, 'error');
			}
		}
		
		redirect(SITE_AREA .'/settings/roles');
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	Builds the matrix for display in the role permissions form.
	 *
	 * @access	public
	 * @return	string	The table(s) of settings, ready to be used in a form.
	 */
	public function matrix() 
	{
		$id = (int)$this->uri->segment(5);
		$role = $this->role_model->find($id);
		
		// Verify role has permission to modify this role's access control
		if ($this->auth->has_permission('Permissions.'.ucwords($role->role_name).'.Manage')) {
			$permissions_full = $role->permissions;
			
			$role_permissions = $role->role_permissions;
	
			$template = array();
			foreach ($permissions_full as $key => $perm)
			{
				$template[$perm->name]['perm_id'] = $perm->permission_id;
				$template[$perm->name]['value'] = 0;
				if(isset($role_permissions[$perm->permission_id]) )
				{
					$template[$perm->name]['value'] = 1;
				}
			}
	
			// Extract our pieces from each permission
			$domains = array();
			
			foreach ($template as $key => $value)
			{
				list($domain, $name, $action) = explode('.', $key);
				
				// Add it to our domains if it's not already there.
				if (!empty($domain) && !array_key_exists($domain, $domains))
				{
					$domains[$domain] = array();
				}
				
				// Add the preference to the domain array
				if (!isset($domains[$domain][$name]))
				{
					$domains[$domain][$name] = array(
						$action => $value
					);
				}
				else 
				{
					$domains[$domain][$name][$action] = $value;
				}
				
				// Store the actions separately for building the table header
				if (!isset($domains[$domain]['actions']))
				{
					$domains[$domain]['actions'] = array();
				}
				
				if (!in_array($action, $domains[$domain]['actions']))
				{
					$domains[$domain]['actions'][] = $action;
				}
			}
			$auth_failed = '';
		} else {
			$auth_failed = lang('matrix_auth_fail');
			$domains = '';
		}

		// Build the table(s) in the view to make things a little clearer,
		// and return it!
		return $this->load->view('settings/matrix', array('domains' => $domains, 'authentication_failed' => $auth_failed), true);
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	public function unique_email($str) 
	{	
		if ($this->user_model->is_unique('email', $str))
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('unique_email', 'The %s address is already in use. Please choose another.');
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	public function save_role($type='insert', $id=0) 
	{	
		if ($type == 'insert')
		{
			$this->form_validation->set_rules('role_name', 'Role Name', 'required|trim|strip_tags|callback_unique_role|max_length[60]|xss_clean');
		}
		else 
		{
			$this->form_validation->set_rules('role_name', 'Role Name', 'required|trim|strip_tags|max_length[60]|xss_clean');
		}
		$this->form_validation->set_rules('description', 'Description', 'trim|strip_tags|max_length[255]|xss_clean');
		
		if ($this->form_validation->run() === false)
		{
			return false;
		}
		
		// Grab our permissions out of the POST vars, if it's there.
		// We'll need it later.
		$permissions = $this->input->post('role_permissions');
		unset($_POST['role_permissions']);
		
		// grab the current role model name
		$current_name = $this->role_model->find($id);

		if ($type == 'insert')
		{
			$id = $this->role_model->insert($_POST);
			
			if (is_numeric($id))
			{
				$return = true;
			} else
			{
				$return = false;
			}
		}
		else if ($type == 'update')
		{
			$return = $this->role_model->update($id, $_POST);
		}
		
		// Add a new management permission for the role.
		if ($type ==  'insert')	{
			$add_perm = array(
				'name'=>'Permissions.'.ucwords($this->input->post('role_name')).'.Manage',
				'description'=>'To manage the access control permissions for the '.ucwords($this->input->post('role_name')).' role.',
				'status'=>'active'
			);
			if ( $this->permission_model->insert($add_perm) ) {
				$prefix = $this->db->dbprefix;
				// give current_role, or admin fallback, access to manage new role ACL
				$assign_role = $this->session->userdata('role_id') ? $this->session->userdata('role_id') : 1;
				$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(".$assign_role.",".$this->db->insert_id().")");
			} else {
				$this->error = 'There was an error creating the ACL permission.';
			}
		}
		else
		{
			// update the permission name (did it this way for brevity on the update_where line)
			$new_perm_name = 'Permissions.'.ucwords($this->input->post('role_name')).'.Manage';
			$old_perm_name = 'Permissions.'.ucwords($current_name->role_name).'.Manage';
			$this->permission_model->update_where('name',$old_perm_name,array('name'=>$new_perm_name));
		}
		
		// Save the permissions.
		if ($permissions && !$this->role_permission_model->set_for_role($id, $permissions))
		{
			$this->error = 'There was an error saving the permissions.';
		}
		
		unset($permissions);
		return $return;
	}
	
	//--------------------------------------------------------------------

	public function unique_role($str) 
	{	
		if ($this->role_model->is_unique('role_name', $str))
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('unique_role', 'The %s role is already in use. Please choose another.');
			return false;
		}
	}
	
	
	// --------------------------------------------------------------------
	
	/*
		Method: permission_matrix()
		
		Creates a real-time modifiable summary table of all roles and permissions
		
		Parameter:
			none
										
		Return:
			rendered view of all permissions
	*/
	public function permission_matrix()
	{
		// for the permission matrix
		$this->load->helper('inflector');
		Assets::add_js($this->load->view('settings/js', null, true), 'inline');
		
		Template::set('roles', $this->role_model->find_all());
		Template::set('matrix_permissions', $this->permission_model->select('permission_id, name')->order_by('name')->find_all());
		Template::set('matrix_roles', $this->role_model->select('role_id, role_name')->find_all());
		
		$role_permissions = $this->role_permission_model->find_all_role_permissions();
		foreach($role_permissions as $rp) {
			$current_permissions[] = $rp->role_id.','.$rp->permission_id;
		}
		Template::set('matrix_role_permissions', $current_permissions);
		
		if (!Template::get("toolbar_title"))
		{
			Template::set("toolbar_title", lang("role_manage"));
		}
		
		Template::set_view('settings/permission_matrix');
		Template::render();
	}
	
	
	// --------------------------------------------------------------------
	
	/*
		Method: matrix_update()
		
		Updates the role_permissions table.
		
		Responses use "die()" instead of "echo()" in case the profiler is 
		enabled. The profiler will add a lot of HTML to the end of the response
		which causes errors.
		
		Parameter:
			$role_perm	- A CSV string of the role and the permission to modify	
			$action		- boolean ()True = Insert, False = Delete)
													
		Return:
			string result
	*/
	
	public function matrix_update()
	{
		$pieces = explode(',',$this->input->post('role_perm', true));
		
		if (!$this->auth->has_permission('Permissions.'.$this->role_model->find( (int) $pieces[0])->role_name.'.Manage')) {
			die(lang("matrix_auth_fail"));
			return false;
		}
		
		if ($this->input->post('action', true) == 'true') { 
			if(is_numeric($this->role_permission_model->create_role_permissions($pieces[0],$pieces[1]))) {
				die(lang("matrix_insert_success"));
			} else {
				die(lang("matrix_insert_fail") . $this->role_permission_model->error);		
			}
		} else {
			if($this->role_permission_model->delete_role_permissions($pieces[0],$pieces[1])) {
				die(lang("matrix_delete_success"));
			} else {
				die(lang("matrix_delete_fail"). $this->role_permission_model->error);
			}
		}
		
	}
	
	//--------------------------------------------------------------------
	
}

// End User Admin class