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

}