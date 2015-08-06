<?php defined('BASEPATH') || exit('No direct script access allowed');

class BF_Loader extends MX_Loader
{
    /**
     * @var The path in which the Sparks loader is located, including trailing slash.
     */
    protected $sparksLoaderPath;

    /**
     * Constructor
     *
     * Sets the paths for the loader.
     */
    public function __construct()
    {
        $this->_ci_ob_level      = ob_get_level();
        $this->_ci_library_paths = array(APPPATH, BFPATH, BASEPATH);
        $this->_ci_helper_paths  = array(APPPATH, BFPATH, BASEPATH);
        $this->_ci_model_paths   = array(APPPATH, BFPATH);
        $this->_ci_view_paths    = array(
            APPPATH . 'views/' => true,
            BFPATH . 'views/'  => true
        );

        $this->sparksLoaderPath = APPPATH . 'third_party/';

        log_message('debug', 'Loader Class Initialized');

        parent::__construct();
    }

    /**
     * Load a helper contained within a module.
     *
     * Copied from MX_Loader, modified to check the /bonfire/helpers directory for
     * a 'BF_' prefixed helper.
     *
     * @param string $helper The helper to load.
     *
     * @return $this
     **/
    public function helper($helper = array())
    {
        if (is_array($helper)) {
            return $this->helpers($helper);
        }

        if (isset($this->_ci_helpers[$helper])) {
            return;
        }

        list($path, $_helper) = Modules::find("{$helper}_helper", $this->_module, 'helpers/');

        if ($path === false) {
            // If the helper was not found in a module, check the traditional locations.
            parent::helper($helper);
            return $this;
        }

        Modules::load_file($_helper, $path);
        $this->_ci_helpers[$_helper] = true;
        return $this;
    }


    /** Load a module library **/
    /**
     * Load a library contained within a module.
     *
     * Copied from MX_Loader, modified to check the bonfire/libraries directory
     * for a 'BF_' prefixed library.
     *
     * @param  string $library     The library to load.
     * @param  mixed  $params      Parameters to pass to the library.
     * @param  string $object_name An alias for the library.
     *
     * @return $this
     */
    public function library($library, $params = null, $object_name = null)
    {
        if (is_array($library)) {
            return $this->libraries($library);
        }

        $class = strtolower(basename($library));

        if (isset($this->_ci_classes[$class]) && $_alias = $this->_ci_classes[$class]) {
            return $this;
        }

        ($_alias = strtolower($object_name)) or $_alias = $class;

        list($path, $_library) = Modules::find($library, $this->_module, 'libraries/');

        /* load library config file as params */
        if ($params == null) {
            list($path2, $file) = Modules::find($_alias, $this->_module, 'config/');
            ($path2) && $params = Modules::load_file($file, $path2, 'config');
        }

        if ($path === false) {
            // Use $this->_ci_load_library() in CI 3
            if (substr(CI_VERSION, 0, 1) != '2') {
                $this->_ci_load_library($library, $params, $object_name);
            } else {
                $this->_ci_load_class($library, $params, $object_name);
                $_alias = $this->_ci_classes[$class];
            }
        } else {
            Modules::load_file($_library, $path);

            $library = ucfirst($_library);
            CI::$APP->$_alias = new $library($params);

            $this->_ci_classes[$class] = $_alias;
        }

        return $this;
    }

    /**
     * Provides a convenience method to use the official Sparks loader.
     *
     * @param string $spark
     * @param array  $autoload
     *
     * @return void
     */
    public function spark($spark, $autoload = array())
    {
        require_once "{$this->sparksLoaderPath}Sparks_Loader.php";

        $loader = new Sparks_Loader();
        $loader->spark($spark, $autoload);
    }

