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
 *
 */
class Auth extends CI_Driver_Library {

    /**
     * The currently in use driver.
     * @var string
     */
    protected $_driver;

    /**
     * Currently enabled drivers.
     * @var string
     */
    protected $valid_drivers = array();

    /**
     * Stores the name of all existing permissions
     *
     * @var array
     */
    private $permissions = NULL;

    /**
     * Stores permissions by role so we don't have to scour the database more than once.
     *
     * @var array
     */
    private $role_permissions = array();

    /**
     * Pointer to the CodeIgniter instance.
     */
    protected $ci;

    //--------------------------------------------------------------------

    public function __construct()
    {
        $this->ci =& get_instance();

        // Set the drivers based on what's defined in application config file
        $this->valid_drivers = $this->ci->config->item('auth.allowed_drivers');

        // Set the default driver
        $this->_driver = $this->ci->config->item('auth.default_driver');

        // We need the users language file for this to work
        // from other modules.
        $this->ci->lang->load('users/users');

        $this->user = $this->{$this->_driver}->autologin();

        log_message('debug', 'Auth Driver initialized.');
    }

    //--------------------------------------------------------------------

    /**
     * Sets the driver to use.
     *
     * @param string $name
     */
    public function set_driver($name)
    {
        $this->_driver = trim( $name );
    }

    //--------------------------------------------------------------------

    /**
     * Attempts to log the user in. The credentials array contains any
     * key/value pairs to be passed to the driver.
     *
     * @param  array   $credentials An array of key/value pairs to match the user on
     * @param  boolean $remember
     * @param  string  $redirect    A URL to redirect to after login.
     *
     * @return mixed
     */
    public function login( $credentials, $remember=FALSE, $redirect=null )
    {
        $user = $this->{$this->_driver}->login($credentials, $remember);

        if ($user)
        {
            $this->user = $user;

            // Save the login info
            $data = array(
                'last_login'            => date('Y-m-d H:i:s', time()),
                'last_ip'               => $this->ci->input->ip_address(),
            );
            $this->ci->user_model->update($user->id, $data);

            // After login Trigger
            $trigger_data = array('user_id'=>$user->id, 'role_id'=>$user->role_id);
            Events::trigger('after_login', $trigger_data );

            if (!empty($redirect))
            {
                redirect($redirect);
            }
        }

        return is_object($user) ? true : false;
    }

    //--------------------------------------------------------------------

    /**
     * Logs a user out.
     *
     * @param string $redirect The site url to redirect to on successful login.
     */
    public function logout($redirect='')
    {
        // Before Logout Event
        $data = array(
            'user_id'   => $this->user_id(),
            'role_id'   => $this->role_id()
        );

        Events::trigger('before_logout', $data);

        $return = $this->{$this->_driver}->logout($redirect);

        // Destroy the session
        $this->ci->session->sess_destroy();

        if (!empty($redirect))
        {
            redirect($redirect);
        }

        return $return;
    }

    //--------------------------------------------------------------------

    /**
     * Gets the current user.
     *
     * @return object The current user, if exists, or NULL.
     */
    public function user()
    {
        if (empty($this->user))
        {
            return NULL;
        }

        return $this->user;
    }

    //--------------------------------------------------------------------

