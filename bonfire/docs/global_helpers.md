Bonfire provides a number of resources that are loaded globally and are intended to make life a little easier for you. Most of these are loaded from the *application/helpers/application_helper.php* file.

### <tt>$current_user</tt>

If a user is logged in, you can access the current user's detail within any of your controllers that extend Bonfire's provided controllers, like Front_Controller, Admin_Controller, etc. This is accessed by <tt>$this->current_user</tt>.

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


The exact details may change over the versions, but the entire <tt>users</tt> table record is provided to you.

This same data is made available within all of your views as <tt>$current_user</tt>.


    <?php echo $current_user->display_name ?>




### <tt>e()</tt>

A convenience function that can (and probably should) be used to replace <tt>echo()</tt> in any places that you output text that a user might have entered. This function helps to defeat XSS attacks by running the text through <tt>htmlspecialchars()</tt>.


    <?php e($user->display_name); ?>




### <tt>js_escape()</tt>

Performs some simple escaping of strings that will be inserted into javascript functions, like <tt>confirm()</tt> or <tt>alert()</tt>. Not suitable for use by itself within document.write. The only parameter is the string to escape.




### <tt>dump()</tt>

Outputs the given variables with formatting and location. Any number of parameters can be passed to this function and they will all be printed out in a helpful manner, but script execution will continue.




### <tt>gravatar_link()</tt>

Returns a URL for the user from [Gravatar](http://gravatar.com) and provides a default image (the [identicon](http://en.gravatar.com/site/implement/images/)) when the user hasn't provided one. This is intended to be used within your views for displaying the user's avatar.


    <?php echo gravatar_image($current_user->email, 48, $current_user->display_name, $current_user->display_name, $class, $id); ?>

    // Returns:
    <img src="http://gravatar.com/avatar/XXX?s=48&r=pg&d=identicon" width="48" height="48" alt="" title="" />


The first parameter is the email address for the user. This is how they are referenced at Gravatar. The second parameter is the size of the image in pixels. The third parameter is the alt tag and the fourth parameter is the title for the tag. The fifth and sixth parameters are the class and id to be given to the img, respectively.



### <tt>logit()</tt>

Logs an error to the Console in the profiler (if loaded) and to the log files.


    logit('Some debugging message', 'debug');


The first parameter is the message to log. The second parameter is the log level.

If the profiler is enabled and the Console class is loaded, which it is by default in development mode, the string will show up in the Console section of the profiler.

If logging is enabled in your <tt>application/config/config.php</tt> file, then it will also be logged to your default log file.



### <tt>module_folders()</tt>

Returns an array of the folders that modules are allowed to be stored in. These are set in <tt>application/config/config.php</tt>.


    print_r( module_folders() );

    Array(
        application/modules,
        bonfire/modules
    )



### <tt>module_list</tt>

Returns a list of all modules in the system. If TRUE is passed as the only parameter, any Bonfire core modules will NOT be shown, only your custom modules. If FALSE is passed, both core and custom modules will be listed.


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




### <tt>module_controller_exists()</tt>

Looks within a module to see if a certain controller exists. The first parameter is the name of the controller, and the second parameter is the module name.

It returns either TRUE or FALSE.


    if (module_controller_exists('content', 'users')) { . . . }




### <tt>module_file_path()</tt>

Locates a file within a module and returns the path to that file. The first parameter is the name of the module. The second parameter is the name of the folder. The last parameter is the name of the file that you're looking for (including the extension).

It returns the full server path to the file, if found.


    $path = module_file_path('users', 'assets', 'js/users.js');




### <tt>module_path()</tt>

Returns the full server path to a module and, optionally, a folder within that module. The first parameter is the name of the module. The second parameter is the name of the folder.


    $path = module_path('users', 'assets');




### <tt>module_files()</tt>

Returns an associative array of files within one or more modules.

The first parameter is the name of the module to restrict the search to. If left NULL, this will provide a list of all files within all of the modules. If a module name is specified, the search will be restricted to that module's files only.


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



### <tt>module_config()</tt>

Returns the 'module_config' array from a module's <tt>config/config.php</tt> file. The <tt>module_config</tt> contains more information about a module, like the author, menu behavior, etc.

