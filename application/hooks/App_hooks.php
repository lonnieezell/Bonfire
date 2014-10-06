<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Application Hooks
 *
 * A set of methods used for the CodeIgniter hooks.
 * @link https://ellislab.com/codeigniter/user-guide/general/hooks.html
 *
 * @package    Bonfire\Hooks\App_hooks
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/developer
 */
class App_hooks
{
    /**
     * @var object The CodeIgniter core object.
     */
    private $ci;

    /**
     * @var array List of pages for which the URL-save/prep hooks are not run.
     */
    private $ignore_pages = array(
        '/users/login',
        '/users/logout',
        '/users/register',
        '/users/forgot_password',
        '/users/activate',
        '/users/resend_activation',
        '/images',
    );

    //--------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->ci =& get_instance();
    }

    /**
     * Check whether the site has been configured to use a Composer Autoloader.
     *
     * @return void
     */
    public function checkAutoloaderConfig()
    {
        if (isset($this->ci->load)) {
            // If the settings lib is not available, try to load it.
            if (! isset($this->ci->settings_lib)) {
                $this->ci->load->library('settings/settings_lib');
            }

            $composerAutoload = $this->ci->settings_lib->item('composer_autoload');
            if ($composerAutoload !== false) {
                if ($composerAutoload === true) {
                    if (file_exists(APPPATH . 'vendor/autoload.php')) {
                        require_once(APPPATH . 'vendor/autoload.php');
                    } elseif (file_exists(APPPATH . '../vendor/autoload.php')) {
                        require_once(APPPATH . '../vendor/autoload.php');
                    }
                } elseif (file_exists($composerAutoload)) {
                    require_once($composerAutoload);
                }
            }
        }
    }

    /**
     * Check the online/offline status of the site.
     *
     * Called by the "post_controller_constructor" hook.
     *
     * @return void
     *
     */
    public function checkSiteStatus()
    {
        if (isset($this->ci->load)) {
            // If the settings lib is not available, try to load it.
            if (! isset($this->ci->settings_lib)) {
                $this->ci->load->library('settings/settings_lib');
            }

            if ($this->ci->settings_lib->item('site.status') == 0) {
                if (! class_exists('Auth')) {
                    $this->ci->load->library('users/auth');
                }

                if (! $this->ci->auth->has_permission('Site.Signin.Offline')) {
                    include (APPPATH . 'errors/offline.php');
                    die();
                }
            }
        }
    }

	/**
	 * Stores the name of the current uri in the session as 'previous_page'.
	 * This allows redirects to take us back to the previous page without
	 * relying on inconsistent browser support or spoofing.
	 *
	 * Called by the "post_controller" hook.
	 *
	 * @return void
	 */
    public function prepRedirect()
    {
        if (! class_exists('CI_Session')) {
            $this->ci->load->library('session');
        }

        if (! in_array($this->ci->uri->ruri_string(), $this->ignore_pages)) {
            $this->ci->session->set_userdata('previous_page', current_url());
        }
    }

	/**
	 * Store the requested page in the session data so we can use it
	 * after the user logs in.
	 *
	 * Called by the "pre_controller" hook.
	 *
	 * @return void
	 */
    public function saveRequested()
	{
        // If the CI_Session class is not loaded, we might be called from
        // a controller that doesn't extend any of Bonfire's controllers.
        // In that case, we need to try to do this the old fashioned way
        // and add it straight to the session.
        if (! class_exists('CI_Session') && get_instance() === null) {
            // Let's try to grab it from the REQUEST_URI since
            // this will work in majority of cases.
            $uri = isset($_SERVER['REQUEST_URI']) && ! empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;

            // Try to get the current URL through PATH INFO
            if (empty($uri) && isset($_SERVER['PATH_INFO'])) {
                $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
                if (trim($path, '/') != '' && $path != "/".SELF) {
                    $uri = $path;
                }
            }

            // Finally, let's try the query string.
            if (empty($uri) && isset($_SERVER['QUERY_STRING'])) {
                $path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
                if (trim($path, '/') != '') {
                    $uri = $path;
                }
            }

                $_SESSION['requested_page'] = $uri;
            return;
        } elseif (! class_exists('CI_Session') && is_object(get_instance())) {
        // If we can get an actual instance, then just load the session lib.
            $this->ci->load->library('session');
        }

        if (! in_array($this->ci->uri->ruri_string(), $this->ignore_pages)) {
			$this->ci->session->set_userdata('requested_page', current_url());
		}
    }

    //--------------------------------------------------------------------------
    // Deprecated Methods (do not use)
    //--------------------------------------------------------------------------

	/**
	 * Check the online/offline status of the site.
	 *
	 * Called by the "post_controller_constructor" hook.
	 *
     * @deprecated since 0.7.1 Use checkSiteStatus().
	 *
	 * @return void
	 *
	 */
	public function check_site_status()
	{
        return $this->checkSiteStatus();
	}

    /**
     * Stores the name of the current uri in the session as 'previous_page'.
     * This allows redirects to take us back to the previous page without
     * relying on inconsistent browser support or spoofing.
     *
     * Called by the "post_controller" hook.
     *
     * @deprecated since 0.7.1 Use prepRedirect().
     *
     * @return void
     */
    public function prep_redirect()
    {
        return $this->prepRedirect();
	}

    /**
     * Store the requested page in the session data so we can use it
     * after the user logs in.
     *
     * Called by the "pre_controller" hook.
     *
     * @deprecated since 0.7.1 Use saveRequested().
     *
     * @return void
     */
    public function save_requested()
	{
        return $this->saveRequested();
	}
}
