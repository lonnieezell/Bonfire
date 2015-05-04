<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * The Auth library provides authentication functions for logging users in/out
 * and managing login attempts as well as authorization functions for restricting
 * access to controllers, content, and actions.
 *
 * Security and ease-of-use are the two primary goals of the Auth system in Bonfire.
 * This library will be updated to reflect the latest security practices while
 * maintaining the simple API.
 *
 * @package Bonfire\Modules\Users\Libraries\Auth
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/roles_and_permissions
 */
class Auth
{
    /** @var string The url to redirect to on successful login. */
    public $login_destination = '';

    /**
     * @var string The date format used for users.last_login, login_attempts.time,
     * and user_cookies.created_on. Passed as the first argument of the PHP date()
     * function when handling any of these values.
     */
    protected $loginDateFormat = 'Y-m-d H:i:s';

    /** @var boolean Allow use of the "Remember Me" checkbox/cookie. */
    private $allowRemember;

    /** @var object A pointer to the CodeIgniter instance. */
    private $ci;

    /** @var string The ip_address of the current user. */
    private $ip_address;

    /** @var array The names of all existing permissions. */
    private $permissions = null;

    /** @var array The permissions by role. */
    private $role_permissions = array();

    /** @var object The logged-in user. */
    private $user;

    //--------------------------------------------------------------------------

    /**
     * Grab a pointer to the CI instance, get the user's IP address, and attempt
     * to automatically log in the user.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ci =& get_instance();

        $this->ip_address = $this->ci->input->ip_address();

        // The users language file is needed for this to work from other modules.
        $this->ci->lang->load('users/users');
        $this->ci->load->model('users/user_model');
        $this->ci->load->library('session');

        // Try to log the user in from session/cookie data.
        $this->autologin();

        log_message('debug', 'Auth class initialized.');
    }

    /**
     * Check the session for the required info, then verify it against the database.
     *
     * @return boolean True if the user is logged in, else false.
     */
    public function is_logged_in()
    {
        return (bool) $this->user();
    }

