<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Role Settings Model
 *
 * Provides access and utility methods for handling role storage in the database.
 *
 * @package Bonfire\Modules\Roles\Models\Role_model
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/roles_and_permissions
 */
class Role_model extends BF_Model
{
    /** @var string Name of the table. */
    protected $table_name = 'roles';

    /** @var string Name of the primary key. */
    protected $key = 'role_id';

    /** @var bool Use soft deletes (if true). */
    protected $soft_deletes = true;

    /** @var string The date format to use. */
    protected $date_format = 'datetime';

    /** @var bool Set the created time automatically (if true). */
    protected $set_created = false;

    /** @var bool Set the modified time automatically (if true). */
    protected $set_modified = false;

    /**
     * @var array Validation rules. Note that role_name rules for updates are added
     * by this model's overridden get_validation_rules() method.
     */
    protected $validation_rules = [
        [
            'field' => 'description',
            'label' => 'lang:bf_description',
            'rules' => 'trim|max_length[255]',
        ],
        [
            'field' => 'login_destination',
            'label' => 'lang:role_login_destination',
            'rules' => 'trim|max_length[255]',
        ],
        [
            'field' => 'default_context',
            'label' => 'lang:role_default_context',
            'rules' => 'trim',
        ],
        [
            'field' => 'default',
            'label' => 'lang:role_default_role',
            'rules' => 'trim|is_numeric|max_length[1]',
        ],
        [
            'field' => 'can_delete',
            'label' => 'lang:role_can_delete_role',
            'rules' => 'trim|is_numeric|max_length[1]',
        ],
    ];

    /** @var array Additional validation rules only used on insert. */
    protected $insert_validation_rules = [
        [
            'field' => 'role_name',
            'label' => 'lang:role_name',
            'rules' => 'required|trim|max_length[60]|unique[roles.role_name]',
        ],
    ];

    protected $updateValidationRules = [
        [
            'field' => 'role_name',
            'label' => 'lang:role_name',
            'rules' => 'required|trim|max_length[60]|unique[roles.role_name,roles.role_id]',
        ],
    ];

    //--------------------------------------------------------------------------

    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns a single role, with an array of permissions.
     *
     * @param int $id The role_id of the role in question.
     *
     * @return bool|array An array of information about the role, along with a sub-array
     * containing the role's applicable permissions, or false.
     */
    public function find($id = null)
    {
        if (empty($id) || ! is_integer($id)) {
            return false;
        }

        $role = parent::find($id);
        if ($role == false) {
            return false;
        }

        $this->get_role_permissions($role);

        return $role;
    }

    /**
     * Locates a role based on the role name. Case insensitive.
     *
     * @param string $name A string with the name of the role.
     *
     * @return bool|object An object with the role and its permissions, or false.
     */
    public function find_by_name($name = null)
    {
        if (empty($name)) {
            return false;
        }

        $role = $this->find_by('role_name', $name);

        $this->get_role_permissions($role);

        return $role;
    }

    /**
     * Get the validation rules for the model.
     *
     * This override adds the role_name rule for updates.
     *
     * @uses $empty_validation_rules Observer to generate validation rules if
     * they are empty.
     *
     * @param string $type The type of validation rules to retrieve: 'update' or
     * 'insert'. If 'insert', appends rules set in $insert_validation_rules.
     *
     * @return array    The validation rules for the model or an empty array.
     */
    public function get_validation_rules($type = 'update')
    {
        if ($type != 'update') {
            return parent::get_validation_rules($type);
        }

        // When updating, add the role_name update rule.
        $validationRules = parent::get_validation_rules($type);
        $validationRules = array_merge($validationRules, $this->updateValidationRules);

        return $validationRules;
    }

    /**
     * A simple update of the role.
     *
     * Additionally, this cleans things up when setting this role as the default
     * role for new users.
     *
     * @param int   $id   The role id.
     * @param array $data Array of key/value pairs with which to update the db.
     *
     * @return bool True on successful update, else false.
     */
    public function update($id = null, $data = null)
    {
        // If this role is set to default, then set all others to NOT be default.
        if (isset($data['default']) && $data['default'] == 1) {
            $validate = $this->skip_validation;
            $this->skip_validation(true);
            parent::update(['default' => 1], ['default' => 0]);
            $this->skip_validation($validate);
        }

        return parent::update($id, $data);
    }