    /**
     * Load class
     *
     * This function loads the requested class.
     *
     * Copied from /bonfire/codeigniter/core/Loader.php, modified to call
     * $this->loadSubclassedLibrary() to check for library extensions using the
     * BF_ prefix in the /bonfire/libraries directory.
     *
     * @param string The item that is being loaded
     * @param mixed  Any additional parameters
     * @param string An optional object name
     *
     * @return  void
     */
    protected function _ci_load_class($class, $params = null, $object_name = null)
    {
        // Get the class name and trim any slashes. The directory path can be included
        // as part of the class name, but a leading slash is not desired.
        $class = str_replace('.php', '', trim($class, '/'));

        // Look for a slash to determine whether the path was included with the
        // class name.
        $subdir = '';
        if (($last_slash = strrpos($class, '/')) !== false) {
            // Extract the path.
            $subdir = substr($class, 0, $last_slash + 1);

            // Get the filename from the path.
            $class = substr($class, $last_slash + 1);
        }

        // Test for both lowercase and capitalized versions of the file name.
        foreach (array(ucfirst($class), strtolower($class)) as $class) {
            // Is this a class extension request? Check the Bonfire libraries for
            // BF_ class extensions.
            if ($this->loadSubclassedLibrary($class, $subdir, $params, $object_name)) {
                return;
            }

            // Search for the requested library file and load it.
            $is_duplicate = false;
            foreach ($this->_ci_library_paths as $path) {
                $filepath = "{$path}libraries/{$subdir}{$class}.php";

                // Does the file exist? No? Bummer...
                if (! file_exists($filepath)) {
                    continue;
                }

                // Safety: Was the class already loaded by a previous call?
                if (in_array($filepath, $this->_ci_loaded_files)) {
                    // Before this is deemed to be a duplicate request, see if a
                    // custom object name is being supplied. If so, return a new
                    // instance of the object.
                    if (! is_null($object_name)) {
                        if (! isset(get_instance()->$object_name)) {
                            return $this->_ci_init_class($class, '', $params, $object_name);
                        }
                    }

                    $is_duplicate = true;
                    log_message('debug', "{$class} class already loaded. Second attempt ignored.");
                    return;
                }

                include_once($filepath);
                $this->_ci_loaded_files[] = $filepath;
                return $this->_ci_init_class($class, '', $params, $object_name);
            }
        }

        // Maybe the library is in a subdirectory, but it wasn't specified?
        if ($subdir == '') {
            $path = strtolower($class) . '/' . $class;
            return $this->_ci_load_class($path, $params);
        }

        // Unable to find the requested class. Do not issue errors if the load call
        // failed due to a duplicate request.
        if ($is_duplicate == false) {
            log_message('error', "Unable to load the requested class: {$class}");
            show_error("Unable to load the requested class: {$class}");
        }
    }


    /**
     * Internal CI Stock Library Loader
     *
     * @used-by CI_Loader::_ci_load_library()
     * @uses    CI_Loader::_ci_init_library()
     *
     * @param string  $library     Library name to load
     * @param string  $file_path   Path to the library filename, relative to libraries/
     * @param mixed   $params      Optional parameters to pass to the class constructor
     * @param string  $object_name Optional object name to assign to
     *
     * @return void
     */
    protected function _ci_load_stock_library($library_name, $file_path, $params, $object_name)
    {
        $prefix = 'CI_';

        if (class_exists($prefix . $library_name, false)) {
            if (class_exists(config_item('subclass_prefix') . $library_name, false)) {
                $prefix = config_item('subclass_prefix');
            }

            // Before we deem this to be a duplicate request, let's see if a custom
            // object name is being supplied. If so, we'll return a new instance
            // of the object.
            if ($object_name !== null) {
                $CI =& get_instance();
                if (! isset($CI->$object_name)) {
                    return $this->_ci_init_library($library_name, $prefix, $params, $object_name);
                }
            }

            log_message('debug', $library_name . ' class already loaded. Second attempt ignored.');
            return;
        }

        $paths = $this->_ci_library_paths;
        // array_pop($paths); // BASEPATH
        $searchResult = array_search(BASEPATH, $paths);
        if ($searchResult !== false) {
            unset($paths[$searchResult]);
        }
        // array_pop($paths); // APPPATH (needs to be the first path checked)
        $searchResult = array_search(APPPATH, $paths);
        if ($searchResult !== false) {
            unset($paths[$searchResult]);
        }
        array_unshift($paths, APPPATH);

        foreach ($paths as $path) {
            if (file_exists($path = "{$path}libraries/{$file_path}{$library_name}.php")) {
                // Override
                include_once($path);
                if (class_exists($prefix . $library_name, FALSE)) {
                    return $this->_ci_init_library($library_name, $prefix, $params, $object_name);
                } else {
                    log_message('debug', "{$path} exists, but does not declare {$prefix}{$library_name}");
                }
            }
        }

        include_once(BASEPATH . "libraries/{$file_path}{$library_name}.php");

        // Check for extensions
        $myLibraryName = config_item('subclass_prefix').$library_name;
        foreach (array("BF_{$library_name}", $myLibraryName) as $subclass) {
            foreach ($paths as $path) {
                if (file_exists($path = "{$path}libraries/{$file_path}{$subclass}.php")) {
                    include_once($path);
                    if (class_exists($subclass, false)) {
                        $prefix = $subclass == $myLibraryName ? config_item('subclass_prefix') : 'BF_';
                        break;
                    } else {
                        log_message('debug', "{$path} exists, but does not declare {$subclass}");
                    }
                }
            }
        }

        return $this->_ci_init_library($library_name, $prefix, $params, $object_name);
    }

