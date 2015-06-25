<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
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
 * Users Settings Controller.
 *
 * Manages the user functionality on the admin pages.
 *
 * @package    Bonfire\Modules\Users\Controllers\Settings
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/developer/roles_and_permissions
 *
 */
class Settings extends Admin_Controller
{
    private $siteSettings;

    private $permissionCreate = 'Bonfire.Users.Add';
    private $permissionManage = 'Bonfire.Users.Manage';
    private $permissionView   = 'Bonfire.Users.View';

    /**
     * Setup the required permissions.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->auth->restrict($this->permissionView);

        $this->lang->load('users');
        $this->load->model('roles/role_model');

        $this->siteSettings = $this->settings_lib->find_all();
        if ($this->siteSettings['auth.password_show_labels'] == 1) {
            Assets::add_module_js('users', 'password_strength.js');
            Assets::add_module_js('users', 'jquery.strength.js');
        }

        Template::set_block('sub_nav', 'users/settings/_sub_nav');
    }

    /*
     * Display the user list and manage the user deletions/banning/purge.
     *
     * @param string $filter The filter to apply to the list.
     * @param int    $offset The offset from which the list will start.
     *
     * @return  void
     */
    public function index($filter = 'all', $offset = 0)
    {
        $this->auth->restrict($this->permissionManage);

        // Fetch roles for the filter and the list.
        $roles = $this->role_model->select('role_id, role_name')
                                  ->where('deleted', 0)
                                  ->order_by('role_name', 'asc')
                                  ->find_all();
        $orderedRoles = array();
        foreach ($roles as $role) {
            $orderedRoles[$role->role_id] = $role;
        }
        Template::set('roles', $orderedRoles);

        // Perform any actions?
        foreach (array('restore', 'purge', 'delete', 'ban', 'deactivate', 'activate') as $act) {
            if (isset($_POST[$act])) {
                $action = "_{$act}";
                break;
            }
        }

        // If an action was found, get the checked users and perform the action.
        if (isset($action)) {
            $checked = $this->input->post('checked');
            if (empty($checked)) {
                // No users checked.
                Template::set_message(lang('us_empty_id'), 'error');
            } else {
                foreach ($checked as $userId) {
                    $this->$action($userId);
                }
            }
        }

        // Actions done, now display the view.
        $where = array('users.deleted' => 0);

        // Filters
        if (preg_match('{first_letter-([A-Z])}', $filter, $matches)) {
            $filterType = 'first_letter';
            $firstLetter = $matches[1];
            Template::set('first_letter', $firstLetter);
        } elseif (preg_match('{role_id-([0-9]*)}', $filter, $matches)) {
            $filterType = 'role_id';
            $roleId = (int) $matches[1];
        } else {
            $filterType = $filter;
        }

        switch ($filterType) {
            case 'inactive':
                $where['users.active'] = 0;
                break;
            case 'banned':
                $where['users.banned'] = 1;
                break;
            case 'deleted':
                $where['users.deleted'] = 1;
                break;
            case 'role_id':
                $where['users.role_id'] = $roleId;
                foreach ($roles as $role) {
                    if ($role->role_id == $roleId) {
                        Template::set('filter_role', $role->role_name);
                        break;
                    }
                }
                break;
            case 'first_letter':
                // @todo Determine whether this needs to be changed to become
                // usable with databases other than MySQL
                $where['SUBSTRING( LOWER(username), 1, 1)='] = $firstLetter;
                break;
            case 'all':
                // Nothing to do
                break;
            default:
                // Unknown/bad $filterType
                show_404("users/index/$filter/");
        }

        // Fetch the users to display
        $this->user_model->limit($this->limit, $offset)
                         ->where($where)
                         ->select(
                             array(
                                'users.id',
                                'users.role_id',
                                'username',
                                'display_name',
                                'email',
                                'last_login',
                                'banned',
                                'active',
                                'users.deleted',
                                'role_name',
                             )
                         );
        Template::set('users', $this->user_model->find_all());

        // Used as the view's index_url and the base for the pager's base_url.
        $indexUrl = site_url(SITE_AREA . '/settings/users/index') . '/';
        Template::set('index_url', $indexUrl);

        // Pagination
        $this->load->library('pagination');

        $this->pager['base_url']    = "{$indexUrl}{$filter}/";
        $this->pager['per_page'] = $this->limit;
        $this->pager['total_rows']  = $this->user_model->where($where)->count_all();
        $this->pager['uri_segment'] = 6;

        $this->pagination->initialize($this->pager);

        Template::set('filter_type', $filterType);
        Template::set('toolbar_title', lang('us_user_management'));

        Template::render();
    }

