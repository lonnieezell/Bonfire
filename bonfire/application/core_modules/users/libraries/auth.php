<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Class: Auth
	
	Provides authentication functions for logging users in/out, restricting access
	to controllers, and managing login attempts. 
	
	Security and ease-of-use are the two primary goals of the Auth system in Bonfire. 
	This lib will be constantly updated to reflect the latest security practices that
	we learn about, while maintaining the simple API.
	
	Author: 
		Lonnie Ezell
*/
class Auth {
	
	/*
		Var: $errors
		An array of errors generated.
	*/
	public	$errors	= array();
	
	/*
		Var: $logged_in
		Stores the logged in value after the first test to improve performance.
		
		Access:
			Private
	*/
	private $logged_in = null;
	
	/*
		Var: $ip_address
		Stores the ip_address of the current user for performance reasons.
		
		Access:
			Private
	*/
	private $ip_address;
	
	/*
		Var: $perms
		Stores permissions by role so we don't have to scour the database more than once.
		
		Access:
			Private
	*/
	private $perms = array();
	
	/*
		Var: $ci
		A pointer to the CodeIgniter instance.
		
		Access:
			Private
	*/
	private $ci;
		
	//--------------------------------------------------------------------
	
	/*
		Method: __construct()
		
		Grabs a pointer to the CI instance, gets the user's IP address,
		and attempts to automatically log in the user.
	*/
	public function __construct() 
	{		
		$this->ci =& get_instance();
		
		$this->ip_address = $this->ci->input->ip_address();
		
		log_message('debug', 'Auth class initialized.');
				
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}
				
		// Try to log the user in from session/cookie data
		$this->autologin();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: login()
	
		Attempt to log the user in.
		
		Parameters:
			$login		The user's login credentials (email/username)
			$password	The user's password
			$remember	Whether the user should be remembered in the system.
			
