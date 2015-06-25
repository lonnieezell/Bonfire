<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * User Model.
 *
 * The central way to access and perform CRUD on users.
 *
 * @package Bonfire\Modules\Users\Models\User_model
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer
 */
class User_model extends BF_Model
{
    /** @var string Name of the users table. */
    protected $table_name = 'users';

    /** @var string Name of the user meta table. */
    protected $meta_table = 'user_meta';

    /** @var string Name of the roles table. */
    protected $roles_table = 'roles';

    /** @var boolean Use soft deletes or not. */
    protected $soft_deletes = true;

    /** @var string The date format to use. */
    protected $date_format = 'datetime';

    /** @var boolean Set the modified time automatically. */
    protected $set_modified = false;

    /** @var boolean Skip the validation. */
    protected $skip_validation = true;

    /** @var array Validation rules. */
    protected $validation_rules = array(
        array(
            'field' => 'password',
            'label' => 'lang:bf_password',
            'rules' => 'max_length[120]|valid_password|matches[pass_confirm]',
        ),
        array(
            'field' => 'pass_confirm',
            'label' => 'lang:bf_password_confirm',
            'rules' => '',
        ),
        array(
            'field' => 'display_name',
            'label' => 'lang:bf_display_name',
            'rules' => 'trim|max_length[255]',
        ),
        array(
            'field' => 'language',
            'label' => 'lang:bf_language',
            'rules' => 'required|trim',
        ),
        array(
            'field' => 'timezones',
            'label' => 'lang:bf_timezone',
            'rules' => 'required|trim|max_length[40]',
        ),
        array(
            'field' => 'username',
            'label' => 'lang:bf_username',
            'rules' => 'trim|max_length[30]',
        ),
        array(
            'field' => 'email',
            'label' => 'lang:bf_email',
            'rules' => 'required|trim|valid_email|max_length[254]',
        ),
        array(
            'field' => 'role_id',
            'label' => 'lang:us_role',
            'rules' => 'trim|max_length[2]|is_numeric',
        ),
    );

    /** @var Array Additional validation rules only used on insert. */
    protected $insert_validation_rules = array(
        array(
            'field' => 'password',
            'label' => 'lang:bf_password',
            'rules' => 'required',
        ),
        array(
            'field' => 'pass_confirm',
            'label' => 'lang:bf_password_confirm',
            'rules' => 'required',
        ),
    );

    /** @var array Metadata for the model's database fields. */
    protected $field_info = array(
        array('name' => 'id', 'primary_key' => 1),
        array('name' => 'created_on'),
        array('name' => 'deleted'),
        array('name' => 'role_id'),
        array('name' => 'email'),
        array('name' => 'username'),
        array('name' => 'password_hash'),
        array('name' => 'reset_hash'),
        array('name' => 'last_login'),
        array('name' => 'last_ip'),
        array('name' => 'banned'),
        array('name' => 'ban_message'),
        array('name' => 'reset_by'),
        array('name' => 'display_name'),
        array('name' => 'display_name_changed'),
        array('name' => 'timezone'),
        array('name' => 'language'),
        array('name' => 'active'),
        array('name' => 'activate_hash'),
        array('name' => 'force_password_reset'),
    );

    //--------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    //--------------------------------------------------------------------------
    // CRUD Method Overrides.
    //--------------------------------------------------------------------------

    /**
     * Count the users in the system.
     *
     * @param boolean $get_deleted If true, count users which have been deleted,
     * else count users which have not been deleted.
     *
     * @return integer The number of users found.
     */
    public function count_all($get_deleted = false)
    {
        if ($get_deleted) {
            // Get only the deleted users
            $this->db->where("{$this->table_name}.deleted !=", 0);
        } else {
            $this->db->where("{$this->table_name}.deleted", 0);
        }

        return $this->db->count_all_results($this->table_name);
    }

