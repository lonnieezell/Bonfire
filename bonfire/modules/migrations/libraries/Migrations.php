<?php defined('BASEPATH') || exit('No direct script access allowed');

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
 * :    class Migration_install_initial_schema extends Migration {
 * :
 * :        function up() {}
 * :
 * :        function down() {}
 * :    }
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
    abstract public function up();

    /**
     * Abstract method ran when decreasing the schema version.
     */
    abstract public function down();

    //--------------------------------------------------------------------

    /**
     * Getter method
     *
     * @param mixed $var
     *
     * @return object
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }
}
//end Migration interface

/**
 * Migrations Library
 *
 * @package Bonfire\Modules\Migrations\Libraries\Migrations
 * @author  Mat'as Montes
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/migrations
 */
class Migrations
{
    //--------------------------------------------------------------------------
    // Class constants
    //--------------------------------------------------------------------------

    /**
     * The prefix used for app migrations.
     */
    const APP_MIGRATION_PREFIX = 'app_';

    /**
     * Migration type used for core migrations.
     */
    const CORE_MIGRATIONS = 'core';

    /**
     * The maximum migrations schema version currently supported by the library.
     */
    const MAX_SCHEMA_VERSION = 3;

    //--------------------------------------------------------------------------
    // Public properties
    //--------------------------------------------------------------------------

    /**
     * @var string The most recent error message.
     *
     * @deprecated since 0.7.1. Use getErrorMessage().
     */
    public $error = '';

    //--------------------------------------------------------------------------
    // Protected properties
    //--------------------------------------------------------------------------

    /**
     * @var CI The CodeIgniter instance.
     */
    protected $_ci;

    /**
     * @var array Errors which have occurred during the current execution of the
     * library.
     */
    protected $errors = array();

    /**
     * @var string The name of the schema version/migrations table.
     */
    protected $migrationsTable = 'schema_version';

    //--------------------------------------------------------------------------
    // Private properties
    //--------------------------------------------------------------------------

    /**
     * @var string Path to the core migrations files.
     */
    private $coreMigrationsPath;

    /**
     * @var int The version of the schema version/migrations table. 0 indicates
     * the schema version is unknown, 1 indicates the "old" schema, 2 or 3
     * indicates the "new" schema. This is intended to prevent multiple calls to
     * the database to check the schema version.
     */
    private static $migrationsSchemaVersion = 0;

    /**
     * @var array Cache of the current versions.
     */
    private static $schemaVersion;

    /**
     * @var bool If true, show verbose messages.
     */
    private $verbose = false;

    //--------------------------------------------------------------------------
    // Public methods
    //--------------------------------------------------------------------------

    /**
     * Initialize the configuration settings
     *
     * @return void
     */
    public function __construct($params = array())
    {
        $this->_ci =& get_instance();

        $this->coreMigrationsPath = isset($params['migrations_path']) ?
            $params['migrations_path'] : (BFPATH . 'migrations');

        // Add trailing slash if not set
        if (substr($this->coreMigrationsPath, -1) != '/') {
            $this->coreMigrationsPath .= '/';
        }

        // Sanity check
        if (! is_dir($this->coreMigrationsPath)) {
            $this->setError('Migrations Library is loaded but the migrations path is configured incorrectly, or the configured path is not a valid directory.');
            show_error($this->getErrorMessage());
        }

        // If the table is missing, create it
        if (! $this->createMigrationsTable(self::MAX_SCHEMA_VERSION)) {
            show_error($this->getErrorMessage());
        }

        if (! is_array(self::$schemaVersion)) {
            self::$schemaVersion = array();
        }
    }

    /**
     * Handle auto-running core and/or app migrations on page load.
     *
     * @return void
     */
    public function autoLatest()
    {
        if ($this->_ci->config->item('migrate.auto_core')) {
            $this->install('');
        }

        if ($this->_ci->config->item('migrate.auto_app')) {
            $this->install(self::APP_MIGRATION_PREFIX);
        }
    }

    /**
     * Execute raw SQL migrations. Will manually break the commands on a ';' so
     * that multiple commmands can be executed in a single migration. Very handy
     * for using phpMyAdmin dumps.
     *
     * @param string $sql A string with one or more SQL commands to be run.
     *
     * @return void
     */
    public function doSqlMigration($sql = '')
    {
        if (empty($sql)) {
            return;
        }

        // Split the sql into usable commands on ';'
        $queries = explode(';', $sql);
        foreach ($queries as $q) {
            if (trim($q)) {
                if ($this->_ci->db->query(trim($q)) === false) {
                    return false;
                }
            }
        }
    }

