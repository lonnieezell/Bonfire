<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2012, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Roles Settings Context
 *
 * Allows the management of the Bonfire roles.
 *
 * @package    Bonfire
 * @subpackage Modules_Roles
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Settings extends Admin_Controller
{

	//--------------------------------------------------------------------

	/**
	 * Sets up the require permissions and loads required classes
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Site.Settings.View');
		$this->auth->restrict('Bonfire.Roles.View');

		$this->load->model('role_model');

		$this->lang->load('roles');

		Assets::add_module_css('roles', 'css/settings.css');
		Assets::add_module_js('roles', 'jquery.tablehover.pack.js');
		Assets::add_module_js('roles', 'js/settings.js');

		// for the render_search_box()
		$this->load->helper('ui/ui');

		Template::set_block('sub_nav', 'settings/_sub_nav');
	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Displays a list of all roles
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function index()
	{
		// Get User Counts
		Template::set('role_counts', $this->user_model->count_by_roles());
		Template::set('total_users', $this->user_model->count_all());

		Template::set('deleted_users', $this->user_model->count_all(TRUE));

		Template::set('roles', $this->role_model->where('deleted', 0)->find_all());

		Template::set('toolbar_title', lang('roles_manage'));
		Template::render();

	}//end index()

	//--------------------------------------------------------------------

	/**
	 * Create a new role in the database
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function create()
	{
		$this->auth->restrict('Bonfire.Roles.New');

		if ($this->input->post('submit'))
		{
			if ($this->save_role())
			{
				Template::set_message(lang('roles_create_failure'), 'success');
				Template::redirect(SITE_AREA .'/settings/roles');
			}
			else
			{
				Template::set_message(lang('roles_create_failure'). $this->role_model->error, 'error');
			}
		}

		Template::set('toolbar_title', lang('roles_create_heading'));
		Template::set_view('settings/role_form');
		Template::render();

	}//end create()

	//--------------------------------------------------------------------

	/**
	 * Edit a role record
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function edit()
	{
		$this->auth->restrict('Bonfire.Roles.Manage');

		$id = (int)$this->uri->segment(5);

		if (empty($id))
		{
			Template::set_message(lang('roles_invalid_id'), 'error');
			redirect(SITE_AREA .'/settings/roles');
		}

		if ($this->input->post('submit'))
		{
			if ($this->save_role('update', $id))
			{
				Template::set_message(lang('roles_edit_success'), 'success');
				// redirect to update the sidebar which will show old name otherwise.
				Template::redirect(SITE_AREA .'/settings/roles');
			}
			else
			{
				Template::set_message(lang('roles_edit_failure'). $this->role_model->error);
			}
		}

		Template::set('role', $this->role_model->find($id));

		Template::set('toolbar_title', lang('roles_edit_heading'));
		Template::set_view('settings/role_form');
		Template::render();

	}//end edit()

	//--------------------------------------------------------------------

	/**
	 * Delete a role record from the database
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function delete()
	{
		$this->auth->restrict('Bonfire.Roles.Manage');

		$id = (int) $this->uri->segment(5);

		if (!empty($id))
		{
			if ($this->role_model->delete($id))
			{
				Template::set_message(lang('roles_delete_success'), 'success');
			}
			else
			{
				Template::set_message(lang('roles_delete_failure') . $this->role_model->error, 'error');
			}
		}

		redirect(SITE_AREA .'/settings/roles');

	}//end delete()

	//--------------------------------------------------------------------

	/**
	 * Builds the matrix for display in the role permissions form.
	 *
	 * @access public
	 *
	 * @return string The table(s) of settings, ready to be used in a form.
	 */
	public function matrix()
	{
		// Make modules list to give translated display names and titles instead of just the module name in english
		$modules_descriptions = array();
		$module_list = module_list();

		foreach ($module_list as $module)
		{
				$mod_config = module_config($module,FALSE,TRUE);

				$modules_descriptions[$module] = array(
					'display_name'	=> isset($mod_config['name']) ? $mod_config['name'] : ucfirst($module),
					'title' 		=> isset($mod_config['description']) ? $mod_config['description'] : ucfirst($module),

				);
		}

		$id = (int)$this->uri->segment(5);
		$role = $this->role_model->find($id);

		// If the ID is empty, we are working with a new
		// role and won't have permissions to show.
		if ($id == 0)
		{
			return '<div class="alert alert-info">'. lang('roles_new_permission_message') .'</div>';
		}

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

					// Add translated display names and titles for modules
					if (array_key_exists(strtolower($name), $modules_descriptions))
					{
						$domains[$domain][$name]['Description'] = $modules_descriptions[strtolower($name)];
					}
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
			}//end foreach

			$auth_failed = '';
		}
		else
		{
			$auth_failed = lang('matrix_auth_failure');
			$domains = '';
		}//end if

		// Build the table(s) in the view to make things a little clearer,
		// and return it!
		return $this->load->view('settings/matrix', array('domains' => $domains, 'modules_descriptions' => $modules_descriptions, 'authentication_failed' => $auth_failed), TRUE);

	}//end matrix()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Callback function to check that the email address entered is unique
	 *
	 * @access public
	 * @todo   Is this used here?
	 *
	 * @param string $str The email address
	 *
	 * @return bool
	 */
	public function unique_email($str)
	{
		if ($this->user_model->is_unique('email', $str))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('unique_email', lang('roles_email_in_use'));
			return FALSE;
		}

	}//end unique_email()

	//--------------------------------------------------------------------

	/**
	 * Saves the role record to the database
	 *
	 * @access public
	 *
	 * @param string $type The type of save operation (insert or edit)
	 * @param int    $id   The record ID in the case of edit
	 *
	 * @return bool
	 */
	public function save_role($type='insert', $id=0)
	{
		if ($type == 'insert')
		{
			$this->form_validation->set_rules('role_name', 'lang:role_name', 'required|trim|strip_tags|unique[roles.role_name]|max_length[60]|xss_clean');
		}
		else
		{
			$this->form_validation->set_rules('role_name', 'lang:role_name', 'required|trim|strip_tags|unique[roles.role_name,roles.role_id]|max_length[60]|xss_clean');
		}

		$this->form_validation->set_rules('description', 'lang:bf_description', 'trim|strip_tags|max_length[255]|xss_clean');
		$this->form_validation->set_rules('login_destination', 'lang:role_login_destination', 'trim|strip_tags|max_length[255]|xss_clean');
		$this->form_validation->set_rules('default', 'lang:role_default_role', 'trim|strip_tags|is_numeric|max_length[1]|xss_clean');
		$this->form_validation->set_rules('can_delete', 'lang:role_can_delete_role', 'trim|strip_tags|is_numeric|max_length[1]|xss_clean');

		$_POST['role_id'] = $id;

		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
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
				$return = TRUE;
			}
			else
			{
				$return = FALSE;
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
				'description'=> sprintf(lang('roles_permission_manage_description'), ucwords($this->input->post('role_name'))),
				'status'=>'active'
			);

			if ( $this->permission_model->insert($add_perm) ) {
				$prefix = $this->db->dbprefix;
				// give current_role, or admin fallback, access to manage new role ACL
				$assign_role = $this->session->userdata('role_id') ? $this->session->userdata('role_id') : 1;
				$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(".$assign_role.",".$this->db->insert_id().")");
			}
			else
			{
				$this->error = lang('roles_acl_permission_failure');
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
			$this->error = lang('roles_permission_save_failure');
		}

		unset($permissions);
		return $return;

	}//end save_role()

	//--------------------------------------------------------------------

	/**
	 * Callback function to check that the role name is unique
	 *
	 * @access public
	 *
	 * @param string $str The role name
	 *
	 * @return bool
	 */
	public function unique_role($str)
	{
		if ($this->role_model->is_unique('role_name', $str))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('unique_role', lang('roles_role_in_use'));
			return FALSE;
		}

	}//end unique_role()


	// --------------------------------------------------------------------

	/**
	 * Creates a real-time modifiable summary table of all roles and permissions
	 *
	 * @return void
	 */
	public function permission_matrix()
	{
		// for the permission matrix
		$this->load->helper('inflector');

		Template::set('roles', $this->role_model->find_all());
		Template::set('matrix_permissions', $this->permission_model->select('permission_id, name')->order_by('name')->find_all());
		Template::set('matrix_roles', $this->role_model->select('role_id, role_name')->find_all());

		$role_permissions = $this->role_permission_model->find_all_role_permissions();
		foreach($role_permissions as $rp) {
			$current_permissions[] = $rp->role_id.','.$rp->permission_id;
		}
		Template::set('matrix_role_permissions', $current_permissions);

		Template::set("toolbar_title", lang('roles_permission_matrix'));

		Template::set_view('settings/permission_matrix');
		Template::render();

	}//end permission_matrix()


	// --------------------------------------------------------------------

	/**
	 * Updates the role_permissions table.
	 *
	 * Responses use "die()" instead of "echo()" in case the profiler is
	 * enabled. The profiler will add a lot of HTML to the end of the response
	 * which causes errors.
	 *
	 * @return mixed
	 */
	public function matrix_update()
	{
		$pieces = explode(',',$this->input->post('role_perm', TRUE));

		if (!$this->auth->has_permission('Permissions.'.$this->role_model->find( (int) $pieces[0])->role_name.'.Manage')) {
			die(lang('matrix_auth_failure'));
			return FALSE;
		}

		if ($this->input->post('action', TRUE) == 'TRUE')
		{
			if(is_numeric($this->role_permission_model->create_role_permissions($pieces[0],$pieces[1])))
			{
				die(lang('matrix_insert_success'));
			}
			else
			{
				die(lang('matrix_insert_failure') . $this->role_permission_model->error);
			}
		}
		else
		{
			if($this->role_permission_model->delete_role_permissions($pieces[0], $pieces[1]))
			{
				die(lang('matrix_delete_success'));
			}
			else
			{
				die(lang('matrix_delete_failure'). $this->role_permission_model->error);
			}
		}//end if

	}//end matrix_update()

	//--------------------------------------------------------------------

}//end Settings