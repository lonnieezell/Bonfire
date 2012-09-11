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
 * Activities Reports Context
 *
 * Allows the administrator to view the activity logs.
 *
 * @package    Bonfire
 * @subpackage Modules_Activities
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Reports extends Admin_Controller
{

	//--------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Site.Reports.View');
		$this->auth->restrict('Bonfire.Activities.View');

		$this->lang->load('activities');
		$this->lang->load('datatable');		

		Template::set('toolbar_title', lang('activity_title'));

		Assets::add_js(Template::theme_url('js/bootstrap.js'));
		Assets::add_js($this->load->view('reports/activities_js', null, true), 'inline');

		Assets::add_js( array ( Template::theme_url('js/jquery.dataTables.min.js' )) );
		Assets::add_js( array ( Template::theme_url('js/bootstrap-dataTables.js' )) );
		Assets::add_css( array ( Template::theme_url('css/datatable.css') ) ) ;
		Assets::add_css( array ( Template::theme_url('css/bootstrap-dataTables.css') ) ) ;		


		//Assets::add_module_css ('activities', 'datatables.css');


		if (has_permission('Activities.User.View')
				|| has_permission('Activities.Module.View')
				|| has_permission('Activities.Date.View'))
		{
			Template::set_block('sub_nav', 'reports/_sub_nav');
		}
	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Lists all log files and allows you to change the log_threshold.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function index()
	{
		if (has_permission('Activities.User.View')
				|| has_permission('Activities.Module.View')
				|| has_permission('Activities.Date.View'))
		{
			// get top 5 modules
			$this->db->group_by('module');
			Template::set('top_modules', $this->activity_model->select('module, COUNT(module) AS activity_count')
					->where('activities.deleted', 0)
					->limit(5)
					->order_by('activity_count', 'DESC')
					->find_all() );

			// get top 5 users and usernames
			$this->db->join('users', 'activities.user_id = users.id', 'left');
			$query = $this->db->select('username, user_id, COUNT(user_id) AS activity_count')
					->where('activities.deleted', 0)
					->group_by('user_id')
					->order_by('activity_count','DESC')
					->limit(5)
					->get($this->activity_model->get_table());
			Template::set('top_users', $query->result());

			Template::set('users', $this->user_model->find_all());
			Template::set('modules', module_list());
			Template::set('activities', $this->activity_model->find_all());
			Template::render();
		}
		else if(has_permission('Activities.Own.View'))
		{
			$this->activity_own();

		}

	}//end index()

	//--------------------------------------------------------------------

	/**
	 * Shows the activities for the specified user.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function activity_user()
	{

		if (!has_permission('Activities.User.View')) {
			Template::set_message(lang('activity_restricted'), 'error');
			Template::redirect(SITE_AREA .'/reports/activities');
		}

		return $this->_get_activity();

	}//end activity_user()

	//--------------------------------------------------------------------

	/**
	 * Shows the activities for the current user.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function activity_own()
	{

		if (!has_permission('Activities.Own.View')) {
			Template::set_message(lang('activity_restricted'), 'error');
			Template::redirect(SITE_AREA .'/reports/activities');
		}

		return $this->_get_activity('activity_own', $this->current_user->id);

	}//end activity_own()

	//--------------------------------------------------------------------

	/**
	 * Shows the activities for the specified module.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function activity_module()
	{
		if (has_permission('Activities.Module.View')) {
			return $this->_get_activity('activity_module');
		}

		Template::set_message(lang('activity_restricted'), 'error');
		Template::redirect(SITE_AREA .'/reports/activities');

	}//end activity_module()

	//--------------------------------------------------------------------

	/**
	 * Shows the activities before the specified date.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function activity_date()
	{
		if (has_permission('Activities.Date.View')) {
			return $this->_get_activity('activity_date');
		}

		Template::set_message(lang('activity_restricted'), 'error');
		Template::redirect(SITE_AREA .'/reports/activities');

	}//end activity_date()


	//--------------------------------------------------------------------

	/**
	 * Deletes the entries in the activity log for the specified area.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function delete()
	{
		$action = $this->input->post("action");
		$which  = $this->input->post("which");

		$this->_delete_activity($action, $which);

		Template::redirect(SITE_AREA .'/reports/activities');

	} // end delete()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Delete the entries in the activity log for the specified area.
	 *
	 * @access private
	 *
	 * @param string $action The area we are in
	 * @param string $which  A specific value to match, or "all"
	 *
	 * @return void
	 */
	private function _delete_activity($action, $which)
	{
		// check for permission to delete this
		$permission = str_replace('activity_', '',$action);
		if (!has_permission('Activities.'.ucfirst($permission).'.Delete')) {
			Template::set_message(lang('activity_restricted'), 'error');
			return;
		}

		if (empty($action))
		{
			Template::set_message('Delete section not specified', 'error');
			return;
		}

		if (empty($which))
		{
			Template::set_message('Delete value not specified', 'error');
			return;
		}

		// different delete where statement switch
		switch ($action)
		{
			case 'activity_date':
				$value = 'activity_id';
			break;

			case 'activity_module':
				$value = 'module';
			break;

			default:
				$value = 'user_id';
			break;
		}

		// set a default delete then check if delete "all" was chosen
		$delete = ($value == 'activity_id') ? $value ." < '".$which."'" : $value ." = '".$which."'";
		if ($which == 'all')
		{
			$delete = $value ." != 'tsTyImbdOBOgwIqtb94N4Gr6ctatWVDnmYC3NfIfczzxPs0xZLNBnQs38dzBYn8'";
		}

		// check if they can delete their own stuff
		$delete .= (has_permission('Activities.Own.Delete')) ? '' : " AND user_id != '" . $this->auth->user_id()."'";

		$affected = $this->activity_model->delete_where($delete);
		if (is_numeric($affected))
		{
			Template::set_message(sprintf(lang('activity_deleted'),$affected),'success');
			$this->activity_model->log_activity($this->auth->user_id(), 'deleted '.$affected.' activities', 'activities');
		}
		else if (isset($affected))
		{
			Template::set_message(lang('activity_nothing'),'attention');
		}
		else
		{
			Template::set_message('Error : '.$this->activity_model->error, 'error');
		}

	}//end _delete_activity()

	/**
	 * Gets all the activity based on parameters passed
	 *
	 * @access public
	 *
	 * @param string $which      Filter the activities by type
	 * @param bool   $find_value Value to filter by
	 *
	 * @return void
	 */
	private function _get_activity($which='activity_user',$find_value=FALSE)
	{
		// check if $find_value has anything in it
		if ($find_value === FALSE)
		{
			$find_value = ($this->input->post($which.'_select') == '') ? $this->uri->segment(5) : $this->input->post($which.'_select');
		}

		if (isset($_POST['delete']))
		{
			$this->_delete_activity($which, $find_value);
		}

		Template::set('filter', $this->input->post($which.'_select'));

		// set a couple default variables
		$options = array('all' => 'All');
		$name = 'All';

		switch ($which)
		{
			case 'activity_module':
				$modules = module_list();
				foreach ($modules as $mod)
				{
					$options[$mod] = $mod;

					if ($find_value == $mod)
					{
						$name = ucwords($mod);
					}
				}
				$where = 'module';
			break;

			case 'activity_date':
				foreach($this->activity_model->find_all_by('deleted', 0) as $e)
				{
					$options[$e->activity_id] = $e->created_on;

					if ($find_value == $e->activity_id)
					{
						$name = $e->created_on;
					}
				}
				$where = 'activity_id';
			break;

			case 'activity_own':
			default:
				if (has_permission('Activities.User.View'))
				{
					foreach($this->user_model->find_all() as $e)
					{
						$options[$e->id] = $e->username;

						if ($find_value == $e->id)
						{
							$name = $e->username;
						}
					}
				}
				else if (has_permission('Activities.Own.View'))
				{
					$options = array();
					$options[$this->current_user->id] = $this->current_user->username;
					$name = $this->current_user->username;
				}

				$where = 'user_id';
			break;
		}

		// set some vars for the view
		$vars = array(
			'which'			=> $which,
			'view_which'	=> ucwords(lang($which)),
			'name'			=> $name,
			'delete_action'	=> $where,
			'delete_id'		=> $find_value
		);
		Template::set('vars', $vars);

		// if we have a filter, apply it here
		$this->db->order_by($where,'asc');
		if (!empty($find_value) && $find_value != 'all')
		{
			$where = ($where == 'activity_id') ? 'activity_id <' : $where;
			$this->db->where($where,$find_value);

			$this->db->where('activities.deleted', 0);
			$total = $this->activity_model->count_by($where, $find_value);

			// set this again for use in the main query
			$this->db->where($where,$find_value);
		}
		else
		{
			$total = $this->activity_model->count_by('activities.deleted', 0);
		}

		// does user have permission to see own records?
		if (!has_permission('Activities.Own.View'))
		{
			$this->db->where('activities.user_id != ', $this->auth->user_id());
		}

		// don't show the deleted records
		$this->db->where('activities.deleted', 0);

		// Pagination
		$this->load->library('pagination');

		$offset = $this->input->get('per_page');

		$limit = $this->settings_lib->item('site.list_limit');

		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= true;

		$this->pagination->initialize($this->pager);

		// get the activities
		$this->db->join('users', 'activities.user_id = users.id', 'left');
		$this->db->order_by('activity_id','desc'); // most recent stuff on top
		$this->db->select('activity, module, activities.created_on AS created, username');
		Template::set('activity_content', $this->activity_model->limit($limit, $offset)->find_all());

		Template::set('select_options', $options);

		Template::set_view('reports/view');
		Template::render();

	}//end _get_activity()


	//--------------------------------------------------------------------

}//end class