    /**
     * Return a list of available migration files in the migrations folder.
     *
     * @param string $type A string that represents the name of the module, or
     * self::APP_MIGRATION_PREFIX for application migrations. If empty, it
     * returns core migrations.
     *
     * @return array An array of migration filenames.
     */
    public function getAvailableVersions($type = '')
    {
        // Get the migrations path for the given type
        $migrationsPath = $this->getMigrationsPath($type);

        // List all *_*.php files in the migrations path
        $files = glob("{$migrationsPath}*_*.php");
        for ($i = 0; $i < count($files); $i++) {
            // Remove path and extension
            $files[$i] = basename($files[$i], '.php');

            // Mark wrongly formatted files as FALSE for later filtering
            if (! preg_match('/^\d{3}_(\w+)$/', $files[$i])) {
                $files[$i] = false;
            }
        }

        $migrations = array_filter($files);
        sort($migrations);

        return $migrations;
    }

    /**
     * Get the most recent error message.
     *
     * @return string    The most recent error message.
     */
    public function getErrorMessage()
    {
        if (empty($this->errors)) {
            return '';
        }

        return end($this->errors);
    }

    /**
     * Get all of the errors.
     *
     * @param string $key   If set, returns only the error message associated
     * with the given key.
     *
     * @return array/string    An array of errors or the error message
     * associated with the given key.
     */
    public function getErrors($key = '')
    {
        if (! empty($key)) {
            return $this->errors[$key];
        }

        return $this->errors;
    }

    /**
     * Retrieve the module versions in a single DB call and set the cache.
     *
     * @return array    The module versions. Keys are the module names and
     * values are the versions. If the database query fails, returns an empty
     * array.
     */
    public function getModuleVersions()
    {
        $schemaVersion = $this->getLibraryVersion();
        $query = $this->_ci->db->get($this->migrationsTable);
        if (! $query->num_rows()) {
            $this->setError('Error retrieving module versions');
            return array();
        }

        if ($schemaVersion == 1) {
            $row = $query->row_array();
            // Get each column in the row and set the module versions...
            foreach (array_keys($row) as $key) {
                if ($key == 'version') {
                    continue;
                }
                $type = rtrim($key, 'version');
                self::$schemaVersion[$type] = $row[$key];
            }
        } else {
            foreach ($query->result() as $row) {
                self::$schemaVersion[$row->type] = $row->version;
            }
        }

        return self::$schemaVersion;
    }

    /**
     * Get the schema version from the cache. If a database query is required,
     * cache the result.
     *
     * @param string $type The type for which the version is requested
     *
     * @return int    The version
     */
    public function getVersion($type = '', $getLatest = false)
    {
        if ($getLatest) {
            return $this->getLatestVersion($type);
        }

        if (isset(self::$schemaVersion[$type])) {
            return self::$schemaVersion[$type];
        }

        if (empty($type) && isset(self::$schemaVersion[self::CORE_MIGRATIONS])) {
            return self::$schemaVersion[self::CORE_MIGRATIONS];
        }

        if ($this->getLibraryVersion() > 1) {
            // New schema table layout
            $type = empty($type) ? self::CORE_MIGRATIONS : $type;
            $row = $this->_ci->db->where('type', $type)
                                 ->get($this->migrationsTable)
                                 ->row();

            self::$schemaVersion[$type] = isset($row->version) ? $row->version: 0;

            return self::$schemaVersion[$type];
        }

        // Old schema table layout
        $row = $this->_ci->db->get($this->migrationsTable)
                             ->row();

        $schema = "{$type}version";

        self::$schemaVersion[$type] = isset($row->$schema) ? $row->$schema : 0;

        return self::$schemaVersion[$type];
    }

    /**
     * Install the migrations for the given type up to the latest version.
     *
     * @param string $type The name of the module or self::APP_MIGRATION_PREFIX
     * for application migrations. If empty, the core migrations will be
     * installed.
     *
     * @return int The version number for the given type after installing the
     * migrations or 0 on error.
     */
    public function install($type = '')
    {
        $latestVersion = $this->getLatestVersion($type);
        if ($latestVersion > 0) {
            return $this->version($latestVersion, $type);
        }

        $this->setError($this->_ci->lang->line('no_migrations_found'));
        return 0;
    }

