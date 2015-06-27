# Roles and Permissions

Bonfire uses a  Role-Based Access Control (RBAC) system for its authentication and authorization system. This provides enough granularity and flexibility for most situations, though may not be suitable for every website.

* <a href="#stock">Stock Roles</a>
* <a href="#new_roles">Creating New Roles</a>
* <a href="#permissions">Permissions</a>
* <a href="#access">Restricting Access</a>
* <a href="#passwords">Managing Passwords</a>

<a id="stock"></a>
## Stock Roles

Bonfire ships with 4 roles by default. They are Administrator, Developer, Editor, and User. Each of these roles has some basic access provided, though you will need to ensure that they all have the appropriate permissions for your site. While their names assume certain capabilities within the site, they can all be changed as needed, though we do highly suggest that Administrator remains unchanged.

### Administrator

The Administrator is the highest level user of the site. This is often the client that you are creating the site for. This is the 'owner' of the site and, as such, is typically given the most power of the site operators. By default, Bonfire provides this role with permissions over the entire site.

### Developer

While the Administrator is the owner of the site, there are still some tools that Bonfire provides that they will never need, like the Module Builder, Translation tools, etc. The Developer role was created to keep the dangerous tools away from the other users of the site, while still providing powerful tools for the development and maintenance process.

### Editor

Editors will typically be the staff hired by the Administrator to handle the day to day operations of the site. They will have a lot of capability, but there are still some sensitive areas that the Administrator might not want them to have access to.

### User

This is the default role that anyone registering to the site is given. As such, it will typically have very limited rights to most of the site.


<a id="new_roles"></a>
## Creating New Roles

New roles are easily created within the admin panel by navigating to `Settings / Roles` and then selecting `New Role` in the navigation bar. The elements of the form are:

* **Role Name** - The name of the role as you want it to appear to users. It should be one word, with no spaces, though you can use underscores in their place.
* **Description** - This field is primarily used on the Roles overview page, but can be used by yourself throughout your template when needed.
* **Login Destination** - This is the relative path that a user is directed to when they login. For example, you can force all admins to be directed to the main admin page by setting this value to `/admin`. Alternatively, you could have Users be directed to their own dashboard or account management page by entering the appropriate relative URL here.
* **Default Admin Context** - When a user logs into the admin area, this allows you to set the context they are directed to, like `/admin/content` The Settings and Developer contexts are unavailable for selection here.
* **Default Role** - The role assigned to new users when they register at the site. By selecting it here, it will be removed from any other role that currently has it.
* **Removable?** - Allows you to provide access to roles other than the Admistrator, while not giving them the power to delete certain roles, keeping them safe from accidents or malicious actions. If this is set to 'Yes', then anyone with the `Bonfire.Roles.Delete` permission can delete this role.

When you first create a role, no permissions will be shown. You can go back and edit the permissions available to the role by selecting the Role from the Role overview page, or by editing through the Permission Matrix.


<a id="permissions"></a>
## Permissions in Bonfire

Permissions in Bonfire are modeled after the excellent and easily-understandable permission naming system in <a href="http://vanillaforums.org">Vanilla Forums</a>. Permissions are described in 3-part, human-readable formats that allow for nearly any type of permission to be created. This allows both the admin screens and your code to maintain a high degree of readability.

<a id="naming"></a>
### Permission Names

Permission names are split into three parts, separated by periods.

Core Bonfire permissions use the following naming convention (`Site` is always used as the first part):

    Site.Action.Permission
    e.g. Site.Signin.Allow

Modules included with Bonfire use the following naming convention (`Bonfire` is always used as the first part):

    Bonfire.Module.Action
    e.g. Bonfire.Roles.View

In application modules (e.g. modules you create or those created by the Code Builder) the names should follow this convention:

    Module.Context.Action
    e.g. Blog.Content.View

* **Module** is typically the name of your module, or a portion of it.
* **Context** is typically the name of the context, e.g. Content, Reports, Settings, or Developer.
* **Action** is a single action that can be checked. Common actions are Manage, View, Edit, and Delete.

