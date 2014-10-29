<?php

class Git_spark extends Spark_type {

    function __construct($data)
    {
        if (!self::git_installed())
        {
            throw new Spark_exception('You have to have git to install this spark.');
        }
        parent::__construct($data);
        $this->tag = $this->tag;
    }

    static function get_spark($data)
    {
        if (self::git_installed())
        {
            return new Git_spark($data);
        }
        else
        {
            Spark_utils::warning('Git not found - reverting to archived copy');
            return new Zip_spark($data);
        }
    }

    private static function git_installed()
    {
        return !!`git`;
    }

    function location_detail()
    {
        return "Git repository at $this->base_location";
    }

    function retrieve()
    {
        // check out the right tag
        `git clone --recursive $this->base_location $this->temp_path`;
        `cd $this->temp_path; git checkout $this->tag -b $this->temp_token`;
        // remove the git directory
        Spark_utils::remove_full_directory("$this->temp_path/.git");

        if (!file_exists($this->temp_path))
        {
            throw new Spark_exception('Failed to retrieve the spark ;(');
        }
        return true;
    }

}
