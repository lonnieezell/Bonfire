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
 * @license   http://opensource.org/licenses/MIT MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Role Permissions Settings Model
 *
 * Provides access and utility methods for handling permission storage in the
 * database.
 *
 * Permissions are a simple string made up of 3 parts:
 * * Domain  - A generic classification system
 * * Context - Typically the module name
 * * Action  - The testable action (View, Manage, etc)
 *
 * Examples permissions would be:
 * * Site.Signin.Allow
 * * Site.Developer.View
 * * Bonfire.Users.Manage
 *
 * @package Bonfire\Modules\Roles\Models\Role_permission_model
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/roles
 */
class Role_permission_model extends BF_Model
{
    /**
     * @var string Name of the table
     */
    protected $table_name = 'role_permissions';

    /**
     * @var string Name of the primary key
     */
    protected $key = 'permission_id';

    /**
     * @var bool Use soft deletes (if true)
     */
    protected $soft_deletes = true;

    /**
     * @var string The date format to use
     */
    protected $date_format = 'datetime';

    /**
     * @var bool Set the created time automatically on a new record (if true)
     */
    protected $set_created = false;

    /**
     * @var bool Set the modified time automatically on editing a record (if true)
     */
    protected $set_modified = false;

    //--------------------------------------------------------------------

    /**
     * Create a new role permission entry
     *
     * @access public
     *
     * @param int $role_id       ID of the role
     * @param int $permission_id ID of the permission
     *
     * @return mixed ID of the new record or false if an error
     */
    public function create($role_id, $permission_id = null)
    {
        if (empty($role_id)) {
            $this->error = 'No Role given.';
            return false;
        }

        if (empty($permission_id)) {
            $this->error = 'No Permission given.';
            return false;
        }

        $data = array(
            'role_id' => $role_id,
            'permission_id' => $permission_id,
        );

        return $this->insert($data);
    }

    /**
     * Remove permission record(s) from the role permissions table.
     *
     * @param int $role_id       ID of the role
     * @param int $permission_id ID of the permission
     *
     * @return bool true on success, else false
     */
    private function delete_for($role_id, $permission_id)
    {
        $where = array();
        if (! empty($role_id)) {
            $where['role_id'] = $role_id;
        }

        if (! empty($permission_id)) {
            $where['permission_id'] = $permission_id;
        }

        $this->db->delete($this->table_name, $where);
        if ($result = $this->db->affected_rows()) {
            return true;
        }

        $this->error = 'DB Error: ' . $this->get_db_error_message();
        return false;
    }

    /**
     * Remove a permission record from the role permissions table.
     *
     * @param int $role_id       ID of the role
     * @param int $permission_id ID of the permission
     *
     * @return bool true on success, else false
     */
    public function delete($role_id = null, $permission_id = null)
    {
        if (empty($role_id)) {
            $this->error = 'No Role given.';
            return false;
        }

        if (empty($permission_id)) {
            $this->error = 'No Permission given.';
            return false;
        }

        return $this->delete_for($role_id, $permission_id);
    }

    /**
     * Remove a permission record from the role permissions table.
     *
     * @param int $role_id ID of the role
     *
     * @return bool true on success, else false
     */
    public function delete_for_role($role_id = null)
    {
        if (empty($role_id)) {
            $this->error = 'No Role given.';
            return false;
        }

        return $this->delete_for($role_id, null);
    }

    /**
     * Remove a permission record from the role permissions table.
     *
     * @param int $permission_id ID of the permission
     *
     * @return bool true on success, else false
     */
    public function delete_for_permission($permission_id)
    {
        if (empty($permission_id)) {
            $this->error = 'No Permission given.';
            return false;
        }

        return $this->delete_for(null, $permission_id);
    }

    /**
     * Sets the permissions for a single role.
     *
     * @param int   $role_id     The int id of the target role.
     * @param array $permissions A simple array with the values being equal to
     * the name of the permission to set. All other permissions are set to 0.
     *
     * @return mixed    false on empty or non-numeric $role_id, else void
     */
    public function set_for_role($role_id = null, $permissions = array())
    {
        if (empty($role_id) || ! is_numeric($role_id)) {
            return false;
        }

        $role = $this->find_by('role_id', $role_id);
        if ($role) {
            // Remove existing permissions
            $this->delete_for_role($role_id);
        }

        // Set the permissions
        $permission_data = array();
        foreach ($permissions as $key => $permission_id) {
            $permission_data[] = array(
                'role_id' => $role_id,
                'permission_id' => $permission_id,
            );
        }

        $this->insert_batch($permission_data);
    }

    /**
     *  A convenience method to assign a single permission to a role by names,
     *  rather than by ids.
     *
     * @param   str $role_name          The name of the role
     * @param   str $permission_name    The name of the permission to assign.
     *
     * @return mixed The inserted id or false on error
     */
    public function assign_to_role($role_name = null, $permission_name = null)
    {
        if (! class_exists('role_model', false)) {
            $this->load->model('roles/role_model');
        }
        $role = $this->role_model->where('deleted', 0)->find_by('role_name', $role_name);

        if (! class_exists('permission_model', false)) {
            $this->load->model('permissions/permission_model');
        }
        $perm = $this->permission_model->find_by('name', $permission_name);

        if (! $role || ! $perm) {
            return false;
        }

        return $this->create($role->role_id, $perm->permission_id);
    }

    /**
     * Return the permissions array for a single role.
     *
     * @param int $role_id The int id of the role to find permissions for.
     *
     * @return object
     */
    public function find_for_role($role_id = null)
    {
        return $this->select('permission_id')->where('role_id', $role_id)->find_all();
    }

    /**
     * Find all the role permissions (role_id and permission_id)
     *
     * @return object
     */
    public function find_all_role_permissions()
    {
        return $this->find_all();
    }

    /**
     * Find all of the roles
     *
     * @access public
     *
     * @return object
     */
    public function find_all_roles()
    {
        if (! class_exists('role_model', false)) {
            $this->load->model('roles/role_model');
        }

        return $this->role_model->find_all();
    }

    /**
     * Create a new role permission entry
     *
     * @param int $role_id       ID of the role
     * @param int $permission_id ID of the permission#
     *
     * @return bool true on success, else false
     */
    public function create_role_permissions($role_id, $permission_id)
    {
        return $this->create($role_id, $permission_id);
    }

    /**
     * Delete the permission for the role
     *
     * @param int $role_id       ID of the role
     * @param int $permission_id ID of the permission#
     *
     * @return bool true on success, else false
     */
    public function delete_role_permissions($role_id, $permission_id)
    {
        return $this->delete($role_id, $permission_id);
    }
}