		Return:
			true/false on success/failure.
	 */ 
	public function login($login=null, $password=null, $remember=false) 
	{
		if (empty($login) || empty($password))
		{
			$error = config_item('auth.login_type') == 'both' ? 'Username/Email' : ucfirst(config_item('auth.login_type'));
			$this->errors[] = $error .' and Password fields must be filled out.';
			return false;
		}
	
		if (!class_exists('User_model'))
		{
			$this->ci->load->model('users/User_model', 'user_model', true);
		}
	
		// Grab the user from the db
		$user = $this->ci->user_model->select('id, email, username, users.role_id, salt, password_hash, temp_password_hash')->find_by(config_item('auth.login_type'), $login);
		
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
				$this->increase_login_attempts($login);
			}
			// Bad username
			$this->increase_login_attempts($login);
			$this->errors[] = 'Incorrect email or password.';
		}
		
		return false;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: logout()
		
		Destroys the autologin information and the current session.
		
		Return:
			void
	*/
	public function logout() 
	{
		// Destroy the autologin information
		$this->delete_autologin();
	
		// Destroy the session
		$this->ci->session->sess_destroy();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: is_logged_in()
	
		Checks the session for the required info, then 
		verifies against the database.
		
		Return:
			true/false
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
	
	/*
		Method: restrict()
	
		Checks that a user is logged in (and, optionally of the correct role)
		and, if not, send them to the login screen.
		
		If no permission is checked, will simply verify that the user is logged in.
		If a permission is passed in to the first parameter, will check the user's role
		and verify that role has the appropriate permission.
		
		Parameters:
			$permission	- (Optional) A string representing the permission to check for.
			
		Return:
			true		- if the user has the appropriate access permissions.
			redirect	- to the previous page if the user doesn't have permissions.
			redirect	- '/login' page if the user is not logged in.
	 */
	public function restrict($permission=null) 
	{	
		// If user isn't logged in, don't need to check permissions
		if ($this->is_logged_in() === false)
		{
			Template::set_message('You must be logged in to view that page.', 'error');
			redirect('login');
		}

		// Check to see if the user has the proper permissions
		if (!empty($permission) && !$this->has_permission($permission))
		{ 
			Template::set_message('You do not have permission to access that page.', 'attention');
			redirect($this->ci->session->userdata('previous_page'));
		} 
		
		return true;
	}
	
	//--------------------------------------------------------------------
	
	
	
	//--------------------------------------------------------------------
	// !UTILITY METHODS
	//--------------------------------------------------------------------
	
	/*
		Method: user_id()
		
		Retrieves the user_id from the current session.
		
		Return:
			The user's id.
	*/
	public function user_id() 
	{
		return $this->ci->session->userdata('user_id');
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: email()
		
		Retrieves the email address from the current session.
		
		Return:
			The user's email.
	*/
	public function email() 
	{
		return $this->ci->session->userdata('email');
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: role_id()
		
		Retrieves the role_id from the current session.
		
		Return:
			The user's role_id.
	*/
	public function role_id() 
	{
		return $this->ci->session->userdata('role_id');
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: has_permission()

		Verifies that the user is logged in and has the appropriate access permissions.
		
		Parameters:
			$permission	- A string with the permission to check for, ie 'Site.Signin.Allow'
			$role_id	- The id of the role to check the permission against. If not role_id is
							passed into the method, then it assumes it to be the current user's role_id.
							
		Return:
			true/false
	*/
	public function has_permission($permission = null, $role_id=null) 
	{
		if (empty($permission))
		{
			return false;
		}
	
		// If no role is being provided, assume it's for the current
		// logged in user.
		if (empty($role_id))
		{
			$role_id = $this->role_id();
		}
		
		// Have we already checked this? 
		if (isset($this->perms[$role_id]))
		{
			$perms = $this->perms[$role_id];
		}
		else
		{
			$perms = $this->ci->permission_model->find_for_role($role_id);
			if (is_array($perms)) $perms = $perms[0];
			
			// Store it so we can reference later
			$this->perms[$role_id] = $perms;
		}
				
		// Did we pass?
		if (isset($perms->$permission) && $perms->$permission == 1)
		{
			return true;
		}

		return false;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: role_name_by_id()
		
		Retrieves the role_name for the request role.
		
		Parameters:
			$role_id	- An int representing the role_id.
			
		Return:
			A string with the name of the matched role.
	*/
	public function role_name_by_id($role_id=0) 
	{
		if (empty($role_id) || !is_numeric($role_id))
		{
			return '';
		}
		
		$roles = array();
		
		// If we already stored the role names, use those...
		if (isset($this->role_names))
		{
			$roles = $this->role_names;
		}
		else 
		{
			$results = $this->ci->role_model->select('role_id, role_name')->find_all();
			
			foreach ($results as $role)
			{
				$roles[$role->role_id] = $role->role_name;
			}
			
			unset($results);
		}
		
		// Try to return the role name
		if (isset($roles[$role_id]))
		{
			return $roles[$role_id];
		}
		
		return '';
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !LOGIN ATTEMPTS
	//--------------------------------------------------------------------
	
	/*
		Method: increase_login_attempts()
	
		Records a login attempt into the database.
		
		Parameters:
			$login	- The login id used (typically email or username)
			
		Return:
			void
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
	
	/*	
		Method: clear_login_attempts()
		
		Clears all login attempts for this user, as well as cleans out old
		logins.
		
		Parameters:
			$login		- The login credentials (typically email)
			$expires	- The time (in seconds) that attempts older than will be deleted

		Return:
			void
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
	
	/*
		Method: num_login_attempts()
	
		Get number of attempts to login occured from given IP-address and/or login
		
		Parameters:
			$login	- (Optional) The login id to check for (email/username). 
						If no login is passed in, it will only check against 
						the IP Address of the current user.
						
		Return:
			An int with the number of attempts.
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
	
	/*
		Method: autologin()
		
		Attempts to log the user in based on an existing 'autologin' cookie.
		
		Return:
			void
	 */
	private function autologin() 
	{
		if ($this->ci->config->item('auth.allow_remember') == false) 
		{ 
			return; 
		}
		
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
			// Save logged in status to save on db access later.
			$this->logged_in = true;
			
			// If a session doesn't exist, we need to refresh our autologin token
			// and get the session started.
			if (!$this->ci->session->userdata('user_id'))
			{
				// Grab the current user info for the session
				$this->ci->load->model('users/User_model', 'user_model', true);
				$user = $this->ci->user_model->select('id, email, password_hash, users.role_id')->find($user_id);
				
				if (!$user) { return; }
				
				$this->setup_session($user->id, $user->password_hash, $user->email, $user->role_id, true, $test_token);
			}
		}
		
		unset($query, $user);
	}
	
	//--------------------------------------------------------------------
	
	
	/*
		Method: create_autologin()
	
		Create the auto-login entry in the database. This method uses
		Charles Miller's thoughts at: 
		http://fishbowl.pastiche.org/2004/01/19/persistent_login_cookie_best_practice/
		
		Parameters:
			$user_id	- An int representing the user_id.
			$old_token	- The previous token that was used to login with.
			
		Return:
			true/false	- Whether the autologin was created or not.
			
		Access:
			Private
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
	
	/*
		Method: delete_autologin()
		
		Deletes the autologin cookie for the current user.
		
		Return:
			void
			
		Access:
			Private
	*/
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
	
	/*
		Method: setup_session()
		
		Creates the session information for the current user. Will also create
		an autologin cookie if required.
		
		Parameters:
			$user_id		- An int with the user's id 
			$password_hash	- The user's password hash. Used to create a new, unique user_token.
			$email			- The user's email address.
			$role_id		- The user's role_id
			$remember		- A boolean (true/false). Whether to keep the user logged in.
			
		Return: 
			true/false on success/failure.
			
		Access:
			Private
	*/
	private function setup_session($user_id=0, $password_hash=null, $email='', $role_id=0, $remember=false, $old_token=null) 
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
		if (!function_exists('do_hash'))
		{
			$this->ci->load->helper('security');
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
			return $this->create_autologin($user_id, $old_token);
		}
		
		return true;
	}
	
	//--------------------------------------------------------------------
	
}

// End Auth class

//--------------------------------------------------------------------

/*
	Function: auth_errors()

	A utility function for showing authentication errors.
	
	Return:
		A string with a <ul> tag of any auth errors, or an empty string if no errors exist.
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

/*
	Function: has_permission()
	
	A convenient shorthand for checking user permissions.
	
	Parameters:
		$permission	- The permission to check for, ie 'Site.Signin.Allow'
	
	Return:
		true/false
 */ 
function has_permission($permission=null)
{
	$ci =& get_instance();
	
	if (class_exists('Auth'))
	{
		return $ci->auth->has_permission($permission); 
	}
	
	return false;
}