    /**
     * Perform a standard delete, but also allow a record to be purged.
     *
     * @param integer $id    The ID of the user to delete.
     * @param boolean $purge If true, the account will be purged from the system.
     * If false, performs a standard delete (with soft-deletes enabled).
     *
     * @return boolean True on success, else false.
     */
    public function delete($id = 0, $purge = false)
    {
        // Temporarily store the current setting for soft-deletes.
        $tempSoftDeletes = $this->soft_deletes;
        if ($purge === true) {
            // Temporarily set the soft_deletes to false to purge the account.
            $this->soft_deletes = false;
        }

        // Reset soft-deletes after deleting the account.
        $result = parent::delete($id);
        $this->soft_deletes = $tempSoftDeletes;

        return $result;
    }

    /**
     * Find a user's record and role information.
     *
     * @param integer $id The user's ID.
     *
     * @return boolean|object An object with the user's information.
     */
    public function find($id = null)
    {
        $this->preFind();

        return parent::find($id);
    }

    /**
     * Find all user records and the associated role information.
     *
     * @return boolean An array of objects with each user's information.
     */
    public function find_all()
    {
        $this->preFind();

        return parent::find_all();
    }

    /**
     * Find a single user based on a field/value match, including role information.
     *
     * @param string $field The field to match. If 'both', attempt to find a user
     * with the $value field matching either the username or email.
     * @param string $value The value to search for.
     * @param string $type  The type of where clause to create ('and' or 'or').
     *
     * @return boolean|object An object with the user's info, or false on failure.
     */
    public function find_by($field = null, $value = null, $type = 'and')
    {
        $this->preFind();

        return parent::find_by($field, $value, $type);
    }

    /**
     * Create a new user in the database.
     *
     * @param array $data An array of user information. 'password' and either 'email'
     * or 'username' are required, depending on the 'auth.use_usernames' setting.
     * 'email' or 'username' must be unique. If 'role_id' is not included, the default
     * role from the Roles model will be assigned.
     *
     * @return boolean|integer The ID of the new user on success, else false.
     */
    public function insert($data = array())
    {
        // If 'display_name' is not provided, set it to 'username' or 'email'.
        if (empty($data['display_name'])) {
            if ($this->settings_lib->item('auth.use_usernames') == 1
                && ! empty($data['username'])
            ) {
                $data['display_name'] = $data['username'];
            } else {
                $data['display_name'] = $data['email'];
            }
        }

        // Hash the password.
        $password = $this->auth->hash_password($data['password']);
        if (empty($password) || empty($password['hash'])) {
            return false;
        }

        $data['password_hash'] = $password['hash'];

        unset($data['password'], $password);

        // Get the default role if the role_id was not provided.
        if (! isset($data['role_id'])) {
            if (! class_exists('role_model', false)) {
                $this->load->model('roles/role_model');
            }
            $data['role_id'] = $this->role_model->default_role_id();
        }

        $id = parent::insert($data);
        Events::trigger('after_create_user', $id);

        return $id;
    }

    /**
     * Update an existing user. Before saving, it will:
     * - Generate a new password/hash if both password and pass_confirm are provided.
     * - Store the country code.
     *
     * @param integer $id   The user's ID.
     * @param array $data An array of key/value pairs to update for the user.
     *
     * @return boolean True if the update succeeded, null on invalid $id, or false
     * on failure.
     */
    public function update($id = null, $data = array())
    {
        if (empty($id)) {
            return null;
        }

        $trigger_data = array(
            'user_id' => $id,
            'data'    => $data,
        );
        Events::trigger('before_user_update', $trigger_data);

        // If the password is provided, hash it.
        if (! empty($data['password'])) {
            $password = $this->auth->hash_password($data['password']);
            if (empty($password) || empty($password['hash'])) {
                return false;
            }

            $data['password_hash'] = $password['hash'];

            unset($data['password'], $password);
        }

        // If the country is passed as 'iso', change it to 'country_iso'.
        if (isset($data['iso'])) {
            $data['country_iso'] = $data['iso'];
            unset($data['iso']);
        }

        $result = parent::update($id, $data);
        if ($result) {
            $trigger_data = array(
                'user_id' => $id,
                'data'    => $data,
            );
            Events::trigger('after_user_update', $trigger_data);
        }

        return $result;
    }

