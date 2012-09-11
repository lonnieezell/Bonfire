<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * Permissions Settings Context
 *
 * Allows the management of the Bonfire permissions.
 *
 * @package    Bonfire
 * @subpackage Modules_Permissions
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Settings extends Admin_Controller
{

	/**
	 * Sets up the require permissions and loads required classes
	 *
	 * @return void
	 */
	function __construct()
	{
 		parent::__construct();

		$this->auth->restrict('Bonfire.Permissions.View');
		$this->auth->restrict('Bonfire.Permissions.Manage');

		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('permission_model');
		$this->lang->load('permissions');
		$this->load->helper('inflector');

		Template::set_block('sub_nav', 'settings/_sub_nav');

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Displays a list of all permissions with pagination
	 *
	 * @access public
	 *
	 * @return void
	 */
	function index()
	{
		// Deleting anything?
		if (isset($_POST['delete']))
		{
			$checked = $this->input->post('checked');

			if (is_array($checked) && count($checked))
			{
				$result = FALSE;
				foreach ($checked as $pid)
				{
					$result = $this->permission_model->delete($pid);
				}

				if ($result)
				{
					Template::set_message(count($checked) .' '. lang('permissions_deleted') .'.', 'success');
				}
				else
				{
					Template::set_message(lang('permissions_del_failure') . $this->permission_model->error, 'error');
				}
			}
			else
			{
				Template::set_message(lang('permissions_del_error') . $this->permission_model->error, 'error');
			}
		}//end if

		$total = $this->permission_model->count_all();

		// Pagination
		$this->load->library('pagination');

		$offset = $this->input->get('per_page');

		$limit = $this->settings_lib->item('site.list_limit');

		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;

		$this->pagination->initialize($this->pager);

		Template::set('results', $this->permission_model->limit($limit, $offset)->find_all());

		Template::set("toolbar_title", lang("permissions_manage"));
		Template::render();

	}//end index()

	//--------------------------------------------------------------------

	/**
	 * Create a new permission in the database
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function create()
	{
		if ($this->input->post('submit'))
		{
			if ($this->save_permissions())
			{
				Template::set_message(lang("permissions_create_success"), 'success');
				Template::redirect(SITE_AREA .'/settings/permissions');
			}
		}

		Template::set('toolbar_title', lang("permissions_create_new_button"));
		Template::set_view('settings/permission_form');
		Template::render();

	}//end create()

	//--------------------------------------------------------------------

	/**
	 * Edit a permission record
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function edit()
	{
		$id = (int)$this->uri->segment(5);

		if (empty($id))
		{
			Template::set_message(lang("permissions_invalid_id"), 'error');
			redirect(SITE_AREA .'/settings/permissions');
		}

		if ($this->input->post('submit'))
		{
			if ($this->save_permissions('update', $id))
			{
				Template::set_message(lang("permissions_edit_success"), 'success');
			}
		}

		Template::set('permissions', $this->permission_model->find($id));

		Template::set('toolbar_title', lang("permissions_edit_heading"));
		Template::set_view('settings/permission_form');
		Template::render();

	}//end edit()

	//--------------------------------------------------------------------

	/**
	 * Save the permission record to the database
	 *
	 * @access private
	 *
	 * @param string $type The type of save operation (insert or edit)
	 * @param int    $id   The record ID in the case of edit
	 *
	 * @return bool
	 */
	private function save_permissions($type='insert', $id=0)
	{

		$this->form_validation->set_rules('name','Name','required|trim|xss_clean|max_length[30]');
		$this->form_validation->set_rules('description','Description','trim|xss_clean|max_length[100]');
		$this->form_validation->set_rules('status','Status','required|trim|xss_clean');
		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
		}

		unset($_POST['submit']);

		if ($type == 'insert')
		{
			$id = $this->permission_model->insert($_POST);

			if (is_numeric($id))
			{
				$return = TRUE;
			}
			else
			{
				$return = FALSE;
			}
		}
		else if ($type == 'update')
		{
			$return = $this->permission_model->update($id, $_POST);
		}

		return $return;

	}//end save_permissions()

	//--------------------------------------------------------------------
}//end settings
