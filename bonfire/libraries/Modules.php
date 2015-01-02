<?php defined('BASEPATH') || exit('No direct script access allowed');

global $CFG;

/* Get module locations from config settings, or use the default module location
 * and offset
 */
is_array(Modules::$locations = $CFG->item('modules_locations')) || Modules::$locations = array(
    realpath(APPPATH) . '/modules/' => '../../application/modules/',
    realpath(BFPATH) . '/modules/' => '../../bonfire/modules/',
);

/* PHP5 spl_autoload */
spl_autoload_register('Modules::autoload');

/**
 * Modules class for Bonfire.
 *
 * Adapted from Wiredesignz Modular Extensions - HMVC.
 * @link https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc
 *
 * Provides utility functions for working with modules, as well as an
 * autoloader that can be used throughout the system. Inspired by, and,
 * to some extent, provides backwards-compatibility with the Modules class
 * provided by WireDesignz HMVC package that we used to use.
 *
 */
class Modules
{
    public static $locations;
    public static $registry;
    public static $routes;

    /**
     * Run a module controller method. Output from module is buffered and
     * returned.
     *
     * @param string $module The module/controller/method to run.
     *
     * @return mixed    The output from the module.
     */
    public static function run($module)
    {
        $method = 'index';

        if (($pos = strrpos($module, '/')) != false) {
            $method = substr($module, $pos + 1);
            $module = substr($module, 0, $pos);
        }

        if ($class = self::load($module)) {
            if (method_exists($class, $method)) {
                ob_start();
                $args = func_get_args();
                $output = call_user_func_array(
                    array($class, $method),
                    array_slice($args, 1)
                );
                $buffer = ob_get_clean();
                return $output !== null ? $output : $buffer;
            }
        }

        log_message('error', "Module controller failed to run: {$module}/{$method}");
    }

