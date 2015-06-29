<?php defined('BASEPATH') || exit('No direct script access allowed');

global $CFG;

/* Get module locations from config settings. If not found, load the application
 * config file. If still not found, use the default module locations and offsets.
 */
is_array(Modules::$locations = $CFG->item('modules_locations')) ||
($CFG->load('application', false, true)
    && is_array(Modules::$locations = $CFG->item('modules_locations'))
) || Modules::$locations = array(
    realpath(APPPATH) . '/modules/' => '../../application/modules/',
    realpath(BFPATH) . '/modules/' => '../../bonfire/modules/',
);

/* PHP5 spl_autoload */
spl_autoload_register('Modules::autoload');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Modules class for Bonfire.
 *
 * Adapted from Wiredesignz Modular Extensions - HMVC.
 * @link https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc
 *
 * Provides utility functions for working with modules, as well as an autoloader
 * which can be used throughout the system. Inspired by, and, to some extent, provides
 * backwards-compatibility with the Modules class provided by WireDesignz HMVC package.
 *
 * @package Bonfire\Libraries\Modules
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer
 */
class Modules
{
    public static $locations;
    public static $registry;
    public static $routes;

    /**
     * Run a module controller method. Output from module is buffered and returned.
     *
     * @param string $module The module/controller/method to run.
     *
     * @return mixed The output from the module.
     */
    public static function run($module)
    {
        $method = 'index';

        // If a directory separator is found in $module, use the right side of the
        // separator as $method, the left side as $module.
        if (($pos = strrpos($module, '/')) != false) {
            $method = substr($module, $pos + 1);
            $module = substr($module, 0, $pos);
        }

        // Load the class indicated by $module and check whether $method exists.
        $class = self::load($module);
        if (! $class || ! method_exists($class, $method)) {
            log_message('error', "Module controller failed to run: {$module}/{$method}");
            return;
        }

        // Buffer the output.
        ob_start();

        // Get the remaining arguments and pass them to $method.
        $args = func_get_args();
        $output = call_user_func_array(array($class, $method), array_slice($args, 1));

        // Get/clean the current buffer.
        $buffer = ob_get_clean();

        // If $output is not null, return it, otherwise return the buffered content.
        return $output !== null ? $output : $buffer;
    }

    /**
     * Load a module controller.
     *
     * @param string $module The module/controller to load.
     *
     * @return mixed The loaded controller.
     */
    public static function load($module)
    {
        // If $module is an array, the first item is $module and the remaining items
        // are $params.
        is_array($module) ? list($module, $params) = each($module) : $params = null;

        // Get the requested controller class name.
        $alias = strtolower(basename($module));

        // Maintain a registry of controllers.
        // - If the controller is not found in the registry, attempt to find and
        //   load it, then add it to the registry.
        // - If the controller was already in the registry or is found, loaded,
        //   and added to the registry successfully, return the registered controller.

        if (! isset(self::$registry[$alias])) {
            // If the controller was not found in the registry, find it.
            list($class) = CI::$APP->router->locate(explode('/', $module));
            if (empty($class)) {
                // The controller was not found.
                return;
            }

            // Set the module directory and load the controller.
            $path = APPPATH . 'controllers/' . CI::$APP->router->directory;
            $class = $class . CI::$APP->config->item('controller_suffix');
            self::load_file($class, $path);

            // Create and register the new controller.
            $controller = ucfirst($class);
            self::$registry[$alias] = new $controller($params);
        }

        // Return the controller from the registry.
        return self::$registry[$alias];
    }

