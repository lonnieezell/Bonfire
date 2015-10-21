# Change Log

## Under development

### 0.8.4
* Upgraded CodeIgniter 3 to 3.0.2
* Upgraded CodeIgniter 2 Support to 2.2.5

## Released versions

#### New Features:

#### Closes Issues:
* #1163/1164: Fix /public/tests.php shows "SimpleTest documentation" instead of "Bonfire Tests".
* #1165: Fixed failed tests and updated root index.php
* #1166: Fixed wrong date displayed due to un-utilized user timezone.

#### Additional Changes:

#### Known Issues:

### 0.8.3
* Upgraded CodeIgniter 3 to 3.0.1
* Upgraded CodeIgniter 2 to 2.2.4

#### New Features:
* MY_*_helper files can now override BF_*_helper files

#### Closes Issues:
* #1153: Error: "Undefined index: user_agent" caused by out-dated `MX_Loader`.
* #1154: [Builder] Cancel button contains "Content" instead of "content".

#### Additional Changes:
* `user_meta` view in `users` module updated to make it less likely to have issues with PHP 7.

#### Known Issues:

### 0.8.2

#### New Features:
* Installer_lib now reads `writable_folders` and `writable_files` from the newly added `/application/config/installer_lib.php`
* The `sysinfo` module's `Developer` controller will attempt to load the `installer_lib` config and display the writable/not writable status of the same directories/files.

#### Closes Issues:
* #1151: [BF_Router] Call to undefined method `_set_404override_controller()`
* #1150: Changing Role name breaks permissions
* #1149: Blog Tutorial defined `$modified_field` without default value.
* #1147: Unique validation fails on existing role.
* #1144: Emailer lang entries missing when sending mail from users module.
* #1143: Emailer Settings: can't send email.
* #1142: Module Builder: Fix #1128 properly (undefined property: Modulebuilder::$load).

#### Additional Changes:
* PasswordHash library (PHPass):
    * removed PHP4 compatibility
    * changed constructor to PHP5-style constructor
    * added visibility to properties/methods
    * deprecated all of the properties/methods except `__construct()`, `CheckPassword()`, and `HashPassword()` (all deprecated properties/methods will become `protected` in a future version)

#### Known Issues:

### 0.8.1

#### New Features:

#### Closes Issues:
* #1136 Profiler: MySQL explain update fails on older versions.
* #1131 Modules Library: modules_locations not loaded from application config.
* #1129 Module Builder: filenames and class names built with incorrect case for CI3.
* #1128 Module Builder: Use of `strip_slashes()` without loading the string helper.
* #1118 Settings error when password options are not selected in security tab.

#### Additional Changes:
* Builder: Added a note to the create_context page reminding users to add route(s) for the new context.
* Contexts: Fix links when using `$top_level_only` parameter in `render_menu()`
* Docs: update paths in installation docs to reflect 0.6 directory changes.
* Installer: Fix error checking writable directories in APPPATH on Windows
* Profiler: Improved SQL highlighting in Queries tab.

#### Known Issues:

### 0.8.0

