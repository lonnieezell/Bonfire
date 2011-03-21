<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Settings.View');
		$this->auth->restrict('Bonfire.Roles.Manage');
		
		$this->load->model('role_model');
		
		Assets::add_js($this->load->view('settings/roles_js', null, true), 'inline');
	}
		
	//--------------------------------------------------------------------
		
	public function index() 
	{
		// Get User Counts
		Template::set('role_counts', $this->user_model->count_by_roles());
		Template::set('total_users', $this->user_model->count_all());
		
		Template::set('deleted_users', $this->user_model->count_all(true));
	
		Template::set('roles', $this->role_model->find_all());
	
		Template::set('toolbar_title', 'Manage User Roles');
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
				Template::redirect('/admin/settings/roles');
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
			redirect('/admin/settings/roles');
		}
	
		$this->auth->restrict('Bonfire.Roles.Manage');
	
		if ($this->input->post('submit'))
		{
			if ($this->save_role('update', $id))
			{
				Template::set_message('Role successfully saved.', 'success');
				//redirect('admin/settings/roles');
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
		
		redirect('/admin/settings/roles');
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
		$permissions = $this->permission_model->find_all();
		
		// Grab a copy of one of the permission sets so that
		// we can break it apart and examine it.
		$template = (array)$permissions[0];
		
		// Clean it up.
		unset($template['permission_id'], $template['role_id']);
		
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
		
		// Build the table(s) in the view to make things a little clearer,
		// and return it!
		return $this->load->view('settings/matrix', array('domains' => $domains), true);
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
		if ($type ==  'insert')
		{
			$this->form_validation->set_rules('role_name', 'Role Name', 'required|trim|strip_tags|callback_unique_role|max_length[60]|xss_clean');
		}
		else 
		{
			$this->form_validation->set_rules('role_name', 'Role Name', 'trim|strip_tags|max_length[60]|xss_clean');
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
		
		// Save the permissions.
		if ($permissions && !$this->permission_model->set_for_role($id, $permissions))
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
	
	//--------------------------------------------------------------------
	
}

// End User Admin class