    /**
     * Library base class autoload.
     *
     * @param string $class The class to load.
     *
     * @return void
     */
    public static function autoload($class)
    {
        // Don't autoload CI_ prefixed classes or those using the config subclass_prefix.
        if (strstr($class, 'CI_')
            || strstr($class, config_item('subclass_prefix'))
        ) {
            return;
        }

        // Autoload Modular Extensions MX core classes.
        if (strstr($class, 'MX_')
            && is_file($location = APPPATH . 'third_party/MX/' . substr($class, 3) . '.php')
        ) {
            include_once $location;
            return;
        }

        // Autoload core classes.
        if (is_file($location = APPPATH . "core/{$class}.php")) {
            include_once $location;
            return;
        }

        // Autoload Bonfire core classes.
        if (strstr($class, 'BF_')
            && is_file($location = BFPATH . "core/{$class}.php")
        ) {
            include_once($location);
            return;
        }

        // Autoload library classes.
        if (is_file($location = APPPATH . "libraries/{$class}.php")) {
            include_once $location;
            return;
        }

        // Autoload Bonfire library classes.
        if (is_file($location = BFPATH . "libraries/{$class}.php")) {
            include_once $location;
            return;
        }
    }

    /**
     * Load a module file.
     *
     * @param string $file The filename.
     * @param string $path The path to the file.
     * @param string $type The type of file.
     * @param mixed  $result
     *
     * @return mixed
     */
    public static function load_file($file, $path, $type = 'other', $result = true)
    {
        // If $file includes the '.php' extension, remove it.
        $fileName = explode('.', $file);
        $ext = array_pop($fileName);
        if ($ext && strcasecmp($ext, 'php') === 0) {
            $file = implode('.', $fileName);
        }
        unset($ext, $fileName);

        // Ensure proper directory separators.
        $path = rtrim(rtrim($path, '/'), "\\");
        $file = ltrim(ltrim($file, '/'), "\\");

        $location = "{$path}/{$file}.php";

        if ($type === 'other') {
            if (class_exists($file, false)) {
                log_message('debug', "File already loaded: {$location}");
                return $result;
            }
            if (file_exists($location)) {
                include_once $location;
            } elseif (file_exists("{$path}/" . ucfirst($file) . '.php')) {
                include_once("{$path}/" . ucfirst($file) . '.php');
            } else {
                log_message('debug', "File not found: {$location}");
                return $result;
            }
        } else {
            // Load config or language array.
            include $location;

            if (! isset($$type) || ! is_array($$type)) {
                show_error("{$location} does not contain a valid {$type} array");
            }

            $result = $$type;
        }

        log_message('debug', "File loaded: {$location}");
        return $result;
    }

    /**
     * Find a file.
     *
     * Scans for files located within module directories. Also scans application
     * directories for models, plugins, and views. Generates fatal error if file
     * not found.
     *
     * @param string $file   The file.
     * @param string $module The module.
     * @param string $base
     *
     * @return array
     */
    public static function find($file, $module, $base)
    {
        $segments = explode('/', $file);
        $file     = array_pop($segments);
        $file_ext = pathinfo($file, PATHINFO_EXTENSION) ? $file : "{$file}.php";

        $path = ltrim(implode('/', $segments) . '/', '/');
        $module ? $modules[$module] = $path : $modules = array();

        if (! empty($segments)) {
            $modules[array_shift($segments)] = ltrim(implode('/', $segments) . '/', '/');
        }

        foreach (Modules::$locations as $location => $offset) {
            foreach ($modules as $module => $subpath) {
                $fullpath = "{$location}{$module}/{$base}{$subpath}";

                if (is_file($fullpath . $file_ext)) {
                    return array($fullpath, $file);
                }

                if (is_file($fullpath . ucfirst($file_ext))) {
                    return array($fullpath, ucfirst($file));
                }
            }
        }

        return array(false, $file);
    }

