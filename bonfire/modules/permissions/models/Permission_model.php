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
 * Permissions Settings Model
 *
 * Provides access and utility methods for handling permission storage in the database.
 *
 * Permissions are a simple string made up of 3 parts:
 * - Domain  - Typically the module name for application modules.
 * - Context - The context name (e.g. Content, Reports, Settings, or Developer).
 * - Action  - The permitted action (View, Manage, Create, Edit, Delete, etc.).
 *
 * Example permissions would be:
 * - Site.Developer.View
 * - Bonfire.Users.Manage
 * - Appmodule.Content.Delete
 *
 * @package Bonfire\Modules\Permissions\Models\Permission_model
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/roles_and_permissions
 *
 */
class Permission_model extends BF_Model
{
    /** @var string Name of the table. */
    protected $table_name = 'permissions';

    /** @var string Name of the primary key. */
    protected $key = 'permission_id';

    /** @var boolean Use soft deletes (if true). */
    protected $soft_deletes = false;

    /** @var string The date format to use. */
    protected $date_format = 'datetime';

    /** @var boolean Set the created time automatically on a new record (if true). */
    protected $set_created = false;

    /** @var boolean Set the modified time automatically on editing a record (if true). */
    protected $set_modified = false;

    /** @var array Metadata for the model's database fields. */
    protected $field_info = array(
        array('name' => 'permission_id', 'primary_key' => 1),
        array('name' => 'name'),
        array('name' => 'description'),
        array('name' => 'status'),
    );

    /** @var array Rules used to validate the model. */
    protected $validation_rules = array(
        array(
            'field' => 'name',
            'label' => 'lang:permissions_name',
            'rules' => 'required|trim|max_length[255]',
        ),
        array(
            'field' => 'description',
            'label' => 'lang:permissions_description',
            'rules' => 'trim|max_length[100]',
        ),
        array(
            'field' => 'status',
            'label' => 'lang:permissions_status',
            'rules' => 'required|trim',
        ),
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

    /**
     * Delete a particular permission from the database.
     *
     * @param integer $id Permission ID to delete.
     *
     * @return boolean True if the permission was deleted successfully, else false.
     */
    public function delete($id = 0)
    {
        // Delete the record.
        $deleted = parent::delete($id);
        if ($deleted !== true) {
            return false;
        }

        // If the delete succeeded, delete the role_permissions for this $id.
        $this->role_permission_model->delete_for_permission($id);

        return true;
    }

    /**
     * Update a particular permission in the database.
     *
     * Remove it from role_permissions if set to inactive.
     *
     * @param integer $id   The primary key value or an array of key/value pairs
     * for the where clause to determine the row to update.
     * @param array   $data An array of key/value pairs to update.
     *
     * @return boolean True if the permission was updated, else false.
     */
    public function update($id = null, $data = null)
    {
        $updated = parent::update($id, $data);

        if ($updated !== true
            || ! isset($data['status'])
            || $data['status'] != 'inactive'
        ) {
            return $updated;
        }

        // If the permission is set to inactive, delete its role_permissions.
        if (is_array($id) && ! isset($id[$this->key])) {
            // If $id is an array and the key is not set, find the key(s) and perform
            // the delete.
            $result = $this->select($this->key)->find_all_by($id);
            if (! $result) {
                // This permission is not assigned to any roles, but the permission
                // was updated successfully.
                return true;
            }

            $returnVal = true;
            foreach ($result as $permission) {
                if (! $this->role_permission_model->delete_for_permission($permission->{$this->key})) {
                    // Save failed result, but continue attempting to delete permissions
                    // from the roles to ensure that the permission is deleted from
                    // as many roles as possible.
                    $returnVal = false;
                }
            }

            return $returnVal;
        }

        // If $id is an array, the key is in the array, otherwise, $id is the key.
        $id_key = is_array($id) ? $id[$this->key] : $id;
        return $this->role_permission_model->delete_for_permission($id_key);
    }

    // -------------------------------------------------------------------------
    // End BF_Model method overrides.
    // -------------------------------------------------------------------------

    /**
     * Delete a particular permission from the database by name.
     *
     * @param string $name The name of the permission to delete.
     *
     * @return boolean True if the permission was deleted successfully, else false.
     */
    public function delete_by_name($name = null)
    {
        $perm = $this->find_by('name', $name);
        if (empty($perm) || ! isset($perm->permission_id)) {
            return false;
        }

        return $this->delete($perm->permission_id);
    }

    /**
     * Check whether a permission is in the system.
     *
     * @param string $permission The name of the permission to check.
     *
     * @return boolean true if the permission was found, null if no permission was
     * passed, else false.
     */
    public function permission_exists($permission = null)
    {
        if (empty($permission)) {
            return null;
        }

        if ($this->find_by('name', $permission)) {
            return true;
        }

        return false;
    }
}