    /**
     * Load a module controller.
     *
     * @param string $module The module/controller to load.
     *
     * @return mixed    The loaded controller.
     */
    public static function load($module)
    {
        is_array($module) ? list($module, $params) = each($module) : $params = null;

        // Get the requested controller class name.
        $alias = strtolower(basename($module));

        // Create or return an existing controller from the registry.
        if (! isset(self::$registry[$alias])) {
            // Find the controller.
            list($class) = CI::$APP->router->locate(explode('/', $module));

            // Controller cannot be located.
            if (empty($class)) {
                return;
            }

            // Set the module directory.
            $path = APPPATH . 'controllers/' . CI::$APP->router->directory;

            // Load the controller class.
            $class = $class . CI::$APP->config->item('controller_suffix');
            self::load_file($class, $path);

            // Create and register the new controller.
            $controller = ucfirst($class);
            self::$registry[$alias] = new $controller($params);
        }

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
        // Don't autoload CI_ prefixed classes or those using the config subclass_prefix
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

        // Autoload Bonfire Core classes.
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
     * @param string $file   The filename.
     * @param string $path   The path to the file.
     * @param string $type   The type of file.
     * @param mixed  $result
     *
     * @return mixed
     */
    public static function load_file($file, $path, $type = 'other', $result = true)
    {
        $file = str_replace('.php', '', $file);
        $location = "{$path}{$file}.php";

        if ($type === 'other') {
            if (class_exists($file, false)) {
                log_message('debug', "File already loaded: {$location}");
                return $result;
            }
            include_once $location;
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
        $segments   = explode('/', $file);
        $file       = array_pop($segments);
        $file_ext = pathinfo($file, PATHINFO_EXTENSION) ? $file : "{$file}.php";

        $path = ltrim(implode('/', $segments).'/', '/');
        $module ? $modules[$module] = $path : $modules = array();

        if (! empty($segments)) {
            $modules[array_shift($segments)] = ltrim(implode('/', $segments) . '/', '/');
        }

        foreach (Modules::$locations as $location => $offset) {
            foreach ($modules as $module => $subpath) {
                $fullpath = "{$location}{$module}/{$base}{$subpath}";

                if ($base == 'libraries/'
                    && is_file($fullpath . ucfirst($file_ext))
                ) {
                    return array($fullpath, ucfirst($file));
                }

                if (is_file($fullpath.$file_ext)) {
                    return array($fullpath, $file);
                }
            }
        }

        return array(false, $file);
    }

    /** Parse module routes **/
    public static function parse_routes($module, $uri)
    {
        /* load the route file */
        if (! isset(self::$routes[$module])) {
            if (list($path) = self::find('routes', $module, 'config/') and $path) {
                self::$routes[$module] = self::load_file('routes', $path, 'route');
            }
        }

        if (! isset(self::$routes[$module])) {
            return;
        }

        /* parse module routes */
        foreach (self::$routes[$module] as $key => $val) {

            $key = str_replace(array(':any', ':num'), array('.+', '[0-9]+'), $key);

            if (preg_match('#^'.$key.'$#', $uri)) {
                if (strpos($val, '$') !== false and strpos($key, '(') !== false) {
                    $val = preg_replace('#^'.$key.'$#', $val, $uri);
                }

                return explode('/', "{$module}/{$val}");
            }
        }
    }

    /**
     * Determines whether a controller exists for a module.
     *
     * @param $controller string The name of the controller to look for (without
     * the .php).
     * @param $module string The name of module to look in.
     *
     * @return boolean
     */
    public static function controller_exists($controller = null, $module = null)
    {
        if (empty($controller) || empty($module)) {
            return false;
        }

        // Look in all module paths.
        $folders = Modules::folders();
        foreach ($folders as $folder) {
            if (is_file("{$folder}{$module}/controllers/{$controller}.php")) {
                return true;
            }
        }

        return false;
    }

    /**
     * Finds the path to a module's file.
     *
     * @param $module string The name of the module to find.
     * @param $folder string The folder within the module to search for the file
     * (ie. controllers).
     * @param $file string The name of the file to search for.
     *
     * @return string The full path to the file.
     */
    public static function file_path($module = null, $folder = null, $file = null)
    {
        if (empty($module) || empty($folder) || empty($file)) {
            return false;
        }

        $folders = Modules::folders();
        foreach ($folders as $module_folder) {
            $test_file = "{$module_folder}{$module}/{$folder}/{$file}";

            if (is_file($test_file)) {
                return $test_file;
            }
        }
    }

    /**
     * Returns the path to the module and its specified folder.
     *
     * @param $module string The name of the module (must match the folder name).
     * @param $folder string The folder name to search for (Optional).
     *
     * @return string The path, relative to the front controller.
     */
    public static function path($module = null, $folder = null)
    {
        $folders = Modules::folders();
        foreach ($folders as $module_folder) {
            if (is_dir($module_folder . $module)) {
                if (! empty($folder)
                    && is_dir("{$module_folder}{$module}/{$folder}")
                ) {
                    return "{$module_folder}{$module}/{$folder}";
                } else {
                    return "{$module_folder}{$module}/";
                }
            }
        }
    }

    /**
     * Returns an associative array of files within one or more modules.
     *
     * @param $module_name string If not NULL, will return only files from that
     * module.
     * @param $module_folder string If not NULL, will return only files within
     * that folder of each module (ie 'views')
     * @param $exclude_core boolean Whether we should ignore all core modules.
     *
     * @return array An associative array, like:
     * array('module_name' => array('folder' => array('file1', 'file2')))
     */
    public static function files($module_name = null, $module_folder = null, $exclude_core = false)
    {
        if (! function_exists('directory_map')) {
            get_instance()->load->helper('directory');
        }

        $files = array();

        foreach (Modules::folders() as $path) {
            // If excluding core modules, skip the core module folder
            if ($exclude_core && strpos($path, 'bonfire/modules') !== false) {
                continue;
            }

            // Only map the whole modules directory if $module_name isn't passed.
            if (empty($module_name)) {
                $modules = directory_map($path);
            } elseif (is_dir($path . $module_name)) {
                // Only map the $module_name directory if it exists
                $path = $path . $module_name;
                $modules[$module_name] = directory_map($path);
            }

            // If the element is not an array, it's a file, so ignore it,
            // otherwise it is assumbed to be a module.
            if (empty($modules) || ! is_array($modules)) {
                continue;
            }

            foreach ($modules as $mod_name => $values) {
                if (is_array($values)) {
                    if (empty($module_folder)) {
                        // Add the entire module
                        $files[$mod_name] = $values;
                    } elseif (isset($values[$module_folder])
                        && count($values[$module_folder])
                    ) {
                        // Add just the specified folder for this module
                        $files[$mod_name] = array(
                            $module_folder  => $values[$module_folder],
                        );
                    }
                }
            }
        }

        return count($files) ? $files : false;
    }

    /**
     * Returns the 'module_config' array from a modules config/config.php file.
     * The 'module_config' contains more information about a module, and even
     * provide enhanced features within the UI. All fields are optional.
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
     * @param $module_name string The name of the module.
     * @param $return_full boolean If true, will return the entire config array.
     * If false, will return only the 'module_config' portion.
     *
     * @return array An array of config settings, or an empty array if empty/not
     * found.
     */
    public static function config($module_name = null, $return_full = false)
    {
        $config_param = array();
        $config_file = Modules::file_path($module_name, 'config', 'config.php');

        if (file_exists($config_file)) {
            include($config_file);

            // Check for the optional module_config and serialize if exists.
            if (isset($config['module_config'])) {
                $config_param = $config['module_config'];
            } elseif ($return_full === true && isset($config) && is_array($config)) {
                $config_param = $config;
            }
        }

        return $config_param;
    }

    /**
     * Returns an array of the folders that modules are allowed to be stored in.
     * These are set in *bonfire/application/third_party/MX/Modules.php*.
     *
     * @return array The folders that modules are allowed to be stored in.
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
        if (! function_exists('directory_map')) {
            get_instance()->load->helper('directory');
        }

        $map = array();
        foreach (Modules::folders() as $folder) {
            // If excluding core modules, skip the core module folder.
            if ($exclude_core && strpos($folder, 'bonfire/modules') !== false) {
                continue;
            }

            $dirs = directory_map($folder, 1);
            if (! is_array($dirs)) {
                $dirs = array();
            }

            $map = array_merge($map, $dirs);
        }

        // Clean out any html or php files.
        if ($count = count($map)) {
            for ($i = 0; $i < $count; $i++) {
                if (strpos($map[$i], '.html') !== false
                    || strpos($map[$i], '.php') !== false
                ) {
                    unset($map[$i]);
                }
            }
        }

        return $map;
    }
}
