<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Authenticated Controller
 *
 * Provides a base class for all controllers that must check user login
 * status.
 *
 * @package    Bonfire\Core\Controllers
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Authenticated_Controller extends Base_Controller
{

	//--------------------------------------------------------------------

	/**
	 * Class constructor setup login restriction and load various libraries
	 *
	 */
	public function __construct()
	{
		// Load the Auth library before the parent constructor to ensure
		// the current user's settings are honored by the parent
		$this->load->library('users/auth');

		parent::__construct();

		// Make sure we're logged in.
		$this->auth->restrict();

		// Load additional libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->CI =& $this;	// Hack to make it work properly with HMVC
	}//end construct()

	//--------------------------------------------------------------------

}

/* End of file Authenticated_Controller.php */
/* Location: ./application/core/Authenticated_Controller.php */