    /**
     * Attempt to log the user in.
     *
     * @param string  $login    The user's login credentials (email/username).
     * @param string  $password The user's password.
     * @param boolean $remember Whether the user should be remembered in the system.
     *
     * @return boolean True if the user has authenticated, else false.
     */
    public function login($login, $password, $remember = false)
    {
        if (empty($login) || empty($password)) {
            Template::set_message(
                sprintf(
                    lang('us_fields_required'),
                    $this->ci->settings_lib->item('auth.login_type') == 'both' ? lang('bf_login_type_both') : lang('bf_' . $this->ci->settings_lib->item('auth.login_type'))
                ),
                'error'
            );
            return false;
        }

        // Grab the user from the db.
        $selects = array(
            'id',
            'email',
            'username',
            'users.role_id',
            'users.deleted',
            'users.active',
            'banned',
            'ban_message',
            'password_hash',
            'force_password_reset'
        );

        if ($this->ci->settings_lib->item('auth.do_login_redirect')) {
            $selects[] = 'login_destination';
        }

        $this->ci->user_model->select($selects);
        if ($this->ci->settings_lib->item('auth.login_type') == 'both') {
            $user = $this->ci->user_model->find_by(
                array('username' => $login, 'email' => $login),
                null,
                'or'
            );
        } else {
            $user = $this->ci->user_model->find_by(
                $this->ci->settings_lib->item('auth.login_type'),
                $login
            );
        }

        // Check whether the username, email, or password doesn't exist.
        if ($user == false) {
            Template::set_message(lang('us_bad_email_pass'), 'error');
            return false;
        }

        // Check whether the account has been activated.
        if ($user->active == 0) {
            $activation_type = $this->ci->settings_lib->item('auth.user_activation_method');
            if ($activation_type > 0) {
                if ($activation_type == 1) {
                    Template::set_message(lang('us_account_not_active'), 'error');
                } elseif ($activation_type == 2) {
                    Template::set_message(lang('us_admin_approval_pending'), 'error');
                }

                return false;
            }
        }

        // Check whether the account has been soft deleted. The >= 1 check ensures
        // this will still work if the deleted field is a UNIX timestamp.
        if ($user->deleted >= 1) {
            Template::set_message(
                sprintf(
                    lang('us_account_deleted'),
                    html_escape(settings_item("site.system_email"))
                ),
                'error'
            );
            return false;
        }

        // Try password
        if (! $this->check_password($password, $user->password_hash)) {
            // Bad password
            Template::set_message(lang('us_bad_email_pass'), 'error');
                $this->increase_login_attempts($login);

            return false;
        }

        // Check whether the account has been banned.
        if ($user->banned) {
            $this->increase_login_attempts($login);
            Template::set_message(
                $user->ban_message ? $user->ban_message : lang('us_banned_msg'),
                'error'
            );
            return false;
        }

        // Check whether the user needs to reset their password.
        if ($user->force_password_reset == 1) {
            Template::set_message(lang('us_forced_password_reset_note'), 'warning');

            // Generate a reset hash to pass the reset_password checks...
            $this->ci->load->helper('string');
            $hash = sha1(random_string('alnum', 40) . $user->email);

            // Save the hash to the db so it can be confirmed later.
            $this->ci->user_model->update_where(
                'id',
                $user->id,
                array('reset_hash' => $hash, 'reset_by' => strtotime("+24 hours"))
            );

            $this->ci->session->set_userdata('pass_check', $hash);
            $this->ci->session->set_userdata('email', $user->email);

            // Redirect the user to the reset password page.
            redirect('/users/reset_password');
        }

        $this->clear_login_attempts($login);

        // The login was successfully validated, so setup the session
        $this->setupSession(
            $user->id,
            $user->username,
            $user->password_hash,
            $user->email,
            $user->role_id,
            $remember,
            '',
            $user->username
        );

            // Save the login info
        $this->ci->user_model->update(
            $user->id,
            array(
                'last_login' => $this->getLoginTimestamp(),
                'last_ip'               => $this->ip_address,
            )
        );

        // Clear the cached result of user() (and is_logged_in(), user_id(), etc.).
            // Doesn't fix `$this->current_user` in controller (for this page load)...
            unset($this->user);

        // Can't pass the array directly to the trigger, must use a variable.
        $trigger_data = array('user_id' => $user->id, 'role_id' => $user->role_id);
        Events::trigger('after_login', $trigger_data);

        // Save the redirect location
        $this->login_destination = empty($user->login_destination) ? '' : $user->login_destination;

        return true;
    }

    /**
     * Destroy the autologin information and the current session.
     *
     * @return void
     */
    public function logout()
    {
        // Can't pass the array directly to the trigger, must use a variable.
        $data = array(
            'user_id'   => $this->user_id(),
            'role_id' => $this->role_id(),
        );
        Events::trigger('before_logout', $data);

        // Destroy the autologin information
        $this->deleteAutologin();

        // Destroy the session
        $this->ci->session->sess_destroy();
    }

    /**
     * Check the session for the required info, then verify it against the database.
     *
     * @return object/boolean Returns the user info or false.
     */
    public function user()
    {
        // If the user has already been cached, return it.
        if (isset($this->user)) {
            return $this->user;
        }

        $this->user = false;

        // Is the required session data available?
        if (! $this->ci->session->userdata('identity')
            || ! $this->ci->session->userdata('user_id')
        ) {
            return false;
        }

        // Grab the user account.
            $user = $this->ci->user_model->find($this->ci->session->userdata('user_id'));
        if ($user === false) {
            return false;
        }

        // Ensure user_token is still equivalent to SHA1 of the user_id and password_hash.
        if (sha1($this->ci->session->userdata('user_id') . $user->password_hash)
            !== $this->ci->session->userdata('user_token')
        ) {
            return false;
        }

        $this->user = $user;
            $this->user->id = (int) $this->user->id;
            $this->user->role_id = (int) $this->user->role_id;

        return $this->user;
    }

