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
 * Bonfire Images
 *
 * A library to assist in on-the-fly resizing of images.
 * To use, make the src of your img tag reflect:
 *
 * http://mysite.com/images/abc.jpg?assets=images&size=xx
 *
 * Parameters:
 * The following parameters are available for use in the params of the URL.
 *
 * assets - The folder (relative to webroot) that the images can be found in.
 *     Optional. Defaults to '/assets'.
 * size - If you want a square image returned, you can simply pass the size param
 *     and this will be used for both the height and width of the image.
 * height - The height (in px) of the image. Not required if 'size' is passed.
 * width - The width (in px) of the image. Not required if 'size' is passed.
 * crop - If 'yes', will crop the image to the size or height/width.
 *
 * @package Bonfire\Controllers\Images
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/working_with_assets
 *
 */
class Images extends Base_Controller
{
    /**
     * Simply redirects all calls to the index() method.
     *
     * @param string $file The name of the image to return.
     *
     * @return void
     */
    public function _remap($file = null)
    {
        $this->index($file);
    }

    /**
     * Performs the image processing based on the parameters provided in the GET
     * request.
     *
     * @param string $file The name of the image to return.
     *
     * @return void
     */
    public function index($file = null)
    {
        if (empty($file) || ! is_string($file)) {
            die('No image to process.');
        }

        $this->output->enable_profiler(false);

        // Get our params
        $assets = $this->input->get('assets') ?: 'assets/images';
        $size   = $this->input->get('size');
        $height = $this->input->get('height');
        $width  = $this->input->get('width');
        $ratio  = $this->input->get('ratio');
        $force  = $this->input->get('force');
        $module = $this->input->get('module');

        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if (empty($ext)) {
            die('Filename does not include a file extension.');
        }

        // Is it a square?
        if (! empty($size)) {
            $height = (int)$size;
            $width  = (int)$size;
            $ratio  = 'no';
        }

        $img_file = empty($module) ? (FCPATH . "{$assets}/{$file}") : Modules::file_path($module, $assets, $file);

        if (! is_file($img_file)) {
            die('Image could not be found.');
        }

        $new_file = FCPATH . 'assets/cache/' . str_replace('.' . $ext, '', $file) . "_{$width}x{$height}." . $ext;

        if (! is_file($new_file) || $force != 'yes') {
            $config = array(
                'image_library'  => 'gd2',
                'source_image'   => $img_file,
                'new_image'      => $new_file,
                'create_thumb'   => false,
                'maintain_ratio' => $ratio == 'no' ? false : true,
                'master_dim'     => empty($width) ? 'height' : 'width',
                'width'          => empty($width) ? $height : $width,
                'height'         => empty($height) ? $width : $height,
            );

            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
        }

        $this->output->set_content_type($ext)
                     ->set_output(file_get_contents($new_file));
    }
}
