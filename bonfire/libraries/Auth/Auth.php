<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Driver_Library {

    /**
     * The currently in use driver.
     * @var string
     */
    protected $_driver;

    /**
     * Currently enabled drivers.
     * @var string
     */
    protected $valid_drivers = array();

    /**
     * Pointer to the CodeIgniter instance.
     */
    protected $ci;

    //--------------------------------------------------------------------

    public function __construct()
    {
        $this->ci =& get_instance();

        // Set the drivers based on what's defined in application config file
        $this->valid_drivers = $this->ci->config->item('auth.allowed_drivers');

        // Set the default driver
        $this->_driver = $this->ci->config->item('auth.default_driver');

        log_message('debug', 'Auth Driver initialized.');
    }

    //--------------------------------------------------------------------

    /**
     * Sets the driver to use.
     *
     * @param string $name
     */
    public function set_driver($name)
    {
        $this->_driver = trim( $name );
    }

    //--------------------------------------------------------------------
    // Utility Methods
    //--------------------------------------------------------------------

    /**
     * Redirect all method calls not in this class to the child class set
     * in the variable 'driver'.
     *
     * @param  mixed $child
     * @param  mixed $arguments
     * @return mixed
     */
    public function __call($child, $arguments)
    {
        return call_user_func_array( array($this->{$this->_driver}, $child), $arguments);
    }

    //--------------------------------------------------------------------

}