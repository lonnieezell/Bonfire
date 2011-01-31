<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

	public $ci;
	
	public	$errors	= array();
	
	private $logged_in = null;
	private $ip_address;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{		
		$this->ci =& get_instance();
		
		$this->ip_address = $this->ci->input->ip_address();
		
		log_message('debug', 'Auth class initialized.');
		
		// Try to log the user in from session/cookie data
		$this->autologin();
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	Attempt to log the user in.
	 *
	 * @param	string	$login		The user's login credentials (email/username)
	 * @param	string	$password	The user's password
	 * @param	bool	$remember	Whether the user should be remembered in the system.
	 */ 
	public function login($login=null, $password=null, $remember=false) 
	{
		if (empty($login) || empty($password))
		{
			$error = config_item('auth.login_type') == 'both' ? 'Username/Email' : ucfirst(config_item('auth.login_type'));
			$this->errors[] = $error .' and Password fields must be filled out.';
			return false;
		}
	
		// Grab the user from the db
		$user = $this->ci->user_model->select('id, email, username, role_id, salt, password_hash, temp_password_hash')->find_by(config_item('auth.login_type'), $login);
		
		if (is_array($user))
		{
			$user = $user[0];
		}
		
		if ($user)
		{
			// Validate the password
			if (!function_exists('do_hash'))
			{
				$this->ci->load->helper('security');
			}

			// Try both the primary password, then the temp password
			if ( do_hash($user->salt . $password) == $user->password_hash || do_hash($password . $user->salt . $user->email) == $user->temp_password_hash)
			{ 
				$this->clear_login_attempts($login);
				// We've successfully validated the login, so setup the session
				$this->setup_session($user->id, $user->password_hash, $user->email, $user->role_id, $remember);
				
				// Save the login info
				$data = array(
					'last_login'			=> date('Y-m-d H:i:s', time()),
					'last_ip'				=> $this->ip_address,
					'temp_password_hash'	=> null	// Clear any temp passwords. One time use only!
				);
				$this->ci->user_model->update($user->id, $data);
				
				return true;
			}
			
			// Bad password
			else
			{
				$this->increase_login_attempts($email);
			}
			// Bad username
			$this->increase_login_attempts($email);
			$this->errors[] = 'Incorrect email or password.';
		}
		
		return false;
	}
	
	//--------------------------------------------------------------------
	
	public function logout() 
	{
		// Destroy the autologin information
		$this->delete_autologin();
	
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
				if (!function_exists('do_hash')) 
				{
					$this->ci->load->helper('security');
				}
				
				// Ensure user_token is still equivalent to the SHA1 of the user_id and password_hash
				if (do_hash($this->ci->session->userdata('user_id') . $user->password_hash) === $this->ci->session->userdata('user_token')) 
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
	 */
	public function restrict($role_name=null) 
	{	
		// Check to see if the user has the proper role
		if (!empty($role_name) && $this->role_id() >= $role_id)
		{
			Template::set('You do not have permission to access this page.', 'attention');
			redirect('login');
		}
	
		if ($this->is_logged_in() === false)
		{
			Template::set_message('You must be logged in to view that page.', 'error');
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
		return $this->logged_in;
	}
	
	//--------------------------------------------------------------------
	
	public function has_permission($permission = null, $role_id=null) 
	{
		if (empty($permission))
		{
			return false;
		}
	
		// If not role is being provided, assume it's for the current
		// logged in user.
		if (empty($role_id))
		{
			$role_id = $this->role_id();
		}
				
		$perms = $this->ci->permission_model->find_for_role($role_id);
		if (is_array($perms)) $perms = $perms[0];
		
		if (isset($perms->$permission) && $perms->$permission == 1)
		{
			return true;
		}
		
		return false;
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !LOGIN ATTEMPTS
	//--------------------------------------------------------------------
	
	/**
	 * Records a login attempt into the database.
	 *
	 * @param	string	$login	The login id used (typically email)
	 * @return	void
	 */
	protected function increase_login_attempts($login=null) 
	{
		if (empty($this->ip_address) || empty($login))
		{
			return;
		}
		
		$this->ci->db->insert('login_attempts', array('ip_address' => $this->ip_address, 'login' => $login));
	}
	
	//--------------------------------------------------------------------
	
	/** 
	 * Clears all login attempts for this user, as well as cleans out old
	 * logins.
	 *
	 * @param	string	$login		The login credentials (typically email)
	 * @param	int		$expires	The time (in seconds) that attempts older than will be deleted
	 * @return	void
	 */
	protected function clear_login_attempts($login=null, $expires = 86400) 
	{
		if (empty($this->ip_address) || empty($login))
		{
			return;
		}
	
		$this->ci->db->where(array('ip_address' => $this->ip_address, 'login' => $login));
		
		// Purge obsolete login attempts
		$this->ci->db->or_where('UNIX_TIMESTAMP(time) <', time() - $expires);

		$this->ci->db->delete('login_attempts');
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Get number of attempts to login occured from given IP-address or login
	 *
	 * @param	string
	 * @param	string
	 * @return	int
	 */
	function num_login_attempts($login=null)
	{
		$this->ci->db->select('1', FALSE);
		$this->ci->db->where('ip_address', $this->ip_address);
		if (strlen($login) > 0) $this->ci->db->or_where('login', $login);

		$query = $this->ci->db->get('login_attempts');
		return $query->num_rows();
	}
	
	//--------------------------------------------------------------------
	// !AUTO-LOGIN 
	//--------------------------------------------------------------------
	
	/**
	 * Autologin()
	 *
	 * Attempts to log the user in based on an existing 'autologin' cookie.
	 *
	 * @return	bool
	 */
	private function autologin() 
	{
		if ($this->logged_in || $this->ci->config->item('auth.allow_remember') == false) 
		{ 
			return true; 
		}
		
		$return = false;
		$this->ci->load->helper('cookie');
		
		$cookie = get_cookie('autologin', true);
		
		if (!$cookie) {	return;	}
		
		// We have a cookie, so split it into user_id and token
		list($user_id, $test_token) = explode('~', $cookie);
		
		// Try to pull a match from the database
		$this->ci->db->where( array('user_id' => $user_id, 'token' => $test_token) );
		$query = $this->ci->db->get('user_cookies');
		
		if ($query->num_rows() == 1)
		{
			// We have a match
			$return = true;
		}
		
		unset($query);
		return $return;
	}
	
	//--------------------------------------------------------------------
	
	
	/**
	 *	Create the auto-login entry in the database. This method uses
	 * Charles Miller's thoughts at: 
	 * http://fishbowl.pastiche.org/2004/01/19/persistent_login_cookie_best_practice/
	 * 
	 * @param	int		$user_id
	 * @param	string	$old_token	The previous token that was used to login with.
	 */
	private function create_autologin($user_id=0, $old_token=null) 
	{
		if (empty($user_id) || $this->ci->config->item('auth.allow_remember') == false)
		{
			return false;
		}
		
		// Generate a random string for our token
		if (!function_exists('random_string')) { $this->load->helper('string'); }
		
		$token = random_string('alnum', 128);
		
		// If an old_token is presented, we're refreshing the autologin information
		// otherwise we're creating a new one.
		if (empty($old_token))
		{
			// Create a new token
			$data = array(
				'user_id'		=> $user_id,
				'token'			=> $token,
				'created_on'	=> date('Y-m-d H:i:s')
			);
			$this->ci->db->insert('user_cookies', $data);
		}
		else
		{
			// Refresh the token
			$this->ci->db->where('user_id', $user_id);
			$this->ci->db->where('token', $old_token);
			$this->ci->db->set('token', $token);
			$this->ci->db->set('created_on', date('Y-m-d H:i:s'));
			$this->ci->db->update('user_cookies');
		}
		
		if ($this->ci->db->affected_rows())
		{
			// Create the autologin cookie
			$this->ci->input->set_cookie('autologin', $user_id .'~'. $token, $this->ci->config->item('auth.remember_length'));	
		
			return true;
		} else
		{
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	private function delete_autologin() 
	{
		if ($this->ci->config->item('auth.allow_remember') == false)
		{
			return;
		}
	
		// First things first.. grab the cookie so we know what row
		// in the user_cookies table to delete.
		if (!function_exists('delete_cookie'))
		{
			$this->ci->load->helper('cookie');
		}
		
		$cookie = get_cookie('autologin');
		if ($cookie)
		{
			list($user_id, $token) = explode('~', $cookie);
			
			// Now we can delete the cookie
			delete_cookie('autologin');		
			
			// And clean up the database
			$this->ci->db->where('user_id', $user_id);
			$this->ci->db->where('token', $token);
			$this->ci->db->delete('user_cookies');
		}
		
		// Also perform a clean up of any autologins older than 2 months
		$this->ci->db->where('created_on', '< DATE_SUB(CURDATE(), INTERVAL 2 MONTH)');
		$this->ci->db->delete('user_cookies');
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	private function setup_session($user_id=0, $password_hash=null, $email='', $role_id=0, $remember=false) 
	{
		if (empty($user_id) || empty($email))
		{
			return false;
		}
		
		// Save the user's session info
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}
		
		$data = array(
			'user_id'		=> $user_id,
			'user_token'	=> do_hash($user_id . $password_hash),
			'email'			=> $email,
			'role_id'		=> $role_id,
			'logged_in'		=> true,
		);
		
		$this->ci->session->set_userdata($data);
		
		// Should we remember the user?
		if ($remember === true)
		{
			return $this->create_autologin($user_id);
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