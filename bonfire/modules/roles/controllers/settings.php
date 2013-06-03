<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
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
		Assets::add_js('codeigniter-csrf.js');
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

		Template::set('toolbar_title', lang("role_manage"));
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
		$this->auth->restrict('Bonfire.Roles.Add');

		if (isset($_POST['save']))
		{
			if ($this->save_role())
			{
				Template::set_message('Role successfully created.', 'success');
				redirect(SITE_AREA .'/settings/roles');
			}
			else
			{
				Template::set_message('There was a problem creating the role: '. $this->role_model->error, 'error');
			}
		}

        Template::set('contexts', list_contexts(true));

        Template::set('toolbar_title', 'Create New Role');
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
			Template::set_message('Invalid Role ID.', 'error');
			redirect(SITE_AREA .'/settings/roles');
		}

		if (isset($_POST['save']))
		{
			if ($this->save_role('update', $id))
			{
				Template::set_message('Role successfully saved.', 'success');
				redirect(SITE_AREA .'/settings/roles');
			}
			else
			{
				Template::set_message('There was a problem saving the role: '. $this->role_model->error);
			}
		}
		elseif (isset($_POST['delete']))
		{
			if ($this->role_model->delete($id))
			{
				Template::set_message('The Role was successfully deleted.', 'success');
				redirect(SITE_AREA .'/settings/roles');
			}
			else
			{
				Template::set_message('We could not delete the role: '. $this->role_model->error, 'error');
			}
		}

		$role = $this->role_model->find($id);
		Template::set('role', $role);
        Template::set('contexts', list_contexts(true));

        $title = lang('bf_action_edit') . ' '. lang('matrix_role');
        Template::set('toolbar_title', isset($role->role_name) ? $title .': '. $role->role_name : $title);
		Template::set_view('settings/role_form');
		Template::render();

	}//end edit()

	//--------------------------------------------------------------------
	// !HMVC METHODS
	//--------------------------------------------------------------------

	/**
	 * Builds the matrix for display in the role permissions form.
	 *
	 * @access private
	 *
	 * @return string The table(s) of settings, ready to be used in a form.
	 */
	public function matrix()
	{
		$id = (int)$this->uri->segment(5);
		$role = $this->role_model->find($id);

		// If the ID is empty, we are working with a new
		// role and won't have permissions to show.
		if ($id == 0)
		{
			return '<div class="alert alert-info">'. lang('role_new_permission_message') .'</div>';
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
			$auth_failed = lang('matrix_auth_fail');
			$domains = '';
		}//end if

		// Build the table(s) in the view to make things a little clearer,
		// and return it!
		return $this->load->view('settings/matrix', array('domains' => $domains, 'authentication_failed' => $auth_failed), TRUE);

	}//end matrix()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Saves the role record to the database
	 *
	 * @access private
	 *
	 * @param string $type The type of save operation (insert or edit)
	 * @param int    $id   The record ID in the case of edit
	 *
	 * @return bool
	 */
	private function save_role($type='insert', $id=0)
	{
		if ($type == 'insert')
		{
			$this->form_validation->set_rules('role_name', 'lang:role_name', 'required|trim|unique[roles.role_name]|max_length[60]');
		}
		else
		{
			$_POST['role_id'] = $id;
			$this->form_validation->set_rules('role_name', 'lang:role_name', 'required|trim|unique[roles.role_name,roles.role_id]|max_length[60]');
		}

		$this->form_validation->set_rules('description', 'lang:bf_description', 'trim|max_length[255]');
		$this->form_validation->set_rules('login_destination', 'lang:role_login_destination', 'trim|max_length[255]');
        $this->form_validation->set_rules('default_context', 'lang:role_default_context', 'trim');
        $this->form_validation->set_rules('default', 'lang:role_default_role', 'trim|is_numeric|max_length[1]');
		$this->form_validation->set_rules('can_delete', 'lang:role_can_delete_role', 'trim|is_numeric|max_length[1]');

		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
		}

		unset($_POST['save']);

		// Grab our permissions out of the POST vars, if it's there.
		// We'll need it later.
		$permissions = $this->input->post('role_permissions');
		unset($_POST['role_permissions']);

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
				'description'=>'To manage the access control permissions for the '.ucwords($this->input->post('role_name')).' role.',
				'status'=>'active'
			);

			if ( $this->permission_model->insert($add_perm) ) {
				// give current_role, or admin fallback, access to manage new role ACL
				$assign_role = $this->session->userdata('role_id') ? $this->session->userdata('role_id') : 1;
				$this->db->insert('role_permissions', array('role_id' => $assign_role, 'permission_id' => $this->db->insert_id()));
			}
			else
			{
				$this->error = 'There was an error creating the ACL permission.';
			}
		}
		else
		{
			// grab the current role model name
			$current_name = $this->role_model->find($id)->role_name;

			// update the permission name (did it this way for brevity on the update_where line)
			$new_perm_name = 'Permissions.'.ucwords($this->input->post('role_name')).'.Manage';
			$old_perm_name = 'Permissions.'.ucwords($current_name).'.Manage';
			$this->permission_model->update_where('name',$old_perm_name,array('name'=>$new_perm_name));
		}

		// Save the permissions.
		if ($permissions && !$this->role_permission_model->set_for_role($id, $permissions))
		{
			$this->error = 'There was an error saving the permissions.';
		}

		unset($permissions);
		return $return;

	}//end save_role()

	//--------------------------------------------------------------------

	/**
	 * Creates a real-time modifiable summary table of all roles and permissions
	 *
	 * @return void
	 */
	public function permission_matrix()
	{
		// for the permission matrix
		$this->load->helper('inflector');

		Template::set('matrix_permissions', $this->permission_model->select('permission_id, name')->order_by('name')->find_all());
		Template::set('matrix_roles', $this->role_model->select('role_id, role_name')->where('deleted', 0)->find_all());

		$role_permissions = $this->role_permission_model->find_all_role_permissions();

		foreach($role_permissions as $rp)
		{
			$current_permissions[] = $rp->role_id.','.$rp->permission_id;
		}

		Template::set('matrix_role_permissions', $current_permissions);
		Template::set("toolbar_title", lang('matrix_header'));

		Template::set_view('settings/permission_matrix');
		Template::render();
	}//end permission_matrix()


	// --------------------------------------------------------------------

	/**
	 * Update the role_permissions table.
	 */
	public function matrix_update()
	{
		// The profiler would add a lot of HTML to the end of the response.
		// This response is supposed to be single piece of text used by JS.
		$this->output->enable_profiler(FALSE);

		$pieces = explode(',',$this->input->post('role_perm'));

		if (!$this->auth->has_permission('Permissions.'.$this->role_model->find( (int) $pieces[0])->role_name.'.Manage'))
		{
			$this->output->set_output(lang("matrix_auth_fail"));

			return;
		}

		if ($this->input->post('action') == 'true')
		{
			if(is_numeric($this->role_permission_model->create_role_permissions($pieces[0],$pieces[1])))
			{
				$msg = lang("matrix_insert_success");
			}
			else
			{
				$msg = lang("matrix_insert_fail") . $this->role_permission_model->error;
			}
		}
		else
		{
			if($this->role_permission_model->delete_role_permissions($pieces[0], $pieces[1]))
			{
				$msg = lang("matrix_delete_success");
			}
			else
			{
				$msg = lang("matrix_delete_fail"). $this->role_permission_model->error;
			}
		}//end if

		$this->output->set_output($msg);
	}//end matrix_update()

	//--------------------------------------------------------------------

}//end Settings