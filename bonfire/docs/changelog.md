## Under development


## Released versions

### 0.7.0

* Minimum PHP version changed to 5.3
* Updated CodeIgniter to version 2.1.4
* Updated Bootstrap to version 2.3.2
* Updated .htaccess sections taken from html5boilerplate, supporting the new Apache 2.4.
* Updated jwerty to version 0.3.1 (seems to fix issue where creating a shortcut for '&' kills keyboard input on all admin pages)

New Features:

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

Closes named issues:

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

Additional changes:

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

The "ban" button in the users page is working now, and the permission Site.Signin.Allow has been removed.  If you created a custom role which banned logins by excluding this permission, they will now be able to log in.

If you attempt to downgrade to 0.6.*, Bonfire will at best restore Site.Signin.Allow to the Administrator role. No other role will be able to log in.  This was written with developers in mind, not downgrading a production system.  (But it would be interesting to hear any feedback).

The comment recommending `IS_AJAX` as a security check has been removed.  `IS_AJAX` is not effective as a security check.  It may have happened to prevent CSRF on AJAX methods, but Bonfire now supports CodeIgniter CSRF protection (see upgrade notes for 0.6.1).  For other purposes, you may prefer to avoid the Bonfire-specific constant in favour of the standard CodeIgniter method `$this->input->is_ajax_request()`.

Because the MY_Controller file no longer ships with Bonfire, you should make a backup of your current MY_Controller file, if you have made any changes. This file will be renamed to Base_Controller.php. Any changes you made should then be redistributed over the new Controller files in application/core.

If you use the $table class var within any of your module's model files, you will need to change that reference to $table_name.

If your module calls the activity_model for logging purposes, you will need to either switch the code to the new log_activity() helper method or load the acvitity_model explicitly.

All templates that use the current Template::yield() function must be updated to Template::content() due to the addition of generators in PHP 5.5.


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

Additions:

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

Additions:

- Fix for parse_views in the Template library
- Change text to use lang file - issue #360
- Fixing Database backups - issue #362
- Cleanup of helpers - issue #366
- Adding Persian language text - issue #324
- Change to the version message in /admin/developer/update


### Version 0.5.1

Additions:

- Hotfix for issue in Module Builder


Additions:

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

Additions:

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


Bugs:

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

Additions:

- Major revamp to the ModuleBuilder
- Added new date_difference function to MY_date_helper.
- Removed the Tester module and replaced with integrated SimpleTest suite.
- You can now view and delete system-wide activities in the Reports context.

Enhancements:

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

Bugs:

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

Additions:

- The Emailer class has a new setting, html or text email format.
- Emailer class now has a way to test your email settings.
- Emailer class has allows you to view the unsent emails in your queue under Statistics / Emailer.
- Updated email class to wrap the $message in the email template by default.
- A new <Unit Testing> framework has been started. Currently supports Unit Testing only. Web/Functional Testing coming later.
- A new <System Events> feature has been added.
- A logit() function was added to the application helper to provide a simple interface to log to both the Console and the log file.
- Added new Activities module to allow other modules a core utility for tracking user activity. Was incorporated into the Users module.
- Added a new Module Builder module that helps create skeleton code for new modules.
- Now has a proper AJAX loading display.
- Adding Activity records to the Module Builder

Enhancements:

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

Bugs:

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