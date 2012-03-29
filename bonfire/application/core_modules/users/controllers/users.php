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

/*
	Class: Users

	Provides front-end functions for users, like login and logout.
*/
class Users extends Front_Controller {

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;

		if (!class_exists('User_model'))
		{
			$this->load->model('users/User_model', 'user_model');
		}

		$this->load->database();

		$this->load->library('users/auth');

		$this->lang->load('users');
	}

	//--------------------------------------------------------------------

	/*
		Method: login()

		Presents the login function and allows the user to actually login.
	*/
	public function login()
	{

		// if the user is not logged in continue to show the login page
		if ($this->auth->is_logged_in() === false)
		{

			if ($this->input->post('submit'))
			{

				$remember = $this->input->post('remember_me') == '1' ? true : false;

				// Try to login
				if ($this->auth->login($this->input->post('login'), $this->input->post('password'), $remember) === true)
				{
					$this->load->model('activities/Activity_model', 'activity_model');

					$this->activity_model->log_activity($this->auth->user_id(), lang('us_log_logged').': ' . $this->input->ip_address(), 'users');

					/*
						In many cases, we will have set a destination for a
						particular user-role to redirect to. This is helpful for
						cases where we are presenting different information to different
						roles that might cause the base destination to be not available.
					*/
					if ($this->settings_lib->item('auth.do_login_redirect') && !empty ($this->auth->login_destination))
					{
						Template::redirect($this->auth->login_destination);
					}
					else
					{
						if (!empty($this->requested_page))
						{
							Template::redirect($this->requested_page);
						}
						else
						{
							Template::redirect('/');
						}
					}
				}

			}

			Template::set_view('users/users/login');
			Template::set('page_title', 'Login');
			Template::render('login');
		}
		else {

			Template::redirect('/');
		}
	}

	//--------------------------------------------------------------------

	/*
		Method: logout()

		Calls the auth->logout method to destroy the session and cleanup,
		then redirects to the home page.
	*/
	public function logout()
	{
		$this->auth->logout();
		redirect('/');
	}

	//--------------------------------------------------------------------

	/*
		Method: forgot_password

		Allows a user to start the process of resetting their password.
		An email is allowed with a special temporary link that is only valid
		for 24 hours. This link takes them to reset_password().
	*/
	public function forgot_password()
	{

		// if the user is not logged in continue to show the login page
		if ($this->auth->is_logged_in() === false)
		{
			if (isset($_POST['submit']))
			{
				$this->form_validation->set_rules('email', 'lang:bf_email', 'required|trim|strip_tags|valid_email|xss_clean');

				if ($this->form_validation->run() === FALSE)
				{
					Template::set_message('Cannot find that email in our records.', 'error');
				} else {
					// We validated. Does the user actually exist?
					$user = $this->user_model->find_by('email', $_POST['email']);

					if (count($user) == 1)
					{
						// User exists, so create a temp password.
						$this->load->helpers(array('string', 'security'));

						$pass_code = random_string('alnum', 40);

						$hash = do_hash($pass_code . $user->salt . $_POST['email']);

						// Save the hash to the db so we can confirm it later.
						$this->user_model->update_where('email', $_POST['email'], array('reset_hash' => $hash, 'reset_by' => strtotime("+24 hours") ));

						// Create the link to reset the password
						$pass_link = site_url('reset_password/'. str_replace('@', ':', $_POST['email']) .'/'. $hash);

						// Now send the email
						$this->load->library('emailer/emailer');

						$data = array(
									'to'	=> $_POST['email'],
									'subject'	=> 'Your Temporary Password',
									'message'	=> $this->load->view('_emails/forgot_password', array('link' => $pass_link), true)
							 );

						if ($this->emailer->send($data))
						{
							Template::set_message('Please check your email for instructions to reset your password.', 'success');
						}
						else
						{
							Template::set_message('Unable to send an email: '. $this->emailer->errors, 'error');
						}
					}
				}

			}

			Template::set_view('users/users/forgot_password');
			Template::set('page_title', 'Password Reset');
			Template::render();
		}
		else {

			Template::redirect('/');
		}
	}

	//--------------------------------------------------------------------

	/*
		Method: profile

		Allows a user to edit their own profileinformation.
	*/
	public function profile()
	{

		if ($this->auth->is_logged_in() === FALSE)
		{
			$this->auth->logout();
			redirect('login');
		}

		if ($this->input->post('submit'))
		{

			$user_id = $this->current_user->id;
			if ($this->save_user($user_id))
			{

				$this->load->model('activities/Activity_model', 'activity_model');

				$user = $this->user_model->find($user_id);
				$log_name = (isset($user->display_name) && !empty($user->display_name)) ? $user->display_name : ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email);
				$this->activity_model->log_activity($this->current_user->id, lang('us_log_edit_profile') .': '.$log_name, 'users');

				Template::set_message('Profile successfully updated.', 'success');
			}
			else
			{
				Template::set_message('There was a problem updating your profile', 'error');
			}//end if
		}//end if

	/*
		@TODO : I do not think these are being used anymore so they are a waste of memory.

		$this->load->config('address');
		$this->load->helper('address');
	*/
		// get the current user information
		$user = $this->user_model->find_user_and_meta ( $this->current_user->id );

		Template::set('user', $user);

		Template::set_view('users/users/profile');
		Template::render();
	}

	//--------------------------------------------------------------------

	/*
		Method: reset_password()

		Allows the user to create a new password for their account. At the moment,
		the only way to get here is to go through the forgot_password() process,
		which creates a unique code that is only valid for 24 hours.

		Parameters:
			$email	- The email address to check against.
			$code	- A randomly generated alphanumeric code. (Generated by forgot_password() ).
	*/
	public function reset_password($email='', $code='')
	{
		// if the user is not logged in continue to show the login page
		if ($this->auth->is_logged_in() === false)
		{
			// If there is no code, then it's not a valid request.
			if (empty($code) || empty($email))
			{
				Template::set_message('That did not appear to be a valid password reset request.', 'attention');
				redirect('/login');
			}

			// Handle the form
			if ($this->input->post('submit'))
			{
				$this->form_validation->set_rules('password', 'lang:bf_password', 'required|trim|strip_tags|min_length[8]|max_length[120]|valid_password|xsx_clean');
				$this->form_validation->set_rules('pass_confirm', 'lang:bf_password_confirm', 'required|trim|strip_tags|matches[password]');

				if ($this->form_validation->run() !== false)
				{
					// The user model will create the password hash for us.
					$data = array('password' => $this->input->post('password'),
					              'pass_confirm'	=> $this->input->post('pass_confirm'),
					              'reset_by'		=> 0,
					              'reset_hash'	=> '');

					if ($this->user_model->update($this->input->post('user_id'), $data))
					{
						$this->load->model('activities/Activity_model', 'activity_model');

						$this->activity_model->log_activity($this->input->post('user_id'), lang('us_log_reset') , 'users');
						Template::set_message('Please login using your new password.', 'success');
						redirect('/login');
					}
					else
					{
						Template::set_message('There was an error resetting your password: '. $this->user_model->error, 'error');
					}
				}
			}

			// Check the code against the database
			$email = str_replace(':', '@', $email);
			$user = $this->user_model->find_by(array(
			                                        'email' => $email,
													'reset_hash' => $code,
													'reset_by >=' => time()
			                                   ));

			// It will be an Object if a single result was returned.
			if (!is_object($user))
			{
				Template::set_message('That did not appear to be a valid password reset request.', 'attention');
				redirect('/login');
			}

			// If we're here, then it is a valid request....
			Template::set('user', $user);

			Template::set_view('users/users/reset_password');
			Template::render();
		}
		else {

			Template::redirect('/');
		}
	}

	//--------------------------------------------------------------------

	public function register()
	{
		// Are users even allowed to register?
		if (!$this->settings_lib->item('auth.allow_register'))
		{
			Template::set_message('New account registrations are not allowed.', 'attention');
			redirect('/');
		}

		$this->load->model('roles/role_model');

		if ($this->input->post('submit'))
		{
			// Validate input
			$this->form_validation->set_rules('email', 'lang:bf_email', 'required|trim|strip_tags|valid_email|max_length[120]|unique[bf_users.email]|xsx_clean');

			if ($this->settings_lib->item('auth.use_usernames'))
			{
				$this->form_validation->set_rules('username', 'lang:bf_username', 'required|trim|strip_tags|max_length[30]|unique[bf_users.username]|xsx_clean');
			}

			$this->form_validation->set_rules('password', 'lang:bf_password', 'required|trim|strip_tags|min_length[8]|max_length[120]|valid_password|xsx_clean');
			$this->form_validation->set_rules('pass_confirm', 'lang:bf_password_confirm', 'required|trim|strip_tags|matches[password]');

			if ($this->form_validation->run() !== false)
			{
				// Time to save the user...
				$data = array('email'		=> $_POST['email'],
				              'username'	=> isset($_POST['username']) ? $_POST['username'] : '',
				              'password'	=> $_POST['password']);

				if ($user_id = $this->user_model->insert($data))
				{
					$this->load->model('activities/Activity_model', 'activity_model');

					$this->activity_model->log_activity($user_id, lang('us_log_register') , 'users');
					Template::set_message('Your account has been created. Please log in.', 'success');
					redirect('login');
				}
			}
		}

		Template::set_view('users/users/register');
		Template::set('page_title', 'Register');
		Template::render();
	}

	//--------------------------------------------------------------------

	public function unique_email($email)
	{
		if ($this->user_model->is_unique('email', $email) === true)
		{
			return true;
		} else {
			$this->form_validation->set_message('unique_email', 'That email address is already in use.');
			return false;
		}
	}

	//--------------------------------------------------------------------

	public function unique_username($username)
	{

		if ($this->user_model->is_unique('username', $username.',bf_users.id') === true)
		{
			return true;
		} else {
			$this->form_validation->set_message('unique_username', 'That username is already in use.');
			return false;
		}
	}

	//--------------------------------------------------------------------

	private function save_user($id=0)
	{

		if ( $id == 0 )
		{
			$id = $this->current_user->id; /* ( $this->input->post('id') > 0 ) ? $this->input->post('id') :  */
		}

		$_POST['id'] = $id;

		// Simple check to make the posted id is equal to the current user's id, minor security check
		if ( $_POST['id'] != $this->current_user->id )
		{
			$this->form_validation->set_message('email', 'Invalid user id.');
			return false;
		}

		// Setting the payload for Events system.
		$payload = array ( 'user_id' => $id, 'data' => $this->input->post() );


		$this->form_validation->set_rules('email', 'lang:bf_email', 'required|trim|valid_email|max_length[120]|unique[bf_users.email,bf_users.id]|xss_clean');
		$this->form_validation->set_rules('password', 'lang:bf_password', 'trim|strip_tags|min_length[8]|max_length[120]|valid_password|xss_clean');

		// check if a value has been entered for the password - if so then the pass_confirm is required
		// if you don't set it as "required" the pass_confirm field could be left blank and the form validation would still pass
		$extra_rules = !empty($_POST['password']) ? 'required|' : '';
		$this->form_validation->set_rules('pass_confirm', 'lang:bf_password_confirm', 'trim|strip_tags|'.$extra_rules.'matches[password]|xss_clean');

		if ($this->settings_lib->item('auth.use_usernames'))
		{
			$this->form_validation->set_rules('username', 'lang:bf_username', 'required|trim|strip_tags|max_length[30]|unique[bf_users.username,bf_users.id]|xsx_clean');
		}

		$this->form_validation->set_rules('display_name', 'lang:bf_display_name', 'trim|strip_tags|max_length[255]|xss_clean');

		// Added Event "before_user_validation" to run before the form validation
		Events::trigger('before_user_validation', $payload );

		if ($this->form_validation->run() === false)
		{
			return false;
		}

		// Compile our core user elements to save.
		$data = array( 'email' => $this->input->post('email') );

		if ($this->input->post('password'))
			$data['password'] = $this->input->post('password');

		if ($this->input->post('display_name'))
			$data['display_name'] = $this->input->post('display_name');

		if ($this->settings_lib->item('auth.use_usernames'))
		{
			if ($this->input->post('username'))
				$data['username'] = $this->input->post('username');
		}

		// Any modules needing to save data?
		// Event to run after saving a user
		Events::trigger('save_user', $payload );

		return $this->user_model->update($id, $data);
	}

	//--------------------------------------------------------------------

}

/* Front-end Users Controller */
/* End of file users.php */
/* Location: ./application/core_modules/users/controllers/users.php */