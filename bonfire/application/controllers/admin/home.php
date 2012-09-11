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
 * Admin Home controller
 *
 * The base controller which handles visits to the admin area homepage in the Bonfire app.
 *
 * @package    Bonfire
 * @subpackage Controllers
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Home extends Admin_Controller
{


	/**
	 * Controller constructor sets the login restriction
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict();
	}//end __construct()

	//--------------------------------------------------------------------


	/**
	 * Redirects the user to the Content context
	 *
	 * @return void
	 */
    public function index()
    {
        if (!class_exists('Role_model'))
        {
            $this->load->model('roles/role_model');
        }
        $user_role = $this->role_model->find((int)$this->current_user->role_id);
        $default_context = ($user_role !== false && isset($user_role->default_context)) ? $user_role->default_context : '';
        redirect(SITE_AREA .'/'.(isset($default_context) && !empty($default_context) ? $default_context : 'content'));
    }//end index()

	//--------------------------------------------------------------------


}//end class