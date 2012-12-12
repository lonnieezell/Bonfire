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
 * @filesource
 */
// ------------------------------------------------------------------------

class MY_Input extends CI_Input
{
	/**
	 * Test for a specific POST parameter
	 *
	 * This is used to test for a specific submit button.
	 * The idiom isn't native to CodeIgniter, but Bonfire
	 * uses it everywhere.
	 *
	 *
	 * (The main CodeIgniter idiom is to rely on
	 * form_validation->run(), which automatically tests
	 * for empty POST data.  If you tried refactoring
	 * Bonfire to do so, you might notice a loss of clarity
	 * when dealing with
	 *
	 *  - forms with many fields
	 *  - create() and edit() methods which call a common
	 *    save() method
	 *
	 * Bonfire also uses forms with multiple submit buttons,
	 * and provide templates (in the modulebuilder) for
	 * developers to write such forms themselves.  An obvious
	 * example is a blog: you want to be able to review the
	 * blog post, and then perform an action such as
	 * [un]publishing it.)
	 *
	 *
	 * @param string $index to test
	 * @return bool
	 */
    public function post_key_exists($index)
	{
		return isset($_POST[$index]);
	}
}

/* End of file MY_Input.php */
/* Location: ./application/core/MY_Input.php */
