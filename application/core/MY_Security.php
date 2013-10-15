<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Security extends CI_Security {

    /*
        An array of controllers to ignore during the CSRF
        cycle. If part of a module, it should be listed as

            {module}/{controller}
     */
    protected $ignored_controllers = array('stats/stats');

    //--------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();
    }

    //--------------------------------------------------------------------

    /**
     * Override the csrf_verify method to allow us to set controllers
     * and modules to override.
     *
     */
    public function csrf_verify()
    {
        global $RTR;

        $module = $RTR->fetch_module();
        $controller = $RTR->fetch_class();

        $bypass = FALSE;

        if (in_array($module .'/'. $controller, $this->ignored_controllers))
        {
            $bypass = TRUE;
        }

        if ( ! $bypass) {
            parent::csrf_verify();
        }
    }

    //--------------------------------------------------------------------

}