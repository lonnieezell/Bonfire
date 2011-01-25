<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

	public $ci;
	
	public	$errors	= array();
	
	private $logged_in = null;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{		
		$this->ci =& get_instance();
		
		log_message('debug', 'Auth class initialized.');
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	Attempt to log the user in.
	 */ 
	public function try_login($email=null, $password=null, $remember=false) 
	{
		if (empty($email) || empty($password))
		{
			$this->errors[] = 'Both Email and Password fields must be filled out.';
			return false;
		}
	
		// Grab the user from the db
		$user = $this->ci->user_model->select('id, email, salt, password_hash')->find_by('email', $email);
		
		if (is_array($user))
		{
			$user = $user[0];
		}
		
		if ($user)
		{
			// Validate the password
			if (!function_exists('dohash'))
			{
				$this->ci->load->helper('security');
			}

			if ( dohash($user->salt . $password) == $user->password_hash)
			{ 
				$this->setup_session($user->id, $user->password_hash, $user->email, null, $remember);
				
				// Log the last login date
				$this->ci->user_model->update($user->id, array('last_login' => date('Y-m-d H:i:s', time())));
				
				return true;
			}
			
			$this->errors[] = 'Incorrect email or password.';
		}
		
		return false;
	}
	
	//--------------------------------------------------------------------
	
	public function logout() 
	{
		// Destroy the autologin cookie
		$this->ci->load->helper('cookie');
		
		delete_cookie('autologin');
	
		// Destroy the session
		$this->ci->session->sess_destroy();
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Checks the session for the required info, then 
	 * verifies against the database.
	 */
	public function is_logged_in() 
	{
		// If we've already checked this session, 
		// return that.
		if (!is_null($this->logged_in))
		{
			return $this->logged_in;
		}
	
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}
		
		// Is there any session data we can use? 
		if ($this->ci->session->userdata('email') && $this->ci->session->userdata('user_id'))
		{
			// Grab the user account
			$user = $this->ci->user_model->select('id, email, salt, password_hash')->find($this->ci->session->userdata('user_id'));
			
			if ($user !== false)
			{
				if (!function_exists('dohash')) 
				{
					$this->ci->load->helper('security');
				}
				
				// Ensure user_token is still equivalent to the SHA1 of the user_id and password_hash
				if (dohash($this->ci->session->userdata('user_id') . $user->password_hash) === $this->ci->session->userdata('user_token')) 
				{ 
					$this->logged_in = true;
					return TRUE;
				}
			}
		}
		
		$this->logged_in = false;
		return false;
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Checks that a user is logged in (and, optionally of the correct role)
	 * and, if not, send them to the login screen.
	 *
	 * Valid Role id constants are: 
	 * 		ROLE_ADMINISTRATOR
	 *		ROLE_MANAGER
	 *		ROLE_CUSTOMER
	 */
	public function restrict($role_id=null) 
	{	
		if ($this->is_logged_in() === false)
		{
			redirect('/login');
		}
		
		// Role Check
		if (!empty($role_id) && $this->role_id() >= $role_id)
		{
			Template::set('You do not have permission to access this page.', 'attention');
			redirect('login');
		}
	}
	
	//--------------------------------------------------------------------
	
	
	
	//--------------------------------------------------------------------
	// !UTILITY METHODS
	//--------------------------------------------------------------------
	
	public function user_id() 
	{
		return $this->ci->session->userdata('user_id');
	}
	
	//--------------------------------------------------------------------
	
	public function email() 
	{
		return $this->ci->session->userdata('email');
	}
	
	//--------------------------------------------------------------------
	
	
	public function role_id() 
	{
		return $this->ci->session->userdata('role_id');
	}
	
	//--------------------------------------------------------------------
	
	public function logged_in() 
	{
		return $this->ci->session->userdata('logged_in');
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	private function setup_session($id=0, $password_hash=null, $email='', $role_id=0, $remember=false) 
	{
		if (empty($id) || empty($email))
		{
			return false;
		}
		
		// Save the user's session info
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}
		
		$data = array(
			'user_id'		=> $id,
			'user_token'	=> dohash($id . $password_hash),
			'email'			=> $email,
			'role'			=> $role_id,
			'logged_in'		=> true,
		);
		
		$this->ci->session->set_userdata($data);
		
		// Should we remember the user?
		if ($remember === true)
		{
			$this->ci->load->helper('cookie');
			
			$cookie = array(
					'name' 		=> 'autologin',
					'value'		=> serialize($data),
					'expire'	=> 60*60*24*14			// Two weeks
				);
		
			set_cookie($cookie);
		}
		
		return true;
	}
	
	//--------------------------------------------------------------------
	
}

// End Auth class

/**
 * A utility function for showing authentication errors.
 */
if (!function_exists('auth_errors'))
{
	function auth_errors()
	{
		$ci =& get_instance();
		
		$errors = $ci->auth->errors;
		
		if (count($errors))
		{
			$str = '<ul>';
			foreach ($errors as $e)
			{
				$str .= "<li>$e</li>";
			}
			$str .= "</li>";
			
			return $str;
		}
		
		return '';
	}
}

//--------------------------------------------------------------------