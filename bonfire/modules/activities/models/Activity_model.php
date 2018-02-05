<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2018, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com/docs/developer/activities
 * @since     Version 1.0
 * @filesource
 */

/**
 * Activities
 *
 * Provides a simple and consistent way to record and display user-related
 * activities in both core and custom modules.
 *
 * @package Bonfire\Modules\Activities\Models\Activity_model
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/activities
 *
 */
class Activity_model extends BF_Model
{
    /** @var string Name of the table. */
    protected $table_name = 'activities';

    /** @var string Name of the primary key. */
    protected $key = 'activity_id';

    /** @var bool Whether to use soft deletes. */
    protected $soft_deletes = true;

    /** @var string The date format to use. */
    protected $date_format = 'datetime';

    /** @var bool Set the created time automatically on a new record. */
    protected $set_created = true;

    /** @var bool Set the modified time automatically on editing a record. */
    protected $set_modified = false;

    //--------------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('activities/activities');
    }

    /**
     * Return all activities created by one or more modules.
     *
     * @param string[]|string $modules Module name(s).
     *
     * @return bool|array An array of activity objects, or false.
     */
    public function find_by_module($modules = [])
    {
        if (empty($modules)) {
            $this->error = lang('activities_model_no_module');
            return false;
        }

        if (! is_array($modules)) {
            $modules = [$modules];
        }

        if (! class_exists('user_model', false)) {
            $this->load->model('users/user_model');
        }

        return $this->select(
            [
                'activity_id',
                "{$this->table_name}.user_id",
                'activity',
                'module',
                "{$this->table_name}.{$this->created_field}",
                'display_name',
                'username',
                'email',
                'last_login',
            ]
        )
            ->where_in('module', $modules)
            ->where("{$this->table_name}.{$this->deleted_field}", 0)
            ->join(
                $this->user_model->get_table(),
                "{$this->table_name}.user_id = " . $this->user_model->get_table() . '.' . $this->user_model->get_key(),
                'left'
            )
            ->find_all();
    }

    /**
     * Find the top modules
     *
     * @param int $limit The number of modules to return.
     *
     * @return array An array of results.
     */
    public function findTopModules($limit = 5)
    {
        return $this->select(['module', 'COUNT(module) AS activity_count'])
            ->group_by('module')
            ->where("{$this->table_name}.{$this->deleted_field}", 0)
            ->limit($limit)
            ->order_by('activity_count', 'desc')
            ->find_all();
    }

    /**
     * Find the top users
     *
     * @param Number $limit The number of users to return
     *
     * @return Array    An array of results
     */
    public function findTopUsers($limit = 5)
    {
        if (! class_exists('user_model', false)) {
            $this->load->model('users/user_model');
        }

        // Fields in the select list must also be in the group by clause for
        // compatibility with most databases other than MySQL.
        $usersFields = ['username', 'user_id'];

        return $this->select(array_merge($usersFields, ['COUNT(user_id) AS activity_count']))
            ->where("{$this->table_name}.{$this->deleted_field}", 0)
            ->join(
                $this->user_model->get_table(),
                "{$this->table_name}.user_id = " . $this->user_model->get_table() . '.' . $this->user_model->get_key(),
                'left'
            )
            ->group_by($usersFields)
            ->order_by('activity_count', 'desc')
            ->limit($limit)
            ->find_all();
    }

    /**
     * Log an activity.
     *
     * @param int    $user_id  An int id of the user that performed the activity.
     * @param string $activity A string detailing the activity. Max length of 255 chars.
     * @param string $module   The name of the module that set the activity.
     *
     * @return bool An int with the ID of the new object, or FALSE on failure.
     */
    public function log_activity($user_id = null, $activity = '', $module = 'any')
    {
        if (is_null($user_id) || ! is_integer($user_id) || $user_id < 0) {
            $this->error = lang('activities_log_no_user_id');
            return false;
        }

        if (empty($activity)) {
            $this->error = lang('activities_log_no_activity');
            return false;
        }

        return $this->insert(
            [
                'user_id'  => $user_id,
                'activity' => $activity,
                'module'   => $module,
            ]
        );
    }
}