    //--------------------------------------------------------------------------
    // Permissions
    //--------------------------------------------------------------------------

    /**
     * Verify that the user is logged in and has the appropriate permissions.
     *
     * @param string  $permission The permission to check for, e.g. 'Site.Signin.Allow'.
     * @param integer $role_id    The id of the role to check the permission against.
     * If role_id is not passed into the method, it assumes the current user's role_id.
     * @param boolean $override   Whether access is granted if this permission doesn't
     * exist in the database.
     *
     * @return boolean True if the user/role has permission or the permission was
     * not found in the database and $override is true, else false.
     */
    public function has_permission($permission, $role_id = null, $override = false)
    {
        // Move permission to lowercase for easier checking.
        $permission = strtolower($permission);

        // If no role is provided, assume it's for the current logged in user.
        if (empty($role_id)) {
            $role_id = $this->role_id();
        }

        $permissions = $this->loadPermissions();

        // Does the user/role have the permission?
        if (isset($permissions[$permission])) {
            $role_permissions = $this->loadRolePermissions($role_id);
            $permission_id    = $permissions[$permission];

            if (isset($role_permissions[$role_id][$permission_id])) {
                return true;
            }
        } elseif ($override) {
            return true;
        }

        return false;
    }

    /**
     * Check whether a permission is in the system.
     *
     * @param string $permission The case-insensitive name of the permission to check.
     *
     * @return boolean True if the permission was found, else false.
     */
    public function permission_exists($permission)
    {
        // Move permission to lowercase for easier checking.
        $permission  = strtolower($permission);
        $permissions = $this->loadPermissions();

        return isset($permissions[$permission]);
    }

    /**
     * Check whether a user is logged in (and, optionally of the correct role) and,
     * if not, send them to the login screen.
     *
     * If no permission is checked, will simply verify that the user is logged in.
     * If a permission is passed in to the first parameter, it will check the user's
     * role and verify that role has the appropriate permission.
     *
     * @param string $permission (Optional) The permission to check for.
     * @param string $uri        (Optional) The redirect URI if the user does not
     * have the correct permission.
     *
     * @return boolean True if the user has the appropriate access permissions.
     * Redirect to the previous page if the user doesn't have permissions.
     * Redirect to LOGIN_AREA page if the user is not logged in.
     */
    public function restrict($permission = null, $uri = null)
    {
        // If user isn't logged in, redirect to the login page.
        if ($this->is_logged_in() === false) {
            $this->ci->load->library('Template');
            Template::set_message($this->ci->lang->line('us_must_login'), 'error');
            Template::redirect(LOGIN_URL);
        }

        // Check whether the user has the proper permissions.
        if (empty($permission) || $this->has_permission($permission)) {
            return true;
        }

        // If the user is logged in, but does not have permission...

        // If $uri is not set, get the previous page from the session.
        if (! $uri) {
                $uri = $this->ci->session->userdata('previous_page');

            // If previous page and current page are the same, but the user no longer
            // has permission, redirect to site URL to prevent an infinite loop.
            if ($uri == current_url()) {
                    $uri = site_url();
            }
        }

        // Inform the user of the lack of permission and redirect.
        $this->ci->load->library('Template');
        Template::set_message(lang('us_no_permission'), 'attention');
            Template::redirect($uri);
    }

    //--------------------------------------------------------------------------
    // Roles
    //--------------------------------------------------------------------------

    /**
     * Retrieves the role_id from the current session.
     *
     * @return integer/boolean The user's role_id or false.
     */
    public function role_id()
    {
        if (! $this->is_logged_in()) {
            return false;
        }
        return $this->user()->role_id;
    }

    /**
     * Retrieve the role_name for the requested role.
     *
     * @param integer $role_id The role ID for which the name will be retrieved.
     *
     * @return string A string with the name of the matched role.
     */
    public function role_name_by_id($role_id)
    {
        if (! is_numeric($role_id)) {
            return '';
        }

        // Retrieve the roles.
        $roles = $this->loadRoles();

        // Try to return the role name.
        if (isset($roles[$role_id])) {
            return $roles[$role_id];
        }

        return '';
    }

