<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Activities
 *
 * Display user activity
 *
 * @package    Bonfire\Modules\Activities\Controllers\Activities
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/activities
 */
class Activities extends Admin_Controller
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

        $this->lang->load('activities/activities');
		$this->load->model('activities/activity_model');
	}

	/**
	 * Display the Activities for a module
	 *
	 * @param string $module Name of the module
	 * @param int    $limit  The number of activities to return
	 *
	 * @return string Displays the activities
	 */
	public function activity_list($module = null, $limit = 25)
	{
        $this->auth->restrict('Activities.Module.View');

		if (empty($module)) {
			log_message(lang('activities_list_no_module'), 'debug');
			return;
		}

		$this->activity_model->order_by('created_on', 'desc')
                             ->limit($limit, 0);

		$this->load->view(
            'activity_list',
            array('activities' => $this->activity_model->find_by_module($module))
        );
	}
}