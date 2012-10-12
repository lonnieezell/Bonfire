<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2012, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Bonfire Images
 *
 * A helper library to assist in on-the-fly resizing of images.
 * To use, make the src of your img tag reflect:
 *
 * http://mysite.com/images/abc.jpg?assets=images&size=xx
 *
 * Parameters:
 * The following parameters are available for use in the params
 * of the URL.
 *
 * assets	- The folder (relative to webroot) that the images can be found in.
 * 			  Optional. Defaults to '/assets'.
 * size	- If you want a square image returned, you can simply pass the size param
 * 			  and this will be used for both the height and width of the image.
 * height	- The height (in px) of the image. Not required if 'size' is passed.
 * width	- The width (in px) of the image. Not required if 'size' is passed.
 * crop	- If 'yes', will crop the image to the size or height/width.
 *
 * @package    Bonfire
 * @subpackage Controllers
 * @category   Helpers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Images extends Base_Controller {

	/**
	 * Simply redirects all calls to the index() method.
	 *
	 * @param string $file The name of the image to return.
	 */
	public function _remap($file=null)
	{
		$this->index($file);
	}//end _remap()

	//--------------------------------------------------------------------

	/**
	 * Performs the image processing based on the parameters provided in the GET request
	 *
	 * @param string $file The name of the image to return.
	 */
	public function index($file=null)
	{

		if (empty($file) || !is_string($file))
		{
			die('No image to process.');
		}

		$this->output->enable_profiler(false);

		// Get our params
		$assets	= $this->input->get('assets') ? $this->input->get('assets') : 'assets/images';
		$size	= $this->input->get('size');
		$height	= $this->input->get('height');
		$width	= $this->input->get('width');
		$crop	= $this->input->get('crop');
		$force	= $this->input->get('force');

		$ext = pathinfo($file, PATHINFO_EXTENSION);

		if (empty($ext))
		{
			die('Filename does not include a file extension.');
		}

		// Is it a square?
		if (!empty($size))
		{
			$height = (int)$size;
			$width	= (int)$size;
		}

		// For now, simply return the file....
		$img_file = FCPATH . $assets .'/'. $file;

		if (!is_file($img_file))
		{
			die('Image could not be found.');
		}

		$new_file = FCPATH .'assets/cache/'. str_replace('.'. $ext, '', $file) ."_{$width}x{$height}.". $ext;

		if (!is_file($new_file) || $force != 'yes')
		{
			$config = array(
				'image_library'		=> 'gd2',
				'source_image'		=> $img_file,
				'new_image'			=> $new_file,
				'create_thumb'		=> false,
				'maintain_ratio'	=> $crop == 'yes' ? true : false,
				'width'				=> $width,
				'height'			=> $height,
			);

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
		}

		$this->output
			->set_content_type($ext)
			->set_output(file_get_contents($new_file));
	}//end index()

	//--------------------------------------------------------------------

}//end class