    /**
     * Checks the session for the required info, then verifies against the database.
     *
     * @return bool
     */
    public function is_logged_in()
    {
        return (bool) $this->user();

    }//end is_logged_in()

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Permissions
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
            $this->ci->load->library('Template');
            Template::set_message($this->ci->lang->line('us_must_login'), 'error');
            Template::redirect('login');
        }

        // Check to see if the user has the proper permissions
        if ( ! empty($permission) && ! $this->has_permission($permission))
        {
            // set message telling them no permission THEN redirect
            Template::set_message( lang('us_no_permission'), 'attention');

            if ( ! $uri)
            {
                $uri = $this->ci->session->userdata('previous_page');

                // If previous page was the same (e.g. user pressed F5),
                // but permission has been removed, then redirecting
                // to it will cause an infinite loop.
                if ($uri == current_url())
                {
                    $uri = site_url();
                }
            }
            Template::redirect($uri);
        }

        return TRUE;

    }//end restrict()

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
    public function has_permission($permission, $role_id=NULL, $override = FALSE)
    {
        // move permission to lowercase for easier checking.
        $permission = strtolower($permission);

        // If no role is being provided, assume it's for the current
        // logged in user.
        if (empty($role_id))
        {
            $role_id = $this->role_id();
        }

        $this->load_permissions();
        $this->load_role_permissions($role_id);

        // did we pass?
        if (isset($this->permissions[$permission]))
        {
            $permission_id = $this->permissions[$permission];

            if (isset($this->role_permissions[$role_id][$permission_id]))
            {
                return TRUE;
            }
        }
        elseif ($override)
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
    public function permission_exists($permission)
    {
        // move permission to lowercase for easier checking.
        $permission = strtolower($permission);

        $this->load_permissions();

        return isset($this->permissions[$permission]);

    }//end permission_exists()

    //--------------------------------------------------------------------

    /**
     * Load the permission names from the database
     *
     * @access public
     *
     * @param int $role_id An INT with the role id to grab permissions for.
     *
     * @return void
     */
    private function load_permissions()
    {
        if ( ! isset($this->permissions))
        {
            $this->ci->load->model('permissions/permission_model');

            $perms = $this->ci->permission_model->find_all();

            $this->permissions = array();

            foreach ($perms as $perm)
            {
                $this->permissions[strtolower($perm->name)] = $perm->permission_id;
            }
        }

    }//end load_permissions()

    //--------------------------------------------------------------------

    /**
     * Retrieves the role_id from the current session.
     *
     * @return int The user's role_id.
     */
    public function role_id()
    {
        if ( ! $this->is_logged_in())
        {
            return FALSE;
        }

        return $this->user()->role_id;

    }//end role_id()

    //--------------------------------------------------------------------

    /**
     * Load the role permissions from the database
     *
     * @access public
     *
     * @param int $role_id An INT with the role id to grab permissions for.
     *
     * @return void
     */
    private function load_role_permissions($role_id=NULL)
    {
        $role_id = ! is_null($role_id) ? $role_id : $this->role_id();

        if ( ! isset($this->role_permissions[$role_id]))
        {
            $this->ci->load->model('roles/role_permission_model');

            $role_perms = $this->ci->role_permission_model->find_for_role($role_id);

            $this->role_permissions[$role_id] = array();

            if (is_array($role_perms))
            {
                foreach($role_perms as $permission)
                {
                    $this->role_permissions[$role_id][$permission->permission_id] = TRUE;
                }
            }
        }

    }//end load_role_permissions()

    //--------------------------------------------------------------------

    /**
     * Retrieves the role_name for the requested role.
     *
     * @access public
     *
     * @param int $role_id An int representing the role_id.
     *
     * @return string A string with the name of the matched role.
     */
    public function role_name_by_id($role_id)
    {
        if ( ! is_numeric($role_id))
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
            if ( ! class_exists('Role_model'))
            {
                $this->ci->load->model('roles/role_model');
            }
            $results = $this->ci->role_model->select('role_id, role_name')->find_all();

            foreach ($results as $role)
            {
                $roles[$role->role_id] = $role->role_name;
            }
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
    // Utility Methods
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
        if ( ! $this->is_logged_in())
        {
            return FALSE;
        }

        return $this->user()->id;

    }//end user_id()

    //--------------------------------------------------------------------

    /**
     * Redirect all method calls not in this class to the child class set
     * in the variable 'driver'.
     *
     * @param  mixed $child
     * @param  mixed $arguments
     * @return mixed
     */
    public function __call($child, $arguments)
    {
        return call_user_func_array( array($this->{$this->_driver}, $child), $arguments);
    }

    //--------------------------------------------------------------------

}