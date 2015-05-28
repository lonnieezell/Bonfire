<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Settings Library
 *
 * Provides methods to retrieve settings from the database and/or config files,
 * and update settings in the database.
 *
 * @todo sort out the handling of module-specific settings, especially in the cache.
 *
 * @todo If someone retrieves a config value with item(), then attempts to change
 * the value with set(), it will attempt to update the value in the database...
 *
 * @package    Bonfire\Modules\Settings\Libraries\Settings_lib
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/developer/settings#settings_lib
 */
class Settings_lib
{
    /** @var object A pointer to the CodeIgniter instance. */
    protected static $ci;

    /** @var array Settings cache. */
    private static $cache = array();

    /** @var array Error messages. */
    protected static $errors = array();

    /** @var array Hold observers to handle settings drivers */
    private static $observers = array();

    private static $settingsModelLoaded = false;

    /**
     * This constructor facilitates loading through CI.
     *
     * @return void
     */
    public function __construct()
    {
        self::init();
        self::find_all();
    }

    /**
     * Initialize the library.
     *
     * @return void
     */
    public static function init()
    {
        if (! is_object(self::$ci)) {
            self::$ci =& get_instance();
        }

        if (! class_exists('settings_model') && isset(self::$ci->load)) {
            self::$ci->load->model('settings/settings_model');
        }
        if (! self::$settingsModelLoaded
            && class_exists('settings_model')
            && isset(self::$ci->db)
            && ! empty(self::$ci->db->database)
            && self::$ci->db->table_exists(self::$ci->settings_model->get_table())
        ) {
            self::$settingsModelLoaded = true;
        }
    }

    /**
     * Get the requested settings value.
     *
     * @param string $name The name of the setting record to retrieve.
     *
     * @return mixed
     */
    public function __get($name)
    {
        return self::get($name);
    }

    /**
     * Set the requested setting value.
     *
     * @param string $name  The name of the setting.
     * @param string $value The value to save.
     *
     * @return bool
     */
    public function __set($name, $value)
    {
        return self::set($name, $value);
    }

    /**
     * Get an error message previously set by the library.
     *
     * @param  integer|null $index The index of the error message to retrieve.
     *
     * @return string|boolean Returns the message at the given index. If no index
     * was provided or no error message was found with the given index, returns
     * the last error message. If no errors have been set, returns false.
     */
    public static function getError($index = null)
    {
        if (is_null($index)
            || ! isset(self::$errors[$index])
        ) {
            return end(self::$errors);
        }

        return self::$errors[$index];
    }

    /**
     * Get the error messages previously set by the library.
     *
     * @return array An array of error messages, or an empty array.
     */
    public static function getErrors()
    {
        return self::$errors;
    }

    /**
     * Retrieve a setting.
     *
     * @param string $name The name of the item to retrieve.
     *
     * @return bool
     */
    public static function item($name)
    {
        if (isset(self::$cache[$name])) {
            return self::$cache[$name];
        }

        self::init();

        $value = null;
        if (self::$settingsModelLoaded) {
            $setting = self::$ci->settings_model->find_by('name', $name);
            if ($setting) {
                $value = $setting->value;
            }
        }

        // Setting doesn't exist, maybe it's a config option.
        if (is_null($value)) {
            $value = config_item($name);
        }

        // Store it for later.
        self::$cache[$name] = $value;

        return $value;
    }

    /**
     * Set a setting in the database.
     *
     * @param string $name   Name of the setting.
     * @param string $value  Value of the setting.
     * @param string $module Name of the module.
     *
     * @return boolean
     */
    public static function set($name, $value, $module = 'core')
    {
        if (! self::$settingsModelLoaded) {
            self::init();

            if (! self::$settingsModelLoaded) {
                self::$errors[] = 'Settings Model could not be loaded';
                return false;
            }
        }

        // If the value is cached and found in the database, update the database.
        if (isset(self::$cache[$name])
            && self::$ci->settings_model->find_by('name', $name)
        ) {
            $setting = self::$ci->settings_model->update_where('name', $name, array('value' => $value));
        } else {
            // Otherwise, insert the data.
            $setting = self::$ci->settings_model->insert(
                array(
                    'name'   => $name,
                    'value'  => $value,
                    'module' => $module,
                )
            );
        }

        self::$cache[$name] = $value;

        return true;
    }

    /**
     * Delete a setting in the database.
     *
     * @param string $name   Name of the setting.
     * @param string $module Name of the module.
     *
     * @return boolean True if the setting was deleted successfully, else false.
     */
    public static function delete($name, $module = 'core')
    {
        if (! self::$settingsModelLoaded) {
            self::init();

            if (! self::$settingsModelLoaded) {
                self::$errors[] = 'Settings Model could not be loaded.';
                return false;
            }
        }

        if (! isset(self::$cache[$name])) {
            self::$errors[] = 'Error deleting setting: setting not found in cache.';
            return false;
        }

        if (self::$ci->settings_model->delete_where(array('name' => $name, 'module' => $module))) {
            unset(self::$cache[$name]);

            return true;
        }

        self::$errors[] = empty(self::$ci->settings_model->error) ? 'Error deleting setting from the database.' : self::$ci->settings_model->error;

        return false;
    }