#### New Features:
* Installed/Enabled version of CodeIgniter upgraded to 3.0
* CI v2.x compatibility maintained, but requires [some additional steps to use/enable](https://github.com/ci-bonfire/Bonfire/blob/develop/bonfire/docs/ci2.md).

#### Closes Issues:

#### Additional Changes:

#### Known Issues:

### 0.7.4

#### New Features:

#### Closes Issues:
* #1119 Module Builder xss_clean error
* #1116 Syntax error in the builder-built view (when adding a required input field), pt 2
* #1115 Can't save settings, 'module' field not being sent to settings table
* #1114 Differences in Routing between CI2 and CI3
* #1113 Syntax error in the builder-built view (when adding a required input field)
* #1112 Can't login to site when offline in CI3

#### Additional Changes:

#### Known Issues:

### 0.7.3

#### New Features:
* If you are not using the installer, add `$config['bonfire.installed'] = "1";` to `/application/config/application.php`.
* Added `Template::setSessionUse($useSession = true)`, deprecated `Template::$ignore_session`. Note that the parameter accepted by `setSessionUse()` would be the opposite value of that used with `$ignore_session`.
* Added `form_validation->reset_validation()` support for CI 2 (in `BF_Form_Validation`, in CI 3 the method calls the parent method, in case of any future changes).
* Added `\application\language\english\bf_form_validation_lang.php` to store custom form validation language entries. This file is automatically loaded by the `BF_Form_validation` library when calling `$this->form_validation->run()`.

#### Closes Issues:
* #1110 `BF_Form_validation` not loaded in CI3
* #1109 Mobule Builder not using `lang(module_field_name)` for create/edit views.
* #1108 Can't create new user using CI3
* #1107 No model error generated for a failed update
* #1106 Settings controller displays error message on success.
* #1105 Doc searching - preg_match error when directory is encountered
* #1103 Installation using CI 3.0 fails due to use of sessions before CI 3 session table is created.
* #1033 Email sending test makes subnav bar crash

#### Additional Changes:
* Upgraded CI to v2.2.2.
* Fixes an issue when creating new roles which caused permissions to modify the new role not to be created/added to the admin/current user.
* Fixes issues with Emailer not displaying saved settings properly.
* Fixes issues with Add/Remove Shortcuts in UI module.
* Fixes result of `BF_Model->update_batch()` when the update completed successfully in CI 3, or failed in CI 2.
* Database module:
    * Fixed display of validation errors on backup.
    * Added message indicating user submitted index with the separator selected in the dropdown.
* CI 3 compatibility improvements:
    * Fix Runtime Notice for Users Settings: Only variables should be passed by reference.
    * Don't use `$this->load->driver('session')`, and don't check for the CI version before loading the session library.
    * Normalize the output of `uri->ruri_string()` before checking it in `App_hooks` (may be needed elsewhere).
    * Fix loading of `BF_`-prefixed libraries when a `MY_`-prefixed library is not present.
    * Don't display developer documentation for modules in the application docs.
* Installer improvements:
    * Updated a number of migrations to remove session use.
    * Removed session from autoload.
    * `bonfire.installed` setting added to application config by the installer.
    * `App_hooks` disables session use when `bonfire.installed` is not found.

#### Known Issues:

### 0.7.2

#### New Features:
* In CI 2.x, uses bfmysqli database driver (a modified version of the mysqli driver) by default.
* CommonMark support in Docs
    * View the [CommonMark Library docs](developer/commonmark) for more information.
* Added `BF_directory_helper`:
    * `bcDirectoryMap()` function provides the same output as CI2's `directory_map()` function. This was used primarily to make the Modules library and various portions of the translate module work properly in CI3.
    * In the long term, both the Modules library and translate module should be updated with more robust path/directory name handling, which would make this function unnecessary.

#### Closes Issues:
* #337 Can not login to admin when site is turned off (previously closed as non-issue, now fixed).
* #357 Custom Message When Site is Off/Closed
* #793 Wrong timezone setting (use `site.default_user_timezone` setting when $user->timezone is unavailable, instead of the admin's timezone).
* #603 Documentation updated to note the potential for `before_`/`after_user_update` events to receive an array in the `user_id` field, and for that field to potentially not include the user's ID.
* #565 `render_user_form` in the admin doesnt pass the payload.
* #1082 Strip '.php' extension from module config files in `config_file_helper`'s `read_config()` function when the file is found by `Modules::file_path()`.
* #1085 `s` key submits forms automatically
* #1087 Country dropdown in extended settings doesn't update state dropdown
* #1089 Fix error deleting roles for permissions during permission_model->update()
* #1097, 1081 Installer looks for database configuration only in /application/config/development/database.php

#### Additional Changes:
* Upgraded CI to v2.2.1
* [Improved support for CI 3](https://github.com/ci-bonfire/Bonfire/blob/develop/bonfire/docs/ci3.md).:
    * Renamed controller, library, and model files to uppercase first letter.
    * Added support for loading controller and model files named with uppercase first letter.
    * Accept null or false return from `config->item()` when config file has not been loaded.
    * Replaced use of `router->fetch_directory()` and `router->fetch_class()` with `router->directory` and `router->class`, respectively.
    * Replaced `random_string('unique', ...)` with `random_string('md5',...)`.
    * Replaced `read_file()` with `file_get_contents()`.
    * Replaced `do_hash()` with `sha1()`.
    * Added Constants from CI 3 to `/application/config/constants.php` (primarily EXIT_* constants).
* Removed mdash entities in documentation for "Submitting Bug Reports and Feature Requests" and updated link in Readme
* Profiler now displays boolean values in config section (displayed values are retrieved from the application_lang file, `'bf_profiler_true'` and `'bf_profiler_false'`).
* Added support for socket connections (prefixed with /) to bfmysqli database driver, similar to mysqli driver socket support in CI3.
* jwerty (keyboard shortcuts):
    * Updated URL for jwerty.
    * Updated jwerty.js to version 0.3.2.
* Added `$allowOffline` array to `/application/hooks/App_hooks.php` to configure pages which are allowed to bypass the site offline functionality. As long as `'/users/login'` is in this list, users with the correct permissions will be able to log in and bring the site back online. If a user does not have the correct permission, and no additional pages have been added to the list, they will still see the contents of the `/application/errors/offline.php` file once they log in to the site. If you want to disable user logins while the site is offline, set this variable to an empty array. Just make sure you don't log out of the site after setting it offline, or you will have to update the database to get the site back online.
* Application Helper:
    * `is_https()`:
        * Added `is_https()` function from CI 3's `core/Common` functions to ensure checks for use of https are consistent and accurate.
        * Modified `gravatar_link()` function to use `is_https()`.
        * Modified the Assets library to use `is_https()` (in `external_js()` and `find_files()`).
        * Did not modify CI 2 core classes to use it, but it could be useful in `core/Config` and `core/Security`. One possibility would be to override `csrf_set_cookie()` in `/bonfire/core/BF_Security.php`.
    * Modified `list_contexts()` to call `Contexts:getContexts()`, keeping the functionality of determining required and available contexts within the Contexts library.
* UI/Contexts Library:
    * Deprecated `set_contexts()` and `get_contexts()` (use `setContexts()` and `getContexts()`).
    * When using `setContexts()` in place of `set_contexts()`, be aware that the behavior has changed slightly:
        * when omitting the second argument (`$siteArea`), `setContexts()` will not change the internal `$site_area` variable, while `set_contexts()` would set it to `SITE_AREA`. (`SITE_AREA` is still the default value for the internal variable.)
        * `setContexts()` will add the required contexts if they are not included in the first argument.
    * `getContexts()` allows an optional first argument to only return contexts with landing pages (the functionality from the `application_helper`'s `list_contexts()` function).
    * Modified internal access of the `self::$contexts` array to use `self::getContexts()` and `self::setContexts()`.
* Language improvements:
    * CI Language files adapted from https://github.com/bcit-ci/codeigniter3-translations (official CI3 translations repository)
    * DB files modified to use `db_unsupported_compression` and `db_unsupported_feature` (instead of `db_unsuported_*`) and FTP library modified to use `ftp_unable_to_mkdir` instead of `ftp_unable_to_makdir`, so other language files may be pulled down from the official CI3 translations.
    * Updated `form_validation_lang` files to allow use with either CI 2 or 3.
    * Changed language prefix for emailer module from `em_` to `emailer_`.
    * Fixed russian language support for activities, logs, and sysinfo modules. Added russian language support to builder.
    * Changed language prefix for translate module from `tr_` to `translate_`.

#### Known Issues:

### 0.7.1

#### New Features:

* Composer Auto-loading may be enabled by setting `'composer_autoload'` to true (or the path to the autoload.php file) in /application/config/config.php. Please be aware that this currently requires hooks to be enabled and the `App_hooks->checkAutoloaderConfig()` method must be included in the `pre_controller` hooks in /application/config/hooks.php.
* Now works with [Sparks](http://getsparks.org) out of the box.
* Brand new documentation system that allows splitting your user and dev-related docs, and searching docs.
* Added bfmysqli driver to support more features in the MySQLi driver without changing the codeigniter driver. Set `$db['dbdriver'] = 'bfmysqli';` in the database config, configure the other settings as you would for the mysqli driver.
* Images controller can reference images from a module using the `module=...` parameter.
* Template class now provides a `check_segment` helper in addition to `check_class` and `check_method`.
* CSRF protection can be bypassed for individual controllers by setting `csrf_ignored_controllers` setting in the site's main index file.
* Added `'languages_available'` and `'database_types'` to Module Builder's configurable options. `languages_available` tells the builder which language files to attempt to build for a new module. `database_types` gives the builder some information about the types supported by the database, and is used in generating the "Database Type" select on the module builder form, as well as handling several other aspects of building the module.

#### Closes Issues:

* #1078 - Module Builder form's script would fail if other scripts using `localStorage` used URLs for keys. Failure of the script would cause collapsing areas in the form to stop functioning. DataTables v1.10 would cause this issue if the `saveState` option was enabled.
* #1075 - Removed statistics collection from installer.
* #1073 - Installation issues with App_hooks when settings table doesn't exist.
* #1048 - (almost) full russian language
* #1041 - Only display edit keyboard shortcuts link to users with permission to edit them
* #1040 - Documentation errors (Performance Tips)
* #1038 - Add HTML5 required attribute to builder-generated form inputs
* #1036 - Builder: Allow user to undo selecting an option under Input Limitations
* #1035, 1042 - Migrations library attempts to insert core version (duplicate key) during installation
* #1011 - `save_requested()` and `prep_redirect()` are broken again.
* #1009 - Activities reports 0 records found when attempting to delete.
* #1001 - Error handling in Module Builder, default max lengths for fields in existing tables, JavaScript updates of table name, use created/modified/soft deletes and log user as checkboxes
* #1000 - Cache errors when using custom ENVIRONMENT settings
* #983 - `Assets::js()` creates invalid path when `base_url` is set to a directory
* #982 - `MY_Security` renamed to `BF_Security`
* #979 - Calling `Modules::Run()` from view is broken
* #975 - `BF_Model->update()`: check validation result before continuing with update
* #968 - Error loading language file in languages other than English
* #967 - Should make the CSRF ignore list configurable.
* #965 - State input no longer required in extended settings
* #964 - Fixed documentation of `Template::set()`
* #962 - Updated docs system doesn't load module docs for application or module docs named something other than index
* #958 - ENVIRONMENT config files are being ignored.
* #957 - Documentation of required server configuration on display of root index.php
* #955 - Fixed the path of the User Modules' views
* #954 - `Assets::js()` returns nothing if string is passed as first parameter
* #952 - Documentation of `BF_Model`'s handling of validation 'label' parameter
* #948 - Add function strtolower in libraries: Module will be better.
* #947 - Remove PHP short tag from view for PHP 5.3 support
* #946 - Unable to load class cache.
* #941 - Model `set_date` when `time_reference` is set to GMT
* #940 - Model `update()` throws error in `modified_on()` when validation fails and `set_modified` is enabled.
* #939, 627 - sync `user_meta` state/country selects
* #937 - Module Builder support for `$log_user`, `$created_by_field`, `$modified_by_field`, and `$deleted_by_field`
* #932 - Resend Activation Link sends incorrect link, preventing user from activating account
* #928 - `min_length[8]` validation called regardless of minimum length setting for password
* #926 - Application helper iif issue
* #922 - Type of `MY_Model` `update_where()` method
* #919 - Incorrect documentation of `date_format` field in `MY_Model`
* #917 - Problem when `SITE_AREA` is changed
* #849 - No need to save `password_iterations` in the user table
* #769 - Filter out soft-deleted records on public index generated by module builder
* #757 - Migration version is always up when SQL migrations fail
* #737 - Paging data in Module Builder
* #726 - Admin notification for approval
* #709 - Language entry is now created when creating a new context
* #697 - Module Builder now warns about any names which pass a `class_exists()` check
* #687 - user-meta fields doesn't respect "required"
* #637 - Bonfire admin topbar CSS breaks scrolling features (Firefox)
* #612 - Modifying set created and set modified from models
* #578 - modulebuilder config: 3 "defaults" not used (except for some error handling which will cause confusion)

#### Additional Changes:

* Updated CodeIgniter to version 2.2
* Updated list of countries in /application/config/address.php and added state codes for a few more countries.
* Separated all Bonfire code from your Application's code
* Most Bonfire-specific code now uses a `BF_` prefix instead of the `MY_` prefix your application would use.
* Removed Settings related to the previously removed Updates module
* The user register form can be modified to include hidden fields named `register_url` and `login_url` that will override the current `REGISTER_URL` and `LOGIN_URL` values, respectively, to make it simpler to use the existing logic in custom modules.
* Added 4th parameter to `timezone_menu` in the date helper to allow passing additional attributes, such as an ID, for the generated select element. Updated the `user_fields` view to make sure of this parameter (and fix an accessibility error on the view).
* Application Helper:
    * The `module_*` methods in the `application_helper` have been moved to the Modules class. The `application_helper` methods are still there but are deprecated.
    * Deprecated `form_has_error()` method since CI's `form_error()` does the same thing.
* Assets Library:
    * Replaced `Assets::$asset_base`, `Assets::$asset_cache_folder`, and `Assets::$asset_folders` with a single `Assets::$directories` array (these were not deprecated because they were private properties). Allow a config item (`'assets.directories'`) to set the value of the new property, but if it is not set the library will attempt to use the old config items (`'assets.base_folder'`, `'assets.cache_folder'`, and `'assets.asset_folders'`).
    * Added closures to hold the functions for CSS/JS minification, which should facilitate swapping out the libraries that perform these functions in the future.
    * Deprecated `Assets::set_globals()`, `Assets::$external_scripts`, `Assets::$inline_scripts`, and `Assets::$module_scripts`. Replaced by `Assets::setGlobals()` and `Assets::$scripts['external']`/`Assets::$scripts['inline']`/`Assets::$scripts['module']`
* Database module:
    * Added support for bfmysqli driver.
    * Changed language files to use `database_` prefix instead of `db_` prefix to prevent conflicts with CodeIgniter's language files.
    * Moved CSS out of the views.
* Docs:
    * Docs system is much more robust now, including link fixing, separating application/developer docs in the UI, a basic search system, and more.
    * Added configuration options to docs module to set the environments in which the docs will be displayed and the name of the toc file.
    * Fixed an issue in the docs module that prevented the sidebar links from building properly for application or developer docs if a toc file was not used.
* Migrations:
    * The Migrations library now only requests version information which hasn't previously been retrieved by the library on the current request (2 static properties maintain the library's schema version and the current versions of the modules and the app/core migrations).
    * Significantly reduced the impact of using `migrate.auto_core` and `migrate.auto_app` when multiple controllers are loaded which inherit from `Base_Controller`.
    * Improved the load time of the migrations view (especially when a large number of modules are installed).
    * Fixed an error in the `version()` method when an error occurred in `glob()` while retrieving the migrations for a given step (see comment in the code for more details).
    * When running multiple migrations, only update the version in the database once.
    * Reduced the number of methods in the library with knowledge of the database table's structure.
    * Overhauled the library's API:
        * Added:
            * `getErrorMessage()` - return the most recent error message
            * `getErrors()` - return all errors
            * `getModuleVersions()` - retrieve the current version of all modules in one call
            * `Migrations::APP_MIGRATION_PREFIX` constant - the prefix used for app migrations (`'app_'`)
            * `Migrations::CORE_MIGRATIONS` constant - the migration type used for core migrations (`'core'`)
            * `Migrations::MAX_SCHEMA_VERSION` - the maximum version of the schema_version table supported by the library (`3`)
        * Deprecated (all of the below still work, but eventually will be removed, their performance may also be degraded, as the methods are now pass-throughs to the new methods):
            * `$error` property (use `getErrorMessage()`)
            * `auto_latest()` - use `autoLatest()`
            * `do_sql_migration()` - use `doSqlMigration()`
            * `get_available_versions()` - use `getAvailableVersions()`
            * `get_latest_version()` - use `getVersion($type, true)`
            * `get_schema_version()` - use `getVersion($type)`
            * `set_verbose()` - use `setVerbose()`
* Translations:
    * Partial Russian and Italian translations.
    * Many improvements to the Brazilian Portugeuse language handling.
    * Improved support for translations in the Activities, Builder, Logs, Sysinfo, and UI modules.
* Template Library:
    * Added `setLayout()` and `getLayout()` methods, deprecated the public `$layout` property (it will become private or protected in a future version).

#### Known Issues:

* If a module is named `'application'` or `'developer'`, the docs module won't be able to generate links to any documents which reside in that module.


### 0.7.0

* Minimum PHP version changed to 5.3
* Updated CodeIgniter to version 2.1.4
* Updated Bootstrap to version 2.3.2
* Updated .htaccess sections taken from html5boilerplate, supporting the new Apache 2.4.
* Updated jwerty to version 0.3.1 (seems to fix issue where creating a shortcut for '&' kills keyboard input on all admin pages)

#### New Features:

* [New Folder Structure](site_structure)
* The password hash changes from SHA1 (salted, but not iterated) to [phpass](http://www.openwall.com/phpass/) libraries. When on PHP 5.3 or with the Suhosin patch installed, Blowfish encryption is used. If bcrypt is not available, will fallback to extended DES-based hashes, or even to phpass' provided (but slower) hash method, if necessary.
* [MY_Model](bonfire_models) now supports Observer methods that allow your model to more easily hook into the default operations without having to rewrite the code.
* MY_Model now supports protecting attributes so they can never be set or updated.
* MY_Model handles return types differently now. Instead of passing to the functions, it's a class var that each model can set. Defaults to object. Can use new scope methods 'as_array', 'as_object()' and 'as_json()' within a call to change the output type temporarily.
* Template library's parser now includes the [Lex template language](https://github.com/pyrocms/lex) when $parse_views == TRUE in the Template.php library.
* Added get_filenames_by_extension() method to the file helper.
* MY_Controller file no longer used as we have split each controller into their own class file in application/core. This allows the HMVC autoloader to find them for us, and frees up the MY_Controller file for end-user only use.
* New front-end theme that is simply the latest Bootstrap theme with a couple of small minor tweaks.
* log_activity helper function included in the application helper. This removes the need for autoloading the activity model and also allows logging of activities to be turned off.
* New constants LOGIN_URL and REGISTER_URL are used in place throughout the site to make it easy to change the login and registration URLs.
* New docs system that provides basic methods for packaging documentation with your modules and application using Markdown formatted text files.

#### Closes named issues:

* #598 - Fix password help message for number required
* #557 - When logging in, check the user isn't banned
* #489 - users index: form buttons only worked for English users
* #575 - Add bf_action_edit and bf_action_undo to language file
* #580 - Add Portugese language for activities module
* #583 - Set display name when entered in the self-service user registration form
* #613 - module builder: SQL error while creating field type 'enum' and 'a', 'b', 'c' as values
* #631 - redirection to install folder fails (certain browser?)
* #617 - combine js doesn't work (when enabled in config)
* #674 - user meta should fallback to current-user if no user provided
* #672 - user meta is considered "empty" if the value is "0"
* #678 - save_meta_for returns void, but it should indicate success/failure
* #683 - Create Module from existing DB Table doesn't generate correct form field IDs
* #680 - Gravatar default images don't work for non-public sites
* #664 - Translate editor: saving sysinfo_lang.php causes CodeIgniter error
* #708 - UI module causes error when admin profiler is disabled
* #720 - builder: don't insist on providing a "length" for INT fields
* #727 - users index page: Filter wont go along with pagination
* #749 - Don't silently ignore values of password_min_length less than 8 (issue #749)
* #750 - AUTO_INCREMENT portability, fixes install errors seen on Percona Server
* #751 - Fix SITE_AREA/ homepage, if you have changed SITE_AREA
* #759 - username validation is bypassed when !auth.use_usernames
* #767 - Don't allow deleting the default role, it causes problems
* #785 - email activation can fail with "The URI you submitted has disallowed characters"

#### Additional changes:

* $table in MY_Model changed to $table_name to avoid potential conflict with the table library.
* database backup - fix effect of yes/no dropdowns for languages other than English
* database backup: .gzip doesn't work very well; use .gz instead
* Template::redirect() - fix escaping of url [XSS?]
* Template::redirect() - should not require javascript support, unless the current request was by ajax
* Template::render() - ajax responses - remove incorrect Content-Type and caching override headers
* builder generated index_front: escape values from user-editable data [XSS]
* users auth library: fix has_permission() when called with a role parameter
* users module: deactivate() shouldn't send "user activated" email
* users module: deactivate() should not show an error when it succeeds
* users login: don't show error when auth.allow_register==FALSE
* users forgotten password: fix "Cannot find that email in our records" message
* default theme: don't show "Sign up" link if auth.allow_register==FALSE
* fix "remember me" in modal_login
* ctrl+s should not perform dangerous actions like deleting tables, because the user might have intended to invoke a harmless browser shortcut
* tentatively standardized on `isset($_POST['save'])` when testing submit buttons.  It's not too ugly, especially when you understand the pitfalls of the alternatives.
* $return_type removed from the user_model functions: find, find_all, and find_by. Use as_array() instead.
* Template::yield() changed to Template::content() due to the addition of generators in PHP 5.5.

#### Upgrade notes

Migrations should be set to auto-run in the application config file so that the new password hash is enforced before you try to login the first time after updating. The migrations force all users to create new passwords so that the newer encryption methods are used.

The "ban" button in the users page is working now, and the permission `Site.Signin.Allow` has been removed.  If you created a custom role which banned logins by excluding this permission, they will now be able to log in.

If you attempt to downgrade to 0.6.*, Bonfire will at best restore `Site.Signin.Allow` to the Administrator role. No other role will be able to log in.  This was written with developers in mind, not downgrading a production system.  (But it would be interesting to hear any feedback).

The comment recommending `IS_AJAX` as a security check has been removed.  `IS_AJAX` is not effective as a security check.  It may have happened to prevent CSRF on AJAX methods, but Bonfire now supports CodeIgniter CSRF protection (see upgrade notes for 0.6.1).  For other purposes, you may prefer to avoid the Bonfire-specific constant in favour of the standard CodeIgniter method `$this->input->is_ajax_request()`.

Because the `MY_Controller` file no longer ships with Bonfire, you should make a backup of your current `MY_Controller` file, if you have made any changes. This file will be renamed to `Base_Controller.php`. Any changes you made should then be redistributed over the new Controller files in application/core.

If you use the $table class var within any of your module's model files, you will need to change that reference to $table_name.

If your module calls the `activity_model` for logging purposes, you will need to either switch the code to the new `log_activity()` helper method or load the `activity_model` explicitly.

All templates that use the current `Template::yield()` function must be updated to `Template::content()` due to the addition of generators in PHP 5.5.


### 0.6.1

#### Regression fixes

- Permissions Matrix silently didn't work - issue #596
- Redirect after login - was redirecting to '/', regardless of the previous page or per-role redirect settings
- Site settings failed to save sometimes, under PHP 5.4 - issue #618

#### Upgrade Notes:

Version 0.6 prevented CSRF attacks using a standard CodeIgniter option.  This should protect against clicking a malicious link (e.g. in an email or forum post), which attempts to perform actions on Bonfire.  E.g. deleting modules or changing user access rights.

When upgrading to Bonfire 0.6.1, you should make sure to update config.php in the application/config folder.  This is necessary in order to enable and configure CSRF protection.

As a result, any AJAX POST request you have will need to include the CSRF token.  If you don't already know how to do this, Bonfire 0.6.1 includes a simple solution.  You just need two extra lines.

In the controller for the page which launches the AJAX request:

    Assets::add_js('codeigniter-csrf.js')

In the AJAX request, an extra data field:

    // assuming your data is not passed as a string
    $.ajax({ ..., type: "POST", data:
                   { ... 'ci_csrf_token' : ci_csrf_token() } } );
    // or
    $.post(url,
                   { ... 'ci_csrf_token' : ci_csrf_token() }, ... );
    // or
    $(elt).load(url,
                   { ... 'ci_csrf_token' : ci_csrf_token() } );

### Version 0.6

#### Additions:

- Major UI Upgrade to use Twitter Bootstrap
- Application and other settings are moved into the DB
- Renaming Module Builder to Code Builder
- Adding Context creation UI to Code Builder
- Many security improvements
- Many User Profile improvements including password strength and email verification
- Language preferences
- Cleanup of permissions
- Language Translation UI
- Custom User Fields
- More Keyboard Shortcuts
- Installer improvements
- Many other bug fixes and improvements


### Version 0.5.2

#### Additions:

- Fix for parse_views in the Template library
- Change text to use lang file - issue #360
- Fixing Database backups - issue #362
- Cleanup of helpers - issue #366
- Adding Persian language text - issue #324
- Change to the version message in /admin/developer/update


### Version 0.5.1

#### Additions:

- Hotfix for issue in Module Builder
- Edit Profile for the front end user - issue #197
- Mode_rewrite check in the installer to set the base_url - issue #52
- Adding extra functionality to the config_file_helper to allow reading and writing of module config files
- Config setting for front end controller which is checked inside the MY_Controller/Front_Controller
- Adding "matches_pattern" method to Form Validation to ensures a string matches a basic pattern
- Upgrading the Migrations DB schema (issue #180) so that we use records instead of DB table columns for the modules
- Moved the Settings into the DB
- Frontend User Profile editing
- New Installer
- Module Builder - various improvements, including Creating a module from an existing DB table
- Updated jquery.forms to 2.94
- Emailer improvements
- Localization improvements, including Persian language files (thanks to Github user "sajjad-ser")


### Version 0.5

#### Additions:

- Edit Profile for the front end user - issue #197
- Mode_rewrite check in the installer to set the base_url - issue #52
- Adding extra functionality to the config_file_helper to allow reading and writing of module config files
- Config setting for front end controller which is checked inside the MY_Controller/Front_Controller
- Adding "matches_pattern" method to Form Validation to ensures a string matches a basic pattern
- Upgrading the Migrations DB schema (issue #180) so that we use records instead of DB table columns for the modules
- Moved the Settings into the DB
- Frontend User Profile editing
- New Installer
- Module Builder - various improvements, including Creating a module from an existing DB table
- Updated jquery.forms to 2.94
- Emailer improvements
- Localization improvements, including Persian language files (thanks to Github user "sajjad-ser")

#### Bugs:

- #236 - Email Settings - fixing the password validation
- #252 - MY_Form_Validation unique() not working
- #260 - `<ul>` tag was closed with `</li>`
- #262 - Front End User Profile update adds table prefix
- #265 - Installer config_file_helper Setup Bug
- #266 - Module Builder: Creating Module
- #276 - Module creation with field with uppercase name
- #282 - Using 'unique' rule on form validation
- #254 - Module Builder - Validation of Decimal fields
- #286 - Backend, Module: create new: wait animation doesn't disappear
- #284 - Delete Module fails
- #288 - Changed "Remember me for two weeks" to "Remember me"
- #290 - First name and last name were previously not being added to the database
- #301 - Fix Line 27 $error_message undefined variable modulebuilder
- #294 - Modulebuilder: Can not delete module
- #239 - Developer Context Fails to Delete Update Cache File
- #292 - Installer locations
- #303 - Emailer queue error
- #304 - Assets::add_module_js() causes fatal error unknown function module_file_path()
- #306 - Fixed redirect on auth->restrict & Fixed database module anchors
- #302 - Horizontal scroll bar appearing in FF
- #307 - Emailer: SMTP Password format error
- #293 - Saving email settings
- #295 - Modulebuilder: form validation callback functions do not work
- #309 - Convert Some text to language file & some fix & RTL layout
- #310 - Fixed Database Module
- #300 - Installer : Assets cache should be writeable
- #314 - Disallow spaces in the keyboard shortcuts


### Version 0.2

#### Additions:

- Major revamp to the ModuleBuilder
- Added new date_difference function to MY_date_helper.
- Removed the Tester module and replaced with integrated SimpleTest suite.
- You can now view and delete system-wide activities in the Reports context.

#### Enhancements:

- Emailer has better tools for testing the Queue.
- Activities logged for more activities throughout Bonfire.
- Added soft_deletes, created and modified fields to ModuleBuilder.
- ModuleBuilder creates better localized files.
- ModuleBuilder now automatcially runs the migration files for a new module.
- Permission names are now allowed to be 255 chars long (up from 50).
- where() method in MY_Model now accepts arrays or custom strings as parameter.
- Better permissions in place to prevent users of other roles from modifying roles you don't want them to.
- Added set_default_theme() method and parameter to Template lib.
- Installer improvements to make it more usable and stable (we hope)
- Creating and deleting roles adds/removes proper permissions to the system.

#### Bugs:

- Fixed bug in MY_Form_Validation to allow the form validation library to work with Modular Extensions HMVC. See http://www.mahbubblog.com/php/form-validation-callbacks-in-hmvc-in-codeigniter/ for more details.
- User module plays better with various display/login options
- User module allows for saving empty usernames.
- Permission matrix now no longer throws PHP error in certain cases.
- If no user existed with provided email/username, the system was throwing PHP errors instead of displaying the proper error to the user.
- Assets lib no longer using site_url() to build file names and causing errors.
- Fixed broken path for asset caching.
- Assets lib now properly finds module assets for both core and user modules.
- Assets lib now works under SSL conditions.
- Email Template now saves to correct location.
- Emailer now correctly sends HTML emails from the queue instead of text-only.
- User_model now returns correct 'deleted' flag (instead of the role's deleted flag).
- Auth library now loads the User model if it's not already loaded.
- Users can no longer delete themselves.
- Modulebuilder no longer spits out html char codes for single quotes in generated migration SQL.
- Modulebuilder now creates safer code when using cdeditor.
- Reports context now checks for proper permisisons (instead of checking for content context permissions).
- Deleted users can no longer log into the system.
- When changing the name of a role, the corresponding Permission.[ROLENAME].Manage now updated to reflect the new name.
- Base_Controller now actually populates the previous_page url.
- Profile no longer causing errors in IE7.
- Removed E_STRICT from default error_reporting as it was reporting errors from CodeIgniter and HMVC that we had no control over.


### Version 0.2-RC1

Released: August 11, 2011

#### Additions:

- The Emailer class has a new setting, html or text email format.
- Emailer class now has a way to test your email settings.
- Emailer class has allows you to view the unsent emails in your queue under Statistics / Emailer.
- Updated email class to wrap the $message in the email template by default.
- A new `Unit Testing` framework has been started. Currently supports Unit Testing only. Web/Functional Testing coming later.
- A new `System Events` feature has been added.
- A logit() function was added to the application helper to provide a simple interface to log to both the Console and the log file.
- Added new Activities module to allow other modules a core utility for tracking user activity. Was incorporated into the Users module.
- Added a new Module Builder module that helps create skeleton code for new modules.
- Now has a proper AJAX loading display.
- Adding Activity records to the Module Builder

#### Enhancements:

- Forgot Password feature was revised for better security.
- Users can have countries stored with their profile. A country table added to db as well as a country_select function to the helper.
- Migrations has better interface. Supports both up/down migrations anytime.
- Migrations have been separated so that the core Bonfire migrations are separate from the app-specific migrations.
- Each module can maintain it's own set of migrations.
- Modules can have a settings array that allows for drop-down menus, custom names, and more. Added module_config() method to application helper.
- Initial translation to Portuguese.
- Bonfire core database migrations moved to migrations/core/ to remove conflicts between your app and Bonfire.
- Roles can now be set as deletable or not in the UI. New Permission: Bonfire.Roles.Delete to use to check if the role is allowed to delete other roles.
- Improved the installer - user brought to the install page if the app hasn't been installed already, allows user to choose which environment is being installed, tries to set the file and folder permissions.
- Adding created_on and modified_on into protected variable of the BF_Model
- Adding activity log when saving the App settings
- Moved site, update and email based config settings into the new settings database table - this removed the need for the config/email.php

#### Bugs:

- Upgraded to the latest version of head.js to fix a bug where AJAX page loads were not working in FF3 and IE.
- Misc. bugfixes in the install routine that should allow for smooth installation in a larger number of cases.
- Deleted users can now be restored.
- Controller override function now doesn't override AJAX layouts.
- Fixed admin theme styles for IE7 and IE8.
- Install no longer fails on unsuccessful migration.
- Timezone settings checked and optionally set by date_default_timezone_set() because of PHP5.3 in index.php.
- Creating new user now correctly checks for existing email address.
- Tester now correctly runs tests from multiple modules at once.
- Migrations now checks that the application helper is loaded.
- Config helper now allows apostrophes in config items.
- HMVC modules locations array moved to application/config.php so it won't be overwritten in the future.
- write_config() function now writes backups to application/archives like it should have been.

### Version 0.1 - Initial Release
Released: March 30, 2011