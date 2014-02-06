<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// PHP5 Autoloader for core files and libraries.
spl_autoload_register('Modules::autoload');

/**
 * Modules class for Bonfire.
 *
 * Provides utility functions for working with modules, as well as an
 * autoloader that can be used throughout the system. Inspired by, and,
 * to some extent, provides backwards-compatibility with the Modules class
 * provided by WireDesignz HMVC package that we used to use.
 *
 */
class Modules
{
    /**
     * @var String Path for Bonfire's "core modules"
     */
    protected static $bfModulesDir = 'bonfire/modules';

    /**
     * @var String File extension
     *
     * Since EXT is deprecated, there's no need to use/redefine a global
     * constant for this.
     */
    private static $ext = '.php';

    /**
     * Modules/routes cache
     * @var array
     */
    private static $routes = array();

    //--------------------------------------------------------------------

    /**
     * Autoloader for core files and libraries.
     *
     * @param  string $class Class to autoload.
     * @return void
     */
    public static function autoload($class)
    {
        /* Don't autoload CI_ prefixed classes or those using the config subclass_prefix */
        if (strstr($class, 'CI_') || strstr($class, config_item('subclass_prefix'))) {
            return;
        }

        /* Autoload App core classes */
        if (is_file($location = APPPATH . "core/{$class}" . self::$ext)) {
            include_once $location;
            return;
        }

        /* Autoload BF core classes */
        if (is_file($location = BFPATH . "core/{$class}" . self::$ext)) {
            include_once $location;
            return;
        }

        /* Autoload App library classes */
        if (is_file($location = APPPATH . "libraries/{$class}" . self::$ext)) {
            include_once $location;
            return;
        }

        /* Autoload Bonfire library classes */
        if (is_file($location = BFPATH . "libraries/{$class}" . self::$ext)) {
            include_once $location;
            return;
        }
    }

    /**
     * Run a controller action from another module
     *
     * @param String $module The Module/Controller/Action to run
     *
     * @return Mixed    The return value from the action
     */
    public static function run($module)
    {
        // Get the arguments to pass them along
        $args = func_get_args();

        // Use the built-in load method to handle this
        return get_instance()->load->controller($module, $args);
    }

    /**
     * Scans the module directories for a specific file.
     *
     *
     * @param  string $file   The name of the file to find.
     * @param  string $module the name of the module or modules to look in for the file.
     * @param  string $base   The path within the module to look for the file.
     * @return array          [ {full_path_to_file}, {file} ] or FALSE
     */
    public static function find($file, $module, $base)
    {
        // Find the actual file name. It will always be the last element.
        $segments   = explode('/', $file);
        $file       = array_pop($segments);
        $file_ext   = pathinfo($file, PATHINFO_EXTENSION) ? $file : $file . self::$ext;

        // Put the pieces back to get the path.
        $path = implode('/', $segments) . '/';
        $base = rtrim($base, '/') . '/';

        // Look in any possible module locations based on the string segments.
        $modules = array();
        if ( ! empty($module)) {
            $modules[$module] = $path;
        }

        // Collect the modules from the segments
        if ( ! empty($segments)) {
            $modules[array_shift($segments)] = ltrim(implode('/', $segments) . '/', '/');
        }

        // Try to find the file/module combo.
        $locations = config_item('modules_locations');
        foreach ($locations as $location) {
            foreach ($modules as $module => $subpath) {
                // Combine the elements to make an actual path to the file
                $full_path = str_replace('//', '/', "{$location}{$module}/{$base}{$subpath}");

                // If it starts with a '/' assume it's a full path already.
                if (substr($path, 0, 1) == '/' && strlen($path) > 1) {
                    $full_path = $path;
                }

                // Libraries are a special consideration since they are
                // frequently ucfirst.
                if ($base == 'libraries/' && is_file($full_path . ucfirst($file_ext))) {
                    return array($full_path, ucfirst($file));
                }

                if (is_file($full_path . $file_ext)) {
                    return array($full_path, $file);
                }
            }
        }

        return array(false, $file);
    }

    /**
     * Convenience method to return the locations where modules can be found.
     *
     * @return array The config settings array for modules_locations.
     */
    public static function folders()
    {
        return config_item('modules_locations');
    }

