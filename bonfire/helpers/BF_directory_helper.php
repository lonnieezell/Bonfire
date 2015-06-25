<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Bonfire Directory Helper
 *
 * @package Bonfire\Helpers\BF_directory_helper
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer
 */
if (! function_exists('bcDirectoryMap')) {
    /**
     * Create a Directory Map
     *
     * Reads the specified directory and builds an array
     * representation of it. Sub-folders contained with the
     * directory will be mapped as well.
     *
     * @param   string  $source_dir     Path to source
     * @param   int $directory_depth    Depth of directories to traverse
     *                      (0 = fully recursive, 1 = current dir, etc)
     * @param   bool    $hidden         Whether to show hidden files
     * @return  array
     */
    function bcDirectoryMap($source_dir, $directory_depth = 0, $hidden = false)
    {
        if ($fp = @opendir($source_dir)) {
            $filedata   = array();
            $new_depth  = $directory_depth - 1;
            $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            while (false !== ($file = readdir($fp))) {
                // Remove '.', '..', and hidden files [optional]
                if ($file === '.'
                    || $file === '..'
                    || ($hidden === false && $file[0] === '.')
                ) {
                    continue;
                }

                if (($directory_depth < 1 || $new_depth > 0)
                    && is_dir($source_dir . $file)
                ) {
                    $filedata[$file] = bcDirectoryMap(
                        $source_dir . $file . DIRECTORY_SEPARATOR,
                        $new_depth,
                        $hidden
                    );
                } else {
                    $filedata[] = $file;
                }
            }

            closedir($fp);
            return $filedata;
        }

        return false;
    }
}
