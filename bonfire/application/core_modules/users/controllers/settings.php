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
 * Users Controller
 *
 * Manages the user functionality on the admin pages.
 *
 * @package    Bonfire
 * @subpackage Modules_Users
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com
 *
 */
class Settings extends Admin_Controller
{

	//--------------------------------------------------------------------

	/**
	 * Setup the required permissions
	 *
	 * @return void
	 */
	public function __construct()
    {
		parent::__construct();

		$this->auth->restrict('Bonfire.Users.View');

		$this->load->model('roles/role_model');

		$this->lang->load('users');

		Template::set_block('sub_nav', 'settings/_sub_nav');

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Remap methods
	 *
	 * @access public
	 *
	 * @param string $method Name of the method being requested
	 */
	public function _remap($method)
	{
		if (method_exists($this, $method))
		{
			$this->$method();
		}

	}//end _remap()

	//--------------------------------------------------------------------

	/*
	 * Display the user list and manage the user deletions/banning/purge
	 *
	 * @access public
	 *
	 * @return  void
	 */
	public function index()
	{
		$this->auth->restrict('Bonfire.Users.Manage');

		$roles = $this->role_model->select('role_id, role_name')->where('deleted', 0)->find_all();
		Template::set('roles', $roles);

		$offset = $this->uri->segment(5);

		// Do we have any actions?
		if (is_array($this->input->post()))
		{
			if (array_key_exists('activate', $this->input->post())) $action = 'activate';
			if (array_key_exists('deactivate', $this->input->post())) $action = 'deactivate';
			if (array_key_exists('ban', $this->input->post())) $action = 'ban';
			if (array_key_exists('delete', $this->input->post())) $action = 'delete';
			if (array_key_exists('purge', $this->input->post())) $action = 'purge';
		}

		if (!empty($action))
		{
			$checked = $this->input->post('checked');

			switch(strtolower($action))
			{
				case 'activate':
					$this->activate($checked);
					break;
				case 'deactivate':
					$this->deactivate($checked);
					break;
				case 'ban':
					$this->ban($checked);
					break;
				case 'delete':
					$this->delete($checked);
					break;
				case 'purge':
					$this->purge($checked);
					break;
			}
		}

		$where = array();
		$show_deleted = FALSE;

		// Filters
		$filter = $this->input->get('filter');
		switch($filter)
		{
			case 'inactive':
				$where['users.active'] = 0;
				break;
			case 'banned':
				$where['users.banned'] = 1;
				break;
			case 'deleted':
				$where['users.deleted'] = 1;
				$show_deleted = TRUE;
				break;
			case 'role':
				$role_id = (int)$this->input->get('role_id');
				$where['users.role_id'] = $role_id;

				foreach ($roles as $role)
				{
					if ($role->role_id == $role_id)
					{
						Template::set('filter_role', $role->role_name);
						break;
					}
				}
				break;

			default:
				$where['users.deleted'] = 0;
				$this->user_model->where('users.deleted', 0);
				break;
		}

		// First Letter
		$first_letter = $this->input->get('firstletter');
		if (!empty($first_letter))
		{
			$where['SUBSTRING( LOWER(username), 1, 1)='] = $first_letter;
		}

		$this->load->helper('ui/ui');

		$this->user_model->limit($this->limit, $offset)->where($where);
		$this->user_model->select('users.id, users.role_id, username, display_name, email, last_login, banned, active, users.deleted, role_name');

		Template::set('users', $this->user_model->find_all($show_deleted));

		// Pagination
		$this->load->library('pagination');

		$this->user_model->where($where);
		$total_users = $this->user_model->count_all();

		$this->pager['base_url'] = site_url(SITE_AREA .'/settings/users/index');
		$this->pager['total_rows'] = $total_users;
		$this->pager['per_page'] = $this->limit;
		$this->pager['uri_segment']	= 5;

		$this->pagination->initialize($this->pager);

		Template::set('current_url', current_url());
		Template::set('filter', $filter);

		Template::set('toolbar_title', lang('us_user_management'));
		Template::render();

	}//end index()

	//--------------------------------------------------------------------

	/**
	 * Manage creating a new user
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function create()
	{
		$this->auth->restrict('Bonfire.Users.Add');

		$this->load->config('address');
		$this->load->helper('address');
		$this->load->helper('date');


		$this->load->config('user_meta');
		$meta_fields = config_item('user_meta_fields');
		Template::set('meta_fields', $meta_fields);

		if ($this->input->post('submit'))
		{
			if ($id = $this->save_user('insert', NULL, $meta_fields))
			{

				$meta_data = array();
				foreach ($meta_fields as $field)
				{
					$meta_data[$field['name']] = $this->input->post($field['name']);
				}

				// now add the meta is there is meta data
				$this->user_model->save_meta_for($id, $meta_data);

				$this->load->model('activities/Activity_model', 'activity_model');

				$user = $this->user_model->find($id);
				$log_name = (isset($user->display_name) && !empty($user->display_name)) ? $user->display_name : ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email);
				$this->activity_model->log_activity($this->current_user->id, lang('us_log_create').' '. $user->role_name . ': '.$log_name, 'users');

				Template::set_message(lang('us_user_created_success'), 'success');
				Template::redirect(SITE_AREA .'/settings/users');
			}
		}

        $settings = $this->settings_lib->find_all();
        if ($settings['auth.password_show_labels'] == 1) {
            Assets::add_module_js('users','password_strength.js');
            Assets::add_module_js('users','jquery.strength.js');
            Assets::add_js($this->load->view('users_js', array('settings'=>$settings), true), 'inline');
        }
        Template::set('roles', $this->role_model->select('role_id, role_name, default')->where('deleted', 0)->find_all());
		Template::set('languages', unserialize($this->settings_lib->item('site.languages')));

		Template::set('toolbar_title', lang('us_create_user'));
		Template::set_view('settings/user_form');
		Template::render();

	}//end create()

	//--------------------------------------------------------------------

	/**
	 * Edit a user
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function edit()
	{
		$this->load->config('address');
		$this->load->helper('address');
		$this->load->helper('date');

		// if there is no id passed in edit the current user
		// this is so we don't have to pass the user id in the url for editing the current users profile
		$user_id = $this->uri->segment(5) != '' ? $this->uri->segment(5) : $this->current_user->id;

		if (empty($user_id))
		{
			Template::set_message(lang('us_empty_id'), 'error');
			redirect(SITE_AREA .'/settings/users');
		}

		if ($user_id != $this->current_user->id)
		{
			$this->auth->restrict('Bonfire.Users.Manage');
		}


		$this->load->config('user_meta');
		$meta_fields = config_item('user_meta_fields');
		Template::set('meta_fields', $meta_fields);

		$user = $this->user_model->find_user_and_meta($user_id);

		if ($this->input->post('submit'))
		{
			if ($this->save_user('update', $user_id, $meta_fields, $user->role_name))
			{

				$meta_data = array();
				foreach ($meta_fields as $field)
				{
					$meta_data[$field['name']] = $this->input->post($field['name']);
				}

				// now add the meta is there is meta data
				$this->user_model->save_meta_for($user_id, $meta_data);

				$this->load->model('activities/Activity_model', 'activity_model');

				$user = $this->user_model->find_user_and_meta($user_id);;
				$log_name = (isset($user->display_name) && !empty($user->display_name)) ? $user->display_name : ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email);
				$this->activity_model->log_activity($this->current_user->id, lang('us_log_edit') .': '.$log_name, 'users');

				Template::set_message(lang('us_user_update_success'), 'success');

				// redirect back to the edit page to make sure that a users password change
				// forces a login check
				Template::redirect($this->uri->uri_string());
			}
		}

		if (isset($user))
		{
			Template::set('roles', $this->role_model->select('role_id, role_name, default')->where('deleted', 0)->find_all());
			Template::set('user', $user);
			Template::set('languages', unserialize($this->settings_lib->item('site.languages')));
		}
		else
		{
			Template::set_message(sprintf(lang('us_unauthorized'),$user->role_name), 'error');
			redirect(SITE_AREA .'/settings/users');
		}

        $settings = $this->settings_lib->find_all();
        if ($settings['auth.password_show_labels'] == 1) {
            Assets::add_module_js('users','password_strength.js');
            Assets::add_module_js('users','jquery.strength.js');
            Assets::add_js($this->load->view('users_js', array('settings'=>$settings), true), 'inline');
        }

        Template::set('toolbar_title', lang('us_edit_user'));

		Template::set_view('settings/user_form');

		Template::render();

	}//end edit()

	//--------------------------------------------------------------------

	/**
	 * Ban a user or group of users
	 *
	 * @access public
	 *
	 * @param array  $users       Array of users to ban
	 * @param string $ban_message Set a message for the user as the reason for banning them
	 *
	 * @return void
	 */
	public function ban($users=array(), $ban_message='')
	{

		if (empty($users))
		{
			return;
		}

		$this->auth->restrict('Bonfire.Users.Manage');

		foreach ($users as $user_id)
		{
			$data = array(
				'banned'		=> 1,
				'ban_message'	=> $ban_message
				);

			$this->user_model->update($user_id, $data);
		}

	}//end ban()

	//--------------------------------------------------------------------

	/**
	 * Delete a user or group of users
	 *
	 * @access public
	 *
	 * @param array $users Array of users to delete
	 *
	 * @return void
	 */
	public function delete($users=array())
	{
		// if the users array is empty then get the user from the segment
		if (empty($users))
		{
			$user_id = $this->uri->segment(5);

			if(!empty($user_id))
			{
				$users = array($user_id);
			}
		}

		if (!empty($users))
		{
			$this->auth->restrict('Bonfire.Users.Manage');

			foreach ($users as $id)
			{
				$user = $this->user_model->find($id);

				if (isset($user) && has_permission('Permissions.'.$user->role_name.'.Manage') && $user->id != $this->current_user->id)
				{
					if ($this->user_model->delete($id))
					{
						$this->load->model('activities/Activity_model', 'activity_model');

						$user = $this->user_model->find($id);
						$log_name = (isset($user->display_name) && !empty($user->display_name)) ? $user->display_name : ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email);
						$this->activity_model->log_activity($this->current_user->id, lang('us_log_delete') . ': '.$log_name, 'users');
						Template::set_message(lang('us_action_deleted'), 'success');
					}
					else
					{
						Template::set_message(lang('us_action_not_deleted'). $this->user_model->error, 'error');
					}
				}
				else
				{
					if ($user->id == $this->current_user->id)
					{
							Template::set_message(lang('us_self_delete'), 'error');
					}
					else
					{
							Template::set_message(sprintf(lang('us_unauthorized'),$user->role_name), 'error');
					}
				}//end if
			}//end foreach
		}
		else
		{
			Template::set_message(lang('us_empty_id'), 'error');
		}//end if

		redirect(SITE_AREA .'/settings/users');

	}//end delete()

