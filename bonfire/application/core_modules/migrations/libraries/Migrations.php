<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Migrations Library
 *
 * Migrations provide a simple method to version the contents of your database, and make
 * those changes easily distributable to other developers in different server environments.
 *
 * Migrations are stored in specially-named PHP files under *bonfire/application/db/migrations/*.
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
 * @package    Bonfire
 * @author     Mat�as Montes
 * @author     Phil Sturgeon http://philsturgeon.co.uk/
 * @author     Spicer Matthews <spicer@cloudmanic.com> Cloudmanic Labs, LLC http://www.cloudmanic.com/
 * @author     Bonfire Dev Team
 */

// ------------------------------------------------------------------------

/**
 * Migration Interface
 *
 * All migrations should implement this, forces up() and down() and gives
 * access to the CI super-global.
 *
 * @package    Bonfire
 * @subpackage Modules_Migrations
 * @category   Libraries
 * @abstract
 * @author     Phil Sturgeon http://philsturgeon.co.uk/
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 */
abstract class Migration
{

	/**
	 * The type of migration being ran, either 'forge' or 'sql'.
	 *
	 * @var string
	 */
	public $migration_type = 'forge';

	//--------------------------------------------------------------------

	/**
	 * Abstract method ran when increasing the schema version. Typically installs
	 * new data to the database or creates new tables.
	 *
	 * @access public
	 * @abstract
	 */
	public abstract function up();

	/**
	 * Abstract method ran when decreasing the schema version.
	 *
	 * @access public
	 * @abstract
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
	}//end __get()

	//--------------------------------------------------------------------
}//end Migration

// ------------------------------------------------------------------------

/**
 * Migrations Library
 *
 * Utility main controller.
 *
 * @package    Bonfire
 * @subpackage Modules_Migrations
 * @category   Libraries
 * @author     Mat�as Montes
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Migrations
{

	/**
	 * Enable or disable the migrations functionality
	 *
	 * @access private
	 *
	 * @var bool
	 */
	private $migrations_enabled = FALSE;

	/**
	 * Path to the migrations files
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $migrations_path = ".";

	/**
	 * Show verbose messages or not
	 *
	 * @access private
	 *
	 * @var bool
	 */
	private $verbose = FALSE;

	/**
	 * Error message
	 *
	 * @access public
	 *
	 * @var string
	 */
	public $error = "";

	//--------------------------------------------------------------------

