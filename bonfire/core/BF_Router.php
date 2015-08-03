<?php defined('BASEPATH') || exit('No direct script access allowed');

require_once BFPATH . 'libraries/Modules.php';
require_once BFPATH . 'libraries/Route.php';

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License.
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link    http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter router class.
 *
 * Install this file as application/third_party/MX/Router.php
 *
 * @copyright   Copyright (c) 2015 Wiredesignz
 * @version     5.5
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/

/**
 * Bonfire Router
 *
 * Parses URIs and determines routing to the appropriate controller. Adapted from
 * MX Router to add searching the Bonfire path(s) and include/utilize the Bonfire
 * Modules and Route libraries.
 *
 * @package Bonfire\Core\BF_Router
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs
 */
class BF_Router extends CI_Router
{
    /** @var string The name of the current module. */
    public $module;

    private $located = 0;

    /**
     * Class constructor runs the route mapping function in CI3.
     *
     * @param array $routing Optional configuration values.
     *
     * @return void
     */
    public function __construct($routing = null)
    {
        parent::__construct($routing);

        log_message('info', 'BF_Router class initialized');
    }

    /**
     * Get the name of the current module.
     *
     * @return string The name of the current module.
     */
    public function fetch_module()
    {
        return $this->module;
    }

    /**
     * Find the controller for the current request.
     *
     * @param  array $segments The URL segments to use to locate the controller.
     *
     * @return array The segments which indicate the location of the controller.
     */
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

    /**
     * Locate the controller.
     *
     * @param  array $segments The URL segments.
     *
     * @return array The segments indicating the location of the controller.
     */
    public function locate($segments)
    {
        $this->located = 0;
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
                if ($directory) {
                    if (is_file($source.ucfirst($directory).$ext)
                        || is_file($source.$directory.$ext)
                    ) {
                        $this->located = 2;
                        return array_slice($segments, 1);
                    }

                    /* module sub-directory exists? */
                    if (is_dir($source.$directory.'/')) {
                        $source .= $directory.'/';
                        $this->directory .= $directory.'/';

                        /* module sub-directory controller exists? */
                        if ($controller) {
                            if (is_file($source.ucfirst($controller).$ext)
                                || is_file($source.$controller.$ext)
                            ) {
                                $this->located = 3;
                                return array_slice($segments, 2);
                            } else {
                                $this->located = -1;
                            }
                        }
                    } elseif (is_file($source.ucfirst($directory).$ext)
                        || is_file($source.$directory.$ext)
                    ) {
                        $this->located = 2;
                        return array_slice($segments, 1);
                    } else {
                        $this->located = -1;
                    }
                }

                /* module controller exists? */
                if (is_file($source.ucfirst($module).$ext)
                    || is_file($source.$module.$ext)
                ) {
                    $this->located = 1;
                    return $segments;
                }
            }
        }

        if (! empty($this->directory)) {
            return;
        }

        foreach (array(APPPATH, BFPATH) as $searchPath) {
            /* application controller exists? */
            if (is_file($searchPath.'controllers/'.ucfirst($module).$ext)
                || is_file($searchPath.'controllers/'.$module.$ext)
            ) {
                return $segments;
            }

            /* application sub-directory controller exists? */
            if ($directory) {
                if (is_file($searchPath.'controllers/'.$module.'/'.ucfirst($directory).$ext)
                    || is_file($searchPath.'controllers/'.$module.'/'.$directory.$ext)
                ) {
                    $this->directory = $module.'/';
                    return array_slice($segments, 1);
                }

                /* application sub-sub-directory controller exists? */
                if ($controller) {
                    if (is_file($searchPath.'controllers/'.$module.'/'.$directory.'/'.ucfirst($controller).$ext)
                        || is_file($searchPath.'controllers/'.$module.'/'.$directory.'/'.$controller.$ext)
                    ) {
                        $this->directory = $module.'/'.$directory.'/';
                        return array_slice($segments, 2);
                    }
                }
            }

            /* application sub-directory default controller exists? */
            if (is_file($searchPath.'controllers/'.$module.'/'.ucfirst($this->default_controller).$ext)
                || is_file($searchPath.'controllers/'.$module.'/'.$this->default_controller.$ext)
            ) {
                $this->directory = $module.'/';
                return array($this->default_controller);
            }
        }

        $this->located = -1;
    }

    /**
     * Set the default controller.
     *
     * NOTE: this method should be protected for use with CI3, but must be public
     * for use with CI2.
     *
     * @return void
     */
    public function _set_default_controller()
    {
        if (empty($this->directory)) {
            // Set the default controller module path.
            $this->_set_module_path($this->default_controller);
        }

        parent::_set_default_controller();

        if (empty($this->class)) {
            $this->_set_404override_controller();
        }
    }

    /**
     * Sets the module path to the 404_override controller. This is pulled from
     * the MX Router primarily for use by the _set_default_controller() method.
     *
     * @return void
     */
    protected function _set_404override_controller()
    {
        $this->_set_module_path($this->routes['404_override']);
    }

    /**
     * Set module path.
     *
     * @param string &$_route The route for which the module path should be set.
     *
     * @return void
     */
    protected function _set_module_path(&$_route)
    {
        if (! empty($_route)) {
            // Are module/directory/controller/method segments being specified?
            $sgs = sscanf($_route, '%[^/]/%[^/]/%[^/]/%s', $module, $directory, $class, $method);

            // set the module/controller directory location if found
            if ($this->locate(array($module, $directory, $class))) {
                //reset to class/method
                switch ($sgs) {
                    case 1: $_route = $module.'/index';
                        break;
                    case 2: $_route = ($this->located < 2) ? $module.'/'.$directory : $directory.'/index';
                        break;
                    case 3: $_route = ($this->located == 2) ? $directory.'/'.$class : $class.'/index';
                        break;
                    case 4: $_route = ($this->located == 3) ? $class.'/'.$method : $method.'/index';
                        break;
                }
            }
        }
    }

    /**
     * Set the class name.
     *
     * @param string $class The base name of the class.
     *
     * @return void
     */
    public function set_class($class)
    {
        $class = $class . $this->config->item('controller_suffix');
        parent::set_class($class);
    }
}
/* End of file ./bonfire/core/BF_Router.php */