    /**
     * Retrieve the list of roles from the library's internal list or the database.
     *
     * @return array The list of role_id and role_name values.
     */
    protected function loadRoles()
    {
        // If the role names are already stored, use them.
        if (! empty($this->role_names)) {
            return $this->role_names;
        }

        // Retrieve the roles from the database.
        if (! class_exists('role_model', false)) {
            $this->ci->load->model('roles/role_model');
        }
        $results = $this->ci->role_model->select('role_id, role_name')
                                        ->find_all();

        // Store the role names.
        $this->role_names = array();
        foreach ($results as $role) {
            $this->role_names[$role->role_id] = $role->role_name;
        }
        return $this->role_names;
    }

    //--------------------------------------------------------------------------
    // Password Methods
    //--------------------------------------------------------------------------

    /**
     * Check the supplied password against the supplied hash.
     *
     * @param string $password The password to check.
     * @param string $hash     The hash.
     *
     * @return boolean True if the password and hash match, else false.
     */
    public function check_password($password, $hash)
    {
        // Load the password hash library
        $hasher = $this->getPasswordHasher(-1);

        // Try password
        return $hasher->CheckPassword($password, $hash);
    }

    /**
     * Hash a password.
     *
     * @param string $pass        The password to hash
     * @param integer $iterations The number of iterations used in hashing the password.
     *
     * @return array An associative array containing the hashed password and number
     * of iterations.
     */
    public function hash_password($pass, $iterations = 0)
    {
        // The shortest valid hash phpass can currently return is 20 characters,
        // which would only happen with CRYPT_EXT_DES.
        $min_hash_len = 20;

        // If $iterations wasn't passed, get it from the settings.
        if (empty($iterations)
            || ! is_numeric($iterations)
            || $iterations <= 0
        ) {
            $iterations = $this->ci->settings_lib->item('password_iterations');
        }

        // Load the password hash library and hash the password.
        $hasher   = $this->getPasswordHasher($iterations);
        $password = $hasher->HashPassword($pass);

        unset($hasher);

        // If the password is shorter than the minimum hash length, something failed.
        if (strlen($password) < $min_hash_len) {
            return false;
        }

        return array('hash' => $password, 'iterations' => $iterations);
    }

    /**
     * Loads the PasswordHash library as needed and returns a new instance.
     *
     * Note: Moving the loading of the 'password_iterations' setting into this method
     * was considered. Since the $iterations value is only really needed for hashing
     * a password, and the 'password_iterations' value is only used when the $iterations
     * value passed to the hash_password() method is not a positive integer, it
     * made more sense to leave it there than to add some indicator to this method
     * that the calling method didn't need to retrieve the 'password_iterations'
     * value.
     *
     * @param integer $iterations The number of iterations to be used in hashing
     * passwords.
     *
     * @return PasswordHash The password hasher.
     */
    protected function getPasswordHasher($iterations)
    {
        if (! class_exists('PasswordHash', false)) {
            require(dirname(__FILE__) . '/../libraries/PasswordHash.php');
        }

        return new PasswordHash((int) $iterations, false);
    }

    //--------------------------------------------------------------------
    // !LOGIN ATTEMPTS
    //--------------------------------------------------------------------

    /**
     * Get number of login attempts from the given IP-address and/or login.
     *
     * @param string $login (Optional) The login id to check for (email/username).
     * If no login is passed in, it will only check against the IP Address of the
     * current user.
     *
     * @return integer The number of attempts.
     */
    public function num_login_attempts($login = null)
    {
        $this->ci->db->select('1', false)
                     ->where('ip_address', $this->ip_address);

        if (strlen($login) > 0) {
            $this->ci->db->or_where('login', $login);
        }

        $query = $this->ci->db->get('login_attempts');

        return $query->num_rows();
    }