The permission naming convention can be changed to meet your requirements, but it is recommended that you use this format to prevent conflicts.

To be safe in naming custom permissions, the use of `Bonfire` or `Site` in the first region of the permission name should be considered reserved. If you choose to use `Site` in the first region for a custom permission, you should consider using an identifier in the second region which will be suitably unique to your site.

<a id="creating_perms"></a>
### Creating Permissions

New permissions can easily be created through the Admin UI by navigating to Settings / Permissions. This screen will provide a list of all existing permissions as well as the option to create new ones.

Each permission has the following three properties...

* **Name** is the permission itself, following the naming scheme outlined above.
* **Description** is a short string describing the permission and its use. This is only used for display in the Permissions overview page.
* **Status** allows permissions to still be available in the system, but not to actually be used. This can be used as a placeholder for in-development features.

<a id="assigning_perms"></a>
### Assigning Permissions

Permissions can be assigned to roles through the `Edit Role` screen. Alternatively, they can be assigned to all roles at once by viewing the `Permission Matrix`, available from both the Roles and Permissions screen.


<a id="access"></a>
## Restricting Access

The `Auth` library provides several useful methods to restrict access, or check access, from any place in your application. If not already loaded, you can load the Auth library with the following code:

    $this->load->library('users/auth');

<a id="restrict"></a>
### `restrict()`

The `restrict()` method can be used to protect an entire method or even class. If used without any parameters, it will simply verify that the user is logged in. If they are not, it will redirect them to the login page.

    $this->auth->restrict();

You can require that a user has a certain Permission granted by passing the name of the permission as the first parameter. You do not have to match the case of the original permission string, as it will be converted to lowercase prior to checking.

    $this->auth->restrict('Bonfire.Users.Manage');

If a user does not have the required permission granted to them, they will be directed to their previous page. You can change the URI they are redirected to by passing it in as the second parameter. This can be either a relative or full URI path.

    $this->auth->restrict('Bonfire.Users.Manage', '/get-outtat-here.html');

<a id="is_logged_in"></a>
### `is_logged_in()`

You can check if a user is logged in with the `is_logged_in()` method. This can be used in your own controller and libraries, as well as in your views to display different information to logged in and logged out users.

    if ($this->auth->is_logged_in()) {
        . . .
    } else {
        . . .
    }

Note that the first time in a session that this function is called, it will verify their identity stored in the session matches their hashed password information in the database. It then sets a flag that can be used for later checks to increase performance, while still maintaining a high level of security.

<a id="has_permission"></a>
### `has_permission()`

The `has_permission()` method allows you to check if the current logged-in user has a specified permission. You pass the name of the permission to check in as the first parameter.

    if (! has_permission('Bonfire.Users.Manage')) {
        . . .
    }

<a id="permission_exists"></a>
### `permission_exists()`

This function allows you to quickly check whether a permission exists in the databse or not. Simply pass in the permission name to check as the first parameter.

    if (permission_exists('Bonfire.Users.Manage')) {
        . . .
    }


<a id="passwords"></a>
## Passwords

While passwords are generally managed through the users module, the auth library includes a couple of basic functions for password management.

### `check_password()`

The `check_password()` method allows you to verify that a given password matches a password hash.

	if ($this->auth->check_password('password to check', 'HashedPassword')) {
		// The passwords match
		...
	}

### `hash_password()`

The `hash_password()` method allows you to hash a password with an optional number of iterations (if not supplied, the site's `password_iterations` setting will be used). This should not be used to check a password, since hashing the same password again won't match a stored hash.

	$password = $this->auth->hash_password('password');
	$hash = $password['hash'];
	$iterations_used = $password['iterations'];

Note that although the number of iterations used in hashing the password is returned by this method, it can be safely ignored. The primary use of the returned value would be to check whether the `hash_password()` method is accepting your input (or using the config value instead) in debugging, since the PasswordHash library stores the number of iterations in the hash and only uses the value in the hash when checking a password.