    /**
     * Create a new user.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);

        $this->load->config('address');
        $this->load->helper('address');
        $this->load->helper('date');

        $this->load->config('user_meta');
        $metaFields = config_item('user_meta_fields');
        Template::set('meta_fields', $metaFields);

        if (isset($_POST['save'])) {
            if ($id = $this->saveUser('insert', null, $metaFields)) {
                $user = $this->user_model->find($id);
                $logName = empty($user->display_name) ? ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email) : $user->display_name;
                log_activity(
                    $this->auth->user_id(),
                    sprintf(lang('us_log_create'), $user->role_name) . ": {$logName}",
                    'users'
                );
                Template::set_message(lang('us_user_created_success'), 'success');

                redirect(SITE_AREA . '/settings/users');
            }
        }

        if ($this->siteSettings['auth.password_show_labels'] == 1) {
            Assets::add_js(
                $this->load->view('users_js', array('settings' => $this->siteSettings), true),
                'inline'
            );
        }

        Template::set(
            'roles',
            $this->role_model->select('role_id, role_name, default')
                             ->where('deleted', 0)
                             ->order_by('role_name', 'asc')
                             ->find_all()
        );
        Template::set('languages', unserialize($this->settings_lib->item('site.languages')));
        Template::set('toolbar_title', lang('us_create_user'));

        Template::set_view('users/settings/user_form');
        Template::render();
    }

    /**
     * Edit a user.
     *
     * @param number/string $userId The ID of the user to edit. If empty, the
     * current user will be displayed/edited.
     *
     * @return void
     */
    public function edit($userId = '')
    {
        $this->load->config('address');
        $this->load->helper('address');
        $this->load->helper('date');

        // If no id is passed in, edit the current user.
        if (empty($userId)) {
            $userId = $this->auth->user_id();
        }

        if (empty($userId)) {
            Template::set_message(lang('us_empty_id'), 'error');

            redirect(SITE_AREA . '/settings/users');
        }

        if ($userId != $this->auth->user_id()) {
            $this->auth->restrict($this->permissionManage);
        }

        $this->load->config('user_meta');
        $metaFields = config_item('user_meta_fields');
        Template::set('meta_fields', $metaFields);

        $user = $this->user_model->find_user_and_meta($userId);

        if (isset($_POST['save'])) {
            if ($this->saveUser('update', $userId, $metaFields, $user->role_name)) {
                $user = $this->user_model->find_user_and_meta($userId);
                $logName = empty($user->display_name) ? ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email) : $user->display_name;
                log_activity(
                    $this->auth->user_id(),
                    lang('us_log_edit') . ": {$logName}",
                    'users'
                );
                Template::set_message(lang('us_user_update_success'), 'success');

                // Redirect to the edit page to ensure that a password change
                // forces a login check.
                redirect($this->uri->uri_string());
            }
        }

        if (! isset($user)) {
            Template::set_message(
                sprintf(lang('us_unauthorized'), $user->role_name),
                'error'
            );

            redirect(SITE_AREA . '/settings/users');
        }

        if ($this->siteSettings['auth.password_show_labels'] == 1) {
            Assets::add_js(
                $this->load->view('users_js', array('settings' => $this->siteSettings), true),
                'inline'
            );
        }

        Template::set(
            'roles',
            $this->role_model->select('role_id, role_name, default')
                             ->where('deleted', 0)
                             ->order_by('role_name', 'asc')
                             ->find_all()
        );
        Template::set('user', $user);
        Template::set('languages', unserialize($this->settings_lib->item('site.languages')));
        Template::set('toolbar_title', lang('us_edit_user'));