    /**
     * Clear all login attempts for this user, as well as expired logins.
     *
     * @param string  $login   The login credentials (typically email).
     * @param integer $expires The expiration time (in seconds). Attempts older
     * than this value will be deleted.
     *
     * @return void
     */
    protected function clear_login_attempts($login, $expires = 86400)
    {
        $this->ci->db->where(array('ip_address' => $this->ip_address, 'login' => $login))
                     ->or_where('time <', $this->getLoginTimestamp(time() - $expires))
                     ->delete('login_attempts');
    }

    /**
     * Record a login attempt in the database.
     *
     * @param string $login The login id used (typically email or username).
     *
     * @return void
     */
    protected function increase_login_attempts($login)
    {
        $this->ci->db->insert(
            'login_attempts',
            array(
                'ip_address' => $this->ip_address,
                'login'      => $login,
                'time'       => $this->getLoginTimestamp(),
            )
        );
    }

    //--------------------------------------------------------------------------
    // !UTILITY METHODS
    //--------------------------------------------------------------------------

    /**
     * Retrieve the logged identity from the current session. Built from the user's
     * submitted login.
     *
     * @return string/boolean The identity used to login, or false.
     */
    public function identity()
    {
        if (! $this->is_logged_in()) {
            return false;
        }
        return $this->ci->session->userdata('identity');
    }

    /**
     * Retrieve the user_id from the current session.
     *
     * @return integer/boolean The user's ID or false.
     */
    public function user_id()
    {
        if (! $this->is_logged_in()) {
            return false;
        }
        return $this->user()->id;
    }

    /**
     * Gets a timestamp using $this->loginDateFormat and the system's configured
     * 'time_reference'.
     *
     * @param integer $time A UNIX timestamp.
     *
     * @return string A timestamp formatted according to $this->loginDateFormat.
     */
    protected function getLoginTimestamp($time = null)
    {
        if (empty($time)) {
            $time = time();
        }
        return strtolower($this->ci->config->item('time_reference')) == 'gmt' ?
            gmdate($this->loginDateFormat, $time) : date($this->loginDateFormat, $time);
    }

    //--------------------------------------------------------------------------
    // Private Methods
    //--------------------------------------------------------------------------

    /**
     * Create the session information for the current user and create an
     * autologin cookie if required.
     *
     * @param integer $user_id       An int with the user's id.
     * @param string  $username      The user's username.
     * @param string  $password_hash The user's password hash. Used to create a
     * new, unique user_token.
     * @param string  $email         The user's email address.
     * @param integer $role_id       The user's role_id.
     * @param boolean $remember      Whether to keep the user logged in.
     * @param string  $old_token     User's db token to test against.
     * @param string  $user_name     User's made name for displaying options.
     *
     * @return boolean True/false on success/failure.
     */
    private function setupSession(
        $user_id,
        $username,
        $password_hash,
        $email,
        $role_id,
        $remember = false,
        $old_token = null,
        $user_name = ''
    ) {
        // What are we using as login identity?

        // If "both", defaults to email, unless we display usernames globally
        if ($this->ci->settings_lib->item('auth.login_type') == 'both') {
            $login = $this->ci->settings_lib->item('auth.use_usernames') ? $username : $email;
        } else {
            $login = $this->ci->settings_lib->item('auth.login_type') == 'username' ? $username : $email;
        }

        // @todo consider taking this out of setupSession()
        if ($this->ci->settings_lib->item('auth.use_usernames') == 0
            && $this->ci->settings_lib->item('auth.login_type') == 'username'
        ) {
            // If we've a username at identity, and don't want made user name, let's have an email nearby.
            $us_custom = $email;
        } else {
            // For backward compatibility, default to username.
            $us_custom = $this->ci->settings_lib->item('auth.use_usernames') == 2 ? $user_name : $username;
        }

        // Save the user's session info

        $this->ci->session->set_userdata(
            array(
                'user_id'     => $user_id,
                'auth_custom' => $us_custom,
                'user_token'  => sha1($user_id . $password_hash),
                'identity'    => $login,
                'role_id'     => $role_id,
                'logged_in'   => true,
            )
        );

        // Should we remember the user?
        if ($remember === true) {
            return $this->createAutologin($user_id, $old_token);
        }

        return true;
    }