    /**
     * Set whether there should be verbose output.
     *
     * @param bool $state True to enable verbose output, false to disable.
     *
     * @return void
     */
    public function setVerbose($state)
    {
        $this->verbose = (bool) $state;
    }

    /**
     * Migrate to a specific schema version.
     *
     * Calls each migration step required to get to the specified schema version.
     *
     * @param int    $version An int that is the target migration version.
     * @param string $type    A string which represents the name of the module,
     * or 'app_' for application migrations. If empty, applies core migrations.
     *
     * @return int/bool Returns 0 (int) if failed, true (bool) if already latest,
     * schema version (int) if upgraded.
     */
    public function version($version, $type = '')
    {
        // Reset the error state to prevent invalid errors when auto-migration
        // is enabled.
        $this->setError('');

        $schemaVersion = $this->getVersion($type);
        $start = $schemaVersion;
        $stop  = $version;

        // Get the migrations path for the given type
        $migrationsPath = $this->getMigrationsPath($type);

        if ($version > $schemaVersion) {
            // Moving Up
            $start++;
            $stop++;
            $step = 1;
            $method = 'up';
        } else {
            // Moving Down
            $step = -1;
            $method = 'down';
        }

        $migrations = array();

        // Prepare to actually DO the migrations
        // But first, make sure that everything is the way it should be
        for ($i = $start; $i != $stop; $i += $step) {
            // Get all files in the migrations path matching the current step
            $migrationFiles = glob(sprintf("{$migrationsPath}%03d_*.php", $i));

            /**
             * @internal Note: the section below previously checked
             * count($migrationFiles) > 1, then count($migrationFiles) == 0. If
             * glob() returned false (an error occurred), count($migrationFiles)
             * would be 1, because count(false) returns 1 (indicating the
             * parameter is not an array, does not implement Countable, and is
             * not null).
             *
             * Checking empty($migrationFiles) will catch both false and an
             * empty array. Doing it first prevents an error (though silently
             * ignored) when checking count($migrationFiles) > 1.
             */

            // Migration step not found, or other error retrieving $migrationFiles
            if (empty($migrationFiles)) {
                // If trying to migrate up to a version greater than the last
                // existing one, migrate to the last one.
                if ($step == 1) {
                    break;
                }

                // If trying to migrate down but a step is missing, something
                // must be wrong.
                $this->setError(sprintf($this->_ci->lang->line("migration_not_found"), $i));
                return 0;
            }

            // Only one migration per step is permitted
            if (count($migrationFiles) > 1) {
                $this->setError(sprintf($this->_ci->lang->line("multiple_migrations_version"), $i));
                return 0;
            }

            // Filename without the extension
            $name = basename($migrationFiles[0], '.php');

            // Filename validations
            if (preg_match('/^\d{3}_(\w+)$/', $name, $match)) {
                // Filename without the extension and without the migration
                // number prefix, converted to lowercase
                $match[1] = strtolower($match[1]);

                // Cannot repeat a migration at different steps
                if (isset($migrations[$match[1]])) {
                    $this->setError(sprintf($this->_ci->lang->line("multiple_migrations_name"), $match[1]));
                    return 0;
                }

                // Load the migration, verify the class loaded successfully
                include $migrationFiles[0];

                // Substitute Migration_ for the migration number and capitalize
                // the first letter of the filename
                $class = 'Migration_' . ucfirst($match[1]);
                if (! class_exists($class)) {
                    $this->setError(sprintf($this->_ci->lang->line("migration_class_doesnt_exist"), $class));
                    return 0;
                }

                // Verify the migration contains the required methods
                if (! is_callable(array($class, "up"))
                    || ! is_callable(array($class, "down"))
                ) {
                    $this->setError(sprintf($this->_ci->lang->line('wrong_migration_interface'), $class));
                    return 0;
                }

                // All validations passed, add the file to the list of migrations
                // to execute. Keep the $class as well, since it would otherwise
                // need to be regenerated before execution.
                $migrations[$match[1]] = $class;
            } else {
                // Filename was in the wrong format
                // Make sure the filename used in the error message is accurate
                $file = basename($migrationFiles[0]);
                $this->setError(sprintf($this->_ci->lang->line("invalid_migration_filename"), $file, $migrationsPath));
                return 0;
            }
        }

        // If there is nothing to do, quit
        if ($migrations === array()) {
            if ($this->verbose) {
                echo "Nothing to do, bye!\n";
            }

            return true;
        }

        if ($this->verbose) {
            // Ensure the version output reflects the migrations found, rather
            // than just parroting the requested version.
            $version = $i + ($step == 1 ? -1 : 0);
            echo "<p>Current schema version: {$schemaVersion}<br />";
            echo "Moving {$method} to version {$version}</p>";
            echo "<hr />";
        }

        // Loop through the migrations and execute them
        $dbForgeLoaded = false;
        foreach ($migrations as $filename => $class) {
            if ($this->verbose) {
                echo "{$filename}:<br />";
                echo "<blockquote>";
            }

            $obj = new $class;

            if ($obj->migration_type == 'forge') {
                if (! $dbForgeLoaded) {
                    $this->_ci->load->dbforge();
                }
                if (call_user_func(array($obj, $method)) === false) {
                    $this->setError("Error calling method '{$method}' in '{$filename}'");
                    if ($this->verbose) {
                        echo $this->getErrorMessage();
                        echo "</blockquote>";
                        echo "<hr />";
                    }
                    break;
                }
            } elseif ($obj->migration_type == 'sql') {
                $sql = $obj->$method();
                if ($this->doSqlMigration($sql) === false) {
                    $this->setError("Error executing SQL Migration for '{$method}' in '{$filename}'");
                    if ($this->verbose) {
                        echo $this->getErrorMessage();
                        echo "</blockquote>";
                        echo "<hr />";
                    }
                    break;
                }
            } else {
                $this->setError("Unsupported migration type '{$obj->migration_type}' in '{$filename}'");
                if ($this->verbose) {
                    echo $this->getErrorMessage();
                    echo "</blockquote>";
                    echo "<hr />";
                }
                break;
            }

            if ($this->verbose) {
                echo "</blockquote>";
                echo "<hr />";
            }

            $schemaVersion += $step;
        }

        // In case the migration is moving through multiple versions, there is
        // no need to update the version in the database on every iteration of
        // the loop, so just do it when the loop is finished (or breaks).
        $this->updateVersion($schemaVersion, $type);

        if ($this->verbose) {
            echo "<p>All done. Schema is at version {$schemaVersion}.</p>";
        }

        return $schemaVersion;
    }

