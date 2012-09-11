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
 * Auth Library
 *
 * Provides authentication functions for logging users in/out, restricting access
 * to controllers, and managing login attempts.
 *
 * Security and ease-of-use are the two primary goals of the Auth system in Bonfire.
 * This lib will be constantly updated to reflect the latest security practices that
 * we learn about, while maintaining the simple API.
 *
 * @package    Bonfire
 * @subpackage Modules_Users
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Auth
{

	/**
	 * An array of errors generated.
	 *
	 * @access public
	 *
	 * @var array
	 */
	public $errors = array();

	/**
	 * The url to redirect to on successful login.
	 *
	 * @access public
	 *
	 * @var string
	 */
	public $login_destination = '/';

	/**
	 * Stores the logged in value after the first test to improve performance.
	 *
	 * @access private
	 *
	 * @var NULL
	 */
	private $logged_in = NULL;

	/**
	 * Stores the ip_address of the current user for performance reasons.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $ip_address;

	/**
	 * Stores permissions by role so we don't have to scour the database more than once.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $perms = array();

	/**
	 * A pointer to the CodeIgniter instance.
	 *
	 * @access private
	 *
	 * @var object
	 */
	private $ci;

	//--------------------------------------------------------------------

	/**
	 * Grabs a pointer to the CI instance, gets the user's IP address,
	 * and attempts to automatically log in the user.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();

		$this->ip_address = $this->ci->input->ip_address();

		// We need the users language file for this to work
		// from other modules.
		$this->ci->lang->load('users/users');

		log_message('debug', 'Auth class initialized.');

		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}

		// Try to log the user in from session/cookie data
		$this->autologin();

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Attempt to log the user in.
	 *
	 * @access public
	 *
	 * @param string $login    The user's login credentials (email/username)
	 * @param string $password The user's password
	 * @param bool   $remember Whether the user should be remembered in the system.
	 *
	 * @return bool
	 */
	public function login($login=NULL, $password=NULL, $remember=FALSE)
	{
		if (empty($login) || empty($password))
		{
			$error = $this->ci->settings_lib->item('auth.login_type') == lang('bf_both') ? lang('bf_username') .'/'. lang('bf_email') : ucfirst($this->ci->settings_lib->item('auth.login_type'));
			Template::set_message(sprintf(lang('us_fields_required'), $error), 'error');
			return FALSE;
		}

		if (!class_exists('User_model'))
		{
			$this->ci->load->model('users/User_model', 'user_model', TRUE);
		}

		// Grab the user from the db
		$selects = 'id, email, username, users.role_id, salt, password_hash, users.role_id, users.deleted, users.active';

		if ($this->ci->settings_lib->item('auth.do_login_redirect'))
		{
			$selects .= ', login_destination';
		}

		$user = $this->ci->user_model->select($selects)->find_by($this->ci->settings_lib->item('auth.login_type'), $login);

		// check to see if a value of FALSE came back, meaning that the username or email or password doesn't exist.
		if($user == FALSE)
		{
			Template::set_message(lang('us_bad_email_pass'), 'error');
			return FALSE;
		}

		if (is_array($user))
		{
			$user = $user[0];
		}

		// check if the account has been activated.
		$activation_type = $this->ci->settings_lib->item('auth.user_activation_method');
		if ($user->active == 0 && $activation_type > 0) // in case we go to a unix timestamp later, this will still work.
		{
			if ($activation_type == 1)
			{
				Template::set_message(lang('us_account_not_active'), 'error');
			}
			elseif ($activation_type == 2)
			{
				Template::set_message(lang('us_admin_approval_pending'), 'error');
			}

			return FALSE;
		}

		// check if the account has been soft deleted.
		if ($user->deleted >= 1) // in case we go to a unix timestamp later, this will still work.
		{
			Template::set_message(sprintf(lang('us_account_deleted'), settings_item("site.system_email")), 'error');
			return FALSE;
		}

		if ($user)
		{
			// Validate the password
			if (!function_exists('do_hash'))
			{
				$this->ci->load->helper('security');
			}

			// Try password
			if (do_hash($user->salt . $password) == $user->password_hash)
			{
				// Do they even have permission to log in?
				if (!$this->has_permission('Site.Signin.Allow', $user->role_id))
				{
					$this->increase_login_attempts($login);
					Template::set_message(lang('us_banned_msg'), 'error');
					return FALSE;
				}

				$this->clear_login_attempts($login);

				// We've successfully validated the login, so setup the session
				$this->setup_session($user->id, $user->username, $user->password_hash, $user->email, $user->role_id, $remember,'', $user->username);

				// Save the login info
				$data = array(
					'last_login'			=> date('Y-m-d H:i:s', time()),
					'last_ip'				=> $this->ip_address,
				);
				$this->ci->user_model->update($user->id, $data);

				$trigger_data = array('user_id'=>$user->id, 'role_id'=>$user->role_id);
				Events::trigger('after_login', $trigger_data );

				// Save our redirect location
				$this->login_destination = isset($user->login_destination) && !empty($user->login_destination) ? $user->login_destination : '';

				return TRUE;
			}

			// Bad password
			else
			{
				Template::set_message(lang('us_bad_email_pass'), 'error');
				$this->increase_login_attempts($login);
			}
		}
		else
		{
			Template::set_message(lang('us_bad_email_pass'), 'error');
		}//end if

		return FALSE;

	}//end login()

	//--------------------------------------------------------------------

	/**
	 * Destroys the autologin information and the current session.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function logout()
	{
		$data = array(
			'user_id'	=> $this->user_id(),
			'role_id'	=> $this->role_id()
		);

		Events::trigger('before_logout', $data);

		// Destroy the autologin information
		$this->delete_autologin();

		// Destroy the session
		$this->ci->session->sess_destroy();

	}//end logout()

	//--------------------------------------------------------------------

	/**
	 * Checks the session for the required info, then verifies against the database.
	 *
	 * @access public
	 *
	 * @return bool|NULL
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
		if ($this->ci->session->userdata('identity') && $this->ci->session->userdata('user_id'))
		{
			// Grab the user account
			$user = $this->ci->user_model->select('id, username, email, salt, password_hash')->find($this->ci->session->userdata('user_id'));

			if ($user !== FALSE)
			{
				if (!function_exists('do_hash'))
				{
					$this->ci->load->helper('security');
				}

				// Ensure user_token is still equivalent to the SHA1 of the user_id and password_hash
				if (do_hash($this->ci->session->userdata('user_id') . $user->password_hash) === $this->ci->session->userdata('user_token'))
				{
					$this->logged_in = TRUE;
					return TRUE;
				}
			}
		}//end if

		$this->logged_in = FALSE;
		return FALSE;

	}//end is_logged_in()

	//--------------------------------------------------------------------

	/**
	 * Checks that a user is logged in (and, optionally of the correct role)
	 * and, if not, send them to the login screen.
	 *
	 * If no permission is checked, will simply verify that the user is logged in.
	 * If a permission is passed in to the first parameter, will check the user's role
	 * and verify that role has the appropriate permission.
	 *
	 * @access public
	 *
	 * @param string $permission (Optional) A string representing the permission to check for.
	 * @param string $uri        (Optional) A string representing an URI to redirect, if FALSE
	 *
	 * @return bool TRUE if the user has the appropriate access permissions. Redirect to the previous page if the user doesn't have permissions. Redirect '/login' page if the user is not logged in.
	 */
	public function restrict($permission=NULL, $uri=NULL)
	{
		// If user isn't logged in, don't need to check permissions
		if ($this->is_logged_in() === FALSE)
		{
			$this->logout();
			Template::set_message($this->ci->lang->line('us_must_login'), 'error');
			Template::redirect('login');
		}

		// Check to see if the user has the proper permissions
		if (!empty($permission) && !$this->has_permission($permission))
		{
			// set message telling them no permission THEN redirect
			Template::set_message( lang('us_no_permission'), 'attention');

			if ($uri)
			{
				Template::redirect($uri);
			}
			else
			{
				Template::redirect($this->ci->session->userdata('previous_page'));
			}
		}

		return TRUE;

	}//end restrict()

	//--------------------------------------------------------------------



	//--------------------------------------------------------------------
	// !UTILITY METHODS
	//--------------------------------------------------------------------

	/**
	 * Retrieves the user_id from the current session.
	 *
	 * @access public
	 *
	 * @return int
	 */
	public function user_id()
	{
		return (int) $this->ci->session->userdata('user_id');

	}//end user_id()

	//--------------------------------------------------------------------

	/**
	 * Retrieves the logged identity from the current session.
	 * Built from the user's submitted login.
	 *
	 * @access public
	 *
	 * @return string The identity used to login.
	 */
	public function identity()
	{
		return $this->ci->session->userdata('identity');

	}//end identity()

	//--------------------------------------------------------------------

	/**
	 * Retrieves the role_id from the current session.
	 *
	 * @return int The user's role_id.
	 */
	public function role_id()
	{
		return (int) $this->ci->session->userdata('role_id');

	}//end role_id()

	//--------------------------------------------------------------------

	/**
	 * Verifies that the user is logged in and has the appropriate access permissions.
	 *
	 * @access public
	 *
	 * @param string $permission A string with the permission to check for, ie 'Site.Signin.Allow'
	 * @param int    $role_id    The id of the role to check the permission against. If role_id is not passed into the method, then it assumes it to be the current user's role_id.
	 * @param bool   $override   Whether or not access is granted if this permission doesn't exist in the database
	 *
	 * @return bool TRUE/FALSE
	 */
	public function has_permission($permission = NULL, $role_id=NULL, $override = FALSE)
	{
		if (empty($permission))
		{
			return FALSE;
		}
		// move permission to lowercase for easier checking.
		else
		{
			$permission = strtolower($permission);
		}

		// If no role is being provided, assume it's for the current
		// logged in user.
		if (empty($role_id))
		{
			$role_id = $this->role_id();
		}

		if (empty($this->perms)) {
			$this->load_permissions($role_id);
		}

		$perms = (object)$this->perms;

		// Did we pass?
		if ((isset($perms->$permission) && $perms->$permission == 1) || (!in_array($permission, $this->perm_desc) && $override))
		{
			return TRUE;
		}

		return FALSE;

	}//end has_permission()

	//--------------------------------------------------------------------

	/**
	 * Checks to see whether a permission is in the system or not.
	 *
	 * @access public
	 *
	 * @param string $permission The name of the permission to check for. NOT case sensitive.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function permission_exists($permission=NULL)
	{
		if (empty($permission))
		{
			return FALSE;
		}
		// move permission to lowercase for easier checking.
		else
		{
			$permission = strtolower($permission);
		}

		if (!isset($this->all_perms)) {
			if (!class_exists('Permissions_model'))
			{
				$this->ci->load->model('permissions/permission_model');
				$this->ci->load->model('roles/role_permission_model');
			}

			$perms = $this->ci->permission_model->find_all();

			$this->all_perms = array();

			foreach ($perms as $perm)
			{
				$this->all_perms[] = strtolower($perm->name);
			}
		}

		 return in_array($permission, $this->all_perms);

	}//end permission_exists()

	//--------------------------------------------------------------------


	/**
	 * Load the permission details from the database into class properties
	 *
	 * @access public
	 *
	 * @param int $role_id An INT with the role id to grab permissions for.
	 *
	 * @return void
	 */
	public function load_permissions($role_id=NULL)
	{
		if (!class_exists('Permissions_model'))
		{
			$this->ci->load->model('permissions/permission_model');
			$this->ci->load->model('roles/role_permission_model');
		}

		$perms_all = $this->ci->permission_model->find_all_by('status','active');
		$perms = array();
		foreach($perms_all as $key => $perm_details)
		{
			$perms[$perm_details->permission_id] = $perm_details->name;
		}

		$this->perm_desc = $perms;

		$role_id = !is_null($role_id) ? $role_id : $this->role_id();

		$role_perms = $this->ci->role_permission_model->find_for_role($role_id);

		if (is_array($role_perms))
		{
			foreach($role_perms as $key => $permission)
			{
				$this->perms[strtolower($perms[$permission->permission_id])] = 1;
			}
		}

	}//end load_permissions()

	//--------------------------------------------------------------------


	/**
	 * Retrieves the role_name for the request role.
	 *
	 * @access public
	 *
	 * @param int $role_id An int representing the role_id.
	 *
	 * @return string A string with the name of the matched role.
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
			if (! class_exists('Role_model'))
			{
				$this->ci->load->model('roles/role_model');
			}
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

	}//end role_name_by_id()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !LOGIN ATTEMPTS
	//--------------------------------------------------------------------

	/**
	 * Records a login attempt into the database.
	 *
	 * @access protected
	 *
	 * @param string $login The login id used (typically email or username)
	 *
	 * @return void
	 */
	protected function increase_login_attempts($login=NULL)
	{
		if (empty($this->ip_address) || empty($login))
		{
			return;
		}

		$this->ci->db->insert('login_attempts', array('ip_address' => $this->ip_address, 'login' => $login));

	}//end increase_login_attempts()

	//--------------------------------------------------------------------

	/**
	 * Clears all login attempts for this user, as well as cleans out old logins.
	 *
	 * @access protected
	 *
	 * @param string $login   The login credentials (typically email)
	 * @param int    $expires The time (in seconds) that attempts older than will be deleted
	 *
	 * @return void
	 */
	protected function clear_login_attempts($login=NULL, $expires = 86400)
	{
		if (empty($this->ip_address) || empty($login))
		{
			return;
		}

		$this->ci->db->where(array('ip_address' => $this->ip_address, 'login' => $login));

		// Purge obsolete login attempts
		$this->ci->db->or_where('UNIX_TIMESTAMP(time) <', time() - $expires);

		$this->ci->db->delete('login_attempts');

	}//end clear_login_attempts()

	//--------------------------------------------------------------------

	/**
	 * Get number of attempts to login occurred from given IP-address and/or login
	 *
	 * @param null $login (Optional) The login id to check for (email/username). If no login is passed in, it will only check against the IP Address of the current user.
	 *
	 * @return int An int with the number of attempts.
	 */
	function num_login_attempts($login=NULL)
	{
		$this->ci->db->select('1', FALSE);
		$this->ci->db->where('ip_address', $this->ip_address);
		if (strlen($login) > 0) $this->ci->db->or_where('login', $login);

		$query = $this->ci->db->get('login_attempts');
		return $query->num_rows();

	}//end num_login_attempts()

	//--------------------------------------------------------------------
	// !AUTO-LOGIN
	//--------------------------------------------------------------------

	/**
	 * Attempts to log the user in based on an existing 'autologin' cookie.
	 *
	 * @access private
	 *
	 * @return void
	 */
	private function autologin()
	{
		if ($this->ci->settings_lib->item('auth.allow_remember') == FALSE)
		{
			return;
		}

		$this->ci->load->helper('cookie');

		$cookie = get_cookie('autologin', TRUE);

		if (!$cookie) {	return;	}

		// We have a cookie, so split it into user_id and token
		list($user_id, $test_token) = explode('~', $cookie);

		// Try to pull a match from the database
		$this->ci->db->where( array('user_id' => $user_id, 'token' => $test_token) );
		$query = $this->ci->db->get('user_cookies');

		if ($query->num_rows() == 1)
		{
			// Save logged in status to save on db access later.
			$this->logged_in = TRUE;

			// If a session doesn't exist, we need to refresh our autologin token
			// and get the session started.
			if (!$this->ci->session->userdata('user_id'))
			{
				// Grab the current user info for the session
				$this->ci->load->model('users/User_model', 'user_model', TRUE);
				$user = $this->ci->user_model->select('id, username, email, password_hash, users.role_id')->find($user_id);

				if (!$user) { return; }

				$this->setup_session($user->id, $user->username, $user->password_hash, $user->email, $user->role_id, TRUE, $test_token, $user->username);
			}
		}

		unset($query, $user);

	}//end autologin()

	//--------------------------------------------------------------------


	/**
	 * Create the auto-login entry in the database. This method uses
	 * Charles Miller's thoughts at:
	 * http://fishbowl.pastiche.org/2004/01/19/persistent_login_cookie_best_practice/
	 *
	 * @access private
	 *
	 * @param int    $user_id    An int representing the user_id.
	 * @param string $old_token The previous token that was used to login with.
	 *
	 * @return bool Whether the autologin was created or not.
	 */
	private function create_autologin($user_id=0, $old_token=NULL)
	{
		if (empty($user_id) || $this->ci->settings_lib->item('auth.allow_remember') == FALSE)
		{
			return FALSE;
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
			$this->ci->input->set_cookie('autologin', $user_id .'~'. $token, $this->ci->settings_lib->item('auth.remember_length'));

			return TRUE;
		}
		else
		{
			return FALSE;
		}

	}//end create_autologin()()

	//--------------------------------------------------------------------

	/**
	 * Deletes the autologin cookie for the current user.
	 *
	 * @access private
	 *
	 * @return void
	 */
	private function delete_autologin()
	{
		if ($this->ci->settings_lib->item('auth.allow_remember') == FALSE)
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

	}//end delete_autologin()

	//--------------------------------------------------------------------

	/**
	 * Creates the session information for the current user. Will also create an autologin cookie if required.
	 *
	 * @access private
	 *
	 * @param int $user_id          An int with the user's id
	 * @param string $username      The user's username
	 * @param string $password_hash The user's password hash. Used to create a new, unique user_token.
	 * @param string $email         The user's email address
	 * @param int    $role_id       The user's role_id
	 * @param bool   $remember      A boolean (TRUE/FALSE). Whether to keep the user logged in.
	 * @param string $old_token     User's db token to test against
	 * @param string $user_name     User's made name for displaying options
	 *
	 * @return bool TRUE/FALSE on success/failure.
	 */
	private function setup_session($user_id=0, $username='', $password_hash=NULL, $email='', $role_id=0, $remember=FALSE, $old_token=NULL,$user_name='')
	{

		if (empty($user_id) || (empty($email) && empty($username)))
		{
			return FALSE;
		}

		// What are we using as login identity?
		//Should I use _identity_login() and move bellow code?

		// If "both", defaults to email, unless we display usernames globally
		if (($this->ci->settings_lib->item('auth.login_type') ==  'both'))
		{
			$login = $this->ci->settings_lib->item('auth.use_usernames') ? $username : $email;
		}
		else
		{
			$login = $this->ci->settings_lib->item('auth.login_type') == 'username' ? $username : $email;
		}

		// TODO: consider taking this out of setup_session()
		if ($this->ci->settings_lib->item('auth.use_usernames') == 0  && $this->ci->settings_lib->item('auth.login_type') ==  'username')
		{
			// if we've a username at identity, and don't want made user name, let's have an email nearby.
			$us_custom = $email;
		}
		else
		{
			// For backward compatibility, defaults to username
			$us_custom = $this->ci->settings_lib->item('auth.use_usernames') == 2 ? $user_name : $username;
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
			'auth_custom'	=> $us_custom,
			'user_token'	=> do_hash($user_id . $password_hash),
			'identity'		=> $login,
			'role_id'		=> $role_id,
			'logged_in'		=> TRUE,
		);

		$this->ci->session->set_userdata($data);

		// Should we remember the user?
		if ($remember === TRUE)
		{
			return $this->create_autologin($user_id, $old_token);
		}

		return TRUE;

	}//end setup_session

	//--------------------------------------------------------------------

	/**
	 * Returns the identity to be used upon user registration.
	 *
	 * @access private
	 * @todo Decision to be made with this method.
	 *
	 * @return void
	 */
	private function _identity_login()
	{
		//Should I move indentity conditional code from setup_session() here?
		//Or should conditional code be moved to auth->identity(),
		//  and if Optional TRUE is passed, it would then determine wich identity to store in userdata?

	}//end _identity_login()

	//--------------------------------------------------------------------

}//end Auth

//--------------------------------------------------------------------

if (!function_exists('auth_errors'))
{
	/**
	 * A utility function for showing authentication errors.
	 *
	 * @access public
	 *
	 * @return string A string with a <ul> tag of any auth errors, or an empty string if no errors exist.
	 */
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

			$str .= "</ul>";

			return $str;
		}

		return '';

	}//end auth_errors()
}

//--------------------------------------------------------------------

if (!function_exists('has_permission'))
{
	/**
	 * A convenient shorthand for checking user permissions.
	 *
	 * @access public
	 *
	 * @param string $permission The permission to check for, ie 'Site.Signin.Allow'
	 * @param bool   $override   Whether or not access is granted if this permission doesn't exist in the database
	 *
	 * @return bool TRUE/FALSE
	 */
	function has_permission($permission=NULL, $override = FALSE)
	{
		$ci =& get_instance();

		if (class_exists('Auth'))
		{
			return $ci->auth->has_permission($permission, NULL, $override);
		}

		return FALSE;

	}//end has_permission()
}

//--------------------------------------------------------------------

if (!function_exists('permission_exists'))
{
	/**
	 * Checks to see whether a permission is in the system or not.
	 *
	 * @access public
	 *
	 * @param string $permission The name of the permission to check for. NOT case sensitive.
	 *
	 * @return bool TRUE/FALSE
	 */
	function permission_exists($permission=NULL)
	{
		if (empty($permission))
		{
			return FALSE;
		}

		$ci =& get_instance();

		if (class_exists('Auth'))
		{
			return $ci->auth->permission_exists($permission);
		}

		return FALSE;

	}//end permission_exists()
}

//--------------------------------------------------------------------

if (!function_exists('abbrev_name'))
{
	/**
	 * Retrieves first and last name from given string.
	 *
	 * @access public
	 *
	 * @param string $name Full name
	 *
	 * @return string The First and Last name from given parameter.
	 */
	function abbrev_name($name = '')
	{
		if (is_string($name))
		{
			list( $fname, $lname ) = explode( ' ', $name, 2 );
			if (is_null($lname)) // Meaning only one name was entered...
			{
				$lastname = ' ';
			}
			else
			{
				$lname = explode( ' ', $lname );
				$size = sizeof($lname);
				$lastname = $lname[$size-1]; //
			}

			return trim($fname.' '.$lastname) ;

		}

		/*
			TODO: Consider an optional parameter for picking custom var session.
				Making it auth private, and using auth custom var
		*/

		return $name;

	}//end abbrev_name()
}