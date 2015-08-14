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
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Base Controller
 *
 * This provides a controller that your controllers can extend. This allows any
 * tasks that need to be performed sitewide to be done in one place.
 *
 * Since it extends from MX_Controller, any controller in the system can be used
 * in the HMVC style. For more details on the HMVC code used in Bonfire, see:
 * https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/wiki/Home
 *
 * @package Bonfire\Application\Core\Base_Controller
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/bonfire/bonfire_controllers
 */
class Base_Controller extends MX_Controller
{
    /**
     * @var string Stores the previously viewed page's complete URL. Managed by
     * App_hooks->prepRedirect() via a 'post_controller' hook and stored in the
     * session.
     */
    protected $previous_page;

    /**
     * @var string Stores the requested page, which will sometimes differ from the
     * current page in cases like the login page, where the requested page will
     * need to be available if they are successfully authenticated. Managed by
     * App_hooks->saveRequested() via a 'pre_controller' hook and stored in the
     * session.
     */
    protected $requested_page;

    /** @var object Stores the current user's details, if they've logged in. */
    protected $current_user = null;

    /** @var bool If true, the user must log in to access any method. */
    protected $require_authentication = false;

    /**
     * @var array Stores a number of items to 'autoload' when the constructor runs.
     * This allows any controller to easily set items which should always be loaded,
     * but not to force the entire application to autoload it through the config/autoload
     * file.
     */
    public $autoload = array(
        'libraries' => array('settings/settings_lib', 'events'),
        'helpers'   => array('application'),
        'models'    => array(),
    );

    //--------------------------------------------------------------------------

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Handle any autoloading here...
        // Because this may be overloaded in someone's custom controllers, this
        // will be updated to call $this->autoloadClasses() in the future.
        $this->autoload_classes();

        $controllerClass = get_class($this);
        Events::trigger('before_controller', $controllerClass);

        if ($this->require_authentication === true) {
            $this->authenticate();
        }

        // Load the lang file here, after the user's language is known
        $this->lang->load('application');

        $cacheDriver = array();

        // Performance optimizations for production environments.
        //
        // Overrides database configuration of save_queries in production/testing
        // environments and db_debug in production as a hedge against accidental
        // configuration issues.
        switch (ENVIRONMENT) {
            case 'production':
                // With debugging information turned off, at times it is possible to
                // continue on after db errors. Also turns off display of any DB errors
                // to reduce info available to hackers.
                $this->db->db_debug = false;

                // If you need more differences between production/testing, you
                // may want to prevent fall-through by adding a break before the
                // 'testing' case.

                // no break
            case 'testing':
                // Saving queries can vastly increase the memory usage.
                $this->db->save_queries = false;

                $cacheDriver['adapter'] = 'apc';
                $cacheDriver['backup']  = 'file';
                break;
            default:
                // Development niceties...
                // Profiler bar?
                $this->showProfiler();

                $cacheDriver['adapter'] = 'dummy';
                break;
        }

        $this->load->driver('cache', $cacheDriver);

        // Auto-migrate core and/or app to latest version.
        if ($this->config->item('migrate.auto_core')
            || $this->config->item('migrate.auto_app')
        ) {
            $this->load->library('migrations/migrations');
            $this->migrations->autoLatest();
        }

        // Make sure no assets end up as a requested page or a 404 page.
        if (! preg_match('/\.(gif|jpg|jpeg|png|css|js|ico|shtml)$/i', $this->uri->uri_string())) {
            $this->previous_page  = $this->session->userdata('previous_page');
            $this->requested_page = $this->session->userdata('requested_page');
        }

        // After-Controller Constructor Event
        $controllerClass = get_class($this);
        Events::trigger('after_controller_constructor', $controllerClass);
    }

    /**
     * If the Auth lib is loaded, set $this->current_user.
     *
     * By returning if the Auth library hasn't already been loaded, we potentially
     * save some time and prevent loading unnecessary libraries.
     *
     * @return void
     */
    protected function set_current_user()
    {
        if (! class_exists('Auth', false)) {
            return;
        }

        // Load the currently logged-in user for convenience.
        if ($this->auth->is_logged_in()) {
            $this->current_user = clone $this->auth->user();

            $this->current_user->user_img = gravatar_link(
                $this->current_user->email,
                22,
                $this->current_user->email,
                "{$this->current_user->email} Profile"
            );

            // If the user has a language setting then use it
            if (isset($this->current_user->language)) {
                $this->config->set_item('language', $this->current_user->language);
                $this->session->set_userdata('language', $this->current_user->language);
            }
        }

        // Make the current user available in the views.
        if (! class_exists('template', false)) {
            $this->load->library('template');
        }
        Template::set('current_user', $this->current_user);
    }

    /**
     * Ensures that a user is logged in. Any additional authentication will need
     * to be done by the child classes.
     *
     * By having the authentication handled here (rather than Authenticated_Controller),
     * it can be called in the Base_Controller's __construct() method to ensure
     * the user's chosen language is used whenever the user is logged in, even if
     * the child controller doesn't require authentication.
     *
     * @return void
     */
    protected function authenticate()
    {
        $this->load->library('users/auth');

        // Ensure the user is logged in.
        $this->auth->restrict();
        $this->set_current_user();
    }

    /**
     * Autoload any class-specific files that are needed throughout the controller.
     * This is often used by base controllers, but can easily be used to autoload
     * models, etc.
     *
     * @deprecated since 0.9.0. Use autoloadClasses() instead. Note the difference
     * in visibility.
     *
     * @return void
     */
    public function autoload_classes()
    {
        $this->autoloadClasses();
    }

    /**
     * Autoload any class-specific files that are needed throughout the controller.
     * This is often used by base controllers, but can easily be used to autoload
     * models, etc.
     *
     * @return void
     */
    protected function autoloadClasses()
    {
        // Using ! empty() because count() returns 1 for certain error conditions.

        if (! empty($this->autoload['libraries'])
            && is_array($this->autoload['libraries'])
        ) {
            foreach ($this->autoload['libraries'] as $library) {
                $this->load->library($library);
            }
        }

        if (! empty($this->autoload['helpers'])
            && is_array($this->autoload['helpers'])
        ) {
            foreach ($this->autoload['helpers'] as $helper) {
                $this->load->helper($helper);
            }
        }

        if (! empty($this->autoload['models'])
            && is_array($this->autoload['models'])
        ) {
            foreach ($this->autoload['models'] as $model) {
                $this->load->model($model);
            }
        }
    }

    /**
     * Enable the profiler if it is permitted for the current request.
     *
     * @param bool $frontEnd Set to true for a front-end (public/non-admin) page,
     * false for an admin page. This determines whether the 'site.show_front_profiler'
     * setting will be used to control display of the profiler.
     *
     * @return void
     */
    protected function showProfiler($frontEnd = true)
    {
        if (is_cli() || $this->input->is_ajax_request()) {
            return;
        }

        if ($frontEnd && ! $this->settings_lib->item('site.show_front_profiler')) {
            return;
        }

        $this->load->library('Console');
        $this->output->enable_profiler(true);
    }
}
