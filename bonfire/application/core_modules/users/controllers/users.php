<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Front_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->load->helper('form');
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
				redirect('admin/dashboard');
			}
		}
	
		Template::set_theme('auth');
		Template::set_view('users/users/login');
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
		$this->load->library('form_validation');
	
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
	
		Template::set_theme('auth');
		Template::set_view('users/users/forgot_password');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}

// End Authorize class