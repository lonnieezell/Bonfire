<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Roles Settings Context
 *
 * Allows the management of the Bonfire roles.
 *
 * @package    Bonfire\Modules\Roles\Controllers\Settings
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/bonfire/roles_and_permissions
 *
 */
class Settings extends Admin_Controller
{
	/**
	 * Setup the required permissions and load required classes
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

		// For the render_search_box()
		$this->load->helper('ui/ui');

		Template::set_block('sub_nav', 'settings/_sub_nav');
	}

	/**
	 * Display a list of all roles
	 *
	 * @return void
	 */
	public function index()
	{
		// Get User Counts
		Template::set('deleted_users', $this->user_model->count_all(true));
		Template::set('role_counts', $this->user_model->count_by_roles());
		Template::set('total_users', $this->user_model->count_all());

		Template::set('roles', $this->role_model->where('deleted', 0)->find_all());

		Template::set('toolbar_title', lang("role_manage"));
		Template::render();
	}

	/**
	 * Create a new role in the database
	 *
	 * @return void
	 */
	public function create()
	{
		$this->auth->restrict('Bonfire.Roles.Add');

		if (isset($_POST['save'])) {
			if ($this->save_role()) {
				Template::set_message(lang('role_create_success'), 'success');
				redirect(SITE_AREA . '/settings/roles');
			} else {
				Template::set_message(lang('role_create_error') . $this->role_model->error, 'error');
			}
		}

        Template::set('contexts', list_contexts(true));
        Template::set('toolbar_title', 'Create New Role');
		Template::set_view('settings/role_form');
		Template::render();
	}

	/**
	 * Edit a role record
	 *
	 * @return void
	 */
	public function edit()
	{
		$this->auth->restrict('Bonfire.Roles.Manage');

		$id = (int)$this->uri->segment(5);
		if (empty($id)) {
			Template::set_message(lang('role_invalid_id'), 'error');
			redirect(SITE_AREA . '/settings/roles');
		}

		if (isset($_POST['save'])) {
			if ($this->save_role('update', $id)) {
				Template::set_message(lang('role_edit_success'), 'success');
				redirect(SITE_AREA . '/settings/roles');
			} else {
				Template::set_message(lang('role_edit_error') . $this->role_model->error, 'error');
			}
		} elseif (isset($_POST['delete'])) {
			if ($this->role_model->delete($id)) {
				Template::set_message(lang('role_delete_success'), 'success');
				redirect(SITE_AREA . '/settings/roles');
			} else {
				Template::set_message(lang('role_delete_error') . $this->role_model->error, 'error');
			}
		}

		$role = $this->role_model->find($id);
        $title = lang('bf_action_edit') . ' ' . lang('matrix_role');

		Template::set('role', $role);
        Template::set('contexts', list_contexts(true));
        Template::set('toolbar_title', isset($role->role_name) ? "{$title}: {$role->role_name}" : $title);
		Template::set_view('settings/role_form');
		Template::render();
	}

	//--------------------------------------------------------------------
	// !HMVC METHODS
	//--------------------------------------------------------------------

