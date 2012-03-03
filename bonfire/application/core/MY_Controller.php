<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

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

	/*
		Var: $previous_page

		Stores the previously viewed page's complete URL.
	*/
	protected $previous_page;

	/*
		Var: $requested_page

		Stores the page requested. This will sometimes be
		different than the previous page if a redirect happened
		in the controller.
	*/
	protected $requested_page;

	//--------------------------------------------------------------------

	public function __construct()
	{
		Events::trigger('before_controller', get_class($this));

		parent::__construct();

		/*
			Performance optimizations for production environments.
		*/
		if (ENVIRONMENT == 'production')
		{
		    $this->db->save_queries = false;
		}

		// Development niceties...
		else if (ENVIRONMENT == 'development')
		{
			// Profiler bar?
			if (!$this->input->is_cli_request() && $this->settings_lib->item('site.show_front_profiler'))
			{
				$this->load->library('Console');
				$this->output->enable_profiler(true);
			}
		}

		// Make sure that we have a cache enine ready to go...
		$this->load->driver('cache', array('adapter' => 'file'));

		$this->previous_page = $this->session->userdata('previous_page');
		$this->requested_page = $this->session->userdata('requested_page');

		// Pre-Controller Event
		Events::trigger('after_controller_constructor', get_class($this));
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

		$this->load->library('template');
		$this->load->library('assets');
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

	protected $current_user = null;

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		// Auth setup
		$this->load->model('users/User_model', 'user_model');
		$this->load->library('users/auth');

		// Make sure we're logged in.
		$this->auth->restrict();

		// Load our current logged in user so we can access it anywhere.
		$this->current_user = $this->user_model->find($this->auth->user_id());

		// Make the current user available in the views
		$this->load->vars( array('current_user' => $this->current_user) );

		// Load additional libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
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

		$this->load->helper('application');

		$this->load->library('template');
		$this->load->library('assets');
		$this->load->library('ui/contexts');

		// Pagination config
		$this->pager = array();
		$this->pager['full_tag_open']	= '<div class="pagination pagination-right"><ul>';
		$this->pager['full_tag_close']	= '</ul></div>';
		$this->pager['next_link'] 		= '&rarr;';
		$this->pager['prev_link'] 		= '&larr;';
		$this->pager['next_tag_open']	= '<li>';
		$this->pager['next_tag_close']	= '</li>';
		$this->pager['prev_tag_open']	= '<li>';
		$this->pager['prev_tag_close']	= '</li>';
		$this->pager['cur_tag_open']	= '<li class="active"><a href="#">';
		$this->pager['cur_tag_close']	= '</a></li>';
		$this->pager['num_tag_open']	= '<li>';
		$this->pager['num_tag_close']	= '</li>';

		$this->limit = $this->settings_lib->item('site.list_limit');

		// load the keyboard shortcut keys
		$shortcut_data = array(
			'shortcuts' => config_item('ui.current_shortcuts'),
			'shortcut_keys' => unserialize($this->settings_lib->item('ui.shortcut_keys')),
		);
		Template::set('shortcut_data', $shortcut_data);

		// Profiler Bar?
		if (ENVIRONMENT == 'development')
		{
			if (!$this->input->is_cli_request() && $this->settings_lib->item('site.show_profiler'))
			{
				$this->load->library('Console');
				$this->output->enable_profiler(true);
			}
		}

		// Basic setup
		Template::set_theme('admin', 'junk');
	}

	//--------------------------------------------------------------------

}

// End Admin_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
