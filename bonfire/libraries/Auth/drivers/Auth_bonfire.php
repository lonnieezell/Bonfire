<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Auth Library
 *
 * Provides authentication functions for logging users
 * in/out and managing login attempts.
 *
 * Security and ease-of-use are the two primary goals of the Auth system in Bonfire.
 * This lib will be constantly updated to reflect the latest security practices that
 * we learn about, while maintaining the simple API.
 *
 * @package    Bonfire
 * @subpackage Application
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Auth_bonfire extends CI_Driver
{
    /**
     * The url to redirect to on successful login.
     *
     * @access public
     *
     * @var string
     */
    public $login_destination = '';

    /**
     * Stores the logged in user after the first test to improve performance.
     *
     * @access private
     *
     * @var object
     */
    private $user;

    /**
     * Stores the ip_address of the current user for performance reasons.
     *
     * @access private
     *
     * @var string
     */
    private $ip_address;

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

        $this->ci->load->library('session');

        log_message('debug', 'Auth Bonfire initialized.');

    }//end __construct()

    //--------------------------------------------------------------------

    /**
     * Attempt to log the user in.
     *
     * @access public
     *
     * @param string $login     The user's login credentials
     * @param bool   $remember  Whether the user should be remembered in the system.
     *
     * @return User Object or NULL
     */
    public function login($credentials, $remember)
    {
        if (empty($credentials['login']) || empty($credentials['password']))
        {
            $error = $this->ci->settings_lib->item('auth.login_type') == 'both' ? lang('bf_username') .'/'. lang('bf_email') : ucfirst($this->ci->settings_lib->item('auth.login_type'));
            Template::set_message(sprintf(lang('us_fields_required'), $error), 'error');
            return FALSE;
        }

        $this->ci->load->model('users/User_model', 'user_model');

        // Grab the user from the db
        $selects = 'id, email, username, users.role_id, users.deleted, users.active, banned, ban_message, password_hash, password_iterations, force_password_reset';

        if ($this->ci->settings_lib->item('auth.do_login_redirect'))
        {
            $selects .= ', login_destination';
        }

        if ($this->ci->settings_lib->item('auth.login_type') == 'both')
        {
            $user = $this->ci->user_model->select($selects)->find_by(array('username' => $credentials['login'], 'email' => $credentials['login']), null, 'or');
        }
        else
        {
            $user = $this->ci->user_model->select($selects)->find_by($this->ci->settings_lib->item('auth.login_type'), $credentials['login']);
        }

        // check to see if a value of FALSE came back, meaning that the username or email or password doesn't exist.
        if ($user == FALSE)
        {
            Template::set_message(lang('us_bad_email_pass'), 'error');
            return FALSE;
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
            Template::set_message(sprintf(lang('us_account_deleted'), html_escape(settings_item("site.system_email"))), 'error');
            return FALSE;
        }

        // Try password
        if ($this->check_password($credentials['password'], $user->password_hash))
        {
            // check if the account has been banned.
            if ($user->banned)
            {
                $this->increase_login_attempts($login);
                Template::set_message($user->ban_message ? $user->ban_message : lang('us_banned_msg'), 'error');
                return FALSE;
            }

            // Check if the user needs to reset their password
            if ($user->force_password_reset == 1)
            {
                Template::set_message(lang('us_forced_password_reset_note'), 'warning');

                // Need to generate a reset hash to pass the reset_password checks...
                $this->ci->load->helpers(array('string', 'security'));

                $pass_code = random_string('alnum', 40);

                $hash = do_hash($pass_code . $user->email);

                // Save the hash to the db so we can confirm it later.
                $this->ci->user_model->update_where('id', $user->id, array('reset_hash' => $hash, 'reset_by' => strtotime("+24 hours") ));

                $this->ci->session->set_userdata('pass_check', $hash);
                $this->ci->session->set_userdata('email', $user->email);
                redirect('/users/reset_password');
            }

            $this->clear_login_attempts($credentials['login']);

            // We've successfully validated the login, so setup the session
            $this->setup_session($user->id, $user->username, $user->password_hash, $user->email, $user->role_id, $remember,'', $user->username);

            // Clear the cached result of user() (and hence is_logged_in(), user_id() etc).
            // Doesn't fix `$this->current_user` in controller (for this page load)...
            unset($this->user);

            // Save our redirect location
            $this->login_destination = isset($user->login_destination) && !empty($user->login_destination) ? $user->login_destination : '';

            return $user;
        }

        // Bad password
        else
        {
            Template::set_message(lang('us_bad_email_pass'), 'error');
            $this->increase_login_attempts($login);
        }

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
        // Destroy the autologin information
        $this->delete_autologin();
    }//end logout()

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // !UTILITY METHODS
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
        if ( ! $this->is_logged_in())
        {
            return FALSE;
        }

        return $this->ci->session->userdata('identity');

    }//end identity()

    //--------------------------------------------------------------------

    /*
     * Passwords
     */

    /**
     * Hash a password
     *
     * @param String $pass      The password to hash
     * @param Int $iterations   The number of iterations used in hashing the password
     *
     * @return Array            An associative array containing the hashed password and number of iterations
     */
    public function hash_password($pass, $iterations=0)
    {
        // The shortest valid hash phpass can currently return is 20 characters,
        // which would only happen with CRYPT_EXT_DES
        $min_hash_len = 20;

        if (empty($iterations) || ! is_numeric($iterations) || $iterations <= 0)
        {
            $iterations = $this->ci->settings_lib->item('password_iterations');
        }

        // Load the password hash library
        if ( ! class_exists('PasswordHash'))
        {
            $this->ci->load->library('auth/PasswordHash',
                array('iterations' => $iterations,
                      'portable' => false));
        }

        $password = $this->ci->passwordhash->HashPassword($pass);

        unset($hasher);

        if (strlen($password) < $min_hash_len)
        {
            return false;
        }

        return array('hash' => $password, 'iterations' => $iterations);

    }

    //--------------------------------------------------------------------

    /**
     * Check the supplied password against the supplied hash
     *
     * @param String $password The password to check
     * @param String $hash     The hash
     *
     * @return Bool    true if the password and hash match, else false
     */
    public function check_password($password, $hash)
    {
        // Load the password hash library
        if ( ! class_exists('PasswordHash'))
        {
            $this->ci->load->library('auth/PasswordHash',
                array('iterations' => -1,
                      'portable' => false));
        }

        // Try password
        $return = $this->ci->passwordhash->CheckPassword($password, $hash);

        unset($hasher);

        return $return;

    }

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
    protected function increase_login_attempts($login)
    {
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
    protected function clear_login_attempts($login, $expires = 86400)
    {
        $this->ci->db->where(array('ip_address' => $this->ip_address, 'login' => $login));

        // Purge obsolete login attempts
        $this->ci->db->or_where('time <', date('Y-m-d H:i:s', time() - $expires));

        $this->ci->db->delete('login_attempts');

    }//end clear_login_attempts()

    //--------------------------------------------------------------------

    /**
     * Get number of attempts to login occurred from given IP-address and/or login
     *
     * @param string $login (Optional) The login id to check for (email/username). If no login is passed in, it will only check against the IP Address of the current user.
     *
     * @return int An int with the number of attempts.
     */
    function num_login_attempts($login=NULL)
    {
        $this->ci->db->select('1', FALSE);
        $this->ci->db->where('ip_address', $this->ip_address);
        if (strlen($login) > 0)
        {
            $this->ci->db->or_where('login', $login);
        }

        $query = $this->ci->db->get('login_attempts');

        return $query->num_rows();

    }//end num_login_attempts()

    //--------------------------------------------------------------------
    // !AUTO-LOGIN
    //--------------------------------------------------------------------

    /**
     * Attempts to log the user in based on an existing 'autologin' cookie.
     *
     * @access protected
     *
     * @return void
     */
    public function autologin()
    {
        $this->ci->load->library('settings/settings_lib');

        if ($this->ci->settings_lib->item('auth.allow_remember') == FALSE)
        {
            return;
        }

        $this->ci->load->helper('cookie');

        $cookie = get_cookie('autologin', TRUE);

        if ( ! $cookie)
        {
            return;
        }

        // We have a cookie, so split it into user_id and token
        list($user_id, $test_token) = explode('~', $cookie);

        // Try to pull a match from the database
        $this->ci->db->where( array('user_id' => $user_id, 'token' => $test_token) );
        $query = $this->ci->db->get('user_cookies');

        if ($query->num_rows() == 1)
        {
            // Grab the current user info for the session
            $this->ci->load->model('users/User_model', 'user_model');
            $user = $this->ci->user_model->select('id, username, email, password_hash, users.role_id')->find($user_id);

            // If a session doesn't exist, we need to refresh our autologin token
            // and get the session started.
            if ( ! $this->ci->session->userdata('user_id'))
            {
                if ( ! $user)
                {
                    return NULL;
                }

                $this->setup_session($user->id, $user->username, $user->password_hash, $user->email, $user->role_id, TRUE, $test_token, $user->username);
            }

            return $user;
        }

    }//end autologin()

    //--------------------------------------------------------------------


    /**
     * Create the auto-login entry in the database. This method uses
     * Charles Miller's thoughts at:
     * http://fishbowl.pastiche.org/2004/01/19/persistent_login_cookie_best_practice/
     *
     * @access protected
     *
     * @param int    $user_id    An int representing the user_id.
     * @param string $old_token The previous token that was used to login with.
     *
     * @return bool Whether the autologin was created or not.
     */
    protected function create_autologin($user_id, $old_token=NULL)
    {
        if ($this->ci->settings_lib->item('auth.allow_remember') == FALSE)
        {
            return FALSE;
        }

        // load random_string()
        $this->ci->load->helper('string');

        // Generate a random string for our token
        $token = random_string('alnum', 128);

        // If an old_token is presented, we're refreshing the autologin information
        // otherwise we're creating a new one.
        if (empty($old_token))
        {
            // Create a new token
            $data = array(
                'user_id'       => $user_id,
                'token'         => $token,
                'created_on'    => date('Y-m-d H:i:s')
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
     * @access protected
     *
     * @return void
     */
    protected function delete_autologin()
    {
        if ($this->ci->settings_lib->item('auth.allow_remember') == FALSE)
        {
            return;
        }

        // First things first.. grab the cookie so we know what row
        // in the user_cookies table to delete.
        $this->ci->load->helper('cookie');

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
     * @access protected
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
    protected function setup_session($user_id, $username, $password_hash, $email, $role_id, $remember=FALSE, $old_token=NULL,$user_name='')
    {
        // What are we using as login identity?
        // Should I use _identity_login() and move below code?

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

        // load do_hash()
        $this->ci->load->helper('security');

        $data = array(
            'user_id'       => $user_id,
            'auth_custom'   => $us_custom,
            'user_token'    => do_hash($user_id . $password_hash),
            'identity'      => $login,
            'role_id'       => $role_id,
            'logged_in'     => TRUE,
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
     * @access protected
     * @todo Decision to be made with this method.
     *
     * @return void
     */
    protected function _identity_login()
    {
        //Should I move indentity conditional code from setup_session() here?
        //Or should conditional code be moved to auth->identity(),
        //  and if Optional TRUE is passed, it would then determine wich identity to store in userdata?

    }//end _identity_login()

    //--------------------------------------------------------------------

}//end Auth


//--------------------------------------------------------------------

if ( ! function_exists('has_permission'))
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
    function has_permission($permission, $override = FALSE)
    {
        $ci =& get_instance();

        return $ci->auth->has_permission($permission, NULL, $override);

    }//end has_permission()
}

//--------------------------------------------------------------------

if ( ! function_exists('permission_exists'))
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
    function permission_exists($permission)
    {
        $ci =& get_instance();

        return $ci->auth->permission_exists($permission);

    }//end permission_exists()
}

//--------------------------------------------------------------------

if ( ! function_exists('abbrev_name'))
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
    function abbrev_name($name)
    {
        if (is_string($name))
        {
            list($fname, $lname) = explode(' ', $name, 2);

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

        // TODO: Consider an optional parameter for picking custom var session.
        // Making it auth private, and using auth custom var

        return $name;

    }//end abbrev_name()
}