<?php
require_once 'spark_type.php';
require_once 'spark_types/git_spark.php';
require_once 'spark_types/hg_spark.php';
require_once 'spark_types/zip_spark.php';

class Spark_source {

    public $url;
    public $version_data;
    public $version;

    function __construct($url)
    {
        $this->url = $url;
        $this->version_data = json_decode(@file_get_contents("http://$this->url/api/system/latest"));
        $this->version = $this->version_data->spark_manager;
        $this->warn_if_outdated();
    }

    function get_url()
    {
        return $this->url;
    }

    // get details on an individual spark
    function get_spark_detail($spark_name, $version = 'HEAD')
    {
        $json_data = @file_get_contents("http://$this->url/api/packages/$spark_name/versions/$version/spec");
        if (!$json_data) return null; // no such spark here
        $data = json_decode($json_data);
        // if we don't succeed - throw an error
        if ($data == null || !$data->success)
        {
            $message = "Error retrieving spark detail from source: $this->url";
            if ($data != null) $message .= " ($data->message)";
            throw new Spark_exception($message);
        }
        // Get the detail for this spark
        return $this->get_spark($data->spec);
    }

    // get details on multiple sparks by search term
    function search($term)
    {
        $json_data = @file_get_contents("http://$this->url/api/packages/search?q=" . urlencode($term)); 
        $data = json_decode($json_data);
        // if the data isn't around of success is false, return a warning for this source
        if ($data == null || !$data->success)
        {
            $message = "Error searching source: $this->url";
            if ($data != null) $message .= " ($data->message)";
            Spark_utils::warning($message);
            return array();
        }
        // Get sparks for each one
        $results = array();
        foreach($data->results as $data)
        {
            $results[] = $this->get_spark($data);
        }
        return $results;
    }

    private function warn_if_outdated()
    {
        if ($this->outdated())
        {
            Spark_utils::warning("Your installed version of spark is outdated (current version: " . $this->outdated() . " / latest: " . $this->version . ")");
            Spark_utils::warning("To upgrade now, use `php tools/spark upgrade-system`");
        }
    }

    function outdated() {
        // Get the version for this source
        if (!$this->version) return; // no version found

        // Split versions
        list($self_major, $self_minor, $self_patch) = explode('.', SPARK_VERSION);
        list($source_major, $source_minor, $source_patch) = explode('.', $this->version);

        // Compare
        if ($self_major < $source_major ||
            $self_major == $source_major && $self_minor < $source_minor ||
            $self_major == $source_major && $self_minor == $source_minor && $self_patch < $source_patch)
        {
            return SPARK_VERSION;
        }
    }

    private function get_spark($data)
    {
        if ($data->repository_type == 'hg') return Mercurial_spark::get_spark($data);
        else if ($data->repository_type == 'git') return Git_spark::get_spark($data);
        else if ($data->repository_type == 'zip') return new Zip_spark($data);
        else throw new Exception('Unknown repository type: ' . $data->repository_type);
    }

}
