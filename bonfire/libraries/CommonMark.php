<?php

class CommonMark
{
    protected $adapter;

    protected $ci;

    protected $defaultDriver = 'MarkdownExtended';

    protected $driver;

    protected $valid_drivers = array('MarkdownExtended');

    public function __construct(array $params = array())
    {
        $this->ci = get_instance();

        if (! empty($params['driver'])) {
            $this->driver = $params['driver'];
        }
        if (! empty($params['defaultDriver'])) {
            $this->defaultDriver = $params['defaultDriver'];
        }

        $validDrivers = $this->ci->config->item('commonmark.valid_drivers');
        if (! empty($validDrivers) && is_array($validDrivers)) {
            $this->valid_drivers = array_merge($this->valid_drivers, $validDrivers);
        }

        if (empty($this->driver)) {
            $this->driver = $this->ci->config->item('commonmark.driver');
        }

        if (empty($this->driver)) {
            $this->driver = $this->defaultDriver;
        }

        $this->loadDriver();
    }

    public function loadDriver($driver = '')
    {
        if (! empty($driver) && in_array($driver, $this->valid_drivers)) {
            $this->driver = $driver;
        }

        $driverName = "CommonMark_{$this->driver}";
        $this->ci->load->library("CommonMark/drivers/{$driverName}");
        $driverName = strtolower($driverName);
        $this->adapter = $this->ci->{$driverName};
    }

    public function parse($text)
    {
        return $this->adapter->parse($text);
    }
}