    //--------------------------------------------------------------------------
    // Other BF_Model Method Overrides.
    //--------------------------------------------------------------------------

    /**
     * Extracts the model's fields (except the key and those handled by observers)
     * from the $post_data and returns an array of name => value pairs.
     *
     * @param array $post_data The post data, usually $this->input->post() when
     * called from the controller.
     *
     * @return array An array of name => value pairs containing the prepared data
     * for the model's fields.
     */
    public function prep_data($post_data)
    {
        // Take advantage of BF_Model's prep_data() method.
        $data = parent::prep_data($post_data);

        // Special handling of the data specific to the User_model.

        // Only set 'timezone' if one was selected from the 'timezones' select.
        if (! empty($post_data['timezones'])) {
            $data['timezone'] = $post_data['timezones'];
        }

        // Only set 'password' if a value was provided (so the user's profile can
        // be updated without changing the password).
        if (! empty($post_data['password'])) {
            $data['password'] = $post_data['password'];
        }

        if ($data['display_name'] === '') {
            unset($data['display_name']);
        }

        // Convert actions to the proper values.
        if (isset($post_data['restore']) && $post_data['restore']) {
            // 'restore': unset the soft-delete flag.
            $data['deleted'] = 0;
        }
        if (isset($post_data['unban']) && $post_data['unban']) {
            // 'unban': unset the banned flag.
            $data['banned'] = 0;
        }
        if (isset($post_data['activate']) && $post_data['activate']) {
            // 'activate': set the 'active' flag.
            $data['active'] = 1;
        } elseif (isset($post_data['deactivate']) && $post_data['deactivate']) {
            // 'deactivate': unset the 'active' flag.
            $data['active'] = 0;
        }

        return $data;
    }

    // -------------------------------------------------------------------------
    // Roles
    // -------------------------------------------------------------------------

    /**
     * Count the number of users that belong to each role.
     *
     * @return boolean|array An array of objects with the name of each role and
     * the number of users in that role, else false.
     */
    public function count_by_roles()
    {
        $this->db->select(array("{$this->roles_table}.role_name", 'count(1) as count'))
                 ->from($this->table_name)
                 ->join($this->roles_table, "{$this->roles_table}.role_id = {$this->table_name}.role_id", 'left')
                 ->group_by("{$this->roles_table}.role_name");

        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result();
        }

