# Modules

Bonfire includes a Modules library to supplement the modules functionality included in Wiredesignz HMVC.

## Methods

Many of these methods are intended for internal use, but are public for backwards-compatibility.

### run($module)

Run a module controller method (as indicated by `$module`).
Output from module is buffered and returned.

- If `$module` contains a slash, it will be split into `$module/$method`.
- If additional parameters are passed to `Modules::Run()`, they will be passed to the method indicated by `$module`.

Returns either a directly returned value from the method or the buffered output of the method.
If the method was not found, an error is logged and nothing is returned.

### load($module)

Loads a controller in a module.
If `$module` is an array, the first item in the array will be used for `$module`, and the remaining items will be used as parameters to be passed to the method.

Returns the loaded controller, or nothing if the controller was not found.

### autoload($class)

Handles autoloading core and library classes using `MX_` and `BF_` prefixes, along with the `CI_` and `'subclass_prefix'`.

This method is registered with PHP as an autoloader when the Modules library is loaded.

### load_file($file, $path[, $type = 'other'[, $result = true]])

Load a module file.
- `$file` is the name of the file to load.
- `$path` is the path to the file.
- `$type` is the type of file, usually `'lang'`, `'config'`, or `'other'` (default).
- `$result` is the value which will be returned, unless `$type` is not `'other'`.

If `$type` is `'lang'` or `'config'`, the value of `$lang` or `$config` will be returned.

### find($file, $module, $base)

Find a file.
Looks for the file within module and application directories.
- `$file` is the file to be found.
- `$module` is the module in which the file should be located.
- `$base` is the type of file (`'models'`, `'plugins'`, `'views'`, etc.).

Returns an array with the first entry containing the full path and the second entry containing the file name.
If the file was not found, the first entry in the array will be `false`.

### parse_routes($module, $uri)

Parse a routes file for a module (from the module's `config/routes.php` file) to locate a route for a given URI.
- `$module` is the module in which the routes file should be located.
- `$uri` is the URI to be found in the routes file.

Returns the parsed route, if found, or nothing.

### controller_exists($controller, $module)

Determine whether a controller (`$controller`) exists in a given module (`$module`).
`$controller` must not include the file extension (`.php`).

This method will return `false` if either `$controller` or `$module` is empty, or if the `$controller` was not found in the `$module`.
Otherwise, it will return `true` (if the `$controller` was found in the `$module`).

### file_path($module, $folder, $file)

- `$module` The module in which the file should be located.
- `$folder` The folder within the module to search for the file (e.g. `'controllers'`).
- `$file` The name fo the file to search for (including the `.php` extension).

Returns the path to a file within a module, if it can be found.
If `$module`, `$folder`, or `$file` is empty, returns false.
If the file is not found, nothing is returned.

### path($module[, $folder = null])

- `$module` The name of the module (must match the name of the module's directory).
- `$folder` The name of the folder to find within the module's directory.

Return the path to the specified folder within the specified module.
If the folder could not be found, the module's directory is returned.
If the module could not be found, nothing is returned.

### files([$module_name = null[, $module_folder = null[, $exclude_core = false]]])

Retrieve a list of files within one or more modules.
- `$module_name` If not null, will return only files from this module.
- `$module_folder` If not null, will return only files within this sub-folder (e.g. `'config'`).
- `$exclude_core` If `true`, excludes Bonfire (core) modules.

Returns an associative array of files found in the format:

    array(
        'module_name' => array(
            'folder' => array('file1', 'file2')
        )
    )

If no files were found, returns false.

### config($module_name[, $return_full = false])

Returns the 'module_config' array from a module's `config/config.php` file.
This array can be used for custom fields in addition to fields used to supply information to Bonfire for the admin UI.

- `$module_name` The name of the module for which the config data will be retrieved.
- `$return_full` If `true` and 'module_config' is not found, the entire config array will be returned.

- Returns an empty array if:
    - the config file was not found,
    - `$config` is not set after including the config file, or
    - 'module_config' is not set and:
        - `$return_full` is false, or
        - `$return_full` is true and `$config` is not an array.
- If 'module_config' is not set, `$return_full` is false, and `$config` is an array, the value of `$config` is returned.
- Otherwise, the value of 'module_config' is returned.

### folders()

Returns an array of the folders in which modules may be stored (these are absolute paths).

### list_modules([$exclude_core = false])

Returns a list of modules in the system.

If `$exclude_core` is true, the Bonfire (core) modules will not be included in the list.

The returned array will contain the names of the folders within the module directories.

## Properties

All of these properties are intended primarily for internal use, but are public for backwards compatibility.
In most cases, one or more of the methods will return the information contained in these properties.

### $locations

An array of module locations, normally retrieved from the `'modules_location'` setting in the site's `config/application.php` file.
The array keys are the canonicalized absolute paths to the modules directories.
The values are relative paths.

The Modules library only uses the keys internally.

### $registry

A registry of loaded controllers.
The keys are the names of the controllers in lowercase.
The values are controller instances.

### $routes

An array of module routes, with the module name as the key and the routes for that module (loaded from the module's `config/routes.php` file) as the value.
