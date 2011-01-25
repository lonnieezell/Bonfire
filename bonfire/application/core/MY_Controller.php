<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Base_Controller extends MX_Controller {
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->load->model('users/User_model', 'user_model', true);
	}
	
	//--------------------------------------------------------------------
	
}

// End Base_Controller class

//--------------------------------------------------------------------

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

class Admin_Controller extends Base_Controller {
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
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
		Template::set_theme('admin');
	}
	
	//--------------------------------------------------------------------
	
}

// End Admin_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */