<?php

class Mercurial_spark extends Spark_type {

    function __construct($data)
    {
        parent::__construct($data);
        $this->tag = $this->tag;
    }

    static function get_spark($data)
    {
        if (self::hg_installed())
        {
            return new Mercurial_spark($data);
        }
        else
        {
            Spark_utils::warning('Mercurial not found - reverting to archived copy');
            return new Zip_spark($data);
        }
    }

    private static function hg_installed()
    {
        return !!`hg`;
    }

    function location_detail()
    {
        return "Mercurial repository at $this->base_location";
    }

    function retrieve()
    {
        `hg clone -r$this->tag $this->base_location $this->temp_path`;
        // remove the mercurial directory
        Spark_utils::remove_full_directory("$this->temp_path/.hg");

        if (!file_exists($this->temp_path))
        {
            throw new Spark_exception('Failed to retrieve the spark ;(');
        }
        return true;
    }

}