    /**
     * Returns a list of all modules in the system.
     *
     * @param bool $exclude_core Whether to exclude the Bonfire core modules or not
     *
     * @return array A list of all modules in the system.
     */
    public static function list_modules($exclude_core=false)
    {
        if ( ! function_exists('directory_map')) {
            $ci =& get_instance();
            $ci->load->helper('directory');
        }

        $map = array();
        $folders = Modules::folders();
        foreach ($folders as $folder) {
            // If excluding core modules and this is a core module, skip it
            if ($exclude_core && strpos($folder, self::$bfModulesDir) !== false) {
                continue;
            }

            $dirs = directory_map($folder, 1);
            if ( ! is_array($dirs)) {
                $dirs = array();
            }

            $map = array_merge($map, $dirs);
        }

        // Clean out any html or php files
        if ($count = count($map)) {
            for ($i = 0; $i < $count; $i++) {
                if (strpos($map[$i], '.html') !== false || strpos($map[$i], '.php') !== false) {
                    unset($map[$i]);
                }
            }
        }

        return $map;
    }

    /**
     * Determines whether a controller exists for a module.
     *
     * @param $controller string The name of the controller to look for (without the .php)
     * @param $module string The name of module to look in.
     *
     * @return boolean
     */
    public static function controller_exists($controller=null, $module=null)
    {
        if (empty($controller) || empty($module)) {
            return false;
        }

        // Look in all module paths
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
     * @param $folder string The folder within the module to search for the file (ie. controllers).
     * @param $file string The name of the file to search for.
     *
     * @return string The full path to the file, or false if the file was not found
     */
    public static function file_path($module=null, $folder=null, $file=null)
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

        return false;
    }

    //--------------------------------------------------------------------

    /**
     * Returns the path to the module and it's specified folder.
     *
     * @param $module string The name of the module (must match the folder name)
     * @param $folder string The folder name to search for. (Optional)
     *
     * @return string The path, relative to the front controller, or false if the folder was not found
     */
    public static function path($module=null, $folder=null)
    {
        foreach (Modules::folders() as $module_folder) {
            if (is_dir($module_folder . $module)) {
                if ( ! empty($folder) && is_dir("{$module_folder}{$module}/{$folder}")) {
                    return "{$module_folder}{$module}/{$folder}";
                }
                return $module_folder . $module . '/';
            }
        }

        return false;
    }

    /**
     * Returns an associative array of files within one or more modules.
     *
     * @param $module_name string If not NULL, will return only files from that module.
     * @param $module_folder string If not NULL, will return only files within that folder of each module (ie 'views')
     * @param $exclude_core boolean Whether we should ignore all core modules.
     *
     * @return array An associative array, like: array('module_name' => array('folder' => array('file1', 'file2')))
     */
    public static function files($module_name=null, $module_folder=null, $exclude_core=false)
    {
        if ( ! function_exists('directory_map')) {
            $ci =& get_instance();
            $ci->load->helper('directory');
        }

        $files = array();
        foreach (Modules::folders() as $path) {
            // If we're ignoring core modules and we find the core module folder... skip it.
            if ($exclude_core === true && strpos($path, 'bonfire/modules') !== false) {
                continue;
            }

            if ( ! empty($module_name) && is_dir($path . $module_name)) {
                $path = $path . $module_name;
                $modules[$module_name] = directory_map($path);
            } else {
                $modules = directory_map($path);
            }

            // If the element is not an array, we know that it's a file,
            // so we ignore it, otherwise it is assumbed to be a module.
            if ( ! is_array($modules) || ! count($modules)) {
                continue;
            }

            foreach ($modules as $mod_name => $values) {
                if (is_array($values)) {
                    // Add just the specified folder for this module
                    if ( ! empty($module_folder) && isset($values[$module_folder]) && count($values[$module_folder])) {
                        $files[$mod_name] = array(
                            $module_folder  => $values[$module_folder],
                        );
                    }
                    // Add the entire module
                    elseif (empty($module_folder)) {
                        $files[$mod_name] = $values;
                    }
                }
            }
        }

        return count($files) ? $files : false;
    }

    /**
     * Returns the 'module_config' array from a modules config/config.php
     * file. The 'module_config' contains more information about a module,
     * and even provide enhanced features within the UI. All fields are optional
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
     * @param $return_full boolean If true, will return the entire config array. If false, will return only the 'module_config' portion.
     *
     * @return array An array of config settings, or an empty array if empty/not found.
     */
    public static function config($module_name=null, $return_full=false)
    {
        $config_param = array();
        $config_file = Modules::file_path($module_name, 'config', 'config.php');

        if (file_exists($config_file)) {
            include($config_file);

            /* Check for the optional module_config and serialize if exists*/
            if (isset($config['module_config'])) {
                $config_param =$config['module_config'];
            } elseif ($return_full === true && isset($config) && is_array($config)) {
                $config_param = $config;
            }
        }

        return $config_param;
    }
}