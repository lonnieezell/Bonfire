<?php defined('BASEPATH') || exit('No direct script access allowed');

defined('EXT') || define('EXT', '.php');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
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
 * Migrations Library
 *
 * Migrations provide a simple method to version the contents of your database, and make
 * those changes easily distributable to other developers in different server environments.
 *
 * Migrations are stored in specially-named PHP files under *application/db/migrations/*.
 * Each migration file must be numbered consecutively, starting at *001* and growing larger
 * with each new migration from there. The rest of the filename should tell what the migration does.
 *
 * For example: 001_install_initial_schema.php
 *
 * The class inside of the file must extend the abstract Migration class and implement both the
 * up() and down() methods to install and uninstall the tables/changes. The class itself should be
 * named:
 *
 * :	class Migration_install_initial_schema extends Migration {
 * :
 * :		function up() {}
 * :
 * :		function down() {}
 * :	}
 *
 * @package Bonfire\Modules\Migrations\Libraries\Migrations
 * @author  Mat'as Montes
 * @author  Phil Sturgeon http://philsturgeon.co.uk/
 * @author  Spicer Matthews <spicer@cloudmanic.com> Cloudmanic Labs, LLC http://www.cloudmanic.com/
 * @author  Bonfire Dev Team
 */

/**
 * Migration Interface
 *
 * All migrations should implement this, forces up() and down() and gives access
 * to the CI super-global.
 *
 * @package Bonfire\Modules\Migrations\Libraries\Migrations
 * @author  Phil Sturgeon http://philsturgeon.co.uk/
 * @author  Bonfire Dev Team
 * @link       http://cibonfire.com/docs/migrations
 */
abstract class Migration
{
	/**
	 * @var string The type of migration being ran, either 'forge' or 'sql'.
	 */
	public $migration_type = 'forge';

	//--------------------------------------------------------------------

	/**
	 * Abstract method ran when increasing the schema version.
	 *
	 * Typically installs new data to the database or creates new tables.
	 */
	public abstract function up();

	/**
	 * Abstract method ran when decreasing the schema version.
	 */
	public abstract function down();

	//--------------------------------------------------------------------

	/**
	 * Getter method
	 *
	 * @param mixed $var
	 *
	 * @return object
	 */
	function __get($var)
	{
		return get_instance()->$var;
	}
}
//end Migration interface

/**
 * Migrations Library
 *
 * Utility main controller.
 *
 * @package Bonfire\Modules\Migrations\Libraries\Migrations
 * @author  Mat'as Montes
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/migrations
 */
class Migrations
{
	/**
	 * @var string Path to the core migrations files
	 */
	private $migrations_path;

	/**
	 * @var bool Show verbose messages or not
	 */
	private $verbose = false;

	/**
	 * @var string Error message
	 */
	public $error = '';

	//--------------------------------------------------------------------