	//--------------------------------------------------------------------

	/**
	 * Purge the selected users which are already marked as deleted
	 *
	 * @access public
	 *
	 * @param array $users
	 *
	 * @return void
	 */
	public function purge($users=array())
	{
		// if the users array is empty then get the user from the segment
		if (empty($users))
		{
			$user_id = $this->uri->segment(5);

			if(!empty($user_id))
			{
				$users = array($user_id);
			}
		}

		if (!empty($users) && is_array($users))
		{
			$this->auth->restrict('Bonfire.Users.Manage');

			foreach ($users as $id)
			{
				$this->user_model->delete($id, TRUE);
			}
			Template::set_message(lang('us_action_purged'), 'success');
		}
		else {
			Template::set_message(lang('us_empty_id'), 'error');
		}

		Template::redirect(SITE_AREA .'/settings/users');

	}//end purge()

	//--------------------------------------------------------------------

	/**
	 * Restore the deleted user
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function restore()
	{
		$id = $this->uri->segment(5);

		if ($this->user_model->update($id, array('users.deleted'=>0)))
		{
			Template::set_message(lang('us_user_restored_success'), 'success');
		}
		else
		{
			Template::set_message(lang('us_user_restored_error'). $this->user_model->error, 'error');
		}

		Template::redirect(SITE_AREA .'/settings/users');

	}//end restore()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !HMVC METHODS
	//--------------------------------------------------------------------

	/**
	 * Show the access logs
	 *
	 * @access public
	 *
	 * @param int $limit Limit the number of logs to show at a time
	 *
	 * @return string Show the access logs
	 */
	public function access_logs($limit=15)
	{
		$logs = $this->user_model->get_access_logs($limit);

		return $this->load->view('settings/access_logs', array('access_logs' => $logs), TRUE);

	}//end access_logs()

	//--------------------------------------------------------------------



	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Callback method to check that the email is unique
	 *
	 * @access public
	 *
	 * @param string $str The email to check
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
			$this->form_validation->set_message('unique_email', lang('us_email_in_use'));
			return FALSE;
		}

	}//end unique_email()

	//--------------------------------------------------------------------

	/**
	 * Save the user
	 *
	 * @access private
	 *
	 * @param string $type          The type of operation (insert or edit)
	 * @param int    $id            The id of the user in the case of an edit operation
	 * @param array  $meta_fields   Array of meta fields fur the user
	 * @param string $cur_role_name The current role for the user being edited
	 *
	 * @return bool
	 */
	private function save_user($type='insert', $id=0, $meta_fields=array(), $cur_role_name = '')
	{

		if ($type == 'insert')
		{
			$this->form_validation->set_rules('email', lang('bf_email'), 'required|trim|unique[users.email]|valid_email|max_length[120]|xss_clean');
			$this->form_validation->set_rules('password', lang('bf_password'), 'required|trim|strip_tags|min_length[8]|max_length[120]|valid_password|xss_clean');
			$this->form_validation->set_rules('pass_confirm', lang('bf_password_confirm'), 'required|trim|strip_tags|matches[password]|xss_clean');
		}
		else
		{
			$_POST['id'] = $id;
			$this->form_validation->set_rules('email', lang('bf_email'), 'required|trim|unique[users.email,users.id]|valid_email|max_length[120]|xss_clean');
			$this->form_validation->set_rules('password', lang('bf_password'), 'trim|strip_tags|min_length[8]|max_length[120]|valid_password|matches[pass_confirm]|xss_clean');
			$this->form_validation->set_rules('pass_confirm', lang('bf_password_confirm'), 'trim|strip_tags|xss_clean');
		}

		$use_usernames = $this->settings_lib->item('auth.use_usernames');

		if ($use_usernames)
		{
			$extra_unique_rule = $type == 'update' ? ',users.id' : '';

			$this->form_validation->set_rules('username', lang('bf_username'), 'required|trim|strip_tags|max_length[30]|unique[users.username'.$extra_unique_rule.']|xss_clean');
		}

		$this->form_validation->set_rules('display_name', lang('bf_display_name'), 'trim|strip_tags|max_length[255]|xss_clean');

		$this->form_validation->set_rules('language', lang('bf_language'), 'required|trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('timezones', lang('bf_timezone'), 'required|trim|strip_tags|max_length[4]|xss_clean');

		if (has_permission('Bonfire.Roles.Manage') && has_permission('Permissions.'.$cur_role_name.'.Manage'))
		{
			$this->form_validation->set_rules('role_id', lang('us_role'), 'required|trim|strip_tags|max_length[2]|is_numeric|xss_clean');
		}

		$meta_data = array();

		foreach ($meta_fields as $field)
		{
			$this->form_validation->set_rules($field['name'], $field['label'], $field['rules']);

			$meta_data[$field['name']] = $this->input->post($field['name']);
		}

		if ($this->form_validation->run($this) === FALSE)
		{
			return FALSE;
		}

		// Compile our core user elements to save.
		$data = array(
			'email'		=> $this->input->post('email'),
			'username'	=> $this->input->post('username'),
			'language'	=> $this->input->post('language'),
			'timezone'	=> $this->input->post('timezones'),
		);

		if ($this->input->post('password'))
		{
			$data['password'] = $this->input->post('password');
		}

		if ($this->input->post('pass_confirm'))
		{
			$data['pass_confirm'] = $this->input->post('pass_confirm');
		}

		if ($this->input->post('role_id'))
		{
			$data['role_id'] = $this->input->post('role_id');
		}

		if ($this->input->post('restore'))
		{
			$data['deleted'] = 0;
		}

		if ($this->input->post('unban'))
		{
			$data['banned'] = 0;
		}

		if ($this->input->post('display_name'))
		{
			$data['display_name'] = $this->input->post('display_name');
		}

		// Activation
		if ($this->input->post('activate'))
		{
			$data['active'] = 1;
		}
		else if ($this->input->post('deactivate'))
		{
			$data['active'] = 0;
		}

		if ($type == 'insert')
		{
			$activation_method = $this->settings_lib->item('auth.user_activation_method');

			// No activation method
			if ($activation_method == 0)
			{
				// Activate the user automatically
				$data['active'] = 1;
			}

			$return = $this->user_model->insert($data);
		}
		else	// Update
		{
			$return = $this->user_model->update($id, $data);
		}

		// Any modules needing to save data?
		Events::trigger('save_user', $this->input->post());

		return $return;

	}//end save_user()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// ACTIVATION METHODS
	//--------------------------------------------------------------------
	/**
	 * Activates selected users accounts.
	 *
	 * @access public
	 *
	 * @param array $users Array of User ID ints
	 *
	 * @return void
	 */
	public function activate($users=false)
	{
		if (!$users)
		{
			return;
		}

		$this->auth->restrict('Bonfire.Users.Manage');
		foreach ($users as $user_id)
		{
			$this->user_status($user_id,1,0);
		}

	}//end activate()

	//--------------------------------------------------------------------

	/**
	 * Deactivates selected users accounts.
	 *
	 * @access public
	 *
	 * @param array $users Array of User ID ints
	 *
	 * @return void
	 */
	public function deactivate($users=false)
	{
		if (!$users)
		{
			return;
		}

		$this->auth->restrict('Bonfire.Users.Manage');

		foreach ($users as $user_id)
		{
			$this->user_status($user_id,0,0);
		}

	}//end deactivate()

	//--------------------------------------------------------------------

	/**
	 * Activates or deavtivates a user from the users dashboard.
	 * Redirects to /settings/users on completion.
	 *
	 * @access private
	 *
	 * @param int  $user_id       User ID int
	 * @param int $status        1 = Activate, -1 = Deactivate
	 * @param int $supress_email 1 = Supress, All others = send email
	 *
	 * @return void
	 */
	private function user_status($user_id = false, $status = 1, $supress_email = 0)
	{
		$supress_email = (isset($supress_email) && $supress_email == 1 ? true : false);

		if ($user_id !== false && $user_id != -1)
		{
			$result = false;
			$type = '';
			if ($status == 1)
			{
				$result = $this->user_model->admin_activation($user_id);
				$type = lang('bf_action_activate');
			}
			else
			{
				$result = $this->user_model->admin_deactivation($user_id);
				$type = lang('bf_action_deactivate');
			}

			$user = $this->user_model->find($user_id);
			$log_name = $this->settings_lib->item('auth.use_own_names') ? $this->current_user->username : ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email);
			if (!isset($this->activity_model))
			{
				$this->load->model('activities/activity_model');
			}

			$this->activity_model->log_activity($this->current_user->id, lang('us_log_status_change') . ': '.$log_name . ' : '.$type."ed", 'users');
			if ($result)
			{
				$message = lang('us_active_status_changed');
				if (!$supress_email)
				{
					// Now send the email
					$this->load->library('emailer/emailer');

					$settings = $this->settings_lib->find_by('name','site.title');

					$data = array
					(
						'to'		=> $this->user_model->find($user_id)->email,
						'subject'	=> lang('us_account_active'),
						'message'	=> $this->load->view('_emails/activated', array('link'=>site_url(),'title'=>$settings->value), true)
					);

					if ($this->emailer->send($data))
					{
						$message = lang('us_active_email_sent');
					}
					else
					{
						$message=lang('us_err_no_email'). $this->emailer->errors;
					}
				}
				Template::set_message($message, 'success');
			}
			else
			{
				Template::set_message(lang('us_err_status_error').$this->user_model->error,'error');
			}//end if
		}
		else
		{
			Template::set_message(lang('us_err_no_id'),'error');
		}//end if

		Template::redirect(SITE_AREA.'/settings/users');

	}//end user_status()

	//--------------------------------------------------------------------

}//end Settings

// End of Admin User Controller
/* End of file settings.php */
/* Location: ./application/core_modules/controllers/settings.php */