    //--------------------------------------------------------------------------
    // Protected methods
    //--------------------------------------------------------------------------

    /**
     * Get the migrations path for a given migration type.
     *
     * @param string $type The name of the module for module migrations.
     * self::APP_MIGRATION_PREFIX for application migrations. Empty for core
     * migrations.
     *
     * @return string    The migrations path for the given migration type.
     */
    protected function getMigrationsPath($type = '')
    {
        switch ($type) {
            // Core migrations
            case '':
            case self::CORE_MIGRATIONS:
                return $this->coreMigrationsPath;

            // Application migrations
            case self::APP_MIGRATION_PREFIX:
                return APPPATH . 'db/migrations/';

            // If it is not a core migration or application migration, it should
            // be the name of a module.
            default:
                return Modules::path(substr($type, 0, -1), 'migrations') . '/';
        }
    }

    /**
     * Set an error message.
     *
     * @param string $value The error message.
     * @param string $key   A key to reference the error message (optional).
     *
     * @return void
     */
    protected function setError($value, $key = '')
    {
        if (empty($key)) {
            $this->errors[] = $value;
        } else {
            $this->errors[$key] = $value;
        }

        // To maintain compatibility until the $error property is removed...
        $this->error = $value;
    }

    //--------------------------------------------------------------------------
    // Private methods
    //--------------------------------------------------------------------------

    /**
     * Check for the existence of a particular column. Primarily used for the
     * "old" schema.
     *
     * @param string $columnName Name of the column to find in the table.
     *
     * @return bool True if the column exists, else false.
     */
    private function checkMigrationsColumn($columnName)
    {
        $row = $this->_ci->db->get($this->migrationsTable)->row();
        return isset($row->$columnName);
    }

