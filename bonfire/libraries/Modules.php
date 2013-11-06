<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

(defined('EXT')) OR define('EXT', '.php');

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
class Modules {

    /**
     * Autoloader for core files and libraries.
     *
     * @param  string $class Class to autoload.
     * @return void
     */
    public static function autoload($class)
    {
        /* don't autoload CI_ prefixed classes or those using the config subclass_prefix */
        if (strstr($class, 'CI_') || strstr($class, config_item('subclass_prefix')))
        {
            return;
        }

        /* autoload core classes */
        if (is_file($location = APPPATH.'core/'.$class.EXT)) {
            include_once $location;
            return;
        }

        /* autoload core classes */
        if (is_file($location = BFPATH.'core/'.$class.EXT)) {
            include_once $location;
            return;
        }

        /* autoload library classes */
        if (is_file($location = APPPATH.'libraries/'.$class.EXT)) {
            include_once $location;
            return;
        }

        /* autoload Bonfire library classes */
        if (is_file($location = BFPATH.'libraries/'.$class.EXT)) {
            include_once $location;
            return;
        }
    }

    //--------------------------------------------------------------------

    public function run($module)
    {
        // Get our arguments so we can send them along.
        $args = func_get_args();

        // Use our built-in load method to handle this.
        get_instance()->load->controller($module, $args);
    }

    //--------------------------------------------------------------------

    /**
     * Scans the module directories for a specific file.
     *
     *
     * @param  string $file   The name of the file to find.
     * @param  string $module the name of the module or modules to look in for the file.
     * @param  string $base   The path within the module to look for the file.
     * @return array          [ {full_path_to_file}, {file} ] or FALSE
     */
    public function find($file, $module, $base)
    {
        // Find our actual file name. It will always be the last element.
        $segments   = explode('/', $file);

        $file       = array_pop($segments);
        $file_ext   = (pathinfo($file, PATHINFO_EXTENSION)) ? $file : $file.EXT;

        // Put the pieces back so that we have our path.
        $path = implode('/', $segments) .'/';

        $base = rtrim($base, '/') .'/';

        // We need to look in any possible module locations
        // based on the string segments.
        $modules = array();
        if ( ! empty($module)) $modules[$module] = $path;

        // Collect the modules from our segments
        if ( ! empty($segments))
        {
            $modules[array_shift($segments)] = ltrim( implode('/', $segments) .'/', '/' );
        }

        // Try to find our file/module combo.
        $locations = config_item('modules_locations');

        foreach ($locations as $location)
        {
            foreach ($modules as $module => $subpath)
            {
                // Combine our elements to make an actual path to the file
                $full_path = $location . $module .'/'. $base . $subpath;

                // If it starts with a '/' assume it's a full path already.
                if (substr($path, 0, 1) == '/')
                {
                    $full_path = $path;
                }

                // Libraries are a special consideration since they are
                // frequently ucfirst.
                if ($base == 'libraries/' && is_file($full_path . ucfirst($file_ext)))
                {
                    return array($full_path, ucfirst($file));
                }

                if (is_file($full_path . $file_ext))
                {
                    return array($full_path, $file);
                }
            }
        }

        return array(false, $file);
    }

    //--------------------------------------------------------------------

    /**
     * Convenience method to return the locations where modules can be found.
     *
     * @return array The config settings array for modules_locations.
     */
    public function folders()
    {
        return config_item('modules_locations');
    }

    //--------------------------------------------------------------------

    /**
     * Returns a list of all modules in the system.
     *
     * @param bool $exclude_core Whether to exclude the Bonfire core modules or not
     *
     * @return array A list of all modules in the system.
     */
    public function list_modules($exclude_core=false)
    {
        if ( ! function_exists('directory_map'))
        {
            $ci =& get_instance();
            $ci->load->helper('directory');
        }

        $map = array();

        $folders = Modules::folders();
        foreach ($folders as $folder)
        {
            // If we're excluding core modules and this module
            // is in the core modules folder... ignore it.
            if ($exclude_core && strpos($folder, 'bonfire/modules') !== false)
            {
                continue;
            }

            $dirs = directory_map($folder, 1);
            if ( ! is_array($dirs))
            {
                $dirs = array();
            }

            $map = array_merge($map, $dirs);
        }

        // Clean out any html or php files
        if ($count = count($map))
        {
            for ($i = 0; $i < $count; $i++)
            {
                if (strpos($map[$i], '.html') !== false || strpos($map[$i], '.php') !== false)
                {
                    unset($map[$i]);
                }
            }
        }

        return $map;
    }

    //--------------------------------------------------------------------

}