        Template::set_view('users/settings/user_form');
        Template::render();
    }

    /**
     * Force all users to require a password reset on their next login.
     *
     * Intended to be used as an AJAX function.
     *
     * @return void
     */
    public function force_password_reset_all()
    {
        $this->auth->restrict($this->permissionManage);

        if ($this->user_model->force_password_reset()) {
            // Resets are in place, so log the user out.
            $this->auth->logout();

            Template::redirect(LOGIN_URL);
        } else {
            Template::redirect($this->previous_page);
        }
    }

    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Ban a user or group of users.
     *
     * @param int    $userId     User to ban
     * @param string $banMessage Set a message indicating the reason the user
     * was banned.
     *
     * @return void
     */
    private function _ban($userId, $banMessage = '')
    {
        $this->user_model->update(
            $userId,
            array(
            'banned'        => 1,
                'ban_message' => $banMessage
            )
        );
    }

    /**
     * Delete a user or group of users.
     *
     * @param int $id User to delete.
     *
     * @return void
     */
    private function _delete($id)
    {
        $user = $this->user_model->find($id);
        if (! isset($user)) {
            Template::set_message(lang('us_invalid_user_id'), 'error');
            redirect(SITE_AREA . '/settings/users');
        }

        if ($user->id == $this->auth->user_id()) {
            Template::set_message(lang('us_self_delete'), 'error');
            redirect(SITE_AREA . '/settings/users');
        }

        if (! has_permission("Permissions.{$user->role_name}.Manage")) {
            Template::set_message(sprintf(lang('us_unauthorized'), $user->role_name), 'error');
            redirect(SITE_AREA . '/settings/users');
        }

        if ($this->user_model->delete($id)) {
                $user = $this->user_model->find($id);
            $logName = empty($user->display_name) ? ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email) : $user->display_name;
            log_activity(
                $this->auth->user_id(),
                lang('us_log_delete') . ": {$logName}",
                'users'
            );
                Template::set_message(lang('us_action_deleted'), 'success');
        } elseif (! empty($this->user_model->error)) {
            Template::set_message(lang('us_action_not_deleted') . $this->user_model->error, 'error');
        }
    }

    /**
     * Purge the selected users which are already marked as deleted.
     *
     * @param int $id User to purge
     *
     * @return void
     */
    private function _purge($id)
    {
        $this->user_model->delete($id, true);
        Template::set_message(lang('us_action_purged'), 'success');

        // Purge any user meta for this user, also.
        $this->db->where('user_id', $id)->delete('user_meta');

        // Any modules needing to save data?
        Events::trigger('purge_user', $id);
    }

    /**
     * Restore the deleted user.
     *
     * @param number $id The ID of the user to restore.
     *
     * @return void
     */
    private function _restore($id)
    {
        if ($this->user_model->update($id, array('users.deleted' => 0))) {
            Template::set_message(lang('us_user_restored_success'), 'success');
        } elseif (! empty($this->user_model->error)) {
            Template::set_message(lang('us_user_restored_error') . $this->user_model->error, 'error');
        }
    }

    /**
     * Save the user.
     *
     * @param string $type            The type of operation (insert or edit).
     * @param int    $id              The id of the user (ignored on insert).
     * @param array  $metaFields      Array of meta fields for the user.
     * @param string $currentRoleName The current role of the user being edited.
     *
     * @return bool/int The id of the inserted user or true on successful update.
     * False if the insert/update failed.
     */
    private function saveUser($type = 'insert', $id = 0, $metaFields = array(), $currentRoleName = '')
    {
        $this->form_validation->set_rules($this->user_model->get_validation_rules($type));

        $extraUniqueRule = '';
        $usernameRequired = '';

        if ($type != 'insert') {
            $_POST['id'] = $id;
            $extraUniqueRule = ',users.id';

            // If a value has been entered for the password, pass_confirm is required.
            // Otherwise, the pass_confirm field could be left blank and the form
            // validation would still pass.
            if ($this->input->post('password')) {
                $this->form_validation->set_rules(
                    'pass_confirm',
                    'lang:bf_password_confirm',
                    "required|matches[password]"
                );
            }
        }

        if ($this->settings_lib->item('auth.login_type') == 'username'
            || $this->settings_lib->item('auth.use_usernames')
           ) {
            $usernameRequired = 'required|';
        }

        $this->form_validation->set_rules('username', 'lang:bf_username', "{$usernameRequired}trim|max_length[30]|unique[users.username{$extraUniqueRule}]");
        $this->form_validation->set_rules('email', 'lang:bf_email', "required|trim|valid_email|max_length[254]|unique[users.email{$extraUniqueRule}]");

        if (has_permission($this->permissionManage)
            && has_permission("Permissions.{$currentRoleName}.Manage")
           ) {
            $this->form_validation->set_rules('role_id', 'lang:us_role', 'required|trim|max_length[2]|is_numeric');
        }

        $metaData = array();
        foreach ($metaFields as $field) {
            if (empty($field['admin_only'])
                || ($field['admin_only'] === true
                    && $this->auth->role_id() == 1
                   )
               ) {
                $this->form_validation->set_rules($field['name'], $field['label'], $field['rules']);
                $metaData[$field['name']] = $this->input->post($field['name']);
            }
        }

        // Setting the payload for Events system.
        $payload = array('user_id' => $id, 'data' => $this->input->post());

        // Event "before_user_validation" to run before the form validation.
        Events::trigger('before_user_validation', $payload);

        if ($this->form_validation->run() === false) {
            return false;
        }

        // Compile the core user elements to save.
        $data = $this->user_model->prep_data($this->input->post());

        $result = false;
        if ($type == 'insert') {
            $activationMethod = $this->settings_lib->item('auth.user_activation_method');

            // No activation method
            if ($activationMethod == 0) {
                // Activate the user automatically
                $data['active'] = 1;
            }

            $id = $this->user_model->insert($data);
            if (is_numeric($id)) {
                $result = $id;
            }
        } else {
            $result = $this->user_model->update($id, $data);
        }

        // Save any meta data for this user. Don't try to save meta data on a
        // failed insert ($id is not numeric).
        if (is_numeric($id) && ! empty($metaData)) {
            $this->user_model->save_meta_for($id, $metaData);
        }

        // Any modules needing to save data?
        $postData = $this->input->post();
        Events::trigger('save_user', $postData);

        return $result;
    }

    //--------------------------------------------------------------------------
    // ACTIVATION METHODS
    //--------------------------------------------------------------------------

    /**
     * Activate the selected user account.
     *
     * @param int $userId The ID of the user to activate.
     *
     * @return void
     */
    private function _activate($userId)
    {
        $this->setUserStatus($userId, 1, 0);
    }

    /**
     * Deactivate the selected user account.
     *
     * @param int $userId The ID of the user to deactivate.
     *
     * @return void
     */
    private function _deactivate($userId)
    {
        $this->setUserStatus($userId, 0, 0);
    }

    /**
     * Activate or deactivate a user from the users dashboard.
     *
     * @param int $userId        The ID of the user to activate/deactivate.
     * @param int $status        1 = Activate, -1 = Deactivate.
     * @param int $suppressEmail 1 = Suppress, All others = send email.
     *
     * @return void
     */
    private function setUserStatus($userId = false, $status = 1, $suppressEmail = 0)
    {
        if ($userId === false || $userId == -1) {
            Template::set_message(lang('us_err_no_id'), 'error');
            return;
        }

        $suppressEmail = isset($suppressEmail) && $suppressEmail == 1;
            $result = false;
            $type = '';

        // Set the user status (activate/deactivate the user).
        if ($status == 1) {
            $result = $this->user_model->admin_activation($userId);
                $type = lang('bf_action_activate');
        } else {
            $result = $this->user_model->admin_deactivation($userId);
                $type = lang('bf_action_deactivate');
            }

        if (! $result) {
            if (! empty($this->user_model->error)) {
                Template::set_message(lang('us_err_status_error') . $this->user_model->error, 'error');
            }
            return;
        }

        // If the status change succeeded, log the change and, if necessary,
        // send the user activation email.
        $user = $this->user_model->find($userId);
        $logName = $this->settings_lib->item('auth.use_own_names') ? $this->current_user->username
            : ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email);

        log_activity(
            $this->auth->user_id(),
            lang('us_log_status_change') . ": {$logName} : {$type}ed",
            'users'
        );

                $message = lang('us_active_status_changed');

        // If the user was activated and the email is not suppressed, send it.
        if ($status == 1 && ! $suppressEmail) {
            $this->load->library('emailer/emailer');
            $siteTitle = $this->settings_lib->item('site.title');

            $data = array(
                'to'      => $user->email,
                        'subject'   => lang('us_account_active'),
                'message' => $this->load->view('_emails/activated', array('link' => site_url(), 'title' => $siteTitle), true),
                    );

            if ($this->emailer->send($data)) {
                        $message = lang('us_active_email_sent');
            } else {
                $message = lang('us_err_no_email') . $this->emailer->error;
                    }
                }
                Template::set_message($message, 'success');
            }
}
/* End of file /users/controllers/settings.php */
