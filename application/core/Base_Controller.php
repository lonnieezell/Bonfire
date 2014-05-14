<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
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
 * Base Controller
 *
 * A controller that your controllers can extend.
 *
 * This allows any tasks which need to be performed sitewide to be done in one
 * place.
 *
 * @package    Bonfire\Application\Core\Base_Controller
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/bonfire/bonfire_controllers
 */
class Base_Controller extends CI_Controller
{
	/**
	 * @var string Stores the previously viewed page's complete URL.
	 */
	protected $previous_page;

	/**
	 * @var string Stores the page requested.
	 *
	 * This will sometimes be different than the previous page if a redirect
	 * happened in the controller.
	 */
	protected $requested_page;

	/**
	 * @var object Stores the current user's details, if they've logged in.
	 */
	protected $current_user = null;

    /**
     * @var bool If TRUE, this class requires the user to be logged in before
	 * accessing any method.
     */
    protected $require_authentication = false;

    /**
     * @var array Stores a number of items to 'autoload' when the class
     * constructor runs. This allows any controller to easily set items which
     * should always be loaded, but not to force the entire application to
     * autoload it through the config/autoload file.
     */
    protected $autoload = array(
        'libraries' => array('settings/settings_lib'),
        'helpers'   => array(),
        'models'    => array(),
    );

	//--------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

        // Most likely, the requested page is saved in the $_SESSION here, so,
		// grab it and make it available to CI's session.
        if (isset($_SESSION['requested_page']) && class_exists('CI_Session')) {
            $this->session->set_userdata(array('requested_page' => $_SESSION['requested_page']));
        }

		$this->load->library('events');

		// Handle any autoloading here...
		$this->autoload_classes();

		Events::trigger('before_controller', get_class($this));

        if ($this->require_authentication === true) {
            $this->authenticate();
        }

		// Load the lang file here, after the user's language is known
		$this->lang->load('application');

		// Performance optimizations for production environments.
		if (ENVIRONMENT == 'production') {
			// Saving queries can vastly increase the memory usage
		    $this->db->save_queries = false;

		    // With debugging information turned off, at times it is possible to
		    // continue on after db errors. Also turns off display of any DB
		    // errors to reduce info available to hackers.
		    $this->db->db_debug = false;

		    $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		}
		// Testing niceties...
		elseif (ENVIRONMENT == 'testing') {
			// Saving Queries can vastly increase the memory usage
			$this->db->save_queries = false;

			$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		}
		// Development niceties...
		else {
			// Profiler bar?
			if ($this->settings_lib->item('site.show_front_profiler')) {
				if ( ! $this->input->is_cli_request()
					&& ! $this->input->is_ajax_request()
				) {
					$this->load->library('Console');
					$this->output->enable_profiler(true);
				}
			}

			$this->load->driver('cache', array('adapter' => 'dummy'));
		}

		// Auto-migrate our core and/or app to latest version.
		if ($this->config->item('migrate.auto_core')
            || $this->config->item('migrate.auto_app')
        ) {
			$this->load->library('migrations/migrations');
			$this->migrations->autoLatest();
		}

		// Make sure no assets in up as a requested page or a 404 page.
		if ( ! preg_match('/\.(gif|jpg|jpeg|png|css|js|ico|shtml)$/i', $this->uri->uri_string())) {
			$this->previous_page  = $this->session->userdata('previous_page');
			$this->requested_page = $this->session->userdata('requested_page');
		}

		// Pre-Controller Event
		Events::trigger('after_controller_constructor', get_class($this));
	}

	/**
	 * If the Auth lib is loaded, it will set the current user, since users will
	 * never be needed if the Auth library is not loaded. By not requiring this
	 * to be executed and loaded for every command, calls that don't need users
	 * at all, or which rely on a different type of auth (like an API or
	 * cronjob), can be sped up.
	 */
	protected function set_current_user()
	{
		if (class_exists('Auth') && isset($this->auth)) {
			// Load the currently logged-in user for convenience
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

			// Make the current user available in the views
            // When calling from Authenticated controller, this class
		    $this->load->library('Template');
			Template::set('current_user', $this->current_user);
		}
	}

    /**
     * Performs the authentication of a class. Ensures that a user is logged in.
     * Any additional authentication will need to be done by the child classes.
     *
     * By having the authenticaiton handled here, it can be called in the
     * Base_Controller's __construct() method to ensure the user's chosen
     * language is used.
     */
    protected function authenticate()
    {
        // Load the Auth library before the parent constructor to ensure the
        // current user's settings are honored by the parent
        $this->load->library('users/auth');

        // Ensure the user is logged in.
        $this->auth->restrict();

        $this->set_current_user();
    }

    /**
     * Autoloads any class-specific files that are needed throughout the
     * controller. This is often used by base controllers, but can easily be
     * used to autoload models, etc.
     *
     * @return void
     */
    public function autoload_classes()
    {
        // Using ! empty() because count() returns 1 for certain error conditions

        if ( ! empty($this->autoload['libraries'])
            && is_array($this->autoload['libraries'])
        ) {
            foreach ($this->autoload['libraries'] as $library) {
                $this->load->library($library);
            }
        }

        if ( ! empty($this->autoload['helpers'])
            && is_array($this->autoload['helpers'])
        ) {
            foreach ($this->autoload['helpers'] as $helper) {
                $this->load->helper($helper);
            }
        }

        if ( ! empty($this->autoload['models'])
            && is_array($this->autoload['models'])
        ) {
            foreach ($this->autoload['models'] as $model) {
                $this->load->model($model);
            }
        }
    }
}
/* End of file ./application/core/Base_Controller.php */