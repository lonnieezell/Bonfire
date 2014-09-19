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
     * @return void;
     **/
    public function helper($helper = array()) {

        if (is_array($helper)) return $this->helpers($helper);

        if (isset($this->_ci_helpers[$helper])) return;

        list($path, $_helper) = Modules::find($helper.'_helper', $this->_module, 'helpers/');

        if ($path === false) {
            if (file_exists(BFPATH . "helpers/BF_{$helper}_helper.php")) {
                include_once(BFPATH . "helpers/BF_{$helper}_helper.php");
            }
            return parent::helper($helper);
        }

        Modules::load_file($_helper, $path);
        $this->_ci_helpers[$_helper] = TRUE;
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
     * @param   string  the item that is being loaded
     * @param   mixed   any additional parameters
     * @param   string  an optional object name
     * @return  void
     */
    protected function _ci_load_class($class, $params = null, $object_name = null)
    {
        // Get the class name, and while we're at it trim any slashes.
        // The directory path can be included as part of the class name,
        // but we don't want a leading slash
        $class = str_replace('.php', '', trim($class, '/'));

        // Was the path included with the class name?
        // We look for a slash to determine this
        $subdir = '';
        if (($last_slash = strrpos($class, '/')) !== false) {
            // Extract the path
            $subdir = substr($class, 0, $last_slash + 1);

            // Get the filename from the path
            $class = substr($class, $last_slash + 1);
        }

        // We'll test for both lowercase and capitalized versions of the file name
        foreach (array(ucfirst($class), strtolower($class)) as $class) {
            // Is this a class extension request?
            // Modified to check the Bonfire libraries for BF_ class extensions.
            if ($this->loadSubclassedLibrary($class, $subdir, $params, $object_name)) {
                return;
            }

            // Lets search for the requested library file and load it.
            $is_duplicate = false;
            foreach ($this->_ci_library_paths as $path) {
                $filepath = "{$path}libraries/{$subdir}{$class}.php";

                // Does the file exist? No? Bummer...
                if (! file_exists($filepath)) {
                    continue;
                }

                // Safety:  Was the class already loaded by a previous call?
                if (in_array($filepath, $this->_ci_loaded_files)) {
                    // Before we deem this to be a duplicate request, let's see
                    // if a custom object name is being supplied.  If so, we'll
                    // return a new instance of the object
                    if (! is_null($object_name)) {
                        $CI =& get_instance();
                        if (! isset($CI->$object_name)) {
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
        } // END FOREACH

        // One last attempt. Maybe the library is in a subdirectory, but it wasn't specified?
        if ($subdir == '') {
            $path = strtolower($class) . '/' . $class;
            return $this->_ci_load_class($path, $params);
        }

        // If we got this far we were unable to find the requested class.
        // We do not issue errors if the load call failed due to a duplicate request.
        if ($is_duplicate == false) {
            log_message('error', "Unable to load the requested class: {$class}");
            show_error("Unable to load the requested class: {$class}");
        }
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
     * @return bool True if the file was loaded (either in this call or previously).
     *              False if the file was not found.
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
            // Before we deem this to be a duplicate request, let's see
            // if a custom object name is being supplied.  If so, we'll
            // return a new instance of the object
            if (! is_null($objectName)) {
                $CI =& get_instance();
                if (! isset($CI->$objectName)) {
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
                $CI =& get_instance();
                if (! isset($CI->$objectName)) {
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
