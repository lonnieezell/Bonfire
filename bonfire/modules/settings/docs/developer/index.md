# Site Settings

**Sections**

- [User Documentation](#user)
    - [Main Settings](#main-settings)
    - [Security Settings](#security-settings)
    - [Developer Settings](#developer-settings)
    - [Extended Settings](#extended-settings)
- [Developer Documentation](#developer)
    - [Settings_lib](#settings_lib)


## User Documentation {#user}

### Main Settings {#main-settings}

#### Site Name

Displayed as part of the title for every page in the default/admin/docs themes

#### Site Email

The default email that system-generated emails are sent from

#### Site Status

Allows you to take the site offline (or bring it back online).

#### Items per page

Allows you to set the default number of items displayed by the pager.

#### Language

Allows you to choose which languages are available for selection by the user.

### Security Settings {#security-settings}

#### Allow User Registrations?

#### Activation Method

Allow you to choose the activation method for the site: None/Email/Admin

#### Login Type

Email Only/Username Only/Email or Username

#### User display across bonfire

Username/Email

#### Allow 'Remember Me'?

Determines whether the 'Remember Me' checkbox is displayed on the login page

#### Password Strength Settings

Set the minimum length of passwords allowed by the system

#### Password Options

##### Should password force numbers?

If checked, passwords must include numbers.

##### Should password force symbols?

If checked, passwords must include symbols (characters other than numbers and letters).

##### Should password force mixed case?

If checked, passwords must include lowercase and uppercase letters.

##### Display password validation labels

If checked, password validation labels will be displayed.

#### Password Stretching

2/4/8/16/31
The number of iterations used in hashing the password. Since this information is stored with the hashed password, this may be changed at any time, but will not change the stretching used on existing passwords. To force a change in this value, you would also have to force all users to reset their passwords.
See [article on password management with phpass](http://www.openwall.com/articles/PHP-Users-Passwords)

#### Force Password Resets

Using the 'Reset Now' button will force all users to reset their passwords on their next login.
It will also force you to reset your password on next login, log you out, and exit the page without saving any other settings on this page.

### Developer Settings {#developer-settings}

#### Show Admin Profiler?

If checked, the profiler will be shown on the admin pages.

#### Show Front End Profiler?

If checked, the profiler will also be shown on the front-end pages.

### Extended Settings {#extended-settings}

Administrators and/or developers can add additional settings by modifying the <tt>/application/config/extended_settings.php</tt> file.
See [Extended Settings](settings/extended_settings) documentation for more information.

## Developer Documentation {#developer}

### Settings_lib {#settings_lib}

The Settings library (Settings_lib) acts as an interface to the Settings_model and the system's configuration files. It also manages caching of the settings to reduce the need to access the database and filesystem when reading site settings.

#### <tt>item($name)</tt> {#settings_lib-item}

Retrieves the requested setting (<tt>$name</tt>) from the database. If the setting was not found in the database, it attempts to retrieve it from the config files. Caches the value so subsequent requests will retrieve it from the cache.

#### <tt>set($name, $value, $module='core')</tt>

Updates/inserts a setting in the database, and caches the new value.

#### <tt>delete($name, $module='core')</tt>

Deletes a setting from the database and removes it from the cache.

#### <tt>find_all()</tt>

Returns all of the settings in the database or the cache. If the cache is not set, initializes the cache with all of the returned settings.

#### <tt>find_by($field, $value)</tt> {#settings_lib-find_by}

Attempts to find a setting that matches the given <tt>$field</tt>/<tt>$value</tt> pair and caches the returned result.
To retrieve multiple settings that match a <tt>$field</tt>/<tt>$value</tt> pair, see [<tt>find_all_by()</tt>](#settings_lib-find_all_by).

#### <tt>find_all_by()</tt> {#settings_lib-find_all_by}

Attempts to Find all settings that match the given <tt>$field</tt>/<tt>$value</tt> pair and caches the returned results.
To retrieve a single setting that matches a <tt>$field</tt>/<tt>$value</tt> pair, see [<tt>find_by($field, $value)</tt>](#settings_lib-find_by).

#### <tt>settings_item($name)</tt> helper method

The <tt>settings_item()</tt> helper method calls [<tt>Settings_lib::item($name)</tt>](#settings_lib-item), and returns the requested setting.
