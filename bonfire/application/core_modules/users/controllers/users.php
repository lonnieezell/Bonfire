<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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
	}
	
	//--------------------------------------------------------------------
	
	public function login() 
	{	
		if ($this->input->post('submit'))
		{
			if ($this->input->post('remember_me') == '1')
			{
				$remember = true;
			} 
			else 
			{
				$remember = false;
			}
		
			// Try to login
			if ($this->auth->login($this->input->post('login'), $this->input->post('password'), $remember) === true)
			{
				redirect('admin/content');
			}
		}
	
		Template::set_view('users/users/login');
		Template::set('page_title', 'Login');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function logout() 
	{
		$this->auth->logout();
		redirect('/');
	}
	
	//--------------------------------------------------------------------
	
	public function forgot_password() 
	{
		if (isset($_POST['submit']))
		{
			$this->form_validation->set_rules('email', 'Email', 'required|trim|strip_tags|valid_email|xss_clean');
			
			if ($this->form_validation->run() === FALSE)
			{
				Template::set_message('Cannot find that email in our records.', 'error');
			} else 
			{
				// We validated. Does the user actually exist?
				$user = $this->user_model->find_by('email', $_POST['email']);
				
				if (count($user) == 1)
				{
					// User exists, so create a temp password.
					$this->load->helper('string');
					$this->load->helper('security');
					
					$new_pass = random_string('alnum', 12);
					
					$hash = do_hash($new_pass . $user[0]->salt . $_POST['email']);
					
					// Save the hash to the db so we can confirm it later.
					$this->user_model->update_where('email', $_POST['email'], array('temp_password_hash' => $hash));
					
					// Now send the email
					$this->load->library('emailer/emailer');
					
					$data = array(
						'to'	=> $_POST['email'],
						'subject'	=> 'Your Temporary Password',
						'message'	=> $this->load->view('_emails/forgot_password', array('new_pass', $new_pass), true)
					);
					
					if ($this->emailer->send($data))
					{
						Template::set_message('Your temporary password has been emailed to you.', 'success');
					}
					else
					{
						Template::set_message('Unable to send your temporary password: '. $this->emailer->errors, 'success');
					}
				}
			}
						
		}
	
		Template::set_view('users/users/forgot_password');
		Template::set('page_title', 'Password Reset');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function register() 
	{
		// Are users even allowed to register? 
		if (!$this->config->item('auth.allow_register'))
		{
			Template::set_message('New account registrations are not allowed.', 'attention');
			redirect('/');
		}
		
		$this->load->model('roles/permission_model');
		$this->load->model('roles/role_model');
	
		if ($this->input->post('submit'))
		{
			// Validate input
			$this->form_validation->set_rules('email', 'Email', 'required|trim|strip_tags|valid_email|max_length[120]|callback_unique_email|xsx_clean');
			if (config_item('auth.use_usernames'))
			{
				$this->form_validation->set_rules('username', 'Username', 'required|trim|strip_tags|max_length[30]|callback_unique_username|xsx_clean');
			}
			$this->form_validation->set_rules('password', 'Password', 'required|trim|strip_tags|min_length[8]|max_length[120]|xsx_clean');
			$this->form_validation->set_rules('pass_confirm', 'Password (again)', 'required|trim|strip_tags|matches[password]');
			
			if ($this->form_validation->run() !== false)
			{
				// Time to save the user...
				$data = array(
					'email'		=> $_POST['email'],
					'username'	=> isset($_POST['username']) ? $_POST['username'] : '',
					'password'	=> $_POST['password']
				);

				if ($this->user_model->insert($data))
				{	
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
		}
		else
		{
			$this->form_validation->set_message('unique_email', 'That email address is already in use.');
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	public function unique_username($username) 
	{
		if ($this->user_model->is_unique('username', $username) === true)
		{
			return true;
		}
		else 
		{
			$this->form_validation->set_message('unique_username', 'That username is already in use.');
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	
}

// End Authorize class