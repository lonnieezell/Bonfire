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
 * Application Hooks
 *
 * This class provides a set of methods used for the CodeIgniter hooks.
 * http://www.codeigniter.com/user_guide/general/hooks.html
 *
 * @package    Bonfire
 * @subpackage Hooks
 * @category   Hooks
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/core/hooks.html
 *
 */
class App_hooks
{


	/**
	 * Stores the CodeIgniter core object.
	 *
	 * @access private
	 *
	 * @var object
	 */
	private $ci;

	/**
	 * List of pages where the hooks are not run.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $ignore_pages = array('login', 'logout', 'register', 'forgot_password', 'activate', 'resend_activation', 'images');

	//--------------------------------------------------------------------


	/**
	 * Costructor
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
	}//end __construct()

	//--------------------------------------------------------------------


	/**
	 * Stores the name of the current uri in the session as 'previous_page'.
	 * This allows redirects to take us back to the previous page without
	 * relying on inconsistent browser support or spoofing.
	 *
	 * Called by the "post_controller" hook.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function prep_redirect()
	{
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}

		if (!in_array($this->ci->uri->uri_string(), $this->ignore_pages))
		{
			$this->ci->session->set_userdata('previous_page', current_url());
		}
	}//end prep_redirect()

	//--------------------------------------------------------------------

	/**
	 * Store the requested page in the session data so we can use it
	 * after the user logs in.
	 *
	 * Called by the "pre_controller" hook.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function save_requested()
	{
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}

		if (!in_array($this->ci->uri->ruri_string(), $this->ignore_pages))
		{
			$this->ci->session->set_userdata('requested_page', current_url());
		}
	}//end save_requested()

	//--------------------------------------------------------------------


	/**
	 * Check the online/offline status of the site.
	 *
	 * Called by the "post_controller_constructor" hook.
	 *
	 * @access public
	 *
	 * @return void
	 *
	 */
	public function check_site_status()
	{
		if ($this->ci->settings_lib->item('site.status') == 0)
		{
			if (!class_exists('Auth'))
			{
				$this->ci->load->library('users/auth');
			}

			if (!$this->ci->auth->has_permission('Site.Signin.Offline'))
			{
				include (APPPATH .'errors/offline'. EXT);
				die();
			}
		}
	}//end check_site_status()

	//--------------------------------------------------------------------

}//end class
