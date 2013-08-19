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
 * Base Controller
 *
 * This controller provides a controller that your controllers can extend
 * from. This allows any tasks that need to be performed sitewide to be
 * done in one place.
 *
 * Since it extends from MX_Controller, any controller in the system
 * can be used in the HMVC style, using modules::run(). See the docs
 * at: https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/wiki/Home
 * for more detail on the HMVC code used in Bonfire.
 *
 * @package    Bonfire\Core\Controllers
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Base_Controller extends MX_Controller
{


	/**
	 * Stores the previously viewed page's complete URL.
	 *
	 * @var string
	 */
	protected $previous_page;

	/**
	 * Stores the page requested. This will sometimes be
	 * different than the previous page if a redirect happened
	 * in the controller.
	 *
	 * @var string
	 */
	protected $requested_page;

	/**
	 * Stores the current user's details, if they've logged in.
	 *
	 * @var object
	 */
	protected $current_user = NULL;

	//--------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->library('events');

		// Since we don't want to autoload libraries in the
		// standard CI way to make things work for uninstalled apps,
		// autoload our few libs here.
		$this->load->library('settings/settings_lib');

		Events::trigger('before_controller', get_class($this));

		$this->set_current_user();

		// load the application lang file here so that the users language is known
		$this->lang->load('application');

		/*
			Performance optimizations for production environments.
		*/
		if (ENVIRONMENT == 'production')
		{
			// Saving Queries can vastly increase the memory usage, depending
			// on your database usage.
		    $this->db->save_queries = FALSE;

		    // With debugging information turned off, we can at times
		    // continue on after db errors. Also turns off display
		    // of any DB errors so we don't give any info to hackers.
		    $this->db->db_debug 	= FALSE;

		    $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		}

		// Testing niceties...
		else if (ENVIRONMENT == 'testing')
		{
			// Saving Queries can vastly increase the memory usage, depending
			// on your database usage.
			$this->db->save_queries = FALSE;

			$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		}

		// Development niceties...
		else if (ENVIRONMENT == 'development')
		{
			if ($this->settings_lib->item('site.show_front_profiler'))
			{
				// Profiler bar?
				if ( ! $this->input->is_cli_request() AND ! $this->input->is_ajax_request())
				{
					$this->load->library('Console');
					$this->output->enable_profiler(TRUE);
				}

			}

			$this->load->driver('cache', array('adapter' => 'dummy'));
		}

		// Auto-migrate our core and/or app to latest version.
		if ($this->config->item('migrate.auto_core') || $this->config->item('migrate.auto_app'))
		{
			$this->load->library('migrations/migrations');
			$this->migrations->auto_latest();
		}

		// Make sure no assets in up as a requested page or a 404 page.
		if ( ! preg_match('/\.(gif|jpg|jpeg|png|css|js|ico|shtml)$/i', $this->uri->uri_string()))
		{
			$this->previous_page = $this->session->userdata('previous_page');
			$this->requested_page = $this->session->userdata('requested_page');
		}

		// Pre-Controller Event
		Events::trigger('after_controller_constructor', get_class($this));
	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * If the Auth lib is loaded, it will set the current user, since users
	 * will never be needed if the Auth library is not loaded. By not requiring
	 * this to be executed and loaded for every command, we can speed up calls
	 * that don't need users at all, or rely on a different type of auth, like
	 * an API or cronjob.
	 */
	protected function set_current_user()
	{
		if (class_exists('Auth'))
		{
			// Load our current logged in user for convenience
			if ($this->auth->is_logged_in())
			{
				$this->current_user = clone $this->auth->user();

				$this->current_user->user_img = gravatar_link($this->current_user->email, 22, $this->current_user->email, "{$this->current_user->email} Profile");

				// if the user has a language setting then use it
				if (isset($this->current_user->language))
				{
					$this->config->set_item('language', $this->current_user->language);
				}
			}

			// Make the current user available in the views
			if (!class_exists('Template'))
			{
				$this->load->library('Template');
			}
			Template::set('current_user', $this->current_user);
		}
	}

	//--------------------------------------------------------------------


}//end Base_Controller


/* End of file Base_Controller.php */
/* Location: ./application/core/Base_Controller.php */