    /**
     * Create the migrations table.
     *
     * @param int $schemaVersion The version of the schema for which the table
     * will be created.
     *
     * @return bool    True if the table was created successfully or already
     * exists. False if an error occurred.
     */
    private function createMigrationsTable($schemaVersion)
    {
        // If the table already exists, there's nothing to do here
        if ($this->_ci->db->table_exists($this->migrationsTable)) {
            return true;
        }

        // Initialize the variables used to define the table
        $data       = array();
        $fields     = array();
        $primaryKey = '';

        switch ($schemaVersion) {
            // Not a significant version difference, but at least it matches the
            // definition in /bonfire/migrations/023_Modify_schema_version_type.php
            case 3:
                $fields = array(
                    'type' => array(
                        'type'       => 'varchar',
                        'constraint' => 40,
                        'null'       => false,
                    ),
                    'version' => array(
                        'type'       => 'int',
                        'constraint' => 4,
                        'default'    => 0,
                    ),
                );
                $primaryKey = 'type';
                $data = array(
                    'type'    => self::CORE_MIGRATIONS,
                    'version' => 0,
                );
                break;

            // The "new" schema
            case 2:
                $fields = array(
                    'type' => array(
                        'type'       => 'varchar',
                        'constraint' => 20,
                        'null'       => false,
                    ),
                    'version' => array(
                        'type'       => 'int',
                        'constraint' => 4,
                        'default'    => 0,
                    ),
                );
                $primaryKey = 'type';
                $data = array(
                    'type'    => self::CORE_MIGRATIONS,
                    'version' => 0,
                );
                break;

            // The "old" schema
            case 1:
                $fields = array(
                    'version' => array(
                        'type'       => 'int',
                        'constraint' => 4,
                        'default'    => 0,
                        'null'       => false,
                    ),
                    'app_version' => array(
                        'type'       => 'int',
                        'constraint' => 4,
                        'default'    => 0,
                        'null'       => false,
                    ),
                );

                // This isn't really a primary key, but it doesn't really
                // matter, as this is a 1-row table
                $primaryKey = 'version';
                $data = array(
                    'version'     => 0,
                    'app_version' => 0,
                );
                break;
        }

        // If $fields and $primaryKey aren't set, the table won't be created.
        if (empty($fields) || empty($primaryKey)) {
            $this->setError('Invalid schema selected for creation of migrations table.');
            return false;
        }

        // Load DBForge and create the table
        $this->_ci->load->dbforge();
        $this->_ci->dbforge->add_field($fields);
        $this->_ci->dbforge->add_key($primaryKey, true);
        $this->_ci->dbforge->create_table($this->migrationsTable, true);

        // If $data has been set, insert it into the table
        if (! empty($data)) {
            $this->_ci->db->insert($this->migrationsTable, $data);
        }

        // Set the schema version for reference later
        self::$migrationsSchemaVersion = $schemaVersion;
        return true;
    }

    /**
     * Retrieve the latest available version.
     *
     * @param string $type A string that represents the name of the module, or
     * 'app_' for application migrations. If empty, it returns core migrations.
     *
     * @return int Version number of the latest available migration file.
     */
    private function getLatestVersion($type = '')
    {
        $migrations = $this->getAvailableVersions($type);
        if (empty($migrations)) {
            return 0;
        }

        $lastMigration = end($migrations);
        if ($lastMigration === false) {
            return 0;
        }

        $lastVersion = substr($lastMigration, 0, 3);

        return intval($lastVersion, 10);
    }

    /**
     * Determine which version of the database table for migrations is currently
     * in use.
     *
     * This function does not currently help in determining the constraint on
     * the 'type' column (the difference between versions 2 and 3 is whether the
     * constraint is 20 or 40), as the db drivers don't currently return this
     * information for all databases.
     *
     * @return int    A number indicating the version of the table in use, or 0
     * if the version could not be determined. 1 is returned for the "old"
     * version, 3 is returned for the "new" version.
     */
    private function getLibraryVersion()
    {
        if (self::$migrationsSchemaVersion) {
            return self::$migrationsSchemaVersion;
        }

        $row = $this->_ci->db->get($this->migrationsTable, 1)->row();

        // Given no definite way to check between versions 2 and 3, we'll assume
        // version 3 if the type column is available
        if (isset($row->type)) {
            self::$migrationsSchemaVersion = self::MAX_SCHEMA_VERSION;
        } elseif (isset($row->app_version)) {
            // If the type column is unavailable, check for the app_version column
            self::$migrationsSchemaVersion = 1;
        } else {
            // If neither column is available, who knows?
            self::$migrationsSchemaVersion = 0;
        }

        return self::$migrationsSchemaVersion;
    }

