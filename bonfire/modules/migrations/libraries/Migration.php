<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
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
 * Migration Interface
 *
 * All migrations should implement this, forces up() and down() and gives access
 * to the CI super-global.
 *
 * @todo Move this to a separate file and require it in the Migrations library.
 *
 * @package Bonfire\Modules\Migrations\Libraries\Migrations
 * @author  Phil Sturgeon http://philsturgeon.co.uk/
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/migrations
 */
abstract class Migration
{
    /** @var string The type of migration being run, either 'forge' or 'sql'. */
    public $migration_type = 'forge';

    //--------------------------------------------------------------------------

    /**
     * Abstract method run when increasing the schema version.
     *
     * Typically installs new data to the database or creates new tables.
     */
    abstract public function up();

    /**
     * Abstract method run when decreasing the schema version.
     */
    abstract public function down();

    /**
     * Getter method
     *
     * @param mixed $var
     *
     * @return mixed
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }
}
