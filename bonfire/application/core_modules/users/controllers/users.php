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
			if ($this->auth->try_login($this->input->post('email'), $this->input->post('password'), $remember) === true)
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
		Template::set_theme('auth');
		Template::set_view('users/users/forgot_password');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}

// End Authorize class