    /**
     * Parse module routes.
     *
     * @param  string $module The module.
     * @param  string $uri    The URI.
     *
     * @return mixed The parsed route or void.
     */
    public static function parse_routes($module, $uri)
    {
        // If the module's route is not already set, load the file and set it.
        if (! isset(self::$routes[$module])) {

            // The use of 'and' allows assignment to occur before the comparison,
            // so '&&' could not be used here without additional parentheses...
            // @see http://php.net/manual/en/language.operators.precedence.php

            if (list($path) = self::find('routes', $module, 'config/')
                and $path
            ) {
                self::$routes[$module] = self::load_file('routes', $path, 'route');
            }
        }

        // If the module's route is still not set, return.
        if (! isset(self::$routes[$module])) {
            return;
        }

        // Parse module routes.
        foreach (self::$routes[$module] as $key => $val) {
            // Translate the placeholders for the regEx.
            $key = str_replace(array(':any', ':num'), array('.+', '[0-9]+'), $key);

            // Parse the route.
            if (preg_match('#^'.$key.'$#', $uri)) {
                if (strpos($val, '$') !== false && strpos($key, '(') !== false) {
                    $val = preg_replace('#^'.$key.'$#', $val, $uri);
                }

                // Return the parsed route.
                return explode('/', "{$module}/{$val}");
            }
        }
    }

    /**
     * Determine whether a controller exists for a module.
     *
     * @param $controller string The controller to look for (without the extension).
     * @param $module     string The module to look in.
     *
     * @return boolean True if the controller is found, else false.
     */
    public static function controller_exists($controller = null, $module = null)
    {
        if (empty($controller) || empty($module)) {
            return false;
        }

        // Look in all module paths.
        foreach (Modules::folders() as $folder) {
            if (is_file("{$folder}{$module}/controllers/{$controller}.php")) {
                return true;
            } elseif (is_file("{$folder}{$module}/controllers/" . ucfirst($controller) . '.php')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find the path to a module's file.
     *
     * @param $module string The name of the module to find.
     * @param $folder string The folder within the module to search for the file
     * (ie. controllers).
     * @param $file   string The name of the file to search for.
     *
     * @return string The full path to the file.
     */
    public static function file_path($module = null, $folder = null, $file = null)
    {
        if (empty($module) || empty($folder) || empty($file)) {
            return false;
        }

        foreach (Modules::folders() as $module_folder) {
            if (is_file("{$module_folder}{$module}/{$folder}/{$file}")) {
                return "{$module_folder}{$module}/{$folder}/{$file}";
            } elseif (is_file("{$module_folder}{$module}/{$folder}/" . ucfirst($file))) {
                return "{$module_folder}{$module}/{$folder}/" . ucfirst($file);
            }
        }
    }

    /**
     * Return the path to the module and its specified folder.
     *
     * @param $module string The name of the module (must match the folder name).
     * @param $folder string The folder name to search for (Optional).
     *
     * @return string The path, relative to the front controller.
     */
    public static function path($module = null, $folder = null)
    {
        foreach (Modules::folders() as $module_folder) {
            // Check each folder for the module's folder.
            if (is_dir("{$module_folder}{$module}")) {
                // If $folder was specified and exists, return it.
                if (! empty($folder)
                    && is_dir("{$module_folder}{$module}/{$folder}")
                ) {
                    return "{$module_folder}{$module}/{$folder}";
                }

                // Return the module's folder.
                return "{$module_folder}{$module}/";
            }
        }
    }

    /**
     * Return an associative array of files within one or more modules.
     *
     * @param $module_name   string  If not null, will return only files from that
     * module.
     * @param $module_folder string  If not null, will return only files within
     * that sub-folder of each module (ie 'views').
     * @param $exclude_core  boolean If true, excludes all core modules.
     *
     * @return array An associative array, like:
     * <code>
     * array(
     *     'module_name' => array(
     *         'folder' => array('file1', 'file2')
     *     )
     * )
     */
    public static function files($module_name = null, $module_folder = null, $exclude_core = false)
    {
        // Ensure the bcDirectoryMap() function is available.
        if (! function_exists('bcDirectoryMap')) {
            get_instance()->load->helper('directory');
        }

        $files = array();
        foreach (Modules::folders() as $path) {
            // If excluding core modules, skip the core module folder.
            if ($exclude_core
                && stripos($path, 'bonfire/modules') !== false
            ) {
                continue;
            }

            // Only map the whole modules directory if $module_name isn't passed.
            if (empty($module_name)) {
                $modules = bcDirectoryMap($path);
            } elseif (is_dir($path . $module_name)) {
                // Only map the $module_name directory if it exists.
                $path = $path . $module_name;
                $modules[$module_name] = bcDirectoryMap($path);
            }

            // If the element is not an array, it's a file, so ignore it. Otherwise,
            // it is assumbed to be a module.
            if (empty($modules) || ! is_array($modules)) {
                continue;
            }

            foreach ($modules as $modDir => $values) {
                if (is_array($values)) {
                    if (empty($module_folder)) {
                        // Add the entire module.
                        $files[$modDir] = $values;
                    } elseif (! empty($values[$module_folder])) {
                        // Add just the specified folder for this module.
                        $files[$modDir] = array(
                            $module_folder => $values[$module_folder],
                        );
                    }
                }
            }
        }

        return empty($files) ? false : $files;
    }

    /**
     * Returns the 'module_config' array from a modules config/config.php file.
     *
     * The 'module_config' contains more information about a module, and even
     * provides enhanced features within the UI. All fields are optional.
     *
     * @author Liam Rutherford (http://www.liamr.com)
     *
     * <code>
     * $config['module_config'] = array(
     *  'name'          => 'Blog',          // The name that is displayed in the UI
     *  'description'   => 'Simple Blog',   // May appear at various places within the UI
     *  'author'        => 'Your Name',     // The name of the module's author
     *  'homepage'      => 'http://...',    // The module's home on the web
     *  'version'       => '1.0.1',         // Currently installed version
     *  'menu'          => array(           // A view file containing an <ul> that will be the sub-menu in the main nav.
     *      'context'   => 'path/to/view'
     *  )
     * );
     * </code>
     *
     * @param $module_name string  The name of the module.
     * @param $return_full boolean Ignored if the 'module_config' portion exists.
     * Otherwise, if true, will return the entire config array, else an empty array
     * is returned.
     *
     * @return array An array of config settings, or an empty array.
     */
    public static function config($module_name = null, $return_full = false)
    {
        // Get the path of the file and determine whether it exists.
        $config_file = Modules::file_path($module_name, 'config', 'config.php');
        if (! file_exists($config_file)) {
            return array();
        }

        // Include the file and determine whether it contains a config array.
        include($config_file);
        if (! isset($config)) {
            return array();
        }

        // Check for the optional module_config and serialize if exists.
        if (isset($config['module_config'])) {
            return $config['module_config'];
        } elseif ($return_full === true && is_array($config)) {
            // If 'module_config' did not exist, $return_full is true, and $config
            // is an array, return it.
            return $config;
        }

        // 'module_config' was not found and either $return_full is false or $config
        // was not an array.
        return array();
    }

    /**
     * Returns an array of the folders in which modules may be stored.
     *
     * @return array The folders in which modules may be stored.
     */
    public static function folders()
    {
        return array_keys(Modules::$locations);
    }

    /**
     * Returns a list of all modules in the system.
     *
     * @param bool $exclude_core Whether to exclude the Bonfire core modules.
     *
     * @return array A list of all modules in the system.
     */
    public static function list_modules($exclude_core = false)
    {
        // Ensure the bcDirectoryMap function is available.
        if (! function_exists('bcDirectoryMap')) {
            get_instance()->load->helper('directory');
        }

        $map = array();
        foreach (Modules::folders() as $folder) {
            // If excluding core modules, skip the core module folder.
            if ($exclude_core && stripos($folder, 'bonfire/modules') !== false) {
                continue;
            }

            $dirs = bcDirectoryMap($folder, 1);
            if (is_array($dirs)) {
                $map = array_merge($map, $dirs);
            }
        }

        $count = count($map);
        if (! $count) {
            return $map;
        }

        // Clean out any html or php files.
        for ($i = 0; $i < $count; $i++) {
            if (stripos($map[$i], '.html') !== false
                || stripos($map[$i], '.php') !== false
            ) {
                unset($map[$i]);
            }
        }

        return $map;
    }
}
