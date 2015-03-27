# Users/Auth

Bonfire includes an Auth library in the Users module which handles user authentication and authorization.
User authentication allows the application to confirm the user's identity (in terms of confirming that they can access a particular user account on the site).
User authorization allows the application to determine what the user is allowed to do (verifying that a user has permission to visit a particular page or perform a particular action).

## Methods

### is_logged_in()

Verify that the user has been authenticated.

### login($login, $password[, $remember = false])

Attempt to authenticate the user with the provided credentials.
- `$login` is the email address or username entered by the user (depending on the site's configuration).
- `$password` is the password entered by the user.
- `$remember` is a boolean value indicating whether the user checked the "Remember Me" box (defaults to `false`), if enabled by the site's configuration.

### logout()

Log the current user out of the site.
Destroys the autologin information (if they checked the "Remember Me" box on login) and the session.

### user()

Get the user's information from the session and/or database.
Returns false if the `user_id` isn't found in the session, the `user_id` found in the session doesn't match a user in the database, or the session's `user_token` is invalid.

### has_permission($permission[, $role_id[, $override = false]])

Determine whether a role has the permission named by the value of `$permission`.
Permission names are case-insensitive.

If `$role_id` is not provided, this method will determine whether the current user's role has the requested permission.
Otherwise, it will check the role that corresponds to the provided `$role_id`.

The `$override` parameter allows access to be granted if `$permission` does not exist in the site's list of permissions.

With the exception of the behavior of `$override`, this method will return `true` if the role has the `$permission`, or `false` if they do not.

### permission_exists($permission)

Returns `true` if `$permission` exists in the site's permissions list, or `false` if it does not.
Permission names are case-insensitive.

### restrict([$permission[, $uri]])

Requires a user to authenticate (and, optionally, to have the requested `$permission`) before allowing them to view the current page.
Generally, this method is called from a controller to regulate access to either the entire controller (when called from the controller's constructor) or a particular action/method.

This is the work-horse of the Auth library, directing the user to the login screen if they have not been authenticated and, optionally, checking for the supplied `$permission` to determine whether the user is authorized to view the page.

If a user chooses not to authenticate, or is not authorized (does not have permission), they will be returned to the previous page or the main site URL, unless `$uri` is provided, in which case they will be redirected to the page indicated by `$uri`.

### role_id()

Retrieve the role_id from the current session.
If the current user is not authenticated, this will return false.

### role_name_by_id($role_id)

A convenience method to return the name of the role which corresponds to the given `$role_id`.

### check_password($password, $hash)

Check whether the supplied `$password` matches the supplied `$hash`.

### hash_password($pass[, $iterations])

Hashes the supplied password (`$pass`), optionally using the supplied number of `$iterations`.
If `$iterations` is not supplied, it will use the site's `password_iterations` configuration to determine the number of iterations to use in hashing the password.
Since the library stores the number of iterations in the password hash, it is not necessary to store this information separately.

### num_login_attempts([$login])

Get the number of login attempts from the current user's IP address or from the given `$login`.

### identity()

Retrieve the logged identity from the current session.
Returns false if the user is not currently logged in.

### user_id()

Retrieve the user_id from the current session.
Returns false if the user is not currently logged in.

## Properties

### $login_destination

The URL to which the user should be redirected when they are successfully authenticated.

## Events

### after_login

    $trigger_data = array(
        'user_id' => $user->id,
        'role_id' => $user->role_id,
    );
    Events::trigger('after_login', $trigger_data);

Triggered near the end of the `login()` method.

### before_logout

    $data = array(
        'user_id'   => $this->user_id(),
        'role_id' => $this->role_id(),
    );
    Events::trigger('before_logout', $data);

Triggered at the beginning of the `logout()` method.

## Helper Functions

Helper functions are not methods of the Auth library (so they are called without using the `$this->auth->` prefix).
They are included automatically when loading the Auth library.

### has_permission($permission[, $override = false])

Determine whether the current user has the requested `$permission`.
A shortcut to `$this->auth->has_permission($permission, null, $override)`

### permission_exists($permission)

Determine whether `$permission` is in the site's list of permissions.
A shortcut to `$this->auth->permission_exists($permission)`

### abbrev_name($name) *Deprecated*

Attempts to return a first and last name from the given string.

This function is deprecated.
It doesn't appear to be used within Bonfire and is unrelated to the Auth library.
If there is some reason to keep this function, it may be moved to a more appropriate location.