    /**
     * Get all of the settings.
     *
     * @return array|null
     */
    public static function find_all()
    {
        if (! self::$settingsModelLoaded) {
            self::init();

            if (! self::$settingsModelLoaded) {
                self::$errors[] = 'Settings Model could not be loaded.';
                return null;
            }
        }

        if (self::$cache) {
            return self::$cache;
        }

        $settings = self::$ci->settings_model->find_all();
        foreach ($settings as $setting) {
            self::$cache[$setting->name] = $setting->value;
        }

        return self::$cache;
    }

    /**
     * Get a setting for specific search criteria.
     *
     * @see find_all_by for multiple matches.
     *
     * @param string $field Setting column name.
     * @param string $value Value ot match.
     *
     * @return array|null
     */
    public static function find_by($field = null, $value = null)
    {
        if (! self::$settingsModelLoaded) {
            self::init();

            if (! self::$settingsModelLoaded) {
                self::$errors[] = 'Settings Model could not be loaded.';
                return null;
            }
        }

        $settings = self::$ci->settings_model->find_by($field, $value);
        foreach ($settings as $setting) {
            self::$cache[$setting['name']] = $setting['value'];
        }

        return $settings;
    }

    /**
     * Get all of the settings based on search criteria.
     *
     * @see find_by for a single setting match.
     *
     * @param string $field Setting column name.
     * @param string $value Value ot match.
     *
     * @return array|null
     */
    public static function find_all_by($field = null, $value = null)
    {
        if (! self::$settingsModelLoaded) {
            self::init();

            if (! self::$settingsModelLoaded) {
                self::$errors[] = 'Settings Model could not be loaded.';
                return null;
            }
        }

        $settings = self::$ci->settings_model->find_all_by($field, $value);
        if (! empty($settings) && is_array($settings)) {
            foreach ($settings as $key => $value) {
                self::$cache[$key] = $value;
            }
        }

        return $settings;
    }

    /**
     * Perform a batch update of settings in the database. Inserts any data not
     * already in the database.
     *
     * @param  array $data The settings to update in the database.
     *
     * @return boolean True on success, false on failure.
     */
    public static function update_batch($data)
    {
        if (! self::$settingsModelLoaded) {
            self::init();

            if (! self::$settingsModelLoaded) {
                self::$errors[] = 'Settings Model could not be loaded';
                return false;
            }
        }

        $index = 'name';
        $internalCache = array();
        $settings = self::$ci->settings_model->find_all();
        foreach ($settings as $setting) {
            $internalCache[$setting->name] = $setting->value;
        }

        $updateData = array();
        $insertData = array();
        foreach ($data as $record) {
            if (isset($internalCache[$record[$index]])) {
                $updateData[] = $record;
            } else {
                if (! isset($record['module'])) {
                    $record['module'] = 'core';
                }
                $insertData[] = $record;
            }
            self::$cache[$record[$index]] = $record['value'];
        }

        $result = false;
        if (! empty($updateData)) {
            $result = self::$ci->settings_model->update_batch($updateData, $index);
            if (! $result && self::$ci->settings_model->error) {
                self::$errors[] = self::$ci->settings_model->error;
            }
        }
        if (! empty($insertData)) {
            $result = self::$ci->settings_model->insert_batch($insertData);
            if (! $result && self::$ci->settings_model->error) {
                self::$errors[] = self::$ci->settings_model->error;
            }
        }

        return $result;
    }

    // -------------------------------------------------------------------------
    // Add/Remove Observers
    // -------------------------------------------------------------------------

    public function attach($observer)
    {
        self::$observers[] = $observer;
    }

    public function detach($observer)
    {
        $remainingObservers = array();
        foreach (self::$observers as $key => $val) {
            if ($val === $observer) {
                // Remove all values from the cache which are attached to this
                // observer.

                continue;
            }

            $remainingObservers[] = $val;
        }
        self::$observers = $remainingObservers;
    }
}

// -----------------------------------------------------------------------------
// ! HELPER METHOD
// -----------------------------------------------------------------------------

if (! function_exists('settings_item')) {
    /**
     * Helper method to retrieve a setting.
     *
     * @param string $name The name of the item to retrieve
     *
     * @return boolean|string Returns result of setting or false if none.
     */
    function settings_item($name = null)
    {
        if ($name === null) {
            return false;
        }

        return Settings_lib::item($name);
    }
}
