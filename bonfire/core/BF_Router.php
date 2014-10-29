<?php defined('BASEPATH') || exit('No direct script access allowed');

require_once BFPATH . 'libraries/Modules.php';
require_once BFPATH . 'libraries/Route.php';

class BF_Router extends CI_Router
{
    protected $module;

    public function fetch_module()
    {
        return $this->module;
    }

    public function _validate_request($segments)
    {

        if (count($segments) == 0) {
            return $segments;
        }

        // Locate module controller
        if ($located = $this->locate($segments)) {
            return $located;
        }

        // Use a default 404_override controller
        if (! empty($this->routes['404_override'])) {
            $segments = explode('/', $this->routes['404_override']);
            if ($located = $this->locate($segments)) {
                return $located;
            }
        }

        // No controller found
        show_404(implode('/', $segments));
    }

    /** Locate the controller **/
    public function locate($segments)
    {
        $this->module = '';
        $this->directory = '';
        $ext = $this->config->item('controller_suffix') . '.php';

        /* Use module route if available */
        if (isset($segments[0])
            && $routes = Modules::parse_routes($segments[0], implode('/', $segments))
        ) {
            $segments = $routes;
        }

        /* Get the segments array elements */
        list($module, $directory, $controller) = array_pad($segments, 3, null);

        /* check modules */
        foreach (Modules::$locations as $location => $offset) {
            /* module exists? */
            if (is_dir($source = $location.$module.'/controllers/')) {
                $this->module = $module;
                $this->directory = $offset.$module.'/controllers/';

                /* module sub-controller exists? */
                if ($directory && is_file($source.$directory.$ext)) {
                    return array_slice($segments, 1);
                }

                /* module sub-directory exists? */
                if ($directory && is_dir($source.$directory.'/')) {

                    $source = $source.$directory.'/';
                    $this->directory .= $directory.'/';

                    /* module sub-directory controller exists? */
                    if (is_file($source.$directory.$ext)) {
                        return array_slice($segments, 1);
                    }

                    /* module sub-directory sub-controller exists? */
                    if ($controller && is_file($source.$controller.$ext)) {
                        return array_slice($segments, 2);
                    }
                }

                /* module controller exists? */
                if (is_file($source.$module.$ext)) {
                    return $segments;
                }
            }
        }

        foreach (array(APPPATH, BFPATH) as $searchPath) {
            /* application controller exists? */
            if (is_file($searchPath.'controllers/'.$module.$ext)) {
                return $segments;
            }

            /* application sub-directory controller exists? */
            if ($directory
                && is_file($searchPath.'controllers/'.$module.'/'.$directory.$ext)
            ) {
                $this->directory = $module.'/';
                return array_slice($segments, 1);
            }

            /* application sub-directory default controller exists? */
            if (is_file($searchPath.'controllers/'.$module.'/'.$this->default_controller.$ext)) {
                $this->directory = $module.'/';
                return array($this->default_controller);
            }
        }
    }

    public function set_class($class)
    {
        $this->class = $class.$this->config->item('controller_suffix');
    }
}
/* End of file ./bonfire/core/BF_Router.php */
