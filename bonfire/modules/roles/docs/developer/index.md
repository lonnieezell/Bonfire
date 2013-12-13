# Roles for Developers

## Initializing the Model

Like most other models in CodeIgniter, the Roles Model is initialized in your controller by using the $this->load->model function:

    $this->load->model('roles/role_model');

Once loaded, the Activities features will be avialable by using <tt>$this->role_model</tt>.

## Basic Usage

The role model extends from BF_Model, providing all of the default features of that model. In addition, the following methods are provided.

### find()

The find method works as the standard <tt>find()</tt> from BF_Model. However, it also grabs the permissions for that role and returns them as an array.

    $role = $this->role_model->find($id);

    print_r($role);

    // Returns:
    stdClass Object
    (
        [role_id] => 1
        [role_name] => Administrator
        [description] => Has full control over every aspect of the site.
        [default] => 0
        [can_delete] => 0
        [login_destination] =>
        [deleted] => 0
        [default_context] => content
        [permissions] => Array
        (
            [Site.Content.View] => stdClass Object
            (
                [permission_id] => 2
                [name] => Site.Content.View
                [description] => Allow users to view the Content Context
                [status] => active
            )
            . . .
        )
        [role_permissions] => Array
        (
            [2] => 1,
            [3] => 1
            . . .
        )
    )

The <tt>permissions</tt> array within the results contains the full contents of the permissions that are assigned to that role. The <tt>role_permissions</tt> array contains a simple array of the permission ID that role has assigned to it.

### find_by_name()

A helper method to retrieve a role by it's name. While the same thing is possible using BF_Model's find_by('name', $..) this is a more readable method. This also retrieves the permissions and role_permissions array.

The only parameter is the name of the role to find. This parameter is case sensitive.

    $role = $this->role_model->find_by_name('administrator');

### update()

This extends the BF_Model update method to perform deal with default roles. When the update is ran, if the data passed into contains a <tt>default</tt> value of 1, this method resets all other methods to be non-default.

### can_delete_role()

Checks the role to see if it's allowed to be deleted or not. Returns TRUE if the role is allowed to be deleted, or FALSE if not. Note that Administrators can always delete roles (except for the Administrator role).

    if ($this->role_model->can_delete_role($id)) . . .

### delete()

Deletes a role. First ensures the role is allowed to be deleted and is not the default role. Any users that belonged to this role are assigned to the current default role. All permissions that were assigned to this role are cleaned up in the database.  Returns TRUE if the role was able to be deleted, or FALSE if it was not.

    $this->role_model->delete($id);

When that role is deleted, the role is always considered to be a soft_delete and the role will stay in the database. If you want the role to be permenantly deleted from the database, you can pass TRUE in as the second parameter.

    $this->role_model->delete($id, true);

### default_role_id()

Returns the id of the role that is currently set to be the default role for new users.

    $default_id = $this->role_model->default_role_id();