    //--------------------------------------------------------------------------
    // Permissions
    //--------------------------------------------------------------------------

    /**
     * Load the permission names from the database.
     *
     * @return array Permissions: key - lowercase name, value - permission ID.
     */
    private function loadPermissions()
    {
        if (! empty($this->permissions)) {
            return $this->permissions;
        }

        if (! class_exists('permission_model', false)) {
            $this->ci->load->model('permissions/permission_model');
        }

        $this->permissions = array();
        $perms = $this->ci->permission_model->find_all();
        if (! empty($perms)) {
            foreach ($perms as $perm) {
                $this->permissions[strtolower($perm->name)] = $perm->permission_id;
            }
        }

        return $this->permissions;
    }

    /**
     * Load the role permissions from the database.
     *
     * @param integer $role_id The role id for which permissions are loaded. Uses
     * the current user's role ID if none is provided.
     *
     * @return void
     */
    private function loadRolePermissions($role_id = null)
    {
        if (is_null($role_id)) {
            $role_id = $this->role_id();
        }

        if (! empty($this->role_permissions[$role_id])) {
            return $this->role_permissions;
        }

        if (! class_exists('role_permission_model', false)) {
            $this->ci->load->model('roles/role_permission_model');
        }

        $this->role_permissions[$role_id] = array();
        $role_perms = $this->ci->role_permission_model->find_for_role($role_id);
        if (! is_array($role_perms)) {
            return $this->role_permissions;
        }

        foreach ($role_perms as $permission) {
            $this->role_permissions[$role_id][$permission->permission_id] = true;
        }

        return $this->role_permissions;
    }

    //--------------------------------------------------------------------------
    // !AUTO-LOGIN
    //--------------------------------------------------------------------------

    /**
     * Attempt to log the user in based on an existing 'autologin' cookie.
     *
     * @return void
     */
    private function autologin()
    {
        $this->ci->load->library('settings/settings_lib');
        if (! $this->allowRemember()) {
            return;
        }

        $this->ci->load->helper('cookie');
        $cookie = get_cookie('autologin', true);
        if (! $cookie) {
            return;
        }

        // A cookie was retrieved, so split it into user_id and token.
        list($user_id, $test_token) = explode('~', $cookie);

        // Try to pull a match from the database
        $this->ci->db->where(array('user_id' => $user_id, 'token' => $test_token));

        $query = $this->ci->db->get('user_cookies');
        if ($query->num_rows() != 1) {
            return;
        }
        // Save logged in status to reduce db access.
        $this->logged_in = true;

        if ($this->ci->session->userdata('user_id')) {
            return;
        }

        // If a session doesn't exist, refresh the autologin token and start the
        // session.

        // Grab the current user info for the session.
        $this->ci->load->model('users/user_model');
        $user = $this->ci->user_model->select(array('id', 'username', 'email', 'password_hash', 'users.role_id'))
                                     ->find($user_id);

        if (! $user) {
            return;
        }

        $this->setupSession(
            $user->id,
            $user->username,
            $user->password_hash,
            $user->email,
            $user->role_id,
            true,
            $test_token,
            $user->username
        );
    }

