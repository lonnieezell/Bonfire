<?php

class Zip_spark extends Spark_type {

    function __construct($data)
    {
        parent::__construct($data);
        $this->temp_file = $this->temp_path . '.zip';
        $this->archive_url = property_exists($this->data, 'archive_url') ? $this->data->archive_url : null;
    }

    function location_detail()
    {
        return "ZIP archive at $this->archive_url";
    }

    private static function unzip_installed()
    {
        return !!`unzip`;
    }

    function retrieve()
    {
        file_put_contents($this->temp_file, file_get_contents($this->archive_url));
        // Try a few ways to unzip
        if (class_exists('ZipArchive'))
        {
            $zip = new ZipArchive;
            $zip->open($this->temp_file);
            $zip->extractTo($this->temp_path);
            $zip->close();
        }
        else
        {
            if (!self::unzip_installed())
            {
                throw new Spark_exception('You have to install PECL ZipArchive or `unzip` to install this spark.');
            }
            `unzip $this->temp_file -d $this->temp_path`;
        }

        if (!file_exists($this->temp_path))
        {
            throw new Spark_exception('Failed to retrieve the spark ;(');
        }
        return true;
    }

}