    /**
     * Checks for the existence of a class extension when loading a library.
     *
     * If a class extension is found in either location (or both), the extension(s)
     * and the library are loaded. If no extensions are found, nothing is loaded.
     *
     * This is an extension of the code originally included in the _ci_load_class
     * method of CodeIgniter's Loader (/bonfire/codeigniter/core/Loader.php). In
     * addition to checking the application's libraries directory for a file using
     * the configured subclass_prefix (by default 'MY_'), it checks bonfire's libraries
     * directory for a file using the 'BF_' prefix.
     *
     * @param string $class      The item that is being loaded.
     * @param string $subdir     A subdirectory within the libraries directory.
     * @param mixed  $params     Any additional parameters.
     * @param string $objectName An optional object name.
     *
     * @return boolean True if the file was loaded (either in this call or previously).
     * False if the file was not found.
     */
    protected function loadSubclassedLibrary($class, $subdir, $params = null, $objectName = null)
    {
        $subclass  = APPPATH . "libraries/{$subdir}" . config_item('subclass_prefix') . "{$class}.php";
        $bfclass   = realpath(BFPATH) . "/libraries/{$subdir}BF_{$class}.php";
        $baseclass = BASEPATH . 'libraries/' . ucfirst($class) . '.php';

        $includeSubclass = file_exists($subclass);
        $includeBfclass  = file_exists($bfclass);

        if ($includeSubclass == false && $includeBfclass == false) {
            return false;
        }

        if (! file_exists($baseclass)) {
            log_message('error', "Unable to load the requested class: {$class}");
            show_error("Unable to load the requested class: {$class}");
        }

        // Safety: Was the class already loaded by a previous call?
        if ($includeSubclass && in_array($subclass, $this->_ci_loaded_files)) {
            // Before this is deemed to be a duplicate request, see if a custom
            // object name is being supplied. If so, return a new instance of the
            // object.
            if (! is_null($objectName)) {
                if (! isset(get_instance()->$objectName)) {
                    return $this->_ci_init_class(
                        $class,
                        config_item('subclass_prefix'),
                        $params,
                        $objectName
                    );
                }
            }

            log_message('debug', "{$class} class already loaded. Second attempt ignored.");
            return true;
        }

        // Safety: Was the class already loaded by a previous call?
        if ($includeBfclass && in_array($bfclass, $this->_ci_loaded_files)) {
            if (! is_null($objectName)) {
                if (! isset(get_instance()->$objectName)) {
                    return $this->_ci_init_class($class, 'BF_', $params, $objectName);
                }
            }

            $is_duplicate = true;
            log_message('debug', "{$class} class already loaded. Second attempt ignored.");
            return true;
        }

        include_once($baseclass);
        if ($includeBfclass) {
            include_once($bfclass);
            $this->_ci_loaded_files[] = $bfclass;
            if (! $includeSubclass) {
                $this->_ci_init_class($class, 'BF_', $params, $objectName);
                return true;
            }
        }
        if ($includeSubclass) {
            include_once($subclass);
            $this->_ci_loaded_files[] = $subclass;
        }

        $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $objectName);
        return true;
    }
}
/* End of file ./bonfire/core/BF_Loader.php */