    /**
     * Create the auto-login entry in the database. This method uses Charles Miller's
     * thoughts at:
     * http://fishbowl.pastiche.org/2004/01/19/persistent_login_cookie_best_practice/
     *
     * @param integer $user_id   An int representing the user_id.
     * @param string  $old_token The previous token that was used to login with.
     *
     * @return boolean Whether the autologin was created or not.
     */
    private function createAutologin($user_id, $old_token = null)
    {
        if (! $this->allowRemember()) {
            return false;
        }

        // Generate a random string for the token.
        if (! function_exists('random_string')) {
            $this->ci->load->helper('string');
        }
        $token = random_string('alnum', 128);

        // If an old token was not provided, create a new one.
        if (empty($old_token)) {
            // Create a new token
            $this->ci->db->insert(
                'user_cookies',
                array(
                'user_id'       => $user_id,
                'token'         => $token,
                    'created_on' => $this->getLoginTimestamp(),
                )
            );
        } else {
            // Refresh the token.
            $this->ci->db->where('user_id', $user_id)
                         ->where('token', $old_token)
                         ->set('token', $token)
                         ->set('created_on', $this->getLoginTimestamp())
                         ->update('user_cookies');
        }

        if ($this->ci->db->affected_rows()) {
            // Create the autologin cookie
            $this->ci->input->set_cookie(
                'autologin',
                $user_id .'~'. $token,
                $this->ci->settings_lib->item('auth.remember_length')
            );

            return true;
        }

        return false;
    }

    /**
     * Delete the autologin cookie for the current user.
     *
     * @return void
     */
    private function deleteAutologin()
    {
        if (! $this->allowRemember()) {
            return;
        }

        // Grab the cookie to determine which row in the table to delete.
        if (! function_exists('get_cookie')) {
            $this->ci->load->helper('cookie');
        }
        $cookie = get_cookie('autologin');
        if ($cookie) {
            list($user_id, $token) = explode('~', $cookie);

            // Now delete the cookie.
            delete_cookie('autologin');

            // Clean up the database
            $this->ci->db->where('user_id', $user_id)
                         ->where('token', $token)
                         ->delete('user_cookies');
        }

        // Perform a clean up of any autologins older than 2 months.
        $this->ci->db->where('created_on <', $this->getLoginTimestamp(strtotime('2 months ago')));

        $this->ci->db->delete('user_cookies');
    }

    //--------------------------------------------------------------------------
    // Utility Methods
    //--------------------------------------------------------------------------

    /**
     * Retrieve the 'auth.allow_remember' setting from the settings library and
     * store it for the library's internal use.
     *
     * @return boolean True if the "Remember Me" checkbox is permitted by the site's
     * settings to be displayed on the login form, else false.
     */
    private function allowRemember()
    {
        if (isset($this->allowRemember)) {
            return $this->allowRemember;
        }

        $this->allowRemember = (bool) $this->ci->settings_lib->item('auth.allow_remember');
        return $this->allowRemember;
    }
}

//------------------------------------------------------------------------------
// Helper Functions
//------------------------------------------------------------------------------

if (! function_exists('has_permission')) {
    /**
     * A convenient shorthand for checking user permissions.
     *
     * @param string  $permission The permission to check for, ie 'Site.Signin.Allow'.
     * @param boolean $override   Whether access is granted if this permission doesn't
     * exist in the database.
     *
     * @return boolean True if the user has the permission or $override is true
     * and the permission wasn't found in the system, else false.
     */
    function has_permission($permission, $override = false)
    {
        return get_instance()->auth->has_permission($permission, null, $override);
    }
}

if (! function_exists('permission_exists')) {
    /**
     * Check to see whether a permission is in the system.
     *
     * @param string $permission Case-insensitive permission to check.
     *
     * @return boolean True if the permission exists, else false.
     */
    function permission_exists($permission)
    {
        return get_instance()->auth->permission_exists($permission);
    }
}

if (! function_exists('abbrev_name')) {
    /**
     * Retrieve first and last name from given string.
     *
     * @deprecated since 0.7.2. No replacement is currently planned.
     *
     * @param string $name Full name.
     *
     * @return string The First and Last name from given parameter.
     */
    function abbrev_name($name)
    {
        if (is_string($name)) {
            list($fname, $lname) = explode(' ', $name, 2);

            if (is_null($lname)) { // Meaning only one name was entered...
                $lastname = ' ';
            } else {
                $lname = explode(' ', $lname);
                $size = sizeof($lname);
                $lastname = $lname[$size-1]; //
            }

            return trim("{$fname} {$lastname}");
        }

        // @todo Consider an optional parameter for picking custom var session.
        // Making it auth private, and using auth custom var

        return $name;
    }
}
