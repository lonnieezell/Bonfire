# Permissions for Developers

## Initializing the Model

Like most other models in CodeIgniter, the Permission Model is initialized in your controller by using the $this->load->model function:

    $this->load->model('permissions/permission_model');

Once loaded, it will be avialable by using <tt>$this->permission_model</tt>.

## Basic Usage

The role model extends from BF_Model, providing all of the default features of that model. In addition, the following methods are provided.

### delete_by_name()

A convenience method that deletes the role by name, instead of the more common 'id'. This is very handy during migrations when you don't know what ID has been assigned to it, but you do the name.

    $this->permission_model->delete_by_name('Site.Content.View');

### permission_exists()

Checks the database to see if a permission already exists. Simply a more descriptive version of the <tt>is_unique</tt> method. The only parameter is the name of the permission.

    if ($this->permission_model->permission_exists('Site.Content.View')) { . . . }
