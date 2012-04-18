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
 * Emailer Model
 *
 * @package    Bonfire
 * @subpackage Emailer
 * @category   Model
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Emailer_model extends BF_Model
{


	/**
	 * Name of the table
	 *
	 * @var string
	 */
	protected $table = 'email_queue';

	/**
	 * Name of the primary key
	 *
	 * @var string
	 */
	protected $key = 'id';

	/**
	 * Use soft deletes or not
	 *
	 * @var bool
	 */
	protected $soft_deletes = FALSE;

	/**
	 * The date format to use
	 *
	 * @var string
	 */
	protected $date_format = 'datetime';

	/**
	 * Set the created time automatically on a new record
	 *
	 * @var bool
	 */
	protected $set_created = FALSE;

	/**
	 * Set the modified time automatically on editing a record
	 *
	 * @var bool
	 */
	protected $set_modified = FALSE;

}//end class