        return false;
    }

    /**
     * Update all users with the current role to have the default role.
     *
     * @param integer $current_role The ID of the role of users which will be set
     * to the default role.
     *
     * @return boolean True on successful update, else false.
     */
    public function set_to_default_role($current_role)
    {
        // Is the $current_role the right data type?
        if (! is_int($current_role)) {
            return false;
        }

        // Get the default role ID.
        if (! class_exists('role_model', false)) {
            $this->load->model('roles/role_model');
        }
        $defaultRoleId = $this->role_model->default_role_id();

        $this->db->where('role_id', $current_role);
        $query = $this->db->update(
            $this->table_name,
            array('role_id' => $defaultRoleId)
        );

        return (bool) $query;
    }

    //--------------------------------------------------------------------------
    // Password Methods.
    //--------------------------------------------------------------------------

    /**
     * Flag one or more user accounts to require a password reset on the user's
     * next login.
     *
     * @param integer $user_id The ID of the user to flag for password reset.
     *
     * @return boolean True if the account was updated successfully, else false.
     */
    public function force_password_reset($user_id = null)
    {
        if (! empty($user_id) && is_numeric($user_id)) {
            $this->db->where('id', $user_id);
        }

        return $this->db->set('force_password_reset', 1)->update($this->table_name);
    }

    /**
     * Generates a new password hash for the given password.
     *
     * @param string $old The password to hash.
     * @param integer $iterations    The number of iterations to use in generating the hash
     *
     * @return array An array with the hashed password and the number of iterations, or false
     */
    public function hash_password($old = '', $iterations = 0)
    {
        $password = $this->auth->hash_password($old, $iterations);
        if (empty($password) || empty($password['hash'])) {
            return false;
        }

        return array($password['hash'], $password['iterations']);
    }

    /**
     * Helper Method for Generating Password Hints based on Settings library.
     *
     * Call this method in the controller and echo $password_hints in the view.
     *
     * @return void
     */
    public function password_hints()
    {
        $message = array(
            sprintf(
                lang('bf_password_min_length_help'),
                (string) $this->settings_lib->item('auth.password_min_length')
            )
        );

        if ($this->settings_lib->item('auth.password_force_numbers') == 1) {
            $message[] = lang('bf_password_number_required_help');
        }

        if ($this->settings_lib->item('auth.password_force_symbols') == 1) {
            $message[] = lang('bf_password_symbols_required_help');
        }

        if ($this->settings_lib->item('auth.password_force_mixed_case') == 1) {
            $message[] = lang('bf_password_caps_required_help');
        }

        Template::set('password_hints', implode('<br />', $message));

        unset($message);
    }

    //--------------------------------------------------------------------------
    // !META METHODS
    //--------------------------------------------------------------------------

    /**
     * Retrieve all meta values defined for a user.
     *
     * @param integer $user_id The ID of the user for which the meta will be retrieved.
     * @param array   $fields  The meta_key names to retrieve.
     *
     * @return stdClass An object with the key/value pairs, or an empty object.
     */
    public function find_meta_for($user_id = null, $fields = null)
    {
        // Is $user_id the right data type?
        if (! is_numeric($user_id)) {
            $this->error = lang('us_invalid_user_id');
            return new stdClass();
        }

        // Limiting to certain fields?
        if (! empty($fields) && is_array($fields)) {
            $this->db->where_in('meta_key', $fields);
        }

        $query = $this->db->where('user_id', $user_id)
                          ->get($this->meta_table);
        if (! $query->num_rows()) {
            return new stdClass();
        }

        $result = new stdClass();
        foreach ($query->result() as $row) {
            $key = $row->meta_key;
            $result->$key = $row->meta_value;
        }

        return $result;
    }

    /**
     * Locate a single user and the user's meta information.
     *
     * @param integer $user_id The ID of the user to fetch.
     *
     * @return boolean|object An object with the user's profile and meta information,
     * or false on failure.
     */
    public function find_user_and_meta($user_id = null)
    {
        // Is $user_id the right data type?
        if (! is_numeric($user_id)) {
            $this->error = lang('us_invalid_user_id');
            return false;
        }

        // Does a user with this $user_id exist?
        $result = $this->find($user_id);
        if (! $result) {
            $this->error = lang('us_invalid_user_id');
            return false;
        }

        // Get the meta data for this user and join it to the user profile data.
        $this->db->where('user_id', $user_id);
        $query = $this->db->get($this->meta_table);
        if ($query->num_rows()) {
            foreach ($query->result() as $row) {
                $key = $row->meta_key;
                $result->$key = $row->meta_value;
            }
        }

        return $result;
    }

    /**
     * Save one or more key/value pairs of meta information for a user.
     *
     * @example
     * $data = array(
     *    'location'    => 'That City, Katmandu',
     *    'interests'   => 'My interests'
     * );
     * $this->user_model->save_meta_for($user_id, $data);
     *
     * @param integer $user_id The ID of the user for which to save the meta data.
     * @param array   $data    An array of key/value pairs to save.
     *
     * @return boolean True on success, else false.
     */
    public function save_meta_for($user_id = null, $data = array())
    {
        // Is $user_id the right data type?
        if (! is_numeric($user_id)) {
            $this->error = lang('us_invalid_user_id');
            return false;
        }

        // If there's no data, get out of here.
        if (empty($data)) {
            return true;
        }

        $result = false;
        $successCount = 0;
        foreach ($data as $key => $value) {
            $obj = array(
                'meta_key'   => $key,
                'meta_value' => $value,
                'user_id'    => $user_id,
            );
            $where = array(
                'meta_key' => $key,
                'user_id'  => $user_id,
            );

            // Determine whether the data needs to be updated or inserted.
            $query = $this->db->where($where)
                              ->get($this->meta_table);
            if ($query->num_rows()) {
                $result = $this->db->update($this->meta_table, $obj, $where);
            } else {
                $result = $this->db->insert($this->meta_table, $obj);
            }

            // Count the number of successful insert/update results.
            if ($result) {
                ++$successCount;
            }
        }

        if ($successCount == count($data)) {
            return true;
        }

        return false;
    }

    //--------------------------------------------------------------------------
    // !ACTIVATION
    //--------------------------------------------------------------------------

    /**
     * Accepts an activation code and validates against a matching entry in the database.
     *
     * There are some instances where the activation hash should be removed but
     * the user should be left inactive (e.g. Admin Activation), so $leave_inactive
     * enables that use case.
     *
     * @param int    $user_id        The user to be activated (null will match any).
     * @param string $code           The activation code to be verified.
     * @param bool   $leave_inactive Flag whether to remove the activate hash value,
     * but leave active = 0.
     *
     * @return int User Id on success, false on error.
     */
    public function activate($user_id, $code, $leave_inactive = false)
    {
        if ($user_id) {
            $this->db->where('id', $user_id);
        }

        $query = $this->db->select('id')
                          ->where('activate_hash', $code)
                          ->get($this->table_name);

        if ($query->num_rows() !== 1) {
            $this->error = lang('us_err_no_matching_code');
            return false;
        }

        // Now we can find the $user_id, even if it was passed as NULL
        $result = $query->row();
        $user_id = $result->id;

        $active = $leave_inactive === false ? 1 : 0;
        if ($this->update($user_id, array('activate_hash' => '', 'active' => $active))) {
            return $user_id;
        }

        return false;
    }

    /**
     * This function is triggered during account setup to ensure user is not active
     * and, if not supressed, generate an activation hash code. This function can
     * be used to deactivate accounts based on public view events.
     *
     * @param int    $user_id    The username or email to match to deactivate
     * @param string $login_type Login Method
     * @param bool   $make_hash  Create a hash
     *
     * @return mixed $activate_hash on success, false on error
     */
    public function deactivate($user_id, $make_hash = true)
    {
        // create a temp activation code.
        $activate_hash = '';
        if ($make_hash === true) {
            $this->load->helper('string');
            $activate_hash = sha1(random_string('alnum', 40) . time());
        }

        $this->db->update(
            $this->table_name,
            array('active' => 0, 'activate_hash' => $activate_hash),
            array('id' => $user_id)
        );

        if ($this->db->affected_rows() != 1) {
            return false;
        }

        return $make_hash ? $activate_hash : true;
    }

    /**
     * Admin specific activation function for admin approvals or re-activation.
     *
     * @param int $user_id The user ID to activate.
     *
     * @return bool True on success, false on error.
     */
    public function admin_activation($user_id = false)
    {
        if ($user_id === false) {
            $this->error = lang('us_err_no_id');
            return false;
        }

        $query = $this->db->select('id')
                      ->where('id', $user_id)
                      ->limit(1)
                      ->get($this->table_name);

        if ($query->num_rows() !== 1) {
            $this->error = lang('us_err_no_matching_id');
            return false;
        }

        $result = $query->row();

        $this->update($result->id, array('activate_hash' => '', 'active' => 1));
        if ($this->db->affected_rows() > 0) {
            return $result->id;
        }

            $this->error = lang('us_err_user_is_active');
        return false;
    }

    /**
     * Admin only deactivation function.
     *
     * @param int $user_id The user ID to deactivate.
     *
     * @return boolean True on success, false on error.
     */
    public function admin_deactivation($user_id = false)
    {
        if ($user_id === false) {
            $this->error = lang('us_err_no_id');
            return false;
        }

        if ($this->deactivate($user_id, 'id', false)) {
            return $user_id;
        }

            $this->error = lang('us_err_user_is_inactive');
        return false;
    }

    /**
     * Count Inactive users.
     *
     * @return int Inactive user count.
     */
    public function count_inactive_users()
    {
        $this->db->where('active', -1);
        return $this->count_all(false);
    }

    /**
     * Configure activation for the given user based on current user_activation_method.
     *
     * @param number $user_id User's ID.
     *
     * @return array A 'message' (string) and 'error' (boolean, true if an error
     * occurred sending the activation email).
     */
    public function set_activation($user_id)
    {
        // User activation method
        $activation_method = $this->settings_lib->item('auth.user_activation_method');

        // Prepare user messaging vars
        $emailMsgData   = array();
        $emailView      = '';
        $subject        = '';
        $email_mess     = '';
        $message        = lang('us_email_thank_you');
        $type           = 'success';
        $site_title     = $this->settings_lib->item('site.title');
        $error          = false;
        $ccAdmin      = false;

        switch ($activation_method) {
            case 0:
                // No activation required.
                // Activate the user and send confirmation email.
                $subject = str_replace(
                    '[SITE_TITLE]',
                    $this->settings_lib->item('site.title'),
                    lang('us_account_reg_complete')
                );

                $emailView  = '_emails/activated';
                $message    .= lang('us_account_active_login');

                $emailMsgData = array(
                    'title' => $site_title,
                    'link'  => site_url(),
                );
                break;
            case 1:
                // Email Activiation.
                // Run the account deactivate to assure everything is set correctly.
                $activation_code    = $this->deactivate($user_id);

                // Create the link to activate membership
                $activate_link = site_url("activate/{$user_id}");
                $subject            =  lang('us_email_subj_activate');
                $emailView          = '_emails/activate';
                $message            .= lang('us_check_activate_email');

                $emailMsgData = array(
                    'title' => $site_title,
                    'code'  => $activation_code,
                    'link'  => $activate_link
                );
                break;
            case 2:
                // Admin Activation.
                $ccAdmin   = true;
                $subject    =  lang('us_email_subj_pending');
                $emailView  = '_emails/pending';
                $message    .= lang('us_admin_approval_pending');

                $emailMsgData = array(
                    'title' => $site_title,
                );
                break;
        }

        $email_mess = $this->load->view($emailView, $emailMsgData, true);

        // Now send the email
        $this->load->library('emailer/emailer');
        $data = array(
            'to'        => $this->find($user_id)->email,
            'subject'   => $subject,
            'message'   => $email_mess,
        );

        if ($this->emailer->send($data)) {
            // If the message was sent successfully and the admin must be notified
            // (Admin Activation is enabled), send another email to the system_email.
            if ($ccAdmin) {
                /**
                 * @todo Add a setting to allow the user to change the email address
                 * of the recipient of this message.
                 *
                 * @todo Add CC/BCC capabilities to emailer, so this doesn't require
                 * sending a second email.
                 */
                $data['to'] = $this->settings_lib->item('system_email');
                if (! empty($data['to'])) {
                    $this->emailer->send($data);
                }
            }
        } else {
            // If the message was not sent successfully, set an error message.
            $message    .= lang('us_err_no_email') . $this->emailer->error;
            $error      = true;
        }

        return array('message' => $message, 'error' => $error);
    }

    // -------------------------------------------------------------------------
    // Misc. Methods.
    // -------------------------------------------------------------------------

    /**
     * Return the most recent login attempts.
     *
     * @param integer $limit The maximum number of results to return.
     *
     * @return boolean|array An array of objects with the login attempts, or false.
     */
    public function get_login_attempts($limit = 15)
    {
        $this->db->limit($limit)
                 ->order_by('login', 'desc');

        $query = $this->db->get('login_attempts');
        if ($query->num_rows()) {
            return $query->result();
        }

        return false;
    }

    /**
     * Set the select and join portions of the SQL query for the find* methods.
     *
     * @todo Set this in the before_find observer?
     *
     * @return void
     */
    protected function preFind()
    {
        if (empty($this->selects)) {
            $this->select("{$this->table_name}.*, role_name");
        }

        $this->db->join(
            $this->roles_table,
            "{$this->roles_table}.role_id = {$this->table_name}.role_id",
            'left'
        );
    }
}
//end User_model
