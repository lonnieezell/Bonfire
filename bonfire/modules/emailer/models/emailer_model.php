<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Emailer Model
 *
 * @package    Bonfire\Modules\Emailer\Models\Emailer_model
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/guides
 */
class Emailer_model extends BF_Model
{
	/**
	 * @var string Name of the table
	 */
	protected $table_name = 'email_queue';

	/**
	 * @var string Name of the primary key
	 */
	protected $key = 'id';

	/**
	 * @var bool Whether to use soft deletes
	 */
	protected $soft_deletes = false;

	/**
	 * @var string The date format to use
	 */
	protected $date_format = 'datetime';

	/**
	 * @var bool Set the created time automatically on a new record (if true)
	 */
	protected $set_created = false;

	/**
	 * @var bool Set the modified time automatically on editing a record (if true)
	 */
	protected $set_modified = false;

}
/* End of file emailer_model.php */