    /**
     * Verifies that a role can be deleted.
     *
     * @param int $role_id The role to verify.
     *
     * @return bool True if the role can be deleted, else false.
     */
    public function can_delete_role($role_id = 0)
    {
        $this->select('can_delete');
        $delete_role = parent::find($role_id);

        return $delete_role->can_delete == 1;
    }

    /**
     * Deletes a role.
     *
     * By default, it will perform a soft_delete and leave the permissions untouched.
     * However, if $purge == true, then all permissions related to this role are
     * also deleted.
     *
     * @param int  $id    An integer with the role_id to delete.
     * @param bool $purge If false, will perform a soft_delete. If true, will
     * remove the role and related permissions from db.
     *
     * @return bool True on successful delete, else false.
     */
    public function delete($id = 0, $purge = false)
    {
        // Can this role be deleted?
        if ($this->can_delete_role($id) == false) {
            $this->error = 'This role can not be deleted.';
            return false;
        }

        if ($this->default_role_id() == $id) {
            $this->error = 'The default role can not be deleted.';
            return false;
        }

        if ($purge === true) {
            // Temporarily disable soft deletes.
            $tempSoftDeletes = $this->soft_deletes;
            $this->soft_deletes = false;
        }

        // Get the role name for permission deletion later.
        $role = $this->role_model->find($id);

        // Delete the record.
        $deleted = parent::delete($id);
        if ($deleted === true) {
            // Update the users to the default role.
            if (! class_exists('user_model', false)) {
                $this->load->model('users/user_model');
            }
            $this->user_model->set_to_default_role($id);

            // Delete the role_permissions for this role.
            if (! class_exists('role_permission_model', false)) {
                $this->load->model('roles/role_permission_model');
            }
            $this->role_permission_model->delete_for_role($id);

            // Delete the manage permission for this role.
            $permission_name = 'Permissions.' . ucwords($role->role_name) . '.Manage';
            if (! class_exists('permission_model', false)) {
                $this->load->model('permissions/permission_model');
            }

            $perm = $this->permission_model->find_by('name', $permission_name);
            if ($perm) {
                // The permission_model's update/delete will remove the
                // role_permissions for this permission
                if ($purge === true) {
                    $this->permission_model->delete_by_name($permission_name);
                } else {
                    $this->permission_model->update(['name' => $permission_name], ['status' => 'inactive']);
                }
            }
        }

        // Restore soft_deletes
        if ($purge === true) {
            $this->soft_deletes = $tempSoftDeletes;
        }

        return $deleted;
    }

    /**
     * Returns the id of the default role.
     *
     * @return int|bool ID of the default role or false.
     */
    public function default_role_id()
    {
        $this->where('default', 1);
        $query = $this->db->get($this->table_name);

        $row = $query->row();
        if (empty($row) || ! isset($row->role_id)) {
            return false;
        }

        return (int) $row->role_id;
    }

    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Finds the permissions and role_permissions array for a single role.
     *
     * @param object $role A reference to an existing role object. This object is
     * modified directly.
     *
     * @return void
     */
    public function get_role_permissions(&$role)
    {
        if (! is_object($role)) {
            return;
        }

        // Grab the active permissions.
        if (! class_exists('permission_model', false)) {
            $this->load->model('permissions/permission_model');
        }
        $permissions = $this->permission_model->find_all_by('status', 'active');

        // Setup the permissions for the role.
        $role->permissions = [];
        foreach ($permissions as $key => $permission) {
            $role->permissions[$permission->name] = $permission;
        }

        if (! class_exists('role_permission_model', false)) {
            $this->load->model('roles/role_permission_model');
        }

        // Get the role permissions for the role
        $role->role_permissions = [];
        $role_permissions = $this->role_permission_model->find_for_role($role->role_id);
        if (! empty($role_permissions) && is_array($role_permissions)) {
            foreach ($role_permissions as $key => $permission) {
                $role->role_permissions[$permission->permission_id] = 1;
            }
        }
    }
}
