# Installer

Bonfire includes the `Installer_lib` library to simplify the installation process.
For additional information, see [Installing Bonfire](developer/installation).

This documentation is primarily intended for those who wish to improve or extend the `Installer_lib`.
The library is not intended for use within an application, and should be removed once the application is installed.

## Properties

### $db_exists

Boolean property to indicate to the install controller whether the default database settings were found.

### $db_settings_exist

Boolean property to indicate to the install controller whether the database settings were found/loaded.

### $php_version

The version of the currently-running PHP parser/extension.
Set to the result of `phpversion()` by the `php_acceptable()` method.

### $mysql_client_version *Deprecated*

The version of the MySQL client.
This property does not appear to be in use and has been deprecated in Bonfire 0.7.2.

### $mysql_server_version *Deprecated*

The version of the MySQL server.
This property does not appear to be in use and has been deprecated in Bonfire 0.7.2.

## Methods

### php_acceptable($version)

Determine whether the installed version of PHP is above `$version`.
Returns `true` if the installed version is at or above `$version`, else it returns `false`.

### db_available()

Checks for the existence of the class/function required to use the posted `driver`.

### test_db_connection()

Attempts to connect ot the database given by the posted settings.
If the `db_available()` method fails or the posted settings cause a connection failure, this method will return `false`.

### is_installed()

Performs some basic checks to determine whether the application has been installed.
Each of the checks below runs in sequence, so later items are not checked if an earlier checks returns a value.
- If /application/config/installed.txt is found, returns `true`.
- If the database config could not be found, returns `false`.
- If $db['default'] has not been defined by the database config, returns `false`.
- If the default database settings could not be found, returns `false`.
- If the `'users'` table does not exist, returns `false`.
- If the `'users'` table is empty, returns `false`.
- If the method has not returned yet, returns `true`.

### cURL_enabled()

Determines whether the `curl_init()` function exists.

### check_folders([$folders])

Checks an array of folders to determine whether they are writable and returns the results in a format usable in the requirements check step of the installation process.
If `$folders` is not provided, uses the private `$writable_folders` property.

### check_files([$files])

Checks an array of files to see if they are writable and returns the results in a format usable in the requirements check step of the installation.
If `$files` is not provided, uses the private `$writable_files` property.

### setup()

Perform the actual installation:
- Loads the database.
- Installs the Bonfire core migrations.
- Configures the site settings.
- Creates the admin user.
- Creates a unique encryption key for the site.
- Runs any application module migrations.
- Creates the `installed.txt` file in `/application/config/` to speed up the process of checking whether the application is installed in the future.
