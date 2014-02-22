<?php

class Spark_type {

    function __construct($data)
    {
        $this->data = $data;
        $this->name = $this->data->name;
        $this->spark_id = property_exists($this->data, 'id') ? $this->data->id : null;
        $this->version = property_exists($this->data, 'version') ? $this->data->version : null;
        $this->tag = property_exists($this->data, 'tag') ? $this->data->tag : $this->version;
        $this->base_location = property_exists($this->data, 'base_location') ? $this->data->base_location : null;

        // Load the dependencies
        $this->dependencies = property_exists($this->data, 'dependencies') ? $this->data->dependencies : array();

        // Assign other data we don't have
        foreach ($this->data as $k=>$v)
        {
            if (!property_exists($this, $k)) $this->$k = $v;
        }

        // used internally
        $this->temp_token = 'spark-' . $this->spark_id . '-' . time();
        $this->temp_path = sys_get_temp_dir() . '/' . $this->temp_token;
    }

    final function installed_path()
    {
        return $this->installed_path;
    }

    function location_detail() { }
    function retrieve() { }

    function install()
    {
        foreach ($this->dependencies as $dependency)
        {
            if ($dependency->is_direct)
            {
                $this->install_dependency($dependency);
            }
        }

        @mkdir(SPARK_PATH); // Two steps for windows
        @mkdir(SPARK_PATH . "/$this->name");
        Spark_utils::full_move($this->temp_path, $this->installation_path);
        Spark_utils::remove_full_directory($this->temp_path);
        $this->installed_path = $this->installation_path;
    }

    private function recurseMove($src,$dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurseMove($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    rename($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    private function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    if (is_dir($dir . "/" . $file)) {
                        $this->rrmdir($dir . "/" . $file);
                    }
                    else {
                        unlink($dir . "/" . $file);
                    }
                }
            }
            reset($files);
            rmdir($dir);
        }
    }

    function install_dependency($dependency_data) {
        // Get the spark object
        $spark = null;
        if ($dependency_data->repository_type == 'hg') $spark = Mercurial_spark::get_spark($dependency_data);
        else if ($dependency_data->repository_type == 'git') $spark = Git_spark::get_spark($dependency_data);
        else if ($dependency_data->repository_type == 'zip') $spark = new Zip_spark($dependency_data);
        else throw new Exception('Unknown repository type: ' . $dependency_data->repository_type);
        // Install the spark
        if ($spark->verify(false)) { // if not installed, install
            $spark->retrieve();
            $spark->install();
            Spark_utils::notice("Installed dependency: $spark->name to " . $spark->installed_path());
        }
        else {
            Spark_utils::warning("Dependency $spark->name is already installed.");
        }
    }

    function verify($break_on_already_installed = true)
    {
        // see if this is deactivated
        if ($this->data->is_deactivated)
        {
            $msg = 'Woah there - it seems the spark you want has been deactivated';
            if ($this->data->spark_home) $msg .= "\nLook for different versions at: " . $this->data->spark_home;
            throw new Spark_exception($msg);
        }
        // see if this is unsupported
        if ($this->data->is_unsupported)
        {
            Spark_utils::warning('This spark is no longer supported.');
            Spark_utils::warning('You can keep using it, or look for an alternate');
        }
        // tell the user if its already installed and throw an error
        $this->installation_path = SPARK_PATH . "/$this->name/$this->version";
        if (is_dir($this->installation_path))
        {
            if ($break_on_already_installed)
            {
                throw new Spark_exception("Already installed.  Try `php tools/spark reinstall $this->name`");
            }
            return false;
        }
        else
        {
            return true;
        }
    }

}