	/**
	 * Build the matrix for display in the role permissions form.
	 *
	 * @return string The table(s) of settings, ready to be used in a form.
	 */
	public function matrix()
	{
		$id = (int)$this->uri->segment(5);
		$role = $this->role_model->find($id);

		// If the ID is empty, we are working with a new role and won't have
        // permissions to show.
		if ($id == 0) {
			return '<div class="alert alert-info">' . lang('role_new_permission_message') . '</div>';
		}

        $auth_failed = '';
        $domains = '';

		// Verify role has permission to modify this role's access control
		if ($this->auth->has_permission('Permissions.' . ucwords($role->role_name) . '.Manage')) {
			$permissions_full = $role->permissions;
			$role_permissions = $role->role_permissions;

			$template = array();
			foreach ($permissions_full as $key => $perm) {
				$template[$perm->name]['perm_id'] = $perm->permission_id;
				$template[$perm->name]['value'] = 0;
				if (isset($role_permissions[$perm->permission_id])) {
					$template[$perm->name]['value'] = 1;
				}
			}

			// Extract our pieces from each permission
			$domains = array();
			foreach ($template as $key => $value) {
				list($domain, $name, $action) = explode('.', $key);

				// Add it to the domain if it's not already there.
				if ( ! empty($domain) && ! array_key_exists($domain, $domains)) {
					$domains[$domain] = array();
				}

				// Add the preference to the domain array
				if (isset($domains[$domain][$name])) {
                    $domains[$domain][$name][$action] = $value;
                } else {
					$domains[$domain][$name] = array(
						$action => $value
					);
				}

				// Store the actions separately for building the table header
				if ( ! isset($domains[$domain]['actions'])) {
					$domains[$domain]['actions'] = array();
				}

				if ( ! in_array($action, $domains[$domain]['actions'])) {
					$domains[$domain]['actions'][] = $action;
				}
			}
		}
        // If the role does not have the Manage permission
		else {
			$auth_failed = lang('matrix_auth_fail');
		}

		// Build the table(s) in the view to make things a little clearer,
		// and return it!
		return $this->load->view('settings/matrix', array('domains' => $domains, 'authentication_failed' => $auth_failed), true);
	}

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Save the role record to the database
	 *
	 * @param string $type The type of save operation (insert or edit)
	 * @param int    $id   The record ID in the case of edit
	 *
	 * @return bool
	 */
	private function save_role($type = 'insert', $id = 0)
	{
		if ($type == 'insert') {
			$this->form_validation->set_rules('role_name', 'lang:role_name', 'required|trim|unique[roles.role_name]|max_length[60]');
		} else {
			$_POST['role_id'] = $id;
			$this->form_validation->set_rules('role_name', 'lang:role_name', 'required|trim|unique[roles.role_name,roles.role_id]|max_length[60]');
		}

		$this->form_validation->set_rules('description', 'lang:bf_description', 'trim|max_length[255]');
		$this->form_validation->set_rules('login_destination', 'lang:role_login_destination', 'trim|max_length[255]');
        $this->form_validation->set_rules('default_context', 'lang:role_default_context', 'trim');
        $this->form_validation->set_rules('default', 'lang:role_default_role', 'trim|is_numeric|max_length[1]');
		$this->form_validation->set_rules('can_delete', 'lang:role_can_delete_role', 'trim|is_numeric|max_length[1]');

		if ($this->form_validation->run() === false) {
			return false;
		}

		unset($_POST['save']);

		// Grab the permissions from the POST vars, if available.
		$permissions = $this->input->post('role_permissions');
		unset($_POST['role_permissions']);

		if ($type == 'insert') {
			$id = $this->role_model->insert($_POST);
            $return = is_numeric($id);
		} elseif ($type == 'update') {
			$return = $this->role_model->update($id, $_POST);
		}

        if ( ! $return) {
            return $return;
        }

		// Add a new management permission for the role.
		if ($type ==  'insert')	{
            $roleName = ucwords($this->input->post('role_name'));
			$add_perm = array(
				'name'        => "Permissions.{$roleName}.Manage",
				'description' => "To manage the access control permissions for the {$roleName} role.",
				'status'      => 'active'
			);

			if ($this->permission_model->insert($add_perm)) {
				// Give current_role, or admin fallback, new Manage permission
                $roleId = false;
                if (class_exists('auth')) {
                    $roleId = $this->auth->role_id();
                }
				$assign_role = $roleId ?: 1;

				$this->db->insert('role_permissions', array(
                    'role_id'       => $assign_role,
                    'permission_id' => $this->db->insert_id(),
                ));
			} else {
				$this->error = 'There was an error creating the ACL permission.';
			}
		}
        // Update
        else {
			// Grab the current role model name
			$current_name = $this->role_model->find($id)->role_name;

			// update the permission name (did it this way for brevity on the update_where line)
			$new_perm_name = 'Permissions.' . ucwords($this->input->post('role_name')) . '.Manage';
			$old_perm_name = 'Permissions.' . ucwords($current_name) . '.Manage';
			$this->permission_model->update_where('name', $old_perm_name, array('name' => $new_perm_name));
		}

		// Save the permissions.
		if ($permissions
            && ! $this->role_permission_model->set_for_role($id, $permissions)
           ) {
			$this->error = 'There was an error saving the permissions.';
		}

		return $return;
	}

	/**
	 * Create a real-time modifiable summary table of all roles and permissions
	 *
	 * @return void
	 */
	public function permission_matrix()
	{
		// For the permission matrix
		$this->load->helper('inflector');

		Template::set(
            'matrix_permissions',
            $this->permission_model->select('permission_id, name')
                                   ->order_by('name')
                                   ->find_all()
        );
		Template::set(
            'matrix_roles',
            $this->role_model->select('role_id, role_name')
                             ->where('deleted', 0)
                             ->find_all()
        );

		$role_permissions = $this->role_permission_model->find_all_role_permissions();
		foreach ($role_permissions as $rp) {
			$current_permissions[] = "{$rp->role_id},{$rp->permission_id}";
		}

		Template::set('matrix_role_permissions', $current_permissions);
		Template::set("toolbar_title", lang('matrix_header'));

		Template::set_view('settings/permission_matrix');
		Template::render();
	}

	/**
	 * Update the role_permissions table.
	 *
	 * @return void
	 */
	public function matrix_update()
	{
		// Disable the profile for AJAX response
		$this->output->enable_profiler(false);

		$pieces = explode(',', $this->input->post('role_perm'));

		if ( ! $this->auth->has_permission('Permissions.' . $this->role_model->find((int)$pieces[0])->role_name . '.Manage')) {
			$this->output->set_output(lang("matrix_auth_fail"));

			return;
		}

        // A box was checked
		if ($this->input->post('action') == 'true') {
			if (is_numeric($this->role_permission_model->create_role_permissions($pieces[0], $pieces[1]))) {
				$msg = lang("matrix_insert_success");
			} else {
				$msg = lang("matrix_insert_fail") . $this->role_permission_model->error;
			}
		}
        // A box was unchecked
        else {
			if ($this->role_permission_model->delete_role_permissions($pieces[0], $pieces[1])) {
				$msg = lang("matrix_delete_success");
			} else {
				$msg = lang("matrix_delete_fail"). $this->role_permission_model->error;
			}
		}

		$this->output->set_output($msg);
	}
}
/* end /bonfire/modules/roles/controllers/settings.php */