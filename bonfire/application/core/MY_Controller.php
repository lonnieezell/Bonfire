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
*/
class Base_Controller extends MX_Controller {
	
	protected $previous_page;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->load->model('users/User_model', 'user_model', true);
		
		$this->lang->load('application');
		
		// Dev Bar?
		if (ENVIRONMENT == 'dev')
		{
			$this->load->library('Console');
			$this->output->enable_profiler(false);
		}
		
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
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
	Class: Admin_Controller
	
	This class provides a base class for all admin-facing controllers. 
	It automatically loads the form, form_validation and pagination
	helpers/libraries, sets defaults for pagination and sets our 
	Admin Theme.
	
	Extends:
		Base_controller	
*/

class Admin_Controller extends Base_Controller {
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict();
		
		// Load additional libraries
		$this->load->helper('form');
		
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
		
		$this->load->library('pagination');
		
		// Pagination config
		$this->pager = array();
		$this->pager['full_tag_open']	= '<div class="pagination">';
		$this->pager['full_tag_close']	= '</div>';
		$this->pager['next_link'] 		= 'Next &raquo;';
		$this->pager['prev_link'] 		= '&laquo; Previous';
		
		$this->limit = 25;
		
		// Basic setup
		Template::set_theme('new_admin');
		Assets::add_css(array('ui.css', 'notifications.css', 'buttons.css'));
	}
	
	//--------------------------------------------------------------------
	
}

// End Admin_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */