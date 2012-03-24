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
				$this->auth->restrict('Bonfire.Users.View');

				$this->load->model('roles/role_model');

				$this->lang->load('users');

				Template::set_block('sub_nav', 'settings/sub_nav');
		}

		//--------------------------------------------------------------------

		public function _remap($method)
		{
				if (method_exists($this, $method))
				{
						$this->$method();
				}
		}

		//--------------------------------------------------------------------

		public function index()
		{

				$roles = $this->role_model->select('role_id, role_name')->where('deleted', 0)->find_all();
				Template::set('roles', $roles);

				$offset = $this->uri->segment(5);

				// Do we have any actions?
				$action = $this->input->post('submit');
				
				// if the action is empty check the delete button
				$action = !empty($action) ? $action : $this->input->post('delete');
				
				if (!empty($action))
				{
						$checked = $this->input->post('checked');

						switch(strtolower($action))
						{
								case 'ban':
										$this->ban($checked);
										break;
								case 'delete':
										$this->delete($checked);
										break;
						}
				}

				$where = array();

				// Filters
				$filter = $this->input->get('filter');
				switch($filter)
				{
						case 'banned':
							$where['users.banned'] = 1;
							break;
						case 'deleted':
							$where['users.deleted'] = 1;
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
				$this->user_model->select('users.id, users.role_id, username, display_name, email, last_login, banned, users.deleted, role_name');

				Template::set('users', $this->user_model->find_all());

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
		}

		//--------------------------------------------------------------------

		public function create()
		{
				$this->auth->restrict('Bonfire.Users.Add');

				$this->load->config('address');
				$this->load->helper('address');

				if ($this->input->post('submit'))
				{

						if ($id = $this->save_user())
						{
								$this->load->model('activities/Activity_model', 'activity_model');

								$user = $this->user_model->find($id);
								$log_name = $this->settings_lib->item('auth.use_own_names') ? $this->current_user->username : ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email);
								$this->activity_model->log_activity($this->current_user->id, lang('us_log_create').' '. $user->role_name . ': '.$log_name, 'users');

								Template::set_message('User successfully created.', 'success');
								Template::redirect(SITE_AREA .'/settings/users');
						}

				}

				Template::set('roles', $this->role_model->select('role_id, role_name, default')->where('deleted', 0)->find_all());

				Template::set('toolbar_title', lang('us_create_user'));
				Template::set_view('settings/user_form');
				Template::render();
		}

		//--------------------------------------------------------------------

		public function edit()
		{
				$this->auth->restrict('Bonfire.Users.Manage');

				$this->load->config('address');
				$this->load->helper('address');
				$this->load->helper('form');

				$user_id = $this->uri->segment(5);
				if (empty($user_id))
				{
						Template::set_message(lang('us_empty_id'), 'error');
						redirect(SITE_AREA .'/settings/users');
				}

				if ($this->input->post('submit'))
				{

						if ($this->save_user('update', $user_id))
						{
								$this->load->model('activities/Activity_model', 'activity_model');

								$user = $this->user_model->find($user_id);
								$log_name = $this->settings_lib->item('auth.use_own_names') ? $this->current_user->username : ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email);
								$this->activity_model->log_activity($this->current_user->id, lang('us_log_edit') .': '.$log_name, 'users');

								Template::set_message('User successfully updated.', 'success');
						}

				}

				$user = $this->user_model->find($user_id);
				if (isset($user) && has_permission('Permissions.'.$user->role_name.'.Manage'))
				{
						Template::set('user', $user);
						Template::set('roles', $this->role_model->select('role_id, role_name, default')->find_all());
						Template::set_view('settings/user_form');
				} else {
						Template::set_message(sprintf(lang('us_unauthorized'),$user->role_name), 'error');
						redirect(SITE_AREA .'/settings/users');
				}

				Template::set('toolbar_title', lang('us_edit_user'));

				Template::render();
		}

		//--------------------------------------------------------------------

		public function ban($users=false, $ban_message='')
		{

				if (!$users)
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
		}

		//--------------------------------------------------------------------

		public function delete($users)
		{

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
												$log_name = $this->settings_lib->item('auth.use_own_names') ? $this->current_user->username : ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email);
												$this->activity_model->log_activity($this->current_user->id, lang('us_log_delete') . ': '.$log_name, 'users');
												Template::set_message('The User was successfully deleted.', 'success');
										} else {
												Template::set_message(lang('us_action_not_deleted'). $this->user_model->error, 'error');
										}
								} else {
										if ($user->id == $this->current_user->id)
										{
												Template::set_message(lang('us_self_delete'), 'error');
										} else {
												Template::set_message(sprintf(lang('us_unauthorized'),$user->role_name), 'error');
										}
								}
						}
				} else {
						Template::set_message(lang('us_empty_id'), 'error');
				}

				redirect(SITE_AREA .'/settings/users');
		}

		//--------------------------------------------------------------------

		public function purge()
		{
				$user_id = $this->uri->segment(5);

				// Handle a single-user purge
				if (!empty($user_id) && is_numeric($user_id))
				{
						$this->user_model->delete($user_id, true);
				}
				// Handle purging all deleted users...
				else
				{
						// Find all deleted accounts
						$users = $this->user_model->where('users.deleted', 1)
														->find_all(true);

						if (is_array($users))
						{
								foreach ($users as $user)
								{
									$this->user_model->delete($user->id, true);
								}
						}
				}

				Template::set_message('Users Purged.', 'success');

				Template::redirect(SITE_AREA .'/settings/users');
		}

		//--------------------------------------------------------------------

		public function restore()
		{
				$id = $this->uri->segment(5);

				if ($this->user_model->update($id, array('users.deleted'=>0)))
				{
					Template::set_message('User successfully restored.', 'success');
				}
				else
				{
					Template::set_message('Unable to restore user: '. $this->user_model->error, 'error');
				}

				Template::redirect(SITE_AREA .'/settings/users');
		}

		//--------------------------------------------------------------------


		//--------------------------------------------------------------------
		// !HMVC METHODS
		//--------------------------------------------------------------------

		public function access_logs($limit=15)
		{
				$logs = $this->user_model->get_access_logs($limit);

				return $this->load->view('settings/access_logs', array('access_logs' => $logs), true);
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
					$this->form_validation->set_message('unique_email', lang('us_email_in_use'));
					return false;
				}
		}

		//--------------------------------------------------------------------

		private function save_user($type='insert', $id=0)
		{

				if ($type == 'insert')
				{
						$this->form_validation->set_rules('email', lang('bf_email'), 'required|trim|callback_unique_email|valid_email|max_length[120]|xss_clean');
						$this->form_validation->set_rules('password', lang('bf_password'), 'required|trim|strip_tags|max_length[40]|xss_clean');
						$this->form_validation->set_rules('pass_confirm', lang('bf_password_confirm'), 'required|trim|strip_tags|matches[password]|xss_clean');
				} else {
						$this->form_validation->set_rules('email', lang('us_label_email'), 'required|trim|valid_email|max_length[120]|xss_clean');
						$this->form_validation->set_rules('password', lang('bf_password'), 'trim|strip_tags|max_length[40]|matches[pass_confirm]|xss_clean');
						$this->form_validation->set_rules('pass_confirm', lang('bf_password_confirm'), 'trim|strip_tags|xss_clean');
				}

				$use_usernames = $this->settings_lib->item('auth.use_own_names');

				$required = false;
				if ($use_usernames)
				{
						$required = 'required|';
				}

				if ($use_usernames)
				{
						$this->form_validation->set_rules('username', lang('bf_username'), $required . 'trim|strip_tags|max_length[30]|callback_unique_username|xsx_clean');
				}

				$this->form_validation->set_rules('display_name', lang('bf_display_name'), 'trim|strip_tags|max_length[255]|xss_clean');

				if ($this->form_validation->run() === false)
				{
						return false;
				}

				// Compile our core user elements to save.
				$data = array(
					'email'		=> $this->input->post('email'),
					'username'	=> $this->input->post('username')
				);

				if ($this->input->post('password'))	$data['password'] = $this->input->post('password');
				if ($this->input->post('pass_confirm'))	$data['pass_confirm'] = $this->input->post('pass_confirm');
				if ($this->input->post('role_id')) $data['role_id'] = $this->input->post('role_id');
				if ($this->input->post('restore')) $data['deleted'] = 0;
				if ($this->input->post('unban')) $data['banned'] = 0;
				if ($this->input->post('display_name')) $data['display_name'] = $this->input->post('display_name');

				if ($type == 'insert')
				{
					$return = $this->user_model->insert($data);
				}
				else	// Update
				{
					$return = $this->user_model->update($id, $data);
				}

				// Any modules needing to save data?
				Events::trigger('save_user', $this->input->post());

				return $return;
		}

		//--------------------------------------------------------------------


}

// End of Admin User Controller
/* End of file settings.php */
/* Location: ./application/core_modules/controllers/settings.php */
