<?php

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
 * @since     0.7.2
 * @filesource
 */

/**
 * Bonfire CommonMark library
 *
 * Interface for converting CommonMark text to HTML using any library for which
 * a driver/adapter is available.
 *
 * @package Bonfire\Libraries\CommonMark
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/commonmark
 */
class CommonMark
{
    /**
     * The adapter which communicates with the conversion library.
     * @var CommonMarkDriver
     */
    protected $adapter;

    /** @var CI_Controller The CI instance. */
    protected $ci;

    /** @var string The name of the default driver/adapter. */
    protected $defaultDriver = 'MarkdownExtended';

    /** @var string The name of the driver/adapter. */
    protected $driver;

    /** @var array The names of the valid driver(s)/adapter(s). */
    protected $valid_drivers = array('MarkdownExtended');

    /**
     * Get the configured/supplied driver name and load it.
     *
     * @param array $params An array of configuration values. Currently supports
     * 'driver' and 'defaultDriver'.
     */
    public function __construct(array $params = array())
    {
        $this->ci = get_instance();

        // Merge the configured list of valid drivers with the internal list.
        $validDrivers = $this->ci->config->item('commonmark.valid_drivers');
        if (! empty($validDrivers) && is_array($validDrivers)) {
            $this->valid_drivers = array_merge($this->valid_drivers, $validDrivers);
        }

        // If a valid driver was passed, set it.
        if (! empty($params['driver'])
            && in_array($params['driver'], $this->valid_drivers)
        ) {
            $this->driver = $params['driver'];
        }

        // If a valid default driver was passed, set it.
        if (! empty($params['defaultDriver'])
            && in_array($params['defaultDriver'], $this->valid_drivers)
        ) {
            $this->defaultDriver = $params['defaultDriver'];
        }

        // Get the configured driver if no valid driver was passed as a parameter.
        $confDriver = $this->ci->config->item('commonmark.driver');
        if (empty($this->driver)
            && in_array($confDriver, $this->valid_drivers)
        ) {
            $this->driver = $confDriver;
        }

        // If the driver is still not set, use the default driver.
        if (empty($this->driver)) {
            $this->driver = $this->defaultDriver;
        }

        $this->loadDriver();
    }

    /**
     * Load the driver which will interface with the conversion library.
     *
     * @param string $driver The name of the driver to load. If omitted, or if
     * $driver is not in the list of valid drivers, $this->driver will be used.
     *
     * @return void
     */
    public function loadDriver($driver = '')
    {
        // If a valid driver was supplied, use it.
        if (! empty($driver) && in_array($driver, $this->valid_drivers)) {
            $this->driver = $driver;
        }

        // Load the abstract driver which the drivers extend.
        require_once(BFPATH . 'libraries/CommonMark/CommonMarkDriver.php');

        // Load the driver and set it as the adapter.
        $driverName = "CommonMark_{$this->driver}";
        $this->ci->load->library("CommonMark/drivers/{$driverName}");
        $driverName = strtolower($driverName);
        $this->adapter = $this->ci->{$driverName};
    }

    /**
     * Convert the CommonMark text to HTML.
     *
     * @param  string $text The CommonMark text to convert.
     *
     * @return string       The result of the conversion (HTML text).
     */
    public function convert($text)
    {
        return $this->adapter->convert($text);
    }

    /**
     * Convert the CommonMark text to HTML.
     *
     * @deprecated since 0.7.2. Use convert().
     *
     * @param string $text The CommonMark text to convert.
     *
     * @return string The result of the conversion (HTML text).
     */
    public function parse($text)
    {
        return $this->convert($text);
    }
}
