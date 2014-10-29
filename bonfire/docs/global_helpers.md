# Global Helper Functions

Bonfire provides a number of resources that are loaded globally and are intended to make life a little easier for you. Most of these are loaded from the *application/helpers/application_helper.php* file.

### `$current_user`

If a user is logged in, you can access the current user's detail within any of your controllers that extend Bonfire's provided controllers, like Front_Controller, Admin_Controller, etc. This is accessed by `$this->current_user`.

This is an object with the user's details:


    stdclass Object
    {
        [id]        => 1
        [role_id]   => 1
        [email]     => darth@empire.com
        [username]  => Darth Vader
        [password_hash] => ...
        [reset_hash]    => ...
        [last_login]    =>
        [last_ip]       =>
        [created_on]    =>
        [deleted]       => 0
        [banned]        => 0
        [ban_message]   =>
        [reset_by]      =>
        [display_name]  => Darth
        [display_name_changed]  =>
        [timezone]      => UM6
        [language]      =>
        [active]        => 1
        [activate_hash] =>
        [password_iterations]   => 8
        [force_password_reset]  => 0
        [role_name]     => Administrator
        [user_img]      => (url for their avatar)
    }


The exact details may change over the versions, but the entire `users` table record is provided to you.

This same data is made available within all of your views as `$current_user`.


    <?php echo $current_user->display_name ?>




### `e()`

A convenience function that can (and probably should) be used to replace `echo()` in any places that you output text that a user might have entered. This function helps to defeat XSS attacks by running the text through `htmlspecialchars()`.


    <?php e($user->display_name); ?>




### `js_escape()`

Performs some simple escaping of strings that will be inserted into javascript functions, like `confirm()` or `alert()`. Not suitable for use by itself within document.write. The only parameter is the string to escape.




### `dump()`

Outputs the given variables with formatting and location. Any number of parameters can be passed to this function and they will all be printed out in a helpful manner, but script execution will continue.




### `gravatar_link()`

Returns a URL for the user from [Gravatar](http://gravatar.com) and provides a default image (the [identicon](http://en.gravatar.com/site/implement/images/)) when the user hasn't provided one. This is intended to be used within your views for displaying the user's avatar.


    <?php echo gravatar_image($current_user->email, 48, $current_user->display_name, $current_user->display_name, $class, $id); ?>

    // Returns:
    <img src="http://gravatar.com/avatar/XXX?s=48&r=pg&d=identicon" width="48" height="48" alt="" title="" />


The first parameter is the email address for the user. This is how they are referenced at Gravatar. The second parameter is the size of the image in pixels. The third parameter is the alt tag and the fourth parameter is the title for the tag. The fifth and sixth parameters are the class and id to be given to the img, respectively.



### `logit()`

Logs an error to the Console in the profiler (if loaded) and to the log files.


    logit('Some debugging message', 'debug');


The first parameter is the message to log. The second parameter is the log level.

If the profiler is enabled and the Console class is loaded, which it is by default in development mode, the string will show up in the Console section of the profiler.

If logging is enabled in your `application/config/config.php` file, then it will also be logged to your default log file.



### `module_folders()`

Returns an array of the folders that modules are allowed to be stored in. These are set in `application/config/config.php`.

DEPRECATED in version 0.7.1. Use Modules::folders() instead.

    print_r( module_folders() );

    Array(
        application/modules,
        bonfire/modules
    )



### `module_list`

Returns a list of all modules in the system. If TRUE is passed as the only parameter, any Bonfire core modules will NOT be shown, only your custom modules. If FALSE is passed, both core and custom modules will be listed.

DEPRECATED in 0.7.1. Use Modules::list_modules() instead.

    print_r( module_list() );

    Array
    (
        [0] => activities
        [1] => builder
        [2] => database
        [3] => emailer
        [5] => logs
        [6] => migrations
        [7] => permissions
        [8] => roles
        [9] => settings
        [10] => sysinfo
        [11] => translate
        [12] => ui
        [13] => update
        [14] => users
    )




### `module_controller_exists()`

Looks within a module to see if a certain controller exists. The first parameter is the name of the controller, and the second parameter is the module name.

It returns either TRUE or FALSE.

DEPRECATED in 0.7.1. Use Modules::controller_exists() instead.

    if (module_controller_exists('content', 'users')) { . . . }




### `module_file_path()`

Locates a file within a module and returns the path to that file. The first parameter is the name of the module. The second parameter is the name of the folder. The last parameter is the name of the file that you're looking for (including the extension).

It returns the full server path to the file, if found.

DEPRECATED in 0.7.1. Use Modules::file_path() instead.

    $path = module_file_path('users', 'assets', 'js/users.js');




### `module_path()`

Returns the full server path to a module and, optionally, a folder within that module. The first parameter is the name of the module. The second parameter is the name of the folder.

DEPRECATED in 0.7.1. Use Modules::path() instead.

    $path = module_path('users', 'assets');




### `module_files()`

Returns an associative array of files within one or more modules.

The first parameter is the name of the module to restrict the search to. If left NULL, this will provide a list of all files within all of the modules. If a module name is specified, the search will be restricted to that module's files only.

DEPRECATED in 0.7.1. Use Modules::files() instead.

    module_files('sysinfo');

    // Produces:
    Array
    (
        [sysinfo] => Array
        (
            [config] => Array
                (
                    [0] => config.php
                )

            [controllers] => Array
                (
                    [0] => developer.php
                )

            [language] => Array
                (
                    [english] => Array
                        (
                            [0] => sysinfo_lang.php
                        )

                    [persian] => Array
                        (
                            [0] => sysinfo_lang.php
                        )

                    [portuguese_br] => Array
                        (
                            [0] => sysinfo_lang.php
                        )

                    [spanish_am] => Array
                        (
                            [0] => sysinfo_lang.php
                        )

                )

            [views] => Array
                (
                    [developer] => Array
                        (
                            [0] => _sub_nav.php
                            [1] => index.php
                            [2] => modules.php
                            [3] => php_info.php
                        )

                )

        )

    )


The second parameter lets you specify a folder within that module to limit the file search to. If left NULL, it will provide all of the files.

The third parameter, when set to TRUE, will exclude the core modules from the list. If FALSE, will include both the core and your custom modules.



### `module_config()`

Returns the 'module_config' array from a module's `config/config.php` file. The `module_config` contains more information about a module, like the author, menu behavior, etc.

DEPRECATED in 0.7.1. Use Modules::config() instead.