	/**
	 * Initialize the configuration settings
	 *
	 * @return void
	 */
	function __construct($params = array())
	{
		$this->_ci =& get_instance();

        $this->migrations_path = isset($params['migrations_path']) ? $params['migrations_path'] : (BFPATH . 'migrations');

		// Add trailing slash if not set
		if (substr($this->migrations_path, -1) != '/') {
			$this->migrations_path .= '/';
		}

		// Sanity check
		if ( ! is_dir($this->migrations_path)) {
			show_error('Migrations has been loaded but is set up incorrectly.');
		}

		$this->_ci->load->dbforge();

		// If the schema_version table is missing, make it
		if ( ! $this->_ci->db->table_exists('schema_version')) {
			$this->_ci->dbforge->add_field(array(
				'type' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => false
                ),
				'version' => array(
                    'type'       => 'INT',
                    'constraint' => 4,
                    'default'    => 0
                ),
			));
			$this->_ci->dbforge->add_key('type', true);
			$this->_ci->dbforge->create_table('schema_version', true);
			$this->_ci->db->insert(
                'schema_version',
                array('type' => 'core', 'version' => 0)
            );
		}
	}

	/**
	 * Set if there should be verbose output or not
	 *
	 * @param bool $state TRUE/FALSE
	 */
	public function set_verbose($state)
	{
		$this->verbose = $state;
	}

	/**
	 * Migrate to a specific schema version.
	 *
	 * Calls each migration step required to get to the schema version of
	 * choice.
	 *
	 * @access public
	 *
	 * @param int    $version An int that is the target version to migrate to.
	 * @param string $type    A string that represents the name of the module,
	 * or 'app_' for application migrations. If empty, it applies core
	 * migrations.
	 *
	 * @return mixed TRUE if already latest, FALSE if failed, int if upgraded
	 */
	public function version($version, $type = '')
	{
		$schema_version = $this->get_schema_version($type);
		$start = $schema_version;
		$stop  = $version;

		switch ($type) {
			case '':
				$migrations_path = $this->migrations_path;
				break;

			case 'app_':
				$migrations_path = APPPATH . 'db/migrations/';
				break;

			default:
				$migrations_path = Modules::path(substr($type, 0, -1), 'migrations') . '/';
				break;
		}

		if ($version > $schema_version) {
			// Moving Up
			$start++;
			$stop++;
			$step = 1;
		} else {
			// Moving Down
			$step = -1;
		}

		$method = $step == 1 ? 'up' : 'down';
		$migrations = array();

		// Prepare to actually DO the migrations
		// But first, make sure that everything is the way it should be
		for ($i = $start; $i != $stop; $i += $step) {
			$f = glob(sprintf($migrations_path . '%03d_*' . EXT, $i));

			// Only one migration per step is permitted
			if (count($f) > 1) {
				$this->error = sprintf($this->_ci->lang->line("multiple_migrations_version"), $i);
				return 0;
			}

			// Migration step not found
			if (count($f) == 0) {
				// If trying to migrate up to a version greater than the last
				// existing one, migrate to the last one.
				if ($step == 1) {
					break;
				}

				// If trying to migrate down but we're missing a step,
				// something must definitely be wrong.
				$this->error = sprintf($this->_ci->lang->line("migration_not_found"), $i);
				return 0;
			}

			$file = basename($f[0]);
			$name = basename($f[0], EXT);

			// Filename validations
			if (preg_match('/^\d{3}_(\w+)$/', $name, $match)) {
				$match[1] = strtolower($match[1]);

				// Cannot repeat a migration at different steps
				if (in_array($match[1], $migrations)) {
					$this->error = sprintf($this->_ci->lang->line("multiple_migrations_name"), $match[1]);
					return 0;
				}

				include $f[0];
				$class = 'Migration_' . ucfirst($match[1]);

				if ( ! class_exists($class)) {
					$this->error = sprintf($this->_ci->lang->line("migration_class_doesnt_exist"), $class);
					return 0;
				}

				if ( ! is_callable(array($class, "up"))
                    || ! is_callable(array($class, "down"))
                   ) {
					$this->error = sprintf($this->_ci->lang->line('wrong_migration_interface'), $class);
					return 0;
				}

				$migrations[] = $match[1];
			} else {
				$this->error = sprintf($this->_ci->lang->line("invalid_migration_filename"), $file, $migrations_path);
				return 0;
			}
		}

		$version = $i + ($step == 1 ? -1 : 0);

		// If there is nothing to do, quit
		if ($migrations === array()) {
			if ($this->verbose) {
				echo "Nothing to do, bye!\n";
			}

			return true;
		}

		if ($this->verbose) {
			echo "<p>Current schema version: {$schema_version}<br />";
			echo "Moving {$method} to version {$version}</p>";
			echo "<hr />";
		}

		// Loop through the migrations
		foreach ($migrations as $m) {
			if ($this->verbose) {
				echo "$m:<br />";
				echo "<blockquote>";
			}

			$class = 'Migration_' . ucfirst($m);
			$c     = new $class;

			if ($c->migration_type == 'forge') {
				call_user_func(array($c, $method));
			} elseif ($c->migration_type == 'sql') {
				$sql = $c->$method();
				$this->do_sql_migration($sql);
			}

			if ($this->verbose) {
				echo "</blockquote>";
				echo "<hr />";
			}

			$schema_version += $step;
			$this->_update_schema_version($schema_version, $type);
		}

		if ($this->verbose) {
			echo "<p>All done. Schema is at version {$schema_version}.</p>";
		}

		return $schema_version;
	}

	/**
	 * Install the schema up to the latest version
	 *
	 * @param string $type A string that represents the name of the module, or
	 * 'app_' for application migrations. If empty, it returns core migrations.
	 *
	 * @return int The version number this type is at
	 */
	public function install($type = '')
	{
		$latest_version = $this->get_latest_version($type);
		if ($latest_version > 0) {
			return $this->version($latest_version, $type);
		}

        $this->error = $this->_ci->lang->line('no_migrations_found');
        return 0;
	}

	/**
	 * Handle auto-upgrading migrations of core and/or app on page load.
	 *
	 * @return void
	 */
	public function auto_latest()
	{
		$auto_core = $this->_ci->config->item('migrate.auto_core');
		$auto_app  = $this->_ci->config->item('migrate.auto_app');

		if ($auto_core) {
			$this->install('');
		}

		if ($auto_app) {
			$this->install('app_');
		}
	}

	/**
	 * Retrieve the latest available version.
	 *
	 * @param string $type A string that represents the name of the module, or
	 * 'app_' for application migrations. If empty, it returns core migrations.
	 *
	 * @return int Latest available migration file.
	 */
	public function get_latest_version($type = '')
	{
		$migrations = $this->get_available_versions($type);
		if ( ! empty($migrations)) {
			$last_migration = end($migrations);
			$last_version =	substr($last_migration, 0, 3);

			return intval($last_version, 10);
		}

        return 0;
	}

	/**
	 * Return a list of available migration files in the migrations folder.
	 *
	 * @param string $type A string that represents the name of the module, or
	 * 'app_' for application migrations. If empty, it returns core migrations.
	 *
	 * @return array An array of migration files
	 */
	public function get_available_versions($type = '')
	{
		switch ($type) {
			case '':
				$migrations_path = $this->migrations_path;
				break;

			case 'app_':
				$migrations_path = APPPATH . 'db/migrations/';
				break;

			default:
				$migrations_path = Modules::path(substr($type, 0, -1), 'migrations') . '/';
				break;
		}

		// List all *_*.php files in the migrations path
		$files = glob($migrations_path . '*_*' . EXT);
		for ($i = 0; $i < count($files); $i++) {
			// Remove path and extension
			$files[$i] = basename($files[$i], EXT);

			// Mark wrongly formatted files as FALSE for later filtering
			if ( ! preg_match('/^\d{3}_(\w+)$/', $files[$i])) {
				$files[$i] = false;
			}
		}

		$migrations = array_filter($files);
		sort($migrations);

		return $migrations;
	}

	/**
	 * Execute raw SQL migrations. Will manually break the commands on a ';' so
	 * that multiple commmands can be run at once. Very handy for using
	 * phpMyAdmin dumps.
	 *
	 * @param string $sql A string with one or more SQL commands to be run.
	 *
	 * @return void
	 */
	public function do_sql_migration($sql = '')
	{
		if (empty($sql)) {
			return;
		}

		// Split the sql into usable commands on ';'
		$queries = explode(';', $sql);
		foreach ($queries as $q) {
			if (trim($q)) {
				$this->_ci->db->query(trim($q));
			}
		}
	}

	/**
	 * Retrieve current schema version
	 *
	 * @param string $type A string that represents the name of the module, or
	 * 'app_' for application migrations. If empty, it returns core migrations.
	 *
	 * @return int Current Schema version
	 */
	public function get_schema_version($type = '')
	{
		if ($this->_check_migrations_column('type')) {
			// new schema table layout
			$type = empty($type) ? 'core' : $type;
			$row = $this->_ci->db->where(array('type' => $type))
                                 ->get('schema_version')
                                 ->row();

			return isset($row->version) ? $row->version: 0;
		}

        $row = $this->_ci->db->get('schema_version')
                             ->row();

        $schema = "{$type}version";

        return isset($row->$schema) ? $row->$schema : 0;
	}

	/**
	 * Store the current schema version in the database.
	 *
	 * @param int    $schema_version An integer with the latest Schema version
	 * reached
	 * @param string $type           A string that is appended with '_schema' to
	 * create the field name to store in the database.
	 *
	 * @return void
	 */
	private function _update_schema_version($schema_version, $type = '')
	{
		logit("[Migrations] Schema {$type} updated to: {$schema_version}");
		if ($this->_check_migrations_column('type')) {
			// new schema table layout
			$type = empty($type) ? 'core' : $type;

			// If the row doesn't exist, create it...
			$query = $this->_ci->db->where(array('type' => $type))
                                   ->get('schema_version');

			if ($schema_version != 0) {
				if ( ! $query->num_rows()) {
					$this->_ci->db->insert(
                        'schema_version',
                        array(
                            'type'        => $type,
                            'version' => $schema_version,
                        )
                    );
				}

				return $this->_ci->db->update('schema_version', array('version' => $schema_version), array('type' => $type));
			} elseif ($query->num_rows()) {
				return $this->_ci->db->delete('schema_version', array('type' => $type));
			}
		} else {
			// If the row doesn't exist, create it...
			if ( ! $this->_check_migrations_column("{$type}version")) {
				$this->_ci->load->dbforge();
				$this->_ci->dbforge->add_column(
                    'schema_version',
                    array(
					$type .'version'	=> array(
						'type'			=> 'INT',
						'constraint'	=> 4,
						'null'			=> true,
						'default'		=> 0
					)
				));

			}

			return $this->_ci->db->update('schema_version', array(
				$type.'version' => $schema_version
			));
		}
	}

	/**
	 * Check if the DB table schema_version is in the new format or old
	 *
	 * @param string $column_name Name of the column to check the existance of
	 *
	 * @return bool
	 */
	private function _check_migrations_column($column_name)
	{
		$row = $this->_ci->db->get('schema_version')->row();
		if (isset($row->$column_name)) {
			return true;
		}

		return false;
	}
}
/* End /modules/migrations/libraries/Migrations.php */