<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Bonfire Security Class
 *
 * @package Bonfire\Core\BF_Security
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer
 */
class BF_Security extends CI_Security
{
    /**
     * @var array Controllers to ignore during the CSRF cycle.
     *
     * If part of a module, the controller should be listed as:
     * {module}/{controller}
     */
    protected $ignored_controllers = array();

    /**
     * The constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Get config value indicating controllers which should be ignored when
        // applying CSRF protection.
        $this->ignored_controllers = config_item('csrf_ignored_controllers');
    }

    /**
     * Show CSRF Error.
     *
     * Override the csrf_show_error method to improve the error message.
     *
     * @return void
     */
    public function csrf_show_error()
    {
        show_error('The action you have requested is not allowed. You either do not have access, or your login session has expired and you need to sign in again.');
    }

    /**
     * Verify Cross Site Request Forgery Protection.
     *
     * Override the csrf_verify method to allow us to set controllers and modules
     * to override.
     *
     * @return object Returns $this to allow method chaining.
     */
    public function csrf_verify()
    {
        if (! empty($this->ignored_controllers)) {
            global $RTR;

            $module = $RTR->fetch_module();
            $controller = $RTR->class;

            $path = empty($module) ? $controller : "{$module}/{$controller}";

            if (in_array($path, $this->ignored_controllers)) {
                return $this;
            }
        }

        return parent::csrf_verify();
    }
}
