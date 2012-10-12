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
 * Unit Testing
 *
 * This class extends the CodeIgniter core Unit_test library to add
 * extra functionality used in Bonfire.
 *
 * @package    Bonfire
 * @subpackage Libraries
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/core/unit_test.html
 *
 */
class MY_Unit_test extends CI_Unit_test
{

	/**
	 * Clears the results array so multiple files don't
	 * bleed over into each other.
	 *
	 * @return void
	 */
	public function reset()
	{
		$this->results = array();
	}//end reset()

	//--------------------------------------------------------------------


}//end class