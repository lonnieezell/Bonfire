<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Class: Base_Controller
	
	This controller provides a controller that your controllers can extend
	from. This allows any tasks that need to be performed sitewide to be 
	done in one place.
	
	Since it extends from MX_Controller, any controller in the system
	can be used in the HMVC style, using modules::run(). See the docs 
	at: https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/wiki/Home
	for more detail on the HMVC code used in Bonfire.
	
	Extends:
		MX_Controller
		
	Package:
		MY_Controller
*/
class Base_Controller extends MX_Controller {
	
	protected $previous_page;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		// Dev Bar?
		if (ENVIRONMENT == 'development')
		{
			$this->load->library('Console');
			$this->output->enable_profiler(true);
		}

		//$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}
	
	//--------------------------------------------------------------------
	
}

// End Base_Controller class

//--------------------------------------------------------------------

/*
	Class: Front_Controller
	
	This class provides a common place to handle any tasks that need to
	be done for all public-facing controllers.
	
	Extends:
		Base_Controller
*/
class Front_Controller extends Base_Controller {
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
	}
	
	//--------------------------------------------------------------------
	
}

// End Front_Controller class

//--------------------------------------------------------------------

/*
	Class: Authenticated_Controller
	
	Provides a base class for all controllers that must check user login
	status.
	
	Extends:
		Base_Controller
*/
class Authenticated_Controller extends Base_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->library('session');
		
		// Auth setup
		$this->load->model('users/User_model', 'user_model');
		$this->load->library('users/auth');
		$this->load->model('roles/permission_model');
		$this->load->model('roles/role_model');
		
		// Make sure we're logged in.
		$this->auth->restrict();
		
		// Load additional libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;	// Hack to make it work properly with HMVC
	}
	
	//--------------------------------------------------------------------
	

}

//--------------------------------------------------------------------

// End Authenticated Controller

/*
	Class: Admin_Controller
	
	This class provides a base class for all admin-facing controllers. 
	It automatically loads the form, form_validation and pagination
	helpers/libraries, sets defaults for pagination and sets our 
	Admin Theme.
	
	Extends:
		Authenticated_controller	
*/

class Admin_Controller extends Authenticated_Controller {
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->lang->load('application');
		$this->load->helper('application');
		
		$this->load->library('pagination');
		
		// Pagination config
		$this->pager = array();
		$this->pager['full_tag_open']	= '<div class="pagination">';
		$this->pager['full_tag_close']	= '</div>';
		$this->pager['next_link'] 		= 'Next &raquo;';
		$this->pager['prev_link'] 		= '&laquo; Previous';
		
		$this->limit = 25;
		
		// Basic setup
		Template::set_theme('admin');
		Assets::add_css(array('ui.css', 'notifications.css', 'buttons.css'));
	}
	
	//--------------------------------------------------------------------
	
}

// End Admin_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */