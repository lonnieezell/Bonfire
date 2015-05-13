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
 * Application Hooks
 *
 * A set of methods used for the CodeIgniter hooks.
 * @link https://ellislab.com/codeigniter/user-guide/general/hooks.html
 *
 * @package   Bonfire\Hooks\App_hooks
 * @author    Bonfire Dev Team
 * @link      http://cibonfire.com/docs/developer
 */
class App_hooks
{
    /** @var array List of pages which bypass the Site Offline page. */
    protected $allowOffline = array(
        '/users/login',
        '/users/logout',
    );

    protected $isInstalled = false;

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

        if (is_object($this->ci)) {
            $this->isInstalled = $this->ci->config->item('bonfire.installed');
            if (! $this->isInstalled) {
                // Is Bonfire installed?
                $this->ci->load->library('installer_lib');
                $this->isInstalled = $this->ci->installer_lib->is_installed();
            }
        }
    }

    /**
     * Check whether the site has been configured to use a Composer Autoloader.
     *
     * @return void
     */
    public function checkAutoloaderConfig()
    {
        // CI 3 loads Composer itself.
        if (defined('CI_VERSION') && substr(CI_VERSION, 0, 1) != '2') {
            return;
        }

        // CI's loader isn't available.
        if (! isset($this->ci->load)) {
            return;
        }

        // If the settings lib is not available, try to load it.
        if (! isset($this->ci->settings_lib)) {
            $this->ci->load->library('settings/settings_lib');
        }

        $composerAutoload = $this->ci->settings_lib->item('composer_autoload');
        if ($composerAutoload === false) {
            return;
        }

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
                if (! class_exists('Auth', false)) {
                    $this->ci->load->library('users/auth');
                }

                if (! $this->ci->auth->has_permission('Site.Signin.Offline')) {
                    $ruriString = '/' . ltrim(str_replace($this->ci->router->directory, '', $this->ci->uri->ruri_string()), '/');
                    if (! in_array($ruriString, $this->allowOffline)) {
                        $offlineReason = $this->ci->settings_lib->item('site.offline_reason');
                        include (APPPATH . 'errors/offline.php');
                        die();
                    }
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
        if (! class_exists('CI_Session', false)) {
            $this->ci->load->library('session');
        }

        $ruriString = '/' . ltrim(str_replace($this->ci->router->directory, '', $this->ci->uri->ruri_string()), '/');
        if (! in_array($ruriString, $this->ignore_pages)) {
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
        if (! $this->isInstalled) {
            return;
        }

        // If the CI_Session class is not loaded, this might be a controller that
        // doesn't extend any of Bonfire's controllers. In that case, try to do
        // this the old fashioned way and add it straight to the session.

        if (! class_exists('CI_Session', false)) {
            if (is_object(get_instance())) {
                // If an instance is available, just load the session lib.
                $this->ci->load->library('session');
            } elseif (get_instance() === null) {
                // If an instance is not available...

                // Try to grab the REQUEST_URI since this will work in most cases.
                $uri = empty($_SERVER['REQUEST_URI']) ? null : $_SERVER['REQUEST_URI'];
                if (empty($uri)) {
                    // Try to get the current URL through PATH INFO.
                    $path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
                    if (trim($path, '/') != '' && $path != '/'.SELF) {
                        $uri = $path;
                    }
                }

                if (empty($uri)) {
                    // Finally, try the query string.
                    $path =  isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
                    if (trim($path, '/') != '') {
                        $uri = $path;
                    }
                }

                // Set the variable in the session and return.
                $_SESSION['requested_page'] = $uri;
                return;
            }
        }

        // Either the session library was available all along or it has been loaded,
        // so determine whether the current URL is in the ignore_pages array and,
        // if it is not, set it as the requested page in the session.
        //
        // Output of uri->ruri_string() is considerably different in CI 3 when using
        // the BF_Router, so the following normalizes the output for the comparison
        // with $this->ignore_pages.

        $ruriString = '/' . ltrim(str_replace($this->ci->router->directory, '', $this->ci->uri->ruri_string()), '/');
        if (! in_array($ruriString, $this->ignore_pages)) {
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