	/**
	 * Initialize the configuration settings
	 *
	 * @return void
	 */
	function __construct()
	{
		$this->_ci =& get_instance();

		$this->_ci->config->load('migrations/migrations');

		$this->migrations_enabled = $this->_ci->config->item('migrations_enabled');
		$this->migrations_path = realpath($this->_ci->config->item('migrations_path'));

		// Idiot check
		$this->migrations_enabled AND $this->migrations_path OR show_error('Migrations has been loaded but is disabled or set up incorrectly.');

		// If not set, set it
		if ($this->migrations_path == '')
		{
			$this->migrations_path = APPPATH . 'migrations/';
		}

		// Add trailing slash if not set
		else if (substr($this->migrations_path, -1) != '/')
		{
			$this->migrations_path .= '/';
		}

		$this->_ci->load->dbforge();
		$this->_ci->lang->load('migrations/migrations');

		// If the schema_version table is missing, make it
		if ( ! $this->_ci->db->table_exists('schema_version'))
		{
			$this->_ci->dbforge->add_field(array(
				'type' => array('type' => 'VARCHAR', 'constraint' => 20, 'null' => FALSE),
				'version' => array('type' => 'INT', 'constraint' => 4, 'default' => 0),
			));
			$this->_ci->dbforge->add_key('type', TRUE);
			$this->_ci->dbforge->create_table('schema_version', TRUE);

			$this->_ci->db->insert('schema_version', array('type' => 'core', 'version' => 0));
		}

		// Make sure out application helper is loaded.
		if (!function_exists('logit'))
		{
			$this->_ci->load->helper('application');
		}

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * This will set if there should be verbose output or not
	 *
	 * @access public
	 *
	 * @param bool $state TRUE/FALSE
	 */
	public function set_verbose($state)
	{
		$this->verbose = $state;

	}//end set_verbose()

	//--------------------------------------------------------------------

	/**
	 * Installs the schema up to the last version
	 *
	 * @param string $type A string that represents the name of the module, or 'app_' for application migrations. If empty, it returns core migrations.
	 *
	 * @return int The version number this type is at
	 */
	public function install($type='')
	{
		$migrations_path = $type == 'app_' ? $this->migrations_path : $this->migrations_path .'core/';

		// Load all *_*.php files in the migrations path
		$files = glob($migrations_path.'*_*'.EXT);
		$file_count = count($files);

		for($i=0; $i < $file_count; $i++)
		{
			// Mark wrongly formatted files as FALSE for later filtering
			$name = basename($files[$i],EXT);
			if(!preg_match('/^\d{3}_(\w+)$/',$name)) $files[$i] = FALSE;
		}

		$migrations = array_filter($files);

		if ( ! empty($migrations))
		{
			sort($migrations);
			$last_migration = basename(end($migrations));

			// Calculate the last migration step from existing migration
			// filenames and procceed to the standard version migration
			$last_version =	substr($last_migration,0,3);
			return $this->version(intval($last_version,10), $type);
		}
		else
		{
			$this->error = $this->_ci->lang->line('no_migrations_found');
			return 0;
		}
	}//end install()

	// --------------------------------------------------------------------

	/**
	 * Migrate to a schema version.
	 *
	 * Calls each migration step required to get to the schema version of
	 * choice.
	 *
	 * @access public
	 *
	 * @param int    $version An int that is the target version to migrate to.
	 * @param string $type    A string that represents the name of the module, or 'app_' for application migrations. If empty, it returns core migrations.
	 *
	 * @return mixed TRUE if already latest, FALSE if failed, int if upgraded
	 */
	function version($version, $type='')
	{
		$schema_version = $this->get_schema_version($type);
		$start = $schema_version;
		$stop = $version;

		switch ($type)
		{
			case '':
				$migrations_path = $this->migrations_path .'core/';
				break;
			case 'app_':
				$migrations_path = $this->migrations_path;
				break;
			default:
				$migrations_path = module_path(substr($type, 0, -1), 'migrations') .'/';
				break;
		}

		if ($version > $schema_version)
		{
			// Moving Up
			$start++;
			$stop++;
			$step = 1;
		}
		else
		{
			// Moving Down
			$step = -1;
		}

		$method = $step == 1 ? 'up' : 'down';
		$migrations = array();

		// We now prepare to actually DO the migrations

		// But first let's make sure that everything is the way it should be
		for($i=$start; $i != $stop; $i += $step)
		{
			$f = glob(sprintf($migrations_path . '%03d_*'.EXT, $i));
			logit($f);
			// Only one migration per step is permitted
			if (count($f) > 1)
			{
				$this->error = sprintf($this->_ci->lang->line("multiple_migrations_version"),$i);
				return 0;
			}

			// Migration step not found
			if (count($f) == 0)
			{
				// If trying to migrate up to a version greater than the last
				// existing one, migrate to the last one.
				if ($step == 1)
				{
					break;
				}

				// If trying to migrate down but we're missing a step,
				// something must definitely be wrong.
				$this->error = sprintf($this->_ci->lang->line("migration_not_found"),$i);
				return 0;
			}

			$file = basename($f[0]);
			$name = basename($f[0],EXT);

			// Filename validations
			if (preg_match('/^\d{3}_(\w+)$/', $name, $match))
			{
				$match[1] = strtolower($match[1]);

				// Cannot repeat a migration at different steps
				if (in_array($match[1], $migrations))
				{
					$this->error = sprintf($this->_ci->lang->line("multiple_migrations_name"),$match[1]);
					return 0;
				}

				include $f[0];
				$class = 'Migration_'.ucfirst($match[1]);

				if ( ! class_exists($class))
				{
					$this->error = sprintf($this->_ci->lang->line("mig_class_doesnt_exist"), $class);
					return 0;
				}

				if ( ! is_callable(array($class,"up")) || ! is_callable(array($class,"down"))) {
					$this->error = sprintf($this->_ci->lang->line('wrong_migration_interface'),$class);
					return 0;
				}

				$migrations[] = $match[1];
			}
			else
			{
				$this->error = sprintf($this->_ci->lang->line("invalid_migration_filename"),$file, $migrations_path);
				return 0;
			}//end if
		}//end for

		$version = $i + ($step == 1 ? -1 : 0);

		// If there is nothing to do, bitch and quit
		if ($migrations === array())
		{
			if ($this->verbose)
			{
				echo "Nothing to do, bye!\n";
			}

			return TRUE;
		}


		if ($this->verbose)
		{
			echo "<p>Current schema version: ".$schema_version."<br/>";
			echo "Moving ".$method." to version ".$version."</p>";
			echo "<hr/>";
		}

		// Loop through the migrations
		foreach($migrations AS $m)
		{
			if ($this->verbose)
			{
				echo "$m:<br />";
				echo "<blockquote>";
			}

			$class = 'Migration_'.ucfirst($m);

			$c = new $class;

			if ($c->migration_type == 'forge')
			{
				call_user_func(array($c, $method));
			}
			else if ($c->migration_type == 'sql')
			{
				$sql = $c->$method();
				$this->do_sql_migration($sql);
			}

			if ($this->verbose)
			{
				echo "</blockquote>";
				echo "<hr/>";
			}

			$schema_version += $step;
			$this->_update_schema_version($schema_version, $type);
		}//end foreach

		if ($this->verbose)
		{
			echo "<p>All done. Schema is at version $schema_version.</p>";
		}

		return $schema_version;

	}//end version()

	// --------------------------------------------------------------------

	/**
	 * Handles auto-upgrading migrations of core and/or app on page load.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function auto_latest()
	{
		$auto_core = $this->_ci->config->item('migrate.auto_core');
		$auto_app = $this->_ci->config->item('migrate.auto_app');

		if ($auto_core)
		{
			$this->version($this->get_latest_version(''), '');
		}

		if ($auto_app)
		{
			$this->version($this->get_latest_version('app_'), 'app_');
		}

	}//end auto_latest()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// ! Utility Methods
	//--------------------------------------------------------------------

	/**
	 * Set's the schema to the latest migration
	 *
	 * @access public
	 *
	 * @return mixed TRUE if already latest, FALSE if failed, int if upgraded
	 */
	public function latest()
	{
		$version = $this->_ci->config->item('migrations_version');
		return $this->version($version);

	}//end latest()

	// --------------------------------------------------------------------

	/**
	 * Retrieves current schema version
	 *
	 * @access public
	 *
	 * @param string $type A string that represents the name of the module, or 'app_' for application migrations. If empty, it returns core migrations.
	 *
	 * @return int Current Schema version
	 */
	public function get_schema_version($type='')
	{
		if ($this->_check_migrations_column('type'))
		{
			// new schema table layout
			$type = empty($type) ? 'core' : $type;
			$row = $this->_ci->db->get_where('schema_version', array('type' => $type))->row();
			return isset($row->version) ? $row->version: 0;
		}
		else
		{
			$row = $this->_ci->db->get('schema_version')->row();

			$schema = $type .'version';

			return isset($row->$schema) ? $row->$schema : 0;
		}

	}//end latest()

	// --------------------------------------------------------------------

	/**
	 * Retrieves the latest available version.
	 *
	 * @param string $type A string that represents the name of the module, or 'app_' for application migrations. If empty, it returns core migrations.
	 *
	 * @return int Latest available migration file.
	 */
	public function get_latest_version($type='')
	{
		switch ($type)
		{
			case '':
				$migrations_path = $this->migrations_path .'core/';
				break;
			case 'app_':
				$migrations_path = $this->migrations_path;
				break;
			default:
				$migrations_path = module_path(substr($type, 0, -1), 'migrations') .'/';
				break;
		}

		$f = glob($migrations_path .'*_*'.EXT);

		return count($f);

	}//end get_latest_version()

	//--------------------------------------------------------------------

	/**
	 * Searches the migrations folder and returns a list of available migration files.
	 *
	 * @access public
	 *
	 * @param string $type A string that represents the name of the module, or 'app_' for application migrations. If empty, it returns core migrations.
	 *
	 * @return array An array of migration files
	 */
	public function get_available_versions($type='')
	{
		switch ($type)
		{
			case '':
				$migrations_path = $this->migrations_path .'core/';
				break;
			case 'app_':
				$migrations_path = $this->migrations_path;
				break;
			default:
				$migrations_path = module_path(substr($type, 0, -1), 'migrations') .'/';
				break;
		}

		$files = glob($migrations_path .'*_*'.EXT);

		for ($i=0; $i < count($files); $i++)
		{
			$files[$i] = str_ireplace($migrations_path, '', $files[$i]);
		}

		return $files;

	}//end get_available_versions()

	//--------------------------------------------------------------------

	/**
	 * Executes raw SQL migrations. Will manually break the commands on a ';' so
	 * that multiple commmands can be run at once. Very handy for using phpMyAdmin
	 * dumps.
	 *
	 * @access public
	 *
	 * @param string $sql A string with one or more SQL commands to be run.
	 *
	 * @return void
	 */
	public function do_sql_migration($sql='')
	{
		if (empty($sql))
		{
			return;
		}

		// Split the sql into usable commands on ';'
		$queries = explode(';', $sql);

		foreach ($queries as $q)
		{
			if (trim($q))
			{
				$this->_ci->db->query(trim($q));
			}
		}

	}//end do_sql_migration()

	//--------------------------------------------------------------------


	/**
	 * Stores the current schema version in the database.
	 *
	 * @access private
	 *
	 * @param int    $schema_version An integer with the latest Schema version reached
	 * @param string $type           A string that is appended with '_schema' to create the field name to store in the database.
	 *
	 * @return void
	 */
	private function _update_schema_version($schema_version, $type='')
	{
		logit('[Migrations] Schema '. $type.' updated to: '. $schema_version);

		if ($this->_check_migrations_column('type'))
		{
			// new schema table layout
			$type = empty($type) ? 'core' : $type;
			// If the row doesn't exist, create it...
			$query = $this->_ci->db->get_where('schema_version', array('type' => $type));

			if ($schema_version != 0)
			{
				if (!$query->num_rows())
				{
					$this->_ci->db->insert('schema_version', array(
						'type'        => $type,
						'version' => $schema_version,
					));

				}

				return $this->_ci->db->update('schema_version', array('version' => $schema_version), array('type' => $type));
			}
			elseif ($query->num_rows())
			{
				return $this->_ci->db->delete('schema_version', array('type' => $type));
			}
		}
		else
		{
			// If the row doesn't exist, create it...
			if (!$this->_check_migrations_column($type .'version'))
			{
				$this->_ci->load->dbforge();

				$this->_ci->dbforge->add_column('schema_version', array(
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

		}//end if

	}//end _update_schema_version()

	//--------------------------------------------------------------------

	/**
	 * Method to check if the DB table schema_version is in the new format or old
	 *
	 * @access private
	 *
	 * @param string $column_name Name of the column to check the existance of
	 *
	 * @return bool
	 */
	private function _check_migrations_column($column_name)
	{
		$row = $this->_ci->db->get('schema_version')->row();

		if (isset($row->$column_name))
		{
			return TRUE;
		}

		return FALSE;

	}//end _check_migrations_column()

	//--------------------------------------------------------------------

	/**
	 * Sets the base path that Migrations uses to find it's migrations
	 * to a user-supplied path. The path will be converted to a full
	 * system path (via realpath) and checked to make sure it's a folder.
	 *
	 * @access public
	 *
	 * @param string $path The path to set, relative to the front controller.
	 *
	 * @return void
	 */
	public function set_path($path=null)
	{
		if (empty($path))
		{
			return;
		}

		$path = realpath($path);

		if (is_dir($path))
		{
			$this->migrations_path = $path .'/';
		}

	}//end set_path()

	//--------------------------------------------------------------------

}//end Migrations

/* End of file Migrations.php */
/* Location: ./libraries/Migrations.php */