    /**
     * Store the current schema version in the database.
     *
     * @param int    $version   An integer with the latest Schema version
     * reached
     * @param string $type      A string that is appended with '_schema' to
     * create the field name to store in the database.
     *
     * @return void
     */
    private function updateVersion($version, $type = '')
    {
        logit("[Migrations] Schema {$type} updated to: {$version}");
        if ($this->getLibraryVersion() > 1) {
            // New schema table layout
            $type = empty($type) ? self::CORE_MIGRATIONS : $type;

            // Get the current version for this type
            $currentVersion = $this->getVersion($type);

            // Remove the row for this type when moving down to 0
            if ($version == 0) {
                if (empty($currentVersion)) {
                    // If the version was not found, just exit
                    return;
                }

                // If the version was found, remove it
                $result = $this->_ci->db->delete(
                    $this->migrationsTable,
                    array('type' => $type)
                );

                // Cache the version
                if ($result) {
                    self::$schemaVersion[$type] = $version;
                }

                return $result;
            }

            // When moving to a version other than 0...
            // If the version was not found, insert it
            if (empty($currentVersion) && $currentVersion !== '0') {
                $result = $this->_ci->db->insert(
                    $this->migrationsTable,
                    array(
                        'type'    => $type,
                        'version' => $version,
                    )
                );
            } else {
                // If the version was found, update it
                $result = $this->_ci->db->update(
                    $this->migrationsTable,
                    array('version' => $version),
                    array('type'    => $type)
                );
            }

            // Cache the version
            if ($result) {
                self::$schemaVersion[$type] = $version;
            }

            return $result;
        }

        // The old schema...
        // Does the column exist?
        if (! $this->checkMigrationsColumn("{$type}version")) {
            // If the column doesn't exist, create it...
            $this->_ci->load->dbforge();
            $this->_ci->dbforge->add_column(
                $this->migrationsTable,
                array(
                    "{$type}version" => array(
                        'type'       => 'int',
                        'constraint' => 4,
                        'null'       => true,
                        'default'    => 0,
                    )
                )
            );
        }

        // Update the version in the column
        $result = $this->_ci->db->update(
            $this->migrationsTable,
            array("{$type}version" => $version)
        );

        // Cache the version
        if ($result) {
            self::$schemaVersion[$type] = $version;
        }

        return $result;
    }

    //--------------------------------------------------------------------------
    // Deprecated Methods
    //--------------------------------------------------------------------------

    /**
     * Handle auto-upgrading migrations of core and/or app on page load.
     *
     * @deprecated since 0.7.1. Use autoLatest().
     *
     * @return void
     */
    public function auto_latest()
    {
        $this->autoLatest();
    }

    /**
     * Execute raw SQL migrations. Will manually break the commands on a ';' so
     * that multiple commmands can be run at once. Very handy for using
     * phpMyAdmin dumps.
     *
     * @deprecated since 0.7.1. Use doSqlMigration().
     *
     * @param string $sql A string with one or more SQL commands to be run.
     *
     * @return void
     */
    public function do_sql_migration($sql = '')
    {
        return $this->doSqlMigration($sql);
    }

    /**
     * Return a list of available migration files in the migrations folder.
     *
     * @deprecated since 0.7.1. Use getAvailableVersions().
     *
     * @param string $type A string that represents the name of the module, or
     * 'app_' for application migrations. If empty, it returns core migrations.
     *
     * @return array An array of migration files
     */
    public function get_available_versions($type = '')
    {
        return $this->getAvailableVersions($type);
    }

    /**
     * Retrieve the latest available version.
     *
     * @deprecated since 0.7.1. Use getVersion($type, true).
     *
     * @param string $type A string that represents the name of the module, or
     * 'app_' for application migrations. If empty, it returns core migrations.
     *
     * @return int Latest available migration file.
     */
    public function get_latest_version($type = '')
    {
        return $this->getLatestVersion($type);
    }

    /**
     * Retrieve current schema version.
     *
     * @deprecated since 0.7.1.
     *
     * @param string $type A string that represents the name of the module, or
     * 'app_' for application migrations. If empty, it returns core migrations.
     *
     * @return int Current Schema version
     */
    public function get_schema_version($type = '')
    {
        return $this->getVersion($type);
    }

    /**
     * Set whether there should be verbose output.
     *
     * @deprecated since 0.7.1 use setVerbose().
     *
     * @param bool $state True to enable verbose output, false to disable.
     */
    public function set_verbose($state)
    {
        $this->setVerbose($state);
    }
}
/* End /modules/migrations/libraries/Migrations.php */
